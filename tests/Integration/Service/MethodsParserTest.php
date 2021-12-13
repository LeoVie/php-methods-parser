<?php

namespace LeoVie\PhpMethodsParser\Tests\Integration\Service;

use LeoVie\PhpMethodsParser\Service\MethodsParser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MethodsParserTest extends KernelTestCase
{
    private const TESTDATA_DIR = __DIR__ . '/../../testdata';

    /**
     * @dataProvider extractMethodsProvider
     *
     * @group now
     */
    public function testExtractMethods(string $filepath, int $expectedCount): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        /** @var MethodsParser $methodsParser */
        $methodsParser = $container->get(MethodsParser::class);

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