<?php

namespace App\Other\Constraint;


use App\Other\Constraint\Assert\Core\Constraint;
use App\Other\Constraint\Assert\Entity\EntityExistsConstraint;
use App\Other\Constraint\Assert\Entity\EntityUniqueConstraint;
use App\Other\Constraint\Assert\Length\LowerLengthConstraint;
use App\Other\Constraint\Assert\Length\UpperLengthConstraint;
use App\Other\Constraint\Core\Group;
use App\Other\Constraint\Core\Resolver;
use App\Request\Core\Request;
use Doctrine\ORM\EntityManagerInterface;

class ConstraintResolver implements Resolver
{
    protected Request $request;
    protected Group $group;
    protected EntityManagerInterface $em;

    /**
     * @param Request $request
     * @param Group $group
     */
    public function __construct(
        Request $request,
        Group $group
    )
    {
        $this->request = $request;
        $this->group = $group;
        $this->em = $request->getEntityManager();
    }

    public function resolve(): Constraint
    {
        if ($this->contains('exists:') || $this->contains('unique:')) {
            [, $condition] = $this->group->split('/');
            [$table, $column] = $condition->divide();

            if ($this->contains('exists:')) {
                return new EntityExistsConstraint(
                    $this->request,
                    $this->em,
                    $table,
                    $column
                );
            }

            return new EntityUniqueConstraint(
                $this->request,
                $this->em,
                $table,
                $column
            );
        }

        if ($this->contains('min:')) {
            [, $value] = $this->group->divide();
            return new LowerLengthConstraint($this->request, (int) $value);
        }

        if ($this->contains('max:')) {
            [, $value] = $this->group->divide();
            return new UpperLengthConstraint($this->request, (int) $value);
        }

        if ($this->exists()) {
            return new ($this->group->retrieve())($this->request);
        }

        throw new UnknownConstraintException($this->group->getContent());
    }

    /**
     * @param string $needle
     * @return bool
     */
    public function contains(string $needle): bool
    {
        return $this->group->contains($needle);
    }

    public function exists(): bool
    {
        return $this->group->exists();
    }
}