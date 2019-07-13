<?php
declare(strict_types=1);

namespace TobiasTest\Urn\RFC2141;

use PHPUnit\Framework\TestCase;
use Tobias\Urn\Exception\InvalidFormatException;
use Tobias\Urn\Exception\InvalidLengthException;
use Tobias\Urn\RFC2141\NamespaceIdentifier;

/**
 * @coversDefaultClass \Tobias\Urn\RFC2141\NamespaceIdentifier
 */
final class NamespaceIdentifierTest extends TestCase
{
    public function invalidStringProvider(): iterable
    {
        yield 'empty' => [
            '',
            InvalidLengthException::class,
            'Namespace identifier cannot be empty.',
        ];
        yield '< 2 chars' => [
            'a',
            InvalidLengthException::class,
            'Namespace identifier is only 1 characters long, expected at least 2 characters.',
        ];
        yield '> 32 chars' => [
            'invalid-identifier-which-exceeds-32-chars',
            InvalidLengthException::class,
            'Namespace identifier is 41 characters long, expected at most 32 characters.',
        ];
        yield 'non-alnum start-char' => [
            '!foobar',
            InvalidFormatException::class,
            'Namespace identifier "!foobar" is not valid.',
        ];
        yield 'non-alpha start-char' => [
            'äfoobar',
            InvalidFormatException::class,
            'Namespace identifier "äfoobar" is not valid.',
        ];
        yield 'non-alnum start-char 2' => [
            '-foobar',
            InvalidFormatException::class,
            'Namespace identifier "-foobar" is not valid.',
        ];
        yield 'reserved nid' => [
            'urn',
            InvalidFormatException::class,
            'Namespace identifier "urn" is reserved and cannot be used.',
        ];
    }

    /**
     * @dataProvider invalidStringProvider
     *
     * @param string $nid
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     */
    public function testThrowExceptionsWithInvalidFormat(
        string $nid,
        string $expectedException,
        string $expectedExceptionMessage
    ): void {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        new NamespaceIdentifier($nid);
    }

    public function testToString(): void
    {
        $this->assertSame('identifier', (string)new NamespaceIdentifier('identifier'));
    }
}
