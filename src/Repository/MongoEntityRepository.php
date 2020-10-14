<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entity;
use App\Repository\Contract\EntityRepository;
use MongoDB\Client;

class MongoEntityRepository implements EntityRepository
{
    private $collection;

    public function __construct()
    {
        $this->collection = (new Client('mongodb://root:password@mongodb.database'))->db_comparison_test->route;
    }

    public function insert(Entity $entity): void
    {
        $insertOneResult = $this->collection->insertOne([
            'origin' => $entity->getOrigin(),
            'destination' => $entity->getDestination(),
            'price' => $entity->getPrice(),
            'departure' => $entity->getDeparture(),
        ]);

        printf("Inserted %d document(s)\n", $insertOneResult->getInsertedCount());

        var_dump($insertOneResult->getInsertedId());
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
