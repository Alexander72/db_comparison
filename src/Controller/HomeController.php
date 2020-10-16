<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        $length = 20;
        $data = [
            'insertData' => [
                'xAxis' => $this->generateLogarithmicXAxis($length),
                'data' => [
                    'MySQL' => $this->generateRandomLogarithmicData($length),
                    'Mongo' => $this->generateRandomLogarithmicData($length),
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
            $result[] = log((float) $i, 2) + rand(0, (int) (log((float) max(0, $i - 1), 2)));
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
}
