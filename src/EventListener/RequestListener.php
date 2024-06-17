<?php

namespace App\EventListener;

use App\Log\Log;
use App\Other\Constraint\Core\Resolver;
use App\Request\Core\Request;
use App\Response\RestResponse;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

use ReflectionClass;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouterInterface;

final class RequestListener
{
    private JWTTokenManagerInterface $tokenManager;
    private RouterInterface $router;
    private RequestStack $requestStack;
    private EntityManagerInterface $em;
    private Resolver $resolver;

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


    #[AsEventListener(event: KernelEvents::REQUEST)]
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $pathInfo = $request->getPathInfo();

        try {
            $parameters = $this->router->match($pathInfo);
            $controller = $parameters['_controller'];

            [$controllerClass, $method] = explode('::', $controller);

            $reflection = new ReflectionClass($controllerClass);
            $methodReflection = $reflection->getMethod($method);
            $parameters = $methodReflection->getParameters();

            foreach ($parameters as $parameter) {
                $parameterType = $parameter->getType();
                if ($parameterType && is_subclass_of($parameterType->getName(), Request::class)) {
                    $requestClass = $parameterType->getName();
                    break;
                }
            }

            if (isset($requestClass)) {
                /**
                 * @var Request $customRequest
                 */
                $customRequest = new $requestClass(
                    $this->requestStack,
                    $this->tokenManager,
                    $this->router,
                    $this->em,
                    $this->resolver
                );

                $customRequest->guard();
                $customRequest->validate();
                $customRequest->match();
            }

        } catch (Exception $e) {
            Log::log($e);

            if ($e instanceof NotFoundHttpException || $e instanceof ResourceNotFoundException) {
                $event->setResponse(RestResponse::notFound());
                return;
            }

            $event->setResponse(RestResponse::internal($e));
        }
    }
}
