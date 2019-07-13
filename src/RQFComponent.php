<?php
declare(strict_types=1);

namespace Tobias\Urn;

use function array_key_exists;
use function http_build_query;
use function parse_str;

final class RQFComponent
{
    public const RESOLUTION_SEPERATOR = '?+';
    public const QUERY_SEPERATOR = '?=';
    public const FRAGMENT_SEPERATOR = '#';

    /** @var string[] */
    private $resolutionParams;

    /** @var string[] */
    private $queryParams;

    /** @var string */
    private $fragment;

    /**
     * RQFComponent constructor.
     *
     * @param string $resolutionParams
     * @param string $queryParams
     * @param string $fragment
     */
    public function __construct(string $resolutionParams, string $queryParams, string $fragment)
    {
        $this->resolutionParams = $this->getParamsAsArray($resolutionParams);
        $this->queryParams = $this->getParamsAsArray($queryParams);
        $this->fragment = $fragment;
    }

    private function getParamsAsArray(string $queryString): array
    {
        $query = [];
        if ($queryString !== '') {
            parse_str($queryString, $query);
        }
        return $query;
    }

    public function isEmpty(): bool
    {
        return count($this->resolutionParams) === 0 && count($this->queryParams) === 0 && $this->fragment === '';
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function hasQueryParam(string $key): bool
    {
        return array_key_exists($key, $this->queryParams);
    }

    public function getQueryParam(string $key): string
    {
        if (!$this->hasQueryParam($key)) {
            throw new Exception\OutOfBoundsException('Query param "' . $key . '" does not exist.');
        }
        return $this->queryParams[$key];
    }

    public function getResolutionParams(): array
    {
        return $this->resolutionParams;
    }

    public function hasResolutionParam(string $key): bool
    {
        return array_key_exists($key, $this->resolutionParams);
    }

    public function getResolutionParam(string $key): string
    {
        if (!$this->hasResolutionParam($key)) {
            throw new Exception\OutOfBoundsException('Resolution param "' . $key . '" does not exist.');
        }
        return $this->resolutionParams[$key];
    }

    private function paramsToString(array $params, string $seperator): string
    {
        return count($params) > 0 ? $seperator . http_build_query($params) : '';
    }

    public function toString(): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        return $this->paramsToString($this->resolutionParams, self::RESOLUTION_SEPERATOR)
            . $this->paramsToString($this->queryParams, self::QUERY_SEPERATOR)
            . ($this->fragment !== '' ? self::FRAGMENT_SEPERATOR : '') . $this->fragment;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
