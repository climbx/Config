<?php

namespace Climbx\Config\Tests\Bag;

use Climbx\Config\Bag\ConfigBag;
use Climbx\Bag\Exception\MissingItemException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Config\Bag\ConfigBag
 */
class ConfigBagTest extends TestCase
{
    public function testName()
    {
        $bag = new ConfigBag('lib/myConfig', ['FOO' => 'BAR']);

        $this->assertSame('lib/myConfig', $bag->getName());
    }

    public function testRequireException()
    {
        $bag = new ConfigBag('lib/myConfig');

        $this->expectException(MissingItemException::class);
        $this->expectExceptionMessage('The parameter "FOO" is missing in "lib/myConfig" configuration file.');

        $bag->require('FOO');
    }
}
