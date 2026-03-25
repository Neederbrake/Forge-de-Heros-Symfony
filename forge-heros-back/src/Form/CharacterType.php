<?php

namespace App\Form;

use App\Entity\Character;
use App\Entity\CharacterClass;
use App\Entity\Race;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom'
            ])
            ->add('level', null, [
                'label' => 'Niveau'
            ])
            // bride les champs html entre 8 et 15
            ->add('strength', IntegerType::class, [
                'label' => 'Force',
                'attr' => ['min' => 8, 'max' => 15]
            ])
            ->add('dexterity', IntegerType::class, [
                'label' => 'Dextérité',
                'attr' => ['min' => 8, 'max' => 15]
            ])
            ->add('constitution', IntegerType::class, [
                'label' => 'Constitution',
                'attr' => ['min' => 8, 'max' => 15]
            ])
            ->add('intelligence', IntegerType::class, [
                'label' => 'Intelligence',
                'attr' => ['min' => 8, 'max' => 15]
            ])
            ->add('wisdom', IntegerType::class, [
                'label' => 'Sagesse',
                'attr' => ['min' => 8, 'max' => 15]
            ])
            ->add('charisma', IntegerType::class, [
                'label' => 'Charisme',
                'attr' => ['min' => 8, 'max' => 15]
            ])
            // on ne demande plus les pv, le serveur les calcule !

            // securise le champ image
            ->add('image', FileType::class, [
                'label' => 'Image (avatar)',
                'mapped' => false,
                'required' => false,
            ])
            ->add('race', EntityType::class, [
                'class' => Race::class,
                'choice_label' => 'name',
                'label' => 'Race',
            ])
            ->add('characterClass', EntityType::class, [
                'class' => CharacterClass::class,
                'choice_label' => 'name',
                'label' => 'Classe',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
        ]);
    }
}