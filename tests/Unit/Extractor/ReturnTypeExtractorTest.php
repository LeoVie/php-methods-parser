<?php

declare(strict_types=1);

namespace LeoVie\PhpMethodsParser\Tests\Unit\Extractor;

use LeoVie\PhpMethodsParser\Converter\NodeTypeToCodeConverter;
use LeoVie\PhpMethodsParser\Extractor\ReturnTypeExtractor;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPUnit\Framework\TestCase;

class ReturnTypeExtractorTest extends TestCase
{
    /** @dataProvider extractProvider */
    public function testExtract(string $expected, NodeTypeToCodeConverter $nodeTypeToStringConverter, ClassMethod|Function_ $method): void
    {
        self::assertSame($expected, (new ReturnTypeExtractor($nodeTypeToStringConverter))->extract($method));
    }

    public function extractProvider(): \Generator
    {
        $nodeTypeToStringConverter = $this->createMock(NodeTypeToCodeConverter::class);
        $nodeTypeToStringConverter->method('convert')->willReturnCallback(
            fn(Identifier $type): string => 'converted_' . $type->toString()
        );

        $method = $this->createMock(ClassMethod::class);
        $method->method('getReturnType')->willReturn(new Identifier('int'));

        yield [
            'expected' => 'converted_int',
            'nodeTypeToStringConverter' => $nodeTypeToStringConverter,
            'method' => $method,
        ];
    }
}