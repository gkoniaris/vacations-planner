<?php
namespace App\Models;

use App\Singletons\Database;

abstract class BaseModel implements BaseModelInterface
{
    protected $table = null;

    public function __construct()
    {
        if ($this->table === null) throw new \Exception('Provide table name');
    }

    public function all()
    {
        $results = Database::selectAll(
            'SELECT * FROM ' . $this->table, 
            [],
            get_class($this)
        );

        return $results;
    }

    public function find($id) {
        $results = Database::select(
            'SELECT * FROM ' . $this->table . ' WHERE id = ?', 
            [$id],
            get_class($this)
        );

        return $results;
    }

    public function create($data) {
        return true;
    }

    public function update($id, $data) {
        return true;
    }
}
