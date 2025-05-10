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

use PHPUnit\Framework\Constraint\Constraint;
use RestCertain\Hamcrest\Constraint\CombinedConstraint;
use RestCertain\Hamcrest\Xml\NamespaceContext;

// phpcs:disable Squiz.Functions.GlobalFunction.Found

/**
 * Similar to {@see \RestCertain\Hamcrest\describedAs()}, `additionallyDescribedAs()` wraps an existing matcher, but
 * instead of overriding the description, it appends to it, enhancing it and adding additional context. All other
 * functions are delegated to the decorated matcher.
 *
 * For example:
 *
 * ```
 * additionallyDescribedAs(
 *     'This additional context supplements the matcher\'s existing failure message.',
 *     equalTo($myBigDecimal),
 * );
 * ```
 *
 * @see Constraint::additionalFailureDescription()
 *
 * @param string $additionalDescription The additional description to add to the wrapped matcher. This may be a
 *     formatted string including conversion specifications, as used by {@see sprintf()}; you may use the `$values`
 *     arguments to insert values into the formatted description.
 * @param mixed $matcher A value or {@see Constraint} to match against.
 * @param mixed ...$values Values to insert into the description if it is an {@see sprintf()}-formatted string.
 */
function additionallyDescribedAs(string $additionalDescription, mixed $matcher, mixed ...$values): Constraint
{
    return Matchers::additionallyDescribedAs($additionalDescription, $matcher, ...$values);
}

/**
 * Creates a matcher that matches if the examined value matches **ALL** of the specified matchers.
 *
 * For example:
 *
 * ```
 * assertThat('myValue', allOf(startsWith('my'), containsString('Val')));
 * ```
 *
 * @param mixed ...$matchers A value or {@see Constraint} to match against.
 */
function allOf(mixed ...$matchers): Constraint
{
    return Matchers::allOf(...$matchers);
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
function aMapWithSize(Constraint | int $sizeMatcher): Constraint
{
    return Matchers::aMapWithSize($sizeMatcher);
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
function anArray(mixed ...$itemMatchers): Constraint
{
    return Matchers::anArray(...$itemMatchers);
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
function anEmptyMap(): Constraint
{
    return Matchers::anEmptyMap();
}

/**
 * Creates a matcher that matches when the examined object is an instance of the specified type.
 *
 * @param class-string $type
 */
function anInstanceOf(string $type): Constraint
{
    return Matchers::anInstanceOf($type);
}

/**
 * Creates a matcher that matches when the examined object is an instance of the specified type.
 *
 * This differs from the Java Hamcrest library in that it cannot force a relationship between the specified type and
 * the examined object. As a result, it is identical to {@see \RestCertain\Hamcrest\anInstanceOf()}.
 *
 * @param class-string $type
 */
function any(string $type): Constraint
{
    return Matchers::any($type);
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
function anyOf(mixed ...$matchers): Constraint
{
    return Matchers::anyOf(...$matchers);
}

/**
 * Creates a matcher that always matches, regardless of the examined value.
 */
function anything(): Constraint
{
    return Matchers::anything();
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
function arrayContaining(mixed ...$itemMatchers): Constraint
{
    return Matchers::arrayContaining(...$itemMatchers);
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
function arrayContainingInAnyOrder(mixed ...$itemMatchers): Constraint
{
    return Matchers::arrayContainingInAnyOrder(...$itemMatchers);
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
function arrayWithSize(Constraint | int $sizeMatcher): Constraint
{
    return Matchers::arrayWithSize($sizeMatcher);
}

/**
 * Asserts that the value matches the given matcher.
 *
 * @param mixed $matcher A value or {@see Constraint} to match against.
 */
function assertThat(mixed $actual, mixed $matcher): void
{
    Matchers::assertThat($actual, $matcher);
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
function blankOrNullString(): Constraint
{
    return Matchers::blankOrNullString();
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
function blankString(): Constraint
{
    return Matchers::blankString();
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
function both(mixed $matcher): CombinedConstraint
{
    return Matchers::both($matcher);
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
function closeTo(float | int $operand, float $error): Constraint
{
    return Matchers::closeTo($operand, $error);
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
function contains(mixed ...$itemMatchers): Constraint
{
    return Matchers::contains(...$itemMatchers);
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
function containsInAnyOrder(mixed ...$itemMatchers): Constraint
{
    return Matchers::containsInAnyOrder(...$itemMatchers);
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
function containsInRelativeOrder(mixed ...$itemMatchers): Constraint
{
    return Matchers::containsInRelativeOrder(...$itemMatchers);
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
function containsString(string $substring): Constraint
{
    return Matchers::containsString($substring);
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
function containsStringIgnoringCase(string $substring): Constraint
{
    return Matchers::containsStringIgnoringCase($substring);
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
function describedAs(string $description, mixed $matcher, mixed ...$values): Constraint
{
    return Matchers::describedAs($description, $matcher, ...$values);
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
function either(mixed $matcher): CombinedConstraint
{
    return Matchers::either($matcher);
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
function emptyArray(): Constraint
{
    return Matchers::emptyArray();
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
function emptyIterable(): Constraint
{
    return Matchers::emptyIterable();
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
function emptyOrNullString(): Constraint
{
    return Matchers::emptyOrNullString();
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
function emptyString(): Constraint
{
    return Matchers::emptyString();
}

/**
 * Creates a matcher that matches if the examined value ends with the specified substring.
 *
 * For example:
 *
 * ```
 * assertThat('myStringOfNote', endsWith('Note'));
 * ```
 */
function endsWith(string $substring): Constraint
{
    return Matchers::endsWith($substring);
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
function endsWithIgnoringCase(string $substring): Constraint
{
    return Matchers::endsWithIgnoringCase($substring);
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
function equalTo(mixed $operand): Constraint
{
    return Matchers::equalTo($operand);
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
function equalToCompressingWhitespace(string $expectedString): Constraint
{
    return Matchers::equalToCompressingWhitespace($expectedString);
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
function equalToIgnoringCase(string $expectedString): Constraint
{
    return Matchers::equalToIgnoringCase($expectedString);
}

/**
 * Creates a matcher that matches when the examined string is equal to the specified `$expectedString`, when
 * whitespace differences are (mostly) ignored.
 *
 * For more information, see {@see \RestCertain\Hamcrest\equalToCompressingWhitespace()}.
 */
function equalToIgnoringWhiteSpace(string $expectedString): Constraint
{
    return Matchers::equalToIgnoringWhiteSpace($expectedString);
}

/**
 * Creates a matcher that is identical to {@see \RestCertain\Hamcrest\equalTo()} except the operand must be an object.
 */
function equalToObject(object $operand): Constraint
{
    return Matchers::equalToObject($operand);
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
function everyItem(mixed $itemMatcher): Constraint
{
    return Matchers::everyItem($itemMatcher);
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
function greaterThan(float | int | string $value): Constraint
{
    return Matchers::greaterThan($value);
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
function greaterThanOrEqualTo(float | int | string $value): Constraint
{
    return Matchers::greaterThanOrEqualTo($value);
}

/**
 * Creates a matcher for hash maps (i.e., associative arrays) matching when the examined hash map contains at least one
 * entry whose key satisfies the specified `$keyMatcher` **and** whose value satisfies the specified `$valueMatcher`.
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
function hasEntry(mixed $keyMatcher, mixed $valueMatcher): Constraint
{
    return Matchers::hasEntry($keyMatcher, $valueMatcher);
}

/**
 * Creates a matcher for iterables that only matches when a single pass over the examined iterable yields at least
 * one item that is matched by the specified item matcher. While matching, the traversal of the examined iterable
 * will stop as soon as a matching item is found.
 *
 * For example:
 *
 * ```
 * assertThat(new ArrayIterator(['foo', 'bar']), hasItem(startsWith('ba')));
 * ```
 *
 * @param mixed $itemMatcher A value or {@see Constraint} to match against.
 */
function hasItem(mixed $itemMatcher): Constraint
{
    return Matchers::hasItem($itemMatcher);
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
function hasItemInArray(mixed $itemMatcher): Constraint
{
    return Matchers::hasItemInArray($itemMatcher);
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
function hasItems(mixed ...$itemMatchers): Constraint
{
    return Matchers::hasItems(...$itemMatchers);
}

/**
 * Creates a matcher for hash maps (i.e., associative arrays) matching when the examined hash map contains at least one
 * key that satisfies the specified matcher.
 *
 * For example:
 *
 * ```
 * assertThat(['foo' => 'bar'], hasKey(equalTo('foo')));
 * ```
 *
 * @param mixed $keyMatcher A value or {@see Constraint} that must be satisfied by at least one entry.
 */
function hasKey(mixed $keyMatcher): Constraint
{
    return Matchers::hasKey($keyMatcher);
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
function hasLength(Constraint | int $lengthMatcher): Constraint
{
    return Matchers::hasLength($lengthMatcher);
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
function hasProperty(string $propertyName, mixed $valueMatcher = null): Constraint
{
    return Matchers::hasProperty($propertyName, $valueMatcher);
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
function hasSize(Constraint | int $sizeMatcher): Constraint
{
    return Matchers::hasSize($sizeMatcher);
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
function hasToString(Constraint | string $toStringMatcher): Constraint
{
    return Matchers::hasToString($toStringMatcher);
}

/**
 * Creates a matcher for hash maps (i.e., associative arrays) matching when the examined hash map contains at least one
 * value that satisfies the specified matcher.
 *
 * For example:
 *
 * ```
 * assertThat(new ArrayIterator(['foo' => 'bar']), hasValue(equalTo('bar')));
 * ```
 *
 * @param mixed $valueMatcher A value or {@see Constraint} that must be satisfied by at least one entry.
 */
function hasValue(mixed $valueMatcher): Constraint
{
    return Matchers::hasValue($valueMatcher);
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
function hasXPath(string $xpath, mixed $valueMatcher = null, ?NamespaceContext $namespaceContext = null): Constraint
{
    return Matchers::hasXPath($xpath, $valueMatcher, $namespaceContext);
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
function in(iterable $collection): Constraint
{
    return Matchers::in($collection);
}

/**
 * If providing a value, this allows a slightly more expressive way to write equality tests. If providing a constraint,
 * this wraps the constraint, allowing for a slightly more expressive way to write tests.
 *
 * For example:
 *
 * ```
 * assertThat($cheese, is($smelly));
 * assertThat($cheese, is(equalTo($smelly)));
 * ```
 *
 * Instead of:
 *
 * ```
 * assertThat($cheese, $smelly);
 * assertThat($cheese, equalTo($smelly));
 * ```
 *
 * @param mixed $matcher A value or {@see Constraint} to wrap.
 */
function is(mixed $matcher): Constraint
{
    return Matchers::is($matcher);
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
function isA(string $type): Constraint
{
    return Matchers::isA($type);
}

/**
 * This is an alias of {@see \RestCertain\Hamcrest\isA()}, provided for easier reading of test statements.
 *
 * @param class-string | "array" | "bool" | "callable" | "float" | "int" | "iterable" | "null" | "numeric" | "object" | "resource" | "scalar" | "string" $type
 */
function isAn(string $type): Constraint
{
    return Matchers::isAn($type);
}

/**
 * Creates a matcher that matches when the examined value is considered empty.
 *
 * @link https://www.php.net/empty PHP Manual: empty Language Construct
 */
function isEmpty(): Constraint
{
    return Matchers::isEmpty();
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
function iterableWithSize(Constraint | int $sizeMatcher): Constraint
{
    return Matchers::iterableWithSize($sizeMatcher);
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
function lessThan(float | int | string $value): Constraint
{
    return Matchers::lessThan($value);
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
function lessThanOrEqualTo(float | int | string $value): Constraint
{
    return Matchers::lessThanOrEqualTo($value);
}

/**
 * Creates a matcher that matches if the examined value matches the Perl-compatible regular expression.
 *
 * ```
 * assertThat('abc', matchesPattern('/^[a-z]+$/'));
 * ```
 */
function matchesPattern(string $pattern): Constraint
{
    return Matchers::matchesPattern($pattern);
}

/**
 * Creates a matcher that matches if the examined value matches the Perl-compatible regular expression.
 *
 * ```
 * assertThat('abc', matchesRegex('/^[a-z]+$/'));
 * ```
 */
function matchesRegex(string $pattern): Constraint
{
    return Matchers::matchesRegex($pattern);
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
function not(mixed $matcher): Constraint
{
    return Matchers::not($matcher);
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
function notANumber(): Constraint
{
    return Matchers::notANumber();
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
function notNullValue(): Constraint
{
    return Matchers::notNullValue();
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
function nullValue(): Constraint
{
    return Matchers::nullValue();
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
function oneOf(mixed ...$elements): Constraint
{
    return Matchers::oneOf(...$elements);
}

/**
 * Creates a matcher that matches only when the examined value is the same instance as the specified target object.
 */
function sameInstance(mixed $object): Constraint
{
    return Matchers::sameInstance($object);
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
function samePropertyValuesAs(object $other, string ...$ignoredProperties): Constraint
{
    return Matchers::samePropertyValuesAs($other, ...$ignoredProperties);
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
function startsWith(string $substring): Constraint
{
    return Matchers::startsWith($substring);
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
function startsWithIgnoringCase(string $substring): Constraint
{
    return Matchers::startsWithIgnoringCase($substring);
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
function stringContainsInOrder(string ...$substrings): Constraint
{
    return Matchers::stringContainsInOrder(...$substrings);
}

/**
 * Creates a matcher that matches only when the examined value is the same instance as the specified target object.
 */
function theInstance(mixed $object): Constraint
{
    return Matchers::theInstance($object);
}
