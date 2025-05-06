<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\Iterable;

use ArrayIterator;
use InvalidArgumentException;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Constraint\GreaterThan;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Framework\Constraint\LogicalOr;
use PHPUnit\Framework\NativeType;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\Iterable\IterableContainsItemsMatching;

class IterableContainsItemsMatchingTest extends TestCase
{
    /**
     * @param mixed[] $testValue
     * @param mixed[] $constraints
     */
    #[DataProvider('successProvider')]
    public function testIterableContainsItemsMatchingSuccess(iterable $testValue, array $constraints): void
    {
        $this->assertThat($testValue, new IterableContainsItemsMatching(...$constraints));
    }

    /**
     * @return array<array{testValue: iterable<mixed>, constraints: mixed[]}>
     */
    public static function successProvider(): array
    {
        return [
            [
                'testValue' => [1, 2, 3],
                'constraints' => [2],
            ],
            [
                'testValue' => [1, 2, 3],
                'constraints' => [
                    new IsType(NativeType::Numeric),
                    LogicalOr::fromConstraints(new GreaterThan(1), new IsEqual(1)),
                    new LessThan(10),
                ],
            ],
            [
                'testValue' => new ArrayIterator([1, 2, 3]),
                'constraints' => [
                    new IsType(NativeType::Numeric),
                    LogicalOr::fromConstraints(new GreaterThan(1), new IsEqual(1)),
                    new LessThan(10),
                    2,
                    '3',
                ],
            ],
        ];
    }

    public function testIterableContainsItemsMatchingFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that value is an iterable that contains an item that is of type numeric or is greater '
            . 'than 1 or is equal to 1 or is less than 1',
        );

        $this->assertThat([1, 2, 3], new IterableContainsItemsMatching(
            new IsType(NativeType::Numeric),
            LogicalOr::fromConstraints(new GreaterThan(1), new IsEqual(1)),
            new LessThan(1),
        ));
    }

    public function testFailureWhenTestValueIsNotIterable(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an iterable');

        $this->assertThat('foo', new IterableContainsItemsMatching('foo'));
    }

    public function testFailureWhenNoConstraintsAreProvided(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one constraint must be provided');

        new IterableContainsItemsMatching();
    }
}
