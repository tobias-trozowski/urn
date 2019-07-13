<?php
declare(strict_types=1);

namespace TobiasTest\Urn\RFC2141;

use PHPUnit\Framework\TestCase;
use Tobias\Urn\Exception\ParserException;
use Tobias\Urn\RFC2141\Parser;
use Tobias\Urn\RFC2141\Urn;

/**
 * @coversDefaultClass \Tobias\Urn\RFC2141\Parser
 */
final class ParserTest extends TestCase
{
    /** @var Parser */
    private $object;

    protected function setUp(): void
    {
        $this->object = new Parser();
    }

    /**
     * @covers ::parse
     */
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

    public function testParseValidUrn(): void
    {
        $instance = $this->object->parse('urn:with:valid-string');
        $this->assertInstanceOf(Urn::class, $instance);
    }
}
