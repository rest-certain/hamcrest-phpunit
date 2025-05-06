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
use PHPUnit\Framework\Constraint\IsEqual;

use function is_iterable;
use function sprintf;

final class IterableMatchesEveryItem extends Constraint
{
    private readonly Constraint $constraint;

    public function __construct(mixed $constraint)
    {
        if (!$constraint instanceof Constraint) {
            $constraint = new IsEqual($constraint);
        }

        $this->constraint = $constraint;
    }

    public function toString(): string
    {
        return $this->constraint->toString();
    }

    protected function failureDescription(mixed $other): string
    {
        if (!is_iterable($other)) {
            return 'value is an iterable';
        }

        return sprintf('value is an iterable in which each item %s', $this->toString());
    }

    protected function matches(mixed $other): bool
    {
        if (!is_iterable($other)) {
            return false;
        }

        foreach ($other as $item) {
            if (!$this->constraint->evaluate($item, '', true)) {
                return false;
            }
        }

        return true;
    }
}
