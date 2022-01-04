<?php

namespace App\Entity;

use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PetRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Filesystem\Filesystem;
use Gedmo\Timestampable\Traits\Timestampable;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Controller\HasUserLikedPet;


/**
 * @ORM\Entity(repositoryClass=PetRepository::class)
 * @ApiResource(
 *      itemOperations={
 *                "get",
 *                "hasUserLiked"={
 *                      "method"="POST",
 *                      "path"="/pet/{id}/has-user-liked",
 *                      "controller"=HasUserLikedPet::class
 *                  }
 *              }
 * )
 */
class Pet
{

    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"users:read"})
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=500)
     * @Groups({"users:read"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="pets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $photos = [];

    /**
     * @ORM\OneToMany(targetEntity=PetLike::class, mappedBy="target", orphanRemoval=true)
     * @ApiSubresource(
     *          itemOperations={"get", "post", "delete"}
     * )
     *
     */
    private $likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPhotos(): ?array
    {
        return $this->photos;
    }

    public function setPhotos(?array $photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    public function filterPhotosToRemove(array $photosToRemove = []) : array {
        
        $filteredArray = array_values(array_diff($this->getPhotos() ?? [], $photosToRemove));
        return $filteredArray;
    }

    /**
     * @return Collection|PetLike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(PetLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setTarget($this);
        }

        return $this;
    }

    public function removeLike(PetLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getTarget() === $this) {
                $like->setTarget(null);
            }
        }

        return $this;
    }
}
