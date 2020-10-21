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
                'xAxis' => $this->prettifyIndexes($reporter->loadData('mysql_insert')),
                'data' => [
                    'MySQL' => $reporter->loadData('mysql_insert')->getOperationDurations(),
                    'Mongo' => $reporter->loadData('mongo_insert')->getOperationDurations(),
                    'Cassandra' => $reporter->loadData('cassandra_insert')->getOperationDurations(),
                ]
            ],
            'updateData' => [
                'xAxis' => $this->prettifyIndexes($reporter->loadData('mysql_select')),
                'data' => [
                    'MySQL' => $reporter->loadData('mysql_select')->getOperationDurations(),
                    'Mongo' => $reporter->loadData('mongo_select')->getOperationDurations(),
                    'Cassandra' => [],
                ]
            ],
        ];
        return $this->render('pages/home.html.twig', $data);
    }

    private function prettifyIndexes(Reporter $reporter): array
    {
        return array_map(function (int $index): string {
            return round($index / 1000) . 'K';
        }, $reporter->getOperationIndexes());
    }
}
