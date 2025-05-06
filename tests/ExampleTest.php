<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest;

use PHPUnit\Framework\TestCase;

use function RestCertain\Hamcrest\assertThat;
use function RestCertain\Hamcrest\both;
use function RestCertain\Hamcrest\containsStringIgnoringCase;
use function RestCertain\Hamcrest\isA;
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
}
