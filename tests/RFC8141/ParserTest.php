<?php
declare(strict_types=1);

namespace TobiasTest\Urn\RFC8141;

use PHPUnit\Framework\TestCase;
use Tobias\Urn\Exception\ParserException;
use Tobias\Urn\RFC8141\Parser;
use Tobias\Urn\RFC8141\Urn;

/**
 * @coversDefaultClass \Tobias\Urn\RFC8141\Parser
 */
final class ParserTest extends TestCase
{
    /** @var Parser */
    private $object;

    protected function setUp(): void
    {
        $this->object = new Parser();
    }

    public function testThrowsErrorOnEmptyString(): void
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('String must not be empty.');
        $this->object->parse('');
    }

    public function shortProvider(): iterable
    {
        yield ['urn', 'Invalid format "urn" is probably not a URN.'];
        yield ['urn:', 'Invalid format "urn:" is probably not a URN.'];
        yield ['urn:foo', 'Invalid format "urn:foo" is probably not a URN.'];
        yield ['urn:foo:', 'Error while parsing URN: Namespace specific string cannot be empty.'];
    }

    /**
     * @dataProvider shortProvider
     *
     * @param string $string
     * @param string $expectedExceptionMessage
     */
    public function testThrowsErrorWhenShort(string $string, string $expectedExceptionMessage): void
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->object->parse($string);
    }

    public function testSimpleStringCanBeParsed(): void
    {
        $urn = $this->object->parse('urn:nid:specific-stuff');
        $this->assertInstanceOf(Urn::class, $urn);
        $this->assertSame('urn:nid:specific-stuff', $urn->toString());
    }

    public function testResolutionParamCanBeParsed(): void
    {
        $urn = $this->object->parse('urn:nid:specific-stuff?+a=b&foo=bar');
        $this->assertInstanceOf(Urn::class, $urn);
        $this->assertSame('urn:nid:specific-stuff?+a=b&foo=bar', $urn->toString());
    }

    public function testQueryParamCanBeParsed(): void
    {
        $urn = $this->object->parse('urn:nid:specific-stuff?=x=y&bar=baz');
        $this->assertInstanceOf(Urn::class, $urn);
        $this->assertSame('urn:nid:specific-stuff?=x=y&bar=baz', $urn->toString());
    }

    public function testFragmentCanBeParsed(): void
    {
        $urn = $this->object->parse('urn:nid:specific-stuff#foobar');
        $this->assertInstanceOf(Urn::class, $urn);
        $this->assertSame('urn:nid:specific-stuff#foobar', $urn->toString());
    }

    public function testComplexStringCanBeParsed(): void
    {
        $urn = $this->object->parse('urn:nid:specific-stuff?+a=b&x=y&foo=bar?=x=y&bar=baz#foobar');
        $this->assertInstanceOf(Urn::class, $urn);
        $this->assertSame('urn:nid:specific-stuff?+a=b&x=y&foo=bar?=x=y&bar=baz#foobar', $urn->toString());
    }
}
