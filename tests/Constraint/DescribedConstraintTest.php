<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\ObjectHasProperty;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\DescribedConstraint;
use RestCertain\Test\Hamcrest\Str;

use function RestCertain\Hamcrest\assertThat;

class DescribedConstraintTest extends TestCase
{
    public function testDescribedConstraintPasses(): void
    {
        $testValue = (object) ['foo' => 'bar'];
        $expectedProperty = 'foo';

        assertThat(
            $testValue,
            new DescribedConstraint(
                'value is a standard object with property "%s"',
                new ObjectHasProperty($expectedProperty),
                $expectedProperty,
            ),
        );
    }

    public function testDescribedConstraintWithNonConstraint(): void
    {
        assertThat('foo', new DescribedConstraint('value is the string "foo"', 'foo'));
    }

    public function testDescribedConstraintFailureMessage(): void
    {
        $testValue = (object) ['baz' => 'bar'];
        $expectedProperty = 'foo';

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is a standard object with property "foo"');

        assertThat(
            $testValue,
            new DescribedConstraint(
                'value is a standard object with property "%s"',
                new ObjectHasProperty($expectedProperty),
                $expectedProperty,
            ),
        );
    }

    public function testDescribedConstraintFailureMessageWithMultipleValues(): void
    {
        $testValue = (object) ['baz' => 'bar'];
        $expectedProperty = 'foo';

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that value is... testing sprintf values foo, some string, '
            . '1234, stdClass Object (...)',
        );

        assertThat(
            $testValue,
            new DescribedConstraint(
                'value is... testing sprintf values %s, %s, %d, %s',
                new ObjectHasProperty($expectedProperty),
                $expectedProperty,
                new Str('some string'),
                1234,
                $testValue,
            ),
        );
    }

    public function testDescribedConstraintFailsWhenDescriptionIsNotAFormattedString(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('value is the string "foo"');

        assertThat('bar', new DescribedConstraint('value is the string "foo"', 'foo'));
    }

    public function testToStringIsEmptyString(): void
    {
        $constraint = new DescribedConstraint('value is the string "foo"', 'foo');

        $this->assertSame('', $constraint->toString());
    }
}
