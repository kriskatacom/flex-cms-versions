<?php

namespace Flex\Core\Plugins;

use Flex\Core\Events\EventManager;
use Flex\Core\Routing\Router;

class PluginManager
{
    protected $events;
    protected $pluginsPath;
    protected $activePlugins = [];

    public function __construct(EventManager $events, array $activePlugins = [])
    {
        $this->events = $events;
        $this->activePlugins = $activePlugins;
        $this->pluginsPath = dirname(__DIR__, 3) . '/plugins';
    }

    public function loadPlugins(Router $router): void
    {
        $loader = require dirname(__DIR__, 3) . '/vendor/autoload.php';

        foreach ($this->activePlugins as $pluginDir) {
            $pluginPath = $this->pluginsPath . '/' . $pluginDir;
            $pluginFile = $pluginPath . '/plugin.php';

            if (file_exists($pluginFile)) {
                $namespacePart = str_replace(' ', '', ucwords(str_replace('-', ' ', $pluginDir)));
                $fullNamespace = "Plugins\\" . $namespacePart . "\\";

                $loader->addPsr4($fullNamespace, $pluginPath . '/src');

                $eventManager = $this->events;

                include_once $pluginFile;
            }
        }

        $this->events->trigger('plugins_loaded');
    }

    public function getAvailablePlugins(): array
    {
        if (!is_dir($this->pluginsPath)) {
            return [];
        }

        $plugins = array_diff(scandir($this->pluginsPath), ['..', '.']);

        return array_values(array_filter($plugins, function ($item) {
            return is_dir($this->pluginsPath . '/' . $item);
        }));
    }
}