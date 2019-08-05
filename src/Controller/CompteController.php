<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Form\CompteType;
use App\Entity\Partenaire;
use App\Repository\CompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/compte")
 */
class CompteController extends AbstractController
{
    /**
     * @Route("/", name="compte_index", methods={"GET"})
     */
    public function index(CompteRepository $compteRepository): Response
    {
        return $this->render('compte/index.html.twig', [
            'comptes' => $compteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/comptePart", name="compte_new", methods={"GET","POST"})
     */
    public function ajoutcompte(Request $request,EntityManagerInterface $entityManager)
    {
        $random=random_int(10000000,999999999);

        $values = json_decode($request->getContent());
       

        $compte=new Compte();

        $compte->setNumCompte($random);
        $compte->setDateCreation(new \DateTime());
        $compte->setSolde($values->solde);

    $rep=$this->getDoctrine()->getRepository(Partenaire::class);
    $Partenaire=$rep->find($values->Partenaire);
    $compte->setPartenaire($Partenaire);

            $repo = $this->getDoctrine()->getRepository(Compte::class);
            
            $entityManager->persist($compte);
            $entityManager->flush();

            return new Response(
                ' and new compte with id: '.$compte->getId()
            );
   
}

    /**
     * @Route("/{id}", name="compte_show", methods={"GET"})
     */
    public function show(Compte $compte): Response
    {
        return $this->render('compte/show.html.twig', [
            'compte' => $compte,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="compte_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Compte $compte): Response
    {
        $form = $this->createForm(CompteType::class, $compte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('compte_index');
        }

        return $this->render('compte/edit.html.twig', [
            'compte' => $compte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="compte_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Compte $compte): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compte->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($compte);
            $entityManager->flush();
        }

        return $this->redirectToRoute('compte_index');
    }
}
