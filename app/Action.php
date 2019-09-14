<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;

class Action extends Model 
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'actions';
	public $timestamps = true;
	
	public function story()
    {
        return $this->belongsTo('App\Story');
    }  
	
	public function getParams(){
		$all_params = ["different_id"=>0,"behaviour_id"=>0,"scene_id"=>0,"say"=>"","menu1"=>"","menu1_to"=>"","menu2"=>"","menu2_to"=>"","menu3"=>"","menu3_to"=>"","menu4"=>"","menu4_to"=>""];
		$params = [];
		if ($this->parameters != ""){
			$params = array_merge(json_decode($this->parameters,true),$params);
		}
		
		foreach ($all_params as $p=>$v){
			if (!isset($params[$p])){
				$params[$p] = $v;
			}
		}
		
		return $params;
	}
}
