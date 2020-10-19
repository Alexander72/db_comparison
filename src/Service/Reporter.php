<?php declare(strict_types=1);

namespace App\Service;

class Reporter
{
    const REPORT_DIR = __DIR__ . '/../../var/output/';

    private array $data;

    public function loadData(string $fileName): void
    {
        $f = fopen(self::REPORT_DIR . $fileName . '.txt', 'r');
        while ($row = fgets($f)) {
            $this->data[] = unserialize($row);
        }
        fclose($f);
    }

    public function getOperationIndexes(): array
    {
        return array_column($this->getData(), 'operation');
    }

    public function getOperationDurations(): array
    {
        return array_column($this->getData(), 'duration');
    }

    public function getOperationsCount(): int
    {
        return count($this->data);
    }

    public function getData(): array
    {
        $result = [];
        foreach ($this->data as $index => $operation) {
            if ($index == 0) {
                $result = [[0, 0]];
                $previousOperation = $operation;
                continue;
            }

            $result[] = [
                'operation' => $operation['operation'],
                'duration' => ($operation['timestamp'] - $previousOperation['timestamp']) / ($operation['operation'] - $previousOperation['operation']),
            ];

            $previousOperation = $operation;
        }

        return $result;
    }
}
