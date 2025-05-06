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

use InvalidArgumentException;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;

use function array_map;
use function count;
use function implode;
use function is_iterable;
use function iterator_to_array;
use function sprintf;

/**
 * The items in the examined iterable must satisfy each corresponding constraint provided, in order. The constraint
 * fails if the examined iterable is of a different size than the number of constraints provided.
 */
final class IterableMatchesItemsInOrder extends Constraint
{
    /**
     * @var Constraint[]
     */
    private array $constraints = [];

    /**
     * @param mixed ...$constraints Values or {@see Constraint}s that must be satisfied by the items in the examined value.
     */
    public function __construct(mixed ...$constraints)
    {
        if ($constraints === []) {
            throw new InvalidArgumentException('At least one constraint must be provided');
        }

        foreach ($constraints as $c) {
            if (!$c instanceof Constraint) {
                $this->constraints[] = new IsEqual($c);
            } else {
                $this->constraints[] = $c;
            }
        }
    }

    public function toString(): string
    {
        return implode(' and ', array_map(fn (Constraint $c) => $c->toString(), $this->constraints));
    }

    protected function failureDescription(mixed $other): string
    {
        if (!is_iterable($other)) {
            return 'value is an iterable';
        }

        if (count($this->constraints) !== count(iterator_to_array($other))) {
            return sprintf('value is an iterable that contains %d items', count($this->constraints));
        }

        return sprintf('value is an iterable where each item (in order) %s', $this->toString());
    }

    protected function matches(mixed $other): bool
    {
        if (!is_iterable($other)) {
            return false;
        }

        $other = iterator_to_array($other);

        if (count($this->constraints) !== count($other)) {
            return false;
        }

        for ($i = 0; $i < count($this->constraints); $i++) {
            if (!$this->constraints[$i]->evaluate($other[$i], '', true)) {
                return false;
            }
        }

        return true;
    }
}
