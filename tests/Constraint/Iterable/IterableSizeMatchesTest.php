<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\Iterable;

use ArrayIterator;
use EmptyIterator;
use InvalidArgumentException;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\Count;
use PHPUnit\Framework\Constraint\GreaterThan;
use PHPUnit\Framework\Constraint\IsEmpty;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsTrue;
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Framework\Constraint\SameSize;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\Cardinality\GreaterThanOrEqualTo;
use RestCertain\Hamcrest\Constraint\Cardinality\LessThanOrEqualTo;
use RestCertain\Hamcrest\Constraint\Iterable\IterableSizeMatches;
use stdClass;

class IterableSizeMatchesTest extends TestCase
{
    #[DataProvider('iterableSizeMatchesProvider')]
    public function testIterableSizeMatches(mixed $testValue, Constraint | int $constraint): void
    {
        $this->assertThat($testValue, new IterableSizeMatches($constraint));
    }

    /**
     * @return array<array{testValue: mixed, constraint: Constraint | int}>
     */
    public static function iterableSizeMatchesProvider(): array
    {
        return [
            ['testValue' => [1, 2, 3], 'constraint' => 3],
            ['testValue' => [1, 2, 3], 'constraint' => new IsEqual(3)],
            ['testValue' => [1, 2, 3], 'constraint' => new IsEqual('3')],
            ['testValue' => ['foo', 'bar', 'baz'], 'constraint' => new Count(3)],
            ['testValue' => ['foo', 'bar', 'baz'], 'constraint' => new GreaterThan(2)],
            ['testValue' => ['foo', 'bar', 'baz'], 'constraint' => new GreaterThanOrEqualTo(3)],
            ['testValue' => ['foo', 'bar', 'baz'], 'constraint' => new LessThan(4)],
            ['testValue' => ['foo', 'bar', 'baz'], 'constraint' => new LessThanOrEqualTo(3)],
            ['testValue' => [], 'constraint' => new IsEmpty()],
            ['testValue' => ['foo', 'bar', 'baz'], 'constraint' => new SameSize([1, 2, 3])],
            ['testValue' => new ArrayIterator([1, 2, 3]), 'constraint' => 3],
            ['testValue' => new ArrayIterator([1, 2, 3]), 'constraint' => new IsEqual(3)],
            ['testValue' => new ArrayIterator([1, 2, 3]), 'constraint' => new IsEqual('3')],
            ['testValue' => new ArrayIterator(['foo', 'bar', 'baz']), 'constraint' => new Count(3)],
            ['testValue' => new ArrayIterator(['foo', 'bar', 'baz']), 'constraint' => new GreaterThan(2)],
            ['testValue' => new ArrayIterator(['foo', 'bar', 'baz']), 'constraint' => new GreaterThanOrEqualTo(3)],
            ['testValue' => new ArrayIterator(['foo', 'bar', 'baz']), 'constraint' => new LessThan(4)],
            ['testValue' => new ArrayIterator(['foo', 'bar', 'baz']), 'constraint' => new LessThanOrEqualTo(3)],
            ['testValue' => new ArrayIterator(), 'constraint' => new IsEmpty()],
            ['testValue' => new ArrayIterator(['foo', 'bar', 'baz']), 'constraint' => new SameSize([1, 2, 3])],
            ['testValue' => new EmptyIterator(), 'constraint' => new IsEmpty()],
        ];
    }

    public function testWhenConstraintIsNotOneOfTheCardinalityConstraints(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Constraint must be one of the constraints: Count, GreaterThan, GreaterThanOrEqualTo, IsEmpty, IsEqual, '
            . 'LessThan, LessThanOrEqualTo, or SameSize. Received PHPUnit\Framework\Constraint\IsTrue',
        );

        new IterableSizeMatches(new IsTrue());
    }

    public function testWhenOtherIsNotAnIterable(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an iterable');

        $this->assertThat('foo', new IterableSizeMatches(1));
    }

    public function testWhenOtherIsNotGreaterThanConstraint(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an iterable with a size that is greater than 2');

        $this->assertThat([1, 2], new IterableSizeMatches(new GreaterThan(2)));
    }

    public function testWhenOtherIsNotLessThanConstraint(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an iterable with a size that is less than 2');

        $this->assertThat([1, 2], new IterableSizeMatches(new LessThan(2)));
    }

    public function testWhenOtherIsNotTheSameSizeAsCount(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an iterable with a count matches 10');

        $this->assertThat([1, 2], new IterableSizeMatches(10));
    }

    public function testWhenOtherIsNotTheSameSizeAsOtherIterable(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an iterable with a count matches 3');

        $this->assertThat([1, 2], new IterableSizeMatches(new SameSize([1, 2, 3])));
    }

    public function testWhenOtherIsNotEmpty(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an empty iterable');

        $this->assertThat([1, 2], new IterableSizeMatches(new IsEmpty()));
    }

    public function testWhenOtherSizeIsNotEqualToInt(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an iterable with a size that is equal to 3');

        $this->assertThat([1, 2], new IterableSizeMatches(new IsEqual(3)));
    }

    public function testWhenOtherSizeIsNotEqualToString(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an iterable with a size that is equal to \'3\'');

        $this->assertThat([1, 2], new IterableSizeMatches(new IsEqual('3')));
    }

    public function testWhenOtherSizeIsNotEqualToWeirdValue(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that value is an iterable with a size that is equal to stdClass Object',
        );

        $this->assertThat([1, 2], new IterableSizeMatches(new IsEqual(new stdClass())));
    }
}
