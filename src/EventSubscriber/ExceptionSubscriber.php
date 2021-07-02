<?php

// namespace App\EventSubscriber;


// use Symfony\Component\HttpFoundation\JsonResponse;

// use Symfony\Component\HttpKernel\KernelEvents;
// use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// use symfony\Component\EventDispatcher\EventSubscriberInterface;
// use Symfony\Doctrine\Common\EventSubscriber;

// class ExceptionSubscriber implements EventSubscriberInterface
// {
//     public  function getSubscribedEvents()
//     {
//         // on recupere le code http
//         $exception = $event->getException();
//         // on cree un array $data  qui stocke  le code recuperé  et le message 

//         if($exception instanceof NotFoundHttpException) {
//             $data = [
//                 'status' => $exception->getStatusCode(),
//                 'message' => 'Resource not found'
//             ];

//         $response = new JsonResponse($data);
//         $event->setResponse($response);
//     }
// }

//     public static function getSubscribedEvents()
//     {
//         return [
//             KernelEvents::EXCEPTION => [
//                 ['processException', 10],
//                 ['logException', 0],
//                 ['notifyException', -10],
//             ],
//         ];
//      }
// }