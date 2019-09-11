<?php

namespace App;

use APp\Character;
use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;

class Behaviour extends Model 
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'behaviours';
	public $timestamps = true;
	
	public function character()
    {
        return $this->belongsTo('App\Character');
    }  
	
	public static function isExist($character_id, $name){
		if (Behaviour::where("character_id","=",$character_id)->where("name","=",$name)->count()>0){
			return true;
		}else{
			return false;
		}
	}
}
