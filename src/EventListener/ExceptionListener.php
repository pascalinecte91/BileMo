<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use OpenApi\Annotations\Response as AnnotationsResponse;
use OpenApi\Annotations as OA;
use App\Exception\ForbiddenException;
use App\Exception\NoValidateException;


/**
 * ExceptionListener class
 */

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
        $message = sprintf(
            'My Error says: %s with code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );
        // à customiser
        $response = new Response();
        $response->setContent($message);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
    /**
     * createResponse function
     * 
     * @param Response $response 
     * @param \Exception $exception
     * @param [type] $message
     * @param [title] $messages
     * return @response
     */

    public function createResponse(Response $response, \Exception $exception, $message): Response
    {
        switch (true) {

            case $exception instanceof JWTDecodeFailureException:
                $response->setContent(json_encode(['code' => 401, 'message' => $message]));
                $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
                break;
            case $exception instanceof ForbiddenException:
                $response->setContent(json_encode(['code' => 403, 'message' => $message]));
                $response->setStatusCode(Response::HTTP_FORBIDDEN);
                break;
            case $exception instanceof NoValidateException:
                $response->setContent(json_encode(['code' => 404, 'message' => $message]));
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                break;
            default:
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $response;
    }
}
