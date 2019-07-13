<?php
declare(strict_types=1);

namespace Tobias\Urn\Exception;

use LengthException;

final class InvalidLengthException extends LengthException implements UrnException
{
}
