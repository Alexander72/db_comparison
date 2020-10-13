<?php declare(strict_types=1);

namespace App\Entity;

class Entity
{
    private ?int $id;
    private string $origin;
    private string $destination;
    private int $price;
    private string $departure;

    public function __construct(
        ?int $id,
        string $origin,
        string $destination,
        int $price,
        string $departure
    ) {
        $this->id = $id;
        $this->origin = $origin;
        $this->destination = $destination;
        $this->price = $price;
        $this->departure = $departure;
    }

    public static function createFromData(array $entityData): self
    {
        return new self(
            $entityData['id'] ?? null,
            $entityData['origin'],
            $entityData['destination'],
            $entityData['price'],
            $entityData['departure'],
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
