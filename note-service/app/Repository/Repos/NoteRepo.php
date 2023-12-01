<?php

namespace App\Repository\Repos;

use App\Models\Note;
use App\Repository\Contracts\NoteContract;

class NoteRepo implements NoteContract
{
    protected $model;
    public function __construct(Note $model)
    {
        $this->model = $model;
    }

    public function index(){
        return $this->model::paginate(10);
    }

    public function store($data){
        return Note::create($data);
    }


    public function update($data,$model){
        $model->update($data);
        return $model->refresh();
    }

}
