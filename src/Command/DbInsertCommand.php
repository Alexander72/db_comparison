<?php

namespace App\Command;

use App\Entity\Entity;
use App\Repository\Contract\EntityRepository;
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

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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

        $data = $this->getDataToWrite();
        foreach ($data as $entityData) {
            $entity = Entity::createFromData($entityData);
            $repository->insert($entity);
        }

        $io->success('Data was successfully inserted to db');

        return Command::SUCCESS;
    }

    private function getDataToWrite(): array
    {
        return [
            [
                'origin' => 'AMS',
                'destination' => 'MOW',
                'price' => 12304,
                'departure' => '2020-12-03',
            ],
            [
                'origin' => 'MOW',
                'destination' => 'AMS',
                'price' => 10304,
                'departure' => '2020-12-04',
            ],
        ];
    }

    protected function getRepository(InputInterface $input): EntityRepository
    {
        return $this->container->get($input->getArgument('db'));
    }
}
