<?php

namespace App\Request\Entity;

use App\Request\Core\AuthorizedRequest;
use Doctrine\ORM\EntityRepository;

/**
 * @template T
 */
abstract class Request extends AuthorizedRequest
{
    /**
     * @return class-string<T>
     */
    abstract public function getEntityClass(): string;

    /**
     * @return EntityRepository<T>
     */
    public function getEntityRepository(): EntityRepository
    {
        return $this->em->getRepository($this->getEntityClass());
    }
}