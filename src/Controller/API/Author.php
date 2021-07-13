<?php


namespace App\Controller\API;


use App\Exception\APIException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * API контроллер книг
 *
 * @Route("/author")
 * Class Author
 * @package App\Controller\API
 */
class Author extends AbstractAPI
{

    /**
     * Обновление информации об авторе
     *
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateAuthor($id): JsonResponse
    {
        try {

            $author = $this->getDoctrine()->getRepository(\App\Entity\Author::class)->find($id);
            if (!$author instanceof \App\Entity\Author) {
                throw new APIException(
                    'Указанный автор не найден',
                    Response::HTTP_NOT_FOUND
                );
            }

            if (!$name = $this->request->get('name')) {
                throw new APIException(
                    'Не указано имя автора',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            } else {
                $author->setName($name);
            }

            $this->entityManager->persist($author);
            $this->entityManager->flush();

            return new JsonResponse(
                [
                    'data' => $author->getId()
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
     * Добавление автора
     *
     * @Route("/", methods={"POST"})
     */
    public function addAuthor(): JsonResponse
    {
        try {
            if (!$this->isGranted('ROLE_ADMIN')) {
                throw new APIException(
                    'Недостаточно прав для добавления автора',
                    Response::HTTP_FORBIDDEN
                );
            }

            $author = new \App\Entity\Author();

            if (!$name = $this->request->get('name')) {
                throw new APIException(
                    'Не указано имя автора',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            } else {
                $author->setName($name);
            }

            $this->entityManager->persist($author);
            $this->entityManager->flush();

            return new JsonResponse(
                [
                    'data' => $author->getId()
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
     * Удаление автора
     *
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteAuthor($id): JsonResponse {
        try {
            if (!$this->isGranted('ROLE_ADMIN')) {
                throw new APIException(
                    'Недостаточно прав для удаления автора',
                    Response::HTTP_FORBIDDEN
                );
            }

            $author = $this->getDoctrine()->getRepository(\App\Entity\Author::class)->find($id);
            if (!$author instanceof \App\Entity\Author) {
                throw new APIException(
                    'Указанный автор не найден',
                    Response::HTTP_NOT_FOUND
                );
            }

            $this->entityManager->remove($author);
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