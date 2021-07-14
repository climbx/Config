<?php

namespace Climbx\Config;

use Climbx\Config\Bag\ConfigBag;
use Climbx\Config\Exception\MissingConfigurationException;
use Climbx\Config\Loader\LoaderInterface;

class ConfigContainer implements ConfigContainerInterface
{
    private array $container = [];

    /**
     * @param LoaderInterface $loader
     */
    public function __construct(
        private LoaderInterface $loader
    ) {
    }

    public function get(string $path): ConfigBag | false
    {
        if ($this->has($path)) {
            return $this->container[$path];
        }

        $config = $this->loader->load($path);

        if (!$config) {
            return false;
        }

        $this->container[$path] = new ConfigBag($path, $config);

        return $this->container[$path];
    }

    public function require(string $path): ConfigBag
    {
        if ($this->has($path)) {
            return $this->container[$path];
        }

        $config = $this->loader->load($path);

        if (!$config) {
            throw new MissingConfigurationException(
                sprintf('The configuration file "%s" is missing', $path)
            );
        }

        $this->container[$path] = new ConfigBag($path, $config);

        return $this->container[$path];
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    private function has(string $path): bool
    {
        return array_key_exists($path, $this->container);
    }
}
