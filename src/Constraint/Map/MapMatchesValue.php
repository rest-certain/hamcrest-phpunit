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

namespace RestCertain\Hamcrest\Constraint\Map;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;

use function array_is_list;
use function is_iterable;
use function iterator_to_array;
use function sprintf;

final class MapMatchesValue extends Constraint
{
    private Constraint $valueConstraint;

    public function __construct(mixed $valueConstraint)
    {
        if (!$valueConstraint instanceof Constraint) {
            $valueConstraint = new IsEqual($valueConstraint);
        }

        $this->valueConstraint = $valueConstraint;
    }

    public function toString(): string
    {
        return '';
    }

    protected function failureDescription(mixed $other): string
    {
        if (!is_iterable($other) || array_is_list(iterator_to_array($other))) {
            return 'value is a hash map (i.e., associative array)';
        }

        return sprintf('value is a hash map with a value that %s', $this->valueConstraint->toString());
    }

    protected function matches(mixed $other): bool
    {
        if (!is_iterable($other) || array_is_list(iterator_to_array($other))) {
            return false;
        }

        foreach ($other as $value) {
            if ($this->valueConstraint->evaluate($value, '', true)) {
                return true;
            }
        }

        return false;
    }
}
