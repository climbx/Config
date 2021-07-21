<?php

namespace Climbx\Config\Bag;

use Climbx\Bag\Bag;

class ConfigBag extends Bag implements ConfigBagInterface
{
    /**
     * @var string ConfigBag name
     */
    private string $name;

    /**
     * @param string $name
     * @param array  $data
     */
    public function __construct(string $name, array $data = [])
    {
        $this->name = $name;
        parent::__construct($data);
    }

    /**
     * @inheritDoc
     */
    public function get($item, $errorMessage = null): int | string | object | array | bool | null
    {
        return parent::get(
            $item,
            sprintf('The parameter "{item}" is missing in "%s" configuration file.', $this->getName())
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
