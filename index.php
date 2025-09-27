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

define('BASE_PATH', __DIR__);

require_once __DIR__ . '/src/Logger.php';
require_once __DIR__ . '/Controller/Controller.php';
require_once __DIR__ . '/Controller/routes/Route.php';

$logger = new Logger();
$logger->info("Приложение запущено",[
    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'CLI',
    'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI'
]);

Route::get('/', [Controller::class, 'index']);
Route::get('/hello', function () {
    echo "Привет от роутера!";
});


Route::dispatch();