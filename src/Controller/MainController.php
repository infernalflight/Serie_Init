<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(HttpClientInterface $httpClient): Response
    {
        $response = $httpClient->request('GET', 'https://api.chucknorris.io/jokes/random');
        $datas = json_decode($response->getContent(), true);

        return $this->render('main/home.html.twig', [
            'joke' => $datas['value'] ?? 'pas de vanne aujourd\'hui, désolé',
        ]);
    }
}
