<?php

namespace App\Controller;

use App\Controller\Core\RestController;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\Security\LoginRequest;
use App\Request\Security\RegisterRequest;
use App\Response\RestResponse;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api')]
class SecurityController extends RestController
{
    private UserPasswordHasherInterface $passwordHasher;
    private JWTTokenManagerInterface $tokenManager;
    private UserRepository $userRepository;

    public function __construct(
        JWTTokenManagerInterface    $tokenManager,
        UserRepository              $userRepository,
        UserPasswordHasherInterface $passwordHasher,
    )
    {
        $this->tokenManager = $tokenManager;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }


    #[Route(path: '/login', name: 'api_login', methods: ['POST'])]
    public function login(
        LoginRequest $request
    ): JsonResponse
    {
        $invalid = static fn () => RestResponse::error('Bad credentials');
        $user = $this->userRepository->findOneBy(
            ['email' => $request->get('login')]
        );

        if ($user === null) {
            return $invalid();
        }

        if (!$this->passwordHasher->isPasswordValid($user, $request->get('password'))) {
            return $invalid();
        }

        $token = $this->tokenManager->create($user);

        return RestResponse::success(['token' => $token]);
    }


    #[Route(path: '/register', name: 'api_register', methods: ['POST'])]
    public function register(
        RegisterRequest        $request,
        EntityManagerInterface $em,
    ): JsonResponse
    {
        $user = new User();

        $user->setName($request->get('name'));
        $user->setEmail($request->get('login'));
        $user->setPassword($this->passwordHasher->hashPassword($user, $request->get('password')));
        $user->setRoles(['ROLE_USER']);
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setUpdatedAt(new DateTime());

        $em->persist($user);
        $em->flush();

        return RestResponse::success($user);
    }
}