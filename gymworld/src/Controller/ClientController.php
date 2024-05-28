<?php

namespace App\Controller;

use App\Entity\OffreClient;
use App\Entity\Offres;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\OffreClientRepository;
use App\services\PdfService;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
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
    public function __construct(private readonly PdfService $pdfService)
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
        Stripe::setApiKey($stripe_secret_key);

        try {
            $checkout_session = Session::create([
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
            $lastOffre = $manager->getRepository(OffreClient::class)->findOneBy(['client' => $client->getId()], ['date_fin' => 'DESC']);
            $date = DateTime::createFromFormat('Y-m-d', $lastOffre->getDateFin()->format('Y-m-d'));
            $offreclient->setDateDebut($date);
            $newDate = DateTime::createFromFormat('Y-m-d', $date->format('Y-m-d'));
            $newDate->add(new DateInterval('P' . $offreDuration . 'M'));
            $offreclient->setDateFin($newDate);
            $manager->persist($offreclient);
            $manager->flush();

            return new RedirectResponse($checkout_session->url, 303);
        } catch (ApiErrorException $e) {
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
    public function dashboard($id,
                              ManagerRegistry $doctrine, Request $request, User $user = null): Response
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
            'form' => $form->createView(), 'user' => $user, 'dateActuelle' => new DateTime()
        ]);

    }


    #[Route('/accessCard/{id}', name: 'app_user_accesscard')]
    public function accessCard(PdfService $pdfService, OffreClientRepository $clientRepository, $id, User $personne = null):
    Response
    {
        if ($id != $this->getUser()->getId() || $personne == null) {
            return $this->redirectToRoute('app_home');
        } else {
            $offres = $personne->getOffreClients();
            if (count($offres) == 0) {
                return $this->redirectToRoute('app_home');
            } else {
                $html = $this->renderView('public/pdf.html.twig', ['personne' =>
                    $personne, 'offre' => $clientRepository->findAppropriate()]);
                $pdfContent = $pdfService->generatePdfContent($html);
                $response = new Response($pdfContent);
                $response->headers->set('Content-Type', 'application/pdf');
                $response->headers->set('Content-Disposition', 'attachment; filename="details.pdf"');
                return $response;
            }
        }
    }

}
