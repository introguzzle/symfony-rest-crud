<?php

namespace App\Request\Core;

use App\Entity\User;
use App\Log\Log;
use App\Other\Constraint\Assert\Core\Constraint;
use App\Other\Constraint\Core\Group;
use App\Other\Constraint\Core\Resolver;
use App\Other\Constraint\Core\Validator;
use App\Other\Constraint\Group\MessageGroup;
use App\Other\Constraint\Validation\RequestValidator;
use App\Other\Constraint\Violation\Violations;
use App\Other\Payload;
use App\Other\Reflector;
use App\Repository\UserRepository;
use App\Response\RestResponse;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
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
    /**
     * @var EntityRepository<User>|UserRepository
     */
    protected EntityRepository|UserRepository $userRepository;
    protected RouterInterface $router;
    protected EntityManagerInterface $em;
    protected Resolver $resolver;
    protected ?Validator $validator;
    protected Violations $violations;

    private bool $useConstraints = true;
    private bool $validatePath = true;
    private bool $guard = true;
    private bool $throw = true;

    public function __construct(
        RequestStack $requestStack,
        JWTTokenManagerInterface $tokenManager,
        RouterInterface $router,
        EntityManagerInterface $em,
        Resolver $resolver,
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->tokenManager = $tokenManager;
        $this->router = $router;
        $this->em = $em;
        $this->userRepository = $this->em->getRepository(User::class);
        $this->resolver = $resolver;
    }

    public function get(
        string $key,
        mixed $default = null
    ): mixed
    {
        return $this->request->get($key, $default) ?? $this->request->getPayload()->get($key, $default);
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

    public function queryBag(): InputBag
    {
        return $this->request->query;
    }

    public function headers(): array
    {
        return $this->request->headers->all();
    }

    public function headerBag(): HeaderBag
    {
        return $this->request->headers;
    }

    /**
     * @return array<string, string|Constraint|Constraint[]>
     */
    abstract public function getValidationProperties(): array;

    /**
     * @return array<string, string>
     */
    public function getMessages(): array
    {
        return [];
    }

    public function constrain(): Violations
    {
        return ($this->getValidator())->validate();
    }

    public function validate(): void
    {
        $this->prepare();

        if ($this->useConstraints) {
            $this->violations = $this->constrain();
            $this->setMessages();

            if ($this->throw && $this->violations->hasAny()) {
                RestResponse::badRequest($this->violations->toArray())->throw();
            }
        }

        $this->after();
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
        return $this->resolver->resolve($group);
    }

    public function prepare(): void
    {

    }

    public function after(): void
    {

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
                RestResponse::notFound()->throw();
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
        return $this->headerBag()->get('Authorization');
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->em;
    }

    public function getTokenManager(): JWTTokenManagerInterface
    {
        return $this->tokenManager;
    }

    public function getValidator(): Validator
    {
        if (!isset($this->validator)) {
            $this->validator = new RequestValidator($this, $this->resolver);
        }

        return $this->validator;
    }

    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;
        return $this;
    }

    public function setThrow(bool $throw): static
    {
        $this->throw = $throw;
        return $this;
    }

    public function passedValidation(): bool
    {
        if (isset($this->violations)) {
            return $this->violations->hasNone();
        }

        return true;
    }

    public function setMessages(array $messages = []): static
    {
        $set = function (array $messages): void {
            foreach ($messages as $key => $message) {
                $group = new MessageGroup($key);
                [$name, $violated] = $group->divide();

                $message = $this->formatMessage($name, $message);
                $this->violations->set($name, $violated, $message);
            }
        };

        $set($this->getMessages());
        $set($messages);

        return $this;
    }

    /**
     * @return array<string, string>
     */

    public function messageProperties(): array
    {
        return [];
    }

    protected function formatMessage(string $name, string $message): string
    {
        $property = $this->messageProperties()[$name] ?? $name;
        return str_replace(':property', $property, $message);
    }
}