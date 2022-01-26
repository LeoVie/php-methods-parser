<?php

declare(strict_types=1);

namespace LeoVie\PhpMethodsParser\Converter;

use LeoVie\PhpMethodsParser\Exception\NodeTypeNotConvertable;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use Safe\Exceptions\StringsException;

class NodeTypeToStringConverter
{
    public const VOID_TYPE = 'void';

    /**
     * @throws NodeTypeNotConvertable
     * @throws StringsException
     */
    public function convert(null|Identifier|Name|ComplexType $type): string
    {
        return match (true) {
            $type === null => $this->convertNull(),
            $type instanceof Identifier => $this->convertIdentifier($type),
            $type instanceof Name => $this->convertName($type),
            $type instanceof UnionType => $this->convertUnionType($type),
            $type instanceof NullableType => $this->convertNullableType($type),
            default => throw NodeTypeNotConvertable::create($type::class),
        };
    }

    private function convertNull(): string
    {
        return self::VOID_TYPE;
    }

    private function convertIdentifier(Identifier $identifier): string
    {
        return $identifier->toString();
    }

    private function convertName(Name $name): string
    {
        return $name->toString();
    }

    /**
     * @throws NodeTypeNotConvertable
     * @throws StringsException
     */
    private function convertUnionType(UnionType $unionType): string
    {
        $convertedTypes = array_map(fn(Identifier|Name $x) => $this->convert($x), $unionType->types);

        return join('|', $convertedTypes);
    }

    /**
     * @throws NodeTypeNotConvertable
     * @throws StringsException
     */
    private function convertNullableType(NullableType $nullableType): string
    {
        return '?' . $this->convert($nullableType->type);
    }
}