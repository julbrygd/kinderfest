<?php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use App\Entity\StartZeit;
use App\Repository\StartZeitRepository;
use App\Entity\StartPunkt;
use App\Repository\StartPunktRepository;
use App\Repository\PersonRepository;
use App\Form\RegisterType;
use App\Entity\Registration;
use App\Repository\RegistrationRepository;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(): Response
    {
        $uuid = Uuid::v4();
        return $this->render('register/index.html.twig', [
            'uuid' => $uuid->toBase32(),
        ]);
    }

    /**
     * @Route("/space/{spId}/{szId}", name="space-sp-sz", requirements={"spId"="\d+", "szId"="\d+"})
     */
    public function getSpace(int $spId, int $szId, StartZeitRepository $szRepo, StartPunktRepository $spReop, PersonRepository $personRepo): Response
    {
        $sp = $spReop->find($spId);
        $sz = $szRepo->find($szId);
        $max = $sz->getMaxPersonen();
        $users = $personRepo->countPersonForStartZeitAndPunkt($sz, $sp);
        return $this->json([
            "max" => $max,
            "count" => $users,
            "free" => $max - $users
        ]);
    }

    /**
     * @Route("/register/{spId}/{szId}", name="register-sp-sz", requirements={"spId"="\d+", "szId"="\d+"})
     */
    public function register(int $spId, int $szId, StartPunktRepository $spr, StartZeitRepository $szr): Response
    {
        $uuid = Uuid::v4();
        $reg = new Registration();
        $reg->setUuid($uuid->toBase32())
            ->setStartZeit($szr->find($szId))
            ->setStartPunk($spr->find($spId));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reg);
        $entityManager->flush();
        return $this->redirectToRoute("register-edit", ["uuid" => $reg->getUuid()]);
    }
    /**
     * @Route("/register/{uuid}", name="register-edit", requirements={"uuid"="\w+"})
     */
    public function edit(string $uuid, PersonRepository $personRepo, RegistrationRepository $repo): Response
    {
        $reg = $repo->findOneBy(['uuid' => $uuid]);
        $sp = $reg->getStartPunk();
        $sz = $reg->getStartZeit();
        $person = new Person();
        $person->setStartPunkt($sp)->setStartZeit($sz);
        $max = $sz->getMaxPersonen();
        $users = $personRepo->countPersonForStartZeitAndPunkt($sz, $sp);
        return $this->render('register/index.html.twig', [
            'sp' => $sp,
            'sz' => $sz,
            'space' => $max - $users,
            'refresh_url' => $this->generateUrl('space-sp-sz', ['spId' => $sp->getId(), 'szId' => $sz->getId()]),
            'save_url' => $this->generateUrl('register-save', ['uuid' => $uuid]),
            'uuid' => $uuid,
            'presonen' => $reg->getPersons()
        ]);
    }

    /**
     * @Route("/register/save/{uuid}", name="register-save", requirements={"uuid"="\w+"}, methods={"POST"})
     */
    public function saveUser(string $uuid, Request $req, StartPunktRepository $spRepo, StartZeitRepository $szRepo, RegistrationRepository $regRepo, PersonRepository $personRepo): Response
    {
        if ($req->isXmlHttpRequest()) {
            $personData = [];
            if ($content = $req->getContent()) {
                $personData = json_decode($content, true);
                $reg = $regRepo->findOneBy(array('uuid' => $personData["uuid"]));
                $sz = $szRepo->find($personData["sz"]);
                $sp = $spRepo->find($personData["sp"]);
                
                $max = $sz->getMaxPersonen();
                $users = $personRepo->countPersonForStartZeitAndPunkt($sz, $sp);

                if ($users >= $max){
                    return $this->json([
                        "success" => false,
                        "msg" => "Diese Start Zeit und Start Punkt ist leider schon voll"
                    ]);
                }
                $person = new Person();
                $person->setStartZeit($sz)
                    ->setStartPunkt($sp)
                    ->setRegistration($reg);
                $person->setName($personData["name"])
                    ->setPreName($personData["pre_name"])
                    ->setAdresse($personData["addres"])
                    ->setPlz($personData["plz"])
                    ->setOrt($personData["ort"])
                    ->setEmail($personData["email"])
                    ->setTel($personData["tel"]);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($person);
                $entityManager->flush();
                $personData["id"] = $person->getId();
                return $this->json([
                    "success"=> true,
                    "data" => $personData
                ]);
            }
        }
    }
}
