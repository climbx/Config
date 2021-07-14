<?php

namespace Climbx\Config\Loader;

use Climbx\Config\Exception\ConfigurationParserException;

class YamlLoader extends Loader implements LoaderInterface
{
    private const FILE_EXT = ['.yml', '.yaml'];

    /**
     * @inheritDoc
     */
    public function load(string $path): array | false
    {
        foreach (self::FILE_EXT as $fileExt) {
            $data = $this->loadData($this->getConfigDir() . $path . $fileExt, $path);

            if (is_array($data)) {
                return $data;
            }
        }

        return false;
    }

    /**
     * @param string $filename
     * @param string $path
     *
     * @return array|false
     *
     * @throws ConfigurationParserException
     */
    private function loadData(string $filename, string $path): array | false
    {
        if (!$this->fileHelper->isReadable($filename)) {
            return false;
        }

        $rawData = $this->fileHelper->getContentAsString($filename);
        $envParsedData = $this->envVarParser->getParsedData($rawData);

        try {
            $data = yaml_parse($envParsedData);

        } catch (\Throwable) {
            throw new ConfigurationParserException(
                sprintf('The configuration file "%s" is not valid yaml.', $path)
            );
        }

        return $data;
    }
}
