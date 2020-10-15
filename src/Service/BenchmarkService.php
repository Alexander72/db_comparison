<?php declare(strict_types=1);

namespace App\Service;

class BenchmarkService
{
    private $startTime;
    public function start(): void
    {
        $this->startTime = microtime();
    }

    public function operationHasBeenMade()
    {

    }

    public function finish(): void
    {

    }

}
