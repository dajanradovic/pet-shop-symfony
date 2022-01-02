<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\Timestampable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Timestampable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Pet::class, mappedBy="user", orphanRemoval=true)
     */
    private $pets;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="create")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $Age;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Interests;

    /**
     * @ORM\OneToMany(targetEntity=UserLike::class, mappedBy="target", orphanRemoval=true)
     */
    private $createdLikes;

    /**
     * @ORM\OneToMany(targetEntity=UserLike::class, mappedBy="author", orphanRemoval=true)
     */
    private $receivedLikes;

    /**
     * @ORM\OneToMany(targetEntity=PetLike::class, mappedBy="author", orphanRemoval=true)
     */
    private $petLikes;

    public function __construct()
    {
        $this->pets = new ArrayCollection();
        $this->createdLikes = new ArrayCollection();
        $this->receivedLikes = new ArrayCollection();
        $this->petLikes = new ArrayCollection();
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
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Pet[]
     */
    public function getPets(): Collection
    {
        return $this->pets;
    }

    public function addPet(Pet $pet): self
    {
        if (!$this->pets->contains($pet)) {
            $this->pets[] = $pet;
            $pet->setUser($this);
        }

        return $this;
    }

    public function removePet(Pet $pet): self
    {
        if ($this->pets->removeElement($pet)) {
            // set the owning side to null (unless already changed)
            if ($pet->getUser() === $this) {
                $pet->setUser(null);
            }
        }

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->Age;
    }

    public function setAge(?int $Age): self
    {
        $this->Age = $Age;

        return $this;
    }

    public function getInterests(): ?string
    {
        return $this->Interests;
    }

    public function setInterests(?string $Interests): self
    {
        $this->Interests = $Interests;

        return $this;
    }

    /**
     * @return Collection|UserLike[]
     */
    public function getCreatedLikes(): Collection
    {
        return $this->createdLikes;
    }

    public function addCreatedLike(UserLike $createdLike): self
    {
        if (!$this->createdLikes->contains($createdLike)) {
            $this->createdLikes[] = $createdLike;
            $createdLike->setTarget($this);
        }

        return $this;
    }

    public function removeCreatedLike(UserLike $createdLike): self
    {
        if ($this->createdLikes->removeElement($createdLike)) {
            // set the owning side to null (unless already changed)
            if ($createdLike->getTarget() === $this) {
                $createdLike->setTarget(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserLike[]
     */
    public function getReceivedLikes(): Collection
    {
        return $this->receivedLikes;
    }

    public function addReceivedLike(UserLike $receivedLike): self
    {
        if (!$this->receivedLikes->contains($receivedLike)) {
            $this->receivedLikes[] = $receivedLike;
            $receivedLike->setAuthor($this);
        }

        return $this;
    }

    public function removeReceivedLike(UserLike $receivedLike): self
    {
        if ($this->receivedLikes->removeElement($receivedLike)) {
            // set the owning side to null (unless already changed)
            if ($receivedLike->getAuthor() === $this) {
                $receivedLike->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PetLike[]
     */
    public function getPetLikes(): Collection
    {
        return $this->petLikes;
    }

    public function addPetLike(PetLike $petLike): self
    {
        if (!$this->petLikes->contains($petLike)) {
            $this->petLikes[] = $petLike;
            $petLike->setAuthor($this);
        }

        return $this;
    }

    public function removePetLike(PetLike $petLike): self
    {
        if ($this->petLikes->removeElement($petLike)) {
            // set the owning side to null (unless already changed)
            if ($petLike->getAuthor() === $this) {
                $petLike->setAuthor(null);
            }
        }

        return $this;
    }
}
