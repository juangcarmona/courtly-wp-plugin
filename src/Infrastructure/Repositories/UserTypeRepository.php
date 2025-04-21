<?php

namespace Juangcarmona\Courtly\Infrastructure\Repositories;
use Juangcarmona\Courtly\Domain\Entities\UserType;
use Juangcarmona\Courtly\Domain\Repositories\UserTypeRepositoryInterface;

class UserTypeRepository implements UserTypeRepositoryInterface
{
    private $wpdb;
    private $table;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix . 'courtly_user_types';
    }

    public function findAll(): array
    {
        $rows = $this->wpdb->get_results("SELECT * FROM {$this->table}");
        return array_map([$this, 'mapRowToEntity'], $rows);
    }
    
    public function findByName(string $name): ?UserType
    {
        $row = $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->table} WHERE name = %s", $name)
        );
    
        return $row ? $this->mapRowToEntity($row) : null;
    }

    public function countAll(): int
    {
        return (int) $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table}");
    }

    public function getUserTypeBreakdown(): array
    {
        $results = $this->wpdb->get_results("
            SELECT ut.display_name, COUNT(u.ID) as user_count
            FROM {$this->table} ut
            LEFT JOIN {$this->wpdb->prefix}usermeta um 
                ON um.meta_key = 'courtly_user_type' AND um.meta_value = ut.name
            LEFT JOIN {$this->wpdb->prefix}users u ON u.ID = um.user_id
            GROUP BY ut.name
        ");

        return $results;
    }

    public function insert(array $data): bool
    {
        return $this->wpdb->insert($this->table, $data) !== false;
    }

    private function mapRowToEntity(object $row): UserType
    {
        return new UserType(
            (int)$row->id,
            $row->name,
            $row->display_name,
            (int)$row->booking_days_in_advance
        );
    }
}
