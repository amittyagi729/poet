<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poem extends Model
{
    use HasFactory;
     use SoftDeletes;
    protected $dates = ['deleted_at'];
      protected $table = 'poems';

      public function favouritepoem(){

    		return $this->hasOne(Poem::class, 'user_id', 'id');

    }
         

     public function matchespoem(){

    		return $this->hasOne(Poem::class, 'user_id', 'id');

         }

}
