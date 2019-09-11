<?php

namespace App\Http\Controllers;

use App\Story;
use App\Scene;
use App\Character;
use App\Behaviour;
use Illuminate\Http\Request;
use Sunra\PhpSimple\HtmlDomParser;

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
		$characters = $story->characters();
        return view('character/index', compact("characters","story"));
    }
	
	public function create($story_id)
	{	
		$character = new Character();
		$story = Story::find($story_id);
		$method = "POST";
		return view('character/edit',compact('character','method','story'));
	}
	
	public function store(Request $request)
    {
		$character = new Character();
		$character = $this->save($character, $request);
		return redirect('story/'.$character->story_id.'/character')->withOk("The character " . $character->name . " has been saved .");
    }
	
	public function show($id)
	{
		$character = Character::find($id);
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
		
		//TODO: check permission
		if (isset($inputs["story_id"])){
			$character->story_id = $inputs["story_id"];
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
		if(!Behaviour::isExist($character->id,"Default")){
			$behaviour = new Behaviour();
			$behaviour->name = "default";
			$behaviour->picture = "";
			$behaviour->character_id = $character->id;
			$behaviour->story_id = $character->story_id;
			$behaviour->save();
		}
		
		return $character;
	}
	
	public function edit(Request $request, $id)
	{	
		$character = Character::find($id);
		$story = Story::find($character->story_id);
		$method = "PUT";
		return view('character/edit',compact('character','story','method'));
	}
	
	public function update(Request $request, $id)
	{		
		$character = Character::find($id);
		$character = $this->save($character, $request);
		return redirect('story/'.$character->story_id.'/character')->withOk("The character " . $character->name . " has been saved .");
	}
	
	public function destroy($character_id)
	{	
		Character::destroy($character_id);
		return redirect()->back();
	}	
}
