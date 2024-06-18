<?php

namespace App\Response;

use App\Other\Constraint\Violation\ViolationList;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RestResponse extends JsonResponse
{
    public function __construct(
        array|object $data = [],
        int          $status = 200,
        array        $headers = []
    )
    {
        if (is_object($data) && method_exists($data, 'toArray')) {
            $data = $data->toArray();
        }

        $data = ['status' => $status] + $data;


        parent::__construct($data, $status, $headers);
    }

    public function throw(): never
    {
        $this->send();
        exit;
    }

    public static function success(
        null|array|object $data = [],
        int               $status = Response::HTTP_OK,
        array             $headers = []
    ): static
    {
        if ($data === null) {
            $data = [];
        }

        return new static(['data' => $data], $status, $headers);
    }

    public static function error(
        string $error,
        int    $status = Response::HTTP_BAD_REQUEST,
        array  $headers = []
    ): static
    {
        return new static(['error' => $error], $status, $headers);
    }

    public static function unauthorized(string $message = 'Unauthorized'): static
    {
        return static::error($message, Response::HTTP_UNAUTHORIZED);
    }

    public static function notFound(): static
    {
        return static::error('Not found', Response::HTTP_NOT_FOUND);
    }

    public static function created(): static
    {
        return static::success([], Response::HTTP_CREATED);
    }

    public static function conflict(): static
    {
        return static::error('Conflict', Response::HTTP_CONFLICT);
    }

    public static function internal(?Throwable $throwable = null): static
    {
        return $throwable === null
            ? static::error('Internal', Response::HTTP_INTERNAL_SERVER_ERROR)
            : static::error($throwable->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function expired(): static
    {
        return static::error('Expired', Response::HTTP_FORBIDDEN);
    }

    /**
     * @param array<string, string> $violations
     * @return static
     */
    public static function badRequest(array $violations): static
    {
        return new static($violations, Response::HTTP_BAD_REQUEST);
    }
}