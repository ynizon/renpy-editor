<?php

namespace App;

use App\Action;
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
		$all_params = ["backgrounds_id"=>[],"musics_id"=>[],"characters_id"=>[],"things_id"=>[]];
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
	
	public function actions()
    {
        return $this->hasMany('App\Action')->orderBy("num_order")->get();
    }  
}
