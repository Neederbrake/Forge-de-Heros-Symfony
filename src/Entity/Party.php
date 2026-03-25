<?php

namespace App\Entity;

use App\Repository\PartyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PartyRepository::class)]
class Party
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['party:read', 'character:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['party:read', 'character:read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['party:read'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['party:read'])]
    private ?int $maxSize = null;

    // correction ici : User avec majuscule
    #[ORM\ManyToOne(inversedBy: 'parties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator = null;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\ManyToMany(targetEntity: Character::class, inversedBy: 'parties')]
    #[Groups(['party:read'])]
    private Collection $characters;

    public function __construct()
    {
        // initialise la liste des personnages
        $this->characters = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMaxSize(): ?int
    {
        return $this->maxSize;
    }

    public function setMaxSize(int $maxSize): static
    {
        $this->maxSize = $maxSize;

        return $this;
    }

    // correction ici : retour de type User
    public function getCreator(): ?User
    {
        return $this->creator;
    }

    // correction ici : argument de type User
    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection<int, Character>
     */
    public function getCharacters(): Collection
    {
        // retourne les personnages du groupe
        return $this->characters;
    }

    public function addCharacter(Character $character): static
    {
        // ajoute un personnage au groupe
        if (!$this->characters->contains($character)) {
            $this->characters->add($character);
        }

        return $this;
    }

    public function removeCharacter(Character $character): static
    {
        // retire un personnage du groupe
        $this->characters->removeElement($character);

        return $this;
    }
}