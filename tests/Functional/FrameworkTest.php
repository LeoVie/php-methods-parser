<?php

declare(strict_types=1);

namespace LeoVie\PhpMethodsParser\Tests\Functional;

use LeoVie\PhpMethodsParser\Service\MethodsParser;
use PHPUnit\Framework\TestCase;

class FrameworkTest extends TestCase
{
    public function testServiceWiring(): void
    {
        $kernel = new TestingKernel('test', true);
        $kernel->boot();
        $methodsParser = $kernel->getContainer()->get(MethodsParser::class);

        self::assertInstanceOf(MethodsParser::class, $methodsParser);
    }
}