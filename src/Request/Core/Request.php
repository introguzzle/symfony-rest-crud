<?php

namespace App\Request\Core;

use App\Entity\User;
use App\Log\Log;
use App\Other\Constraint\Assert\Core\Constraint;
use App\Other\Constraint\ConstraintResolver;
use App\Other\Constraint\Core\Group;
use App\Other\Constraint\Errors;
use App\Other\Constraint\Group\DefinitionGroup;
use App\Other\Constraint\Group\InitialGroup;
use App\Other\Payload;
use App\Other\Reflector;
use App\Other\RestResponse;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Stringable;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouterInterface;
use Throwable;

abstract class Request implements Stringable
{
    protected SymfonyRequest $request;
    protected JWTTokenManagerInterface $tokenManager;
    protected UserRepository $userRepository;
    protected RouterInterface $router;
    protected EntityManagerInterface $em;

    private bool $useConstraints = true;
    private bool $validatePath = true;
    private bool $guard = true;


    public function __construct(
        RequestStack $requestStack,
        JWTTokenManagerInterface $tokenManager,
        UserRepository $userRepository,
        RouterInterface $router,
        EntityManagerInterface $em,
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->tokenManager = $tokenManager;
        $this->userRepository = $userRepository;
        $this->router = $router;
        $this->em = $em;
    }

    public function get(
        string $key,
        mixed $default = null
    ): mixed
    {
        return $this->request->getPayload()->get($key, $default);
    }

    public function getRequest(): SymfonyRequest
    {
        return $this->request;
    }

    public function getBody(): InputBag
    {
        return $this->request->request;
    }

    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    public function getTokenPayload(): ?Payload
    {
        try {
            return Payload::fromArray($this->tokenManager->parse($this->getToken()));
        } catch (JWTDecodeFailureException $e) {
            return (($payload = $e->getPayload()) === null)
                ? null
                : Payload::fromArray($payload)->setExpired(true);

        } catch (Throwable $e) {
            Log::log($e);
            return null;
        }
    }

    public function getToken(): string
    {
        return str_replace('Bearer ', '', $this->getAuthorizationHeader());
    }

    public function all(): array
    {
        return $this->request->request->all();
    }

    public function query(): array
    {
        return $this->request->query->all();
    }

    /**
     * @return array<string, string|Constraint|Constraint[]>
     */
    abstract public function getValidationProperties(): array;

    public function constrain(): Errors
    {
        $errors = new Errors();

        foreach ($this->getValidationProperties() as $propertyName => $constraints) {
            $propertyValue = $this->get($propertyName);

            if ($constraints instanceof Constraint) {
                $constraints->add($propertyValue, $errors, $propertyName);
                continue;
            }

            if (is_string($constraints)) {
                $group = new InitialGroup($constraints);

                foreach ($group->split(':') as $constraint) {
                    $result = $this->resolveConstraint($constraint)->add(
                        $propertyValue,
                        $errors,
                        $propertyName
                    );

                    if (!$result) {
                        // Break from inner loop
                        goto in;
                    }
                }
            }

            if (is_array($constraints)) {
                foreach ($constraints as $constraint) {
                    if ($constraint instanceof Constraint) {
                        $constraint->add($propertyValue, $errors, $propertyName);
                    }

                    if (is_string($constraint)) {
                        $result = $this->resolveConstraint(new DefinitionGroup($constraint, ':'))->add(
                            $propertyValue,
                            $errors,
                            $propertyName
                        );

                        if (!$result) {
                            // Break from inner loop
                            goto in;
                        }
                    }
                }
            }

            // Imitate breaking from inner loops that appear before
            in:
        }

        return $errors;
    }

    public function validate(): void
    {
        if ($this->useConstraints) {
            $errors = $this->constrain();

            if ($errors->count() > 0) {
                RestResponse::badRequest($errors)->throw();
            }
        }
    }

    public function guard(): void
    {
        if ($this->guard) {
            if ($this->getAuthorizationHeader() === null) {
                RestResponse::unauthorized('Missing token')->throw();
            }

            $payload = $this->getTokenPayload();
            $now = (new DateTime())->getTimestamp();

            if ($payload === null) {
                RestResponse::unauthorized()->throw();
            }

            if ($payload->isExpired()) {
                RestResponse::expired()->throw();
            }

            if ($now >= $payload->expiresAt->getTimestamp() || $now <= $payload->issuedAt->getTimestamp()) {
                RestResponse::expired()->throw();
            }
        }
    }

    protected function resolveConstraint(Group $group): Constraint
    {
        return (new ConstraintResolver($this, $group))->resolve();
    }

    public function retrieveUser(): ?User
    {
        return $this->userRepository->findOneBy([
            'name' => $this->getTokenPayload()->username
        ]);
    }

    public function match(): void
    {
        if ($this->validatePath) {
            try {
                $this->router->getMatcher()->match($this->getRequest()->getPathInfo());
            } catch (ResourceNotFoundException) {
                RestResponse::notFound()->send();
                exit;
            }
        }
    }

    public function setGuard(bool $guard): static
    {
        $this->guard = $guard;
        return $this;
    }

    public function setUseConstraints(bool $useConstraints): static
    {
        $this->useConstraints = $useConstraints;
        return $this;
    }

    public function setValidatePath(bool $validatePath): static
    {
        $this->validatePath = $validatePath;
        return $this;
    }

    public function __toString(): string
    {
        return Reflector::reflect($this);
    }

    public function getAuthorizationHeader(): ?string
    {
        return $this->getHeaders()->get('Authorization');
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->em;
    }

    public function getTokenManager(): JWTTokenManagerInterface
    {
        return $this->tokenManager;
    }

    /**
     * @return HeaderBag
     */
    public function getHeaders(): HeaderBag
    {
        return $this->request->headers;
    }
}