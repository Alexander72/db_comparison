<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DbInsertCommand extends AbstractCommand
{
    const INPUT_STRING_LENGTH = 54.5;

    protected static $defaultName = 'db:insert';

    protected function configure()
    {
        parent::configure();

        $this->setDescription('Writes data to specific db and calculates latencies.')
            ->addOption('inputFile', 'i', InputOption::VALUE_REQUIRED, 'Source file to get data for insertion from. With only filename will look in input/ directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $repository = $this->getRepository($input);

        $entitiesCount = $this->getEntityCount($input);

        $io->note('Starting data insertion.');
        $progressBar = $io->createProgressBar($entitiesCount);
        $progressBar->start();
        foreach ($this->getDataToInsert($input) as $index => $entityData) {
            if ($index == 0) {
                $this->benchmarkService->start($input->getArgument('db') . '_insert', $entitiesCount);
            }

            $progressBar->advance();

            $repository->insert($entityData);

            $this->benchmarkService->operationHasBeenMade();
        }
        $this->benchmarkService->finish();
        $progressBar->finish();
        $io->newLine(2);

        $io->success("$entitiesCount rows were successfully inserted in db in {$this->benchmarkService->getTotalExecutionTime()} seconds.");

        return Command::SUCCESS;
    }

    private function getDataToInsert(InputInterface $input): \Generator
    {
        $rowIndex = 0;
        $f = fopen($this->getSourceFileName($input), 'r');
        while (($data = fgetcsv($f)) !== FALSE) {
            $row = $this->getRowForInsertion($data);
            yield $rowIndex++ => $row;
        }
        fclose($f);
    }

    private function getEntityCount(InputInterface $input): int
    {
        $fileSize = filesize($this->getSourceFileName($input));
        return round($fileSize / self::INPUT_STRING_LENGTH);
    }

    private function getRowForInsertion(?array $data): array
    {
        $row = array_combine([
            'origin',
            'destination',
            'price',
            'departure',
            'saved'
        ], $data);

        $row['price'] = (int) $row['price'];

        return $row;
    }

    private function getSourceFileName(InputInterface $input): string
    {
        $fileName = $input->getOption('inputFile');

        if (file_exists($fileName)) {
            return $fileName;
        }

        $fileName = __DIR__ . '/../../input/' . $fileName;

        if (!file_exists($fileName)) {
            throw new \Exception("File $fileName does not exist.");
        }

        return $fileName;
    }
}
