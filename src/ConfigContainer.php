<?php

namespace Climbx\Config;

use Climbx\Config\Bag\ConfigBag;
use Climbx\Config\Exception\NotFoundException;
use Climbx\Config\Reader\ReaderInterface;

class ConfigContainer implements ConfigContainerInterface
{
    private array $container = [];

    /**
     * @param ReaderInterface $reader
     */
    public function __construct(
        private ReaderInterface $reader
    ) {
    }

    public function get(string $id): ConfigBag
    {
        if (array_key_exists($id, $this->container)) {
            return $this->container[$id];
        }

        $config = $this->reader->read($id);

        if ($config === false) {
            throw new NotFoundException(
                sprintf('The configuration file "%s" is missing', $id)
            );
        }

        $this->container[$id] = new ConfigBag($id, $config);

        return $this->container[$id];
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return $this->reader->isReadable($id);
    }
}
