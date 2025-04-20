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

    public function findAll()
    {
        return $this->wpdb->get_results("SELECT * FROM {$this->table}");
    }

    public function findById($id)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->table} WHERE id = %d", $id)
        );
    }

    public function insert($data)
    {
        return $this->wpdb->insert($this->table, $data) !== false;
    }

    public function delete($id)
    {
        return $this->wpdb->delete($this->table, ['id' => $id]) !== false;
    }
}
