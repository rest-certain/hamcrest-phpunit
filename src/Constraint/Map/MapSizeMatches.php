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

use InvalidArgumentException;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\Count;
use PHPUnit\Framework\Constraint\GreaterThan;
use PHPUnit\Framework\Constraint\IsEmpty;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Framework\Constraint\SameSize;
use RestCertain\Hamcrest\Constraint\Cardinality\GreaterThanOrEqualTo;
use RestCertain\Hamcrest\Constraint\Cardinality\LessThanOrEqualTo;

use function array_is_list;
use function is_int;
use function is_iterable;
use function iterator_to_array;
use function sprintf;

final class MapSizeMatches extends Count
{
    private Constraint $constraint;

    public function __construct(Constraint | int $constraint)
    {
        $this->constraint = match (true) {
            is_int($constraint) => new Count($constraint),
            $constraint instanceof SameSize, $constraint instanceof Count, $constraint instanceof GreaterThan,
                $constraint instanceof GreaterThanOrEqualTo, $constraint instanceof IsEmpty,
                $constraint instanceof IsEqual, $constraint instanceof LessThan,
                $constraint instanceof LessThanOrEqualTo => $constraint,
            default => throw new InvalidArgumentException(
                'Constraint must be one of the constraints: Count, GreaterThan, GreaterThanOrEqualTo, '
                    . 'IsEmpty, IsEqual, LessThan, LessThanOrEqualTo, or SameSize. Received ' . $constraint::class,
            ),
        };

        // We're extending Count to use its getCountOf() method, but we do not use its expected value.
        parent::__construct(0);
    }

    public function toString(): string
    {
        return $this->constraint->toString();
    }

    protected function failureDescription(mixed $other): string
    {
        if (!is_iterable($other) || (array_is_list($value = iterator_to_array($other)) && $value !== [])) {
            return 'value is a hash map (i.e., associative array)';
        }

        if ($this->constraint instanceof Count) {
            return sprintf('value is a hash map with a %s', $this->toString());
        }

        if ($this->constraint instanceof IsEmpty) {
            return 'value is an empty hash map';
        }

        return sprintf('value is a hash map with a size that %s', $this->toString());
    }

    protected function matches(mixed $other): bool
    {
        if (!is_iterable($other) || (array_is_list($value = iterator_to_array($other)) && $value !== [])) {
            return false;
        }

        if (
            $this->constraint instanceof GreaterThan
            || $this->constraint instanceof GreaterThanOrEqualTo
            || $this->constraint instanceof IsEqual
            || $this->constraint instanceof LessThan
            || $this->constraint instanceof LessThanOrEqualTo
        ) {
            return (bool) $this->constraint->evaluate($this->getCountOf($other), '', true);
        }

        return (bool) $this->constraint->evaluate($other, '', true);
    }
}
