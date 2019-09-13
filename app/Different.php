<?php

namespace App;

use App\Background;
use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;

class Different extends Model 
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'differents';
	public $timestamps = true;
	
	public function background()
    {
        return $this->belongsTo('App\Background');
    }  
	
	public static function isExist($background_id, $name){
		if (Different::where("background_id","=",$background_id)->where("name","=",$name)->count()>0){
			return true;
		}else{
			return false;
		}
	}
}
