<?php

namespace App\Entity;

use App\Repository\MoveRepository;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MoveRepository::class)
 */
class Move
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_moves_get_collection", "api_moves_get_item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"api_moves_get_collection", "api_moves_get_item"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Groups({"api_moves_get_collection", "api_moves_get_item"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_moves_get_collection", "api_moves_get_item"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"api_moves_get_collection", "api_moves_get_item"})
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visibility;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="move", cascade={"persist", "remove"})
     * @Groups({"api_moves_get_item"})
     * @ORM\OrderBy({"orderPart"="ASC"})
     */
    private $videos;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryMove::class, inversedBy="moves")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"api_moves_get_collection", "api_moves_get_item"})
     */
    private $categoryMove;

    /**
     * @ORM\ManyToOne(targetEntity=Level::class, inversedBy="moves")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"api_moves_get_collection", "api_moves_get_item"})
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity=School::class, inversedBy="moves")
     * @Groups({"api_moves_get_collection", "api_moves_get_item"})
     */
    private $school;

    public function __construct()
    {
        $this->videos = new ArrayCollection();
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

    public function isVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): self
    {
        $this->visibility = $visibility;

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
     * @return Collection<int, Video>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setMove($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getMove() === $this) {
                $video->setMove(null);
            }
        }

        return $this;
    }

    public function getCategoryMove(): ?CategoryMove
    {
        return $this->categoryMove;
    }

    public function setCategoryMove(?CategoryMove $categoryMove): self
    {
        $this->categoryMove = $categoryMove;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): self
    {
        $this->level = $level;

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
}
