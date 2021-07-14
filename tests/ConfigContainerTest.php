<?php

namespace Climbx\Config\Tests;

use Climbx\Config\Bag\ConfigBag;
use Climbx\Config\ConfigContainer;
use Climbx\Config\Exception\MissingConfigurationException;
use Climbx\Config\Loader\JsonLoader;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Config\ConfigContainer
 * @covers \Climbx\Config\Bag\ConfigBag
 */
class ConfigContainerTest extends TestCase
{
    public function testGetExistingConfig()
    {
        $loader = $this->createStub(JsonLoader::class);
        $loader->method('load')->willReturn(['FOO' => 'BAR']);

        $container = new ConfigContainer($loader);

        $config = $container->get('config');

        $this->assertInstanceOf(ConfigBag::class, $config);
        $this->assertSame(['FOO' => 'BAR'], $config->getAll());
    }

    public function testGetMissingConfig()
    {
        $loader = $this->createStub(JsonLoader::class);
        $loader->method('load')->willReturn(false);

        $container = new ConfigContainer($loader);

        $this->assertFalse( $container->get('config'));
    }

    public function testRequireConfig()
    {
        $loader = $this->createStub(JsonLoader::class);
        $loader->method('load')->willReturn(['FOO' => 'BAR']);

        $container = new ConfigContainer($loader);

        $config = $container->require('config');

        $this->assertInstanceOf(ConfigBag::class, $config);
        $this->assertSame(['FOO' => 'BAR'], $config->getAll());
    }

    public function testRequireMissingConfig()
    {
        $loader = $this->createStub(JsonLoader::class);
        $loader->method('load')->willReturn(false);

        $container = new ConfigContainer($loader);

        $this->expectException(MissingConfigurationException::class);
        $this->expectExceptionMessage('The configuration file "config" is missing');

        $container->require('config');
    }
}
