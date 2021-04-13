<?php

namespace App\Controller;

use App\Entity\StartZeit;
use App\Repository\StartZeitRepository;
use App\Entity\StartPunkt;
use App\Repository\StartPunktRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(StartZeitRepository $startZeitRepository, StartPunktRepository $startPunkt): Response
    {
        return $this->render('index/index.html.twig', [
            'start_zeiten' => $startZeitRepository->findAll(),
            'start_punkt' => $startPunkt->findAll(),
        ]);
    }
}
