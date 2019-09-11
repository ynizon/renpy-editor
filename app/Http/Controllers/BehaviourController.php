<?php

namespace App\Http\Controllers;

use App\Story;
use App\Scene;
use App\Behaviour;
use App\Character;
use Illuminate\Http\Request;

class BehaviourController extends Controller
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


	public function index($story_id, $character_id)
    {		
		$story = Story::find($story_id);
		$character = Character::find($character_id);
		$behaviours = $character->behaviours();
        return view('behaviour/index', compact("behaviours","story","character"));
    }
	
	public function create($story_id, $character_id)
	{
		$behaviour = new Behaviour();
		$character = Character::find($character_id);
		$story = Story::find($character->story_id);
		$method = "POST";
		return view('behaviour/edit',compact('behaviour','method','story','character'));
	}
	
	public function store(Request $request)
    {
		$behaviour = new Behaviour();
		$behaviour = $this->save($behaviour, $request);
		return redirect('story/'.$behaviour->story_id.'/character/'.$behaviour->character_id.'/behaviour')->withOk("The behaviour " . $behaviour->name . " has been saved .");
    }
	
	public function show($id)
	{
		$behaviour = Behaviour::find($id);
		return view('behaviour/show',compact('behaviour'));
	}

	private function save($behaviour, $request)
	{
		$inputs = $request->all();		
		if (isset($inputs["name"])){
			$behaviour->name = $inputs["name"];			
		}
		if (isset($inputs["picture"])){
			$behaviour->picture = $inputs["picture"];
		}
		
		//TODO: check permission
		if (isset($inputs["story_id"])){
			$behaviour->story_id = $inputs["story_id"];
		}
		if (isset($inputs["character_id"])){
			$behaviour->character_id = $inputs["character_id"];
		}

		$behaviour->save();
		
		return $behaviour;
	}
	
	public function edit(Request $request, $id)
	{	
		$behaviour = Behaviour::find($id);
		$character = Character::find($behaviour->character_id);
		$story = Story::find($behaviour->story_id);
		$method = "PUT";
		return view('behaviour/edit',compact('behaviour','story','method','character'));
	}
	
	public function update(Request $request, $id)
	{		
		$behaviour = Behaviour::find($id);
		$behaviour = $this->save($behaviour, $request);
		return redirect('story/'.$behaviour->story_id.'/character/'.$behaviour->character_id.'/behaviour')->withOk("The behaviour " . $behaviour->name . " has been saved .");
	}
	
	public function destroy($behaviour_id)
	{	
		Behaviour::destroy($behaviour_id);
		return redirect()->back();
	}	
}
