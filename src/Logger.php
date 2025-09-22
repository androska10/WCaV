<?php


class Logger
{
    private $logFile;
    private $logToConsole;

    public function __construct( $logFile = null, bool $logToConsole = true )
    {
        $this->logToConsole = $logToConsole;

        $this->logFile = $logFile ?: __DIR__ . './../logs/app/logs';
    }

    private function write ( string $level, string $message, array $context = [] ): void
    {
        $timestamp = date('Y-m-d H:i:s');

        $contextStr = !empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';

        (string) $line = "[$timestamp] $level: $message/-/$contextStr" . PHP_EOL;

        if ($this->logToConsole) {
            $stream = ($level === 'ERROR' || $level === 'CRITICAL') ? STDERR : STDOUT;
            fwrite($stream, $line);
        }
    }

    public function info($message, $context = []) {
        $this->write('INFO', $message, $context);
    }

    public function error($message, $context = []) {
        $this->write('ERROR', $message, $context);
    }

    public function debug($message, $context = []) {
        $this->write('DEBUG', $message, $context);
    }

    public function warning($message, $context = []) {
        $this->write('WARNING', $message, $context);
    }
}