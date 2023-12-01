<?php

namespace App\Repository\Contracts;

use App\Models\Note;
use Illuminate\Database\Eloquent\Model;

interface NoteContract
{
    public function __construct(Note $model);

    public function index();
    public function store($data);
    public function update($data,Model $model);
}
