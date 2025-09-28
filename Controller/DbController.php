<?php
namespace Controller;

class DbController {
    public static function importDump() {
        $host = $_ENV['MYSQLHOST'] ?? '127.0.0.1';
        $user = $_ENV['MYSQLUSER'] ?? 'root';
        $pass = $_ENV['MYSQLPASSWORD'] ?? '';
        $db   = $_ENV['MYSQLDATABASE'] ?? 'trade';
        $port = $_ENV['MYSQLPORT'] ?? 3306;

        try {
            $pdo = new \PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]);

            $sql = file_get_contents(BASE_PATH . '/trade.sql');
            $pdo->exec($sql);

            echo "<h2>✅ Дамп успешно загружен!</h2>";
        } catch (\Exception $e) {
            echo "<h2>❌ Ошибка:</h2><pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
        }
    }
}