<?php

namespace App\Models;

interface BaseModelInterface
{
    
    public function find($id);

    public function all();

    public function create($data);

    public function update($id, $data);

}