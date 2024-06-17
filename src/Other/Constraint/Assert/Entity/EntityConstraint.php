<?php

namespace App\Other\Constraint\Assert\Entity;

use App\Other\Constraint\Assert\Core\AbstractConstraint;
use App\Request\Core\Request;
use Doctrine\ORM\EntityManagerInterface;

abstract class EntityConstraint extends AbstractConstraint
{
    protected EntityManagerInterface $em;
    protected string $table;

    public function __construct(
        string $definition,
        EntityManagerInterface $em,
        string $table
    )
    {
        parent::__construct($definition);
        $this->em = $em;
        $this->table = $table;
    }
}