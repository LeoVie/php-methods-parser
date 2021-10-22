<?php

declare(strict_types=1);

namespace LeoVie\PhpMethodsParser\Service;

use LeoVie\PhpFilesystem\Service\Filesystem;
use LeoVie\PhpMethodsParser\NodeVisitor\ExtractMethodsNodeVisitor;
use LeoVie\PhpMethodsParser\Wrapper\LineAndColumnLexerWrapper;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use Safe\Exceptions\FilesystemException;

class MethodsParser
{
    public function __construct(
        private ParserFactory             $parserFactory,
        private NodeTraverser             $nodeTraverser,
        private ExtractMethodsNodeVisitor $extractMethodsNodeVisitor,
        private Filesystem                $filesystem,
        private LineAndColumnLexerWrapper $lineAndColumnLexerWrapper
    )
    {
    }

    /**
     * @return array<ClassMethod|Function_>
     * @throws FilesystemException
     */
    public function extractMethods(string $filepath): array
    {
        $this->extractMethodsNodeVisitor = $this->extractMethodsNodeVisitor->reset();

        $parser = $this->parserFactory->create(ParserFactory::PREFER_PHP7, $this->lineAndColumnLexerWrapper->getLexer());

        /** @var Node[] $ast */
        $ast = $parser->parse($this->filesystem->readFile($filepath));

        $this->nodeTraverser->addVisitor($this->extractMethodsNodeVisitor);
        $this->nodeTraverser->traverse($ast);

        return $this->extractMethodsNodeVisitor->getMethods();
    }
}