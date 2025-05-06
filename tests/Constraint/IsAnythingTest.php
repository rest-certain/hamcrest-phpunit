<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint;

use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\IsAnything;

class IsAnythingTest extends TestCase
{
    public function testIsAnything(): void
    {
        $this->assertThat('whatever', new IsAnything());
    }

    public function testToString(): void
    {
        $constraint = new IsAnything();

        $this->assertSame('is anything', $constraint->toString());
    }
}
