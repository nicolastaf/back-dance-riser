<?php

namespace App\Form;

use App\Entity\Style;
use App\Entity\CategoryMove;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CategoryMoveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'empty_data' => '',
            ])
            ->add('description', TextType::class, [
                'label' => 'Description de la catégorie de mouvement',
                'empty_data' => '',
            ])
            ->add('image', FileType::class, [
                'label' => 'Ajouter/Changer l\'image de la catégorie de mouvement',
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
            ->add('style', EntityType::class, [
                'class' => Style::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                    ->orderBy('s.name', 'ASC');
                },
            ])
            ->add('activated', ChoiceType::class, [
                'label' => 'Activé / Désactivé',
                'choices' => [
                    'Activé' => true,
                    'Désactivé' => false,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoryMove::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
