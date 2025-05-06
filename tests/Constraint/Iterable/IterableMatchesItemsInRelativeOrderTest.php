<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\Iterable;

use ArrayIterator;
use InvalidArgumentException;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\Iterable\IterableMatchesItemsInRelativeOrder;

class IterableMatchesItemsInRelativeOrderTest extends TestCase
{
    /**
     * @param mixed[] $constraints
     */
    #[DataProvider('iterableSuccessProvider')]
    public function testIterableMatchesItemsInRelativeOrder(mixed $testValue, array $constraints): void
    {
        $this->assertThat($testValue, new IterableMatchesItemsInRelativeOrder(...$constraints));
    }

    /**
     * @return array<array{testValue: mixed, constraints: mixed[]}>
     */
    public static function iterableSuccessProvider(): array
    {
        return [
            ['testValue' => [1, 2, 3, 4, 5], 'constraints' => [2, 4]],
            ['testValue' => ['a', 'b', 'c', 'd', 'e'], 'constraints' => [new IsEqual('b'), new IsEqual('d')]],
            [
                'testValue' => new ArrayIterator(['a', 'b', 'c', 'd', 'e']),
                'constraints' => [new IsEqual('b'), new IsEqual('d')],
            ],
        ];
    }

    public function testWhenConstraintsAreNotSatisfiedInRelativeOrder(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            "Failed asserting that value is an iterable where each item (in relative order) is equal to 'b' and is "
            . "equal to 'd'",
        );

        $this->assertThat(
            new ArrayIterator(['a', 'd', 'b', 'c', 'e']),
            new IterableMatchesItemsInRelativeOrder(new IsEqual('b'), new IsEqual('d')),
        );
    }

    public function testWhenOtherIsNotAnIterable(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an iterable');

        $this->assertThat('foo', new IterableMatchesItemsInRelativeOrder(new IsEqual('b'), new IsEqual('d')));
    }

    public function testWhenNoConstraintsAreProvided(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one constraint must be provided');

        new IterableMatchesItemsInRelativeOrder();
    }
}
