<?php
declare(strict_types=1);

namespace Tobias\Urn\RFC8141;

use Tobias\Urn\AbstractNamespaceSpecificString;

final class NamespaceSpecificString extends AbstractNamespaceSpecificString
{
    private const PCHAR = '[a-z0-9-._~]|%[a-f0-9]{2}|[!$&\'()*+,;=]|:|@';
    private const PATTERN_ALLOWED = '/^(' . self::PCHAR . ')(' . self::PCHAR . '|\/|\\?)*$/i';
//    public const PATTERN_ALLOWED = '[A-Za-z0-9\(\)+,-.:=@;\$_!*\'%/?#]|%[a-fA-F0-9]{2}';

    public function __construct(string $nss, bool $isEncoded)
    {
        parent::__construct($nss, $isEncoded, self::PATTERN_ALLOWED);
    }
}
