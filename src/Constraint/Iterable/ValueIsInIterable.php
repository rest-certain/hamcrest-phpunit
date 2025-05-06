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

namespace RestCertain\Hamcrest\Constraint\Iterable;

use PHPUnit\Framework\Constraint\Constraint;

use function in_array;
use function is_array;

final class ValueIsInIterable extends Constraint
{
    /**
     * @param iterable<mixed> $collection The collection to check for the value.
     */
    public function __construct(private readonly iterable $collection, private readonly bool $strict = false)
    {
    }

    public function toString(): string
    {
        return 'is found in the iterable' . ($this->strict ? ' (using strict comparison)' : '');
    }

    protected function matches(mixed $other): bool
    {
        if (is_array($this->collection)) {
            return in_array($other, $this->collection, $this->strict);
        }

        foreach ($this->collection as $value) {
            // phpcs:ignore Universal.Operators.StrictComparisons, SlevomatCodingStandard.Operators.DisallowEqualOperators
            if (!$this->strict && $value == $other) {
                return true;
            }
            if ($this->strict && $value === $other) {
                return true;
            }
        }

        return false;
    }
}
