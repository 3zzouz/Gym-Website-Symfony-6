<?php

namespace App\Controller;

use App\Entity\Offres;
use App\Form\MailerFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class PublicController extends AbstractController
{
    #[Route('/services', name: 'app_services')]
    public function services(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Offres::class);
        $services = $repository->findAll();
        return $this->render('MainPages/client/service.html.twig', [
            'services' => $services
        ]);
    }

    #[Route(path: '/team', name: 'app_team')]
    public function team(): Response
    {
        return $this->render('MainPages/client/team.html.twig', ['controller_name' => 'ClientController']);
    }

    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('MainPages/client/index.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(MailerFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new Email())
                ->to('gymworld135@gmail.com')
                ->from('gymworld135@gmail.com')
                ->subject($data['subject'])
                ->text($data['from'] . " " . $data['message']);
            $mailer->send($email);

            $this->addFlash('success', 'Email sent successfully!');
        }

        return $this->render('MainPages/client/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/timetable/{num<\d+>?1}', name: 'app_timetable')]
    public function timetable($num, Request $request): Response
    {

        $response = $this->render('MainPages/client/timetable.html.twig', [
            'num' => $num
        ]);
        $response->headers->set('Cache-Control', 'max-age=1, must-revalidate');
        $response->headers->set('Pragma', 'no-cache'); // Pour HTTP 1.0
        $response->headers->set('Expires', '0'); // Pour les proxies
        return $response;
    }

}
