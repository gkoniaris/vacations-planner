<?php
namespace App\Core\Models;

use App\Core\Singletons\Database;

abstract class BaseModel implements BaseModelInterface
{
    protected $table = null;
    protected $wheres = [];
    protected $joins = [];
    protected $limit = null;
    protected $data = [];
    protected $fillable = [];
    protected $hidden = [];
    protected $withHidden = false;
    private $database;

    public function __construct()
    {
        if ($this->table === null) {
            throw new \Exception('Provide table name');
        }

        $this->database = Database::getInstance();
    }

    private function generatePlaceholders($length)
    {
        $placeholders = '';

        for ($i = 0; $i < $length; $i++) {
            $placeholders .= '?';
            if ($i !== $length - 1) {
                $placeholders .= ', ';
            }
        }

        return $placeholders;
    }

    private function removeHidden($dataset)
    {
        if ($this->withHidden) return $dataset;
        if (!sizeof($this->hidden)) return $dataset;

        if (is_object($dataset))
        {
            foreach($this->hidden as $key)
            {
                unset($dataset->{$key});
            }

            return $dataset;
        }

        if (is_array($dataset))
        {
            foreach($dataset as $item)
            {
                foreach($this->hidden as $key)
                {
                    unset($item->{$key});
                }
            }

            return $dataset;
        }
    }

    public function withHidden()
    {
        $this->withHidden = true;

        return $this;
    }

    public function set($field, $value)
    {
        $this->{$field} = $value;
        $this->data[$field] = $value;
    }

    public function all()
    {
        $results = $this->database->selectAll(
            'SELECT * FROM ' . $this->table,
            [],
            get_class($this)
        );

        return $this->removeHidden($results);
    }

    public function find($id)
    {
        $results = $this->database->select(
            'SELECT * FROM ' . $this->table . ' WHERE id = ?',
            [$id],
            get_class($this)
        );

        return $this->removeHidden($results);
    }

    public function findBy($field, $value)
    {
        $results = $this->database->select(
            'SELECT * FROM ' . $this->table . ' WHERE ' . $field . ' = ?',
            [$value],
            get_class($this)
        );

        return $this->removeHidden($results);
    }

    public function fill($data)
    {
        foreach ($data as $field => $value) {
            if (!in_array($field, $this->fillable)) {
                continue;
            }
            $this->{$field} = $value;
            $this->data[$field] = $value;
        }

        return $this;
    }

    public function save()
    {
        return $this->create($this->data);
    }

    public function create($data)
    {
        $modelData = (array)$data;
        
        ksort($modelData);

        $keys = array_map(function ($key) {
            return '`' . $key . '`';
        }, array_keys($modelData));

        $fields = implode(',', $keys);
        $placeholders = $this->generatePlaceholders(sizeof($modelData));
        $values = array_values($modelData);

        $id = $this->database->insert(
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
