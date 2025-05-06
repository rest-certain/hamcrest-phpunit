<?php

declare(strict_types=1);

namespace RestCertain\Test\Hamcrest\Xml;

use PHPUnit\Framework\TestCase;
use RestCertain\Hamcrest\Xml\NamespaceContext;

class NamespaceContextTest extends TestCase
{
    public function testNamespaceContext(): void
    {
        $namespaceContext = new NamespaceContext('foo', 'https://example.com/foo');

        $this->assertSame('foo', $namespaceContext->prefix);
        $this->assertSame('https://example.com/foo', $namespaceContext->uri);
    }
}
