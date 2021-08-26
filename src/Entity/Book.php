<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
#[ApiResource(
    collectionOperations: ['get','post'],
    itemOperations: ['get','post','delete'],
)]
class Book
{
    /**
     * @Groups({"read"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $ISBN;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @Groups({"read", "write"})
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="books")
     */
    private $user;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime")
     */
    private $publicationDate;

    /**
     * Returns user object
     * 
     * @Groups({"read"})
     */
    private $userObject  = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getISBN(): ?string
    {
        return $this->ISBN;
    }

    public function setISBN(string $ISBN): self
    {
        $this->ISBN = $ISBN;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getUserObject(): ?array
    {
        return $this->userObject;
    }

    public function setUserObject(array $userObject): self
    {
        $this->userObject = $userObject;

        return $this;
    }
}
