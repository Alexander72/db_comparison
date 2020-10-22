<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\Reporter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index(Reporter $reporter)
    {
        $data = [
            'insertData' => [
                'title' => 'Inserts',
                'xAxis' => $this->getXAxis($reporter, 'mysql_insert'),
                'data' => [
                    'MySQL' => $this->getData($reporter, 'mysql_insert'),
                    'Mongo' => $this->getData($reporter, 'mongo_insert'),
                    'Cassandra' => $this->getData($reporter, 'cassandra_insert'),
                ]
            ],
            'selectData' => [
                'title' => 'Selects by exact comparison',
                'xAxis' => $this->getXAxis($reporter, 'mysql_select'),
                'data' => [
                    'MySQL' => $this->getData($reporter, 'mysql_select'),
                    'Mongo' => $this->getData($reporter, 'mongo_select'),
                ]
            ],
            'selectByRangeData' => [
                'title' => 'Selects by range',
                'xAxis' => $this->getXAxis($reporter, 'mysql_select_by_range'),
                'data' => [
                    'MySQL' => $this->getData($reporter, 'mysql_select_by_range'),
                    'Mongo' => $this->getData($reporter, 'mongo_select_by_range'),
                ]
            ],
        ];
        return $this->render('pages/home.html.twig', ['chartsData' => $data]);
    }

    private function prettifyIndexes(Reporter $reporter): array
    {
        return array_map(function (int $index): string {
            return $index >= 1000 ? round($index / 1000) . 'K' : (string) $index;
        }, $reporter->getOperationIndexes());
    }

    private function getXAxis(Reporter $reporter, string $dataName): array
    {
        return $this->prettifyIndexes($reporter->loadData($dataName));
    }

    private function getData(Reporter $reporter, string $dataName): array
    {
        return $reporter->loadData($dataName)->getOperationDurations();
    }
}
