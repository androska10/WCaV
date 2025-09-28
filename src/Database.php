<?php

require_once BASE_PATH . '/src/Logger.php';

class Database 
{
    private $logger;
    private \PDO $pdo;

    public function __construct()
    {
        $this->logger = new Logger();

        $host = $_ENV['MYSQLHOST'] ?? '127.0.0.1';
        $port = $_ENV['MYSQLPORT'] ?? '3306';
        $dbname = $_ENV['MYSQLDATABASE'] ?? 'railway';
        $user = $_ENV['MYSQLUSER'] ?? 'root';
        $password = $_ENV['MYSQLPASSWORD'] ?? 'password';

        try
        {
            $this->pdo = new \PDO(
                "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
                $user,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );

            $this->logger->info('Подключение к MySQL: Успех!');
        
        } catch (PDOException $e)
        {
            $this->logger->error('Ошибка подключения к MySQL: ' . $e->getMessage());
            throw new \Exception('Не удалось подключиться к базе данных', 0, $e);
        }
        
    }

    public function saveKlines (array $data): void
    {

    }
}