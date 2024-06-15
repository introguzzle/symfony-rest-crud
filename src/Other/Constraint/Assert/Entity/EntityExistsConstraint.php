<?php

namespace App\Other\Constraint\Assert\Entity;

use App\Request\Core\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

class EntityExistsConstraint extends EntityConstraint
{
    protected string $column;

    public function __construct(
        Request $request,
        EntityManagerInterface $em,
        string $table,
        string $column
    )
    {
        parent::__construct($request, $em, $table);
        $this->column = $column;
    }

    public function test(mixed $value): bool
    {
        $sql = "SELECT COUNT($this->column) AS count FROM $this->table WHERE $this->column = ?";
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('count', 'count');
        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $value); // Параметры в NativeQuery пронумерованы с 1

        return (int) $query->getSingleScalarResult() > 0;
    }
    public function getMessage(): string
    {
        return 'This field does not exist';
    }
}