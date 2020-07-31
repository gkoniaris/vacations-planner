<?php
namespace App\Models;

use App\Singletons\Database;

abstract class BaseModel implements BaseModelInterface
{
    protected $table = null;
    protected $wheres = [];
    protected $joins = [];
    protected $limit = null;
    protected $data = [];

    public function __construct()
    {
        if ($this->table === null) throw new \Exception('Provide table name');
    }

    private function generatePlaceholders($length)
    {
        $placeholders = '';

        for ($i = 0; $i < $length; $i++) {
            $placeholders .= '?';
            if ($i !== $length - 1) $placeholders .= ', ';
        }

        return $placeholders;
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

    public function find($id)
    {
        $results = Database::select(
            'SELECT * FROM ' . $this->table . ' WHERE id = ?', 
            [$id],
            get_class($this)
        );

        return $results;
    }

    public function fill($data) 
    {
        foreach($data as $key => $value) {
            $this->data[$key] = $value;
        }

        return $this;
    }

    public function save()
    {
        return $this->create($this->data);
    }

    public function create($data)
    {
        $modelData = $data;
        
        ksort($modelData);

        $keys = array_map(function($key) {
            return '`' . $key . '`';
        }, array_keys($modelData));

        $fields = implode(',', $keys);
        $placeholders = $this->generatePlaceholders(sizeof($modelData));
        $values = array_values($modelData);

        $id = Database::insert(
            'INSERT INTO ' . $this->table . ' (' . $fields . ') VALUES (' . $placeholders . ')', 
            $values
        );

        $createdModel = $this->find($id);

        return $createdModel;
    }

    public function update($id, $data)
    {
        return true;
    }

    public function where($field, $equalityOperator, $data)
    {
        return true;
    }

    public function get()
    {
        return true;
    }
}
