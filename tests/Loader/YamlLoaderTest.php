<?php

namespace Climbx\Config\Tests\Loader;

use Climbx\Config\Exception\ConfigurationParserException;
use Climbx\Config\Loader\Loader;
use Climbx\Config\Loader\YamlLoader;
use Climbx\Config\Parser\EnvVarParser;
use Climbx\Filesystem\FileHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Config\Loader\Loader
 * @covers \Climbx\Config\Loader\YamlLoader
 * @covers \Climbx\Filesystem\FileHelper
 */
class YamlLoaderTest extends TestCase
{
    public function testLoadMissingFile()
    {
        $configDir = '/path/to/config/dir/';
        $fileHelper = $this->createStub(FileHelper::class);

        $loader = new YamlLoader($configDir, $fileHelper);

        $data = $loader->load('myConfig');

        $this->assertFalse($data);
    }

    public function testFileHelperPassingFilenameArg()
    {
        $configDir = '/path/to/config/dir/';

        $fileHelper = $this->createMock(FileHelper::class);
        $fileHelper->expects($this->exactly(2))
            ->method('isReadable')
            ->withConsecutive(
                [$this->equalTo('/path/to/config/dir/myConfig.yml')],
                [$this->equalTo('/path/to/config/dir/myConfig.yaml')],
            );

        $loader = new YamlLoader($configDir, $fileHelper);

        $loader->load('myConfig');
    }

    public function testLoadDataWithInvalidYaml()
    {
        $configDir = '/path/to/config/dir/';

        $fileAsString = "FOO:\n\tBAR=10\n";

        $fileHelper = $this->createStub(FileHelper::class);
        $fileHelper->method('isReadable')->willReturn(true);
        $fileHelper->method('getContentAsString')->willReturn($fileAsString);

        $loader = new YamlLoader($configDir, $fileHelper);

        $this->expectException(ConfigurationParserException::class);
        $this->expectExceptionMessage('The configuration file "myConfig" is not valid yaml.');
        $loader->load('myConfig');
    }

    public function testLoadEmptyData()
    {
        $configDir = '/path/to/config/dir/';

        $fileAsString = "";

        $fileHelper = $this->createStub(FileHelper::class);
        $fileHelper->method('isReadable')->willReturn(true);
        $fileHelper->method('getContentAsString')->willReturn($fileAsString);

        $loader = new YamlLoader($configDir, $fileHelper);
        $data = $loader->load('myConfig');

        $this->assertIsArray($data);
        $this->assertEquals([], $data);
    }

    public function testLoadData()
    {
        $configDir = '/path/to/config/dir/';

        $fileAsString = "FOO:\n  BAR: BAZ\n  BAZ: 1234\n";

        $fileHelper = $this->createStub(FileHelper::class);
        $fileHelper->method('isReadable')->willReturn(true);
        $fileHelper->method('getContentAsString')->willReturn($fileAsString);

        $loader = new YamlLoader($configDir, $fileHelper);
        $data = $loader->load('myConfig');

        $this->assertEquals(['FOO' => ['BAR' => 'BAZ', 'BAZ' => 1234]], $data);
    }

    public function testIsReadable()
    {
        $configDir = '/path/to/config/dir/';

        $fileHelper = $this->createStub(FileHelper::class);
        $fileHelper->method('isReadable')->willReturnMap([
            ['/path/to/config/dir/readableConfig.yml', false],
            ['/path/to/config/dir/readableConfig.yaml', true],
            ['/path/to/config/dir/missingConfig.yml', false],
            ['/path/to/config/dir/missingConfig.yaml', false]
        ]);

        $loader = new YamlLoader($configDir, $fileHelper);

        $this->assertTrue($loader->isReadable('readableConfig'));
        $this->assertFalse($loader->isReadable('missingConfig'));
    }
}
