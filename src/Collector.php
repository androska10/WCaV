<?php
/**
 * Умеет делать Http запрос и возращает json
 */

require_once __DIR__ . '/Logger.php';

class Collector 
{
    private $logger;
    

    public function __construct()
    {
        $this->logger = new Logger();
    }

    public function fetchData (string $url): array
    {
        $data = [];
        
        
        $ch = curl_init($url);

        if ($ch === false) {
            $this->logger->error("Не удалось инициализировать cURL для URL: $url");
            return $data;
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            CURLOPT_HTTPHEADER => [
                'Accept: application/json, text/plain, */*',
                'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
                'Referer: https://www.lada.ru/',
                'Origin: https://www.lada.ru',
                'Sec-Fetch-Dest: empty',
                'Sec-Fetch-Mode: cors',
                'Sec-Fetch-Site: same-site',
            ],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_FAILONERROR => false
        ]);

        $response = curl_exec($ch);
        
        if ($response === false) {
            $error = curl_error($ch);
            $this->logger->error("cURL ошибка: $error при запросе к $url");
        } else {
            $data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->logger->error("Ошибка JSON: " . json_last_error_msg() . "в ответ: $response");
                $data = [];
            }
        }

        // Add filter for Data 

        curl_close($ch);

        return $data;
    }

   
}