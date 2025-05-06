<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Constraint\String;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\EmptyStringException;
use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Constraint\String\StringEndsWithIgnoringCase;
use RestCertain\Test\Hamcrest\Str;
use stdClass;

use function tmpfile;

class StringEndsWithIgnoringCaseTest extends TestCase
{
    #[DataProvider('successProvider')]
    public function testStringEndsWithIgnoringCaseSuccess(mixed $testValue, string $assertionValue): void
    {
        $this->assertThat($testValue, new StringEndsWithIgnoringCase($assertionValue));
    }

    /**
     * @return array<array{testValue: mixed, assertionValue: string}>
     */
    public static function successProvider(): array
    {
        return [
            ['testValue' => 'foo bAR', 'assertionValue' => 'Bar'],
            ['testValue' => new Str('foo bAR'), 'assertionValue' => 'BAR'],
            ['testValue' => 12345, 'assertionValue' => '45'],
            ['testValue' => 42.00001, 'assertionValue' => '001'],
            ['testValue' => true, 'assertionValue' => '1'],
        ];
    }

    #[DataProvider('failureProvider')]
    public function testStringEndsWithIgnoringCaseFailure(mixed $testValue, string $assertionValue): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessageMatches('/Failed asserting that .* ends with \'(.*)\', ignoring case/s');

        $this->assertThat($testValue, new StringEndsWithIgnoringCase($assertionValue));
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
            ['testValue' => 12345, 'assertionValue' => '245'],
            ['testValue' => 42.00001, 'assertionValue' => '011'],
            ['testValue' => true, 'assertionValue' => 'True'],
            ['testValue' => false, 'assertionValue' => '0'],
            ['testValue' => false, 'assertionValue' => ' '],
            ['testValue' => $tempFile, 'assertionValue' => 'anID'],
            ['testValue' => null, 'assertionValue' => ' '],
            ['testValue' => new stdClass(), 'assertionValue' => 'foo'],
            ['testValue' => ['foo'], 'assertionValue' => 'FOO'],
        ];
    }

    public function testEmptyStringException(): void
    {
        $this->expectException(EmptyStringException::class);

        new StringEndsWithIgnoringCase('');
    }
}
