<?php declare(strict_types=1);

namespace App\Command;

use App\Repository\Contract\EntityRepository;
use Symfony\Component\Console\Input\InputInterface;

class DbSelectAggregatedCommand extends DbSelectByRangeCommand
{
    protected static $defaultName = 'db:selectAggregated';

    protected function doSelect(EntityRepository $repository): void
    {
        $range = $this->getRangeEdges();

        $repository->selectAvgByRange('price', 'departure', $range['gt'], $range['lt']);
    }

    protected function getBenchmarkName(): string
    {
        return 'select_aggregated';
    }
}
