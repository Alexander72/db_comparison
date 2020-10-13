<?php declare(strict_types=1);

namespace App\Repository\Contract;

use App\Entity\Entity;

interface EntityRepository
{
    public function insert(Entity $entity): void;
    public function update(Entity $entity): void;
    public function selectById(int $entityId): Entity;
    public function deleteById(int $entityId): void;
}
