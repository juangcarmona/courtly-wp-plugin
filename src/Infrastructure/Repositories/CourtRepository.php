<?php

namespace Juangcarmona\Courtly\Infrastructure\Repositories;

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
        return $this->wpdb->get_results("SELECT * FROM {$this->table}", ARRAY_A);
    }

    public function findById(int $id): ?object
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->table} WHERE id = %d", $id)
        );
    }

    public function insert(array $data): bool
    {
        return $this->wpdb->insert($this->table, $data) !== false;
    }

    public function delete(int $id): bool
    {
        return $this->wpdb->delete($this->table, ['id' => $id]) !== false;
    }
}
