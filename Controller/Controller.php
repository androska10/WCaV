<?php

require_once __DIR__ . '/../src/Logger.php';
require_once BASE_PATH . '/src/Collector.php';
require_once BASE_PATH . '/src/Database.php';

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
        
        $db = new Database();
        $data = $db->getKlines();

        $filePath = BASE_PATH . '/View/home.php';

        if (file_exists($filePath)) {
            include $filePath;
        } else {
            http_response_code(404);
            echo "View не найден: home.php";
        }
    }

    public function getKlines ()
    {
        $this->logger->info('Запрошены данные из API Binance');

        $collector = new Collector();
        $url = "https://api.binance.com/api/v3/uiKlines?symbol=BTCUSDT&interval=5m";
        $data = $collector->fetchData($url);

        $db = new Database();
        if ($db->saveKlines($data)) {
            echo "Данные успешно сохранены)";
            $this->logger->info('Данные Binace успешно сохранены');
        } else {
            echo "Данные НЕ сохранены(";
            $this->logger->warning('Ошибка сохранения данных с Binance');
        }
    }
}