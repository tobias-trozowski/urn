<?php
declare(strict_types=1);

namespace TobiasTest\Urn;

use PHPUnit\Framework\TestCase;
use Tobias\Urn\RQFComponent;

final class RQFComponentTest extends TestCase
{
    public function testToString(): void
    {
        $this->assertSame('', (new RQFComponent('', '', ''))->toString());
        $this->assertSame('', (string)new RQFComponent('', '', ''));

        $rqf = new RQFComponent('foo=bar&baz=bar', 'bar=baz&foo=bar', 'fragment');
        $this->assertSame('?+foo=bar&baz=bar?=bar=baz&foo=bar#fragment', $rqf->toString());
    }

    public function testResolutionParams(): void
    {
        $rqf = new RQFComponent('foo=bar&baz=bar', '', '');
        $this->assertSame(['foo' => 'bar', 'baz' => 'bar'], $rqf->getResolutionParams());
        $this->assertTrue($rqf->hasResolutionParam('foo'));
        $this->assertTrue($rqf->hasResolutionParam('baz'));
        $this->assertSame('bar', $rqf->getResolutionParam('foo'));
        $this->assertSame('bar', $rqf->getResolutionParam('baz'));
    }

    public function testQueryParams(): void
    {
        $rqf = new RQFComponent('', 'foo=bar&baz=bar', '');
        $this->assertSame(['foo' => 'bar', 'baz' => 'bar'], $rqf->getQueryParams());
        $this->assertTrue($rqf->hasQueryParam('foo'));
        $this->assertTrue($rqf->hasQueryParam('baz'));
        $this->assertSame('bar', $rqf->getQueryParam('foo'));
        $this->assertSame('bar', $rqf->getQueryParam('baz'));
    }
}
