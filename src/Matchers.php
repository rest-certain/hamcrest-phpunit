<?php

/**
 * This file is part of rest-certain/hamcrest-phpunit
 *
 * rest-certain/hamcrest-phpunit is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the License
 * or (at your option) any later version.
 *
 * rest-certain/hamcrest-phpunit is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser
 * General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with rest-certain/hamcrest-phpunit. If not, see
 * <https://www.gnu.org/licenses/>.
 *
 * @copyright Copyright (c) REST Certain Contributors <https://rest-certain.dev>
 * @license https://opensource.org/license/lgpl-3-0/ GNU Lesser General Public License version 3 or later
 */

declare(strict_types=1);

namespace RestCertain\Hamcrest;

use LogicException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\GreaterThan;
use PHPUnit\Framework\Constraint\IsEmpty;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsEqualIgnoringCase;
use PHPUnit\Framework\Constraint\IsEqualWithDelta;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Constraint\IsNan;
use PHPUnit\Framework\Constraint\IsNull;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Framework\Constraint\LogicalAnd;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\LogicalOr;
use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\Constraint\StringContains;
use PHPUnit\Framework\Constraint\StringEndsWith;
use PHPUnit\Framework\Constraint\StringStartsWith;
use PHPUnit\Framework\NativeType;
use RestCertain\Hamcrest\Constraint\Cardinality\GreaterThanOrEqualTo;
use RestCertain\Hamcrest\Constraint\CombinedConstraint;
use RestCertain\Hamcrest\Constraint\DescribedConstraint;
use RestCertain\Hamcrest\Constraint\IsAnything;
use RestCertain\Hamcrest\Constraint\Iterable\IterableContainsItemsMatching;
use RestCertain\Hamcrest\Constraint\Iterable\IterableMatchesEveryItem;
use RestCertain\Hamcrest\Constraint\Iterable\IterableMatchesItemsInAnyOrder;
use RestCertain\Hamcrest\Constraint\Iterable\IterableMatchesItemsInOrder;
use RestCertain\Hamcrest\Constraint\Iterable\IterableMatchesItemsInRelativeOrder;
use RestCertain\Hamcrest\Constraint\Iterable\IterableSizeMatches;
use RestCertain\Hamcrest\Constraint\Iterable\ValueIsInIterable;
use RestCertain\Hamcrest\Constraint\Map\MapMatchesKey;
use RestCertain\Hamcrest\Constraint\Map\MapMatchesKeyValuePair;
use RestCertain\Hamcrest\Constraint\Map\MapMatchesValue;
use RestCertain\Hamcrest\Constraint\Map\MapSizeMatches;
use RestCertain\Hamcrest\Constraint\String\StringEndsWithIgnoringCase;
use RestCertain\Hamcrest\Constraint\String\StringEqualsStringCompressingWhitespace;
use RestCertain\Hamcrest\Constraint\String\StringStartsWithIgnoringCase;
use RestCertain\Hamcrest\Xml\NamespaceContext;

use function array_is_list;
use function array_map;
use function array_values;
use function assert;

final class Matchers
{
    /**
     * Creates a matcher that matches if the examined value matches **ALL** of the specified matchers.
     *
     * For example:
     *
     * ```
     * assertThat('myValue', allOf(startsWith('my'), containsString('Val')));
     * ```
     *
     * @param mixed ...$matchers Values or {@see Constraint}s that must pass for the examined value to match.
     */
    public static function allOf(mixed ...$matchers): Constraint
    {
        return LogicalAnd::fromConstraints(...array_values($matchers));
    }

    /**
     * Creates a matcher for hash maps (i.e., associative arrays) that matches when the length of the hash map satisfies
     * the specified matcher.
     *
     * For example:
     *
     * ```
     * assertThat(['foo' => 123, 'bar' => 456], is(aMapWithSize(equalTo(2))));
     * ```
     */
    public static function aMapWithSize(Constraint | int $sizeMatcher): Constraint
    {
        return new MapSizeMatches($sizeMatcher);
    }

    /**
     * Creates a matcher for arrays that matches when the examined value is iterable, and each item in the examined
     * iterable satisfies the corresponding matcher in the specified item matchers. For a positive match, the examined
     * iterable must be of the same length as the number of specified matchers.
     *
     * For example:
     *
     * ```
     * assertThat([1, 2, 3], is(anArray(equalTo(1), equalTo(2), equalTo(3))));
     * ```
     *
     * @param mixed ...$itemMatchers Values or {@see Constraint}s that must be satisfied by the items in the examined value.
     */
    public static function anArray(mixed ...$itemMatchers): Constraint
    {
        return self::arrayContaining(...$itemMatchers);
    }

    /**
     * Creates a matcher for hash maps (i.e., associative arrays) that matches when the length of the hash map is zero.
     *
     * For example:
     *
     * ```
     * assertThat([], anEmptyMap());
     * ```
     */
    public static function anEmptyMap(): Constraint
    {
        return self::aMapWithSize(0);
    }

    /**
     * Creates a matcher that matches when the examined object is an instance of the specified type.
     *
     * @param class-string $type
     */
    public static function anInstanceOf(string $type): Constraint
    {
        return new IsInstanceOf($type);
    }

    /**
     * Creates a matcher that matches when the examined object is an instance of the specified type.
     *
     * This differs from the Java Hamcrest library in that it cannot force a relationship between the specified type and
     * the examined object. As a result, it is identical to {@see self::anInstanceOf()}.
     *
     * @param class-string $type
     */
    public static function any(string $type): Constraint
    {
        return new IsInstanceOf($type);
    }

    /**
     * Creates a matcher that matches if the examined object matches **ANY** of the specified matchers.
     *
     * For example:
     *
     * ```
     * assertThat('myValue', anyOf(startsWith('foo'), containsString('Val')));
     * ```
     *
     * @param mixed ...$matchers Values or {@see Constraint}s, at least one of which must pass for the examined value to match.
     */
    public static function anyOf(mixed ...$matchers): Constraint
    {
        return LogicalOr::fromConstraints(...array_values($matchers));
    }

    /**
     * Creates a matcher that always matches, regardless of the examined value.
     */
    public static function anything(): Constraint
    {
        return new IsAnything();
    }

    /**
     * Creates a matcher for arrays that matches when the examined value is iterable, and each item in the examined
     * iterable satisfies the corresponding matcher in the specified item matchers. For a positive match, the examined
     * iterable must be of the same length as the number of specified matchers.
     *
     * For example:
     *
     * ```
     * assertThat(['foo', 'bar'], arrayContaining(equalTo('foo'), equalTo('bar')));
     * ```
     *
     * @param mixed ...$itemMatchers Values or {@see Constraint}s that must be satisfied by the items in the examined value.
     */
    public static function arrayContaining(mixed ...$itemMatchers): Constraint
    {
        return new IterableMatchesItemsInOrder(...$itemMatchers);
    }

    /**
     * Creates an order agnostic matcher for arrays that matches when each item in the examined array satisfies one
     * matcher anywhere in the specified item matchers. For a positive match, the examined array must be of the same
     * length as the number of specified matchers.
     *
     * Note: each of the specified matchers will only be used once during a given examination, so be careful when
     * specifying matchers that may be satisfied by more than one entry in an examined array.
     *
     * For example:
     *
     * ```
     * assertThat(['foo', 'bar'], arrayContainingInAnyOrder(equalTo('bar'), equalTo('foo')));
     * ```
     *
     * @param mixed ...$itemMatchers Values or {@see Constraint}s that must be satisfied by the items in the examined value.
     */
    public static function arrayContainingInAnyOrder(mixed ...$itemMatchers): Constraint
    {
        return new IterableMatchesItemsInAnyOrder(...$itemMatchers);
    }

    /**
     * Creates a matcher for arrays that matches when the length of the array satisfies the specified matcher.
     *
     * For example:
     *
     * ```
     * assertThat(['foo', 'bar'], arrayWithSize(equalTo(2)));
     * ```
     */
    public static function arrayWithSize(Constraint | int $sizeMatcher): Constraint
    {
        return new IterableSizeMatches($sizeMatcher);
    }

    /**
     * Asserts that the value matches the given matcher.
     *
     * @param mixed $matcher A value or {@see Constraint} to match against.
     */
    public static function assertThat(mixed $value, mixed $matcher): void
    {
        if (!$matcher instanceof Constraint) {
            $matcher = new IsEqual($matcher);
        }

        Assert::assertThat($value, $matcher);
    }

    /**
     * Creates a matcher that matches when the examined value is `null`, or contains zero or more whitespace characters
     * and nothing else.
     *
     * For example:
     *
     * ```
     * assertThat(null, is(blankOrNullString()));
     * ```
     */
    public static function blankOrNullString(): Constraint
    {
        return LogicalOr::fromConstraints(new IsNull(), new StringEqualsStringCompressingWhitespace(''));
    }

    /**
     * Creates a matcher that matches when the examined value contains zero or more whitespace characters and nothing
     * else.
     *
     * For example:
     *
     * ```
     * assertThat("  \n \t \v   \n", is(blankString()));
     * ```
     */
    public static function blankString(): Constraint
    {
        return new StringEqualsStringCompressingWhitespace('');
    }

    /**
     * Creates a matcher that matches when both of the specified matchers match the examined value.
     *
     * For example:
     *
     * ```
     * assertThat('fab', both(containsString('a'))->and(containsString('b')));
     * ```
     *
     * @param mixed $matcher A value or {@see Constraint} to match against.
     */
    public static function both(mixed $matcher): CombinedConstraint
    {
        return CombinedConstraint::both($matcher);
    }

    /**
     * Creates a matcher that matches when an examined numeric value is equal to the specified operand, within a range
     * of +/- error.
     *
     * For example:
     *
     * ```
     * assertThat(1.03, is(closeTo(1.0, 0.03)));
     * ```
     */
    public static function closeTo(float | int $operand, float $error): Constraint
    {
        return new IsEqualWithDelta($operand, $error);
    }

    /**
     * Creates a matcher for iterables that matches when the examined value is iterable, and each item in the examined
     * iterable satisfies the corresponding matcher in the specified item matchers. For a positive match, the examined
     * iterable must be of the same length as the number of specified matchers.
     *
     * For example:
     *
     * ```
     * assertThat(new ArrayIterator(['foo', 'bar']), contains(equalTo('foo'), equalTo('bar')));
     * ```
     *
     * @param mixed ...$itemMatchers Values or {@see Constraint}s that must be satisfied by the items in the examined value.
     */
    public static function contains(mixed ...$itemMatchers): Constraint
    {
        return self::arrayContaining(...$itemMatchers);
    }

    /**
     * Creates an order agnostic matcher for iterables that matches when each item in the examined iterable satisfies
     * one matcher anywhere in the specified item matchers. For a positive match, the examined iterable must be of the
     * same length as the number of specified matchers.
     *
     * Note: each of the specified matchers will only be used once during a given examination, so be careful when
     * specifying matchers that may be satisfied by more than one entry in an examined iterable.
     *
     * For example:
     *
     * ```
     * assertThat(new ArrayIterator(['foo', 'bar']), containsInAnyOrder(equalTo('bar'), equalTo('foo')));
     * ```
     *
     * @param mixed ...$itemMatchers Values or {@see Constraint}s that must be satisfied by the items in the examined value.
     */
    public static function containsInAnyOrder(mixed ...$itemMatchers): Constraint
    {
        return self::arrayContainingInAnyOrder(...$itemMatchers);
    }

    /**
     * Creates a matcher for iterables that matches when a single pass over the examined iterable yields a series of
     * items that satisfy the item matchers in the same relative order.
     *
     * For example:
     *
     * ```
     * assertThat(['a', 'b', 'c', 'd', 'e'], containsInRelativeOrder(equalTo('b'), equalTo('d')));
     * ```
     *
     * @param mixed ...$itemMatchers Values or {@see Constraint}s that must be satisfied by the items in the examined value.
     */
    public static function containsInRelativeOrder(mixed ...$itemMatchers): Constraint
    {
        return new IterableMatchesItemsInRelativeOrder(...$itemMatchers);
    }

    /**
     * Creates a matcher that matches if the examined value contains the specified substring anywhere.
     *
     * For example:
     *
     * ```
     * assertThat('myStringOfNote', containsString('ring'));
     * ```
     */
    public static function containsString(string $substring): Constraint
    {
        return new StringContains($substring);
    }

    /**
     * Creates a matcher that matches if the examined value contains the specified substring anywhere, ignoring casing.
     *
     * For example:
     *
     * ```
     * assertThat('myStringOfNote', containsStringIgnoringCase('Ring'));
     * ```
     */
    public static function containsStringIgnoringCase(string $substring): Constraint
    {
        return new StringContains($substring, true);
    }

    /**
     * Wraps an existing matcher, overriding its description with that specified. All other functions are delegated to
     * the decorated matcher.
     *
     * The beginning of failure messages is "Failed asserting that" in most cases. The description provided here should
     * return the second part of that sentence. (See also {@see Constraint::failureDescription()}.)
     *
     * For example:
     *
     * ```
     * describedAs('value is a big decimal with the value %s', equalTo($myBigDecimal), $myBigDecimal->toString());
     * ```
     *
     * @param string $description The new description for the wrapped matcher. This may be a formatted string including
     *     conversion specifications, as used by {@see sprintf()}. You may use the `$values` arguments to insert values
     *     into the formatted description.
     * @param mixed $matcher A value or {@see Constraint} to match against.
     * @param mixed ...$values Values to insert into the description if it is an {@see sprintf()}-formatted string.
     */
    public static function describedAs(string $description, mixed $matcher, mixed ...$values): Constraint
    {
        return new DescribedConstraint($description, $matcher, ...$values);
    }

    /**
     * Creates a matcher that matches when either of the specified matchers matches the examined value.
     *
     * For example:
     *
     * ```
     * assertThat('fan', either(containsString('a'))->or(containsString('b')));
     * ```
     *
     * @param mixed $matcher A value or {@see Constraint} to match against.
     */
    public static function either(mixed $matcher): CombinedConstraint
    {
        return CombinedConstraint::either($matcher);
    }

    /**
     * Creates a matcher for arrays that matches when the length of the array is zero.
     *
     * For example:
     *
     * ```
     * assertThat([], emptyArray());
     * ```
     */
    public static function emptyArray(): Constraint
    {
        return self::arrayWithSize(0);
    }

    /**
     * Creates a matcher for iterables matching examined iterables that have no items.
     *
     * For example:
     *
     * ```
     * assertThat(new ArrayIterator(), is(emptyIterable()));
     * ```
     */
    public static function emptyIterable(): Constraint
    {
        return self::arrayWithSize(0);
    }

    /**
     * Creates a matcher that matches when the examined value is `null`, or is a zero-length string.
     *
     * For example:
     *
     * ```
     * assertThat(null, is(emptyOrNullString()));
     * ```
     */
    public static function emptyOrNullString(): Constraint
    {
        return LogicalOr::fromConstraints(new IsNull(), new IsIdentical(''));
    }

    /**
     * Creates a matcher that matches when the examined string is a zero-length string.
     *
     * For example:
     *
     * ```
     * assertThat('', is(emptyString()));
     * ```
     */
    public static function emptyString(): Constraint
    {
        return new IsIdentical('');
    }

    /**
     * Creates a matcher that matches if the examined value ends with the specified substring.
     *
     * For example:
     *
     * ```
     * assertThat('myStringOfNote', endsWith('Note'))
     * ```
     */
    public static function endsWith(string $substring): Constraint
    {
        return new StringEndsWith($substring);
    }

    /**
     * Creates a matcher that matches if the examined value ends with the specified substring, ignoring casing.
     *
     * For example:
     *
     * ```
     * assertThat('myStringOfNote', endsWithIgnoringCase('note'));
     * ```
     */
    public static function endsWithIgnoringCase(string $substring): Constraint
    {
        return new StringEndsWithIgnoringCase($substring);
    }

    /**
     * Creates a matcher that matches when the examined value is logically equal to the specified operand. The values
     * are compared using the equality operator (`==`). In PHP, two object instances are equal if they have the same
     * attributes and values (also compared using equality) and are instances of the same class. Two non-object values
     * are equal if they have the same value after type juggling (i.e., `"1" == 1`).
     *
     * @link https://www.php.net/manual/en/language.operators.comparison.php PHP Manual: Comparison Operators
     * @link https://www.php.net/manual/en/language.oop5.object-comparison.php PHP Manual: Comparing Objects
     */
    public static function equalTo(mixed $operand): Constraint
    {
        return new IsEqual($operand);
    }

    /**
     * Creates a matcher that matches when the examined string is equal to the specified `$expectedString`, when
     * whitespace differences are (mostly) ignored. To be exact, the following whitespace rules are applied:
     *
     * - All leading and trailing whitespace of both the `$expectedString` and the examined string are ignored.
     * - Any remaining whitespace, appearing within either string, is collapsed to a single space before comparison.
     *
     * For example:
     *
     * ```
     * assertThat("   my\tfoo  bar ", equalToIgnoringWhiteSpace(' my  foo bar'));
     * ```
     */
    public static function equalToCompressingWhitespace(string $expectedString): Constraint
    {
        return new StringEqualsStringCompressingWhitespace($expectedString);
    }

    /**
     * Creates a matcher that matches when the examined string is equal to the specified `$expectedString`, ignoring casing.
     *
     * For example:
     *
     * ```
     * assertThat('Foo', equalToIgnoringCase('FOO'));
     * ```
     */
    public static function equalToIgnoringCase(string $expectedString): Constraint
    {
        return new IsEqualIgnoringCase($expectedString);
    }

    /**
     * Creates a matcher that matches when the examined string is equal to the specified `$expectedString`, when
     * whitespace differences are (mostly) ignored.
     *
     * For more information, see {@see self::equalToCompressingWhitespace()}.
     */
    public static function equalToIgnoringWhiteSpace(string $expectedString): Constraint
    {
        return self::equalToCompressingWhitespace($expectedString);
    }

    /**
     * Creates a matcher that is identical to {@see self::equalTo()} except the operand must be an object.
     */
    public static function equalToObject(object $operand): Constraint
    {
        return self::equalTo($operand);
    }

    /**
     * Creates a matcher for iterables that only matches when a single pass over the examined iterable yields items
     * that are all matched by the specified item matcher.
     *
     * For example:
     *
     * ```
     * assertThat(['bar', 'baz'], everyItem(startsWith('ba')));
     * ```
     *
     * @param mixed $itemMatcher A value or {@see Constraint} to match against every item in the iterable.
     */
    public static function everyItem(mixed $itemMatcher): Constraint
    {
        return new IterableMatchesEveryItem($itemMatcher);
    }

    /**
     * Creates a matcher that matches when the examined value is greater than the specified value.
     *
     * For example:
     *
     * ```
     * assertThat(2, is(greaterThan(1)));
     * ```
     *
     * @param float | int | numeric-string $value
     */
    public static function greaterThan(float | int | string $value): Constraint
    {
        return new GreaterThan($value);
    }

    /**
     * Creates a matcher that matches when the examined value is greater than or equal to the specified value.
     *
     * For example:
     *
     * ```
     * assertThat(1, is(greaterThanOrEqualTo(1)));
     * ```
     *
     * @param float | int | numeric-string $value
     */
    public static function greaterThanOrEqualTo(float | int | string $value): Constraint
    {
        return new GreaterThanOrEqualTo($value);
    }

    /**
     * Creates a matcher for hash maps (i.e., associative arrays) matching when the examined hash map contains at least
     * one entry whose key satisfies the specified `$keyMatcher` **and** whose value satisfies the specified
     * `$valueMatcher`.
     *
     * For example:
     *
     * ```
     * assertThat(['bar' => 'foo'], hasEntry(equalTo('bar'), equalTo('foo')))
     * ```
     *
     * @param mixed $keyMatcher A value or {@see Constraint} that, in combination with the `$valueMatcher`, must be satisfied by at least one entry.
     * @param mixed $valueMatcher A value or {@see Constraint} that, in combination with the `$keyMatcher`, must be satisfied by at least one entry.
     */
    public static function hasEntry(mixed $keyMatcher, mixed $valueMatcher): Constraint
    {
        return new MapMatchesKeyValuePair($keyMatcher, $valueMatcher);
    }

    /**
     * Creates a matcher for iterables that only matches when a single pass over the examined iterable yields at least
     * one item that is matched by the specified item matcher. While matching, the traversal of the examined iterable
     * will stop as soon as a matching item is found.
     *
     * For example:
     *
     * ```
     * assertThat(['foo', 'bar'], hasItem(startsWith('ba')));
     * ```
     *
     * @param mixed $itemMatcher A value or {@see Constraint} to match against.
     */
    public static function hasItem(mixed $itemMatcher): Constraint
    {
        return new IterableContainsItemsMatching($itemMatcher);
    }

    /**
     * Creates a matcher for arrays that matches when the examined array contains at least one item that is matched by
     * the specified item matcher. The traversal of the examined array will stop as soon as a matching item is found.
     *
     * For example:
     *
     * ```
     * assertThat(['foo', 'bar'], hasItemInArray(startsWith('ba')));
     * ```
     *
     * @param mixed $itemMatcher A value or {@see Constraint} to match against.
     */
    public static function hasItemInArray(mixed $itemMatcher): Constraint
    {
        return new IterableContainsItemsMatching($itemMatcher);
    }

    /**
     * Creates a matcher for iterables that matches when consecutive passes over the examined iterable yield at least
     * one item that is matched by the corresponding matcher from the specified item matchers. While matching, each
     * traversal of the examined iterable will stop as soon as a matching item is found.
     *
     * For example:
     *
     * ```
     * assertThat(['foo', 'bar', 'baz'], hasItems(endsWith('z'), endsWith('o')));
     * ```
     *
     * @param mixed ...$itemMatchers A value or {@see Constraint} to match against.
     */
    public static function hasItems(mixed ...$itemMatchers): Constraint
    {
        return new IterableContainsItemsMatching(...$itemMatchers);
    }

    /**
     * Creates a matcher for hash maps (i.e., associative arrays) matching when the examined hash map contains at least
     * one key that satisfies the specified matcher.
     *
     * For example:
     *
     * ```
     * assertThat(['foo' => 'bar'], hasKey(equalTo('foo')));
     * ```
     *
     * @param mixed $keyMatcher A value or {@see Constraint} that must be satisfied by at least one entry.
     */
    public static function hasKey(mixed $keyMatcher): Constraint
    {
        return new MapMatchesKey($keyMatcher);
    }

    /**
     * Creates a matcher that matches when a string has the length that satisfies the specified matcher.
     *
     * For example:
     *
     * ```
     * assertThat('text', hasLength(4));
     * ```
     */
    public static function hasLength(Constraint | int $lengthMatcher): Constraint
    {
        throw new LogicException('Not implemented yet');
    }

    /**
     * Creates a matcher that matches when the examined object has a property with the specified name. If a value
     * matcher is provided, the property value is also matched against it.
     *
     * For example:
     *
     * ```
     * assertThat(myObject, hasProperty('foo'));
     * assertThat(myObject, hasProperty('foo', equalTo('bar')));
     * ```
     */
    public static function hasProperty(string $propertyName, mixed $valueMatcher = null): Constraint
    {
        throw new LogicException('Not implemented yet');
    }

    /**
     * Creates a matcher for iterables that matches when the length of the iterable satisfies the specified matcher.
     *
     * For example:
     *
     * ```
     * assertThat(new ArrayIterator(['foo', 'bar']), hasSize(equalTo(2)));
     * ```
     */
    public static function hasSize(Constraint | int $sizeMatcher): Constraint
    {
        return self::arrayWithSize($sizeMatcher);
    }

    /**
     * Creates a matcher that matches when the examined value satisfies the specified matcher after being cast to a string.
     *
     * For example:
     *
     * ```
     * assertThat(true, hasToString(equalTo('1')));
     * ```
     */
    public static function hasToString(Constraint | string $toStringMatcher): Constraint
    {
        throw new LogicException('Not implemented yet');
    }

    /**
     * Creates a matcher for hash maps (i.e., associative arrays) that matches when the examined hash map contains at
     * least one value that satisfies the specified matcher.
     *
     * For example:
     *
     * ```
     * assertThat(new ArrayIterator(['foo' => 'bar']), hasValue(equalTo('bar')));
     * ```
     *
     * @param mixed $valueMatcher A value or {@see Constraint} that must be satisfied by at least one entry.
     */
    public static function hasValue(mixed $valueMatcher): Constraint
    {
        return new MapMatchesValue($valueMatcher);
    }

    /**
     * Creates a matcher that matches when the examined XML document or node has a value at the specified XPath query
     * that satisfies the specified value matcher. The XPath query is relative to the root of the document.
     *
     * If the value matcher is `null`, this matcher matches when the XML document or node contains a node at the
     * specified XPath query.
     *
     * If your query uses namespace prefixes, you may provide a namespace context to be used when evaluating the
     * XPath query.
     *
     * For example:
     *
     * ```
     * assertThat(xml, hasXPath('/root/something[2]/cheese'));
     * assertThat(xml, hasXPath('/root/something[2]/cheese', equalTo('Cheddar')));
     * assertThat(xml, hasXPath(
     *     '/ns:root/ns:something[2]/ns:cheese',
     *     is(equalTo('Cheddar')),
     *     new NamespaceContext(prefix: 'ns', uri: 'http://example.com/ns'),
     * ));
     * ```
     *
     * @param string $xpath The XPath query to match against.
     * @param mixed $valueMatcher A value or {@see Constraint} to match against.
     * @param NamespaceContext | null $namespaceContext The namespace context to use when evaluating the XPath query.
     */
    public static function hasXPath(
        string $xpath,
        mixed $valueMatcher = null,
        ?NamespaceContext $namespaceContext = null,
    ): Constraint {
        throw new LogicException('Not implemented yet');
    }

    /**
     * Creates a matcher that matches when the examined object is found within the specified iterable.
     *
     * For example:
     *
     * ```
     * assertThat('foo', is(in(['bar', 'foo'])));
     * ```
     *
     * @param iterable<mixed> $collection The iterable in which matching items must be found.
     */
    public static function in(iterable $collection): Constraint
    {
        return new ValueIsInIterable($collection);
    }

    /**
     * Decorates another matcher, retaining its behavior but allowing tests to be slightly more expressive.
     *
     * For example:
     *
     * ```
     * assertThat($cheese, is(equalTo($smelly)))
     * ```
     *
     * Instead of:
     *
     * ```
     * assertThat($cheese, equalTo($smelly))
     * ```
     *
     * @param mixed $matcher A value or {@see Constraint} to wrap.
     */
    public static function is(mixed $matcher): Constraint
    {
        return match (true) {
            $matcher instanceof Constraint => $matcher,
            default => new IsEqual($matcher),
        };
    }

    /**
     * Creates a matcher that performs an identity check against the examined value and the type.
     *
     * The type may be a fully qualified class name or a string indicating the type. Accepted type values are:
     *
     * - `'array'`
     * - `'bool'`
     * - `'callable'`
     * - `'float'`
     * - `'int'`
     * - `'iterable'`
     * - `'null'`
     * - `'numeric'`
     * - `'object'`
     * - `'resource'`
     * - `'scalar'`
     * - `'string'`
     *
     * @param class-string | "array" | "bool" | "callable" | "float" | "int" | "iterable" | "null" | "numeric" | "object" | "resource" | "scalar" | "string" $type
     */
    public static function isA(string $type): Constraint
    {
        $nativeType = NativeType::tryFrom($type);

        if ($nativeType !== null) {
            return new IsType($nativeType);
        }

        return new IsInstanceOf($type);
    }

    /**
     * This is an alias of {@see self::isA()}, provided for easier reading of test statements.
     *
     * @param class-string | "array" | "bool" | "callable" | "float" | "int" | "iterable" | "null" | "numeric" | "object" | "resource" | "scalar" | "string" $type
     */
    public static function isAn(string $type): Constraint
    {
        return self::isA($type);
    }

    /**
     * Creates a matcher that matches when the examined value is considered empty.
     *
     * @link https://www.php.net/empty PHP Manual: empty Language Construct
     */
    public static function isEmpty(): Constraint
    {
        return new IsEmpty();
    }

    /**
     * Creates a matcher for iterables that matches when the length of the iterable satisfies the specified matcher.
     *
     * For example:
     *
     * ```
     * assertThat(new ArrayIterator(['foo', 'bar']), iterableWithSize(equalTo(2)));
     * ```
     */
    public static function iterableWithSize(Constraint | int $sizeMatcher): Constraint
    {
        return self::arrayWithSize($sizeMatcher);
    }

    /**
     * Creates a matcher that matches when the examined value is less than the specified value.
     *
     * For example:
     *
     * ```
     * assertThat(1, is(lessThan(2)));
     * ```
     *
     * @param float | int | numeric-string $value
     */
    public static function lessThan(float | int | string $value): Constraint
    {
        return new LessThan($value);
    }

    /**
     * Creates a matcher that matches when the examined value is less than or equal to the specified value.
     *
     * For example:
     *
     * ```
     * assertThat(1, is(lessThanOrEqualTo(1)));
     * ```
     *
     * @param float | int | numeric-string $value
     */
    public static function lessThanOrEqualTo(float | int | string $value): Constraint
    {
        return LogicalOr::fromConstraints(new LessThan($value), new IsEqual($value));
    }

    /**
     * Creates a matcher that matches if the examined value matches the Perl-compatible regular expression.
     *
     * ```
     * assertThat('abc', matchesPattern('/^[a-z]+$/'));
     * ```
     */
    public static function matchesPattern(string $pattern): Constraint
    {
        return self::matchesRegex($pattern);
    }

    /**
     * Creates a matcher that matches if the examined value matches the Perl-compatible regular expression.
     *
     * ```
     * assertThat('abc', matchesRegex('/^[a-z]+$/'));
     * ```
     */
    public static function matchesRegex(string $pattern): Constraint
    {
        return new RegularExpression($pattern);
    }

    /**
     * Creates a matcher that wraps an existing matcher but inverts the logic by which it will match.
     *
     * For example:
     *
     * ```
     * assertThat($cheese, is(not(equalTo($smelly))));
     * ```
     *
     * @param mixed $matcher A value or {@see Constraint} whose sense will be inverted.
     */
    public static function not(mixed $matcher): Constraint
    {
        return new LogicalNot($matcher);
    }

    /**
     * Creates a matcher that matches when an examined float is not a number (i.e., `is_nan()` returns `true`).
     *
     * For example:
     *
     * ```
     * assertThat(sqrt(-1), is(notANumber()));
     * ```
     */
    public static function notANumber(): Constraint
    {
        return new IsNan();
    }

    /**
     * A shortcut to the frequently used `not(nullValue())`.
     *
     * For example:
     *
     * ```
     * assertThat($cheese, is(notNullValue()));
     * ```
     *
     * Instead of:
     *
     * ```
     * assertThat($cheese, is(not(nullValue())));
     * ```
     */
    public static function notNullValue(): Constraint
    {
        return new LogicalNot(new IsNull());
    }

    /**
     * Creates a matcher that matches if examined value is `null`.
     *
     * For example:
     *
     * ```
     * assertThat($cheese, is(nullValue());
     * ```
     */
    public static function nullValue(): Constraint
    {
        return new IsNull();
    }

    /**
     * Creates a matcher that matches when the examined value is equal to one of the specified elements.
     *
     * For example:
     *
     * ```
     * assertThat('foo', is(oneOf('bar', 'foo')));
     * ```
     */
    public static function oneOf(mixed ...$elements): Constraint
    {
        $elements = array_map(fn (mixed $element) => new IsEqual($element), $elements);
        assert(array_is_list($elements));

        return LogicalOr::fromConstraints(...$elements);
    }

    /**
     * Creates a matcher that matches only when the examined value is the same instance as the specified target object.
     */
    public static function sameInstance(mixed $object): Constraint
    {
        return new IsIdentical($object);
    }

    /**
     * Creates a matcher that matches when the examined object has values for all of its public properties that are
     * equal to the corresponding values of the specified other object. If any properties are marked as ignored, they
     * will be dropped from evaluation for both the examined and other objects.
     *
     * For example:
     *
     * ```
     * assertThat(myObject, samePropertyValuesAs(otherObject));
     * assertThat(myObject, samePropertyValuesAs(otherObject, 'age', 'height'));
     * ```
     */
    public static function samePropertyValuesAs(object $other, string ...$ignoredProperties): Constraint
    {
        throw new LogicException('Not implemented yet');
    }

    /**
     * Creates a matcher that matches if the examined value starts with the specified substring.
     *
     * For example:
     *
     * ```
     * assertThat('myStringOfNote', startsWith('my'));
     * ```
     */
    public static function startsWith(string $substring): Constraint
    {
        return new StringStartsWith($substring);
    }

    /**
     * Creates a matcher that matches if the examined value starts with the specified substring, ignoring casing.
     *
     * For example:
     *
     * ```
     * assertThat('myStringOfNote', startsWithIgnoringCase('My'));
     * ```
     */
    public static function startsWithIgnoringCase(string $substring): Constraint
    {
        return new StringStartsWithIgnoringCase($substring);
    }

    /**
     * Creates a matcher that matches when the examined string contains all the specified substrings, considering
     * the order of their appearance.
     *
     * For example:
     *
     * ```
     * assertThat('myfoobarbaz', stringContainsInOrder('bar', 'foo'));
     * ```
     *
     * This fails as "foo" occurs before "bar" in the string "myfoobarbaz"
     */
    public static function stringContainsInOrder(string ...$substrings): Constraint
    {
        throw new LogicException('Not implemented yet');
    }

    /**
     * Creates a matcher that matches only when the examined value is the same instance as the specified target object.
     *
     * @see self::sameInstance()
     */
    public static function theInstance(mixed $object): Constraint
    {
        return self::sameInstance($object);
    }
}
