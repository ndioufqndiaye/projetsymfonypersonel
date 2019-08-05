<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Compte;
use App\Entity\Partenaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
           
            ->add('password')
                
            ->add('matricule')
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('adresse')
            ->add('telephone')
            ->add('status')
            ->add('imageFile',VichImageType::class)
            
            /*->add('Partenaire',EntityType::class, [
                'class' => Partenaire::class,
                'choice_label' => 'partenaire_id']
            )
            ->add('compte',EntityType::class, [
                'class' => Compte::class,
                'choice_label' => 'compte_id']
            )*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false
        ]);
    }
}
