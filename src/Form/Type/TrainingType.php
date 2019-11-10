<?php

namespace App\Form\Type;

use App\Entity\Job;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TrainingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class)
            ->add('Jobs', EntityType::class, [
                'class' => Job::class,
                'choice_label' => 'title',
                'multiple' => true,
            ])
            ->add('save', SubmitType::class)
        ;
    }
}