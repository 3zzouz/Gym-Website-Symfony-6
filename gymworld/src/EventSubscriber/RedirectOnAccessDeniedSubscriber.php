<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
    // If the exception is not an AccessDeniedException, return
        if (!$event->getThrowable() instanceof AccessDeniedHttpException) {
            return;
        }

        $request = $event->getRequest();
        if (str_contains($request->getRequestUri(), 'admin')) {
            $url = $this->urlGenerator->generate('app_login_admin');
        } // Generate the URL for the page you want to redirect to
        else {
            $url = $this->urlGenerator->generate('app_login_user');
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