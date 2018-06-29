<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'description' => 'Name of the lesson',
            ))
            ->add('description', TextType::class, array(
                'description' => 'Description of the lesson',
            ))
            ->add('duration', IntegerType::class, array(
                'description' => 'Duration of the lesson',
            ))
            ->add('certificate', CheckboxType::class, array(
                'description' => 'Has lesson certification or not',
            ))
            ->add('lesson_type', EntityType::class, array(
                'class'=>'AppBundle:LessonType',
                'description' => 'Type of the lesson',
            ))
            ->add('duration_type', EntityType::class, array(
                'class'=>'AppBundle:DurationType',
                'description' => 'Duration of the lesson',
            ))
            ->add('sector', EntityType::class, array(
                'class'=>'AppBundle:Sector',
                'description' => 'Sector concerned by the lesson',
            ))
            ->add('level', EntityType::class, array(
                'class'=>'AppBundle:Level',
                'description' => 'Level required for the lesson',
            ))
            ->add('price', NumberType::class, array(
                'description' => 'Price of the lesson',
                'scale' => 2
            ))
            ->add('author', EntityType::class, array(
                'class' => 'AppBundle:User',
                'description' => 'Author of the lesson',
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Lesson'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_lesson';
    }


}
