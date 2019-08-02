<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Compte;
use App\Entity\Partenaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api")
 */

class PartenaireController extends AbstractController
{
    /**
     * @Route("/partenaire", name="partenaire", methods={"POST"})
     */
    public function ajoutPartenaire(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $random=random_int(10000000,999999999);

        $values = json_decode($request->getContent());

        $user = new User();
            $user->setMatricule($values->matricule);
            $user->setNom($values->nom);
            $user->setPrenom($values->prenom);
            $user->setEmail($values->email);
            $user->setAdresse($values->adresse);
            $user->setTelephone($values->telephone);
            $user->setStatus($values->status);
            $user->setUsername($values->username);
            $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
            $user->setRoles($values->roles);

            $partenaire = new Partenaire();
            $partenaire->setMatricule($values->matricule);
            $partenaire->setNomPartenaire($values->nomPartenaire);
            $partenaire->setNINEA($values->ninea);
            $partenaire->setEmail($values->email);
            $partenaire->setAdresse($values->adresse);
            $partenaire->setTelephone($values->telephone);
            $partenaire->setStatus($values->status);

            $compte=new Compte();

            $compte->setNumCompte($random);
            $compte->setDateCreation(new \DateTime());
            $compte->setSolde($values->solde);

        // relation entre USER et PARTENAIRE
        $user->setPartenaire($partenaire);

        // relation entre USER et COMPTE
        $user->setCompte($compte);

        // relation entre COMPTE et PARTENAIRE
        $compte->setPartenaire($partenaire);

        $entityManager = $this->getDoctrine()->getManager();
        
        $entityManager->persist($user);
        $entityManager->persist($partenaire);
        $entityManager->persist($compte);

        $entityManager->flush();

        return new Response(
            'Saved new partenaire with id: '.$partenaire->getId()
            .' and new utilisateur with id: '.$user->getId()
            .' and new compte with id: '.$compte->getId()
        );
    }
}
