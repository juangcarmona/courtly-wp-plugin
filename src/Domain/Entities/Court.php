<?php
namespace Juangcarmona\Courtly\Domain\Entities;

class Court implements BaseEntity
{
    private int $id;
    private int $number;
    private string $name;
    private ?string $description;
    private array $pictures; // array of image URLs or relative paths

    public function __construct(
        int $id,
        int $number,
        string $name,
        ?string $description = null,
        array $pictures = []
    ) {
        $this->id = $id;
        $this->number = $number;
        $this->name = $name;
        $this->description = $description;
        $this->pictures = $pictures;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPictures(): array
    {
        return $this->pictures;
    }

    public function addPicture(string $url): void
    {
        if (!in_array($url, $this->pictures)) {
            $this->pictures[] = $url;
        }
    }

    public function removePicture(string $url): void
    {
        $this->pictures = array_filter($this->pictures, fn($pic) => $pic !== $url);
    }

    // BaseEntity implementation:
    public static function schema(string $table): string
    {
        return "CREATE TABLE IF NOT EXISTS $table (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            number INT NOT NULL,
            name VARCHAR(100) NOT NULL,
            description TEXT NULL,
            pictures TEXT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
    }
}
