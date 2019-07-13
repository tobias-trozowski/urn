<?php
declare(strict_types=1);

namespace TobiasTest\Urn;

use PHPUnit\Framework\TestCase;
use Tobias\Urn\Exception\InvalidLengthException;
use TobiasTest\Urn\Asset\NamespaceSpecificString;
use function array_map;
use function implode;

/**
 * @coversDefaultClass \Tobias\Urn\AbstractNamespaceSpecificString
 */
final class AbstractNamespaceSpecificStringTest extends TestCase
{
    private const PATTERN_ALLOWED = '/.*/i';

    private function int2string(int ...$num): string
    {
        return implode('', array_map('chr', $num));
    }

    public function encodedReservedStringProvider(): iterable
    {
        yield ['%01%05%18%20', $this->int2string(0x01, 0x05, 0x18, 0x20)];
        yield ['%7F%A3%A9%FF', $this->int2string(0x7F, 0xA3, 0xA9, 0xFF)];
        yield ['%25%2F%3F%23%3C%22%26%5C', '%/?#<"&\\'];
        yield ['%3E%5B%5D%5E%60%7B%7C%7D%7E', '>[]^`{|}~'];
    }

    public function rawReservedStringProvider(): iterable
    {
        yield [$this->int2string(0x01, 0x05, 0x18, 0x20), '%01%05%18%20'];
        yield [$this->int2string(0x7F, 0xA3, 0xA9, 0xFF), '%7F%A3%A9%FF'];
        yield ['%/?#<"&\\', '%25%2F%3F%23%3C%22%26%5C'];
        yield ['>[]^`{|}~', '%3E%5B%5D%5E%60%7B%7C%7D%7E'];
    }

    public function testEmptyStringThrowsException(): void
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('Namespace specific string cannot be empty.');
        new NamespaceSpecificString('', true, self::PATTERN_ALLOWED);
    }

    /**
     * @dataProvider encodedReservedStringProvider
     *
     * @param string $string
     * @param string $expectedValue
     */
    public function testReservedStringAreDecodedAccordingly(string $string, string $expectedValue): void
    {
        $nss = new NamespaceSpecificString($string, true, self::PATTERN_ALLOWED);

        $this->assertEqualsIgnoringCase($expectedValue, $nss->getRaw());
    }

    /**
     * @dataProvider rawReservedStringProvider
     *
     * @param string $string
     * @param string $expectedValue
     */
    public function testReservedStringAreEncodedAccordingly(string $string, string $expectedValue): void
    {
        $nss = new NamespaceSpecificString($string, false, self::PATTERN_ALLOWED);

        $this->assertEqualsIgnoringCase($expectedValue, $nss->toString());
    }

    public function testToString(): void
    {
        $nss = new NamespaceSpecificString('foobar', false, self::PATTERN_ALLOWED);
        $this->assertSame('foobar', $nss->toString());
        $this->assertSame('foobar', (string)$nss);
    }
}
