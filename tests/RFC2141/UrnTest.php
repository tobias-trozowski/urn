<?php
declare(strict_types=1);

namespace TobiasTest\Urn\RFC2141;

use PHPUnit\Framework\TestCase;
use Tobias\Urn\RFC2141\NamespaceIdentifier;
use Tobias\Urn\RFC2141\NamespaceSpecificString;
use Tobias\Urn\RFC2141\Urn;

/**
 * @coversDefaultClass \Tobias\Urn\RFC2141\Urn
 */
final class UrnTest extends TestCase
{
    /** @var Urn */
    private $object;

    protected function setUp(): void
    {
        $this->object = new Urn(
            new NamespaceIdentifier('foo'),
            new NamespaceSpecificString('bar', false)
        );
    }

    public function testToString(): void
    {
        $this->assertSame('urn:foo:bar', $this->object->toString());
        $this->assertSame('urn:foo:bar', (string)$this->object);
    }

    public function testEqualsOtherUrn(): void
    {
        $other = new Urn(
            new NamespaceIdentifier('foo'),
            new NamespaceSpecificString('bar', false)
        );
        $other2 = new Urn(
            new NamespaceIdentifier('foo'),
            new NamespaceSpecificString('baz', false)
        );

        $this->assertTrue($this->object->equals($other));
        $this->assertFalse($this->object->equals($other2));
    }
}
