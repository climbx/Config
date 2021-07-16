<?php

namespace Climbx\Config;

use Climbx\Config\Bag\ConfigBag;
use Climbx\Config\Exception\ContainerExceptionInterface;
use Climbx\Config\Exception\NotFoundExceptionInterface;
use Psr\Container\ContainerInterface;

interface ConfigContainerInterface extends ContainerInterface
{
    /**
     * Returns a config bag from its id.
     *
     * If the config file is not found, a NotFoundExceptionInterface exception is thrown.
     * If a problem occurs while loading, a ContainerExceptionInterface exception is thrown.
     *
     * @param string $id
     *
     * @return ConfigBag
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function get(string $id): ConfigBag;
}
