webhook-collector/
├── src/
│   ├── Collector.php          # Логика cURL-запросов
│   ├── Validator.php          # Валидация данных
│   ├── Database.php           # Работа с PDO и MySQL
│   ├── Logger.php             # Простое логирование в файл
│   └── App.php                # Главный класс, управляющий процессом
├── tests/
│   ├── CollectorTest.php
│   ├── ValidatorTest.php
│   └── DatabaseTest.php       # (опционально, если есть время)
├── logs/
│   └── app.log                # Файл логов
├── vendor/                    # Composer dependencies (только PHPUnit)
├── composer.json
├── phpunit.xml                # Конфиг PHPUnit
└── index.php                  # Точка входа (CLI)
