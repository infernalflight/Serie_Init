<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie')]
class SerieController extends AbstractController
{
    #[Route('/list/{page}', requirements: ['page' => '\d+'], defaults: ['page' => 1],  name: '_list')]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {


        $limit = $this->getParameter('nb_limit_series');

        // Requetage par methode héritée "findBy"
        $list = $serieRepository->findBy(
            ['status' => 'ended'],
            ['firstAirDate' => 'DESC'],
            $limit,
            ($page - 1) * $limit
        );

        // Requetage par QueryBuilder
        $list = $serieRepository->findBestSeries(300, 8);

        // Requetage par DQL
        $list = $serieRepository->getSeriesByDql(300);

        // Requetage par SQL Raw
        $list = $serieRepository->getSeriesBySql(300);


        $date = (new \DateTime("-10 day"))->format('Y-m-d');
        $result = $serieRepository->getByDateOptionnel($date);


        $count = $serieRepository->count(['status' => 'ended']);

        $nbPage = ceil($count / $limit);

        return $this->render('serie/list.html.twig', [
            'series' => $list,
            'page' => $page,
            'pages_total' => $nbPage
        ]);
    }

    #[Route('/details/{id}', requirements: ['id' => '\d+'], name: "_details")]
    public function details(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);

        return $this->render('serie/details.html.twig', [
            'serie' => $serie
        ]);
    }

    #[Route('/new', name: '_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            $serie->setDateCreated(new \DateTime());

            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'Nouvelle série enregistrée.');
            return $this->redirectToRoute('serie_list');
        }

        return $this->render('serie/edit.html.twig', [
            'serie_form' => $serieForm,
        ]);
    }

    #[Route('/edit/{id}', name: '_edit', requirements: ['id' => '\d+'])]
    public function edit(int $id, SerieRepository $serieRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $serie = $serieRepository->find($id);

        $serieForm = $this->createForm(SerieType::class, $serie);
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            $serie->setDateCreated(new \DateTime());

            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'Série modifiée.');
            return $this->redirectToRoute('serie_list');
        }


        return $this->render('serie/edit.html.twig', [
            'serie_form' => $serieForm,
        ]);
    }


}
