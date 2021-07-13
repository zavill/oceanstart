<?php


namespace App\Controller\API;


use App\Entity\Author;
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
                $dateObj = \DateTime::createFromFormat('d.m.Y H:i:s', $publishDate . ' 00:00:00');
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

    /**
     * Обновление информации о книге
     *
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateBook($id): JsonResponse
    {
        try {
            if (!$this->isGranted('ROLE_ADMIN')) {
                throw new APIException(
                    'Недостаточно прав для изменения книги',
                    Response::HTTP_FORBIDDEN
                );
            }

            $book = $this->getDoctrine()->getRepository(\App\Entity\Book::class)->find($id);
            if (!$book instanceof \App\Entity\Book) {
                throw new APIException(
                    'Указанная книга не найдена',
                    Response::HTTP_NOT_FOUND
                );
            }

            if (!$name = $this->request->get('name')) {
                throw new APIException(
                    'Не указано название книги',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            } else {
                $book->setName($name);
            }

            $authorsRepository = $this->getDoctrine()->getRepository(Author::class);

            if (!$author = $this->request->get('author')) {
                throw new APIException(
                    'Не указан автор книги',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            } else {
                $author = $authorsRepository->find($author);
                if (!$author instanceof Author) {
                    throw new APIException(
                        'Указанный автор не найден',
                        Response::HTTP_NOT_FOUND
                    );
                }
                $book->setAuthor($author);
            }

            if ($coAuthors = $this->request->get('coAuthor')) {
                $book->removeAllCoAuthors();
                if (!empty($coAuthors[0])) { // Если соавторы указаны
                    foreach ($coAuthors as $coAuthor) {
                        $coAuthor = $authorsRepository->find($coAuthor);
                        if (!$coAuthor instanceof Author) {
                            throw new APIException(
                                'Указанный автор не найден',
                                Response::HTTP_NOT_FOUND
                            );
                        } else {
                            $book->addCoAuthor($coAuthor);
                        }
                    }
                }
            }
            if (!$shortDescription = $this->request->get('shortDescription')) {
                throw new APIException(
                    'Не указано короткое описание книги',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            } else {
                $book->setShortDescription($shortDescription);
            }

            if (!$publishDate = $this->request->get('publishDate')) {
                throw new APIException(
                    'Не указана дата публикации книги',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            } else {
                $dateObj = \DateTime::createFromFormat('d.m.Y H:i:s', $publishDate . ' 00:00:00');
                if ($dateObj) {
                    $book->setPublishDate($dateObj);
                } else {
                    throw new APIException(
                        'Некорректно указана дата',
                        Response::HTTP_UNPROCESSABLE_ENTITY
                    );
                }
            }

            $this->entityManager->persist($book);
            $this->entityManager->flush();

            return new JsonResponse(
                [
                    'data' => $book->getId()
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

    /**
     * Создание книги
     *
     * @Route("/{id}", methods={"POST"})
     */
    public function createBook($id): JsonResponse
    {
        try {
            if (!$this->isGranted('ROLE_ADMIN')) {
                throw new APIException(
                    'Недостаточно прав для создания книги',
                    Response::HTTP_FORBIDDEN
                );
            }

            $book = new \App\Entity\Book();

            if (!$name = $this->request->get('name')) {
                throw new APIException(
                    'Не указано название книги',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            } else {
                $book->setName($name);
            }

            $authorsRepository = $this->getDoctrine()->getRepository(Author::class);

            if (!$author = $this->request->get('author')) {
                throw new APIException(
                    'Не указан автор книги',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            } else {
                $author = $authorsRepository->find($author);
                if (!$author instanceof Author) {
                    throw new APIException(
                        'Указанный автор не найден',
                        Response::HTTP_NOT_FOUND
                    );
                }
                $book->setAuthor($author);
            }

            if ($coAuthors = $this->request->get('coAuthor')) {
                $book->removeAllCoAuthors();
                if (!empty($coAuthors[0])) { // Если соавторы указаны
                    foreach ($coAuthors as $coAuthor) {
                        $coAuthor = $authorsRepository->find($coAuthor);
                        if (!$coAuthor instanceof Author) {
                            throw new APIException(
                                'Указанный автор не найден',
                                Response::HTTP_NOT_FOUND
                            );
                        } else {
                            $book->addCoAuthor($coAuthor);
                        }
                    }
                }
            }
            if (!$shortDescription = $this->request->get('shortDescription')) {
                throw new APIException(
                    'Не указано короткое описание книги',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            } else {
                $book->setShortDescription($shortDescription);
            }

            if (!$publishDate = $this->request->get('publishDate')) {
                throw new APIException(
                    'Не указана дата публикации книги',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            } else {
                $dateObj = \DateTime::createFromFormat('d.m.Y H:i:s', $publishDate . ' 00:00:00');
                if ($dateObj) {
                    $book->setPublishDate($dateObj);
                } else {
                    throw new APIException(
                        'Некорректно указана дата',
                        Response::HTTP_UNPROCESSABLE_ENTITY
                    );
                }
            }

            $this->entityManager->persist($book);
            $this->entityManager->flush();

            return new JsonResponse(
                [
                    'data' => $book->getId()
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

    /**
     * Удаление книги
     *
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteBook($id): JsonResponse {
        try {
            if (!$this->isGranted('ROLE_ADMIN')) {
                throw new APIException(
                    'Недостаточно прав для изменения книги',
                    Response::HTTP_FORBIDDEN
                );
            }

            $book = $this->getDoctrine()->getRepository(\App\Entity\Book::class)->find($id);
            if (!$book instanceof \App\Entity\Book) {
                throw new APIException(
                    'Указанная книга не найдена',
                    Response::HTTP_NOT_FOUND
                );
            }

            $this->entityManager->remove($book);
            $this->entityManager->flush();

            return new JsonResponse(
                [
                    'data' => -1
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