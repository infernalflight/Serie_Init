<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/serie', name: 'serie')]
class SerieController extends AbstractController
{
    #[Route('/list/{page}', requirements: ['page' => '\d+'], defaults: ['page' => 1],  name: '_list')]
    #[IsGranted('ROLE_USER')]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {

        $limit = $this->getParameter('nb_limit_series');

        // Requetage par methode héritée "findBy"
    /**
        $list = $serieRepository->findBy(
            ['status' => 'returning'],
            ['firstAirDate' => 'DESC'],
            $limit,
            ($page - 1) * $limit
        );
**/
        $list = $serieRepository->findWithJoin(['status' => 'returning'], $limit, $page);



        // Requetage par QueryBuilder
        // $list = $serieRepository->findBestSeries(300, 8);

        // Requetage par DQL
        // $list = $serieRepository->getSeriesByDql(300);

        // Requetage par SQL Raw
        // $list = $serieRepository->getSeriesBySql(300);


        //$date = (new \DateTime("-10 day"))->format('Y-m-d');
        //$result = $serieRepository->getByDateOptionnel($date);

        $count = $serieRepository->count(['status' => 'returning']);

        $nbPage = ceil($count / $limit);

        return $this->render('serie/list.html.twig', [
            'series' => $list,
            'page' => $page,
            'pages_total' => $nbPage
        ]);
    }

    #[Route('/details/{id}', requirements: ['id' => '\d+'], name: "_details")]
    #[IsGranted('ROLE_USER')]
    public function details(Serie $serie): Response
    {
        return $this->render('serie/details.html.twig', [
            'serie' => $serie
        ]);
    }

    #[Route('/new', name: '_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            if ($serieForm->get('poster_file')->getData() instanceof UploadedFile) {
                $posterFile = $serieForm->get('poster_file')->getData();
                $newFileName = $slugger->slug($serie->getName()) . '_' . uniqid() .'.'. $posterFile->guessExtension();
                $posterFile->move($this->getParameter('posters_path'), $newFileName);
                $serie->setPoster($newFileName);
            }

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
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, EntityManagerInterface $entityManager, Serie $serie, SluggerInterface $slugger): Response
    {
        $serieForm = $this->createForm(SerieType::class, $serie);
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            if ($serieForm->get('poster_file')->getData() instanceof UploadedFile) {
                $posterFile = $serieForm->get('poster_file')->getData();
                $newFileName = $slugger->slug($serie->getName()) . '_' . uniqid() . '.'. $posterFile->guessExtension();
                $posterFile->move($this->getParameter('posters_path'), $newFileName);

                if ($serie->getPoster() && file_exists($this->getParameter('posters_path').$serie->getPoster())) {
                    unlink($this->getParameter('posters_path').$serie->getPoster());
                }

                $serie->setPoster($newFileName);
            }

            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'Série modifiée.');
            return $this->redirectToRoute('serie_list');
        }

        return $this->render('serie/edit.html.twig', [
            'serie_form' => $serieForm,
        ]);
    }

    #[Route('/remove/{id}', name: '_remove', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function remove(Serie $serie, EntityManagerInterface $entityManager): Response
    {

        foreach($serie->getSeasons() as $season) {
            $season->setSerie(null);
        }

        $entityManager->remove($serie);
        $entityManager->flush();

        $this->addFlash('success', 'La série a été supprimée');
        return $this->redirectToRoute('serie_list');
    }


}
