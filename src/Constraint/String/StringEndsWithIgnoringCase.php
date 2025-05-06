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

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\EmptyStringException;
use PHPUnit\Util\Exporter;
use Stringable;

use function is_resource;
use function is_scalar;
use function sprintf;
use function str_ends_with;
use function strtolower;

final class StringEndsWithIgnoringCase extends Constraint
{
    /**
     * @throws EmptyStringException
     */
    public function __construct(private readonly string $suffix)
    {
        if ($this->suffix === '') {
            throw new EmptyStringException();
        }
    }

    public function toString(): string
    {
        return sprintf('ends with %s, ignoring case', Exporter::export($this->suffix));
    }

    protected function matches(mixed $other): bool
    {
        if (!$other instanceof Stringable && !is_scalar($other) && !is_resource($other)) {
            return false;
        }

        return str_ends_with(strtolower((string) $other), strtolower($this->suffix));
    }
}
