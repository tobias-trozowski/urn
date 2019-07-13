<?php
declare(strict_types=1);

namespace Tobias\Urn\RFC2141;

use Throwable;
use Tobias\Urn\Exception\ParserException;
use Tobias\Urn\Urn as UrnInterface;
use Tobias\Urn\UrnParser;
use function explode;
use function sprintf;
use function stripos;

final class Parser implements UrnParser
{
    public function parse(string $stringUrn): UrnInterface
    {
        if ($stringUrn === '') {
            throw new ParserException('String must not be empty.');
        }

        $parts = explode(':', $stringUrn);
        if (count($parts) < 3 || stripos($stringUrn, UrnInterface::SCHEME) !== 0) {
            throw new ParserException(sprintf('Invalid format "%s" is probably not a URN.', $stringUrn));
        }

        try {
            return new Urn(
                new NamespaceIdentifier($parts[1]),
                new NamespaceSpecificString($parts[2], true)
            );
        } catch (Throwable $e) {
            throw new ParserException('Error while parsing URN: ' . $e->getMessage(), 0, $e);
        }
    }
}
