<?php


namespace App\Controller;


use App\Entity\Book;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends BaseController
{
    /**
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

}