<?php
declare(strict_types=1);

namespace Tobias\Urn;

use Tobias\Urn\Exception\InvalidFormatException;
use Tobias\Urn\Exception\InvalidLengthException;
use function mb_strlen;
use function preg_match;
use function sprintf;

trait ValidateNamespaceIdentifierTrait
{
    private function validateValue(string $value, int $min, int $max, string $regex): void
    {
        $length = mb_strlen($value);
        if ($length === 0) {
            throw new InvalidLengthException('Namespace identifier cannot be empty.');
        }

        if ($length < $min) {
            throw new InvalidLengthException(
                sprintf(
                    'Namespace identifier is only %d characters long, expected at least %d characters.',
                    $length,
                    $min
                )
            );
        }

        if ($length > $max) {
            throw new InvalidLengthException(
                sprintf(
                    'Namespace identifier is %d characters long, expected at most %d characters.',
                    $length,
                    $max
                )
            );
        }

        if (preg_match($regex, $value) !== 1) {
            throw new InvalidFormatException(
                sprintf(
                    'Namespace identifier "%s" is not valid.',
                    $value
                )
            );
        }
    }
}
