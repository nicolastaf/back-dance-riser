<?php

namespace App\Entity;

use App\Repository\ChoreographyRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ChoreographyRepository::class)
 */
class Choreography
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_choregraphies_get_collection", "api_choreographies_get_item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"api_choregraphies_get_collection", "api_choreographies_get_item"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Groups({"api_choregraphies_get_collection", "api_choreographies_get_item"})
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"api_choregraphies_get_collection", "api_choreographies_get_item"})
     */
    private $image;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_choregraphies_get_collection", "api_choreographies_get_item"})
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Style::class, inversedBy="choreographies")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"api_choregraphies_get_collection", "api_choreographies_get_item"})
     */
    private $style;

    /**
     * @ORM\ManyToOne(targetEntity=School::class, inversedBy="choreographies")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"api_choreographies_get_item", "api_choregraphies_get_collection"})
     */
    private $school;

    /**
     * @ORM\OneToMany(targetEntity=ChoreographyPart::class, mappedBy="choreography", cascade={"persist", "remove"})
     * @Groups({"api_choreographies_get_item"})
     * @ORM\OrderBy({"orderChoreo"="ASC"})
     */
    private $choreographyParts;

    public function __construct()
    {
        $this->choreographyParts = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getStyle(): ?Style
    {
        return $this->style;
    }

    public function setStyle(?Style $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): self
    {
        $this->school = $school;

        return $this;
    }

    /**
     * @return Collection<int, ChoreographyPart>
     */
    public function getChoreographyParts(): Collection
    {
        return $this->choreographyParts;
    }

    public function addChoreographyPart(ChoreographyPart $choreographyPart): self
    {
        if (!$this->choreographyParts->contains($choreographyPart)) {
            $this->choreographyParts[] = $choreographyPart;
            $choreographyPart->setChoreography($this);
        }

        return $this;
    }

    public function removeChoreographyPart(ChoreographyPart $choreographyPart): self
    {
        if ($this->choreographyParts->removeElement($choreographyPart)) {
            // set the owning side to null (unless already changed)
            if ($choreographyPart->getChoreography() === $this) {
                $choreographyPart->setChoreography(null);
            }
        }

        return $this;
    }
}
