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
    private string $outputFileName;


    public function start(
        string $name,
        int $operationsCount,
        int $totalLogRecords = 1000
    ): void {
        $this->currentOperation = 0;
        $this->operationsCount = $operationsCount;
        $this->totalLogRecords = $totalLogRecords;
        $this->outputFileName = self::OUTPUT_FILE_DIR . $name . '.txt';
        $this->outputFileDescriptor = fopen($this->outputFileName, 'w');
        $this->startTime = microtime(true);
    }

    public function operationHasBeenMade()
    {
        $this->currentOperation++;

        if ($this->shouldBeLogged()) {
            $this->logOperation();
        }
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

    public function getReport(): array
    {
        $result = [];

        $index = 0;
        $previousOperation = null;

        $f = fopen($this->outputFileName, 'r');
        while ($operation = fgets($f)) {
            $operation = unserialize($operation);

            $previousOperationIndex = $previousOperation['operation'] ?? 0;
            $previousOperationTimestamp = $previousOperation['timestamp'] ?? $this->startTime;

            $result[] = [
                $operation['operation'],
                ($operation['timestamp'] - $previousOperationTimestamp) / ($operation['operation'] - $previousOperationIndex),
            ];

            $previousOperation = $operation;

            $index++;
        }
        fclose($f);

        return $result;
    }

    public function __destruct()
    {
        if (gettype($this->outputFileDescriptor) == 'resource') {
            fclose($this->outputFileDescriptor);
        }
    }
}
