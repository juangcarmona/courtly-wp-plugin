<?php

class UserTypeRepository
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
