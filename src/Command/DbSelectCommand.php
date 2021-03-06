<?php

namespace App\Command;

use App\Repository\Contract\EntityRepository;
use Symfony\Component\Console\Input\InputInterface;

class DbSelectCommand extends AbstractDbSelectCommand
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
        $this->setDescription('Selects data from specific db by exact comparison and calculates latencies.');
    }

    protected function getBenchmarkName(): string
    {
        return 'select';
    }

    protected function doSelect(EntityRepository $repository): void
    {
        $where = [
            'origin' => self::CITIES[rand(0, count(self::CITIES) - 1)],
            'destination' => self::CITIES[rand(0, count(self::CITIES) - 1)],
        ];
        $repository->select($where);
    }
}
