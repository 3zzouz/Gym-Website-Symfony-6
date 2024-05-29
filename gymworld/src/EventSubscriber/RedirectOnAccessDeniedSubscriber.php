<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RedirectOnAccessDeniedSubscriber implements EventSubscriberInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        if (str_contains($request->getRequestUri(), 'error')) {
            return;
        }
        // If the exception is not an AccessDeniedException, return
        if (!$event->getThrowable() instanceof AccessDeniedHttpException) {
            //get the error code and error text
            $error_code = $event->getThrowable() instanceof HttpExceptionInterface ? $event->getThrowable()->getStatusCode() :
                $event->getThrowable()->getCode();
            $error_text = $event->getThrowable()->getMessage();
            $error_text = str_replace('/', '-', $error_text);
            $url = $this->urlGenerator->generate('error_custom', ['statuscode' => $error_code, 'statustext' => $error_text]);
        } else {
            if (str_contains($request->getRequestUri(), 'admin')) {
                $url = $this->urlGenerator->generate('app_login_admin');
            } // Generate the URL for the page you want to redirect to
            else {
                $url = $this->urlGenerator->generate('app_login_user');
            }
        }
        // Create a response and set it on the event
        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}