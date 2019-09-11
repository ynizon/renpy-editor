<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;

class Scene extends Model 
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'scenes';
	public $timestamps = true;
	
	public function story()
    {
        return $this->belongsTo('App\Story');
    }  
	
	public function getParams(){
		$all_params = ["background_id"=>0,"music_id"=>0,"characters_id"=>[],"things_id"=>[]];
		$params = [];
		if ($this->parameters != ""){
			$params = json_decode($this->parameters,true);
		}
		foreach ($all_params as $p=>$v){
			if (!isset($params[$p])){
				$params[$p] = $v;
			}
		}
		
		return $params;
	}
}
