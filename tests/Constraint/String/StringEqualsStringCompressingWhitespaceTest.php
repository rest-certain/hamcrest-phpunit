<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\String;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\String\StringEqualsStringCompressingWhitespace;

class StringEqualsStringCompressingWhitespaceTest extends TestCase
{
    #[DataProvider('successProvider')]
    public function testStringEqualsStringCompressingWhitespace(string $testValue, string $value): void
    {
        $this->assertThat($testValue, new StringEqualsStringCompressingWhitespace($value));
    }

    /**
     * @return array<array{testValue: string, value: string}>
     */
    public static function successProvider(): array
    {
        return [
            ['testValue' => "   FOO \t   bar \n   BAZ   \n", 'value' => 'foo bar baz'],
            ['testValue' => "  \n \t \v   \n", 'value' => ''],
            ['testValue' => '', 'value' => ''],
        ];
    }

    public function testFailsForNonString(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage("Failed asserting that null equals 'foo bar baz' when compressing whitespace");

        $this->assertThat(null, new StringEqualsStringCompressingWhitespace('foo bar baz'));
    }

    public function testFailureMessageWhenValueContainsNewline(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage("Failed asserting that 'foo' equals <text> when compressing whitespace");

        $this->assertThat('foo', new StringEqualsStringCompressingWhitespace("foo\nbar\nbaz"));
    }
}
