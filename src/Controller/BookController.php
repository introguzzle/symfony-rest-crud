<?php

namespace App\Controller;

use App\Controller\Core\RestController;
use App\Entity\Book;
use App\Other\RestResponse;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Request\AuthorizedRequest;
use App\Request\CreateBookRequest;

use DateTime;
use DateTimeImmutable;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/books')]
class BookController extends RestController
{
    protected BookRepository $bookRepository;
    protected EntityManager $entityManager;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        UserRepository $userRepository,
        ValidatorInterface $validator,
        BookRepository $bookRepository,
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($jwtManager, $userRepository, $validator);
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
            return new JsonResponse(['error' => 'Book not found or you do not have access to it'], JsonResponse::HTTP_NOT_FOUND);
        }

        return RestResponse::success([
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'published_at' => $book->getPublishedAt()?->format('Y-m-d H:i:s'),
            'created_at' => $book->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updated_at' => $book->getUpdatedAt()?->format('Y-m-d H:i:s')
        ]);
    }

    #[Route('/', name: 'api_books_get_all', methods: ['GET'], stateless: true)]
    public function getAll(
        AuthorizedRequest $request
    ): JsonResponse
    {
        $data = [];

        foreach ($request->retrieveUser()->getBooks() as $book) {
            $data[] = $book->toArray();
        }

        return RestResponse::success($data);
    }

    #[Route('/create', name: 'api_books_create', methods: ['POST'], stateless: true)]
    public function create(CreateBookRequest $request): JsonResponse
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
