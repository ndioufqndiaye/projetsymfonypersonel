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
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api")
 */
class UtilisateurController extends AbstractController
{
    /**
     * @Route("/utili", name="utilisateur")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());
        if(isset($values->username,$values->password)) {

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

            $rep=$this->getDoctrine()->getRepository(Partenaire::class);
            $Partenaire=$rep->find($values->Partenaire);
            $user->setPartenaire($Partenaire);

            $rep=$this->getDoctrine()->getRepository(Compte::class);
            $compte=$rep->find($values->compte);
            $user->setCompte($compte);

            $errors = $validator->validate($user);

            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }


            $entityManager->persist($user);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'L\'utilisateur a été créé'
            ];

            return new JsonResponse($data, 201);
        }
        $data = [
            'status' => 500,
            'message' => 'Vous devez renseigner les clés username et password'
        ];
        return new JsonResponse($data, 500);
    }

    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request)
    {
        $user = $this->getUser();
        return $this->json([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles()
        ]);
    }
}
