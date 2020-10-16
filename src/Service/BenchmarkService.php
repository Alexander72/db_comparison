<?php declare(strict_types=1);

namespace App\Service;

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

            default:
                throw new \Exception('Unsupported strategy: ' . $this->strategy);
        }
    }

    private function logOperation(): void
    {
        $this->currentOperation++;
        $this->operations[] = [
            'operation' => $this->currentOperation,
            'duration' => $this->lastOperationsExecutionTime(),
        ];
    }

    private function lastOperationsExecutionTime(): float
    {
        if (empty($this->operations)) {
            return microtime(true) - $this->startTime;
        } else {
            return microtime(true) - $this->operations[count($this->operations) - 1]['duration'];
        }
    }
}
