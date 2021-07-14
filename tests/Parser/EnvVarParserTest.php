<?php

namespace Climbx\Config\Tests\Parser;

use Climbx\Bag\Bag;
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
            ->method('has')
            ->with(
                $this->equalTo('FOO')
            )->willReturn(true);

        $bag->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('FOO')
            )->willReturn('BAR');

        $parser = new EnvVarParser($bag);

        $parser->getParsedData('$env(FOO)');
    }

    /**
     * @dataProvider stubMapProvider
     */
    public function testGetParsedData(
        array $envStubHasMap,
        array $envStubGetMap,
        string $data,
        string $expectedResult
    ) {
        // EnvVarParser dependency: env vars Bag.
        $envStub = $this->createStub(Bag::class);
        $envStub->method('has')->willReturnMap($envStubHasMap); // Env Bag has($item) method
        $envStub->method('get')->willReturnMap($envStubGetMap); // Env Bag get($item) method

        $parser = new EnvVarParser($envStub);

        $this->assertEquals($expectedResult, $parser->getParsedData($data));
    }

    /**
     * @return array[]
     */
    public function stubMapProvider(): array
    {
        return [
            [[['FOO', true]], [['FOO', 'BAR']], '$env(FOO)', 'BAR'], // FOO key is found in Bag
            [[['FOO', false]], [], '$env(FOO)', '$env(FOO)'], // FOO key is not found in Bag
            [[['FOO', true]], [['FOO', 'BAR']], 'env(FOO)', 'env(FOO)'], // Expression do not match $env(VAR) pattern.
        ];
    }
}
