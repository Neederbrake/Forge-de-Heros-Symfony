<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['skill:read', 'class:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['skill:read', 'class:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['skill:read', 'class:read'])]
    private ?string $ability = null;

    /**
     * @var Collection<int, CharacterClass>
     */
    #[ORM\ManyToMany(targetEntity: CharacterClass::class, mappedBy: 'skills')]
    private Collection $characterClasses;

    public function __construct()
    {
        $this->characterClasses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAbility(): ?string
    {
        return $this->ability;
    }

    public function setAbility(string $ability): static
    {
        $this->ability = $ability;

        return $this;
    }

    /**
     * @return Collection<int, CharacterClass>
     */
    public function getCharacterClasses(): Collection
    {
        // retourne les classes liees a cette competence
        return $this->characterClasses;
    }

    public function addCharacterClass(CharacterClass $characterClass): static
    {
        // ajoute la classe et met a jour le lien inverse
        if (!$this->characterClasses->contains($characterClass)) {
            $this->characterClasses->add($characterClass);
            $characterClass->addSkill($this);
        }

        return $this;
    }

    public function removeCharacterClass(CharacterClass $characterClass): static
    {
        // retire la classe et nettoie le lien inverse
        if ($this->characterClasses->removeElement($characterClass)) {
            $characterClass->removeSkill($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        // utilise getname pour afficher la competence
        return $this->getName() ?? '';
    }
}
