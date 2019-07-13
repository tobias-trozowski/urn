<?php
declare(strict_types=1);

namespace Tobias\Urn\RFC2141;

use Tobias\Urn\AbstractNamespaceSpecificString;

final class NamespaceSpecificString extends AbstractNamespaceSpecificString
{
    public const PATTERN_ALLOWED = '/^([0-9a-z()+,-.:=@;\$_!*\']|(%[0-9a-f]{2}))+$/i';

    public function __construct(string $nss, bool $isEncoded)
    {
        parent::__construct($nss, $isEncoded, self::PATTERN_ALLOWED);
    }
}
