<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, array(
                'description' => 'Firstname of the user',
            ))
            ->add('lastname', TextType::class, array(
                'description' => 'Lastname of the user',
            ))
            ->add('email', EmailType::class, array(
                'description' => 'Email of the user',
                'required' => false
            ))
            ->add('country', EntityType::class, array(
                'class'=>'AppBundle:Country',
                'description' => 'Country',
            ))
            ->add('school', TextType::class, array(
                'description' => 'School of the user',
            ))
            ->add('studiesLevel', EntityType::class, array(
                'class' => 'AppBundle:StudiesLevel',
                'description' => 'Studies\' level of the user',
            ))
            ->add('currentPosition', TextType::class, array(
                'description' => 'Current position of the user',
            ))
            ->add('sector', EntityType::class, array(
                'class'=>'AppBundle:Sector',
                'description' => 'Working sector',
                'required' => false
            ))
            ->add('description', TextType::class, array(
                'description' => 'Description of the user',
            ))
            ->add('situation', EntityType::class, array(
                'class' => 'AppBundle:Situation',
                'description' => 'Situation of the user',
            ))
            ->add('studiesLevel', EntityType::class, array(
                'class'=>'AppBundle:StudiesLevel',
                'description' => 'Studies\' level of the user',
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_user_update_type';
    }
}
