<?php

namespace LeoVie\PhpMethodsParser\Tests\Functional\Service;

use LeoVie\PhpMethodsParser\Service\MethodsParser;
use LeoVie\PhpMethodsParser\Tests\Functional\TestingKernel;
use PHPUnit\Framework\TestCase;

class MethodsParserTest extends TestCase
{
    private const TESTDATA_DIR = __DIR__ . '/../../testdata';

    /**
     * @dataProvider extractMethodsProvider
     *
     * @group now
     */
    public function testExtractMethods(string $filepath, int $expectedCount): void
    {
        $kernel = new TestingKernel('test', true);
        $kernel->boot();
        $methodsParser = $kernel->getContainer()->get(MethodsParser::class);

        self::assertCount($expectedCount, $methodsParser->extractMethods($filepath));
    }

    public function extractMethodsProvider(): array
    {
        return [
            'class' => [
                'filepath' => self::TESTDATA_DIR . '/ThisIsAClass.php',
                'expected' => 1,
            ],
            'interface' => [
                'filepath' => self::TESTDATA_DIR . '/ThisIsAnInterface.php',
                'expected' => 0,
            ],
        ];
    }
}