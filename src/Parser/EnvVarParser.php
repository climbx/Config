<?php

namespace Climbx\Config\Parser;

use Climbx\Bag\BagInterface;
use Climbx\Config\Exception\MissingEnvParameterException;

class EnvVarParser
{
    /**
     * @var string
     */
    private string $path;

    /**
     * @param BagInterface $env
     */
    public function __construct(
        private BagInterface $env
    ) {
    }

    /**
     * @param string $path
     * @param string $configFile
     *
     * @return string
     */
    public function getParsedData(string $path, string $configFile): string
    {
        $this->path = $path;

        return preg_replace_callback(
            '#\$env\(([a-zA-Z]+(_?[a-zA-Z0-9]+)*)\)#',
            function ($matches) {
                return $this->requireEnvVar($matches[1]);
            },
            $configFile
        );
    }

    /**
     * @param string $var
     *
     * @return string
     *
     * @throws MissingEnvParameterException
     */
    private function requireEnvVar(string $var): string
    {
        $value = $this->env->get($var);

        if ($value !== false) {
            return $value;
        }

        throw new MissingEnvParameterException(sprintf(
            'A reference to "%s" .env parameter has been added to "%s" config file and is missing in .env file',
            $var,
            $this->path,
        ));
    }
}
