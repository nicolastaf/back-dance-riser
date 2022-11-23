<?php

namespace App\Entity;


use DateTimeImmutable;
use App\Repository\StyleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=StyleRepository::class)
 */
class Style
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_choregraphies_get_collection", "api_choreographies_get_item", "api_styles_get_collection"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_choregraphies_get_collection", "api_choreographies_get_item", "api_styles_get_collection"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"api_styles_get_collection"})
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
     * @ORM\OneToMany(targetEntity=Choreography::class, mappedBy="style")
     */
    private $choreographies;

    /**
     * @ORM\OneToMany(targetEntity=CategoryMove::class, mappedBy="style")
     */
    private $categoryMoves;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activated;

    public function __construct()
    {
        $this->choreographies = new ArrayCollection();
        $this->categoryMoves = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    /**
     * @return Collection<int, Choreography>
     */
    public function getChoreographies(): Collection
    {
        return $this->choreographies;
    }

    public function addChoreography(Choreography $choreography): self
    {
        if (!$this->choreographies->contains($choreography)) {
            $this->choreographies[] = $choreography;
            $choreography->setStyle($this);
        }

        return $this;
    }

    public function removeChoreography(Choreography $choreography): self
    {
        if ($this->choreographies->removeElement($choreography)) {
            // set the owning side to null (unless already changed)
            if ($choreography->getStyle() === $this) {
                $choreography->setStyle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CategoryMove>
     */
    public function getCategoryMoves(): Collection
    {
        return $this->categoryMoves;
    }

    public function addCategoryMove(CategoryMove $categoryMove): self
    {
        if (!$this->categoryMoves->contains($categoryMove)) {
            $this->categoryMoves[] = $categoryMove;
            $categoryMove->setStyle($this);
        }

        return $this;
    }

    public function removeCategoryMove(CategoryMove $categoryMove): self
    {
        if ($this->categoryMoves->removeElement($categoryMove)) {
            // set the owning side to null (unless already changed)
            if ($categoryMove->getStyle() === $this) {
                $categoryMove->setStyle(null);
            }
        }

        return $this;
    }

    public function isActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }
}
