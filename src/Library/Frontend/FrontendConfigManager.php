<?php

namespace ReadShare\Library\Frontend;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FrontendConfigManager {
    /**
     * @var string
     */
    private $configPath;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ConfigCache
     */
    private $cache;

    public function __construct($configPath, $projectDir, ConfigCache $cache, ContainerInterface $container) {
        $this->configPath = $projectDir . $configPath;
        $this->container = $container;
        $this->cache = $cache;
    }

    public function getConfig() {
        $config = null;
        if ($this->cache->isFresh()) {
            $config = unserialize(file_get_contents($this->cache->getPath()));
        } else {
            $configContent = file_get_contents($this->configPath);
            $config = $this->compileNode(json_decode($configContent, true));

            $this->cache->write(serialize($config));
        }

        return $config;
    }

    /**
     * @param $configNode array|string|int|null
     * @return array|string|int|null
     */
    private function compileNode($configNode) {
        if (is_array($configNode)) {
            foreach ($configNode as $key => $childNode) {
                if (is_string($childNode)) {
                    $configNode[$key] = $this->parseValue($childNode);
                } else if (is_array($childNode)) {
                    $configNode[$key] = $this->compileNode($childNode);
                }
            }
        }

        return $this->parseValue($configNode);
    }

    private function parseValue($configValue) {
        if (!is_string($configValue))
            return $configValue;

        if (preg_match('/^%([\w\.]+)%$/', $configValue, $match)) {
            $parametersStr = $match[1];
            $parameters = explode('.', $parametersStr);

            $value = $this->container->getParameter($parameters[0]);
            unset($parameters[0]);

            foreach ($parameters as $parameter)
                $value = $value[$parameter];

            return $value;
        } else if (preg_match('/^#(\w+)#$/', $configValue, $match)) {
            $parameters = $match[1];

            return getenv($parameters) ?? $parameters;
        } else return $configValue;
    }
}
