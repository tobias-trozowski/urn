<?php
declare(strict_types=1);

namespace Tobias\Urn\RFC2141;

use Tobias\Urn\Exception\InvalidFormatException;
use Tobias\Urn\NamespaceIdentifier as NamespaceIdentifierInterface;
use Tobias\Urn\Urn as UrnInterface;
use Tobias\Urn\ValidateNamespaceIdentifierTrait;
use function sprintf;

final class NamespaceIdentifier implements NamespaceIdentifierInterface
{
    use ValidateNamespaceIdentifierTrait;
    private const MIN_LENGTH = 2;
    private const MAX_LENGTH = 32;
    private const PATTERN_ALLOWED = '/^[0-9a-z][0-9a-z-]{1,31}$/i';


    /** @var string */
    private $namespaceIdentifier;

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

    public function toString(): string
    {
        return $this->namespaceIdentifier;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
