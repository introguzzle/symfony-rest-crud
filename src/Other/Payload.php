<?php

namespace App\Other;

use DateTime;
use DateTimeInterface;

class Payload
{
    public DateTimeInterface $issuedAt;
    public DateTimeInterface $expiresAt;

    /**
     * @var string[]
     */
    public array $roles;
    public string $username;
    private bool $expired = false;

    public function __construct(
        DateTimeInterface $issuedAt,
        DateTimeInterface $expiresAt,
        array $roles,
        string $username
    )
    {
        $this->issuedAt = $issuedAt;
        $this->expiresAt = $expiresAt;
        $this->roles = $roles;
        $this->username = $username;
    }

    public static function fromArray(array $payload): static
    {
        return new static(
            (new DateTime())->setTimestamp($payload['iat']),
            (new DateTime())->setTimestamp($payload['exp']),
            $payload['roles'],
            $payload['username']
        );
    }

    public function isExpired(): bool
    {
        return $this->expired;
    }

    public function setExpired(bool $expired): static
    {
        $this->expired = $expired;
        return $this;
    }
}