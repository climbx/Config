<?php

namespace Climbx\Config\Tests\Loader;

use Climbx\Config\Exception\ConfigurationParserException;
use Climbx\Config\Loader\JsonLoader;
use Climbx\Filesystem\FileHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Config\Loader\Loader
 * @covers \Climbx\Config\Loader\JsonLoader
 * @covers \Climbx\Filesystem\FileHelper
 */
class JsonLoaderTest extends TestCase
{
    public function testLoadMissingFile()
    {
        $configDir = '/path/to/config/dir/';
        $fileHelper = $this->createStub(FileHelper::class);

        $loader = new JsonLoader($configDir, $fileHelper);

        $data = $loader->load('myConfig');

        $this->assertFalse($data);
    }

    public function testFileHelperPassingFilenameArg()
    {
        $configDir = '/path/to/config/dir/';

        $fileHelper = $this->createMock(FileHelper::class);
        $fileHelper->expects($this->once())
            ->method('isReadable')
            ->with(
                $this->equalTo('/path/to/config/dir/myConfig.json')
            );

        $loader = new JsonLoader($configDir, $fileHelper);

        $loader->load('myConfig');
    }

    public function testLoadDataWithInvalidJson()
    {
        $configDir = '/path/to/config/dir/';

        $fileAsString = "{\nFOO:BAR\n}";

        $fileHelper = $this->createStub(FileHelper::class);
        $fileHelper->method('isReadable')->willReturn(true);
        $fileHelper->method('getContentAsString')->willReturn($fileAsString);

        $loader = new JsonLoader($configDir, $fileHelper);

        $this->expectException(ConfigurationParserException::class);
        $this->expectExceptionMessage('The configuration file "myConfig" is not valid json.');
        $loader->load('myConfig');
    }

    public function testLoadEmptyData()
    {
        $configDir = '/path/to/config/dir/';

        $fileAsString = "{\n}";

        $fileHelper = $this->createStub(FileHelper::class);
        $fileHelper->method('isReadable')->willReturn(true);
        $fileHelper->method('getContentAsString')->willReturn($fileAsString);

        $loader = new JsonLoader($configDir, $fileHelper);
        $data = $loader->load('myConfig');

        $this->assertIsArray($data);
        $this->assertEquals([], $data);
    }

    public function testLoadData()
    {
        $configDir = '/path/to/config/dir/';

        $fileAsString = "{\n\t\"FOO\":\"BAR\",\n\t\"BAZ\":1234\n}";

        $fileHelper = $this->createStub(FileHelper::class);
        $fileHelper->method('isReadable')->willReturn(true);
        $fileHelper->method('getContentAsString')->willReturn($fileAsString);

        $loader = new JsonLoader($configDir, $fileHelper);
        $data = $loader->load('myConfig');

        $this->assertEquals(['FOO' => 'BAR', 'BAZ' => 1234], $data);
    }

    public function testIsReadable()
    {
        $configDir = '/path/to/config/dir/';

        $fileHelper = $this->createStub(FileHelper::class);
        $fileHelper->method('isReadable')->willReturnMap([
            ['/path/to/config/dir/readableConfig.json', true], ['/path/to/config/dir/missingConfig.json', false]
        ]);

        $loader = new JsonLoader($configDir, $fileHelper);

        $this->assertTrue($loader->isReadable('readableConfig'));
        $this->assertFalse($loader->isReadable('missingConfig'));
    }
}
