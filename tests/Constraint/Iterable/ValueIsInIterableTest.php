<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\Iterable;

use ArrayIterator;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\Iterable\ValueIsInIterable;

class ValueIsInIterableTest extends TestCase
{
    /**
     * @param iterable<mixed> $collection
     */
    #[DataProvider('successProvider')]
    public function testValueIsInIterable(mixed $testValue, iterable $collection, bool $strict = false): void
    {
        $this->assertThat($testValue, new ValueIsInIterable($collection, $strict));
    }

    /**
     * @return array<array{testValue: mixed, collection: iterable<mixed>}>
     */
    public static function successProvider(): array
    {
        $object = (object) ['foo' => 'bar'];

        return [
            ['testValue' => 'baz', 'collection' => ['foo', 'bar', 'baz']],
            ['testValue' => '456', 'collection' => new ArrayIterator([123, 456, 789])],
            ['testValue' => '789', 'collection' => ['a' => 123, 'b' => 456, 'c' => 789]],
            [
                'testValue' => (object) ['foo' => 'bar'],
                'collection' => new ArrayIterator(['a' => (object) ['foo' => 'bar']]),
            ],
            [
                'testValue' => $object,
                'collection' => new ArrayIterator(['a' => $object]),
                'strict' => true,
            ],
        ];
    }

    public function testWhenStrictComparisonFails(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('is found in the iterable (using strict comparison)');

        $this->assertThat(
            (object) ['foo' => 'bar'],
            new ValueIsInIterable(new ArrayIterator(['a' => (object) ['foo' => 'bar']]), true),
        );
    }

    public function testNonStrictComparisonFails(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('is found in the iterable');

        $this->assertThat(1234, new ValueIsInIterable(['a' => 123, 'b' => 456, 'c' => 789]));
    }
}
