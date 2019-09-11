<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Story extends Model 
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stories';
	public $timestamps = true;
	
	public function characters()
    {
        return $this->hasMany('App\Character')->orderBy("name")->get();
    } 

	public function backgrounds()
    {
        return $this->hasMany('App\Background')->orderBy("name")->get();
    } 
	
	public function scenes()
    {
        return $this->hasMany('App\Scene')->orderBy("name")->get();
    } 
	
	public function things()
    {
        return $this->hasMany('App\Thing')->orderBy("name")->get();
    } 
	
	public function actions()
    {
        return $this->hasMany('App\Action')->orderBy("name")->get();
    } 
	
	public function musics()
    {
        return $this->hasMany('App\Music')->orderBy("name")->get();
    } 
	/*
	public function users()
    {
        return $this->belongsToMany('App\User',"user_group");
    } 
	*/
}
