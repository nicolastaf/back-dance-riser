<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MemberRepository::class)
 */
class Member
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"app_api_member_post_item"})
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activated;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="members")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"app_api_member_post_item"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=School::class, inversedBy="members")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"app_api_member_post_item", "api_users_get_item"})
     */
    private $school;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $newRequest;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function isNewRequest(): ?bool
    {
        return $this->newRequest;
    }

    public function setNewRequest(bool $newRequest): self
    {
        $this->newRequest = $newRequest;

        return $this;
    }
}
