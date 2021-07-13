<?php


namespace App\Controller;


use App\Entity\Author;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends BaseController
{
    /**
     * Главная страница админ-панели
     *
     * @Route("/admin/", name="admin-panel")
     * @return Response
     */
    public function index(): Response
    {
        $params = [
            'books' => $this->getAllBooksAction(),
            'authors' => $this->getAllAuthorsAction()
        ];

        return $this->render('admin.index.html.twig', $params);
    }

    /**
     * Детальная информация о книге
     *
     * @Route("/admin/book/{id}/", name="admin-detail-book")
     * @param $id
     * @return Response
     */
    public function detailBookController($id): Response
    {
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        if (!$book instanceof Book) {
            return $this->redirectToRoute('admin-panel');
        }

        $params = [
            'book' => $book,
            'authors' => $this->getAllAuthorsAction()
        ];

        return $this->render('admin.detail.book.html.twig', $params);
    }

    /**
     * Добавление книги
     *
     * @Route("/admin/add-book", name="admin-add-book")
     * @return Response
     */
    public function addBookController(): Response
    {
        $params = [
            'authors' => $this->getAllAuthorsAction()
        ];
        return $this->render('admin.create.book.html.twig', $params);
    }

    /**
     * Страница с авторами
     *
     * @Route("/admin/authors", name="admin-authors")
     * @return Response
     */
    public function authorsController(): Response
    {
        $params = [
            'authors' => $this->getAllAuthorsAction()
        ];
        return $this->render('admin.authors.html.twig', $params);
    }

    /**
     * Детальная информация об авторе
     *
     * @Route("/admin/author/{id}/", name="admin-detail-author")
     * @param $id
     * @return Response
     */
    public function detailAuthorController($id): Response
    {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        if (!$author instanceof Author) {
            return $this->redirectToRoute('admin-authors');
        }

        $params = [
            'author' => $author
        ];

        return $this->render('admin.detail.author.html.twig', $params);
    }

    /**
     * Добавление автора
     *
     * @Route("/admin/add-author", name="admin-add-author")
     * @return Response
     */
    public function addAuthorController(): Response
    {
        return $this->render('admin.create.author.html.twig');
    }
}