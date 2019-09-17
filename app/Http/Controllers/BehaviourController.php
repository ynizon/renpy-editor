<?php

namespace App\Http\Controllers;

use App\Action;
use App\Story;
use App\Scene;
use App\Behaviour;
use App\Character;
use Illuminate\Http\Request;
use App\Providers\HelperServiceProvider as Helpers;
use Storage;

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
		if (Helpers::checkPermission($character->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$behaviours = $character->behaviours();
        return view('behaviour/index', compact("behaviours","story","character"));
    }
	
	public function create($story_id, $character_id)
	{
		$behaviour = new Behaviour();
		$character = Character::find($character_id);
		if (Helpers::checkPermission($character->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
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
	
	private function save($behaviour, $request)
	{
		$inputs = $request->all();		
		if (isset($inputs["name"])){
			$behaviour->name = $inputs["name"];			
		}
		
		$name = str_replace(".png","",$behaviour->name);
		$name = str_replace(".gif","",$name);
		$name = str_replace(".jpg","",$name);
		$name = str_replace(".jpeg","",$name);
		$behaviour->name = $name;
				
		$behaviour->picture = "";
		if (isset($inputs["picture"])){
			$behaviour->picture = $inputs["picture"];
		}
		
		if (isset($inputs["story_id"])){
			$behaviour->story_id = $inputs["story_id"];
		}
		if (isset($inputs["character_id"])){
			$behaviour->character_id = $inputs["character_id"];
		}

		if (Helpers::checkPermission($behaviour->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		
		//Upload file
		if ($request->file("picture_file") != ""){
			$extension = substr(strtolower($request->file("picture_file")->getClientOriginalName()),-4);
               if (in_array($extension, [".gif",".jpg",".png"])){
				$character = Character::find($behaviour->character_id);
				if (!is_dir("stories")){
					mkdir ("stories");
				}
				if (!is_dir("stories/".$behaviour->story_id)){
					mkdir ("stories/".$behaviour->story_id);
				}
				if (!is_dir("stories/".$behaviour->story_id."/images")){
					mkdir ("stories/".$behaviour->story_id."/images");
				}
				
				if (!is_dir("stories/".$behaviour->story_id."/images/".Helpers::encName($character->name))){
					mkdir ("stories/".$behaviour->story_id."/images/".Helpers::encName($character->name));
				}
				
				Storage::disk('public')->put("stories/".$behaviour->story_id."/images/".Helpers::encName($character->name)."/".Helpers::encName($behaviour->name).$extension, file_get_contents($request->file("picture_file")));			
				$behaviour->picture = env("APP_URL")."/stories/".$behaviour->story_id."/images/".Helpers::encName($character->name)."/".Helpers::encName($behaviour->name).$extension;
			}else{
                    $behaviour->picture = "";
               }
		}
		
		$behaviour->save();
		
		return $behaviour;
	}
	
	public function edit(Request $request, $id)
	{	
		$behaviour = Behaviour::find($id);
		$character = Character::find($behaviour->character_id);
		if (Helpers::checkPermission($character->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$story = Story::find($behaviour->story_id);
		$method = "PUT";
		return view('behaviour/edit',compact('behaviour','story','method','character'));
	}
	
	public function update(Request $request, $id)
	{		
		$behaviour = Behaviour::find($id);
		if (Helpers::checkPermission($behaviour->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$behaviour = $this->save($behaviour, $request);
		return redirect('story/'.$behaviour->story_id.'/character/'.$behaviour->character_id.'/behaviour')->withOk("The behaviour " . $behaviour->name . " has been saved .");
	}
	
	public function destroy($behaviour_id)
	{	
		$behaviour = Behaviour::find($behaviour_id);
		if (Helpers::checkPermission($behaviour->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		Behaviour::destroy($behaviour_id);
		return redirect()->back();
	}	
	
	public function show (Request $request, $story_id, $character_id){
		$character = Character::find($character_id);
		if (Helpers::checkPermission($character->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
          $behaviour_id = 0;
          if ($request->input("action_id") != "0"){
               $action = Action::find($request->input("action_id"));
               if ($action->parameters != ""){
                    $params = json_decode($action->parameters,true);
                    $behaviour_id = $params["info"];
               }
          }
		$behaviours = $character->behaviours();
		return view('behaviour/show',compact('behaviours','behaviour_id'));
	}
}
