<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use App\Story;
use App\Background;
use App\Scene;
use App\Thing;
use App\Action;
use App\Character;
use App\Music;
use App\Behaviour;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		$stories = [];
		$lst = Story::where("user_id","=",Auth::user()->id)->get();
		foreach ($lst as $l){
			$stories[$l->id] = $l;
		}
		//We add hare stories
		$lst = DB::select ("select * from user_story where email = ? ", array(Auth::user()->email));
		foreach ($lst as $l){
			$stories[$l->id]= Story::find($l->id);
		}
        return view('story/index', compact("stories"));
    }	
}
