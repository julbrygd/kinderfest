<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PersonRepository;
use App\Repository\StartPunktRepository;
use App\Repository\StartZeitRepository;
use App\Repository\RegistrationRepository;

class ShowRegController extends AbstractController
{
    #[Route('/show/reg', name: 'show_reg')]
    public function index(RegistrationRepository $regRepo, StartZeitRepository $szRepo, StartPunktRepository $spRepo): Response
    {

        $data = array();
        $zeiten = $szRepo->findAll();
        foreach($spRepo->findAll() as $sp){
            $data[$sp->getId()] = array(
                "name" => $sp->getName(),
                "zeiten" => array()
            );
            foreach($zeiten as $sz){
                $data[$sp->getId()]["zeiten"][$sz->getId()] = array(
                    "zeit" => $sz->getZeit(),
                    "personen" => array()
                );
            }
        }
        foreach($regRepo->findAll() as $reg){
            $data[$reg->getStartPunk()->getId()]["zeiten"][$reg->getStartZeit()->getId()]["personen"] = $reg->getPersons();
        }
        return $this->render('show_reg/index.html.twig', [
            'controller_name' => 'ShowRegController',
            'data' => $data
        ]);
    }
}
