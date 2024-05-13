<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id','first_name', 'last_name', 'address',
   ];
   public $connection = 'mysql_S';

}
