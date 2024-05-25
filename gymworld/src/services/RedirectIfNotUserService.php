<?php

namespace App\services;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RedirectIfNotUserService
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function redirectIfNotUser($condition = false)
    {
        if (!$condition) {
            return new RedirectResponse($this->urlGenerator->generate('app_login_user'));
        }
        return null;
    }
}