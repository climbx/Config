<?php

namespace Climbx\Config\Loader;

use Climbx\Config\Exception\ContainerExceptionInterface;

interface LoaderInterface
{
    /**
     * Returns an array of a parsed configuration file data.
     *
     * If an error occurs while parsing file, a ContainerExceptionInterface exception is thrown.
     *
     * @param string $id
     *
     * @return array|false
     *
     * @throws ContainerExceptionInterface
     */
    public function load(string $id): array | false;

    /**
     * Returns true if the config file is found and is readable, false otherwise.
     *
     * @param string $id
     *
     * @return bool
     */
    public function isReadable(string $id): bool;
}
