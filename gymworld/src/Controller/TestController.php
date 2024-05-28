<?php

namespace App\Controller;

use App\Repository\ActiviteRepository;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class TestController extends AbstractController
{
    #[Route(path: '/test', name: 'test')]
    public function test(ActiviteRepository $repository,Request $request): Response
    {
        return $this->render('public/pdf.html.twig');
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path:'test/admin', name:'test_admin')]
    public function testAdmin(ActiviteRepository $repository,Request $request): Response
    {
        return $this->render('test/index.html.twig');
    }

    #[IsGranted('ROLE_USER')]
    #[Route(path:'test/user', name:'test_user')]
    public function testUser(ActiviteRepository $repository,Request $request): Response
    {
        return $this->render('test/index.html.twig');
    }
}