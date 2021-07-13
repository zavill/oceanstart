<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity=Author::class, inversedBy="booksCoAuthor")
     */
    private $coAuthor;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shortDescription;

    /**
     * @ORM\Column(type="date")
     */
    private $publishDate;

    public function __construct()
    {
        $this->coAuthor = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getCoAuthor(): Collection
    {
        return $this->coAuthor;
    }

    public function addCoAuthor(Author $coAuthor): self
    {
        if (!$this->coAuthor->contains($coAuthor)) {
            $this->coAuthor[] = $coAuthor;
        }

        return $this;
    }

    public function removeCoAuthor(Author $coAuthor): self
    {
        $this->coAuthor->removeElement($coAuthor);

        return $this;
    }

    public function removeAllCoAuthors(): self
    {
        $this->coAuthor->clear();

        return $this;
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

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publishDate;
    }

    public function setPublishDate(\DateTimeInterface $publishDate): self
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    public function toJson(): array
    {
        $result = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'shortDescription' => $this->getShortDescription(),
            'publishDate' => $this->getPublishDate()->format('d.m.Y'),
            'author' => $this->getAuthor()->getName()
        ];

        foreach ($this->coAuthor as $coAuthor) {
            $result['coAuthor'][] = $coAuthor->getName();
        }

        return $result;
    }
}
