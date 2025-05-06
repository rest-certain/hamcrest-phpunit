<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\Cardinality;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\Cardinality\GreaterThanOrEqualTo;

class GreaterThanOrEqualToTest extends TestCase
{
    public function testGreaterThanOrEqualTo(): void
    {
        $constraint = new GreaterThanOrEqualTo(1);

        $this->assertThat(2, $constraint);
        $this->assertThat(1, $constraint);
    }

    public function testFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that 1 is greater than or equal to 2');

        $this->assertThat(1, new GreaterThanOrEqualTo(2));
    }
}
