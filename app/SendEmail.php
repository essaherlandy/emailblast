<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendEmail extends Model
{
    protected $fillable = ['title','description','club','attachment'];
}
