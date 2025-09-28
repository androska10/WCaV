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

use Controller\DbController;

define('BASE_PATH', __DIR__);

require_once __DIR__ . '/src/Logger.php';
require_once __DIR__ . '/Controller/Controller.php';
require_once BASE_PATH . '/Controller/DbController.php';
require_once __DIR__ . '/Controller/routes/Route.php';
require_once BASE_PATH . '/src/Collector.php';

$logger = new Logger();
$logger->info("Приложение запущено",[
    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'CLI',
    'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI'
]);

Route::get('/', function () {
    echo "<h1>Привет от роутера!</h1>";
});
Route::get('/hello', [Controller::class, 'index']);
Route::get('/hello/binance', [Controller::class, 'getKlines']);

Route::get('/SQL_SELECT_TABLE', [DbController::class, 'importDump']);


Route::dispatch();