<?php

namespace App\Form;

use App\Entity\Level;
use App\Entity\School;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SchoolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Le nom de l\'école',
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Description de l\'école',
                'empty_data' => '',
            ])
            ->add('email', EmailType::class, [
                'empty_data' => '',
                'label' => 'Email de l\'école',
            ])
            ->add('phone', TextType::class, [
                'empty_data' => '',
                'label' => 'Téléphone de l\'école'
            ])
            ->add('lessonType', ChoiceType::class, [
                'label' => 'Les cours sont:',
                'choices' => [
                    'privé' => 'Private',
                    'public' => 'Public',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('openTo', ChoiceType::class, [
                'label' => 'Ouvert aux',
                'choices' => [
                    'Bébés' => 'Babies',
                    'Enfants' => 'Children',
                    'Adultes' => 'Adults',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('level', EntityType::class, [
                'class' => Level::class,
                'label' => 'Niveaux acceptés dans l\'école',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('image', FileType::class, [
                'label' => 'Ajouter/Changer l\'image de l\'école',
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Vous devez ajouter une image de type jpeg/jpg/png/svg',
                    ])
                ],
            ])
            ->add('agendaLink', TextType::class, [
                'label' => 'Lien de l\'agenda de l\'école',
                'empty_data' => '',
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => School::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
