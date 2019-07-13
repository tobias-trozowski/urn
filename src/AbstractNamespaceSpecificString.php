<?php
declare(strict_types=1);

namespace Tobias\Urn;

use IntlChar;
use Tobias\Urn\Exception\InvalidFormatException;
use Tobias\Urn\Exception\InvalidLengthException;
use Tobias\Urn\NamespaceSpecificString as NamespaceSpecificStringInterface;
use function ord;
use function preg_match;
use function sprintf;
use function str_split;
use function urldecode;

abstract class AbstractNamespaceSpecificString implements NamespaceSpecificStringInterface
{
    /** @var string */
    protected $encoded;

    /** @var string */
    protected $raw;

    public function __construct(string $nss, bool $isEncoded, string $validationPattern)
    {
        if ($isEncoded) {
            if ($nss === '') {
                throw new InvalidLengthException('Namespace specific string cannot be empty.');
            }
            if (preg_match($validationPattern, $nss) !== 1) {
                throw new InvalidFormatException(
                    sprintf(
                        'Namespace specific string "%s" contains invalid characters.',
                        $nss
                    )
                );
            }
            $this->encoded = $nss;
            $this->raw = $this->decode($nss);
        } else {
            $this->encoded = $this->encode($nss);
            $this->raw = $nss;
        }
    }

    protected function decode(string $encoded): string
    {
        return urldecode($encoded);
    }

    protected function encode(string $rawString): string
    {
        $string = '';
        $array = str_split($rawString);
        foreach ($array as $char) {
            if ($this->isReservedCharacter($char)) {
                $string .= '%';
                $string .= IntlChar::chr(IntlChar::forDigit((ord($char) >> 4) & 0xF, 16));
                $string .= IntlChar::chr(IntlChar::forDigit(ord($char) & 0xF, 16));
            } else {
                $string .= $char;
            }
        }
        return $string;
    }

    private function isReservedCharacter(string $char): bool
    {
        $ord = ord($char);
        return ($ord >= 0x01 && $ord <= 0x20) || ($ord >= 0x7F && $ord <= 0xFF)
            || (
                $char === '%' || $char === '/' || $char === '?' || $char === '#' || $char === '<' || $char === '"'
                || $char === '&' || $char === '\\' || $char === '>' || $char === '[' || $char === ']'
                || $char === '^' || $char === '`' || $char === '{' || $char === '|' || $char === '}' || $char === '~'
            );
    }

    public function getRaw(): string
    {
        return $this->raw;
    }

    public function toString(): string
    {
        return $this->encoded;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
