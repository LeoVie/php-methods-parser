<?php

declare(strict_types=1);

namespace LeoVie\PhpMethodsParser\Extractor;

use LeoVie\PhpMethodsParser\Converter\NodeTypeToStringConverter;
use LeoVie\PhpMethodsParser\Exception\NodeTypeNotConvertable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Safe\Exceptions\StringsException;

class ReturnTypeExtractor
{
    public function __construct(private NodeTypeToStringConverter $nodeTypeToStringConverter)
    {
    }

    /**
     * @throws StringsException
     * @throws NodeTypeNotConvertable
     */
    public function extract(ClassMethod|Function_ $method): string
    {
        return $this->nodeTypeToStringConverter->convert($method->getReturnType());
    }
}