<?php

namespace App\Controller;

use App\Entity\StartPunkt;
use App\Form\StartPunktType;
use App\Repository\StartPunktRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/start/punkt")
 */
class StartPunktController extends AbstractController
{
    /**
     * @Route("/", name="start_punkt_index", methods={"GET"})
     */
    public function index(StartPunktRepository $startPunktRepository): Response
    {
        return $this->render('start_punkt/index.html.twig', [
            'start_punkts' => $startPunktRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="start_punkt_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $startPunkt = new StartPunkt();
        $form = $this->createForm(StartPunktType::class, $startPunkt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($startPunkt);
            $entityManager->flush();

            return $this->redirectToRoute('start_punkt_index');
        }

        return $this->render('start_punkt/new.html.twig', [
            'start_punkt' => $startPunkt,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="start_punkt_show", methods={"GET"})
     */
    public function show(StartPunkt $startPunkt): Response
    {
        return $this->render('start_punkt/show.html.twig', [
            'start_punkt' => $startPunkt,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="start_punkt_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, StartPunkt $startPunkt): Response
    {
        $form = $this->createForm(StartPunktType::class, $startPunkt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('start_punkt_index');
        }

        return $this->render('start_punkt/edit.html.twig', [
            'start_punkt' => $startPunkt,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="start_punkt_delete", methods={"POST"})
     */
    public function delete(Request $request, StartPunkt $startPunkt): Response
    {
        if ($this->isCsrfTokenValid('delete'.$startPunkt->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($startPunkt);
            $entityManager->flush();
        }

        return $this->redirectToRoute('start_punkt_index');
    }
}
