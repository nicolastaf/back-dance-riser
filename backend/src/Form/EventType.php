<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'évènement',
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Le nom de l\'évènement',
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Description de l\'évènement',
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'La description de l\'évènement',
                ]
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de l\'événement',  
            ])
            ->add('image', FileType::class, [
                'label' => 'Ajouter/Changer l\'image de l\'événement',
                'mapped' => false,
                'required' => false,
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
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'empty_data' => '',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'empty_data' => '',
            ])
            ->add('location', LocationType::class, [
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
