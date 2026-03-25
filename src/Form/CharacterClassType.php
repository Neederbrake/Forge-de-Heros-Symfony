<?php

namespace App\Form;

use App\Entity\CharacterClass;
use App\Entity\Skill;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterClassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // construit le formulaire de classe
        $builder
            ->add('name', null, [
                'label' => 'Nom de la classe'
            ])
            ->add('description', null, [
                'label' => 'Description'
            ])
            ->add('healthDice', null, [
                'label' => 'Dé de vie'
            ])
            ->add('skills', EntityType::class, [
                // champ de selection multiple
                'class' => Skill::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Compétences'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CharacterClass::class,
        ]);
    }
}
