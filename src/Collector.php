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

    public function fetchData(string $url): array
    {
        $ch = curl_init($url);

        if ($ch === false) {
            $this->logger->error("Не удалось инициализировать cURL", ['url' => $url]);
            return [];
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36',
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
            ],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_FAILONERROR => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        $this->logger->info("Запрос к API", [
            'url' => $url,
            'http_code' => $httpCode,
            'response_preview' => substr($response ?? '', 0, 200),
            'curl_error' => $error
        ]);

        if ($error) {
            $this->logger->error("cURL ошибка", ['url' => $url, 'error' => $error]);
            return [];
        }

        if ($httpCode !== 200) {
            $this->logger->error("HTTP ошибка", [
                'url' => $url,
                'http_code' => $httpCode,
                'full_response' => $response
            ]);
            return [];
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->error("Ошибка парсинга JSON", [
                'url' => $url,
                'json_error' => json_last_error_msg(),
                'response' => $response
            ]);
            return [];
        }

        return $data;
    }
   
}