.. _matchers:

Matchers
========

.. php:namespace:: RestCertain\Hamcrest

.. php:function:: additionallyDescribedAs($additionalDescription, $matcher[, ...$values])

   Similar to :php:func:`describedAs()`, ``additionallyDescribedAs()`` wraps an existing matcher, but
   instead of overriding the description, it appends to it, enhancing it and adding additional context. All other
   functions are delegated to the decorated matcher.

   For example:

   .. code-block:: php

      additionallyDescribedAs(
          'This additional context supplements the matcher\'s existing failure message.',
          equalTo($myBigDecimal),
      );

   :param string $additionalDescription: The additional description to add to the wrapped matcher. This may be a formatted string including conversion specifications, as used by `sprintf() <https://www.php.net/sprintf>`_; you may use the ``$values`` arguments to insert values into the formatted description.
   :param mixed $matcher: A value or ``PHPUnit\Framework\Constraint\Constraint`` to match against.
   :param mixed ...$values: Values to insert into the description if it is an `sprintf() <https://www.php.net/sprintf>`_-formatted string.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: allOf(...$matchers)

   Creates a matcher that matches if the examined value matches **ALL** of the specified matchers.

   For example:

   .. code-block:: php

      assertThat('myValue', allOf(startsWith('my'), containsString('Val')));

   :param mixed ...$matchers: A value or ``PHPUnit\Framework\Constraint\Constraint`` to match against.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: aMapWithSize($sizeMatcher)

   Creates a matcher for hash maps (i.e., associative arrays) that matches when the length of the hash map satisfies the
   specified matcher.

   For example:

   .. code-block:: php

      assertThat(['foo' => 123, 'bar' => 456], is(aMapWithSize(equalTo(2))));

   :param PHPUnit\\Framework\\Constraint\\Constraint|int $sizeMatcher:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: anArray(...$itemMatchers)

   Creates a matcher for arrays that matches when the examined value is iterable, and each item in the examined iterable
   satisfies the corresponding matcher in the specified item matchers. For a positive match, the examined iterable must
   be of the same length as the number of specified matchers.

   For example:

   .. code-block:: php

      assertThat([1, 2, 3], is(anArray(equalTo(1), equalTo(2), equalTo(3))));

   :param mixed ...$itemMatchers: Values or ``PHPUnit\Framework\Constraint\Constraint`` instances that must be satisfied by the items in the examined value.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: anEmptyMap()

   Creates a matcher for hash maps (i.e., associative arrays) that matches when the length of the hash map is zero.

   For example:

   .. code-block:: php

      assertThat([], anEmptyMap());

   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: anInstanceOf($type)

   Creates a matcher that matches when the examined object is an instance of the specified type.

   :param string $type: A fully-qualified class name.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: any($type)

   Creates a matcher that matches when the examined object is an instance of the specified type.

   This differs from the Java Hamcrest library in that it cannot force a relationship between the specified type and
   the examined object. As a result, it is identical to :php:func:`anInstanceOf()`.

   :param string $type: A fully-qualified class name.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: anyOf(...$matchers)

   Creates a matcher that matches if the examined object matches **ANY** of the specified matchers.

   For example:

   .. code-block:: php

      assertThat('myValue', anyOf(startsWith('foo'), containsString('Val')));

   :param mixed ...$matchers: Values or ``PHPUnit\Framework\Constraint\Constraint`` instances, at least one of which must pass for the examined value to match.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: anything()

   Creates a matcher that always matches, regardless of the examined value.

   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: arrayContaining(...$itemMatchers)

   Creates a matcher for arrays that matches when the examined value is iterable, and each item in the examined iterable
   satisfies the corresponding matcher in the specified item matchers. For a positive match, the examined iterable must
   be of the same length as the number of specified matchers.

   For example:

   .. code-block:: php

      assertThat(['foo', 'bar'], arrayContaining(equalTo('foo'), equalTo('bar')));

   :param mixed ...$itemMatchers: Values or ``PHPUnit\Framework\Constraint\Constraint`` instances that must be satisfied by the items in the examined value.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: arrayContainingInAnyOrder(...$itemMatchers)

   Creates an order agnostic matcher for arrays that matches when each item in the examined array satisfies one matcher
   anywhere in the specified item matchers. For a positive match, the examined array must be of the same length as the
   number of specified matchers.

   .. note::

      Each of the specified matchers will only be used once during a given examination, so be careful when
      specifying matchers that may be satisfied by more than one entry in an examined array.

   For example:

   .. code-block:: php

      assertThat(['foo', 'bar'], arrayContainingInAnyOrder(equalTo('bar'), equalTo('foo')));

   :param mixed ...$itemMatchers: Values or ``PHPUnit\Framework\Constraint\Constraint`` instances that must be satisfied by the items in the examined value.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: arrayWithSize($sizeMatcher)

   Creates a matcher for arrays that matches when the length of the array satisfies the specified matcher.

   For example:

   .. code-block:: php

      assertThat(['foo', 'bar'], arrayWithSize(equalTo(2)));

   :param PHPUnit\\Framework\\Constraint\\Constraint|int $sizeMatcher:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: assertThat($actual, $matcher)

   Asserts that the value matches the given matcher.

   :param mixed $actual:
   :param mixed $matcher: A value or ``PHPUnit\Framework\Constraint\Constraint`` to match against.
   :returntype: void

.. php:function:: blankOrNullString()

   Creates a matcher that matches when the examined value is ``null``, or contains zero or more whitespace characters and
   nothing else.

   For example:

   .. code-block:: php

      assertThat(null, is(blankOrNullString()));

   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: blankString()

   Creates a matcher that matches when the examined value contains zero or more whitespace characters and nothing else.

   For example:

   .. code-block:: php

      assertThat("  \n \t \v   \n", is(blankString()));

   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: both($matcher)

   Creates a matcher that matches when both of the specified matchers match the examined value.

   For example:

   .. code-block:: php

      assertThat('fab', both(containsString('a'))->and(containsString('b')));

   :param mixed $matcher: A value or ``PHPUnit\Framework\Constraint\Constraint`` to match against.
   :returntype: RestCertain\\Hamcrest\\Constraint\\CombinedConstraint

.. php:function:: closeTo($operand, $error)

   Creates a matcher that matches when an examined numeric value is equal to the specified operand, within a range of
   +/- error.

   For example:

   .. code-block:: php

      assertThat(1.03, is(closeTo(1.0, 0.03)));

   :param float|int $operand:
   :param float $error:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: contains(...$itemMatchers)

   Creates a matcher for iterables that matches when the examined value is iterable, and each item in the examined
   iterable satisfies the corresponding matcher in the specified item matchers. For a positive match, the examined
   iterable must be of the same length as the number of specified matchers.

   For example:

   .. code-block:: php

      assertThat(new ArrayIterator(['foo', 'bar']), contains(equalTo('foo'), equalTo('bar')));

   :param mixed ...$itemMatchers: Values or ``PHPUnit\Framework\Constraint\Constraint`` instances that must be satisfied by the items in the examined value.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: containsInAnyOrder(...$itemMatchers)

   Creates an order agnostic matcher for iterables that matches when each item in the examined iterable satisfies one
   matcher anywhere in the specified item matchers. For a positive match, the examined iterable must be of the same
   length as the number of specified matchers.

   .. note::

      Each of the specified matchers will only be used once during a given examination, so be careful when
      specifying matchers that may be satisfied by more than one entry in an examined iterable.

   For example:

   .. code-block:: php

      assertThat(new ArrayIterator(['foo', 'bar']), containsInAnyOrder(equalTo('bar'), equalTo('foo')));

   :param mixed ...$itemMatchers: Values or ``PHPUnit\Framework\Constraint\Constraint`` instances that must be satisfied by the items in the examined value.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: containsInRelativeOrder(...$itemMatchers)

   Creates a matcher for iterables that matches when a single pass over the examined iterable yields a series of items
   that satisfy the item matchers in the same relative order.

   For example:

   .. code-block:: php

      assertThat(['a', 'b', 'c', 'd', 'e'], containsInRelativeOrder(equalTo('b'), equalTo('d')));

   :param mixed ...$itemMatchers: Values or ``PHPUnit\Framework\Constraint\Constraint`` instances that must be satisfied by the items in the examined value.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: containsString($substring)

   Creates a matcher that matches if the examined value contains the specified substring anywhere.

   For example:

   .. code-block:: php

      assertThat('myStringOfNote', containsString('ring'));

   :param string $substring:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: containsStringIgnoringCase($substring)

   Creates a matcher that matches if the examined value contains the specified substring anywhere, ignoring casing.

   For example:

   .. code-block:: php

      assertThat('myStringOfNote', containsStringIgnoringCase('Ring'));

   :param string $substring:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: describedAs($description, $matcher[, ...$values])

   Wraps an existing matcher, overriding its description with that specified. All other functions are delegated to the
   decorated matcher.

   The beginning of failure messages is "Failed asserting that" in most cases. The description provided here should
   return the second part of that sentence.

   For example:

   .. code-block:: php

      describedAs('value is a big decimal with the value %s', equalTo($myBigDecimal), $myBigDecimal->toString());

   :param string $description: The new description for the wrapped matcher. This may be a formatted string including conversion specifications, as used by `sprintf() <https://www.php.net/sprintf>`_. You may use the ``$values`` arguments to insert values into the formatted description.
   :param mixed $matcher: A value or ``PHPUnit\Framework\Constraint\Constraint`` to match against.
   :param mixed ...$values: Values to insert into the description if it is an `sprintf() <https://www.php.net/sprintf>`_-formatted string.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: either($matcher)

   Creates a matcher that matches when either of the specified matchers matches the examined value.

   For example:

   .. code-block:: php

      assertThat('fan', either(containsString('a'))->or(containsString('b')));

   :param mixed $matcher: A value or ``PHPUnit\Framework\Constraint\Constraint`` to match against.
   :returntype: RestCertain\\Hamcrest\\Constraint\\CombinedConstraint

.. php:function:: emptyArray()

   Creates a matcher for arrays that matches when the length of the array is zero.

   For example:

   .. code-block:: php

      assertThat([], emptyArray());

   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: emptyIterable()

   Creates a matcher for iterables matching examined iterables that have no items.

   For example:

   .. code-block:: php

      assertThat(new ArrayIterator(), is(emptyIterable()));

   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: emptyOrNullString()

   Creates a matcher that matches when the examined value is ``null``, or is a zero-length string.

   For example:

   .. code-block:: php

      assertThat(null, is(emptyOrNullString()));

   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: emptyString()

   Creates a matcher that matches when the examined string is a zero-length string.

   For example:

   .. code-block:: php

      assertThat('', is(emptyString()));

   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: endsWith($substring)

   Creates a matcher that matches if the examined value ends with the specified substring.

   For example:

   .. code-block:: php

      assertThat('myStringOfNote', endsWith('Note'));

   :param string $substring:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: endsWithIgnoringCase($substring)

   Creates a matcher that matches if the examined value ends with the specified substring, ignoring casing.

   For example:

   .. code-block:: php

      assertThat('myStringOfNote', endsWithIgnoringCase('note'));

   :param string $substring:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: equalTo($operand)

   Creates a matcher that matches when the examined value is logically equal to the specified operand. The values are
   compared using the equality operator (``==``). In PHP, two object instances are equal if they have the same attributes
   and values (also compared using equality) and are instances of the same class. Two non-object values are equal if
   they have the same value after type juggling (i.e., ``"1" == 1``).

   :param mixed $operand:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: equalToCompressingWhitespace($expectedString)

   Creates a matcher that matches when the examined string is equal to the specified ``$expectedString``, when whitespace
   differences are (mostly) ignored. To be exact, the following whitespace rules are applied:

   - All leading and trailing whitespace of both the ``$expectedString`` and the examined string are ignored.
   - Any remaining whitespace, appearing within either string, is collapsed to a single space before comparison.

   For example:

   .. code-block:: php

      assertThat("   my\tfoo  bar ", equalToIgnoringWhiteSpace(' my  foo bar'));

   :param string $expectedString:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: equalToIgnoringCase($expectedString)

   Creates a matcher that matches when the examined string is equal to the specified ``$expectedString``, ignoring casing.

   For example:

   .. code-block:: php

      assertThat('Foo', equalToIgnoringCase('FOO'));

   :param string $expectedString:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: equalToIgnoringWhiteSpace($expectedString)

   Creates a matcher that matches when the examined string is equal to the specified ``$expectedString``, when whitespace
   differences are (mostly) ignored.

   For more information, see :php:func:`equalToCompressingWhitespace()`.

   :param string $expectedString:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: equalToObject($operand)

   Creates a matcher that is identical to :php:func:`equalTo()` except the operand must be an object.

   :param object $operand:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: everyItem($itemMatcher)

   Creates a matcher for iterables that only matches when a single pass over the examined iterable yields items that are
   all matched by the specified item matcher.

   For example:

   .. code-block:: php

      assertThat(['bar', 'baz'], everyItem(startsWith('ba')));

   :param mixed $itemMatcher: A value or ``PHPUnit\Framework\Constraint\Constraint`` to match against every item in the iterable.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: greaterThan($value)

   Creates a matcher that matches when the examined value is greater than the specified value.

   For example:

   .. code-block:: php

      assertThat(2, is(greaterThan(1)));

   :param float|int|string $value:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: greaterThanOrEqualTo($value)

   Creates a matcher that matches when the examined value is greater than or equal to the specified value.

   For example:

   .. code-block:: php

      assertThat(1, is(greaterThanOrEqualTo(1)));

   :param float|int|string $value:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: hasEntry($keyMatcher, $valueMatcher)

   Creates a matcher for hash maps (i.e., associative arrays) matching when the examined hash map contains at least one
   entry whose key satisfies the specified ``$keyMatcher`` **and** whose value satisfies the specified ``$valueMatcher``.

   For example:

   .. code-block:: php

      assertThat(['bar' => 'foo'], hasEntry(equalTo('bar'), equalTo('foo')))

   :param mixed $keyMatcher: A value or ``PHPUnit\Framework\Constraint\Constraint`` that, in combination with the ``$valueMatcher``, must be satisfied by at least one entry.
   :param mixed $valueMatcher: A value or ``PHPUnit\Framework\Constraint\Constraint`` that, in combination with the ``$keyMatcher``, must be satisfied by at least one entry.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: hasItem($itemMatcher)

   Creates a matcher for iterables that only matches when a single pass over the examined iterable yields at least one
   item that is matched by the specified item matcher. While matching, the traversal of the examined iterable will stop
   as soon as a matching item is found.

   For example:

   .. code-block:: php

      assertThat(new ArrayIterator(['foo', 'bar']), hasItem(startsWith('ba')));

   :param mixed $itemMatcher: A value or ``PHPUnit\Framework\Constraint\Constraint`` to match against.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: hasItemInArray($itemMatcher)

   Creates a matcher for arrays that matches when the examined array contains at least one item that is matched by the
   specified item matcher. The traversal of the examined array will stop as soon as a matching item is found.

   For example:

   .. code-block:: php

      assertThat(['foo', 'bar'], hasItemInArray(startsWith('ba')));

   :param mixed $itemMatcher: A value or ``PHPUnit\Framework\Constraint\Constraint`` to match against.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: hasItems(...$itemMatchers)

   Creates a matcher for iterables that matches when consecutive passes over the examined iterable yield at least one
   item that is matched by the corresponding matcher from the specified item matchers. While matching, each traversal of
   the examined iterable will stop as soon as a matching item is found.

   For example:

   .. code-block:: php

      assertThat(['foo', 'bar', 'baz'], hasItems(endsWith('z'), endsWith('o')));

   :param mixed ...$itemMatchers: A value or ``PHPUnit\Framework\Constraint\Constraint`` to match against.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: hasKey($keyMatcher)

   Creates a matcher for hash maps (i.e., associative arrays) matching when the examined hash map contains at least one
   key that satisfies the specified matcher.

   For example:

   .. code-block:: php

      assertThat(['foo' => 'bar'], hasKey(equalTo('foo')));

   :param mixed $keyMatcher: A value or ``PHPUnit\Framework\Constraint\Constraint`` that must be satisfied by at least one entry.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: hasSize($sizeMatcher)

   Creates a matcher for iterables that matches when the length of the iterable satisfies the specified matcher.

   For example:

   .. code-block:: php

      assertThat(new ArrayIterator(['foo', 'bar']), hasSize(equalTo(2)));

   :param PHPUnit\\Framework\\Constraint\\Constraint|int $sizeMatcher:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: hasValue($valueMatcher)

   Creates a matcher for hash maps (i.e., associative arrays) matching when the examined hash map contains at least one
   value that satisfies the specified matcher.

   For example:

   .. code-block:: php

      assertThat(new ArrayIterator(['foo' => 'bar']), hasValue(equalTo('bar')));

   :param mixed $valueMatcher: A value or ``PHPUnit\Framework\Constraint\Constraint`` that must be satisfied by at least one entry.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: in($collection)

   Creates a matcher that matches when the examined object is found within the specified iterable.

   For example:

   .. code-block:: php

      assertThat('foo', is(in(['bar', 'foo'])));

   :param iterable $collection: The iterable in which matching items must be found.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: is($matcher)

   If providing a value, this allows a slightly more expressive way to write equality tests. If providing a constraint,
   this wraps the constraint, allowing for a slightly more expressive way to write tests.

   For example:

   .. code-block:: php

      assertThat($cheese, is($smelly));
      assertThat($cheese, is(equalTo($smelly)));

   Instead of:

   .. code-block:: php

      assertThat($cheese, $smelly);
      assertThat($cheese, equalTo($smelly));

   :param mixed $matcher: A value or ``PHPUnit\Framework\Constraint\Constraint`` to wrap.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: isA($type)

   Creates a matcher that performs an identity check against the examined value and the type.

   The type may be a fully qualified class name or a string indicating the type. Accepted type values are:

   - ``'array'``
   - ``'bool'``
   - ``'callable'``
   - ``'float'``
   - ``'int'``
   - ``'iterable'``
   - ``'null'``
   - ``'numeric'``
   - ``'object'``
   - ``'resource'``
   - ``'scalar'``
   - ``'string'``

   :param string $type:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: isAn($type)

   This is an alias of :php:func:`isA()`, provided for easier reading of test statements.

   :param string $type:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: isEmpty()

   Creates a matcher that matches when the examined value is considered empty.

   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: iterableWithSize($sizeMatcher)

   Creates a matcher for iterables that matches when the length of the iterable satisfies the specified matcher.

   For example:

   .. code-block:: php

      assertThat(new ArrayIterator(['foo', 'bar']), iterableWithSize(equalTo(2)));

   :param PHPUnit\\Framework\\Constraint\\Constraint|int $sizeMatcher:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: lessThan($value)

   Creates a matcher that matches when the examined value is less than the specified value.

   For example:

   .. code-block:: php

      assertThat(1, is(lessThan(2)));

   :param float|int|string $value:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: lessThanOrEqualTo($value)

   Creates a matcher that matches when the examined value is less than or equal to the specified value.

   For example:

   .. code-block:: php

      assertThat(1, is(lessThanOrEqualTo(1)));

   :param float|int|string $value:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: matchesPattern($pattern)

   Creates a matcher that matches if the examined value matches the Perl-compatible regular expression.

   .. code-block:: php

      assertThat('abc', matchesPattern('/^[a-z]+$/'));

   :param string $pattern:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: matchesRegex($pattern)

   Creates a matcher that matches if the examined value matches the Perl-compatible regular expression.

   .. code-block:: php

      assertThat('abc', matchesRegex('/^[a-z]+$/'));

   :param string $pattern:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: not($matcher)

   Creates a matcher that wraps an existing matcher but inverts the logic by which it will match.

   For example:

   .. code-block:: php

      assertThat($cheese, is(not(equalTo($smelly))));

   :param mixed $matcher: A value or ``PHPUnit\Framework\Constraint\Constraint`` whose sense will be inverted.
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: notANumber()

   Creates a matcher that matches when an examined float is not a number (i.e., ``is_nan()`` returns ``true``).

   For example:

   .. code-block:: php

      assertThat(sqrt(-1), is(notANumber()));

   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: notNullValue()

   A shortcut to the frequently used ``not(nullValue())``.

   For example:

   .. code-block:: php

      assertThat($cheese, is(notNullValue()));

   Instead of:

   .. code-block:: php

      assertThat($cheese, is(not(nullValue())));

   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: nullValue()

   Creates a matcher that matches if examined value is ``null``.

   For example:

   .. code-block:: php

      assertThat($cheese, is(nullValue());

   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: oneOf(...$elements)

   Creates a matcher that matches when the examined value is equal to one of the specified elements.

   For example:

   .. code-block:: php

      assertThat('foo', is(oneOf('bar', 'foo')));

   :param mixed ...$elements:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: sameInstance($object)

   Creates a matcher that matches only when the examined value is the same instance as the specified target object.

   :param mixed $object:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: startsWith($substring)

   Creates a matcher that matches if the examined value starts with the specified substring.

   For example:

   .. code-block:: php

      assertThat('myStringOfNote', startsWith('my'));

   :param string $substring:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: startsWithIgnoringCase($substring)

   Creates a matcher that matches if the examined value starts with the specified substring, ignoring casing.

   For example:

   .. code-block:: php

      assertThat('myStringOfNote', startsWithIgnoringCase('My'));

   :param string $substring:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

.. php:function:: theInstance($object)

   Creates a matcher that matches only when the examined value is the same instance as the specified target object.

   :param mixed $object:
   :returntype: PHPUnit\\Framework\\Constraint\\Constraint

