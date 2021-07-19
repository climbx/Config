<?php

namespace Climbx\Config\Tests;

use Climbx\Config\Bag\ConfigBag;
use Climbx\Config\ConfigContainer;
use Climbx\Config\Exception\NotFoundException;
use Climbx\Config\Loader\JsonLoader;
use Climbx\Config\Reader\Reader;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Config\ConfigContainer
 * @covers \Climbx\Config\Bag\ConfigBag
 */
class ConfigContainerTest extends TestCase
{
    public function testGetConfig()
    {
        $reader = $this->createStub(Reader::class);
        $reader->method('read')->willReturn(['FOO' => 'BAR']);

        $container = new ConfigContainer($reader);

        $config = $container->get('config');

        $this->assertInstanceOf(ConfigBag::class, $config);
        $this->assertSame(['FOO' => 'BAR'], $config->getAll());

        $alreadyLoadedConfig = $container->get('config');
        $this->assertSame($config, $alreadyLoadedConfig);
    }

    public function testGetMissingConfig()
    {
        $reader = $this->createStub(Reader::class);
        $reader->method('read')->willReturn(false);

        $container = new ConfigContainer($reader);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The configuration file "config" is missing');

        $container->get('config');
    }

    public function testGetEmptyConfig()
    {
        $reader = $this->createStub(Reader::class);
        $reader->method('read')->willReturn([]);

        $container = new ConfigContainer($reader);

        $config = $container->get('config');

        $this->assertInstanceOf(ConfigBag::class, $config);
        $this->assertSame([], $config->getAll());
    }

    public function testHasConfig()
    {
        $reader = $this->createStub(Reader::class);
        $reader->method('isReadable')->willReturnMap([['existingConfig', true], ['missingConfig', false]]);

        $container = new ConfigContainer($reader);

        $this->assertTrue($container->has('existingConfig'));
        $this->assertFalse($container->has('missingConfig'));
    }
}
