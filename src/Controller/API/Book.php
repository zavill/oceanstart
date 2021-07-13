<?php


namespace App\Controller\API;


use App\Exception\APIException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * API контроллер книг
 *
 * @Route("/book")
 * Class Book
 * @package App\Controller\API
 */
class Book extends AbstractAPI
{

    /**
     * Получение всех книг с фильтрацией
     *
     * @Route("/", methods={"GET"})
     */
    public function getBooks(): JsonResponse
    {
        try {
            $sortField = $this->request->get('sortField');
            if ($sortField === 'id' || $sortField === 'coAuthor') {
                throw new APIException(
                    'Некорректно указано поле сортировки',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $orderBy = ($sortField ? [$sortField => 'ASC'] : []);

            $filter = [];
            if ($name = $this->request->get('name')) {
                $filter['name'] = $name;
            }
            if ($author = $this->request->get('author')) {
                $filter['author'] = $author;
            }
            if ($coAuthors = $this->request->get('coAuthor')) {
                $filter['coAuthor'] = $coAuthors;
            }
            if ($shortDescription = $this->request->get('shortDescription')) {
                $filter['shortDescription'] = $shortDescription;
            }
            if ($publishDate = $this->request->get('publishDate')) {
                $dateObj = \DateTime::createFromFormat('d-m-Y', $publishDate);
                if ($dateObj) {
                    $filter['publishDate'] = $dateObj;
                } else {
                    throw new APIException(
                        'Некорректно указана дата',
                        Response::HTTP_UNPROCESSABLE_ENTITY
                    );
                }
            }

            $bookRepository = $this->getDoctrine()->getRepository(\App\Entity\Book::class);
            $books = $bookRepository->findBy($filter, $orderBy);

            foreach ($books as $key => $book) {
                $books[$key] = $book->toJson();
            }

            return new JsonResponse(
                [
                    'data' => $books
                ]
            );
        } catch (APIException $exception) {
            return new JsonResponse(
                [
                    'error' => $exception->getMessage()
                ],
                $exception->getCode()
            );
        }
    }

}