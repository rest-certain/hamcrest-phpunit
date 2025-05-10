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
 * Similar to {@see DescribedConstraint}, AdditionallyDescribedConstraint wraps an existing matcher, but instead of
 * overriding the description, it appends to it, enhancing it and adding additional context. All other functions are
 * delegated to the decorated matcher.
 *
 * @see Constraint::additionalFailureDescription
 */
final class AdditionallyDescribedConstraint extends Constraint
{
    private readonly Constraint $constraint;

    /**
     * @var mixed[]
     */
    private readonly array $values;

    /**
     * @param string $additionalDescription The additional description to add to the wrapped matcher. This may be a
     *     formatted string including conversion specifications, as used by {@see sprintf()}; you may use the `$values`
     *     arguments to insert values into the formatted description.
     * @param mixed $constraint A value or {@see Constraint} to test against.
     * @param mixed ...$values Values to insert into the description if it is an {@see sprintf()}-formatted string.
     */
    public function __construct(private readonly string $additionalDescription, mixed $constraint, mixed ...$values)
    {
        if (!$constraint instanceof Constraint) {
            $constraint = new IsEqual($constraint);
        }

        $this->constraint = $constraint;
        $this->values = $values;
    }

    #[Override] public function toString(): string
    {
        return $this->constraint->toString();
    }

    #[Override] protected function additionalFailureDescription(mixed $other): string
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
            return sprintf($this->additionalDescription, ...$exportedValues);
        }

        return $this->additionalDescription;
    }

    #[Override] protected function matches(mixed $other): bool
    {
        return (bool) $this->constraint->evaluate($other, '', true);
    }
}
