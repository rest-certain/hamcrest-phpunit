<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\Map;

use ArrayIterator;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Constraint\GreaterThan;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\Map\MapMatchesValue;

class MapMatchesValueTest extends TestCase
{
    #[DataProvider('successProvider')]
    public function testMapMatchesValue(mixed $testValue, mixed $valueConstraint): void
    {
        $this->assertThat($testValue, new MapMatchesValue($valueConstraint));
    }

    /**
     * @return array<array{testValue: mixed, valueConstraint: mixed}>
     */
    public static function successProvider(): array
    {
        return [
            [
                'testValue' => ['foo' => 'bar'],
                'valueConstraint' => 'bar',
            ],
            [
                'testValue' => new ArrayIterator(['foo' => 123, 'bar' => 456, 'baz' => 789]),
                'valueConstraint' => new GreaterThan(450),
            ],
        ];
    }

    public function testWhenOtherIsNotAnArray(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that value is a hash map (i.e., associative array)',
        );

        $this->assertThat('foo', new MapMatchesValue('foo'));
    }

    public function testWhenOtherIsAnArrayList(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that value is a hash map (i.e., associative array)',
        );

        $this->assertThat([1, 2, 3], new MapMatchesValue('foo'));
    }

    public function testWhenOtherIsAnIteratorList(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that value is a hash map (i.e., associative array)',
        );

        $this->assertThat(new ArrayIterator([1, 2, 3]), new MapMatchesValue('foo'));
    }

    public function testWhenKeyConstraintIsNotSatisfied(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            "Failed asserting that value is a hash map with a value that is equal to 'foo'",
        );

        $this->assertThat(['baz' => 'bar'], new MapMatchesValue('foo'));
    }

    public function testToStringReturnsAnEmptyString(): void
    {
        $constraint = new MapMatchesValue('foo');

        $this->assertSame('', $constraint->toString());
    }
}
