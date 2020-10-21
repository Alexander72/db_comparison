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

    public function select(array $where): void
    {
        $query = "SELECT * FROM route WHERE ";
        $query .= implode(' AND ', array_map(function ($field, $value) {
            return "`$field` = '$value'";
        }, array_keys($where), $where));

        $data = $this->connection->query($query)->fetchAll();
    }

    public function selectByRange(string $field, $gt, $lt): void
    {
        $query = "SELECT * FROM route WHERE `$field` BETWEEN '$gt' AND '$lt'";

        $data = $this->connection->query($query)->fetchAll();
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
