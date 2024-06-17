<?php

namespace App\Other\Constraint;

use App\Other\Constraint\Assert\Core\Constraint;
use App\Other\Constraint\Assert\Entity\EntityExistsConstraint;
use App\Other\Constraint\Assert\Entity\EntityUniqueConstraint;
use App\Other\Constraint\Assert\Length\LowerLengthConstraint;
use App\Other\Constraint\Assert\Length\UpperLengthConstraint;
use App\Other\Constraint\Assert\Match\EqualityConstraint;
use App\Other\Constraint\Assert\Match\MatchConstraint;
use App\Other\Constraint\Assert\Null\ConditionalRequiredConstraint;
use App\Other\Constraint\Core\Group;
use App\Other\Constraint\Core\Resolver;
use App\Other\Constraint\Exception\UnknownConstraintException;
use Doctrine\Inflector\Rules\Pattern;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ConstraintResolver implements Resolver
{
    protected EntityManagerInterface $em;
    protected RequestStack $requestStack;

    /**
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $em
     */
    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $em
    )
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function resolve(Group $group): Constraint
    {
        if (($exists = $group->contains('exists:')) || $group->contains('unique:')) {
            [, $condition] = $group->split('/');
            [$table, $column] = $condition->divide();

            if ($exists) {
                return new EntityExistsConstraint(
                    'exists',
                    $this->em,
                    $table,
                    $column
                );
            }

            return new EntityUniqueConstraint(
                'unique',
                $this->em,
                $table,
                $column
            );
        }

        if ($group->contains('required_if:')) {
            [, $other] = $group->divide();
            return new ConditionalRequiredConstraint(
                'required_if',
                $this->get($other)
            );
        }

        if ($group->contains('regex:')) {
            [, $regex] = $group->divide();
            return new MatchConstraint(
                'regex',
                new Pattern($regex)
            );
        }

        if ($group->contains('same:')) {
            [, $other] = $group->divide();
            return new EqualityConstraint(
                'same',
                $other,
                $this->get($other)
            );
        }

        if ($group->contains('min:')) {
            [, $value] = $group->divide();
            return new LowerLengthConstraint(
                'min',
                (int) $value
            );
        }

        if ($group->contains('max:')) {
            [, $value] = $group->divide();
            return new UpperLengthConstraint(
                'max',
                (int) $value
            );
        }

        if ($group->exists()) {
            return new ($group->retrieve())($group->getContent());
        }

        throw new UnknownConstraintException($group->getContent());
    }

    public function get(string $other): mixed
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request?->get($other) ?? $request?->getPayload()->get($other);
    }
}