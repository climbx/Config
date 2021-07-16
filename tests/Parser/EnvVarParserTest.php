<?php

namespace Climbx\Config\Tests\Parser;

use Climbx\Bag\Bag;
use Climbx\Config\Exception\EnvParameterNotFoundException;
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

        $parser->getParsedData('myConfigFile', ['LEVEL1' => ['LEVEL2' => ['LEVEL3' => '$env(FOO)']]]);
    }

    /**
     * @dataProvider stubMapProvider
     */
    public function testGetParsedDataz(
        array $envStubGetMap,
        array $data,
        array $expectedResult
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
            // Simple .env reference
            [[['BAR', 'BAZ']], ['FOO' => '$env(BAR)'], ['FOO' => 'BAZ']],
            // Expression do not match $env(VAR) pattern.
            [[['BAR', 'BAZ']], ['FOO' => 'env(BAR)'], ['FOO' => 'env(BAR)']],
            // Multidimensional array with multiple .env references.
            [
                [ // Bag Map
                    ['BAR1', 'BAZ1'],
                    ['BAR2', 'BAZ2']
                ],
                [ // Raw data to parse
                    'FOO' => [
                        'BAR' => '$env(BAR1)'
                    ],
                    'LEVEL1' => [
                        'LEVEL2' => [
                            'LEVEL3' => '$env(BAR2)'
                        ]
                    ]
                ],
                [ // Expected result
                    'FOO' => [
                        'BAR' => 'BAZ1'
                    ],
                    'LEVEL1' => [
                        'LEVEL2' => [
                            'LEVEL3' => 'BAZ2'
                        ]
                    ]
                ],
            ],
        ];
    }

    public function testGetParsedDataException()
    {
        $envStub = $this->createStub(Bag::class);
        $envStub->method('get')->willReturn(false);

        $parser = new EnvVarParser($envStub);

        $this->expectException(EnvParameterNotFoundException::class);
        $this->expectExceptionMessage(
            'A reference to "BAR" .env parameter has been added to "myConfig" config file and is missing in .env file'
        );

        $parser->getParsedData('myConfig', ['FOO' => '$env(BAR)']);
    }
}
