<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\ChoreographyPartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ChoreographyPartRepository::class)
 */
class ChoreographyPart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_choregraphies_get_collection", "api_choreographies_get_item"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"api_choregraphies_get_collection", "api_choreographies_get_item"})
     */
    private $orderChoreo;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Choreography::class, inversedBy="choreographyParts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $choreography;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="choreographyPart", cascade={"persist", "remove"})
     * @Groups({"api_choreographies_get_item"})
     * @ORM\OrderBy({"orderPart"="ASC"})
     */
    private $videos;

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

    public function getOrderChoreo(): ?int
    {
        return $this->orderChoreo;
    }

    public function setOrderChoreo(int $orderChoreo): self
    {
        $this->orderChoreo = $orderChoreo;

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

    public function getChoreography(): ?Choreography
    {
        return $this->choreography;
    }

    public function setChoreography(?Choreography $choreography): self
    {
        $this->choreography = $choreography;

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
            $video->setChoreographyPart($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getChoreographyPart() === $this) {
                $video->setChoreographyPart(null);
            }
        }

        return $this;
    }
}
