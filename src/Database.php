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

    public function saveKlines(array $klinesData): bool
    {
        if (empty($klinesData)) {
            $this->logger->warning('Нет данных для сохранения');
            return false;
        }

        $sql = "INSERT IGNORE INTO klines(
            open_time, open_price, high_price, low_price, close_price, volume,
            close_time, quote_asset_volume, number_of_trades,
            taker_buy_base_asset_volume, taker_buy_quote_asset_volume, ignore_field
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare($sql);
            $inserted = 0;

            foreach ($klinesData as $kline) {
                if (!is_array($kline) || count($kline) < 12) continue;
                $stmt->execute(array_slice($kline, 0, 12));
                $inserted += $stmt->rowCount();
            }

            $this->pdo->commit();
            $this->logger->info("Успешно сохранено новых записей: $inserted");
            return true;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->logger->error("Ошибка вставки: " . $e->getMessage());
            return false;
        }
    }

    public function getKlines(int $minCount = 100, int $maxCount = 500): array
    {
        $countSql = "SELECT COUNT(*) FROM klines";
        $count = (int) $this->pdo->query($countSql)->fetchColumn();

        if ($count < $minCount) {
            $this->logger->warning("Мало данных. Требуется минимум: $minCount, есть: $count");
            return [];
        }

        // Получаем последние $maxCount записей (самые свежие)
        $selectSql = "SELECT * FROM klines ORDER BY open_time DESC LIMIT :maxCount";
        $stmt = $this->pdo->prepare($selectSql);
        $stmt->bindValue(':maxCount', $maxCount, PDO::PARAM_INT);
        $stmt->execute();

        // Если нужен порядок от старых к новым — разверните массив
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_reverse($data); // теперь [старая, ..., новая]
    }
}