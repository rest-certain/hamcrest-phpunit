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

namespace RestCertain\Hamcrest\Constraint\String;

use Override;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Util\Exporter;

use function is_string;
use function preg_replace;
use function sprintf;
use function str_contains;
use function strtolower;
use function trim;

final class StringEqualsStringCompressingWhitespace extends Constraint
{
    public function __construct(private readonly string $value)
    {
    }

    #[Override] public function toString(): string
    {
        if (str_contains($this->value, "\n")) {
            return 'equals <text> when compressing whitespace';
        }

        return sprintf('equals %s when compressing whitespace', Exporter::export($this->value));
    }

    #[Override] protected function matches(mixed $other): bool
    {
        if (!is_string($other)) {
            return false;
        }

        return strtolower($this->compressWhitespace($other)) === strtolower($this->compressWhitespace($this->value));
    }

    private function compressWhitespace(string $string): string
    {
        return trim((string) preg_replace('/[\p{Z}\p{C}]+/u', ' ', $string));
    }
}
