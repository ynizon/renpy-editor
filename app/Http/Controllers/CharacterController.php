<?php

namespace App\Http\Controllers;

use App\Story;
use App\Scene;
use App\Character;
use App\Behaviour;
use Illuminate\Http\Request;
use Sunra\PhpSimple\HtmlDomParser;
use App\Providers\HelperServiceProvider as Helpers;

class CharacterController extends Controller
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

	public function index($story_id)
    {		
		$story = Story::find($story_id);
		if (Helpers::checkPermission($story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$characters = $story->characters();
        return view('character/index', compact("characters","story"));
    }
	
	public function create($story_id)
	{	
		$character = new Character();
		$character->color = "c822c8";
		$story = Story::find($story_id);
		if (Helpers::checkPermission($story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$method = "POST";
		return view('character/edit',compact('character','method','story'));
	}
	
	public function store(Request $request)
    {
		$character = new Character();
		$character = $this->save($character, $request);
		
		$behaviours = $character->behaviours();
		if (count($behaviours)==1){
			$behaviour = $behaviours->first();
			if ($behaviour->name == "default"){
				return redirect('behaviour/'.$behaviour->id.'/edit')->withOk("Please, upload a picture for your character.");
				exit();
			}
		}
		return redirect('story/'.$character->story_id.'/character')->withOk("The character " . $character->name . " has been saved .");
    }
	
	public function show($id)
	{
		$character = Character::find($id);
		if (Helpers::checkPermission($character->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		return view('character/show',compact('character'));
	}

	private function save($character, $request)
	{
		$inputs = $request->all();		
		if (isset($inputs["name"])){
			$character->name = $inputs["name"];			
		}
		if (isset($inputs["color"])){
			$character->color = str_replace("#","",$inputs["color"]);
		}
		
		if (isset($inputs["story_id"])){
			$character->story_id = $inputs["story_id"];
		}

		if (Helpers::checkPermission($character->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$character->save();
		
		if (isset($inputs["url_import"])){
			if ("" != $inputs["url_import"]){
				if (substr($inputs["url_import"],0,22) == "https://cloudnovel.net"){
					$str =  file_get_contents($inputs["url_import"]);
					$dom = HtmlDomParser::str_get_html($str);
					$elems = $dom->find("ul[id='responsive'] li img");
					foreach ($elems as $elem){
						$name = strtolower(basename($elem->src));
						
						if(!Behaviour::isExist($character->id,$name)){
							$behaviour = new Behaviour();
							$behaviour->name = $name;
							$behaviour->picture = $elem->src;
							$behaviour->character_id = $character->id;
							$behaviour->story_id = $character->story_id;
							$behaviour->save();
						}
					}
				}
			}
		}
		
		//Default behaviour
		$behaviours = $character->behaviours();
		if(!Behaviour::isExist($character->id,"Default") and count($behaviours)==0){
			$behaviour = new Behaviour();
			$behaviour->name = "default";
			$behaviour->picture = "";
			if (isset($inputs["picture"])){
				$behaviour->picture = $inputs["picture"];
			}
			
			//Upload file
			if ($request->file("picture_file") != ""){
				if (substr(strtolower($request->file("picture_file")->getClientOriginalName()),-4) == ".png"){					
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
					
					Storage::disk('public')->put("stories/".$behaviour->story_id."/images/".Helpers::encName($character->name)."/".Helpers::encName($behaviour->name).".png", file_get_contents($request->file("picture_file")));			
				}
			}
			$behaviour->character_id = $character->id;
			$behaviour->story_id = $character->story_id;
			$behaviour->save();
		}
		
		return $character;
	}
	
	public function edit(Request $request, $id)
	{	
		$character = Character::find($id);
		if (Helpers::checkPermission($character->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$story = Story::find($character->story_id);
		$method = "PUT";
		return view('character/edit',compact('character','story','method'));
	}
	
	public function update(Request $request, $id)
	{		
		$character = Character::find($id);
		if (Helpers::checkPermission($character->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$character = $this->save($character, $request);
		
		$behaviours = $character->behaviours();
		if (count($behaviours)==1){
			$behaviour = $behaviours->first();
			if ($behaviour->name == "default"){
				return redirect('behaviour/'.$behaviour->id.'/edit')->withOk("Please, upload a picture for your character.");
				exit();
			}
		}
		return redirect('story/'.$character->story_id.'/character')->withOk("The character " . $character->name . " has been saved .");
	}
	
	public function destroy($character_id)
	{	
		$character = Character::find($character_id);
		if (Helpers::checkPermission($character->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		Character::destroy($character_id);
		return redirect()->back();
	}	
}
