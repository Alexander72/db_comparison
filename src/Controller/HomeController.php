<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        $data = [
            'insertData' => [
                'MySQL' => [4, 5, 8, 10, 14, 16, 20],
                'Mongo' => [5, 5, 7, 9, 12, 13, 15],
                'Cassandra' => [5, 6, 6, 13, 13, 16, 17],
            ],
            'updateData' => [
                'MySQL' => [1, 3, 5, 10, 18, 26, 30],
                'Mongo' => [4, 6, 7, 7, 10, 15, 18],
                'Cassandra' => [2, 3, 6, 10, 14, 16, 22],
            ],
        ];
        return $this->render('pages/home.html.twig', $data);
    }
}
