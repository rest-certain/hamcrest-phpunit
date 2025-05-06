<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\Iterable;

use ArrayIterator;
use InvalidArgumentException;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\Iterable\IterableMatchesItemsInOrder;

use function RestCertain\Hamcrest\equalTo;
use function RestCertain\Hamcrest\equalToObject;
use function RestCertain\Hamcrest\notNullValue;
use function RestCertain\Hamcrest\startsWith;

class IterableMatchesItemsInOrderTest extends TestCase
{
    /**
     * @param mixed[] $constraints
     */
    #[DataProvider('iterableSuccessProvider')]
    public function testIterableMatchesItemsInOrder(mixed $testValue, array $constraints): void
    {
        $this->assertThat($testValue, new IterableMatchesItemsInOrder(...$constraints));
    }

    /**
     * @return array<array{testValue: mixed, constraints: mixed[]}>
     */
    public static function iterableSuccessProvider(): array
    {
        return [
            [
                'testValue' => [1, 2, 3],
                'constraints' => [1, 2, 3],
            ],
            [
                'testValue' => [1, 'foo', (object) ['foo' => 'bar']],
                'constraints' => [
                    equalTo(1),
                    startsWith('f'),
                    equalToObject((object) ['foo' => 'bar']),
                ],
            ],
            [
                'testValue' => new ArrayIterator([1, 2, 3]),
                'constraints' => [
                    notNullValue(),
                    '2',
                    new IsIdentical(3),
                ],
            ],
        ];
    }

    public function testConstraintFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that value is an iterable where each item (in order) is '
            . 'equal to 1 and is equal to 2 and is equal to 3',
        );

        $this->assertThat(
            [1, 2, 4],
            new IterableMatchesItemsInOrder(equalTo(1), equalTo(2), equalTo(3)),
        );
    }

    public function testLengthFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an iterable that contains 3 items');

        $this->assertThat(
            [1, 2],
            new IterableMatchesItemsInOrder(equalTo(1), equalTo(2), equalTo(3)),
        );
    }

    public function testNonIterableFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an iterable');

        $this->assertThat(
            'foo bar',
            new IterableMatchesItemsInOrder(equalTo(1), equalTo(2), equalTo(3)),
        );
    }

    public function testNoConstraintsFailure(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one constraint must be provided');

        new IterableMatchesItemsInOrder();
    }
}
