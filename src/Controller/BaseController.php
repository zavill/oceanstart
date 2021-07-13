<?php


namespace App\Controller;


use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{

    /**
     * Получение списка всех книг
     *
     * @return BookRepository[]|object[]
     */
    protected function getAllBooksAction()
    {
        return $this->getDoctrine()->getRepository(Book::class)->findAll();
    }

    /**
     * Получение списка всех авторов
     *
     * @return AuthorRepository[]|object[]
     */
    protected function getAllAuthorsAction()
    {
        return $this->getDoctrine()->getRepository(Author::class)->findAll();
    }

}