<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\Map;

use ArrayIterator;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Constraint\GreaterThan;
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Framework\Constraint\LogicalAnd;
use PHPUnit\Framework\Constraint\StringContains;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\Map\MapMatchesKeyValuePair;

class MapMatchesKeyValuePairTest extends TestCase
{
    #[DataProvider('successProvider')]
    public function testMapMatchesKeyValuePair(mixed $testValue, mixed $keyConstraint, mixed $valueConstraint): void
    {
        $this->assertThat($testValue, new MapMatchesKeyValuePair($keyConstraint, $valueConstraint));
    }

    /**
     * @return array<array{testValue: mixed, keyConstraint: mixed, valueConstraint: mixed}>
     */
    public static function successProvider(): array
    {
        return [
            [
                'testValue' => ['foo' => 'bar'],
                'keyConstraint' => 'foo',
                'valueConstraint' => 'bar',
            ],
            [
                'testValue' => new ArrayIterator(['foo' => 123, 'bar' => 456, 'baz' => 789]),
                'keyConstraint' => new StringContains('r'),
                'valueConstraint' => LogicalAnd::fromConstraints(new GreaterThan(400), new LessThan(500)),
            ],
        ];
    }

    public function testWhenOtherIsNotAnArray(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that value is a hash map (i.e., associative array)',
        );

        $this->assertThat('foo', new MapMatchesKeyValuePair('foo', 'bar'));
    }

    public function testWhenOtherIsAnArrayList(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that value is a hash map (i.e., associative array)',
        );

        $this->assertThat([1, 2, 3], new MapMatchesKeyValuePair('foo', 'bar'));
    }

    public function testWhenOtherIsAnIteratorList(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that value is a hash map (i.e., associative array)',
        );

        $this->assertThat(new ArrayIterator([1, 2, 3]), new MapMatchesKeyValuePair('foo', 'bar'));
    }

    public function testWhenKeyConstraintIsNotSatisfied(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            "Failed asserting that value is a hash map with a key that is equal to 'foo' "
            . "having a value that is equal to 'bar'",
        );

        $this->assertThat(['baz' => 'bar'], new MapMatchesKeyValuePair('foo', 'bar'));
    }

    public function testWhenValueConstraintIsNotSatisfied(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            "Failed asserting that value is a hash map with a key that is equal to 'foo' "
            . "having a value that is equal to 'bar'",
        );

        $this->assertThat(['foo' => 'baz'], new MapMatchesKeyValuePair('foo', 'bar'));
    }

    public function testToStringReturnsAnEmptyString(): void
    {
        $constraint = new MapMatchesKeyValuePair('foo', 'bar');

        $this->assertSame('', $constraint->toString());
    }
}
