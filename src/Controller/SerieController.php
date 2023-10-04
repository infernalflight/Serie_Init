<?php

namespace App\Controller;

use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie')]
class SerieController extends AbstractController
{

    #[Route('/list', name: '_list')]
    public function list(SerieRepository $serieRepository): Response
    {
        $list = $serieRepository->findAll();

        return $this->render('serie/list.html.twig', [
            'series' => $list
        ]);
    }
}
