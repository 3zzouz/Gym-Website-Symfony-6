<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TestController extends AbstractController
{
    #[Route(path: '/error/{statuscode}/{statustext}', name: 'error_custom')]
    public function errorPageCustom($statuscode, $statustext): Response
    {
        return $this->render('@Twig/Exception/error.html.twig',
            [
                'status_code' => $statuscode,
                'status_text' => $statustext
            ]);
    }

    #[Route(path: 'test', name: 'test')]
    public function test(): Response
    {
       return $this->render('test.html.twig');
    }
}