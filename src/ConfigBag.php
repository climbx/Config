<?php

namespace Climbx\Config;

use Climbx\Bag\Bag;
use Climbx\Config\Exception\ConfigurationException;

class ConfigBag extends Bag
{
    /**
     * @param $item
     *
     * @return array|bool|int|object|string|null
     */
    public function require($item): array | bool | int | object | string | null
    {
        if (!$this->has($item)) {
            throw new ConfigurationException(sprintf('The parameter "%s" was not found.', $item));
        }

        return $this->get($item);
    }
}
