<?php

namespace Climbx\Config;

use Climbx\Config\Exception\ConfigurationParserException;
use Climbx\Config\Bag\ConfigBag;

interface ConfigContainerInterface
{
    /**
     * Returns a config bag from path.
     *
     * If the config file is not found, false is returned
     *
     * @param string $path
     *
     * @return ConfigBag|false
     */
    public function get(string $path): ConfigBag | false;

    /**
     * Returns a config bag from path.
     *
     * If the config file is not found, an exception is thrown.
     *
     * @param string $path
     *
     * @return ConfigBag
     *
     * @throws ConfigurationParserException
     */
    public function require(string $path): ConfigBag;
}
