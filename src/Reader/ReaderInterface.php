<?php

namespace Climbx\Config\Reader;

use Climbx\Config\Exception\ContainerExceptionInterface;

interface ReaderInterface
{
    /**
     * Returns an array of data from a parsed configuration file.
     *
     * If the configuration file is missing, the method returns false.
     * If an error occurs while reading, a ContainerExceptionInterface exception is thrown.
     *
     * @param string $id
     *
     * @return array|false
     *
     * @throws ContainerExceptionInterface
     */
    public function read(string $id): array | false;

    /**
     * Checks if the configuration file exists and is readable.
     *
     * @param string $id
     *
     * @return bool
     */
    public function isReadable(string $id): bool;
}
