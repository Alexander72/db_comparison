<?php declare(strict_types=1);

namespace App\Command;

use App\Repository\Contract\EntityRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AbstractDbSelectCommand extends AbstractCommand
{
    const OPERATIONS_COUNT = 1000;
    const TOTAL_LOG_RECORDS = 1000;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $repository = $this->getRepository($input);

        $this->prepare($input);
        $io->note('Starting data selection.');

        $progressBar = $io->createProgressBar(static::OPERATIONS_COUNT);
        $this->benchmarkService->start($this->getFullBenchmarkName($input), static::OPERATIONS_COUNT, static::TOTAL_LOG_RECORDS);
        for ($i = 0; $i < static::OPERATIONS_COUNT; $i++) {
            $this->doSelect($repository);

            $this->benchmarkService->operationHasBeenMade();
            $progressBar->advance();
        }
        $this->benchmarkService->finish();
        $progressBar->finish();
        $io->newLine(2);

        $io->success(static::OPERATIONS_COUNT . " selects were successfully made in db in {$this->benchmarkService->getTotalExecutionTime()} seconds.");

        return Command::SUCCESS;
    }

    abstract protected function doSelect(EntityRepository $repository): void;

    abstract protected function getBenchmarkName(): string;

    private function getFullBenchmarkName(InputInterface $input): string
    {
        return $input->getArgument('db') . '_' . $this->getBenchmarkName();
    }

    protected function prepare(InputInterface $input)
    {
    }
}
