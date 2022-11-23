<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Level;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'empty_data' => '',
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'choices' => [
                    'Membre' => 'ROLE_MEMBER',
                    'Manager' => 'ROLE_MANAGER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'empty_data' => '',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'empty_data' => '',
            ])
            ->add('blaze', TextType::class, [
                'empty_data' => '',
                'label' => 'Blaze'
            ])
            ->add('level', EntityType::class, [
                'class' => Level::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                    ->orderBy('l.name', 'ASC');
                },
            ])
            ->add('activated', ChoiceType::class, [
                'label' => 'Activé / Désactivé',
                'choices' => [
                    'Activé' => true,
                    'Désactivé' => false,
                ],
            ])
            ->add('agendaLink', TextType::class, [
                'label' => 'Lien de l\'agenda de l\'utilisateur',
                'empty_data' => '',
            ])
        ;
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
