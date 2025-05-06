<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\Map;

use ArrayIterator;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Constraint\StringContains;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\Map\MapMatchesKey;

class MapMatchesKeyTest extends TestCase
{
    #[DataProvider('successProvider')]
    public function testMapMatchesKey(mixed $testValue, mixed $keyConstraint): void
    {
        $this->assertThat($testValue, new MapMatchesKey($keyConstraint));
    }

    /**
     * @return array<array{testValue: mixed, keyConstraint: mixed}>
     */
    public static function successProvider(): array
    {
        return [
            [
                'testValue' => ['foo' => 'bar'],
                'keyConstraint' => 'foo',
            ],
            [
                'testValue' => new ArrayIterator(['foo' => 123, 'bar' => 456, 'baz' => 789]),
                'keyConstraint' => new StringContains('r'),
            ],
        ];
    }

    public function testWhenOtherIsNotAnArray(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that value is a hash map (i.e., associative array)',
        );

        $this->assertThat('foo', new MapMatchesKey('foo'));
    }

    public function testWhenOtherIsAnArrayList(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that value is a hash map (i.e., associative array)',
        );

        $this->assertThat([1, 2, 3], new MapMatchesKey('foo'));
    }

    public function testWhenOtherIsAnIteratorList(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that value is a hash map (i.e., associative array)',
        );

        $this->assertThat(new ArrayIterator([1, 2, 3]), new MapMatchesKey('foo'));
    }

    public function testWhenKeyConstraintIsNotSatisfied(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            "Failed asserting that value is a hash map with a key that is equal to 'foo'",
        );

        $this->assertThat(['baz' => 'bar'], new MapMatchesKey('foo'));
    }

    public function testToStringReturnsAnEmptyString(): void
    {
        $constraint = new MapMatchesKey('foo');

        $this->assertSame('', $constraint->toString());
    }
}
