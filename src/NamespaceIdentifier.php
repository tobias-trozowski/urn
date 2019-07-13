<?php
declare(strict_types=1);

namespace Tobias\Urn;

interface NamespaceIdentifier
{
    public function toString(): string;
}
