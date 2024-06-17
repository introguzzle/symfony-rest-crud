<?php

namespace App\Controller;

use App\Controller\Core\RestController;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Request\Book\CreateRequest;
use App\Request\Core\AuthorizedRequest;
use App\Response\RestResponse;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/books')]
class BookController extends RestController
{
    protected BookRepository $bookRepository;
    protected EntityManager $entityManager;

    public function __construct(
        BookRepository $bookRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->bookRepository = $bookRepository;
        $this->entityManager = $entityManager;
    }


    #[Route('/{id}', name: 'api_books_get', methods: ['GET'], stateless: true)]
    public function get(
        int $id,
        AuthorizedRequest $request
    ): JsonResponse
    {
        $user = $request->retrieveUser();
        $book = $this->bookRepository->find($id);

        if (!$book || $book->getUser()->getId() !== $user->getId()) {
            return RestResponse::notFound();
        }

        return RestResponse::success([
            'id'           => $book->getId(),
            'title'        => $book->getTitle(),
            'author'       => $book->getAuthor(),
            'published_at' => $book->getPublishedAt()?->format('Y-m-d H:i:s'),
            'created_at'   => $book->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updated_at'   => $book->getUpdatedAt()?->format('Y-m-d H:i:s')
        ]);
    }

    #[Route('/', name: 'api_books_get_all', methods: ['GET'], stateless: true)]
    public function getAll(
        AuthorizedRequest $request
    ): JsonResponse
    {
        return RestResponse::success(Book::fromArray(
            $request->retrieveUser()->getBooks()
        ));
    }

    #[Route('/create', name: 'api_books_create', methods: ['POST'], stateless: true)]
    public function create(CreateRequest $request): JsonResponse
    {
        $book = new Book();

        $book->setTitle($request->get('title'));
        $book->setAuthor($request->get('author'));
        $book->setPublishedAt(new DateTimeImmutable());
        $book->setCreatedAt(new DateTimeImmutable());
        $book->setUpdatedAt(new DateTime());
        $book->setUser($request->retrieveUser());

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return RestResponse::success($book);
    }

    #[Route('/{id}/put', name: 'app_book_put', methods: ['PUT'], stateless: true)]
    public function put(AuthorizedRequest $request): Response
    {
        return RestResponse::success();

    }

    #[Route('/{id}', name: 'app_book_patch', methods: ['PATCH'], stateless: true)]
    public function patch(AuthorizedRequest $request, Book $book): Response
    {
        return RestResponse::success();

    }

    #[Route('/{id}', name: 'app_book_delete', methods: ['DELETE'], stateless: true)]
    public function delete(AuthorizedRequest $request): Response
    {
        return RestResponse::success();
    }
}
