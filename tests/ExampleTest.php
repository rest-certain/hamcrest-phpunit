<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest;

use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsEqualIgnoringCase;
use PHPUnit\Framework\Constraint\StringEndsWith;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Matchers;

use function RestCertain\Hamcrest\arrayContainingInAnyOrder;
use function RestCertain\Hamcrest\assertThat;
use function RestCertain\Hamcrest\both;
use function RestCertain\Hamcrest\containsStringIgnoringCase;
use function RestCertain\Hamcrest\hasItem;
use function RestCertain\Hamcrest\isA;
use function RestCertain\Hamcrest\startsWith;
use function RestCertain\Hamcrest\startsWithIgnoringCase;

class ExampleTest extends TestCase
{
    public function testExample1(): void
    {
        assertThat(
            'The quick brown fox jumps over the lazy dog',
            both(isA('string'))
                ->and(startsWithIgnoringCase('the'))
                ->and(containsStringIgnoringCase('FOX')),
        );
    }

    public function testExample2(): void
    {
        Matchers::assertThat(
            'The quick brown fox jumps over the lazy dog',
            Matchers::both(Matchers::isA('string'))
                ->and(Matchers::startsWithIgnoringCase('the'))
                ->and(Matchers::containsStringIgnoringCase('FOX')),
        );
    }

    public function testExample3(): void
    {
        $this->assertThat(
            'The quick brown fox jumps over the lazy dog',
            both(isA('string'))
                ->and(startsWithIgnoringCase('the'))
                ->and(containsStringIgnoringCase('FOX')),
        );
    }

    public function testExample4(): void
    {
        $this->assertThat(['foo', 'bar', 'baz'], hasItem(startsWith('ba')));
    }

    public function testExample5(): void
    {
        assertThat(
            ['foo', 'bar', 'baz'],
            arrayContainingInAnyOrder(
                new StringEndsWith('z'),
                new IsEqualIgnoringCase('FOO'),
                new IsEqual('bar'),
            ),
        );
    }
}
