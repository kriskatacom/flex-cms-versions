<?php

namespace Flex\Core\Controllers;

use Flex\Core\Routing\View;

abstract class BaseController
{
    public function callAction(string $method, array $parameters = [])
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $parameters);
        }

        throw new \Exception("Методът {$method} не съществува в " . get_class($this));
    }

    public function render(View $view): void
    {
        extract($view->data);

        $reflection = new \ReflectionClass($this);
        $controllerDir = dirname($reflection->getFileName(), 2);

        $fullViewPath = dirname($controllerDir) . '/views/' . $view->path . '.php';

        if (strpos($controllerDir, 'app' . DIRECTORY_SEPARATOR . 'Controllers') !== false) {
            $fullViewPath = dirname($controllerDir, 2) . '/views/' . $view->path . '.php';
        }

        ob_start();
        if (file_exists($fullViewPath)) {
            include $fullViewPath;
        } else {
            echo "View file not found: " . htmlspecialchars($fullViewPath);
        }
        $content = ob_get_clean();

        $layoutPath = dirname(__DIR__, 2) . "/views/layouts/{$view->layout}.php";

        if (file_exists($layoutPath)) {
            include $layoutPath;
        } else {
            echo $content;
        }
    }

    protected function json(array $data, int $status = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}
