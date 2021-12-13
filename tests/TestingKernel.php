<?php

namespace LeoVie\PhpMethodsParser\Tests;

use LeoVie\PhpFilesystem\PhpFilesystemBundle;
use LeoVie\PhpMethodsParser\PhpMethodsParserBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestingKernel extends Kernel
{
    public function registerBundles()
    {
        return [new PhpMethodsParserBundle(), new PhpFilesystemBundle()];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }
}