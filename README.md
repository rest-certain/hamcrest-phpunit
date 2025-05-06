<h1 align="center">rest-certain/hamcrest-phpunit</h1>

<p align="center">
    <strong>Hamcrest matchers that work natively with PHPUnit</strong>
</p>

<p align="center">
    <a href="https://github.com/rest-certain/hamcrest-phpunit"><img src="https://img.shields.io/badge/source-rest--certain/hamcrest--phpunit-blue.svg?style=flat-square" alt="Source Code"></a>
    <a href="https://packagist.org/packages/rest-certain/hamcrest-phpunit"><img src="https://img.shields.io/packagist/v/rest-certain/hamcrest-phpunit.svg?style=flat-square&label=release" alt="Download Package"></a>
    <a href="https://php.net"><img src="https://img.shields.io/packagist/php-v/rest-certain/hamcrest-phpunit.svg?style=flat-square&colorB=%238892BF" alt="PHP Programming Language"></a>
    <a href="https://github.com/rest-certain/hamcrest-phpunit/blob/main/NOTICE"><img src="https://img.shields.io/packagist/l/rest-certain/hamcrest-phpunit.svg?style=flat-square&colorB=purple" alt="Read License"></a>
    <a href="https://github.com/rest-certain/hamcrest-phpunit/actions/workflows/continuous-integration.yml"><img src="https://img.shields.io/github/actions/workflow/status/rest-certain/hamcrest-phpunit/continuous-integration.yml?branch=main&style=flat-square&logo=github" alt="Build Status"></a>
    <a href="https://codecov.io/gh/rest-certain/hamcrest-phpunit"><img src="https://img.shields.io/codecov/c/gh/rest-certain/hamcrest-phpunit?label=codecov&logo=codecov&style=flat-square" alt="Codecov Code Coverage"></a>
</p>

## About

This library provides [Hamcrest](https://hamcrest.org) matchers that return
[PHPUnit](https://phpunit.de) `Constraint` instances, allowing the matchers to
be used anywhere a PHPUnit constraint is allowed.

This is not an official Hamcrest project and has no affiliation with
[hamcrest.org](https://hamcrest.org). The *matchers* this library provides are
not true Hamcrest matchers in that they do not return Hamcrest `Matcher` instances,
as [defined by the Hamcrest project](https://hamcrest.org/JavaHamcrest/tutorial#writing-custom-matchers).
Instead, the functions in this library return `Constraint` instances, for use
with PHPUnit.

This project adheres to a [code of conduct](CODE_OF_CONDUCT.md). By participating
in this project and its community, you are expected to uphold this code.

> [!TIP]
> Check out the **official** PHP port of Hamcrest Matchers:
> [hamcrest/hamcrest-php](https://packagist.org/packages/hamcrest/hamcrest-php)!

## Installation

Install this package as a development dependency using [Composer](https://getcomposer.org).

``` bash
composer require --dev rest-certain/hamcrest-phpunit
```

## Usage

This library provides all Hamcrest matchers as static methods on the
`RestCertain\Hamcrest\Matchers` class and also as functions in the
`RestCertain\Hamcrest` namespace. These methods and functions may be used within
the context of PHPUnit tests.

``` php
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
```

## Contributing

Contributions are welcome! To contribute, please familiarize yourself with
[CONTRIBUTING.md](CONTRIBUTING.md).

## Coordinated Disclosure

Keeping user information safe and secure is a top priority, and we welcome the
contribution of external security researchers. If you believe you've found a
security issue in software that is maintained in this repository, please read
[SECURITY.md](SECURITY.md) for instructions on submitting a vulnerability report.

## Copyright and License

rest-certain/hamcrest-phpunit is copyright © [REST Certain Contributors](https://rest-certain.dev)
and licensed for use under the terms of the GNU Lesser General Public License
(LGPL-3.0-or-later) as published by the Free Software Foundation. Please see
[COPYING.LESSER](COPYING.LESSER), [COPYING](COPYING), and [NOTICE](NOTICE) for
more information.
