<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AuthorRepository::class)
 */
class Author
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Book::class, mappedBy="author")
     */
    private $books;

    /**
     * @ORM\ManyToMany(targetEntity=Book::class, mappedBy="coAuthor")
     */
    private $booksCoAuthor;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->booksCoAuthor = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $Name): self
    {
        $this->name = $Name;

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->setAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getAuthor() === $this) {
                $book->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooksCoAuthor(): Collection
    {
        return $this->booksCoAuthor;
    }

    public function addBooksCoAuthor(Book $booksCoAuthor): self
    {
        if (!$this->booksCoAuthor->contains($booksCoAuthor)) {
            $this->booksCoAuthor[] = $booksCoAuthor;
            $booksCoAuthor->addCoAuthor($this);
        }

        return $this;
    }

    public function removeBooksCoAuthor(Book $booksCoAuthor): self
    {
        if ($this->booksCoAuthor->removeElement($booksCoAuthor)) {
            $booksCoAuthor->removeCoAuthor($this);
        }

        return $this;
    }
}
