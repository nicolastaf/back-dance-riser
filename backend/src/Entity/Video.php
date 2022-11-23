<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\VideoRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=VideoRepository::class)
 */
class Video
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"api_choreographies_get_item", "api_moves_get_item"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_choreographies_get_item", "api_moves_get_item"})
     */
    private $link;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"api_choreographies_get_item", "api_moves_get_item"})
     */
    private $orderPart;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=ChoreographyPart::class, inversedBy="videos")
     */
    private $choreographyPart;

    /**
     * @ORM\ManyToOne(targetEntity=Move::class, inversedBy="videos")
     */
    private $move;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getOrderPart(): ?int
    {
        return $this->orderPart;
    }

    public function setOrderPart(int $orderPart): self
    {
        $this->orderPart = $orderPart;

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

    public function getChoreographyPart(): ?ChoreographyPart
    {
        return $this->choreographyPart;
    }

    public function setChoreographyPart(?ChoreographyPart $choreographyPart): self
    {
        $this->choreographyPart = $choreographyPart;

        return $this;
    }

    public function getMove(): ?Move
    {
        return $this->move;
    }

    public function setMove(?Move $move): self
    {
        $this->move = $move;

        return $this;
    }
}
