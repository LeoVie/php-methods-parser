<?php

declare(strict_types=1);

namespace LeoVie\PhpMethodsParser\Tests\Unit\NodeVisitor;

use LeoVie\PhpMethodsParser\NodeVisitor\ExtractMethodsNodeVisitor;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPUnit\Framework\TestCase;

class ExtractMethodsNodeVisitorTest extends TestCase
{
    /**
     * @param ClassMethod[] $expected
     * @param Node[] $nodes
     *
     * @dataProvider enterNodeAndGetMethodsProvider
     */
    public function testEnterNodeAndGetMethods(array $expected, array $nodes): void
    {
        $extractClassMethodsNodeVisitor = new ExtractMethodsNodeVisitor();

        foreach ($nodes as $node) {
            $extractClassMethodsNodeVisitor->enterNode($node);
        }

        self::assertSame($expected, $extractClassMethodsNodeVisitor->getMethods());
    }

    public function enterNodeAndGetMethodsProvider(): \Generator
    {
        yield 'no nodes' => [
            'expected' => [],
            'nodes' => [],
        ];

        yield 'no ClassMethods' => [
            'expected' => [],
            'nodes' => [
                $this->mockNode(),
                $this->mockNode(),
            ],
        ];

        $nodes = [
            $this->mockClassMethod(),
            $this->mockClassMethod(),
        ];
        yield 'only ClassMethods' => [
            'expected' => $nodes,
            'nodes' => $nodes,
        ];

        $nodes = [
            $this->mockClassMethod(),
            $this->mockNode(),
            $this->mockFunction(),
        ];
        yield 'mixed Nodes' => [
            'expected' => [$nodes[0], $nodes[2]],
            'nodes' => $nodes,
        ];

        $nodes = [
            $this->mockInterfaceClassMethod(),
        ];
        yield 'interface class method' => [
            'expected' => [],
            'nodes' => $nodes,
        ];
    }

    public function testReset(): void
    {
        $extractClassMethodsNodeVisitor = new ExtractMethodsNodeVisitor();

        $nodes = [
            $this->mockClassMethod(),
            $this->mockClassMethod(),
        ];
        foreach ($nodes as $node) {
            $extractClassMethodsNodeVisitor->enterNode($node);
        }

        $extractClassMethodsNodeVisitor = $extractClassMethodsNodeVisitor->reset();

        self::assertSame([], $extractClassMethodsNodeVisitor->getMethods());
    }

    private function mockNode(): mixed
    {
        return $this->createMock(Node::class);
    }

    private function mockClassMethod(): ClassMethod
    {
        $classMethod = $this->createMock(ClassMethod::class);
        $classMethod->method('getStmts')->willReturn([$this->mockStmt()]);

        return $classMethod;
    }

    private function mockStmt(): Node\Stmt
    {
        return $this->createMock(Node\Stmt::class);
    }

    private function mockFunction(): Function_
    {
        $function = $this->createMock(Function_::class);
        $function->method('getStmts')->willReturn([$this->mockStmt()]);

        return $function;
    }

    private function mockInterfaceClassMethod(): ClassMethod
    {
        $classMethod = $this->createMock(ClassMethod::class);
        $classMethod->method('getStmts')->willReturn(null);

        return $classMethod;
    }
}