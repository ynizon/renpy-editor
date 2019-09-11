<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;

class Thing extends Model 
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'things';
	public $timestamps = true;
	
}
