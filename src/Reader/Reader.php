<?php

namespace Climbx\Config\Reader;

use Climbx\Config\Loader\LoaderInterface;
use Climbx\Config\Parser\EnvVarParserInterface;

class Reader implements ReaderInterface
{
    public function __construct(
        private LoaderInterface $loader,
        private EnvVarParserInterface $envVarParser,
    ) {
    }

    public function read(string $id): array | false
    {
        $rawData = $this->loader->load($id);

        if ($rawData === false) {
            return false;
        }

        return $this->envVarParser->getParsedData($id, $rawData);
    }

    public function isReadable(string $id): bool
    {
        return $this->loader->isReadable($id);
    }
}
