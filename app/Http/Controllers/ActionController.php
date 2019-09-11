<?php

namespace App\Http\Controllers;

use App\Story;
use App\Scene;
use App\Music;
use App\Action;
use Illuminate\Http\Request;

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

	public function index($story_id)
    {		
		$story = Story::find($story_id);
		$actions = $story->actions();
        return view('action/index', compact("actions","story"));
    }
	
	public function create(Request $request, $story_id)
	{	
		$action = new Action();
		$story = Story::find($story_id);
		$method = "POST";
		$scene = new Scene();
		if ("" != $request->input("scene_id")){
			$scene = Scene::find($request->input("scene_id"));
		}
		
		return view('action/edit',compact('action','method','story','scene','music'));
	}
	
	public function store(Request $request)
    {
		$action = new Action();
		$action = $this->save($action, $request);
		return redirect('story/'.$action->story_id.'/action')->withOk("The action " . $action->name . " has been saved .");
    }
	
	public function show($id)
	{
		$action = Action::find($id);
		return view('action/show',compact('action'));
	}

	private function save($action, $request)
	{
		$inputs = $request->all();		
		if (isset($inputs["name"])){
			$action->name = $inputs["name"];			
		}
		
		if (isset($inputs["end"])){
			$action->end = $inputs["end"];
		}
		
		//TODO: check permission
		if (isset($inputs["story_id"])){
			$action->story_id = $inputs["story_id"];
		}

		$tab = [];
		
		if (isset($inputs["scene"])){
			$tab["scene_id"] = $inputs["scene"];
		}
		
		if (isset($inputs["say"])){
			$tab["say"] = $inputs["say"];
		}
		if (isset($inputs["behaviour"])){
			$tab["behaviour_id"] = $inputs["behaviour"];
		}
		
		for ($k=1;$k<=4;$k++){
			if (isset($inputs["menu".$k])){
				$tab["menu".$k] = $inputs["menu".$k];
			}
			if (isset($inputs["menu".$k."_to"])){
				$tab["menu".$k."_to"] = $inputs["menu".$k."_to"];
			}
		}
		$action->parameters = json_encode($tab); 
		
		$action->save();
		
		return $action;
	}
	
	public function edit(Request $request, $id)
	{	
		$action = Action::find($id);
		$story = Story::find($action->story_id);
		$scene = new Scene();
		if ("" != $request->input("scene_id")){
			$scene = Scene::find($request->input("scene_id"));
		}
		
		$params = $action->getParams();
		if ($params["scene_id"] != 0){
			$scene = Scene::find($params["scene_id"]);
		}
		
		$method = "PUT";
		return view('action/edit',compact('action','story','method','scene'));
	}
	
	public function update(Request $request, $id)
	{		
		$action = Action::find($id);
		$action = $this->save($action, $request);
		return redirect('story/'.$action->story_id.'/action')->withOk("The action " . $action->name . " has been saved .");
	}
	
	public function destroy($action_id)
	{	
		Action::destroy($action_id);
		return redirect()->back();
	}	
}
