<?php

namespace Climbx\Config\Tests\Loader;

use Climbx\Config\Exception\ConfigurationParserException;
use Climbx\Config\Loader\JsonLoader;
use Climbx\Config\Parser\EnvVarParser;
use Climbx\Filesystem\FileHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Config\Loader\Loader
 * @covers \Climbx\Config\Loader\JsonLoader
 * @covers \Climbx\Config\Parser\EnvVarParser
 * @covers \Climbx\Filesystem\FileHelper
 */
class JsonLoaderTest extends TestCase
{
    public function testLoadMissingFile()
    {
        $configDir = '/path/to/config/dir/';
        $fileHelper = $this->createStub(FileHelper::class);
        $envVarParser = $this->createStub(EnvVarParser::class);

        $loader = new JsonLoader($configDir, $fileHelper, $envVarParser);

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

        $envVarParser = $this->createStub(EnvVarParser::class);

        $loader = new JsonLoader($configDir, $fileHelper, $envVarParser);

        $loader->load('myConfig');
    }

    public function testLoadDataWithInvalidJson()
    {
        $configDir = '/path/to/config/dir/';

        $fileAsString = "{\nFOO:BAR\n}";

        $fileHelper = $this->createStub(FileHelper::class);
        $fileHelper->method('isReadable')->willReturn(true);
        $fileHelper->method('getContentAsString')->willReturn($fileAsString);

        $envVarParser = $this->createStub(EnvVarParser::class);
        $envVarParser->method('getParsedData')->willReturn($fileAsString);

        $loader = new JsonLoader($configDir, $fileHelper, $envVarParser);

        $this->expectException(ConfigurationParserException::class);
        $this->expectExceptionMessage('The configuration file "myConfig" is not valid json.');
        $loader->load('myConfig');
    }

    public function testLoadData()
    {
        $configDir = '/path/to/config/dir/';

        $fileAsString = "{\n\t\"FOO\":\"BAR\",\n\t\"BAZ\":1234\n}";

        $fileHelper = $this->createStub(FileHelper::class);
        $fileHelper->method('isReadable')->willReturn(true);
        $fileHelper->method('getContentAsString')->willReturn($fileAsString);

        $envVarParser = $this->createStub(EnvVarParser::class);
        $envVarParser->method('getParsedData')->willReturn($fileAsString);

        $loader = new JsonLoader($configDir, $fileHelper, $envVarParser);
        $data = $loader->load('myConfig');

        $this->assertEquals(['FOO' => 'BAR', 'BAZ' => 1234], $data);
    }
}
