<?php

require_once __DIR__ . '/src/Logger.php';

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

        $filePath = __DIR__ . '/View/index.html';

        if (file_exists($filePath)) {
            $this->logger->info('Отдаю HTML-страницу', ['file' => $filePath]);
            readfile($filePath);
            return true;
        } else {
            $this->logger->error('Файл не найден', ['file' => $filePath]);
            http_response_code(404);
            echo "Страница не найдена";
            return false;
        }
    }
}