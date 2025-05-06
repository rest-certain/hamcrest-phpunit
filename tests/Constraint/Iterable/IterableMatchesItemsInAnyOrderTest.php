<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\Iterable;

use ArrayIterator;
use InvalidArgumentException;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\Iterable\IterableMatchesItemsInAnyOrder;

use function RestCertain\Hamcrest\equalTo;
use function RestCertain\Hamcrest\equalToObject;
use function RestCertain\Hamcrest\notNullValue;
use function RestCertain\Hamcrest\startsWith;

class IterableMatchesItemsInAnyOrderTest extends TestCase
{
    /**
     * @param mixed[] $constraints
     */
    #[DataProvider('iterableSuccessProvider')]
    public function testIterableMatchesItemsInOrder(mixed $testValue, array $constraints): void
    {
        $this->assertThat($testValue, new IterableMatchesItemsInAnyOrder(...$constraints));
    }

    /**
     * @return array<array{testValue: mixed, constraints: mixed[]}>
     */
    public static function iterableSuccessProvider(): array
    {
        return [
            [
                'testValue' => [2, 1, 3],
                'constraints' => [1, 2, 3],
            ],
            [
                'testValue' => [1, 'foo', (object) ['foo' => 'bar']],
                'constraints' => [
                    equalToObject((object) ['foo' => 'bar']),
                    equalTo(1),
                    startsWith('f'),
                ],
            ],
            [
                'testValue' => new ArrayIterator([1, 2, 3]),
                'constraints' => [
                    new IsIdentical(3),
                    '2',
                    notNullValue(),
                ],
            ],
        ];
    }

    public function testConstraintFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that value is an iterable where each item (in any order) is '
            . 'equal to 1 and is equal to 2 and is equal to 3',
        );

        $this->assertThat(
            [4, 1, 2],
            new IterableMatchesItemsInAnyOrder(equalTo(1), equalTo(2), equalTo(3)),
        );
    }

    public function testLengthFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an iterable that contains 3 items');

        $this->assertThat(
            [2, 1],
            new IterableMatchesItemsInAnyOrder(equalTo(1), equalTo(2), equalTo(3)),
        );
    }

    public function testNonIterableFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an iterable');

        $this->assertThat(
            'foo bar',
            new IterableMatchesItemsInAnyOrder(equalTo(1), equalTo(2)),
        );
    }

    public function testNoConstraintsFailure(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one constraint must be provided');

        new IterableMatchesItemsInAnyOrder();
    }
}
