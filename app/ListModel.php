<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListModel extends Model
{
    use SoftDeletes;

    protected $table = 'lists';

    public function tasks()
    {
        return $this->hasMany('App\TaskModel', 'list_id');
    }
}
