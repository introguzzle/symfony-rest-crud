<?php

namespace App\Controller;

use App\Controller\Core\RestController;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Request\Book\BookRequest;
use App\Request\Book\CreateRequest;
use App\Request\Book\DeleteRequest;
use App\Request\Book\PatchRequest;
use App\Request\Book\PutRequest;
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
    protected EntityManager $em;

    public function __construct(
        BookRepository $bookRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->bookRepository = $bookRepository;
        $this->em = $entityManager;
    }


    #[Route('/{id}', name: 'api_books_get', methods: ['GET'], stateless: true)]
    public function get(
        int $id,
        BookRequest $request
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

    #[Route('', name: 'api_books_get_all', methods: ['GET'], stateless: true)]
    public function getAll(
        BookRequest $request
    ): JsonResponse
    {
        return RestResponse::success(Book::fromArray(
            $request->retrieveUser()->getBooks()
        ));
    }

    #[Route('', name: 'api_books_create', methods: ['POST'], stateless: true)]
    public function create(CreateRequest $request): JsonResponse
    {
        $book = new Book();

        $book->setTitle($request->title);
        $book->setAuthor($request->author);
        $book->setPublishedAt(new DateTimeImmutable());
        $book->setCreatedAt(new DateTimeImmutable());
        $book->setUpdatedAt(new DateTime());
        $book->setUser($request->retrieveUser());

        $this->em->persist($book);
        $this->em->flush();

        return RestResponse::success($book);
    }

    #[Route('', name: 'app_book_put', methods: ['PUT'], stateless: true)]
    public function put(PutRequest $request): Response
    {
        $book = $request->getEntity();

        $book->setTitle($request->title);
        $book->setAuthor($request->author);

        $this->em->persist($book);
        $this->em->flush();

        return RestResponse::success($book);
    }

    #[Route('', name: 'app_book_patch', methods: ['PATCH'], stateless: true)]
    public function patch(PatchRequest $request): Response
    {
        $book = $request->getEntity();

        if ($request->title) {
            $book->setTitle($request->title);
        }

        if ($request->author) {
            $book->setAuthor($request->author);
        }

        $this->em->persist($book);
        $this->em->flush();

        return RestResponse::success($book);
    }

    #[Route('', name: 'app_book_delete', methods: ['DELETE'], stateless: true)]
    public function delete(DeleteRequest $request): Response
    {
        $book = $request->getEntity();

        $this->em->remove($book);
        $this->em->flush();

        return RestResponse::success();
    }
}
