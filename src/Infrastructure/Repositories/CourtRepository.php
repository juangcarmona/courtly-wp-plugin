<?php

namespace Juangcarmona\Courtly\Infrastructure\Repositories;

use Juangcarmona\Courtly\Domain\Entities\Court;
use Juangcarmona\Courtly\Domain\Repositories\CourtRepositoryInterface;

class CourtRepository implements CourtRepositoryInterface
{
    private $wpdb;
    private $table;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix . 'courtly_courts';
    }

    public function findAll(): array
    {
        $rows = $this->wpdb->get_results("SELECT * FROM {$this->table}", ARRAY_A);
        return array_map([$this, 'mapRowToEntity'], $rows);
    }

    public function findById(int $id): ?Court
    {
        $row = $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->table} WHERE id = %d", $id),
            ARRAY_A
        );

        return $row ? $this->mapRowToEntity($row) : null;
    }

    public function insert(array $data): bool
    {
        $data['pictures'] = implode(',', $data['pictures'] ?? []);
        return $this->wpdb->insert($this->table, $data) !== false;
    }

    public function delete(int $id): bool
    {
        return $this->wpdb->delete($this->table, ['id' => $id]) !== false;
    }

    private function mapRowToEntity(array $row): Court
    {
        return new Court(
            (int)$row['id'],
            (int)$row['number'],
            $row['name'],
            $row['description'] ?? null,
            $this->deserializePictures($row['pictures'] ?? '')
        );
    }

    private function deserializePictures(string $raw): array
    {
        return array_filter(array_map('trim', explode(',', $raw)));
    }
}
