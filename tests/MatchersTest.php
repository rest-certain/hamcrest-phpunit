<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest;

use ArrayIterator;
use EmptyIterator;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Constraint\GreaterThan;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Framework\Constraint\StringContains;
use PHPUnit\Framework\Constraint\StringEndsWith;
use PHPUnit\Framework\Constraint\StringStartsWith;
use PHPUnit\Framework\NativeType;
use PHPUnit\Framework\TestCase;
use Stringable;
use stdClass;

use function RestCertain\Hamcrest\aMapWithSize;
use function RestCertain\Hamcrest\additionallyDescribedAs;
use function RestCertain\Hamcrest\allOf;
use function RestCertain\Hamcrest\anArray;
use function RestCertain\Hamcrest\anEmptyMap;
use function RestCertain\Hamcrest\anInstanceOf;
use function RestCertain\Hamcrest\any;
use function RestCertain\Hamcrest\anyOf;
use function RestCertain\Hamcrest\anything;
use function RestCertain\Hamcrest\arrayContaining;
use function RestCertain\Hamcrest\arrayContainingInAnyOrder;
use function RestCertain\Hamcrest\arrayWithSize;
use function RestCertain\Hamcrest\assertThat;
use function RestCertain\Hamcrest\blankOrNullString;
use function RestCertain\Hamcrest\blankString;
use function RestCertain\Hamcrest\both;
use function RestCertain\Hamcrest\closeTo;
use function RestCertain\Hamcrest\contains;
use function RestCertain\Hamcrest\containsInAnyOrder;
use function RestCertain\Hamcrest\containsInRelativeOrder;
use function RestCertain\Hamcrest\containsString;
use function RestCertain\Hamcrest\containsStringIgnoringCase;
use function RestCertain\Hamcrest\describedAs;
use function RestCertain\Hamcrest\either;
use function RestCertain\Hamcrest\emptyArray;
use function RestCertain\Hamcrest\emptyIterable;
use function RestCertain\Hamcrest\emptyOrNullString;
use function RestCertain\Hamcrest\emptyString;
use function RestCertain\Hamcrest\endsWith;
use function RestCertain\Hamcrest\endsWithIgnoringCase;
use function RestCertain\Hamcrest\equalTo;
use function RestCertain\Hamcrest\equalToCompressingWhitespace;
use function RestCertain\Hamcrest\equalToIgnoringCase;
use function RestCertain\Hamcrest\equalToIgnoringWhiteSpace;
use function RestCertain\Hamcrest\equalToObject;
use function RestCertain\Hamcrest\everyItem;
use function RestCertain\Hamcrest\greaterThan;
use function RestCertain\Hamcrest\greaterThanOrEqualTo;
use function RestCertain\Hamcrest\hasEntry;
use function RestCertain\Hamcrest\hasItem;
use function RestCertain\Hamcrest\hasItemInArray;
use function RestCertain\Hamcrest\hasItems;
use function RestCertain\Hamcrest\hasKey;
use function RestCertain\Hamcrest\hasSize;
use function RestCertain\Hamcrest\hasValue;
use function RestCertain\Hamcrest\in;
use function RestCertain\Hamcrest\is;
use function RestCertain\Hamcrest\isA;
use function RestCertain\Hamcrest\isAn;
use function RestCertain\Hamcrest\isEmpty;
use function RestCertain\Hamcrest\iterableWithSize;
use function RestCertain\Hamcrest\lessThan;
use function RestCertain\Hamcrest\lessThanOrEqualTo;
use function RestCertain\Hamcrest\matchesPattern;
use function RestCertain\Hamcrest\matchesRegex;
use function RestCertain\Hamcrest\not;
use function RestCertain\Hamcrest\notANumber;
use function RestCertain\Hamcrest\notNullValue;
use function RestCertain\Hamcrest\nullValue;
use function RestCertain\Hamcrest\oneOf;
use function RestCertain\Hamcrest\sameInstance;
use function RestCertain\Hamcrest\startsWith;
use function RestCertain\Hamcrest\startsWithIgnoringCase;
use function RestCertain\Hamcrest\theInstance;
use function fopen;
use function sqrt;

class MatchersTest extends TestCase
{
    public function testAdditionallyDescribedAs(): void
    {
        assertThat(
            'foo',
            additionallyDescribedAs(
                'The string should start with "%s".',
                new StringStartsWith('f'),
                'f',
            ),
        );
    }

    public function testAdditionallyDescribedAsWithFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            "Failed asserting that 'bar' starts with \"f\".\nThe string should start with \"f\".",
        );

        assertThat(
            'bar',
            additionallyDescribedAs(
                'The string should start with "%s".',
                new StringStartsWith('f'),
                'f',
            ),
        );
    }

    public function testAllOf(): void
    {
        assertThat('myValue', allOf(new StringStartsWith('my'), new StringContains('Val')));
    }

    public function testAMapWithSize(): void
    {
        assertThat(['foo' => 'bar'], is(aMapWithSize(equalTo(1))));
    }

    public function testAnArray(): void
    {
        assertThat([1, 2, 3], is(anArray(equalTo(1), equalTo(2), equalTo(3))));
    }

    public function testAnEmptyMap(): void
    {
        assertThat([], is(anEmptyMap()));
    }

    public function testAnInstanceOf(): void
    {
        assertThat(new Str('myValue'), anInstanceOf(Str::class));
    }

    public function testAny(): void
    {
        assertThat(new Str('myValue'), any(Stringable::class));
    }

    public function testAnyOf(): void
    {
        assertThat('myValue', anyOf(new StringStartsWith('foo'), new StringContains('Val')));
    }

    public function testAnything(): void
    {
        assertThat('any value', is(anything()));
    }

    public function testArrayContaining(): void
    {
        assertThat([1, 2, 3], is(arrayContaining(equalTo(1), equalTo(2), equalTo(3))));
    }

    public function testArrayContainingInAnyOrder(): void
    {
        assertThat([2, 1, 3], is(arrayContainingInAnyOrder(equalTo(1), equalTo(2), equalTo(3))));
    }

    public function testArrayWithSize(): void
    {
        assertThat(['foo', 'bar'], is(arrayWithSize(equalTo(2))));
        assertThat(new ArrayIterator(['foo', 'bar']), is(arrayWithSize(2)));
        assertThat(['foo', 'bar'], is(arrayWithSize(new GreaterThan(1))));
        assertThat(['foo', 'bar'], is(arrayWithSize(new LessThan(3))));
    }

    public function testAssertThat(): void
    {
        assertThat('myValue', new StringStartsWith('my'));
        assertThat('myValue', 'myValue');
    }

    public function testBlankOrNullString(): void
    {
        assertThat("  \n \t \v   \n", is(blankOrNullString()));
        assertThat(null, is(blankOrNullString()));
    }

    public function testBlankString(): void
    {
        assertThat("  \n \t \v   \n", is(blankString()));
    }

    public function testBoth(): void
    {
        assertThat('fab', both(new StringContains('a'))->and(new StringContains('b')));
    }

    public function testCloseTo(): void
    {
        assertThat(1.029, is(closeTo(1.0, 0.03)));
        assertThat(0.971, is(closeTo(1.0, 0.03)));
        assertThat('1.029', is(closeTo(1.0, 0.03)));
        assertThat('0.971', is(closeTo(1.0, 0.03)));
        assertThat(1, is(closeTo(1, 0.03)));
        assertThat('1', is(closeTo(1, 0.03)));
    }

    public function testContains(): void
    {
        assertThat(new ArrayIterator(['foo', 'bar']), contains(equalTo('foo'), equalTo('bar')));
    }

    public function testContainsInAnyOrder(): void
    {
        assertThat(new ArrayIterator(['foo', 'bar']), containsInAnyOrder(equalTo('bar'), equalTo('foo')));
    }

    public function testContainsInRelativeOrder(): void
    {
        assertThat(['a', 'b', 'c', 'd', 'e'], containsInRelativeOrder(equalTo('b'), equalTo('d')));
    }

    public function testContainsString(): void
    {
        assertThat('myStringOfNote', containsString('ring'));
    }

    public function testContainsStringIgnoringCase(): void
    {
        assertThat('myStringOfNote', containsStringIgnoringCase('Ring'));
    }

    public function testDescribedAs(): void
    {
        assertThat('foo', describedAs('value is a string that starts with "%s"', new StringStartsWith('f'), 'f'));
    }

    public function testDescribedAsWithFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that value is a string that starts with "f"');

        assertThat('bar', describedAs('value is a string that starts with "%s"', new StringStartsWith('f'), 'f'));
    }

    public function testEither(): void
    {
        assertThat('fan', either(new StringContains('a'))->or(new StringContains('b')));
    }

    public function testEmptyArray(): void
    {
        assertThat([], is(emptyArray()));
    }

    public function testEmptyIterable(): void
    {
        assertThat(new ArrayIterator(), is(emptyIterable()));
    }

    public function testEmptyOrNullString(): void
    {
        assertThat(null, is(emptyOrNullString()));
        assertThat('', is(emptyOrNullString()));
    }

    public function testEmptyString(): void
    {
        assertThat('', is(emptyString()));
    }

    public function testEndsWith(): void
    {
        assertThat('myStringOfNote', endsWith('Note'));
    }

    public function testEndsWithIgnoringCase(): void
    {
        assertThat('myStringOfNote', endsWithIgnoringCase('note'));
    }

    public function testEqualTo(): void
    {
        assertThat('foo', equalTo('foo'));
        assertThat((object) ['foo' => 'bar'], equalTo((object) ['foo' => 'bar']));
    }

    public function testEqualToCompressingWhitespace(): void
    {
        assertThat("   my\tfoo  bar ", equalToCompressingWhitespace(' my  foo bar'));
    }

    public function testEqualToIgnoringCase(): void
    {
        assertThat('Foo', equalToIgnoringCase('FOO'));
    }

    public function testEqualToIgnoringWhitespace(): void
    {
        assertThat("   my\tfoo  bar ", equalToIgnoringWhiteSpace(' my  foo bar'));
    }

    public function testEqualToObject(): void
    {
        assertThat((object) ['foo' => 'bar'], equalToObject((object) ['foo' => 'bar']));
    }

    public function testEveryItem(): void
    {
        assertThat(['bar', 'baz'], everyItem(new StringStartsWith('ba')));
    }

    public function testGreaterThan(): void
    {
        assertThat(2, is(greaterThan(1)));
    }

    public function testGreaterThanOrEqualTo(): void
    {
        assertThat(1, is(greaterThanOrEqualTo(1)));
    }

    public function testHasEntry(): void
    {
        assertThat(['bar' => 'foo'], hasEntry(equalTo('bar'), equalTo('foo')));
    }

    public function testHasItem(): void
    {
        assertThat(new ArrayIterator(['foo', 'bar']), hasItem(new StringStartsWith('ba')));
    }

    public function testHasItemInArray(): void
    {
        assertThat(['foo', 'bar'], hasItemInArray(new StringStartsWith('ba')));
    }

    public function testHasItems(): void
    {
        assertThat(['foo', 'bar', 'baz'], hasItems(new StringEndsWith('z'), new StringEndsWith('o')));
    }

    public function testHasKey(): void
    {
        assertThat(['foo' => 'bar'], hasKey(equalTo('foo')));
    }

    public function testHasLength(): void
    {
        $this->markTestIncomplete('Not implemented yet');
    }

    public function testHasProperty(): void
    {
        $this->markTestIncomplete('Not implemented yet');
    }

    public function testHasSize(): void
    {
        assertThat(new ArrayIterator(['foo', 'bar']), hasSize(equalTo(2)));
    }

    public function testHasToString(): void
    {
        $this->markTestIncomplete('Not implemented yet');
    }

    public function testHasValue(): void
    {
        assertThat(new ArrayIterator(['foo' => 'bar']), hasValue(equalTo('bar')));
    }

    public function testHasXPath(): void
    {
        $this->markTestIncomplete('Not implemented yet');
    }

    public function testIn(): void
    {
        assertThat('foo', is(in(['bar', 'foo'])));
    }

    public function testIs(): void
    {
        assertThat('foo', is(new IsType(NativeType::String)));
    }

    /**
     * @param class-string | "array" | "bool" | "callable" | "float" | "int" | "iterable" | "null" | "numeric" | "object" | "resource" | "scalar" | "string" $type
     */
    #[DataProvider('isAProvider')]
    public function testIsA(mixed $value, string $type): void
    {
        assertThat($value, isA($type));
    }

    /**
     * @param class-string | "array" | "bool" | "callable" | "float" | "int" | "iterable" | "null" | "numeric" | "object" | "resource" | "scalar" | "string" $type
     */
    #[DataProvider('isAProvider')]
    public function testIsAn(mixed $value, string $type): void
    {
        assertThat($value, isAn($type));
    }

    /**
     * @return array<array{value: mixed, type: string}>
     */
    public static function isAProvider(): array
    {
        return [
            ['value' => new Str('foo'), 'type' => Str::class],
            ['value' => ['foo', 'bar'], 'type' => 'array'],
            ['value' => true, 'type' => 'bool'],
            ['value' => false, 'type' => 'bool'],
            ['value' => fn () => true, 'type' => 'callable'],
            ['value' => 1.0, 'type' => 'float'],
            ['value' => 1, 'type' => 'int'],
            ['value' => new ArrayIterator(['foo', 'bar']), 'type' => 'iterable'],
            ['value' => null, 'type' => 'null'],
            ['value' => 1.0, 'type' => 'numeric'],
            ['value' => 1, 'type' => 'numeric'],
            ['value' => '1', 'type' => 'numeric'],
            ['value' => new stdClass(), 'type' => 'object'],
            ['value' => fopen('php://temp', 'r'), 'type' => 'resource'],
            ['value' => 'foo', 'type' => 'scalar'],
            ['value' => 1, 'type' => 'scalar'],
            ['value' => 1.0, 'type' => 'scalar'],
            ['value' => true, 'type' => 'scalar'],
            ['value' => false, 'type' => 'scalar'],
            ['value' => 'foo', 'type' => 'string'],
        ];
    }

    public function testIsEmpty(): void
    {
        assertThat([], isEmpty());
        assertThat(new ArrayIterator(), isEmpty());
        assertThat(new EmptyIterator(), isEmpty());
        assertThat('', isEmpty());
        assertThat(false, isEmpty());
        assertThat(null, isEmpty());
        assertThat(0, isEmpty());
    }

    public function testIterableWithSize(): void
    {
        assertThat(new ArrayIterator(['foo', 'bar']), iterableWithSize(equalTo(2)));
    }

    public function testLessThan(): void
    {
        assertThat(1, is(lessThan(2)));
    }

    public function testLessThanOrEqualTo(): void
    {
        assertThat(1, is(lessThanOrEqualTo(1)));
    }

    public function testMatchesPattern(): void
    {
        assertThat('abc', matchesPattern('/^[a-z]+$/'));
    }

    public function testMatchesRegex(): void
    {
        assertThat('abc', matchesRegex('/^[a-z]+$/'));
    }

    public function testNot(): void
    {
        assertThat('foo', is(not(equalTo('bar'))));
    }

    public function testNotANumber(): void
    {
        assertThat(sqrt(-1), is(notANumber()));
    }

    public function testNotNullValue(): void
    {
        assertThat('foo', is(notNullValue()));
    }

    public function testNullValue(): void
    {
        assertThat(null, is(nullValue()));
    }

    public function testOneOf(): void
    {
        assertThat('foo', is(oneOf('bar', 'foo')));
    }

    public function testSameInstance(): void
    {
        $foo = new Str('foo');

        assertThat($foo, sameInstance($foo));
    }

    public function testStartsWith(): void
    {
        assertThat('myStringOfNote', startsWith('my'));
    }

    public function testStartsWithIgnoringCase(): void
    {
        assertThat('myStringOfNote', startsWithIgnoringCase('My'));
    }

    public function testStringContainsInOrder(): void
    {
        $this->markTestIncomplete('Not implemented yet');
    }

    public function testTheInstance(): void
    {
        $foo = new Str('foo');

        assertThat($foo, is(theInstance($foo)));
    }
}
