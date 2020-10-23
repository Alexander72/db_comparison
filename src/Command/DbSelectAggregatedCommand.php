<?php declare(strict_types=1);

namespace App\Command;

use App\Repository\Contract\EntityRepository;
use Symfony\Component\Console\Input\InputInterface;

class DbSelectAggregatedCommand extends AbstractDbSelectCommand
{
    protected static $defaultName = 'db:selectAggregated';

    protected function doSelect(EntityRepository $repository): void
    {
        // TODO: Implement doSelect() method.
    }

    protected function getBenchmarkName(InputInterface $input): string
    {
        return 'select_aggregated';
    }
}
