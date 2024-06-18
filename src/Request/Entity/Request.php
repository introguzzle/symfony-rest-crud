<?php

namespace App\Request\Entity;

use App\Entity\Entity;
use App\Other\Options;
use App\Other\UnsupportedMethodException;
use App\Request\Core\AuthorizedRequest;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    abstract public function getEntityOptions(): Options;

    /**
     * @return T
     */
    public function getEntity(): Entity
    {
        $options = $this->getEntityOptions();

        if ($options->getFetchMode() === null) {
            throw new UnsupportedMethodException();
        }

        if ($options->getFetchMode()->isCollection()) {
            throw new UnsupportedMethodException();
        }

        $criteria = [];

        foreach ($options->getProperties() as $property) {
            $criteria = [$property => $this->get($property)];
        }

        return $this->getEntityRepository()->findOneBy($criteria);
    }

    public function getEntityCollection(): Collection
    {
        $options = $this->getEntityOptions();

        if ($options->getFetchMode() === null) {
            throw new UnsupportedMethodException();
        }

        if ($options->getFetchMode()->isSingle()) {
            throw new UnsupportedMethodException();
        }

        $criteria = [];

        foreach ($options->getProperties() as $property) {
            $criteria = [$property => $this->get($property)];
        }

        return new ArrayCollection($this->getEntityRepository()->findBy($criteria));
    }
}