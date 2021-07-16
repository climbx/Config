<?php

namespace Climbx\Config\Loader;

use Climbx\Filesystem\FileHelper;

abstract class Loader implements LoaderInterface
{
    public const TYPE_JSON = 'json';
    public const TYPE_YAML = 'yaml';

    /**
     * @param string       $configDir
     * @param FileHelper   $fileHelper
     */
    public function __construct(
        private string $configDir,
        protected FileHelper $fileHelper,
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
