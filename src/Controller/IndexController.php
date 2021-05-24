<?php

namespace App\Controller;

use App\Entity\StartZeit;
use App\Repository\StartZeitRepository;
use App\Entity\StartPunkt;
use App\Repository\StartPunktRepository;
use App\Entity\Person;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function PHPSTORM_META\map;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(StartZeitRepository $startZeitRepository, StartPunktRepository $startPunkt, PersonRepository $personRepo): Response
    {
        $start_zeiten = $startZeitRepository->findAll();
        $start_punkte =  $startPunkt->findAll();
        $persons = array();
        foreach($start_punkte as $startPunkt){
            $persons[$startPunkt->getId()] = array();
            foreach($start_zeiten as $start_zeit){
                $persons[$startPunkt->getId()][$start_zeit->getId()] = ($personRepo->countPersonForStartZeitAndPunkt($start_zeit, $startPunkt));
            }
        }
        return $this->render('index/index.html.twig', [
            'start_zeiten' => $start_zeiten,
            'start_punkt' => $start_punkte,
            'persons' => $persons,
        ]);
    }
}