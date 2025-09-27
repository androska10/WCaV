<?php

require_once __DIR__ . '/../src/Logger.php';
require_once BASE_PATH . '/src/Collector.php';

class Controller
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger();
        $this->logger->info('Init Controller');
    }

    public function index()
    {
        $this->logger->info('Запрошена главная страница');

        $collector = new Collector();

        // $url = "https://www.lada.ru/api-v1/os-cars/938141/city-id";
        $url = "https://jsonplaceholder.typicode.com/posts/1";
        $data = $collector->fetchData($url);

        $filePath = BASE_PATH . '/View/home.php';

        if (file_exists($filePath)) {
            include $filePath;
        } else {
            http_response_code(404);
            echo "View не найден: home.php";
        }
    }
}