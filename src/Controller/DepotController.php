<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Entity\Compte;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DepotController extends AbstractController
{
    /**
     * @Route("/depot", name="depot")
     */
    public function depot(Request $request,EntityManagerInterface $entityManager)
    {
        $values = json_decode($request->getContent());
        if(isset($values->montant)) {

            $depot = new Depot();
        if(($values->montant)>=75000){
            $depot->setMontant($values->montant);
            $depot->setDatedepot(new \DateTime());

            $repo = $this->getDoctrine()->getRepository(Compte::class);
            $compte = $repo->find($values->compte);
            $depot->setCompte($compte);

            $compte->setSolde($compte->getSolde() + $values->montant);

            $entityManager->persist($compte);

            

            $entityManager->persist($depot);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'Le depot est fait avec succées'
            ];

            return new JsonResponse($data, 201);
        
        if(!$compte){
        $data = [
            'status' => 500,
            'message' => 'erreur lors du depot'
        ];
        return new JsonResponse($data, 500);
    }
}else{
    echo 'veuillez deposer plus de 75000';
}
}
}
}
