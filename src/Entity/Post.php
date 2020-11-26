<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post_reader")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     * @Groups("post_reader")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post_reader")
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=Document::class, inversedBy="posts")
     */
    private $documents;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @Groups("post_reader")
     */
    private $createdBy;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="interests")
     * @Groups("post_reader")
     */
    private $interested;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("post_reader")
     */
    private $title;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("post_reader")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups("post_reader")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     * @Groups("post_reader")
     */
    private $image;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->interested = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        $this->documents->removeElement($document);

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getInterested(): Collection
    {
        return $this->interested;
    }

    public function addInterested(User $interested): self
    {
        if (!$this->interested->contains($interested)) {
            $this->interested[] = $interested;
        }

        return $this;
    }

    public function removeInterested(User $interested): self
    {
        $this->interested->removeElement($interested);
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

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
}
