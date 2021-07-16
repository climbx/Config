<?php

namespace Climbx\Config\Parser;

use Climbx\Config\Exception\ContainerExceptionInterface;

interface EnvVarParserInterface
{
    /**
     * parses config data and replaces .env references by its value.
     *
     * If a reference is not found in .env, a ContainerExceptionInterface exception is thrown.
     *
     * @param string $id
     * @param array  $data
     *
     * @return array
     *
     * @throws ContainerExceptionInterface
     */
    public function getParsedData(string $id, array $data): array;
}
