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
        
        
        $ch = curl_init($url);//https://www.lada.ru/api-v1/os-cars/938141/city-id

        if ($ch === false) {
            $this->logger->error('');
            return $data;
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_USERAGENT => 'WCaV Collector/1.0 via cURL',
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json'
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