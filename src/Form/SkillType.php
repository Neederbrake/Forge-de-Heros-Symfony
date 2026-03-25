<?php

namespace App\Form;

use App\Entity\CharacterClass;
use App\Entity\Skill;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // construit le formulaire de competence
        $builder
            ->add('name', null, [
                'label' => 'Nom de la compétence'
            ])
            ->add('ability', null, [
                'label' => 'Caractéristique associée'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Skill::class,
        ]);
    }
}
