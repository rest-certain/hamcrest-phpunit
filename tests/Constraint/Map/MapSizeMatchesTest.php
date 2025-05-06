<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\Map;

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
use RestCertain\Hamcrest\Constraint\Map\MapSizeMatches;
use stdClass;

class MapSizeMatchesTest extends TestCase
{
    #[DataProvider('mapSizeMatchesProvider')]
    public function testMapSizeMatches(mixed $testValue, Constraint | int $constraint): void
    {
        $this->assertThat($testValue, new MapSizeMatches($constraint));
    }

    /**
     * @return array<array{testValue: mixed, constraint: Constraint | int}>
     */
    public static function mapSizeMatchesProvider(): array
    {
        return [
            ['testValue' => ['a' => 1, 'b' => 2, 'c' => 3], 'constraint' => 3],
            ['testValue' => ['a' => 1, 'b' => 2, 'c' => 3], 'constraint' => new IsEqual(3)],
            ['testValue' => ['a' => 1, 'b' => 2, 'c' => 3], 'constraint' => new IsEqual('3')],
            ['testValue' => ['foo' => 123, 'bar' => 456, 'baz' => 789], 'constraint' => new Count(3)],
            ['testValue' => ['foo' => 123, 'bar' => 456, 'baz' => 789], 'constraint' => new GreaterThan(2)],
            ['testValue' => ['foo' => 123, 'bar' => 456, 'baz' => 789], 'constraint' => new GreaterThanOrEqualTo(3)],
            ['testValue' => ['foo' => 123, 'bar' => 456, 'baz' => 789], 'constraint' => new LessThan(4)],
            ['testValue' => ['foo' => 123, 'bar' => 456, 'baz' => 789], 'constraint' => new LessThanOrEqualTo(3)],
            ['testValue' => [], 'constraint' => new IsEmpty()],
            ['testValue' => ['foo' => 123, 'bar' => 456, 'baz' => 789], 'constraint' => new SameSize([1, 2, 3])],
            ['testValue' => new ArrayIterator(['a' => 1, 'b' => 2, 'c' => 3]), 'constraint' => 3],
            ['testValue' => new ArrayIterator(['a' => 1, 'b' => 2, 'c' => 3]), 'constraint' => new IsEqual(3)],
            ['testValue' => new ArrayIterator(['a' => 1, 'b' => 2, 'c' => 3]), 'constraint' => new IsEqual('3')],
            [
                'testValue' => new ArrayIterator(['foo' => 123, 'bar' => 456, 'baz' => 789]),
                'constraint' => new Count(3),
            ],
            [
                'testValue' => new ArrayIterator(['foo' => 123, 'bar' => 456, 'baz' => 789]),
                'constraint' => new GreaterThan(2),
            ],
            [
                'testValue' => new ArrayIterator(['foo' => 123, 'bar' => 456, 'baz' => 789]),
                'constraint' => new GreaterThanOrEqualTo(3),
            ],
            [
                'testValue' => new ArrayIterator(['foo' => 123, 'bar' => 456, 'baz' => 789]),
                'constraint' => new LessThan(4),
            ],
            [
                'testValue' => new ArrayIterator(['foo' => 123, 'bar' => 456, 'baz' => 789]),
                'constraint' => new LessThanOrEqualTo(3),
            ],
            ['testValue' => new ArrayIterator(), 'constraint' => new IsEmpty()],
            [
                'testValue' => new ArrayIterator(['foo' => 123, 'bar' => 456, 'baz' => 789]),
                'constraint' => new SameSize([1, 2, 3]),
            ],
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

        new MapSizeMatches(new IsTrue());
    }

    public function testWhenOtherIsNotAnIterable(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is a hash map');

        $this->assertThat('foo', new MapSizeMatches(1));
    }

    public function testWhenOtherIsAListArray(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is a hash map');

        $this->assertThat([1, 2, 3], new MapSizeMatches(1));
    }

    public function testWhenOtherIsAListIterator(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is a hash map');

        $this->assertThat(new ArrayIterator([1, 2, 3]), new MapSizeMatches(1));
    }

    public function testWhenOtherIsNotGreaterThanConstraint(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is a hash map with a size that is greater than 2');

        $this->assertThat(['a' => 1, 'b' => 2], new MapSizeMatches(new GreaterThan(2)));
    }

    public function testWhenOtherIsNotLessThanConstraint(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is a hash map with a size that is less than 2');

        $this->assertThat(['a' => 1, 'b' => 2], new MapSizeMatches(new LessThan(2)));
    }

    public function testWhenOtherIsNotTheSameSizeAsCount(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is a hash map with a count matches 10');

        $this->assertThat(['a' => 1, 'b' => 2], new MapSizeMatches(10));
    }

    public function testWhenOtherIsNotTheSameSizeAsOtherIterable(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is a hash map with a count matches 3');

        $this->assertThat(['a' => 1, 'b' => 2], new MapSizeMatches(new SameSize([1, 2, 3])));
    }

    public function testWhenOtherIsNotEmpty(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an empty hash map');

        $this->assertThat(['a' => 1, 'b' => 2], new MapSizeMatches(new IsEmpty()));
    }

    public function testWhenOtherSizeIsNotEqualToInt(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is a hash map with a size that is equal to 3');

        $this->assertThat(['a' => 1, 'b' => 2], new MapSizeMatches(new IsEqual(3)));
    }

    public function testWhenOtherSizeIsNotEqualToString(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is a hash map with a size that is equal to \'3\'');

        $this->assertThat(['a' => 1, 'b' => 2], new MapSizeMatches(new IsEqual('3')));
    }

    public function testWhenOtherSizeIsNotEqualToWeirdValue(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that value is a hash map with a size that is equal to stdClass Object',
        );

        $this->assertThat(['a' => 1, 'b' => 2], new MapSizeMatches(new IsEqual(new stdClass())));
    }
}
