<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;

class Background extends Model 
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'backgrounds';
	public $timestamps = true;
	
	public function story()
    {
        return $this->belongsTo('App\Story');
    }

	public function differents()
    {
		return $this->hasMany('App\Different')->get();
    }  
}
