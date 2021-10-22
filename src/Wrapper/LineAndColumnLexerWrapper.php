<?php

declare(strict_types=1);

namespace LeoVie\PhpMethodsParser\Wrapper;

use PhpParser\Lexer;

class LineAndColumnLexerWrapper
{
    public function __construct(private Lexer $lexer)
    {
    }

    public function getLexer(): Lexer
    {
        return $this->lexer;
    }
}