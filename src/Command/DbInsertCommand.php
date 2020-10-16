<?php

namespace App\Command;

use App\Entity\Entity;
use App\Repository\Contract\EntityRepository;
use App\Service\BenchmarkService;
use DateTime;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DbInsertCommand extends Command
{
    protected static $defaultName = 'db:insert';

    private ContainerInterface $container;

    private BenchmarkService $benchmarkService;

    public function __construct(
        ContainerInterface $container,
        BenchmarkService $benchmarkService
    ) {
        $this->container = $container;
        $this->benchmarkService = $benchmarkService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Writes data to specific db and calculates latencies.')
            ->addArgument('db', InputArgument::REQUIRED, 'Db to write. Should be one of the: mysql, mongodb, cassandra');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $repository = $this->getRepository($input);

        $count = 0;
        foreach ($this->getDataToWrite() as $entityData) {
            if ($count == 0) {
                $this->benchmarkService->start(34);
            }

            $repository->insert($entityData);

            $this->benchmarkService->operationHasBeenMade();

            $count++;
        }
        $this->benchmarkService->finish();

        $io->success("$count rows were successfully inserted in db");
        $io->table(
            [
                'operationIndex',
                'duration',
            ],
            $this->benchmarkService->getReport()
        );

        return Command::SUCCESS;
    }

    private function getDataToWrite(): \Generator
    {
        $f = fopen(__DIR__ . '/../../input/routes_small.csv', 'r');
        while (($data = fgetcsv($f)) !== FALSE) {
            $row = array_combine([
                'origin',
                'destination',
                'price',
                'departure',
                'saved'
            ], $data);

            $row['price'] = (int) $row['price'];

            yield $row;
        }
        fclose($f);
    }

    protected function getRepository(InputInterface $input): EntityRepository
    {
        return $this->container->get($input->getArgument('db'));
    }
}
