<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        $data = [
            'insertData' => [
                'xAxis' => $this->generateLogarithmicXAxis(20),
                'data' => [
                    'MySQL' => $this->generateRandomLogarithmicData(20),
                    'Mongo' => $this->generateRandomLogarithmicData(20),
                    'Cassandra' => $this->generateRandomLogarithmicData(20),
                ]
            ],
            'updateData' => [
                'xAxis' => $this->generateLogarithmicXAxis(20),
                'data' => [
                    'MySQL' => $this->generateRandomLogarithmicData(20),
                    'Mongo' => $this->generateRandomLogarithmicData(20),
                    'Cassandra' => $this->generateRandomLogarithmicData(20),
                ]
            ],
        ];
        return $this->render('pages/home.html.twig', $data);
    }

    protected function generateRandomLogarithmicData(int $length): array
    {
        return [4, 5, 8, 10, 14, 16, 20];
    }

    private function generateLogarithmicXAxis(int $length)
    {
        $result = [];
        for ($i = 0; $i < $length; $i++) {

        }
    }
}
