<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entity;
use App\Repository\Contract\EntityRepository;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use MongoDB\Collection;

class MongoEntityRepository implements EntityRepository
{
    private Collection $collection;

    public function __construct()
    {
        $this->collection = (new Client('mongodb://root:password@mongodb.database'))->db_comparison_test->route;
        $this->collection->findOne();
    }

    public function select(array $where): void
    {
        $this->collection->find($where);
    }

    public function insert(array $entity): void
    {
        $row = $entity;
        $row['departure'] = new UTCDateTime(\DateTime::createFromFormat('Y-m-d', $row['departure'])->getTimestamp() * 1000);
        $this->collection->insertOne($row);
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
