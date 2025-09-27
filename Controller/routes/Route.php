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

        $logger = new Logger(); // или передай извне (лучше через DI, но для простоты так)
        $logger->info("Request received", [
            'method' => $method,
            'uri' => $uri
        ]);

        $possibleRouttes = self::$routes[$method] ?? [];

        foreach ($possibleRouttes as $pattern => $handler) {
            $params = self::matchRoute($pattern, $uri);
            if ($params !== false) {
                $logger->info("Route matched", [
                    'pattern' => $pattern,
                    'handler' => is_string($handler) ? $handler : 'Closure',
                    'params' => $params
                ]);
                if (is_callable($handler)) {
                    call_user_func_array($handler, $params);
                } elseif (is_string($handler) && strpos($handler, '@') !== false) {
                    [$class, $method] = explode('@', $handler);
                    $controller = new $class();
                    call_user_func_array([$controller, $method], $params);
                }
                return;
            }
        }

        $logger->warning("Route not found", [
            'method' => $method,
            'uri' => $uri
        ]);
        echo "404 --- Страница не найдена";
    }

    private static function matchRoute (string $pattern, string $uri): bool|array
    {
        // Шаг 1: Экранируем специальные символы в шаблоне, кроме {параметров}
        $regex = preg_quote($pattern, '/');

        // Шаг 2: Найдём все имена параметров: {id}, {name} и т.д.
        if (!preg_match_all('/\{([^\/\}]+)\}/', $pattern, $matches)) {
            // Нет параметров — просто сравниваем как есть
            return $pattern === $uri ? [] : false;
        }

        $paramNames = $matches[1]; // ['id', 'slug', ...]

        // Шаг 3: Заменяем {имя} на регулярное выражение ([^/]+)
        $regex = preg_replace('/\\\{[^\/\}]+\\\}/', '([^/]+)', $regex);

        // Шаг 4: Делаем полное совпадение (от начала до конца)
        $regex = '/^' . $regex . '$/';

        // Шаг 5: Проверяем, совпадает ли URI с шаблоном
        if (!preg_match($regex, $uri, $matches)) {
            return false; // не совпало
        }

        // Шаг 6: Убираем всё совпадение целиком (первый элемент)
        array_shift($matches);

        // Шаг 7: Собираем ассоциативный массив: имя → значение
        if (count($matches) !== count($paramNames)) {
            return false; // на всякий случай (должно совпадать)
        }

        return array_combine($paramNames, $matches);
    }
}