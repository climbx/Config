<?php

namespace Climbx\Config\Parser;

use Climbx\Bag\BagInterface;
use Climbx\Config\Exception\EnvParameterNotFoundException;

class EnvVarParser implements EnvVarParserInterface
{
    public function __construct(
        private BagInterface $env
    ) {
    }

    public function getParsedData(string $id, array $data): array
    {
        foreach ($data as $key => $value) {
            $data[$key] = (is_array($value)) ? $this->getParsedData($id, $value) : $this->parseData($id, $value);
        }

        return $data;
    }

    /**
     * @param string $id
     * @param string $value
     *
     * @return string
     */
    private function parseData(string $id, string $value): string
    {
        return preg_replace_callback(
            '#\$env\(([a-zA-Z]+(_?[a-zA-Z0-9]+)*)\)#',
            function ($matches) use ($id) {
                return $this->requireEnvVar($id, $matches[1]);
            },
            $value
        );
    }

    /**
     * @param string $id
     * @param string $var
     *
     * @return string
     *
     * @throws EnvParameterNotFoundException
     */
    private function requireEnvVar(string $id, string $var): string
    {
        $value = $this->env->get($var);

        if ($value !== false) {
            return $value;
        }

        throw new EnvParameterNotFoundException(sprintf(
            'A reference to "%s" .env parameter has been added to "%s" config file and is missing in .env file',
            $var,
            $id,
        ));
    }
}
