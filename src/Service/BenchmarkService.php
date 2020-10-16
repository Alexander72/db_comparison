<?php declare(strict_types=1);

namespace App\Service;

use Exception;

class BenchmarkService
{
    const LOGARITHMIC_STRATEGY = 'logarithmic';

    private float $startTime;
    private float $totalExecutionTime;
    private int $operationsCount;
    private string $strategy;
    private int $currentOperation;
    private array $operations;

    public function start(int $operationsCount, string $strategy = self::LOGARITHMIC_STRATEGY): void
    {
        $this->setOperationsCount($operationsCount);
        $this->strategy = $strategy;
        $this->currentOperation = 0;
        $this->startTime = microtime(true);
    }

    public function setOperationsCount(int $operationsCount): void
    {
        $this->operationsCount = $operationsCount;
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

    private function shouldBeLogged(): bool
    {
        switch($this->strategy)
        {
            case self::LOGARITHMIC_STRATEGY:
                return $this->isValuePowOfBase($this->currentOperation, 2);
            default:
                throw new Exception('Unsupported strategy: ' . $this->strategy);
        }
    }

    private function logOperation(): void
    {
        $this->operations[] = [
            'operation' => $this->currentOperation,
            'timestamp' => microtime(true),
        ];
    }

    private function isValuePowOfBase(int $value, int $base)
    {
        if ($base <= 1) {
            throw new Exception("Base cannot be less or equal to 1: $base.");
        }

        $currentValue = 1;
        do {
            if ($currentValue == $value) {
                return true;
            }
            $currentValue *= $base;
        } while ($currentValue <= $value);

        return false;
    }

    public function getReport(): array
    {
        $result = [];
        foreach ($this->operations as $operation) {
            $result[] = [
                //operationIndex
                //averageDuration
            ];
        }

        return $result;
    }
}
