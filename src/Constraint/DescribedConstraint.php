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

use Override;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Util\Exporter;
use Stringable;

use function is_string;
use function sprintf;

/**
 * Wraps an existing matcher, overriding its description with that specified. All other functions are delegated to
 * the decorated matcher.
 *
 * The beginning of failure messages is "Failed asserting that" in most cases. The description provided here should
 * return the second part of that sentence. (See also {@see Constraint::failureDescription()}.)
 *
 * For example:
 *
 * ```
 * describedAs('value is a big decimal with the value %s', equalTo($myBigDecimal), $myBigDecimal->toString());
 * ```
 */
final class DescribedConstraint extends Constraint
{
    private readonly Constraint $constraint;

    /**
     * @var mixed[]
     */
    private readonly array $values;

    /**
     * @param string $description The new description for the wrapped matcher. This may be a formatted string including
     *     conversion specifications, as used by {@see sprintf()}. You may use the `$values` arguments to insert values
     *     into the formatted description.
     * @param mixed $constraint A value or {@see Constraint} to test against.
     * @param mixed ...$values Values to insert into the description if it is an {@see sprintf()}-formatted string.
     */
    public function __construct(private readonly string $description, mixed $constraint, mixed ...$values)
    {
        if (!$constraint instanceof Constraint) {
            $constraint = new IsEqual($constraint);
        }

        $this->constraint = $constraint;
        $this->values = $values;
    }

    #[Override] public function toString(): string
    {
        return '';
    }

    #[Override] protected function failureDescription(mixed $other): string
    {
        $exportedValues = [];
        foreach ($this->values as $value) {
            // Check for strings, since the exporter adds quotation marks, and we don't want that.
            if ($value instanceof Stringable || is_string($value)) {
                $exportedValues[] = (string) $value;
            } else {
                $exportedValues[] = Exporter::shortenedExport($value);
            }
        }

        if ($exportedValues !== []) {
            return sprintf($this->description, ...$exportedValues);
        }

        return $this->description;
    }

    #[Override] protected function matches(mixed $other): bool
    {
        return (bool) $this->constraint->evaluate($other, '', true);
    }
}
