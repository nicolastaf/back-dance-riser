<?php

namespace App\Form;

use App\Entity\Move;
use App\Entity\Level;
use App\Entity\Video;
use App\Entity\School;
use App\Entity\CategoryMove;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MoveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Le nom du mouv',
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Description du mouv',
                'empty_data' => '',
            ])
            ->add('image', FileType::class, [
                'label' => 'Ajouter/Changer l\'image du Move',
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
            ->add('visibility', ChoiceType::class, [
                'choices' => [
                    'Pour les inscrits du site' => true,
                    'Pour l\'école' => false,
                ],
            ])
            ->add('categoryMove', EntityType::class, [
                'class' => CategoryMove::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ])
            ->add('level', EntityType::class, [
                'class' => Level::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                    ->orderBy('l.name', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Move::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
