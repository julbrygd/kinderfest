<?php

namespace App\Controller;

use App\Entity\StartZeit;
use App\Form\StartZeitType;
use App\Repository\StartZeitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/start/zeit")
 */
class StartZeitController extends AbstractController
{
    /**
     * @Route("/", name="start_zeit_index", methods={"GET"})
     */
    public function index(StartZeitRepository $startZeitRepository): Response
    {
        return $this->render('start_zeit/index.html.twig', [
            'start_zeits' => $startZeitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="start_zeit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $startZeit = new StartZeit();
        $form = $this->createForm(StartZeitType::class, $startZeit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($startZeit);
            $entityManager->flush();

            return $this->redirectToRoute('start_zeit_index');
        }

        return $this->render('start_zeit/new.html.twig', [
            'start_zeit' => $startZeit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="start_zeit_show", methods={"GET"})
     */
    public function show(StartZeit $startZeit): Response
    {
        return $this->render('start_zeit/show.html.twig', [
            'start_zeit' => $startZeit,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="start_zeit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, StartZeit $startZeit): Response
    {
        $form = $this->createForm(StartZeitType::class, $startZeit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('start_zeit_index');
        }

        return $this->render('start_zeit/edit.html.twig', [
            'start_zeit' => $startZeit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="start_zeit_delete", methods={"POST"})
     */
    public function delete(Request $request, StartZeit $startZeit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$startZeit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($startZeit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('start_zeit_index');
    }
}
