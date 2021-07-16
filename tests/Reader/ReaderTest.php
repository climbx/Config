<?php

namespace Climbx\Config\Tests\Reader;

use Climbx\Config\Loader\JsonLoader;
use Climbx\Config\Loader\YamlLoader;
use Climbx\Config\Parser\EnvVarParser;
use Climbx\Config\Reader\Reader;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Config\Reader\Reader
 */
class ReaderTest extends TestCase
{
    public function testReadMissingConfig()
    {
        $loader = $this->createStub(YamlLoader::class);
        $loader->method('load')->willReturn(false);

        $parser = $this->createStub(EnvVarParser::class);

        $reader = new Reader($loader, $parser);

        $this->assertFalse($reader->read('missingConfig'));
    }

    public function testRead()
    {
        $loader = $this->createStub(JsonLoader::class);
        $loader->method('load')->willReturn(['FOO' => 'BAR']);

        $parser = $this->createMock(EnvVarParser::class);
        $parser->expects($this->once())
            ->method('getParsedData')
            ->with(
                $this->equalTo('myConfig'),
                $this->equalTo(['FOO' => 'BAR'])
            )->willReturn(['FOO' => 'BAR']);

        $reader = new Reader($loader, $parser);

        $this->assertEquals(['FOO' => 'BAR'], $reader->read('myConfig'));
    }

    public function testIsReadable()
    {
        $loader = $this->createStub(JsonLoader::class);
        $loader->method('isReadable')->willReturnMap([['readableConfig', true], ['missingConfig', false]]);

        $parser = $this->createStub(EnvVarParser::class);

        $reader = new Reader($loader, $parser);

        $this->assertTrue($reader->isReadable('readableConfig'));
        $this->assertFalse($reader->isReadable('missingConfig'));
    }
}
