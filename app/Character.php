<?php

namespace App;

use App\Behaviour;
use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;

class Character extends Model 
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'characters';
	public $timestamps = true;
	
	public function behaviours()
    {
		return $this->hasMany('App\Behaviour')->get();
    }  
	
	public function story()
    {
        return $this->belongsTo('App\Story')->get();
    }  
		
}
