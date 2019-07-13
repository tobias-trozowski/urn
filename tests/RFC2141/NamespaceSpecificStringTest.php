<?php
declare(strict_types=1);

namespace TobiasTest\Urn\RFC2141;

use PHPUnit\Framework\TestCase;
use Tobias\Urn\Exception\InvalidFormatException;
use Tobias\Urn\RFC2141\NamespaceSpecificString;
use function sprintf;

/**
 * @coversDefaultClass \Tobias\Urn\RFC2141\NamespaceSpecificString
 */
final class NamespaceSpecificStringTest extends TestCase
{
    public function validStringProvider(): iterable
    {
        yield ['0123456789abcdefghijklmnopqrstuvwxyz', true];
        yield ['-._', true];
        yield ['()+,:=@;$!*\'', true];
        yield ['%ab%cd%ef', true];
    }

    public function invalidStringProvider(): iterable
    {
        yield ['~#', true];
    }

    /**
     * @dataProvider validStringProvider
     *
     * @param string $string
     * @param bool   $encoded
     */
    public function testValidStringsAccepted(
        string $string,
        bool $encoded
    ): void {
        new NamespaceSpecificString($string, $encoded);
        $this->addToAssertionCount(1);
    }

    /**
     * @dataProvider invalidStringProvider
     *
     * @param string $string
     * @param bool   $encoded
     */
    public function testInvalidStringsThrowsException(
        string $string,
        bool $encoded
    ): void {
        $this->expectException(InvalidFormatException::class);
        $this->expectExceptionMessage(sprintf('Namespace specific string "%s" contains invalid characters.', $string));

        new NamespaceSpecificString($string, $encoded);
    }
}
