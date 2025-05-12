Usage
=====

.. _installation:

Installation
------------

To use the REST Certain Hamcrest Matchers for PHPUnit, first require it as a development dependency using Composer:

.. code-block:: console

   composer require --dev rest-certain/hamcrest-phpunit

Using the Matchers
------------------

All matchers are provided as functions available in the :php:ns:`RestCertain\\Hamcrest` namespace. You may alternatively
access them through static methods on the ``RestCertain\Hamcrest\Matchers`` class. It's all up to how you wish to use
them.

Use the Matchers as Functions
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

   use PHPUnit\Framework\TestCase;

   use function RestCertain\Hamcrest\assertThat;
   use function RestCertain\Hamcrest\both;
   use function RestCertain\Hamcrest\containsStringIgnoringCase;
   use function RestCertain\Hamcrest\isA;
   use function RestCertain\Hamcrest\startsWithIgnoringCase;

   class ExampleTest extends TestCase
   {
       public function testExample(): void
       {
           assertThat(
               'The quick brown fox jumps over the lazy dog',
               both(isA('string'))
                   ->and(startsWithIgnoringCase('the'))
                   ->and(containsStringIgnoringCase('FOX')),
           );
       }
   }

Use the Matchers as Methods
~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

   use PHPUnit\Framework\TestCase;
   use RestCertain\Hamcrest\Matchers;

   class ExampleTest extends TestCase
   {
       public function testExample(): void
       {
           Matchers::assertThat(
               'The quick brown fox jumps over the lazy dog',
               Matchers::both(Matchers::isA('string'))
                   ->and(Matchers::startsWithIgnoringCase('the'))
                   ->and(Matchers::containsStringIgnoringCase('FOX')),
           );
       }
   }

Use the Matchers Everywhere You Use PHPUnit Constraints
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Since the matchers return instances of ``PHPUnit\Framework\Constraint\Constraint``, you can use them anywhere you can
use a PHPUnit constraint. For example:

.. code-block:: php

   // Where $this->assertThat() is the PHPUnit method from PHPUnit\Framework\TestCase.
   $this->assertThat(['foo', 'bar', 'baz'], hasItem(startsWith('ba')));

Likewise, many of the matchers accept constraints as arguments, and you may use any PHPUnit constraint.

.. code-block:: php

   assertThat(
       ['foo', 'bar', 'baz'],
       arrayContainingInAnyOrder(
           new StringEndsWith('z'),
           new IsEqualIgnoringCase('FOO'),
           new IsEqual('bar'),
       ),
   );
