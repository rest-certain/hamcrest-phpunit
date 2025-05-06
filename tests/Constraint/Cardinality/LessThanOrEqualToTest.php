<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\Cardinality;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\Cardinality\LessThanOrEqualTo;

class LessThanOrEqualToTest extends TestCase
{
    public function testLessThanOrEqualTo(): void
    {
        $constraint = new LessThanOrEqualTo(2);

        $this->assertThat(2, $constraint);
        $this->assertThat(1, $constraint);
    }

    public function testFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that 2 is less than or equal to 1');

        $this->assertThat(2, new LessThanOrEqualTo(1));
    }
}
