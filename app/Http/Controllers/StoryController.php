<?php

namespace App\Http\Controllers;
use View;
use Auth;
use DB;
use App\Story;
use App\Scene;
use App\ResizeImage;
use App\Character;
use Illuminate\Http\Request;
use App\Providers\HelperServiceProvider as Helpers;

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
		$story->width = 1280;
		$story->height = 720;		
		$method = "POST";
		return view('story/edit',compact('story','method'));
	}
	
	public function store(Request $request)
    {
		$story = new Story();
		$story = $this->save($story, $request);
		
		if (0 == count($story->scenes())){
			$scene = new Scene();
			$scene->name = "001-Start";
			$scene->story_id = $story->id;
			$scene->noremove = 1;
			$scene->parameters = json_encode($scene->getParams());
			$scene->save();
			
			$scene = new Scene();
			$scene->name = "999-End";
			$scene->story_id = $story->id;
			$scene->noremove = 1;
			$scene->parameters = json_encode($scene->getParams());
			$scene->save();
		}
		
		return redirect('home')->withOk("The story " . $story->name . " has been saved .");
    }
	
	public function show($id)
	{
		$story = Story::find($id);
		$errors = [];
		if (!is_dir("stories")){
			mkdir ("stories");
		}
		if (!is_dir("stories/".$id)){
			mkdir ("stories/".$id);
		}
		if (!is_dir("stories/".$id."/images")){
			mkdir ("stories/".$id."/images");
		}
		$characters = $story->characters();
		foreach ($characters as $character){
			if (!is_dir("stories/".$id."/images/".Helpers::encName($character->name))){
				mkdir ("stories/".$id."/images/".Helpers::encName($character->name));
			}
			$behaviours = $character->behaviours();
			foreach ($behaviours as $behaviour){
				$bOk = false;
				if ($behaviour->picture != ""){
					try{
						$s = file_get_contents($behaviour->picture);
						$file = "stories/".$id."/images/".Helpers::encName($character->name)."/".Helpers::encName(basename($behaviour->picture));
						file_put_contents($file,$s);
						$bOk = true;
					}catch(\Exception $e){
						
					}
				}
				if (!$bOk){
					$errors["/story/".$character->story_id."/character/".$character->id."/behaviour"] =  $character->name. ">".$behaviour->name ." doesn't have a valid picture.";
				}
			}
		}
		
		if (!is_dir("stories/".$id."/images/things")){
			mkdir ("stories/".$id."/images/things");
		}
		$things = $story->things();
		foreach ($things as $thing){
			$bOk = false;
			if ($thing->picture != ""){
				try{
					$s = file_get_contents($thing->picture);
					$file = "stories/".$id."/images/things/".Helpers::encName(basename($thing->picture));
					file_put_contents($file,$s);
					$bOk = true;
				}catch(\Exception $e){
				}
			}
			if (!$bOk){
				$errors["/thing/".$thing->id."/edit"] = $thing->name ." doesn't have a valid picture.";
			}
		}
		
		if (!is_dir("stories/".$id."/images")){
			mkdir ("stories/".$id."/images");
		}
		$backgrounds = $story->backgrounds();
		foreach ($backgrounds as $background){
			$bOk = false;
			if ($background->picture != ""){
				try{
					$s = file_get_contents($background->picture);
					$file = "stories/".$id."/images/".Helpers::encName(basename($background->picture));
					file_put_contents($file,$s);
					
					$pic = new ResizeImage($file);
					$pic->resizeTo($story->width,$story->height);
					$pic->saveImage($file);
					$bOk = true;
				}catch(\Exception $e){
				}
			}
			if (!$bOk){
				$errors["/background/".$background->id."/edit"] = $background->name ." doesn't have a valid picture.";
			}
		}
		
		$musics = $story->musics();
		foreach ($musics as $music){
			$bOk = false;
			if ($music->music != ""){
				try{
					$s = file_get_contents($music->music);
					$file = "stories/".$id."/".Helpers::encName(basename($music->music));
					file_put_contents($file,$s);
					$bOk = true;
				}catch(\Exception $e){
				}
			}
			if (!$bOk){
				$errors["/music/".$music->id."/edit"] = $music->name ." doesn't have a valid music.";
			}
		}
		
		define("TAB","    ");
		
		
		$script = View::make('story/script', compact('story','TAB'))->render();
		file_put_contents("stories/".$id."/script.rpy",$script);
		
		return view('story/show',compact('story','TAB','errors'));
	}

	private function save($story, $request)
	{
		$inputs = $request->all();
		if (isset($inputs["name"])){
			$story->name = $inputs["name"];
		}
		if (isset($inputs["width"])){
			$story->width = $inputs["width"];
		}
		if (isset($inputs["height"])){
			$story->height = $inputs["height"];
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
				$new_json["backgrounds_id"] = [];
				foreach ($new_json["backgrounds_id"] as $id){
					$new_json["backgrounds_id"][] = $redirect["backgrounds"][$id];
				}
				$new_json["musics_id"] = [];
				foreach ($new_json["musics_id"] as $id){
					$new_json["musics_id"][] = $redirect["musics"][$id];
				}
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
			$new->scene_id = $redirect["scenes"][$info->scene_id];
			if ($info->parameters != ""){
				$new_json = json_decode($info->parameters,true);
				$element = $new_json["element"];
				if ($new_json["subject_id"] != 0){
					$new_json["subject_id"] = $redirect[$element."s"][$new_json["subject_id"]];
				}
				
				$new->parameters = json_encode($new_json);
			}
			$new->save();			
		}
		
        return redirect('home')->withOk("The story " . $story->name . " has been duplicated .");
    }
	
}
