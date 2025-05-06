<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\Iterable;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\NativeType;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\Iterable\IterableMatchesEveryItem;

use function RestCertain\Hamcrest\assertThat;

class IterableEveryItemMatchesTest extends TestCase
{
    public function testIterableEveryItemMatches(): void
    {
        assertThat([1, 2, 3], new IterableMatchesEveryItem(new IsType(NativeType::Int)));
    }

    public function testIterableEveryItemMatchesWhenArgumentIsAConstant(): void
    {
        assertThat(['foo', 'foo', 'foo'], new IterableMatchesEveryItem('foo'));
    }

    public function testIterableEveryItemMatchesWhenEveryItemDoesNotMatch(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an iterable in which each item is of type int');

        assertThat([1, 'foo', 3], new IterableMatchesEveryItem(new IsType(NativeType::Int)));
    }

    public function testIterableEveryItemMatchesWhenValueIsNotAnIterable(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is an iterable');

        assertThat('foo bar', new IterableMatchesEveryItem(new IsType(NativeType::Int)));
    }
}
