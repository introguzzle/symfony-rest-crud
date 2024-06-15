<?php

namespace App\Controller\Core;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class RestController extends AbstractController
{
    protected JWTTokenManagerInterface $jwtManager;
    protected UserRepository $userRepository;
    protected ValidatorInterface $validator;

    /**
     * @param JWTTokenManagerInterface $jwtManager
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        UserRepository $userRepository,
        ValidatorInterface $validator
    )
    {
        $this->jwtManager = $jwtManager;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }
}