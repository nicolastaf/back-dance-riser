<?php

namespace App\Form;

use App\Entity\Style;
use App\Entity\School;
use App\Entity\Choreography;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ChoreographyType extends AbstractType
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Le nom de la chorégraphie',
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Description de la chorégraphie',
                'empty_data' => '',
            ])
            ->add('image', FileType::class, [
                'label' => 'Ajouter/Changer l\'image de la chorégraphie',
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
            ->add('school', EntityType::class, [
                'class' => School::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('s')
                    ->join('s.members', 'm')
                    ->where('m.user = :user')
                    ->setParameter('user', $user)
                    ->andWhere('s.activated = 1')
                    ->orderBy('s.name', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Choreography::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
