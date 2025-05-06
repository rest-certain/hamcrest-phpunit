<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\IsEqualIgnoringCase;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\StringContains;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\CombinedConstraint;
use RestCertain\Hamcrest\Constraint\Operator;

class CombinedConstraintTest extends TestCase
{
    public function testBoth(): void
    {
        $constraint = CombinedConstraint::both('foo')
            ->and(new IsEqualIgnoringCase('FOO'))
            ->and(new StringContains('oo'));

        $this->assertThat('foo', $constraint);
    }

    public function testEither(): void
    {
        $constraint = CombinedConstraint::either('foo')
            ->or(new StringContains('bar'))
            ->or(new IsIdentical('BAR'));

        $this->assertThat('BAR', $constraint);
    }

    public function testBothWithCombinedAndOr(): void
    {
        $constraint = CombinedConstraint::both('foo')
            ->and(new IsEqualIgnoringCase('FOO'))
            ->or(new StringContains('oo'));

        $this->assertThat('shoo', $constraint);
    }

    public function testEitherWithCombinedAndOr(): void
    {
        $constraint = CombinedConstraint::either(new StringContains('foo'))
            ->or(new IsEqualIgnoringCase('BAR'))
            ->and('bAr');

        $this->assertThat('bAr', $constraint);
    }

    public function testBothWithFailure(): void
    {
        $constraint = CombinedConstraint::both('foo')
            ->and(new IsEqualIgnoringCase('FOO'))
            ->and(new StringContains('oo'));

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            "Failed asserting that 'bar' ( is equal to 'foo' and is equal to 'FOO' ) "
            . 'and contains "oo" [ASCII](length: 2)',
        );

        $this->assertThat('bar', $constraint);
    }

    public function testEitherWithFailure(): void
    {
        $constraint = CombinedConstraint::either('foo')
            ->or(new StringContains('bar'))
            ->or(new IsIdentical('BAR'));

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            "Failed asserting that 'Bar' ( is equal to 'foo' or contains \"bar\" [ASCII](length: 3) ) "
            . "or is identical to 'BAR'",
        );

        $this->assertThat('Bar', $constraint);
    }

    public function testOperatorAndPrecedenceForBoth(): void
    {
        $constraint = CombinedConstraint::both('foo');

        $this->assertSame(Operator::And->value, $constraint->operator());
        $this->assertSame(Operator::And->precedence(), $constraint->precedence());
    }

    public function testOperatorAndPrecedenceForAndAlso(): void
    {
        $constraint = CombinedConstraint::either('foo')->and('bar');

        $this->assertSame(Operator::And->value, $constraint->operator());
        $this->assertSame(Operator::And->precedence(), $constraint->precedence());
    }

    public function testOperatorAndPrecedenceForEither(): void
    {
        $constraint = CombinedConstraint::either('foo');

        $this->assertSame(Operator::Or->value, $constraint->operator());
        $this->assertSame(Operator::Or->precedence(), $constraint->precedence());
    }

    public function testOperatorAndPrecedenceForOrElse(): void
    {
        $constraint = CombinedConstraint::both('foo')->or('bar');

        $this->assertSame(Operator::Or->value, $constraint->operator());
        $this->assertSame(Operator::Or->precedence(), $constraint->precedence());
    }
}
