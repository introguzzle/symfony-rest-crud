<?php

namespace App\Other\Constraint\Assert\Entity;

use App\Request\Core\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Throwable;

class EntityExistsConstraint extends EntityConstraint
{
    protected string $column;
    /**
     * @var true
     */
    protected bool $failed = true;

    public function __construct(
        string                 $definition,
        EntityManagerInterface $em,
        string                 $table,
        string                 $column
    )
    {
        parent::__construct($definition, $em, $table);
        $this->column = $column;
    }

    public function test(mixed $value): bool
    {
        try {
            $sql = "SELECT COUNT($this->column) AS count FROM $this->table WHERE $this->column = ?";
            $rsm = new ResultSetMapping();
            $rsm->addScalarResult('count', 'count');
            $query = $this->em->createNativeQuery($sql, $rsm);
            $query->setParameter(1, $value);

            $this->failed = false;

            return ((int)$query->getSingleScalarResult()) > 0;
        } catch (Throwable) {
            $this->failed = true;
            return false;
        }
    }

    public function getMessage(): string
    {
        return 'This field does not exist';
    }
}