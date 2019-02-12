<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskModel extends Model
{
    use SoftDeletes;

    protected $table = 'tasks';

    public function list()
    {
        return $this->belongsTo('App\ListModel', 'task_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
