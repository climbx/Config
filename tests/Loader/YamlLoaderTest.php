<?php

namespace Climbx\Config\Tests\Loader;

use Climbx\Config\Exception\ConfigurationParserException;
use Climbx\Config\Loader\YamlLoader;
use Climbx\Config\Parser\EnvVarParser;
use Climbx\Filesystem\FileHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Config\Loader\YamlLoader
 * @covers \Climbx\Filesystem\FileHelper
 * @covers \Climbx\Config\Parser\EnvVarParser
 */
class YamlLoaderTest extends TestCase
{
    public function testLoadMissingFile()
    {
        $configDir = '/path/to/config/dir/';
        $fileHelper = $this->createStub(FileHelper::class);
        $envVarParser = $this->createStub(EnvVarParser::class);

        $loader = new YamlLoader($configDir, $fileHelper, $envVarParser);

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

        $envVarParser = $this->createStub(EnvVarParser::class);

        $loader = new YamlLoader($configDir, $fileHelper, $envVarParser);

        $loader->load('myConfig');
    }

    public function testLoadDataWithInvalidYaml()
    {
        $configDir = '/path/to/config/dir/';

        $fileAsString = "FOO:\n\tBAR=10\n";

        $fileHelper = $this->createStub(FileHelper::class);
        $fileHelper->method('isReadable')->willReturn(true);
        $fileHelper->method('getContentAsString')->willReturn($fileAsString);

        $envVarParser = $this->createStub(EnvVarParser::class);
        $envVarParser->method('getParsedData')->willReturn($fileAsString);

        $loader = new YamlLoader($configDir, $fileHelper, $envVarParser);

        $this->expectException(ConfigurationParserException::class);
        $this->expectExceptionMessage('The configuration file "myConfig" is not valid yaml.');
        $loader->load('myConfig');
    }

    public function testLoadData()
    {
        $configDir = '/path/to/config/dir/';

        $fileAsString = "FOO:\n  BAR: BAZ\n  BAZ: 1234\n";

        $fileHelper = $this->createStub(FileHelper::class);
        $fileHelper->method('isReadable')->willReturn(true);
        $fileHelper->method('getContentAsString')->willReturn($fileAsString);

        $envVarParser = $this->createStub(EnvVarParser::class);
        $envVarParser->method('getParsedData')->willReturn($fileAsString);

        $loader = new YamlLoader($configDir, $fileHelper, $envVarParser);
        $data = $loader->load('myConfig');

        $this->assertEquals(['FOO' => ['BAR' => 'BAZ', 'BAZ' => 1234]], $data);
    }
}
