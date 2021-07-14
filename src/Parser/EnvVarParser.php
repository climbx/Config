<?php

namespace Climbx\Config\Parser;

use Climbx\Bag\BagInterface;

class EnvVarParser
{
    public function __construct(
        private BagInterface $env
    ) {
    }

    public function getParsedData(string $configFile): string
    {
        return preg_replace_callback(
            '#\$env\(([a-zA-Z]+(_?[a-zA-Z0-9]+)*)\)#',
            function ($matches) {
                if ($this->hasEnvValue($matches[1])) {
                    return $this->getEnvValue($matches[1]);
                }

                return $matches[0];
            },
            $configFile
        );
    }

    /**
     * @param string $var
     *
     * @return bool
     */
    private function hasEnvValue(string $var): bool
    {
        return $this->env->has($var);
    }

    /**
     * @param string $var
     *
     * @return string
     */
    private function getEnvValue(string $var): string
    {
        return $this->env->get($var);
    }
}
