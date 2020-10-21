<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entity;
use App\Repository\Contract\EntityRepository;
use Cassandra;
use Cassandra\SimpleStatement;

class CassandraEntityRepository implements EntityRepository
{
    private Cassandra\Session $session;

    public function __construct()
    {
        $cluster = Cassandra::cluster()
            ->withContactPoints('cassandra.database')
            ->build();
        $this->session = $cluster->connect('db_comparison_test');
    }


    public function insert(array $entity): void
    {
        $statement = new SimpleStatement("
            INSERT INTO route (origin, destination, price, departure) VALUES (
            '{$entity['origin']}',
            '{$entity['destination']}',
            {$entity['price']},
            '{$entity['departure']}'
        )
        ");

        $this->session->execute($statement, []);
    }

    public function update(Entity $entity): void
    {
        // TODO: Implement update() method.
    }

    public function selectById(int $entityId): Entity
    {
        // TODO: Implement selectById() method.
    }

    public function selectByRange(string $field, $gt, $lt): void
    {
        // TODO: Implement selectByRange() method.
    }

    public function select(array $where): void
    {
        // TODO: Implement select() method.
    }

    public function deleteById(int $entityId): void
    {
        // TODO: Implement deleteById() method.
    }

}
