<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\ObjectHasProperty;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\AdditionallyDescribedConstraint;
use RestCertain\Test\Hamcrest\Str;

use function RestCertain\Hamcrest\assertThat;

class AdditionallyDescribedConstraintTest extends TestCase
{
    public function testAdditionallyDescribedConstraintPasses(): void
    {
        $testValue = (object) ['foo' => 'bar'];
        $expectedProperty = 'foo';

        assertThat(
            $testValue,
            new AdditionallyDescribedConstraint(
                "The object doesn't have the expected property '%s'",
                new ObjectHasProperty($expectedProperty),
                $expectedProperty,
            ),
        );
    }

    public function testAdditionallyDescribedConstraintWithNonConstraint(): void
    {
        assertThat('foo', new AdditionallyDescribedConstraint('The string is not the same string.', 'foo'));
    }

    public function testAdditionallyDescribedConstraintFailureMessage(): void
    {
        $testValue = (object) ['baz' => 'bar'];
        $expectedProperty = 'foo';

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessageMatches(
            '/^Failed asserting that stdClass Object #\d+ \(\n    \'baz\' => \'bar\',\n\) has property "foo"\.\n'
            . 'The object doesn\'t have the expected property \'foo\'\.$/',
        );

        assertThat(
            $testValue,
            new AdditionallyDescribedConstraint(
                "The object doesn't have the expected property '%s'.",
                new ObjectHasProperty($expectedProperty),
                $expectedProperty,
            ),
        );
    }

    public function testAdditionallyDescribedConstraintFailureMessageWithMultipleValues(): void
    {
        $testValue = (object) ['baz' => 'bar'];
        $expectedProperty = 'foo';

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessageMatches(
            '/^Failed asserting that stdClass Object #\d+ \(\n    \'baz\' => \'bar\',\n\) has property "foo".\n'
            . 'We got the following: foo, some string, 1234, stdClass Object \(...\)$/',
        );

        assertThat(
            $testValue,
            new AdditionallyDescribedConstraint(
                'We got the following: %s, %s, %d, %s',
                new ObjectHasProperty($expectedProperty),
                $expectedProperty,
                new Str('some string'),
                1234,
                $testValue,
            ),
        );
    }

    public function testAdditionallyDescribedConstraintFailsWhenDescriptionIsNotAFormattedString(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            "Failed asserting that 'bar' is equal to 'foo'.\nSomething something something.",
        );

        assertThat('bar', new AdditionallyDescribedConstraint('Something something something.', 'foo'));
    }
}
