<?php
declare(strict_types=1);

namespace Tobias\Urn\RFC8141;

use Tobias\Urn\NamespaceIdentifier;
use Tobias\Urn\NamespaceSpecificString;
use Tobias\Urn\RQFComponent;
use Tobias\Urn\Urn as UrnInterface;

/**
 * @see https://tools.ietf.org/html/rfc8141
 */
final class Urn implements UrnInterface
{
    /** @var NamespaceIdentifier */
    private $namespaceIdentifier;

    /** @var NamespaceSpecificString */
    private $namespaceSpecificString;

    /** @var RQFComponent */
    private $rqfComponent;

    /**
     * Urn constructor.
     *
     * @param NamespaceIdentifier     $namespaceIdentifier
     * @param NamespaceSpecificString $namespaceSpecificString
     * @param RQFComponent            $rqfComponents
     */
    public function __construct(
        NamespaceIdentifier $namespaceIdentifier,
        NamespaceSpecificString $namespaceSpecificString,
        RQFComponent $rqfComponents
    ) {
        $this->namespaceIdentifier = $namespaceIdentifier;
        $this->namespaceSpecificString = $namespaceSpecificString;
        $this->rqfComponent = $rqfComponents;
    }

    public function getNamespaceIdentifier(): NamespaceIdentifier
    {
        return $this->namespaceIdentifier;
    }

    public function getNamespaceSpecificString(): NamespaceSpecificString
    {
        return $this->namespaceSpecificString;
    }

    public function getRQF(): RQFComponent
    {
        return $this->rqfComponent;
    }

    public function toString(): string
    {
        return 'urn:' . $this->namespaceIdentifier->toString()
            . ':' . $this->namespaceSpecificString->toString()
            . $this->rqfComponent->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Compares this URN to another one and returns true whenever both are equal to each other
     *
     * As described in Section 3, the r-, q- and f-component SHALL NOT be taken into
     * account when determining URN-equivalence.
     *
     * @param UrnInterface $other
     *
     * @return bool
     * @see https://tools.ietf.org/html/rfc8141#section-3
     *
     */
    public function equals(UrnInterface $other): bool
    {
        return $this->namespaceIdentifier->toString() === $other->getNamespaceIdentifier()->toString()
            && $this->namespaceSpecificString->toString() === $other->getNamespaceSpecificString()->toString();
    }
}
