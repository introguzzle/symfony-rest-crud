<?php

namespace App\Request\Core;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

abstract class UnguardedRequest extends Request
{
    public function __construct(
        RequestStack             $requestStack,
        JWTTokenManagerInterface $tokenManager,
        UserRepository           $userRepository,
        RouterInterface          $router,
        EntityManagerInterface   $em
    )
    {
        parent::__construct($requestStack, $tokenManager, $userRepository, $router, $em);
        $this->setGuard(false);
    }
}