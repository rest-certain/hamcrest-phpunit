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

namespace RestCertain\Hamcrest\Constraint\Cardinality;

use Override;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Util\Exporter;

class LessThanOrEqualTo extends Constraint
{
    private LessThan $lessThan;
    private IsEqual $isEqual;

    public function __construct(private readonly mixed $value)
    {
        $this->lessThan = new LessThan($value);
        $this->isEqual = new IsEqual($value);
    }

    #[Override] public function toString(): string
    {
        return 'is less than or equal to ' . Exporter::export($this->value);
    }

    #[Override] protected function matches(mixed $other): bool
    {
        return $this->lessThan->evaluate($other, '', true) || $this->isEqual->evaluate($other, '', true);
    }
}
