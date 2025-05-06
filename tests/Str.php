<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest;

use Override;
use Stringable;

final readonly class Str implements Stringable
{
    public function __construct(private string $value)
    {
    }

    #[Override] public function __toString(): string
    {
        return $this->value;
    }
}
