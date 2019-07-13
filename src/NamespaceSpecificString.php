<?php
declare(strict_types=1);

namespace Tobias\Urn;

interface NamespaceSpecificString
{
    public function getRaw(): string;

    public function toString(): string;
}
