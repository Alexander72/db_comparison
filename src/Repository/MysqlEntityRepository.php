<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entity;
use App\Repository\Contract\EntityRepository;
use Doctrine\DBAL\Driver\Connection;

class MysqlEntityRepository implements EntityRepository
{
    private Connection $connection;

    public function __construct()
    {
        $this->connection = new \Doctrine\DBAL\Driver\PDO\Connection('mysql:host=mysql.database;dbname=db_comparison_test', 'root', 'password');
    }

    public function insert(array $entity): void
    {
        $query = "INSERT INTO route (origin, destination, price, departure) VALUES (
            '{$entity['origin']}',
            '{$entity['destination']}',
            {$entity['price']},
            '{$entity['departure']}'
        )";
        $this->connection->exec($query);
    }

    public function update(Entity $entity): void
    {
        // TODO: Implement update() method.
    }

    public function selectById(int $entityId): Entity
    {
        // TODO: Implement selectById() method.
    }

    public function deleteById(int $entityId): void
    {
        // TODO: Implement deleteById() method.
    }
}
