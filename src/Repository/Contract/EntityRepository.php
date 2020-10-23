<?php declare(strict_types=1);

namespace App\Repository\Contract;

use App\Entity\Entity;

interface EntityRepository
{
    public function insert(array $entity): void;
    public function update(Entity $entity): void;
    public function selectById(int $entityId): Entity;
    public function select(array $where): void;
    public function selectByRange(string $field, $gt, $lt): void;
    public function selectAvgByRange(string $avgField, string $conditionField, $gt, $lt): void;
    public function deleteById(int $entityId): void;
}
