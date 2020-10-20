<?php declare(strict_types=1);

namespace App\Command;

use App\Repository\Contract\EntityRepository;
use App\Service\BenchmarkService;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class AbstractCommand extends Command
{
    protected ContainerInterface $container;

    protected BenchmarkService $benchmarkService;

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
        $this->addArgument('db', InputArgument::REQUIRED, 'Db to write. Should be one of the: mysql, mongodb, cassandra');
    }

    protected function getRepository(InputInterface $input): EntityRepository
    {
        return $this->container->get($input->getArgument('db'));
    }
}
