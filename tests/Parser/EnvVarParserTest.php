<?php

namespace Climbx\Config\Tests\Parser;

use Climbx\Bag\Bag;
use Climbx\Config\Exception\MissingEnvParameterException;
use Climbx\Config\Parser\EnvVarParser;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Config\Parser\EnvVarParser
 */
class EnvVarParserTest extends TestCase
{
    public function testGetParsedDataPregMatch()
    {
        $bag = $this->createMock(Bag::class);

        $bag->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('FOO')
            )->willReturn('BAR');

        $parser = new EnvVarParser($bag);

        $parser->getParsedData('myConfigFile', '$env(FOO)');
    }

    /**
     * @dataProvider stubMapProvider
     */
    public function testGetParsedData(
        array $envStubGetMap,
        string $data,
        string $expectedResult
    ) {
        $envStub = $this->createStub(Bag::class);
        $envStub->method('get')->willReturnMap($envStubGetMap); // Env Bag get($item) method

        $parser = new EnvVarParser($envStub);

        $this->assertEquals($expectedResult, $parser->getParsedData('myConfigFile', $data));
    }

    /**
     * @return array[]
     */
    public function stubMapProvider(): array
    {
        return [
            [[['FOO', 'BAR']], '$env(FOO)', 'BAR'], // FOO key is found in Bag
            [[['FOO', 'BAR']], 'env(FOO)', 'env(FOO)'], // Expression do not match $env(VAR) pattern.
        ];
    }

    public function testGetParsedDataException()
    {
        $envStub = $this->createStub(Bag::class);
        $envStub->method('get')->willReturn(false);

        $parser = new EnvVarParser($envStub);

        $this->expectException(MissingEnvParameterException::class);
        $this->expectExceptionMessage(
            'A reference to "FOO" .env parameter has been added to "myConfig" config file and is missing in .env file'
        );

        $parser->getParsedData('myConfig', '$env(FOO)');
    }
}
