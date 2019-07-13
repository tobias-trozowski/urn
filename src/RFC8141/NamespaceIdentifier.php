<?php
declare(strict_types=1);

namespace Tobias\Urn\RFC8141;

use Tobias\Urn\Exception\InvalidFormatException;
use Tobias\Urn\NamespaceIdentifier as NamespaceIdentifierInterface;
use Tobias\Urn\Urn as UrnInterface;
use Tobias\Urn\ValidateNamespaceIdentifierTrait;
use function preg_match;
use function sprintf;

/**
 * Represents a Namespace Identifier (NID) part of a Uniform Resource Identifier (URN) according to RFC 8141.
 *
 * @see <a href="https://tools.ietf.org/html/rfc8141">URN Syntax</a>
 * @see <a href="https://tools.ietf.org/html/rfc1737">Functional Requirements for Uniform Resource Names</a>
 * @see <a href="http://www.iana.org/assignments/urn-namespaces/urn-namespaces.xhtml">Official IANA Registry of URN
 *      Namespaces</a>
 */
final class NamespaceIdentifier implements NamespaceIdentifierInterface
{
    use ValidateNamespaceIdentifierTrait;
    private const MIN_LENGTH = 2;
    private const MAX_LENGTH = 32;
    private const PATTERN_ALLOWED = '/^[0-9a-z][0-9a-z-]{0,30}[0-9a-z]$/i';
    private const PATTERN_EXCLUSION = '/^([a-z]{2}-{1,2}|X-)/i';
    private const PATTERN_INFORMAL = '/^(urn-\d\d*)/i';

    /** @var string */
    private $namespaceIdentifier;

    /**
     * NamespaceIdentifier constructor.
     *
     * @param string $namespaceIdentifier
     */
    public function __construct(string $namespaceIdentifier)
    {
        $this->validateValue(
            $namespaceIdentifier,
            self::MIN_LENGTH,
            self::MAX_LENGTH,
            self::PATTERN_ALLOWED
        );
        if ($namespaceIdentifier === UrnInterface::SCHEME) {
            throw new InvalidFormatException(
                sprintf(
                    'Namespace identifier "%s" is reserved and cannot be used.',
                    $namespaceIdentifier
                )
            );
        }
        $this->namespaceIdentifier = $namespaceIdentifier;
    }

    public function isFormal(): bool
    {
        return preg_match(self::PATTERN_EXCLUSION, $this->namespaceIdentifier) !== 1 && !$this->isInformal();
    }

    public function isInformal(): bool
    {
        return preg_match(self::PATTERN_INFORMAL, $this->namespaceIdentifier) === 1;
    }

    public function toString(): string
    {
        return $this->namespaceIdentifier;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
