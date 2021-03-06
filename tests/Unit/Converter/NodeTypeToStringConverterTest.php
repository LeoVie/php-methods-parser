<?php

declare(strict_types=1);

namespace LeoVie\PhpMethodsParser\Tests\Unit\Converter;

use LeoVie\PhpMethodsParser\Converter\NodeTypeToCodeConverter;
use LeoVie\PhpMethodsParser\Exception\NodeTypeNotConvertable;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use PHPUnit\Framework\TestCase;

class NodeTypeToStringConverterTest extends TestCase
{
    /** @dataProvider convertProvider */
    public function testConvert(string $expected, null|Identifier|Name|ComplexType $type): void
    {
        self::assertSame($expected, (new NodeTypeToCodeConverter())->convert($type));
    }

    public function convertProvider(): array
    {
        return [
            'null' => [
                'expected' => NodeTypeToCodeConverter::VOID_TYPE,
                'type' => null,
            ],
            'Identifier' => [
                'expected' => 'int',
                'type' => new Identifier('int'),
            ],
            'Name' => [
                'expected' => 'Foo\\Bar',
                'type' => new Name('Foo\\Bar'),
            ],
            'UnionType' => [
                'expected' => 'Foo\\Bar|int',
                'type' => new UnionType([new Name('Foo\\Bar'), new Identifier('int')]),
            ],
            'NullableType' => [
                'expected' => '?string',
                'type' => new NullableType(new Identifier('string')),
            ],
        ];
    }

    public function testConvertThrowsWhenNodeTypeNotConvertable(): void
    {
        self::expectException(NodeTypeNotConvertable::class);

        (new NodeTypeToCodeConverter())->convert(new IntersectionType([new Name('Foo\\Bar'), new Identifier('int')]));
    }
}