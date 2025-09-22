<?php
/**
 * Что должен делать index.php?
 * 
 * 1. Получить запрос
 * 2. Маршрутизация запросов
 * 3. Запустить нужный обработчик
 * 4. Вернуть ответ
 * 
 */

require_once __DIR__ . '/src/Logger.php';
require_once __DIR__ . '/Controller.php';

$logger = new Logger();

$logger->info("Приложение запущено",[
    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'CLI',
    'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI'
]);

$uri = $_SERVER["REQUEST_URI"] ?? '/';

if ($uri === '/' || $uri === '/index.php') {
    $controller = new Controller ();
    $controller->index();
} else {
    http_response_code(404);
    $logger->warning("Страница не найдена", ['uri' => $uri]);
    echo "<h1>404 - Страница не найдена</h1>";
}