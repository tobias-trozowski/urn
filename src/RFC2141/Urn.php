<?php
declare(strict_types=1);

namespace Tobias\Urn\RFC2141;

use Tobias\Urn\NamespaceIdentifier as NamespaceIdentifierInterface;
use Tobias\Urn\NamespaceSpecificString as NamespaceSpecificStringInterface;
use Tobias\Urn\Urn as UrnInterface;

final class Urn implements UrnInterface
{
    /** @var NamespaceIdentifier */
    private $namespaceIdentifier;

    /** @var NamespaceSpecificString */
    private $namespaceSpecificString;

    /**
     * Urn constructor.
     *
     * @param NamespaceIdentifier     $nid
     * @param NamespaceSpecificString $nss
     */
    public function __construct(NamespaceIdentifier $nid, NamespaceSpecificString $nss)
    {
        $this->namespaceIdentifier = $nid;
        $this->namespaceSpecificString = $nss;
    }

    public function getNamespaceIdentifier(): NamespaceIdentifierInterface
    {
        return $this->namespaceIdentifier;
    }

    public function getNamespaceSpecificString(): NamespaceSpecificStringInterface
    {
        return $this->namespaceSpecificString;
    }

    public function toString(): string
    {
        return 'urn:' . $this->namespaceIdentifier->toString() . ':' . $this->namespaceSpecificString->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @inheritDoc
     */
    public function equals(UrnInterface $other): bool
    {
        return $this->namespaceIdentifier->toString() === $other->getNamespaceIdentifier()->toString()
            && $this->namespaceSpecificString->toString() === $other->getNamespaceSpecificString()->toString();
    }
}
