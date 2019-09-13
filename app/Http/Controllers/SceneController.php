<?php

namespace App\Http\Controllers;

use App\Story;
use App\Background;
use App\Character;
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
		return redirect('story/'.$scene->story_id.'/scene')->withOk("The scene " . $scene->name . " has been saved .");
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
		$scene = Story::find($id);
		if (Helpers::checkPermission($scene->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		Scene::destroy($id);
		return redirect()->back();
	}	
	
}
