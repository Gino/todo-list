<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskModel extends Model
{
    protected $table = 'tasks';

    public function list()
    {
        return $this->belongsTo('App\ListModel', 'task_id');
    }
}
