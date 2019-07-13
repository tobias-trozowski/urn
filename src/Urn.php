<?php
declare(strict_types=1);

namespace Tobias\Urn;

interface Urn
{
    public const SCHEME = 'urn';

    public function getNamespaceIdentifier(): NamespaceIdentifier;

    public function getNamespaceSpecificString(): NamespaceSpecificString;

    /**
     * Compares this URN to another one and returns true whenever both are equal to each other
     *
     * @param Urn $other
     *
     * @return bool
     */
    public function equals(Urn $other): bool;

    public function toString(): string;
}
