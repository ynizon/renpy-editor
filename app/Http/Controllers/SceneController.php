<?php

namespace App\Http\Controllers;

use App\Story;
use App\Background;
use App\Character;
use App\Different;
use App\Behaviour;
use App\Music;
use App\Thing;
use App\Scene;
use Illuminate\Http\Request;
use App\Providers\HelperServiceProvider as Helpers;

class SceneController extends Controller
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

		$scenes = $story->scenes();
        return view('scene/index', compact("scenes","story"));
    }
	
	public function create($story_id)
	{	
		$scene = new Scene();
          $params = $scene->getParams();
          
          //On prend tout par defaut
          foreach ($params as $key=>$value){
               switch ($key){
                    case "backgrounds_id":
                         foreach (Background::where("story_id","=",$story_id)->get() as $info){                              
                              $params[$key][] = $info->id;
                         }
                         break;
                    case "musics_id":
                         foreach (Music::where("story_id","=",$story_id)->get() as $info){
                              $params[$key][] = $info->id;
                         }
                    case "characters_id":
                         foreach (Character::where("story_id","=",$story_id)->get() as $info){
                              $params[$key][] = $info->id;
                         }
                         break;
                    case "things_id":
                         foreach (Thing::where("story_id","=",$story_id)->get() as $info){
                              $params[$key][] = $info->id;
                         }
                         break;
               }
               
          }          
          
          $scene->parameters = json_encode($params);
		$story = Story::find($story_id);
		if (Helpers::checkPermission($story_id) == false){
			return view('errors/403',  array());
			exit();		
		}		
		$method = "POST";
		return view('scene/edit',compact('scene','method','story'));
	}
	
	public function store(Request $request)
    {
		$scene = new Scene();		
		$scene = $this->save($scene, $request);
		return redirect('scene/'.$scene->id.'/edit')->withOk("The scene " . $scene->name . " has been saved .");
    }
	
	private function save($scene, $request)
	{
		$inputs = $request->all();		
		if (isset($inputs["name"])){
			$scene->name = $inputs["name"];			
		}
		
		if (isset($inputs["story_id"])){
			$scene->story_id = $inputs["story_id"];
		}
		
		if (Helpers::checkPermission($scene->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}

		$tab = [];
		if (isset($inputs["backgrounds"])){
			$tab["backgrounds_id"] = $inputs["backgrounds"];
		}
		if (isset($inputs["characters"])){
			$tab["characters_id"] = $inputs["characters"];
		}
		if (isset($inputs["musics"])){
			$tab["musics_id"] = $inputs["musics"];
		}
		if (isset($inputs["things"])){
			$tab["things_id"] = $inputs["things"];
		}
		
		$scene->parameters = json_encode($tab);
		
		$scene->save();
		
		return $scene;
	}
	
	public function edit(Request $request, $id)
	{	
		$scene = Scene::find($id);
		$story = Story::find($scene->story_id);		
		if (Helpers::checkPermission($scene->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$method = "PUT";
		return view('scene/edit',compact('scene','story','method'));
	}
	
	public function update(Request $request, $id)
	{		
		$scene = Scene::find($id);
		if (Helpers::checkPermission($scene->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$scene = $this->save($scene, $request);
		return redirect('/scene/'.$scene->id.'/edit')->withOk("The scene " . $scene->name . " has been saved .");
	}
	
	public function destroy($id)
	{	
		$scene = Scene::find($id);
		if (Helpers::checkPermission($scene->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		Scene::destroy($id);
		return redirect()->back();
	}	
	
	public function addone($story_id, Request $request)
	{			
		if (Helpers::checkPermission($story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$scene = new Scene();
		$scene->story_id = $story_id;
		$scene->name = $request->input("name");
		
          $params = $scene->getParams();
          
          //On prend tout par defaut
          foreach ($params as $key=>$value){
               switch ($key){
                    case "backgrounds_id":
                         foreach (Background::where("story_id","=",$story_id)->get() as $info){                              
                              $params[$key][] = $info->id;
                         }
                         break;
                    case "musics_id":
                         foreach (Music::where("story_id","=",$story_id)->get() as $info){
                              $params[$key][] = $info->id;
                         }
                    case "characters_id":
                         foreach (Character::where("story_id","=",$story_id)->get() as $info){
                              $params[$key][] = $info->id;
                         }
                         break;
                    case "things_id":
                         foreach (Thing::where("story_id","=",$story_id)->get() as $info){
                              $params[$key][] = $info->id;
                         }
                         break;
               }               
          } 
          $scene->parameters = json_encode($params);
          
		$scene->save();
		
		echo json_encode(["name"=>$scene->name, "id"=>$scene->id]);
	}	
	
	public function duplicate($id)
    {	
		$scene = Scene::find($id);
		$story = Story::find($scene->story_id);
		
		if (Helpers::checkPermission($scene->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		
		$new_scene = $scene->replicate();
		$new_scene->name = $new_scene->name. " Copy";
		$new_scene->noremove = 0;
		$new_scene->save();
		
		foreach ($scene->actions() as $action){
			$new_action = $action->replicate();
			$new_action->scene_id = $new_scene->id;
			$new_action->save();
		}
        return redirect('/story/'.$scene->story_id.'/scene')->withOk("The scene " . $scene->name . " has been duplicated .");
    }
}
