<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class,[
                'label' => 'Adresse',
                'empty_data' => '',
            ])
            ->add('addressComplement', TextType::class, [
                'label' => 'Complément d\'adresse',
                'empty_data' => '',
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal',
                'empty_data' => '',
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'empty_data' => '',
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'empty_data' => '',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
