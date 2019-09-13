<?php

namespace App\Http\Controllers;

use App\Story;
use App\Scene;
use App\Background;
use Illuminate\Http\Request;
use App\Providers\HelperServiceProvider as Helpers;

class BackgroundController extends Controller
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
		$backgrounds = $story->backgrounds();
        return view('background/index', compact("backgrounds","story"));
    }
	
	public function create($story_id)
	{	
		$background = new Background();
		$story = Story::find($story_id);
		if (Helpers::checkPermission($story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$method = "POST";
		return view('background/edit',compact('background','method','story'));
	}
	
	public function store(Request $request)
    {
		$background = new Background();
		$background = $this->save($background, $request);
		return redirect('story/'.$background->story_id.'/background')->withOk("The background " . $background->name . " has been saved .");
    }
	
	public function show($id)
	{
		$background = Background::find($id);
		if (Helpers::checkPermission($background->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		return view('background/show',compact('background'));
	}

	private function save($background, $request)
	{
		$inputs = $request->all();		
		if (isset($inputs["name"])){
			$background->name = $inputs["name"];			
		}
		
		$background->picture = "";
		if (isset($inputs["picture"])){
			$background->picture = $inputs["picture"];			
		}
				
		if (isset($inputs["story_id"])){
			$background->story_id = $inputs["story_id"];
		}

		if (Helpers::checkPermission($background->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		
		//Upload file
		if ($request->file("picture_file") != ""){
			if (substr(strtolower($request->file("picture_file")->getClientOriginalName()),-4) == ".png"){
				if (!is_dir("stories")){
					mkdir ("stories");
				}
				if (!is_dir("stories/".$background->story_id)){
					mkdir ("stories/".$background->story_id);
				}
				
				Storage::disk('public')->put("stories/".$background->story_id."/".Helpers::encName($background->name).".png", file_get_contents($request->file("picture_file")));			
			}
		}
		
		$background->save();
		
		return $background;
	}
	
	public function edit(Request $request, $id)
	{	
		$background = Background::find($id);
		if (Helpers::checkPermission($background->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$story = Story::find($background->story_id);
		$method = "PUT";
		return view('background/edit',compact('background','story','method'));
	}
	
	public function update(Request $request, $id)
	{		
		$background = Background::find($id);
		if (Helpers::checkPermission($background->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$background = $this->save($background, $request);
		return redirect('story/'.$background->story_id.'/background')->withOk("The background " . $background->name . " has been saved .");
	}
	
	public function destroy($background_id)
	{	
		$background = Background::find($background_id);
		if (Helpers::checkPermission($background->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		Background::destroy($background_id);
		return redirect()->back();
	}	
}
