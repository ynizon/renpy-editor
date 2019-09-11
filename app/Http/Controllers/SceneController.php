<?php

namespace App\Http\Controllers;

use App\Story;
use App\Background;
use App\Character;
use App\Thing;
use App\Scene;
use Illuminate\Http\Request;

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
		$scenes = $story->scenes();
        return view('scene/index', compact("scenes","story"));
    }
	
	public function create($story_id)
	{	
		$scene = new Scene();
		$story = Story::find($story_id);
		$method = "POST";
		return view('scene/edit',compact('scene','method','story'));
	}
	
	public function store(Request $request)
    {
		$scene = new Scene();
		$scene = $this->save($scene, $request);
		return redirect('story/'.$scene->story_id.'/scene')->withOk("The scene " . $scene->name . " has been saved .");
    }
	
	public function show($id)
	{
		$scene = Scene::find($id);
		return view('scene/show',compact('scene'));
	}

	private function save($scene, $request)
	{
		$inputs = $request->all();		
		if (isset($inputs["name"])){
			$scene->name = $inputs["name"];			
		}
		
		//TODO: check permission
		if (isset($inputs["story_id"])){
			$scene->story_id = $inputs["story_id"];
		}

		$tab = [];
		if (isset($inputs["background"])){
			$tab["background_id"] = $inputs["background"];
		}
		if (isset($inputs["characters"])){
			$tab["characters_id"] = $inputs["characters"];
		}
		if (isset($inputs["music"])){
			$tab["music_id"] = $inputs["music"];
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
		$method = "PUT";
		return view('scene/edit',compact('scene','story','method'));
	}
	
	public function update(Request $request, $id)
	{		
		$scene = Scene::find($id);
		$scene = $this->save($scene, $request);
		return redirect('story/'.$scene->story_id.'/scene')->withOk("The scene " . $scene->name . " has been saved .");
	}
	
	public function destroy($scene_id)
	{	
		Scene::destroy($scene_id);
		return redirect()->back();
	}	
}
