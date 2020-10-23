<?php declare(strict_types=1);

namespace App\Command;

use App\Repository\Contract\EntityRepository;
use DateInterval;
use DateTimeImmutable;
use Symfony\Component\Console\Input\InputInterface;

class DbSelectByRangeCommand extends AbstractDbSelectCommand
{
    const TOTAL_LOG_RECORDS = 100;

    protected static $defaultName = 'db:selectByRange';
    private int $daysTotal;
    private DateTimeImmutable $minDay;
    private DateTimeImmutable $maxDay;

    protected function configure()
    {
        parent::configure();
        $this->setDescription('Selects data from specific db by range and calculates latencies.');
    }

    protected function getBenchmarkName(): string
    {
        return 'select_by_range';
    }

    protected function doSelect(EntityRepository $repository): void
    {
        $range = $this->getRangeEdges();

        $repository->selectByRange('departure', $range['gt'], $range['lt']);
    }

    protected function prepare(InputInterface $input)
    {
        $this->minDay = new DateTimeImmutable('2019-02-10');
        $this->maxDay = new DateTimeImmutable('2019-06-15');

        $this->daysTotal = (int) $this->maxDay->diff($this->minDay)->format('%a');
    }

    protected function getRangeEdges(): array
    {
        $daysLeast = rand(0, $this->daysTotal);
        $daysGreatest = rand(0, $this->daysTotal);

        if ($daysGreatest < $daysLeast) {
            $tmp = $daysGreatest;
            $daysGreatest = $daysLeast;
            $daysLeast = $tmp;
        }

        $gt = $this->minDay->add(new DateInterval("P{$daysLeast}D"));
        $lt = $this->minDay->add(new DateInterval("P{$daysGreatest}D"));

        return ['gt' => $gt->format('Y-m-d'), 'lt' => $lt->format('Y-m-d')];
    }
}
