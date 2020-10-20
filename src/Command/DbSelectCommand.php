<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DbSelectCommand extends AbstractCommand
{
    protected static $defaultName = 'db:select';

    const CITIES = [
        'HEL', 'BER', 'MSQ', 'TBS', 'BCN', 'MIL', 'PAR', 'RIX', 'PRG', 'VNO', 'ATH', 'IST', 'WAW', 'AMS', 'MAD', 'LON',
        'ROM', 'LIS', 'VCE', 'MUC', 'BUD', 'TLL', 'EVN', 'SOF', 'NCE', 'VIE', 'KUT', 'AGP', 'BRU', 'OPO', 'DUB', 'TCI',
        'BAK', 'PMI', 'BUH', 'CTA', 'VAR', 'BOJ', 'IBZ', 'TIV', 'PMO', 'AYT', 'BUS', 'NAP', 'FRA', 'LCA', 'ALC', 'ZRH',
        'LYS', 'GDN', 'CPH', 'EDI', 'HAM', 'DUS', 'STO', 'OSL', 'BLQ', 'VLC', 'ZAG', 'GVA', 'DRS', 'SKG', 'HAJ', 'SPU',
        'HER', 'STR', 'MRS', 'RHO', 'GOA', 'CFU', 'PSA', 'GZP', 'BEG', 'BTS', 'VRN', 'MLA', 'LJU', 'BRI', 'REK', 'ANK',
        'BOD', 'OLB', 'PUY', 'RMI', 'CGN', 'LPA', 'GRO', 'CAG', 'FLR', 'IZM', 'SVQ', 'KRK', 'FNC', 'DLM', 'MAN', 'PFO',
        'JTR', 'DBV', 'TRN', 'TGD', 'TLS', 'LEJ', 'TIA', 'SXB', 'ADA', 'SZG', 'INN', 'FKB', 'SKP', 'BIO', 'CHQ', 'KGS',
        'SUF', 'BJV', 'ECN', 'DEB', 'LWN', 'KVD', 'FAO', 'TRS', 'EIN', 'KLV', 'FMM', 'OST', 'MJT', 'PLQ', 'PSR', 'SCV',
        'LEI', 'AJA', 'REU', 'TKU', 'JMK', 'RJK', 'NUE', 'WRO', 'OVD', 'ZTH', 'BFS', 'GLA', 'FSC', 'BRQ', 'EAP', 'ZAD',
        'RTM', 'SCQ', 'PGF', 'LPL', 'LUX', 'POZ', 'KVA', 'KUN', 'AJI', 'ACE', 'TAY', 'LIL', 'ANR', 'TRD', 'DOL', 'ASR',
        'NAV', 'BZG', 'AHO', 'GRZ', 'UME', 'DTM', 'VGO', 'BZR', 'LLK'
    ];

    protected function configure()
    {
        parent::configure();
        $this->setDescription('Selects data from specific db and calculates latencies.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $repository = $this->getRepository($input);

        $io->note('Starting data selection.');

        $operationsCount = 100000;
        $progressBar = $io->createProgressBar($operationsCount);
        $this->benchmarkService->start($input->getArgument('db') . '_select', $operationsCount);
        for ($i = 0; $i < $operationsCount; $i++) {
            $repository->select($this->generateWhere());
            $this->benchmarkService->operationHasBeenMade();
            $progressBar->advance();
        }
        $this->benchmarkService->finish();
        $progressBar->finish();
        $io->newLine(2);

        $io->success("$operationsCount selects were successfully made in db in {$this->benchmarkService->getTotalExecutionTime()} seconds.");

        return Command::SUCCESS;
    }

    private function generateWhere(): array
    {
        return [
            'origin' => self::CITIES[rand(0, count(self::CITIES) - 1)],
            'destination' => self::CITIES[rand(0, count(self::CITIES) - 1)],
        ];
    }
}
