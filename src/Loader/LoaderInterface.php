<?php

namespace Climbx\Config\Loader;

use Climbx\Config\Exception\ConfigurationParserException;

interface LoaderInterface
{
    /**
     * Returns an array of a parsed configuration file raw data.
     *
     * @param string $path
     *
     * @return array|false
     *
     * @throws ConfigurationParserException
     */
    public function load(string $path): array | false;
}
