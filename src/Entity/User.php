<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read_user"})
     * @Groups("post_reader")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"read_user"})
     * @Groups("post_reader")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"read_user"})
     * 
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="createdBy")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="user")
     */
    private $documents;

    /**
     * @ORM\ManyToMany(targetEntity=Post::class, mappedBy="interested")
     */
    private $interests;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read_user"})
     * @Groups("post_reader")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read_user"})
     * @Groups("post_reader")
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"read_user"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read_user"})
     */
    private $classe;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     * @Groups({"read_user"})
     * @Groups("post_reader")
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=Post::class, mappedBy="Lovers")
     */
    private $loves;

    public function __construct(){
        $this->posts = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->interests = new ArrayCollection();
        $this->loves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCreatedBy($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCreatedBy() === $this) {
                $post->setCreatedBy(null);
            }
        }

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
            $document->setUser($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getUser() === $this) {
                $document->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getInterests(): Collection
    {
        return $this->interests;
    }

    public function addInterest(Post $interest): self
    {
        if (!$this->interests->contains($interest)) {
            $this->interests[] = $interest;
            $interest->addInterested($this);
        }

        return $this;
    }

    public function removeInterest(Post $interest): self
    {
        if ($this->interests->removeElement($interest)) {
            $interest->removeInterested($this);
        }

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(?int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(string $classe): self
    {
        $this->classe = $classe;

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

    /**
     * @return Collection|Post[]
     */
    public function getLoves(): Collection
    {
        return $this->loves;
    }

    public function addLove(Post $love): self
    {
        if (!$this->loves->contains($love)) {
            $this->loves[] = $love;
            $love->addLover($this);
        }

        return $this;
    }

    public function removeLove(Post $love): self
    {
        if ($this->loves->removeElement($love)) {
            $love->removeLover($this);
        }

        return $this;
    }
}
