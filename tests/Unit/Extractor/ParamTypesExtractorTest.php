<?php

declare(strict_types=1);

namespace LeoVie\PhpMethodsParser\Tests\Unit\Extractor;

use LeoVie\PhpMethodsParser\Converter\NodeTypeToCodeConverter;
use LeoVie\PhpMethodsParser\Extractor\ParamTypesExtractor;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPUnit\Framework\TestCase;

class ParamTypesExtractorTest extends TestCase
{
    /** @dataProvider extractProvider */
    public function testExtract(array $expected, NodeTypeToCodeConverter $nodeTypeToStringConverter, ClassMethod|Function_ $method): void
    {
        self::assertSame($expected, (new ParamTypesExtractor($nodeTypeToStringConverter))->extract($method));
    }

    public function extractProvider(): \Generator
    {
        $nodeTypeToStringConverter = $this->createMock(NodeTypeToCodeConverter::class);
        $nodeTypeToStringConverter->method('convert')->willReturnCallback(
            fn(Identifier $type): string => 'converted_' . $type->toString()
        );

        $method = $this->createMock(ClassMethod::class);
        $method->params = [
            $this->mockParam(new Identifier('int')),
            $this->mockParam(new Identifier('string')),
        ];

        yield [
            'expected' => [
                'converted_int',
                'converted_string'
            ],
            'nodeTypeToStringConverter' => $nodeTypeToStringConverter,
            'method' => $method,
        ];
    }

    private function mockParam(null|Identifier|Name|ComplexType $type): Param
    {
        $param = $this->createMock(Param::class);
        $param->type = $type;

        return $param;
    }
}