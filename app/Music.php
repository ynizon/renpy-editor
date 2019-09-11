<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;

class Music extends Model 
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'musics';
	public $timestamps = true;
	
	public function story()
    {
        return $this->belongsTo('App\Story');
    }  
}
