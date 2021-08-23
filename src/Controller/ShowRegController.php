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
    /** 
     *  @Route("/show/reg", name="show_reg")
    */
    public function index(RegistrationRepository $regRepo, StartZeitRepository $szRepo, StartPunktRepository $spRepo): Response
    {
        $mails = array();
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
            foreach($reg->getPersons() as $person){
                $mails[$person->getEmail()] = true;
            }
        }
        return $this->render('show_reg/index.html.twig', [
            'controller_name' => 'ShowRegController',
            'data' => $data,
            'mails' => implode(";", array_keys($mails))
        ]);
    }

    /**
     * @Route("/show/reg_csv", name="show_reg_csv")
     */
    public function csv(PersonRepository $personRepo): Response {
        $data = "Namen;Vornamen;Adresse;PLZ;Ort;Tel;E-Mail Adresse;Start Zeit;Start Punk" . PHP_EOL;
        foreach($personRepo->findAll() as $person){
            $data .= $person->getName() . ";";
            $data .= $person->getPreName() . ";";
            $data .= $person->getAdresse() . ";";
            $data .= $person->getPlz() . ";";
            $data .= $person->getOrt() . ";";
            $data .= $person->getTel() . ";";
            $data .= $person->getEmail() . ";";
            $data .= $person->getStartZeit()->getZeit()->format("H:i") . ";";
            $data .= $person->getStartPunkt()->getName() . PHP_EOL;
        }
        $response = new Response($data);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="anmeldungen.csv"');
        return $response;
    }
}
