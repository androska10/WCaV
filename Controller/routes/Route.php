<?php

class Route
{
    protected static $routes = [];

    public static function get (string $uri, $handler): void
    {
        self::$routes['GET'][$uri] = $handler;
    }

    public static function post (string $uri, $handler): void
    {
        self::$routes['POST'][$uri] = $handler;
    }

    public static function dispatch() 
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $logger = new Logger();
        $logger->info("Request received", [
            'method' => $method,
            'uri' => $uri
        ]);

        $possibleRoutes = self::$routes[$method] ?? [];

        foreach ($possibleRoutes as $pattern => $handler) {
            $params = self::matchRoute($pattern, $uri);
            if ($params !== false) {
                
                if (is_string($handler)) {
                    $logHandler = $handler;
                } elseif (is_array($handler)) {
                    $logHandler = implode('::', $handler);
                } else {
                    $logHandler = 'Closure';
                }
                $logger->info("Route matched", [
                    'pattern' => $pattern,
                    'handler' => $logHandler,
                    'params' => $params
                ]);

                
                if (is_array($handler) && count($handler) === 2) {
                    [$class, $method] = $handler;
                    $controller = new $class();
                    call_user_func_array([$controller, $method], $params);
                } elseif (is_string($handler) && strpos($handler, '@') !== false) {
                    [$class, $method] = explode('@', $handler);
                    $controller = new $class();
                    call_user_func_array([$controller, $method], $params);
                } elseif (is_callable($handler)) {
                    call_user_func_array($handler, $params);
                }

                return;
            }
        }

        $logger->warning("Route not found", [
            'method' => $method,
            'uri' => $uri
        ]);
        http_response_code(404);
        echo "404 --- Страница не найдена";
    }

    private static function matchRoute (string $pattern, string $uri): bool|array
    {
        $regex = preg_quote($pattern, '/');

        if (!preg_match_all('/\{([^\/\}]+)\}/', $pattern, $matches)) {
            return $pattern === $uri ? [] : false;
        }

        $paramNames = $matches[1];

        $regex = preg_replace('/\\\{[^\/\}]+\\\}/', '([^/]+)', $regex);

        $regex = '/^' . $regex . '$/';

        if (!preg_match($regex, $uri, $matches)) {
            return false;
        }

        array_shift($matches);

        if (count($matches) !== count($paramNames)) {
            return false; 
        }

        return array_combine($paramNames, $matches);
    }
}