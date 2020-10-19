<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\Reporter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index(Reporter $reporter)
    {
        $reporter->loadData('mongo_insert');
        $length = $reporter->getOperationsCount();
        $mongoInserts = $reporter->getOperationDurations();
        $reporter->loadData('mysql_insert');
        $mysqlInserts = $reporter->getOperationDurations();
        $data = [
            'insertData' => [
                'xAxis' => $this->prettifyIndexes($reporter),
                'data' => [
                    'MySQL' => $mysqlInserts,
                    'Mongo' => $mongoInserts,
                    'Cassandra' => $this->generateRandomLogarithmicData($length),
                ]
            ],
            'updateData' => [
                'xAxis' => $this->generateLogarithmicXAxis($length),
                'data' => [
                    'MySQL' => $this->generateRandomLogarithmicData($length),
                    'Mongo' => $this->generateRandomLogarithmicData($length),
                    'Cassandra' => $this->generateRandomLogarithmicData($length),
                ]
            ],
        ];
        return $this->render('pages/home.html.twig', $data);
    }

    protected function generateRandomLogarithmicData(int $length): array
    {
        $result = [];
        for ($i = 1; $i <= $length; $i++) {
            $result[] = 0;
        }

        return $result;
    }

    private function generateLogarithmicXAxis(int $length)
    {
        $result = [];
        for ($i = 0; $i < $length; $i++) {
            $result[] = empty($result) ? 1 : $result[count($result) - 1] * 2;
        }

        return $result;
    }

    private function prettifyIndexes(Reporter $reporter): array
    {
        return array_map(function (int $index): string {
            return round($index / 1000) . 'K';
        }, $reporter->getOperationIndexes());
    }
}
