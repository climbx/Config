<?php

namespace Climbx\Config\Loader;

use Climbx\Config\Parser\EnvVarParser;
use Climbx\Filesystem\FileHelper;

abstract class Loader implements LoaderInterface
{
    public const TYPE_JSON = 'json';
    public const TYPE_YAML = 'yaml';

    /**
     * @param string       $configDir
     * @param FileHelper   $fileHelper
     * @param EnvVarParser $envVarParser
     */
    public function __construct(
        private string $configDir,
        protected FileHelper $fileHelper,
        protected EnvVarParser $envVarParser,
    ) {
    }

    /**
     * @return string
     */
    protected function getConfigDir(): string
    {
        return $this->configDir;
    }
}
