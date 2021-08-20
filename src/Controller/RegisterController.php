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
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Uid\UuidV4;

class RegisterController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

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

    private function getNewRegistration(int $spId, int $szId, StartPunktRepository $spr, StartZeitRepository $szr): \App\Entity\Registration {
        $uuid = Uuid::v4();
        $reg = new Registration();
        $reg->setUuid($uuid->toBase32())
            ->setStartZeit($szr->find($szId))
            ->setStartPunk($spr->find($spId));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reg);
        $entityManager->flush();
        return $reg;
    }

    /**
     * @Route("/register/{spId}/{szId}", name="register-sp-sz", requirements={"spId"="\d+", "szId"="\d+"})
     */
    public function register(int $spId, int $szId, StartPunktRepository $spr, StartZeitRepository $szr, RegistrationRepository $regRepo): Response
    {
        $registrations = $this->session->get("registrations", null);
        $uuid = null;
        if($registrations != null){
            if(is_array($registrations) && array_key_exists($spId, $registrations)){
                $tmp = $registrations[$spId];
                if(is_array($tmp) && array_key_exists($szId, $tmp)){
                    $uuid = $tmp[$szId];
                }
            }
        }
        $reg = null;
        if($uuid == null){
            $reg = $this->getNewRegistration($spId, $szId, $spr, $szr);
        } else {
            $reg = $regRepo->findOneBy(['uuid' => $registrations[$spId][$szId]]);
            if($reg === null){
                $reg = $this->getNewRegistration($spId, $szId, $spr, $szr);
            }
        }
        if(is_array($registrations)){
            if(array_key_exists($spId, $registrations)){
                if(!is_array($registrations[$spId])){
                    $registrations[$spId] = array($szId => $uuid);
                } else {
                    $registrations[$spId][$szId] = $reg->getUuid();
                }
            } else {
                $registrations[$spId] = array($szId => $reg->getUuid());
            }
            $this->session->set("registrations", $registrations);
        } else {
            $this->session->set("registrations", array($spId => array($szId => $reg->getUuid())));
        }
        $redirect = $reg->getUuid();
        if($redirect instanceof UuidV4){
            $redirect = $reg->getUuid()->toBase32();
        }
        return $this->redirectToRoute("register-edit", ["uuid" =>  $redirect]);
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
            'presonen' => $reg->getPersons(),
            'delete_url' => $this->generateUrl('register-delete', ['uuid' => $uuid]),
            'email' => $reg->getEmail()
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
                if ($personRepo->checkIfPersonRegistered($personData["pre_name"], $personData["name"], $personData["addres"], $personData["plz"])){
                    return $this->json([
                        "success" => false,
                        "msg" => $personData["pre_name"] . " " . $personData["name"] . "ist schon angemeldet, eine Doppel Anmeldung ist nicht erlaubt"
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

    /**
     * @Route("/register/sendmail/{csrf}", name="register-sendmail",  requirements={"uuid"="\w+"}, methods={"POST"})
     */
    public function sendMail(string $csrf, MailerInterface $mailer, Request $req, LoggerInterface $log, RegistrationRepository $repo) {
        if($this->isCsrfTokenValid("sendmail", $csrf)){           
            if ($content = $req->getContent()) {
                $mailData = json_decode($content, true);
                $mail = new TemplatedEmail();
                $reg = $repo->findOneBy(["uuid" => $mailData["uuid"]]);
                $mailData["start_punkt"] = $reg->getStartPunk()->getName();
                $mailData["start_zeit"] = $reg->getStartZeit()->getZeit()->format("H:i");
                $mail->from("kinderfest@conrad.pics")
                    ->to($mailData["mail"])
                    ->subject("Anmeldung Kinderfest Birsfelden 2021")
                    ->htmlTemplate('register/mail.html.twig')
                    ->context($mailData)
                    ->getHeaders()
                        // this header tells auto-repliers ("email holiday mode") to not
                        // reply to this message because it's an automated email
                        ->addTextHeader('X-Auto-Response-Suppress', 'OOF, DR, RN, NRN, AutoReply');
                try {
                    $mailer->send($mail);
                    $reg = $repo->findOneBy(["uuid" => $mailData["uuid"]]);
                    if($reg !== null){
                        $reg->setEmail($mailData["mail"]);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->flush();
                    }
                } catch (TransportExceptionInterface $e) {
                    $log->error("Error sending mail", array("exception"=>$e));
                }
                return $this->json(array(
                    "sucess" => true
                ));
            }
        } else {
            return $this->json(array(
                "success" => false,
                "msg" => "Error csrf token not valid"
            ));
        }
    }

    /**
     * @Route("/register/delete/{uuid}", name="register-delete", requirements={"uuid"="\w+"}, methods={"DELETE"})
     */
    public function deleteUser(string $uuid, PersonRepository $repo, Request $req) {
        if ($req->isXmlHttpRequest()) {
            $personData = [];
            if ($content = $req->getContent()) {
                $personData = json_decode($content, true);
                $person = $repo->find($personData["id"]);
                $csrf = $personData["token"];
                if($person == null) {
                    return $this->json(array(
                        "success" => false,
                        "msg" => "Person with id "+$personData["id"]+" not fund!"
                    ));
                } else if($person->getRegistration()->getUuid()->toBase32() != $uuid) {
                    return $this->json(array(
                        "success" => false,
                        "msg" => "The uuids do not match!",
                        "person" => $person->getRegistration()->getUuid()->toBase32()
                    ));
                }else if(!$this->isCsrfTokenValid('delete-item',$csrf)) {
                    return $this->json(array(
                        "success" => false,
                        "msg" => "Bitte laden sie die Seite neu, die Sicherheits nummer ist ungültig!"
                    ));
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($person);
                $entityManager->flush();
                return $this->json(array(
                    "success" => true
                ));
            }
        }
    }

    /**
     * @Route("/register/jobs/clean", name="register-clean")
     */
    public function clean(RegistrationRepository $repo): Response
    {
        $found = $repo->findBy(["email" => null]);
        $log = array();
        $now = new \DateTime();
        $entityManager = $this->getDoctrine()->getManager();
        foreach($found as $reg){
            $diff = abs($now->getTimestamp() - $reg->getUpdated()->getTimestamp());
            if($diff > 60 * 60){
                $log[] = "Delete Registration with id " . $reg->getId();
                $entityManager->remove($reg);
                $entityManager->flush();
            }
        }
        return $this->render('register/clean.html.twig', ["found" => $found, "log" => $log]);
    }
}
