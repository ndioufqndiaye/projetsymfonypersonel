<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Partenaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('Admin');
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $password = $this->encoder->encodePassword($user, 'pass1234');
        $user->setPassword($password);
        $user->setMatricule('SUPER');
        $user->setNom('ndiaye');
        $user->setPrenom('kabirou');
        $user->setEmail('kab@gmail.com');
        $user->setAdresse('dakar');
        $user->setTelephone(775452210);
        $user->setStatus('activer');
        //$user->setPartenaire();
        //$user->setCompte();
       

       
       $manager->persist($user);

       $manager->flush();
   }

        
}