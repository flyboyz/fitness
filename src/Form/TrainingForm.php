<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\Training;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('jobs', EntityType::class, [
                    'class'     => Job::class,
                    'choice_label' => 'title',
                    'label'     => 'Who is fighting in this round?',
                    'expanded'  => true,
                    'multiple'  => true,
                    'mapped' => false,
                ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
            'allow_extra_fields' => true
        ]);
    }
}