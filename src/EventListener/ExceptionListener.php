<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;


class ExceptionListener
{

    /**
     * onKernelException function
     *
     * @param ExceptionEvent $event
     * @return void
     */


    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
       
        $response = new Response();


        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
          //  ->setContent(json_encode(['message'=>'Internal error']));
        }

        $event->setResponse($response);
    }
}
