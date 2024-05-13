<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'user_id','user_prpfile_id', 'note_text', 'file_upload',
   ];
   public $connection = 'mysql_T';
}
