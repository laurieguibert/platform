<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserLessonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startedAt', DateTimeType::class)
            ->add('endedAt', DateTimeType::class)
            ->add('certificated', CheckboxType::class, array(
                'description' => 'Is user certified on the lesson ?'
            ))
            ->add('certificatedDate', DateTimeType::class)
            ->add('favorite', CheckboxType::class, array(
                'description' => 'Is lesson a favorite one for the user ?'
            ))
            ->add('user', EntityType::class, array(
                'class'=>'AppBundle:User',
                'description' => 'User',
            ))
            ->add('lesson', EntityType::class, array(
                'class'=>'AppBundle:Lesson',
                'description' => 'Lesson',
            ))
            ->add('status', EntityType::class, array(
                'class'=>'AppBundle:UserLessonStatus',
                'description' => 'User lesson status',
            ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\UserLesson'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_userlesson';
    }


}
