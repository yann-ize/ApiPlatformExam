<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['departement:read']],
            // security: "is_authenticated()"
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['departement:read:collection']],
            // security: "is_authenticated()"
        ),
        new Post(
            normalizationContext: ['groups' => ['departement:read']],
            denormalizationContext: ['groups' => ['departement:write']],
            // security: "is_authenticated()"
        ),
        new Put(
            normalizationContext: ['groups' => ['departement:read']],
            denormalizationContext: ['groups' => ['departement:write']],
            // security: "is_authenticated()"
        ),
        new Delete(
            // security: "is_authenticated()"
        )
    ]
)]

#[ApiFilter(SearchFilter::class, properties: [
    'region' => 'exact',
    'numero' => 'exact',
    'label' => 'partial',
    'mairies.codePostal' => 'exact'
])]

class Departement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['departement:read', 'departement:read:collection'])]
    private ?int $id = null;

    #[ORM\Column(length: 3)]
    #[Groups(['departement:read', 'departement:read:collection', 'departement:write'])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 3)]
    #[Assert\Type(type: 'string')]
    private ?string $numero = null;

    #[ORM\Column(length: 100)]
    #[Groups(['departement:read', 'departement:read:collection', 'departement:write'])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 100)]
    #[Assert\Type(type: 'string')]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    #[Groups(['departement:read', 'departement:read:collection', 'departement:write'])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 255)]
    #[Assert\Type(type: 'string')]
    private ?string $region = null;

    #[ORM\OneToMany(targetEntity: Mairie::class, mappedBy: 'departement', orphanRemoval: true)]
    //#[Groups(['departement:read', 'departement:read:collection', 'departement:write'])]
    private Collection $mairies;

    public function __construct()
    {
        $this->mairies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;
        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;
        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return Collection<int, Mairie>
     */
    public function getMairies(): Collection
    {
        return $this->mairies;
    }

    public function addMairie(Mairie $mairie): static
    {
        if (!$this->mairies->contains($mairie)) {
            $this->mairies->add($mairie);
            $mairie->setDepartement($this);
        }

        return $this;
    }

    public function removeMairie(Mairie $mairie): static
    {
        if ($this->mairies->removeElement($mairie)) {
            if ($mairie->getDepartement() === $this) {
                $mairie->setDepartement(null);
            }
        }

        return $this;
    }
}
