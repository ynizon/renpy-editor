<?php

namespace App\Http\Controllers;

use App\Story;
use App\Scene;
use App\Thing;
use Illuminate\Http\Request;
use App\Providers\HelperServiceProvider as Helpers;

class ThingController extends Controller
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
		$things = $story->things();
        return view('thing/index', compact("things","story"));
    }
	
	public function create($story_id)
	{	
		$thing = new Thing();
		$story = Story::find($story_id);
		if (Helpers::checkPermission($story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$method = "POST";
		return view('thing/edit',compact('thing','method','story'));
	}
	
	public function store(Request $request)
    {
		$thing = new Thing();
		$thing = $this->save($thing, $request);
		return redirect('story/'.$thing->story_id.'/thing')->withOk("The thing " . $thing->name . " has been saved .");
    }
	
	public function show($id)
	{
		$thing = Thing::find($id);
		if (Helpers::checkPermission($thing->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		return view('thing/show',compact('thing'));
	}

	private function save($thing, $request)
	{
		$inputs = $request->all();		
		if (isset($inputs["name"])){
			$thing->name = $inputs["name"];			
		}
		
		$name = str_replace(".png","",$thing->name);
		$name = str_replace(".gif","",$name);
		$name = str_replace(".jpg","",$name);
		$name = str_replace(".jpeg","",$name);
		$thing->name = $name;
		
		$thing->picture = "";
		if (isset($inputs["picture"])){
			$thing->picture = $inputs["picture"];			
		}
		
		if (isset($inputs["story_id"])){
			$thing->story_id = $inputs["story_id"];
		}

		if (Helpers::checkPermission($thing->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		
		//Upload file
		if ($request->file("picture_file") != ""){
               $extension = substr(strtolower($request->file("picture_file")->getClientOriginalName()),-4);
               if (in_array($extension, [".gif",".jpg",".png"])){
				if (!is_dir("stories")){
					mkdir ("stories");
				}
				if (!is_dir("stories/".$thing->story_id)){
					mkdir ("stories/".$thing->story_id);
				}
				
				Storage::disk('public')->put("stories/".$thing->story_id."/".Helpers::encName($thing->name).$extension, file_get_contents($request->file("picture_file")));			
				$thing->picture = env("APP_URL")."/stories/".$thing->story_id."/".Helpers::encName($thing->name).$extension;
			}else{
                    $thing->picture = "";
               }
		}
		
		$thing->save();
		
		return $thing;
	}
	
	public function edit(Request $request, $id)
	{	
		$thing = Thing::find($id);
		$story = Story::find($thing->story_id);
		if (Helpers::checkPermission($thing->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$method = "PUT";
		return view('thing/edit',compact('thing','story','method'));
	}
	
	public function update(Request $request, $id)
	{		
		$thing = Thing::find($id);
		if (Helpers::checkPermission($thing->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$thing = $this->save($thing, $request);
		return redirect('story/'.$thing->story_id.'/thing')->withOk("The thing " . $thing->name . " has been saved .");
	}
	
	public function destroy($thing_id)
	{	
		$thing = Thing::find($thing_id);
		if (Helpers::checkPermission($thing->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		Thing::destroy($thing_id);
		return redirect()->back();
	}	
}
