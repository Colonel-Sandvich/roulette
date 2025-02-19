<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;

class ModelNotFound extends \RuntimeException
{
    public const string MODEL = 'Model';

    /**
     * Mark the constructor as final
     *
     * @see https://phpstan.org/blog/solving-phpstan-error-unsafe-usage-of-new-static
     */
    final public function __construct(
        string $message = "",
        int $code = 200,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function make(string $message): static
    {
        return new static("[" . static::MODEL . "]: " . $message);
    }

    public static function byId(int|string $id): static
    {
        return static::make("Failed to find by: `id` = `{$id}`");
    }
}
