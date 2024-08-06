<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_templates';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

}