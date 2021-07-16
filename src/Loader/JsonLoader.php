<?php

namespace Climbx\Config\Loader;

use Climbx\Config\Exception\ConfigurationParserException;

class JsonLoader extends Loader
{
    private const FILE_EXT = '.json';

    public function load(string $id): array | false
    {
        if (!$this->isReadable($id)) {
            return false;
        }

        $rawData = $this->fileHelper->getContentAsString($this->getFilename($id));
        $data = json_decode($rawData, true);

        if (null === $data) {
            throw new ConfigurationParserException(
                sprintf('The configuration file "%s" is not valid json.', $id)
            );
        }

        return $data;
    }

    public function isReadable(string $id): bool
    {
        return $this->fileHelper->isReadable($this->getFilename($id));
    }

    /**
     * @param string $id
     *
     * @return string
     */
    private function getFilename(string $id): string
    {
        return $this->getConfigDir() . $id . self::FILE_EXT;
    }
}
