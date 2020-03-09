<?php

namespace Framework\Parser;

class YAMLParser
{
    /**
     * Path to config folder
     * @var string
     */
    private $configPath = null;

    /**
     * YAMLParser constructor.
     */
    public function __construct()
    {
        $this->configPath = dirname(__DIR__, 2) . '/config/';
    }

//    /**
//     * For debug only
//     */
//    public function getConfigPath()
//    {
//        return self::$configPath;
//    }

    /**
     * Read a yaml file and return its content (YAML file MUST be in /src/config folder)
     * If file does not exist, return empty array
     * @param string $fileName
     * @return array
     */
    public function getContent($fileName): array
    {
        if ($this->hasFile($fileName)) return \yaml_parse_file($this->configPath . $fileName . '.yaml');

        return [];
    }

    /**
     * @param $fileName
     * @throws \InvalidArgumentException
     * @return bool
     */
    private function hasFile($fileName)
    {
        if (!is_string($fileName)) throw new \InvalidArgumentException('yaml file path must be string, ' . gettype($fileName) . ' given');

        if (file_exists($this->configPath . $fileName . '.yaml')) return true;

        return false;
    }

}