<?php declare(strict_types=1);

namespace App\Service;

class BenchmarkService
{
    const OUTPUT_FILE_DIR = __DIR__ . '/../../var/output/';

    private float $startTime;
    private float $totalExecutionTime;
    private int $operationsCount;
    private int $currentOperation;
    private $outputFileDescriptor;
    private int $totalLogRecords;


    public function start(
        string $name,
        int $operationsCount,
        int $totalLogRecords = 1000
    ): void {
        $this->currentOperation = 0;
        $this->operationsCount = $operationsCount;
        $this->totalLogRecords = $totalLogRecords;
        $this->outputFileDescriptor = fopen(self::OUTPUT_FILE_DIR . $name . '.txt', 'w');
        $this->startTime = microtime(true);
    }

    public function operationHasBeenMade(): void
    {
        if ($this->shouldBeLogged()) {
            $this->logOperation();
        }

        $this->currentOperation++;
    }

    public function finish(): void
    {
        $this->totalExecutionTime = microtime(true) - $this->startTime;
        fclose($this->outputFileDescriptor);
    }

    public function getTotalExecutionTime(): float
    {
        return $this->totalExecutionTime;
    }

    public function getTotalOperations(): int
    {
        return $this->currentOperation;
    }

    public function getOperationsCount(): int
    {
        return $this->operationsCount;
    }

    /**
     * total: 200
     * total log records: 10
     * 20, 40, 60, 80, 100, 120, 140, 160, 180, 200
     * 35: false
     * 40: true
     * floor(200 / 10) = 20
     * 35 % 20 != 0 -> false
     * 40 % 20 == 0 -> true
     */
    private function shouldBeLogged(): bool
    {
        if ($this->totalLogRecords >= $this->operationsCount) {
            return true;
        }

        return  $this->currentOperation % round($this->operationsCount / $this->totalLogRecords) == 0;
    }

    private function logOperation(): void
    {
        fwrite($this->outputFileDescriptor, serialize([
            'operation' => $this->currentOperation,
            'timestamp' => microtime(true),
        ]) . "\n");
    }

    public function __destruct()
    {
        if (gettype($this->outputFileDescriptor) == 'resource') {
            fclose($this->outputFileDescriptor);
        }
    }
}
