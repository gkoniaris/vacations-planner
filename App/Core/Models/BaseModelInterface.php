<?php

namespace App\Core\Models;

interface BaseModelInterface
{
    
    public function find($id);

    public function all();

    public function create($data);

    public function update($id, $data);

    public function where($field, $equalityOperator, $data);

    public function get();

}