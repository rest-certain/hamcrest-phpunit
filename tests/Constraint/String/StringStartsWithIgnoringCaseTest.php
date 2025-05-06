<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\String;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\EmptyStringException;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\String\StringStartsWithIgnoringCase;
use RestCertain\Test\Hamcrest\Str;
use stdClass;

use function tmpfile;

class StringStartsWithIgnoringCaseTest extends TestCase
{
    #[DataProvider('successProvider')]
    public function testStringStartsWithIgnoringCaseSuccess(mixed $testValue, string $assertionValue): void
    {
        $this->assertThat($testValue, new StringStartsWithIgnoringCase($assertionValue));
    }

    /**
     * @return array<array{testValue: mixed, assertionValue: string}>
     */
    public static function successProvider(): array
    {
        $tempFile = tmpfile();

        return [
            ['testValue' => 'fOO', 'assertionValue' => 'Foo'],
            ['testValue' => new Str('fOO'), 'assertionValue' => 'Foo'],
            ['testValue' => 12345, 'assertionValue' => '123'],
            ['testValue' => 42.00001, 'assertionValue' => '42.0'],
            ['testValue' => true, 'assertionValue' => '1'],
            ['testValue' => $tempFile, 'assertionValue' => 'Resource ID'],
        ];
    }

    #[DataProvider('failureProvider')]
    public function testStringStartsWithIgnoringCaseFailure(mixed $testValue, string $assertionValue): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessageMatches('/Failed asserting that .* starts with \'(.*)\', ignoring case/s');

        $this->assertThat($testValue, new StringStartsWithIgnoringCase($assertionValue));
    }

    /**
     * @return array<array{testValue: mixed, assertionValue: string}>
     */
    public static function failureProvider(): array
    {
        $tempFile = tmpfile();

        return [
            ['testValue' => 'FOB', 'assertionValue' => 'Foo'],
            ['testValue' => new Str('FOB'), 'assertionValue' => 'Foo'],
            ['testValue' => 12345, 'assertionValue' => '124'],
            ['testValue' => 42.00001, 'assertionValue' => '42.1'],
            ['testValue' => true, 'assertionValue' => 'True'],
            ['testValue' => false, 'assertionValue' => '0'],
            ['testValue' => false, 'assertionValue' => ' '],
            ['testValue' => $tempFile, 'assertionValue' => 'Res ID'],
            ['testValue' => null, 'assertionValue' => ' '],
            ['testValue' => new stdClass(), 'assertionValue' => 'foo'],
            ['testValue' => ['foo'], 'assertionValue' => 'FOO'],
        ];
    }

    public function testEmptyStringException(): void
    {
        $this->expectException(EmptyStringException::class);

        new StringStartsWithIgnoringCase('');
    }
}
