<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entity;
use App\Repository\Contract\EntityRepository;
use DateTime;
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
        $this->collection->find($where)->toArray();
    }

    public function selectByRange(string $field, $gt, $lt): void
    {
        $data = $this->collection->find([
            $field => [
                '$gt' => $this->getFormattedDate($gt),
                '$lt' => $this->getFormattedDate($lt),
            ]
        ])->toArray();
    }

    public function selectAvgByRange(string $avgField, string $conditionField, $gt, $lt): void
    {
        $data = $this->collection->aggregate([
            [
                '$match' => [
                    $conditionField => [
                        '$gt' => $this->getFormattedDate($gt),
                        '$lt' => $this->getFormattedDate($lt),
                    ]
                ],
            ],
            [
                '$group' => [
                    '_id' => [
                        '$dateToString' => [
                            'date' => '$departure',
                            'format' => '%m',
                        ],
                    ],
                    'avg_price' => [
                        '$avg' => '$price',
                    ],
                ],
            ],
            [
                '$sort' => [
                    '_id' => 1,
                ],
            ],
        ])->toArray();
        $a = 1;
    }

    public function insert(array $entity): void
    {
        $row = $entity;
        $row['departure'] = new UTCDateTime(DateTime::createFromFormat('Y-m-d', $row['departure'])->getTimestamp() * 1000);
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

    private function getFormattedDate($gt): UTCDateTime
    {
        return new UTCDateTime(DateTime::createFromFormat('Y-m-d', $gt)->getTimestamp() * 1000);
    }

}
