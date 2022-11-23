<?php

namespace App\Entity;

use App\Repository\SchoolRepository;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SchoolRepository::class)
 */
class School
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_schools_get_item", "app_api_member_post_item", "api_users_get_item", "api_schools_get_collection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"api_schools_get_item", "api_schools_get_collection", "api_choregraphies_get_collection", "api_users_get_item", "api_moves_get_collection", "api_moves_get_item"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_schools_get_item", "api_schools_get_collection", "api_users_get_item"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Groups({"api_schools_get_item", "api_schools_get_collection"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     * @Groups({"api_schools_get_item"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     * @Groups({"api_schools_get_item"})
     */
    private $phone;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups({"api_schools_get_item"})
     */
    private $lessonType = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups({"api_schools_get_item"})
     */
    private $openTo = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"api_schools_get_item", "api_schools_get_collection"})
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activated;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="school", cascade={"persist", "remove"})
     * @Groups({"api_schools_get_collection", "api_schools_get_item"})
     */
    private $locations;

    /**
     * @ORM\OneToMany(targetEntity=Choreography::class, mappedBy="school")
     */
    private $choreographies;

    /**
     * @ORM\OneToMany(targetEntity=Member::class, mappedBy="school", cascade={"persist", "remove"})
     */
    private $members;

    /**
     * @ORM\ManyToMany(targetEntity=Level::class, inversedBy="schools")
     * @Groups({"api_schools_get_item"})
     */
    private $level;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $newRequest;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentRequest;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"api_users_get_item", "api_schools_get_item", "api_schools_get_collection"})
     */
    private $agendaLink;

    /**
     * @ORM\OneToMany(targetEntity=Move::class, mappedBy="school")
     */
    private $moves;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->choreographies = new ArrayCollection();
        $this->members = new ArrayCollection();
        $this->level = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->activated = true;
        $this->moves = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getLessonType(): ?array
    {
        return $this->lessonType;
    }

    public function setLessonType(?array $lessonType): self
    {
        $this->lessonType = $lessonType;

        return $this;
    }

    public function getOpenTo(): ?array
    {
        return $this->openTo;
    }

    public function setOpenTo(?array $openTo): self
    {
        $this->openTo = $openTo;

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

    public function isActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

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
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
            $location->setSchool($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getSchool() === $this) {
                $location->setSchool(null);
            }
        }

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
            $choreography->setSchool($this);
        }

        return $this;
    }

    public function removeChoreography(Choreography $choreography): self
    {
        if ($this->choreographies->removeElement($choreography)) {
            // set the owning side to null (unless already changed)
            if ($choreography->getSchool() === $this) {
                $choreography->setSchool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Member>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setSchool($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getSchool() === $this) {
                $member->setSchool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Level>
     */
    public function getLevel(): Collection
    {
        return $this->level;
    }

    public function addLevel(Level $level): self
    {
        if (!$this->level->contains($level)) {
            $this->level[] = $level;
        }

        return $this;
    }

    public function removeLevel(Level $level): self
    {
        $this->level->removeElement($level);

        return $this;
    }

    public function isNewRequest(): ?bool
    {
        return $this->newRequest;
    }

    public function setNewRequest(bool $newRequest): self
    {
        $this->newRequest = $newRequest;

        return $this;
    }

    public function getCommentRequest(): ?string
    {
        return $this->commentRequest;
    }

    public function setCommentRequest(?string $commentRequest): self
    {
        $this->commentRequest = $commentRequest;

        return $this;
    }

    public function getAgendaLink(): ?string
    {
        return $this->agendaLink;
    }

    public function setAgendaLink(?string $agendaLink): self
    {
        $this->agendaLink = $agendaLink;

        return $this;
    }

    /**
     * @return Collection<int, Move>
     */
    public function getMoves(): Collection
    {
        return $this->moves;
    }

    public function addMove(Move $move): self
    {
        if (!$this->moves->contains($move)) {
            $this->moves[] = $move;
            $move->setSchool($this);
        }

        return $this;
    }

    public function removeMove(Move $move): self
    {
        if ($this->moves->removeElement($move)) {
            // set the owning side to null (unless already changed)
            if ($move->getSchool() === $this) {
                $move->setSchool(null);
            }
        }

        return $this;
    }
}
