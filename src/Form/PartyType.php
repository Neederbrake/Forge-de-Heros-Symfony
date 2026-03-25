<?php

namespace App\Form;

use App\Entity\Character;
use App\Entity\Party;
use App\Entity\user;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom du groupe'
            ])
            ->add('description', null, [
                'label' => 'Description'
            ])
            ->add('maxSize', null, [
                'label' => 'Taille maximum'
            ])
            ->add('creator', EntityType::class, [
                'class' => user::class,
                'choice_label' => 'id',
                'label' => 'Créateur'
            ])
            ->add('characters', EntityType::class, [
                'class' => Character::class,
                'choice_label' => 'id',
                'multiple' => true,
                'label' => 'Personnages'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Party::class,
        ]);
    }
}
