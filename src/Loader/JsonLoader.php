<?php

namespace Climbx\Config\Loader;

use Climbx\Config\Exception\ConfigurationParserException;

class JsonLoader extends Loader
{
    private const FILE_EXT = '.json';

    /**
     * @inheritDoc
     */
    public function load(string $path): array | false
    {
        $filename = $this->getConfigDir() . $path . self::FILE_EXT;

        if (!$this->fileHelper->isReadable($filename)) {
            return false;
        }

        $rawData = $this->fileHelper->getContentAsString($filename);
        $envParsedData = $this->envVarParser->getParsedData($rawData);
        $data = json_decode($envParsedData, true);

        if (null === $data) {
            throw new ConfigurationParserException(
                sprintf('The configuration file "%s" is not valid json.', $path)
            );
        }

        return $data;
    }
}
