<?php

namespace App\Http\Controllers;
use View;
use Auth;
use DB;
use Storage;
use App\Story;
use App\Thing;
use App\Background;
use App\Behaviour;
use App\Music;
use App\Scene;
use App\Different;
use App\Action;
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
          $story->lang = 'United Kingdom';
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
			$params = $scene->getParams();
			foreach ($params as $param=>$tab){
				$params[$param][] = 0;
			}
			
			$scene->name = "001-Start";
			$scene->parameters = json_encode($params);
			$scene->story_id = $story->id;
			$scene->noremove = 1;			
			$scene->save();
			
			$scene = new Scene();
			$scene->name = "999-End";
			$scene->story_id = $story->id;
			$scene->noremove = 1;
			$scene->parameters = json_encode($params);
			$scene->save();
			
			$action = new Action();
			$action->story_id = $story->id;
			$action->scene_id = $scene->id;
			$action->name = "game end";
			$action->num_order = 1;
			$action->parameters = '{"element":"game","subject_id":"0","verb":"end","info":null,"action_id":"0"}';
			$action->save();
		}
		
		return redirect('home')->withOk("The story " . $story->name . " has been saved .");
    }
	
	public function show($id)
	{
		$story = Story::find($id);
		if (Helpers::checkPermission($id) == false){
			return view('errors/403',  array());
			exit();		
		}
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
          
          //For resize
          $height_max = $story->height*69/100;
          $height_max_thing = $story->height*28/100;
          
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
						$file = "stories/".$id."/images/".Helpers::encName($character->name)."/".Helpers::encName(basename($behaviour->picture));
                              
						if (!file_exists($file)){
							$s = file_get_contents($behaviour->picture);
							file_put_contents($file,$s);
                                   $image_info = getimagesize($file);    
                                   $image_info["height"] = $image_info[1];
                                   $image_info["width"] = $image_info[0];
                                   if ($image_info["height"]>($height_max)){
                                        $new_height = $height_max;
                                        $new_width = $height_max/$image_info["height"]*$image_info["width"];
                                        $pic = new ResizeImage($file);
                                        $pic->resizeTo($new_width,$new_height);
                                        $pic->saveImage($file);
                                   }
						}
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
					$file = "stories/".$id."/images/things/".Helpers::encName(basename($thing->picture));
					if (!file_exists($file)){
						$s = file_get_contents($thing->picture);					
						file_put_contents($file,$s);
                              
                              $image_info = getimagesize($file);                                   
                              $image_info["height"] = $image_info[1];
                              $image_info["width"] = $image_info[0];
                              if ($image_info["height"]>($height_max)){
                                   $new_height = $height_max_thing;
                                   $new_width = $height_max_thing/$image_info["height"]*$image_info["width"];
                                   $pic = new ResizeImage($file);
                                   $pic->resizeTo($new_width,$new_height);
                                   $pic->saveImage($file);
                              }
					}					
					$bOk = true;
				}catch(\Exception $e){
				}
			}else{
				$file = "stories/".$id."/images/things/".Helpers::encName(basename($thing->name)).".png";
				if (file_exists($file)){
					$bOk = true;
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
			$differents = $background->differents();
			foreach ($differents as $different){
				$bOk = false;
				
				if ($different->picture != ""){
					try{
						$file = "stories/".$id."/images/".Helpers::encName($background->name)."-".Helpers::encName(basename($different->picture));
						if (!file_exists($file)){
							$s = file_get_contents($different->picture);						
							file_put_contents($file,$s);
						}
						
						$pic = new ResizeImage($file);
						$pic->resizeTo($story->width,$story->height);
						$pic->saveImage($file);						
						$bOk = true;
					}catch(\Exception $e){
						
					}
				}
				if (!$bOk){
					$errors["/story/".$background->story_id."/background/".$background->id."/different"] =  $background->name. ">".$different->name ." doesn't have a valid picture.";
				}
			}			
		}
		
		$musics = $story->musics();
		foreach ($musics as $music){
			$bOk = false;
			if ($music->music != ""){
				try{					
					$file = "stories/".$id."/".Helpers::encName(basename($music->music));
					if (!file_exists($file)){
						$s = file_get_contents($music->music);
						file_put_contents($file,$s);
					}
					$bOk = true;
				}catch(\Exception $e){
				}
			}else{
				$file = "stories/".$id."/".Helpers::encName(basename($music->name)).".ogg";
				if (file_exists($file)){
					$bOk = true;
				}
			}
			if (!$bOk){
				$errors["/music/".$music->id."/edit"] = $music->name ." doesn't have a valid music.";
			}
		}
		
		//Checking ressources
		$tabScenes = [];
		$scenes = $story->scenes();
		foreach ($scenes as $scene){
			if (in_array($scene->name, $tabScenes)){
				$errors["/story/".$scene->story_id."/scene"] = "Scene name (".$scene->name.") is duplicated.";
			}else{
				$tabScenes[] = $scene->name;
			}
			$actions = $scene->actions();
			foreach ($actions as $action){
				$action_params = $action->getParams();
				switch ($action_params["element"]){
					case "background":
						$background = Background::find($action_params["subject_id"]);
						if ($background == null){
							$errors["/scene/".$scene->id."/edit?rnd=background".$action_params["subject_id"]] = $action->name." is not valid (deleted resources).";
						}else{
							switch ($action_params["verb"]){
								case "show":
									$different = Different::find($action_params["info"]);
									if ($different == null){
										$errors["/scene/".$scene->id."/edit?rnd=different".$action_params["info"]] = $action->name." is not valid (deleted resources).";
									}
									break;
							}
						}
						break;
						
					case "thing":
						$thing = Thing::find($action_params["subject_id"]);
						if ($thing == null){
							$errors["/scene/".$scene->id."/edit?rnd=thing".$action_params["subject_id"]] = $action->name." is not valid (deleted resources).";
						}
						break;
						
					case "music":
						$music = Music::find($action_params["subject_id"]);
						if ($music == null){
							$errors["/scene/".$scene->id."/edit?rnd=music".$action_params["subject_id"]] = $action->name." is not valid (deleted resources).";
						}
						break;
						
					case "character":
                              if ($action_params["subject_id"] != 0){
                                   $character = Character::find($action_params["subject_id"]);
                                   if ($character == null){
                                        $errors["/scene/".$scene->id."/edit?rnd=character".$action_params["subject_id"]] = $action->name." is not valid (deleted resources).";
                                   }else{
                                        switch ($action_params["verb"]){
                                             case "show":
                                                  $behaviour = Behaviour::find($action_params["info"]);
                                                  if ($behaviour == null){
                                                       $errors["/scene/".$scene->id."/edit?rnd=behaviour".$action_params["info"]] = $action->name." is not valid (deleted resources).";
                                                  }
                                                  break;
                                        }
                                   }
                              }
				}
			}
		}
		
		$TAB = "    ";
		
		if (count($errors)==0){
			$script = View::make('story/script', compact('story','TAB'))->render();
			file_put_contents("stories/".$id."/script.rpy",$script);
			
			$zip = new \ZipArchive();
			$zip->open("stories/".$story->id.".zip", \ZIPARCHIVE::CREATE);
			$zip = Helpers::zip_r("stories/".$story->id, $zip,"story-".$story->id);
			$zip->close();
		}
		
		return view('story/show',compact('story','TAB','errors'));
	}

	private function save($story, $request)
	{		
		if ($story->id != 0 and Helpers::checkPermission($story->id) == false){
			return view('errors/403',  array());
			exit();		
		}
		
		$inputs = $request->all();
		if (isset($inputs["name"])){
			$story->name = $inputs["name"];
		}
		if (isset($inputs["lang"])){
			$story->lang = $inputs["lang"];
		}
          if (isset($inputs["width"])){
			$story->width = $inputs["width"];
		}
		if (isset($inputs["height"])){
			$story->height = $inputs["height"];
		}
          
          $story->picture = "";
		if (isset($inputs["picture"])){
			$story->picture = $inputs["picture"];
		}
          
		$story->starting_script = "";
		if (isset($inputs["starting_script"])){
			$story->starting_script = $inputs["starting_script"];
		}
		$story->user_id = Auth::user()->id;
		$story->save();
		
          //Upload file
		if ($request->file("picture_file") != ""){
			$extension = substr(strtolower($request->file("picture_file")->getClientOriginalName()),-4);
               if (in_array($extension, [".png"])){
				if (!is_dir("stories")){
					mkdir ("stories");
				}
				if (!is_dir("stories/".$story->id)){
					mkdir ("stories/".$story->id);
				}
				
				if (!is_dir("stories/".$story->id."/gui")){
					mkdir ("stories/".$story->id."/gui");
				}
				
                $file = "stories/".$story->id."/gui/main_menu.png";
				Storage::disk('public')->put($file, file_get_contents($request->file("picture_file")));			
				$story->picture = env("APP_URL")."/stories/".$story->id."/gui/main_menu.png";

				$pic = new ResizeImage($file);
				$pic->resizeTo($story->width,$story->height);
				$pic->saveImage($file);
                    
			}else{
                    $story->picture = "";
               }
               $story->save();
		}
          
		return $story;
	}
	
	public function edit(Request $request, $id)
	{	
		$story = Story::find($id);
		if (Helpers::checkPermission($id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$method = "PUT";
		return view('story/edit',compact('story','method'));
	}
	
	public function update(Request $request, $id)
	{
		if (Helpers::checkPermission($id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$story = Story::find($id);
		$story = $this->save($story, $request);
		return redirect('home')->withOk("The story " . $story->name . " has been saved .");
	}
	
	public function destroy($id)
	{	
		if (Helpers::checkPermission($id) == false){
			return view('errors/403',  array());
			exit();		
		}
          Helpers::deleteAll("stories/".$id);
          
		Story::destroy($id);
		return redirect()->back();
	}
	
	
	public function share($id, Request $request)
    {		
		if (Helpers::checkPermission($id) == false){
			return view('errors/403',  array());
			exit();		
		}
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
		if (Helpers::checkPermission($id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$story = Story::find($id);
		$new_story = $story->replicate();
		$new_story->name = "Copy of ". $new_story->name. "(".date("Y-m-d H:i:s").")";
		$new_story->save();
		
		$redirect = ["backgrounds"=>[],"differents"=>[],"musics"=>[],"things"=>[], "characters"=>[],"behaviours"=>[],"scenes"=>[]];
		foreach ($story->backgrounds() as $info){
			$new = $info->replicate();
			$new->story_id = $new_story->id;
			$new->save();
			$redirect["backgrounds"][$info->id] = $new->id;
			
			foreach ($info->differents() as $different){
				$new_different = $different->replicate();
				$new_different->story_id = $new_story->id;
				$new_different->character_id = $new->id;
				$new_different->save();
				$redirect["differents"][$different->id] = $new_different->id;
			}
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
	
     /* Use for tree only */
     private function getRecursAction($iLevel, $scene, &$all, &$scenes_dones){
          $to_do = [];
          
          $actions = $scene->actions();
          foreach ($actions as $action){
               if ($action->parameters != ""){
                    $action_params = json_decode($action->parameters,true);
                    switch ($action_params["verb"]){
                         case "iftrue":
                         case "jump":
                              $goto_scene = Scene::find($action_params["info"]);                                                                 
                              $to_do[$goto_scene->id] = $goto_scene;
                              $all[$iLevel][$scene->id]["from_to"][] = ["scene_".$scene->id,"scene_".$goto_scene->id];                                   
                              $all[$iLevel][$scene->id]["scenes"][$goto_scene->id] = $goto_scene->id;
                              break;
                              
                         case "menu":
                              $actions_params = json_decode($action_params["info"],true);
                              
                              for ($k=1;$k<=config("app.max_menu_choice"); $k++){                                        
                                   if ($actions_params["menu".$k."_to"] != 0){
                                        $goto_scene = Scene::find($actions_params["menu".$k."_to"]);
                                        $goto_id = $goto_scene->id;
                                        
                                        $to_do[$goto_scene->id] = $goto_scene;                                        
                                        $all[$iLevel][$scene->id]["from_to"][] = ["scene_".$scene->id,"scene_".$goto_id];
                                        $all[$iLevel][$scene->id]["scenes"][$goto_id] = $goto_id;
                                   }
                              }
                              break;
                    }
               }
          }

          return $to_do;               
     }
     
     /* Show decision making tree */
     public function tree(Request $request, $id)
	{
		if (Helpers::checkPermission($id) == false){
			return view('errors/403',  array());
			exit();		
		}
          
         
		$story = Story::find($id);
		
          //Init
          $scenesTmp = $story->scenes();
          
          
          
          //Get the starting scene
          $iLevel = 1;
          $scenes = [];
          $all = [];
          $scenes_dones = [];
          $scene = $scenesTmp->first();
          
          $to_do = $this->getRecursAction($iLevel, $scene, $all, $scenes_dones);
          $scenes_dones[] = $scene->id;
          
          //Get jump and menu actions (go to)
          while (isset($all[$iLevel])){
               $iLevel++;
               foreach ($to_do as $scene_id=>$scene){
                    $to_do = $this->getRecursAction($iLevel, $scene, $all, $scenes_dones);
               }          
               foreach ($all[($iLevel-1)] as $scene_id=>$scene){
                    foreach ($scene["scenes"] as $x_id){
                         $scenes_dones[] = $x_id;
                    }
               }
          }
          
          //Create scenes and from_to links
          $iMaxLevel = $iLevel;
          $trees = [];
          $b = true;
          $from_to = [];
          $scenes = [];
          $iLevel=0;
          while ($iLevel < $iMaxLevel){               
               $iLevel++;
               if (isset($all[$iLevel])){
                    foreach ($all[$iLevel] as $scene_id => $info){
                         $scene = Scene::find($scene_id);
                         $scenes[$scene_id] = ["id"=>"scene_".$scene->id,"name"=>"<a style='color:#fff' target='_blank' href='/scene/".$scene->id."/edit'>".$scene->name."</a>","image"=>$scene->getThumbnail(),"description"=>count($info["from_to"]),"color"=>"#".Helpers::random_color(false)];
                         foreach ($info["from_to"] as $from){
                              $from_to[] = $from;
                         }
                         
                         foreach ($info["scenes"] as $scene_id){
                              $scene = Scene::find($scene_id);
                              $scenes[$scene_id] = ["id"=>"scene_".$scene->id,"name"=>"<a style='color:#fff' target='_blank' href='/scene/".$scene->id."/edit'>".$scene->name."</a>","image"=>$scene->getThumbnail(),"description"=>"","color"=>"#".Helpers::random_color(false)];
                         }
                    }
               }
          }
          
          //Complete description with links for scenes which have been already created
          $scenes_data = [];
          foreach ($scenes as $scene_id=>$sceneTmp){               
               if ($sceneTmp["description"] > 0){
                    $sceneTmp["description"] = "";
               }else{
                    $sceneTmp["description"] = "";
                    $scene = Scene::find($scene_id);
                    $actions = $scene->actions();
                    foreach ($actions as $action){
                         if ($action->parameters != ""){
                              $action_params = json_decode($action->parameters,true);
                              switch ($action_params["verb"]){
                                   case "jump":
                                   case "iftrue":
                                        $goto_scene = Scene::find($action_params["info"]);
                                        $sceneTmp["description"] .= "<li><a target='_blank' style='color:#ddd;' href='/scene/".$goto_scene->id."/edit'>".$goto_scene->name."</a></li>";
                                        break;
                                        
                                   case "menu":
                                        $actions_params = json_decode($action_params["info"],true);

                                        for ($k=1;$k<=config("app.max_menu_choice"); $k++){                                        
                                             if ($actions_params["menu".$k."_to"] != 0){
                                                  $goto_scene = Scene::find($actions_params["menu".$k."_to"]);
                                                  $sceneTmp["description"] .= "<li><a target='_blank' style='color:#ddd;' href='/scene/".$goto_scene->id."/edit'>".$goto_scene->name."</a></li>";
                                             }
                                        }
                                        break;
                              }
                         }                         
                    }
                    if ($sceneTmp["description"] != ""){
                         $sceneTmp["description"] = "<ul>".$sceneTmp["description"] . "</ul>";
                    }
                    
               }
               $scenes_data[] = $sceneTmp;
          }
         
          $highcharts = true;
		return view('story/tree',compact('story','highcharts','from_to','scenes_data'));
	}
}
