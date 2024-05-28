<?php

namespace App\Controller;

use App\Repository\ActiviteRepository;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class TestController extends AbstractController
{
    #[Route(path: '/test', name: 'test')]
    public function test(ActiviteRepository $repository,Request $request): Response
    {
        dd($request->getRequestUri());
        return $this->render('test/index.html.twig');
    }
}