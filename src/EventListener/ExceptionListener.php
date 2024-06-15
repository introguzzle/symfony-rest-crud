<?php

namespace App\EventListener;

use App\Log\Log;
use App\Other\RestResponse;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class ExceptionListener
{
    #[AsEventListener(event: KernelEvents::EXCEPTION, priority: 1)]
    public function onKernelRequest(ExceptionEvent $event): void
    {
        if (($throwable = $event->getThrowable()) instanceof NotFoundHttpException) {
            return;
        }

        Log::log($throwable);
        $event->setResponse(RestResponse::internal($throwable));
    }
}