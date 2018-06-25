<?php

namespace AppBundle\Form;

use Doctrine\DBAL\Types\BooleanType;
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
            ))
            ->add('password', TextType::class, array(
                'description' => 'Password of the user',
            ))
            ->add('sector', EntityType::class, array(
                'class'=>'AppBundle:Sector',
                'description' => 'Working sector',
            ))
            ->add('status', BooleanType::class, array(
                'description' => 'Status of the user',
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
