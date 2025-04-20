<?php

namespace Juangcarmona\Courtly\Infrastructure\Repositories;

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

    public function findAll()
    {
        return $this->wpdb->get_results("SELECT * FROM {$this->table}");
    }

    public function countAll(): int {
        global $wpdb;
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}courtly_user_types");
    }
    
    public function getUserTypeBreakdown(): array {
        global $wpdb;
        $results = $wpdb->get_results("
            SELECT ut.display_name, COUNT(u.ID) as user_count
            FROM {$wpdb->prefix}courtly_user_types ut
            LEFT JOIN {$wpdb->prefix}usermeta um ON um.meta_key = 'courtly_user_type' AND um.meta_value = ut.name
            LEFT JOIN {$wpdb->prefix}users u ON u.ID = um.user_id
            GROUP BY ut.name
        ");
        return $results;
    }    

    public function findByName($name)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->table} WHERE name = %s", $name)
        );
    }

    public function insert($data)
    {
        return $this->wpdb->insert($this->table, $data) !== false;
    }
}
