<?php

namespace App\Form;

use App\Entity\Machine;
use App\Entity\TroubleshootingNode;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TroubleshootingNodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un titre',
                    ]),
                ],
                'label' => 'Titre',
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une description',
                    ]),
                ],
                'label' => 'Description',
                'attr' => ['rows' => 5],
            ])
            ->add('machine', EntityType::class, [
                'class' => Machine::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Sélectionnez une machine (optionnel)',
                'label' => 'Machine associée',
            ])
            ->add('isSolution', CheckboxType::class, [
                'required' => false,
                'label' => 'Est une solution',
                'help' => 'Cochez si ce nœud représente une solution finale',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TroubleshootingNode::class,
        ]);
    }
} 