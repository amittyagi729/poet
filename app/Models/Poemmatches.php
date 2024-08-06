<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poemmatches extends Model
{
    use HasFactory;
   
    protected $table = 'poems_matches';


 public function poem(){

    	return $this->belongsTo(Poem::class, 'user_poem_id', 'id', 'match_poem_id');
    }


}
