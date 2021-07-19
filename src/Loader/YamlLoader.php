<?php

namespace Climbx\Config\Loader;

use Climbx\Config\Exception\ConfigurationParserException;

class YamlLoader extends Loader implements LoaderInterface
{
    private const FILE_EXT_SHORT = '.yml';
    private const FILE_EXT_LONG = '.yaml';

    public function load(string $id): array | false
    {
        foreach ($this->getFilenames($id) as $filename) {
            $data = $this->loadData($filename, $id);

            if (is_array($data)) {
                return $data;
            }
        }

        return false;
    }

    public function isReadable(string $id): bool
    {
        foreach ($this->getFilenames($id) as $filename) {
            if ($this->fileHelper->isReadable($filename)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $filename
     * @param string $id
     *
     * @return array|false
     *
     * @throws ConfigurationParserException
     */
    private function loadData(string $filename, string $id): array | false
    {
        if (!$this->fileHelper->isReadable($filename)) {
            return false;
        }

        $rawData = $this->fileHelper->getContentAsString($filename);

        try {
            $data = yaml_parse($rawData);

            return (null === $data) ? [] : (array) $data;
        } catch (\Throwable) {
            throw new ConfigurationParserException(
                sprintf('The configuration file "%s" is not valid yaml.', $id)
            );
        }
    }

    /**
     * @param string $id
     *
     * @return string[]
     */
    private function getFilenames(string $id): array
    {
        $prefix = $this->getConfigDir() . $id;

        return [
            $prefix . self::FILE_EXT_SHORT,
            $prefix . self::FILE_EXT_LONG,
        ];
    }
}
