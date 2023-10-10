<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/season', name: 'season')]
#[IsGranted('ROLE_ADMIN')]
class SeasonController extends AbstractController
{

    #[Route('/new', name: '_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $season = new Season();
        $seasonForm = $this->createForm(SeasonType::class, $season);

        $seasonForm->handleRequest($request);

        if ($seasonForm->isSubmitted() && $seasonForm->isValid()) {
            $em->persist($season);
            $em->flush();

            $this->addFlash('success', 'Nouvelle saison enregistrée');
            return $this->redirectToRoute('serie_list');
        }

        return $this->render('season/edit.html.twig', [
            'seasonForm' => $seasonForm
        ]);
    }
}
