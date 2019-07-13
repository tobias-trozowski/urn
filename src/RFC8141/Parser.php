<?php
declare(strict_types=1);

namespace Tobias\Urn\RFC8141;

use Throwable;
use Tobias\Urn\Exception\ParserException;
use Tobias\Urn\RQFComponent as RQF;
use Tobias\Urn\Urn as UrnInterface;
use Tobias\Urn\UrnParser;
use function preg_match;
use function rtrim;
use function sprintf;
use function strlen;
use function strpos;
use function substr;

final class Parser implements UrnParser
{
    public function parse(string $stringUrn): UrnInterface
    {
        if ($stringUrn === '') {
            throw new ParserException('String must not be empty.');
        }

        if (substr_count($stringUrn, ':') < 2 || stripos($stringUrn, UrnInterface::SCHEME) !== 0) {
            throw new ParserException(sprintf('Invalid format "%s" is probably not a URN.', $stringUrn));
        }

        $nid = explode(':', $stringUrn)[1];
        $specificPart = substr($stringUrn, strlen(UrnInterface::SCHEME) + strlen($nid) + 2);
        $specificPart = rtrim($specificPart, '#');
        $endOfNSS = $this->getEndIndex($specificPart);

        try {
            return new Urn(
                new NamespaceIdentifier($nid),
                new NamespaceSpecificString(substr($specificPart, 0, $endOfNSS), true),
                $this->parseRQFComponents(substr($specificPart, $endOfNSS))
            );
        } catch (Throwable $e) {
            throw new ParserException('Error while parsing URN: ' . $e->getMessage(), 0, $e);
        }
    }

    private function getEndIndex(string $string): int
    {
        /** @var int $index */
        $index = strlen($string);

        $rComponentIndex = strpos($string, RQF::RESOLUTION_SEPERATOR);
        if ($rComponentIndex > 0 && $rComponentIndex < $index) {
            $index = $rComponentIndex;
        }
        $qComponentIndex = strpos($string, RQF::QUERY_SEPERATOR);
        if ($qComponentIndex > 0 && $qComponentIndex < $index) {
            $index = $qComponentIndex;
        }
        $fragmentIndex = strpos($string, RQF::FRAGMENT_SEPERATOR);
        if ($fragmentIndex > 0 && $fragmentIndex < $index) {
            $index = $fragmentIndex;
        }

        return $index;
    }

    private function parseRQFComponents(string $rqfString): RQF
    {
        if ($rqfString === '') {
            return new RQF('', '', '');
        }

        $regex = '/^(?!$)(?:\?\+(?P<resolutionParams>.*?))?(?:\?\=(?P<queryParams>.*?))?(?:#(?P<fragment>.*?))?$/i';

        if (preg_match($regex, $rqfString, $matches) !== 1) {
            return new RQF('', '', '');
        }

        $resolutionString = $matches['resolutionParams'] ?? '';
        $queryString = $matches['queryParams'] ?? '';
        $fragmentString = $matches['fragment'] ?? '';

        return new RQF(
            $resolutionString,
            $queryString,
            $fragmentString
        );
    }
}
