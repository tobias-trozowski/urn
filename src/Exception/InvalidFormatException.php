<?php
declare(strict_types=1);

namespace Tobias\Urn\Exception;

use InvalidArgumentException;

final class InvalidFormatException extends InvalidArgumentException implements UrnException
{
}
