<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserItem extends Model
{
    use SoftDeletes;
    public $table = 'user_items';

    public $guarded = [];
}
