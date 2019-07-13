<?php
declare(strict_types=1);

namespace Tobias\Urn;

interface UrnParser
{
    public function parse(string $stringUrn): Urn;
}
