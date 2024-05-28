<?php

namespace App\Controller;

use App\Entity\OffreClient;
use App\Entity\Offres;
use App\Entity\User;
use App\Form\UserType;
use App\services\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[IsGranted('ROLE_USER')]
class ClientController extends AbstractController
{
    public function __construct(private PdfService $pdfService)
    {
    }


    #[Route('/success', name: 'success')]
    public function success(): Response
    {
        return $this->render('MainPages/client/paysuccess.html.twig');
    }

    #[Route(path: '/payment', name: 'app_pay')]
    public function pay(Request $request, EntityManagerInterface $manager): Response
    {
        $offreID = $request->request->get('offreID');
        $offrename = $request->request->get('offre');
        $offreDuration = $request->request->get('offreDuration');
        $price = $request->request->get('price');

        $stripe_secret_key = $this->getParameter('STRIPE_SECRET_KEY');
        \Stripe\Stripe::setApiKey($stripe_secret_key);

        try {
            $checkout_session = \Stripe\Checkout\Session::create([
                "mode" => "payment",
                "success_url" => $this->generateUrl('success', [], UrlGeneratorInterface::ABSOLUTE_URL),
                "line_items" => [
                    [
                        "price_data" => [
                            "currency" => "usd",
                            "unit_amount" => $price * 100,
                            "product_data" => [
                                "name" => $offrename,
                            ],
                        ],
                        "quantity" => 1,
                    ],
                ],
            ]);

            $offreclient = new OffreClient();
            $offre = $manager->getRepository(Offres::class)->find($offreID);
            $client = $manager->getRepository(User::class)->find($this->getUser()->getId());
            $offreclient->setClient($client);
            $offreclient->setOffre($offre);
            $date = new \DateTime();
            $offreclient->setDateDebut($date);
            $offreclient->setDateFin($date->add(new \DateInterval('P' . $offreDuration . 'D')));
            $manager->persist($offreclient);
            $manager->flush();

            return new RedirectResponse($checkout_session->url, 303);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log('offreID: ' . $offreID);
            error_log('offre: ' . $offrename);
            error_log('offreDuration: ' . $offreDuration);
            error_log('price: ' . $price);
            error_log($e->getMessage());
            return new RedirectResponse($this->generateUrl('error', [], UrlGeneratorInterface::ABSOLUTE_URL));
        }
    }

    #[Route(path: '/error', name: 'error')]
    public function error(): Response
    {
        return $this->render('MainPages/client/error.html.twig');
    }

    #[Route('/user/dashboard/{id}', name: 'app_user_dashboard')]
    public function dashboard(User                                  $user = null, $id, EntityManagerInterface $manager,
                              \Doctrine\Persistence\ManagerRegistry $doctrine, Request $request): Response
    {
        if ($id != $this->getUser()->getId()) {
            return $this->redirectToRoute('app_home');
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'your profile has been successfully edited');
        }
        return $this->render('MainPages/client/dashboard.html.twig', [
            'form' => $form->createView(), 'user' => $user, 'dateActuelle' => new \DateTime()
        ]);

    }


    #[Route('/accessCard/{id}', name: 'app_user_accesscard')]
    public function accessCard(PdfService $pdfService, $id, User $personne = null,):
    Response
    {
        if ($id != $this->getUser()->getId()) {
            return $this->redirectToRoute('app_home');
        }
        if ($personne == null) {
            return $this->redirectToRoute('app_home');
        } else {
            $offres = $personne->getOffreClients();

            if (count($offres) == 0) {
                return $this->redirectToRoute('app_home');
            } else {
                $html = $this->renderView('public/pdf.html.twig', ['personne' => $personne]); // Utilise renderView() pour générer le HTML sans le rendre
                $pdfContent = $pdfService->generatePdfContent($html);
                $response = new Response($pdfContent);
                $response->headers->set('Content-Type', 'application/pdf');
                $response->headers->set('Content-Disposition', 'attachment; filename="details.pdf"');
                return $response;
            }
        }
    }

}
