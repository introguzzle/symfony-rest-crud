<?php

namespace App\EventListener;

use App\Entity\Entity;
use App\Other\Constraint\Core\Resolver;
use App\Other\EntityConverter;
use App\Other\Route;
use App\Request\Core\Request;
use App\Response\RestResponse;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

use ReflectionClass;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouterInterface;

final class RequestListener
{
    public RequestStack $requestStack;
    public JWTTokenManagerInterface $tokenManager;
    public RouterInterface $router;
    public EntityManagerInterface $em;
    public Resolver $resolver;

    public string $controllerClass;
    public string $controllerClassMethod;
    public string $requestClass;

    public function __construct(
        RequestStack             $requestStack,
        JWTTokenManagerInterface $tokenManager,
        RouterInterface          $router,
        EntityManagerInterface   $em,
        Resolver                 $resolver,
    )
    {
        $this->requestStack = $requestStack;
        $this->tokenManager = $tokenManager;
        $this->router = $router;
        $this->em = $em;
        $this->resolver = $resolver;
    }


    public function boot(): void
    {
        Entity::setConverter(new EntityConverter());
    }

    public function match(SymfonyRequest $request): Route
    {
        $array = $this->router->match($request->getPathInfo());
        return new Route($array['_route'], $array['_controller']);
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $reflection = new ReflectionClass($this->controllerClass);
        $parameters = $reflection->getMethod($this->controllerClassMethod)->getParameters();

        foreach ($parameters as $parameter) {
            $parameterType = $parameter->getType();

            if ($parameterType && is_subclass_of($this->requestClass = $parameterType->getName(), Request::class)) {
                $request = $this->buildRequest();

                $request->guard();
                $request->validate();
                $request->match();
                break;
            }
        }
    }


    public function buildRequest(): Request
    {
        return new ($this->requestClass)(...$this->getProperties());
    }

    #[AsEventListener(event: KernelEvents::REQUEST)]
    public function onKernelRequest(RequestEvent $event): void
    {
        $this->boot();

        try {
            $route = $this->match($event->getRequest());

            $this->controllerClass = $route->getClass();
            $this->controllerClassMethod = $route->getClassMethod();

            $this->handle();

        } catch (Exception $e) {
            if ($e instanceof NotFoundHttpException || $e instanceof ResourceNotFoundException) {
                $event->setResponse(RestResponse::notFound());
                return;
            }

            $event->setResponse(RestResponse::internal($e));
        }
    }

    public function getProperties(): array
    {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties();

        $arguments = [];

        foreach ($properties as $property) {
            $arguments[] = $property->getValue($this);
        }

        return $arguments;
    }
}
