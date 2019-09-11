<?php

namespace App\Http\Controllers;
use Auth;
use DB;
use App\Story;
use App\Scene;
use App\Character;
use Illuminate\Http\Request;

class StoryController extends Controller
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

	public function create()
	{	
		$story = new Story();
		$method = "POST";
		return view('story/edit',compact('story','method'));
	}
	
	public function store(Request $request)
    {
		$story = new Story();
		$story = $this->save($story, $request);
		return redirect('home')->withOk("The story " . $story->name . " has been saved .");
    }
	
	public function show($id)
	{
		$story = Story::find($id);
		
		if (!is_dir("stories")){
			mkdir ("stories");
		}
		if (!is_dir("stories/".$id)){
			mkdir ("stories/".$id);
		}
		if (!is_dir("stories/".$id."/characters")){
			mkdir ("stories/".$id."/characters");
		}
		$characters = $story->characters();
		foreach ($characters as $character){
			$behaviours = $character->behaviours();
			foreach ($behaviours as $behaviour){
				if ($behaviour->picture != ""){
					try{
						$s = file_get_contents($behaviour->picture);
						$file = "stories/".$id."/characters/".$behaviour->name;
						file_put_contents($file,$s);
					}catch(\Exception $e){
					}
				}
			}
		}
		
		if (!is_dir("stories/".$id."/things")){
			mkdir ("stories/".$id."/things");
		}
		$things = $story->things();
		foreach ($things as $thing){
			if ($thing->picture != ""){
				try{
					$s = file_get_contents($thing->picture);
					$file = "stories/".$id."/things/".$thing->name;
					file_put_contents($file,$s);
				}catch(\Exception $e){
				}
			}
		}
		
		if (!is_dir("stories/".$id."/backgrounds")){
			mkdir ("stories/".$id."/backgrounds");
		}
		$backgrounds = $story->backgrounds();
		foreach ($backgrounds as $background){
			if ($background->picture != ""){
				try{
					$s = file_get_contents($background->picture);
					$file = "stories/".$id."/backgrounds/".$background->name;
					file_put_contents($file,$s);
				}catch(\Exception $e){
				}
			}
		}
		
		if (!is_dir("stories/".$id."/musics")){
			mkdir ("stories/".$id."/musics");
		}
		$musics = $story->musics();
		foreach ($musics as $music){
			if ($music->music != ""){
				try{
					$s = file_get_contents($music->music);
					$file = "stories/".$id."/musics/".$music->name;
					file_put_contents($file,$s);
				}catch(\Exception $e){
				}
			}
		}
		
		return view('story/show',compact('story'));
	}

	private function save($story, $request)
	{
		$inputs = $request->all();
		if (isset($inputs["name"])){
			$story->name = $inputs["name"];
		}
		$story->user_id = Auth::user()->id;
		$story->save();
		
		return $story;
	}
	
	public function edit(Request $request, $id)
	{	
		$story = Story::find($id);
		$method = "PUT";
		return view('story/edit',compact('story','method'));
	}
	
	public function update(Request $request, $id)
	{
		$story = Story::find($id);
		$story = $this->save($story, $request);
		return redirect('home')->withOk("The story " . $story->name . " has been saved .");
	}
	
	public function destroy($id)
	{	
		Story::destroy($id);
		return redirect()->back();
	}
	
	
	public function share($id, Request $request)
    {		
		$story = Story::find($id);
		if ($request->input("emails") != ""){
			try{
				$emails = explode("\r\n",$request->input("emails"));
				foreach ($emails as $email){
					DB::insert("insert into user_story (email, story_id) VALUES (?,?)", array($email, $id));
				}
			}catch(\Exception $e){
				
			}
		}
		$emails = DB::select("select * from user_story where story_id = ?", array($id));
        return view('story/share',compact('story','emails'));
    }
	
	public function duplicate($id)
    {	
		$story = Story::find($id);
		$new_story = $story->replicate();
		$new_story->name = "Copy of ". $new_story->name. "(".date("Y-m-d H:i:s").")";
		$new_story->save();
		
		$redirect = ["backgrounds"=>[],"musics"=>[],"things"=>[], "characters"=>[],"behaviours"=>[],"scenes"=>[]];
		foreach ($story->backgrounds() as $info){
			$new = $info->replicate();
			$new->story_id = $new_story->id;
			$new->save();
			$redirect["backgrounds"][$info->id] = $new->id;
		}
		
		foreach ($story->musics() as $info){
			$new = $info->replicate();
			$new->story_id = $new_story->id;
			$new->save();
			$redirect["musics"][$info->id] = $new->id;
		}
		
		foreach ($story->things() as $info){
			$new = $info->replicate();
			$new->story_id = $new_story->id;
			$new->save();
			$redirect["things"][$info->id] = $new->id;
		}
		
		foreach ($story->characters() as $info){
			$new = $info->replicate();
			$new->story_id = $new_story->id;
			$new->save();
			$redirect["characters"][$info->id] = $new->id;
			
			foreach ($info->behaviours() as $behaviour){
				$new_behaviour = $behaviour->replicate();
				$new_behaviour->story_id = $new_story->id;
				$new_behaviour->character_id = $new->id;
				$new_behaviour->save();
				$redirect["behaviours"][$behaviour->id] = $new_behaviour->id;
			}
		}
		
		foreach ($story->scenes() as $info){
			$new = $info->replicate();
			$new->story_id = $new_story->id;
			if ($info->parameters != ""){
				$new_json = json_decode($info->parameters,true);
				$new_json["background_id"] = $redirect["backgrounds"][$new_json["background_id"]];
				$new_json["music_id"] = $redirect["musics"][$new_json["music_id"]];
				$new_json["characters_id"] = [];
				foreach ($new_json["characters_id"] as $id){
					$new_json["characters_id"][] = $redirect["characters"][$id];
				}
				$new_json["things_id"] = [];
				foreach ($new_json["things_id"] as $id){
					$new_json["things_id"][] = $redirect["things"][$id];
				}
				
				$new->parameters = json_encode($new_json);
			}
			$new->save();
			$redirect["scenes"][$info->id] = $new->id;
		}
		
		foreach ($story->actions() as $info){
			$new = $info->replicate();
			$new->story_id = $new_story->id;
			if ($info->parameters != ""){
				$new_json = json_decode($info->parameters,true);
				$new_json["behaviour_id"] = $redirect["behaviours"][$new_json["behaviour_id"]];
				$new_json["scene_id"] = $redirect["scenes"][$new_json["scene_id"]];
				
				$new->parameters = json_encode($new_json);
			}
			$new->save();			
		}
		
        return redirect('home')->withOk("The story " . $story->name . " has been duplicated .");
    }
	
}
