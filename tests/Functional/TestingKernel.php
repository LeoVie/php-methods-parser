<?php

declare(strict_types=1);

namespace LeoVie\PhpMethodsParser\Tests\Functional;

use LeoVie\PhpFilesystem\PhpFilesystemBundle;
use LeoVie\PhpMethodsParser\PhpMethodsParserBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestingKernel extends Kernel
{
    public function registerBundles(): array
    {
        return [
            new PhpMethodsParserBundle(),
            new PhpFilesystemBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }
}