<?php

namespace App\Http\Controllers;

use App\Story;
use App\Background;
use App\Thing;
use App\Scene;
use App\Different;
use App\Behaviour;
use App\Character;
use App\Music;
use App\Action;
use Illuminate\Http\Request;
use App\Providers\HelperServiceProvider as Helpers;

class ActionController extends Controller
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

	public function delete_action($story_id, $scene_id, $action_id, Request $request){		
		$action = Action::find($action_id);
		if (Helpers::checkPermission($action->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		
		Action::destroy($action_id);
	}
	
	public function add_action($story_id, $scene_id, Request $request){		
	
		if (Helpers::checkPermission($story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$data = $request->input("data");
		$order = ($request->input("order"));
		
		//echo var_dump($data);exit();
		$subject = "game";
		
		if ($data["action_id"] == 0){
			$action = new Action();
		}else{
			$action = Action::find($data["action_id"]);
		}		
		
		switch ($data["element"]){
			case "background":
				$subject = Background::find($data["subject_id"])->name;
				break;
			case "character":
				$subject = Character::find($data["subject_id"])->name;
				break;
			case "music":
				$subject = Music::find($data["subject_id"])->name;
				break;
			case "thing":
				$subject = Thing::find($data["subject_id"])->name;
				break;
		}
		$action->name = $subject ." ".$data["verb"];
		if ($data["info"]!= ""){
			switch ($data["element"]){
				default:
					$action->name .= ":". substr($data["info"],0,50);
					break;
				case "game":
					if ($data["verb"] == "jump"){
						$scene = Scene::find($data["info"]);
						$action->name .= ":". $scene->name;
					}else{
						$action->name .= ":". substr($data["info"],0,50);
					}
					break;
				case "character":
					if ($data["verb"] == "show"){
						$behaviour = Behaviour::find($data["info"]);
						$action->name .= ":". $behaviour->name;
					}else{
						$action->name .= ":". substr($data["info"],0,50);
					}
					break;
					
				case "background":
					if ($data["verb"] == "show"){
						$different = Different::find($data["info"]);
						$action->name .= ":". $different->name;
					}else{
						$action->name .= ":". substr($data["info"],0,50);
					}
					break;
			}			
		}
		$action->parameters = json_encode($data);		
		$action->story_id = $story_id;
		$action->scene_id = $scene_id;
		
		$nb = Action::where("scene_id","=",$scene_id)->count();
		if ($order == -1){
			$action->num_order = ($nb+1);
		}else{
			$action->num_order = ($order+1);
		}
		$action->save();
	}
		
	public function show($story_id,$id)
	{
		$scene = Scene::find($id);
		if (Helpers::checkPermission($scene->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$actions = Action::where("scene_id","=",$id)->orderBy("num_order")->get();
		
		//Reorder
		$k=0;
		foreach ($actions as $action){
			$k++;
			$action->num_order = $k;
			$action->save();
		}		
		
		return view('action/show',compact('scene','actions'));
	}	
	
	public function edit_action($story_id, $scene_id, $action_id, Request $request){		
		$action = Action::find($action_id);
		if (Helpers::checkPermission($action->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		return view('action/edit',compact('action'));
	}
     
     public function order_action($story_id, $scene_id, $action_id, Request $request){		
		$action = Action::find($action_id);
		if (Helpers::checkPermission($action->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
          $scene = Scene::find($scene_id);
          if ($request->input("order") != ""){
               switch ($request->input("order")){
                    case "down":
                         foreach ($scene->actions() as $ac){
                              if ($ac->num_order == ($action->num_order+1)){
                                   $ac->num_order = ($ac->num_order-1);
                                   $ac->save();
                              }
                         }
                         $action->num_order = ($action->num_order+1); 
                         break;
                    case "up":
                         foreach ($scene->actions() as $ac){
                              if ($ac->num_order == ($action->num_order-1)){
                                   $ac->num_order = ($ac->num_order+1);
                                   $ac->save();
                              }
                         }
                         $action->num_order = ($action->num_order-1); 
                         break;
               }
          }
          $action->save();
		return view('action/order',compact('action'));
	}
}
