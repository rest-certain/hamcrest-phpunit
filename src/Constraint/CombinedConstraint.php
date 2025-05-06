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

namespace RestCertain\Hamcrest\Constraint;

use Closure;
use Override;
use PHPUnit\Framework\Constraint\BinaryOperator;
use PHPUnit\Framework\Constraint\LogicalAnd;
use PHPUnit\Framework\Constraint\LogicalOr;

final class CombinedConstraint extends BinaryOperator
{
    private function __construct(private readonly Operator $operator, mixed $constraint)
    {
        parent::__construct($constraint);
    }

    public function and(mixed $other): self
    {
        return new self(Operator::And, new LogicalAnd($this, $this->checkConstraint($other)));
    }

    #[Override] public function operator(): string
    {
        return $this->operator->value;
    }

    public function or(mixed $other): self
    {
        return new self(Operator::Or, new LogicalOr($this, $this->checkConstraint($other)));
    }

    #[Override] public function precedence(): int
    {
        return $this->operator->precedence();
    }

    #[Override] protected function matches(mixed $other): bool
    {
        return match ($this->operator) {
            Operator::And => $this->getAndMatcher()($other),
            Operator::Or => $this->getOrMatcher()($other),
        };
    }

    /**
     * @return Closure(mixed $other): bool
     */
    private function getAndMatcher(): Closure
    {
        return function (mixed $other): bool {
            foreach ($this->constraints() as $constraint) {
                if (!$constraint->evaluate($other, '', true)) {
                    return false;
                }
            }

            return $this->constraints() !== [];
        };
    }

    /**
     * @return Closure(mixed $other): bool
     */
    private function getOrMatcher(): Closure
    {
        return function (mixed $other): bool {
            foreach ($this->constraints() as $constraint) {
                if ($constraint->evaluate($other, '', true)) {
                    return true;
                }
            }

            return false;
        };
    }

    public static function both(mixed $item): self
    {
        return new self(Operator::And, $item);
    }

    public static function either(mixed $item): self
    {
        return new self(Operator::Or, $item);
    }
}
