<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{

    /**
     * Контроллер главной страницы
     *
     * @Route("/", name="home")
     * @return Response
     */
    public function index(): Response
    {
        $params = [
            'books' => $this->getAllBooksAction(),
            'authors' => $this->getAllAuthorsAction()
        ];

        return $this->render('index.html.twig', $params);
    }

}