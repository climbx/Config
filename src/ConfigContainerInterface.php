<?php

namespace Climbx\Config;

use Climbx\Config\Exception\ConfigurationException;

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
     * @throws ConfigurationException
     */
    public function require(string $path): ConfigBag;
}