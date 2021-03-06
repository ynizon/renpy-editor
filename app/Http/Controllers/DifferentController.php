<?php

namespace App\Http\Controllers;

use App\Story;
use App\Action;
use App\Scene;
use App\Different;
use App\Background;
use Illuminate\Http\Request;
use App\Providers\HelperServiceProvider as Helpers;
use Storage;

class DifferentController extends Controller
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


	public function index($story_id, $background_id)
    {		
		$story = Story::find($story_id);
		$background = Background::find($background_id);
		if (Helpers::checkPermission($background->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$differents = $background->differents();
        return view('different/index', compact("differents","story","background"));
    }
	
	public function create($story_id, $background_id)
	{
		$different = new Different();
		$background = Background::find($background_id);
		if (Helpers::checkPermission($background->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$story = Story::find($background->story_id);
		$method = "POST";
		return view('different/edit',compact('different','method','story','background'));
	}
	
	public function store(Request $request)
    {
		$different = new Different();
		$different = $this->save($different, $request);
		return redirect('story/'.$different->story_id.'/background/'.$different->background_id.'/different')->withOk("The different " . $different->name . " has been saved .");
    }
	
	private function save($different, $request)
	{
		$inputs = $request->all();		
		if (isset($inputs["name"])){
			$different->name = $inputs["name"];			
		}
		
		$name = str_replace(".png","",$different->name);
		$name = str_replace(".gif","",$name);
		$name = str_replace(".jpg","",$name);
		$name = str_replace(".jpeg","",$name);
		$different->name = $name;
		
		$different->picture = "";
		if (isset($inputs["picture"])){
			$different->picture = $inputs["picture"];
		}
		
		if (isset($inputs["story_id"])){
			$different->story_id = $inputs["story_id"];
		}
		if (isset($inputs["background_id"])){
			$different->background_id = $inputs["background_id"];
		}

		if (Helpers::checkPermission($different->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		
		//Upload file
		if ($request->file("picture_file") != ""){
			$extension = substr(strtolower($request->file("picture_file")->getClientOriginalName()),-4);
               if (in_array($extension, [".gif",".jpg",".png"])){
				$background = Background::find($different->background_id);
				if (!is_dir("stories")){
					mkdir ("stories");
				}
				if (!is_dir("stories/".$different->story_id)){
					mkdir ("stories/".$different->story_id);
				}
				if (!is_dir("stories/".$different->story_id."/images")){
					mkdir ("stories/".$different->story_id."/images");
				}
				
				if (!is_dir("stories/".$different->story_id."/images/".Helpers::encName($background->name))){
					mkdir ("stories/".$different->story_id."/images/".Helpers::encName($background->name));
				}
				
				Storage::disk('public')->put("stories/".$different->story_id."/images/".Helpers::encName($background->name)."/".Helpers::encName($different->name).$extension, file_get_contents($request->file("picture_file")));			
				$behaviour->picture = env("APP_URL")."/stories/".$different->story_id."/images/".Helpers::encName($background->name)."/".Helpers::encName($different->name).$extension;
			}else{
                    $behaviour->picture = "";
               }
		}
		
		$different->save();
		
		return $different;
	}
	
	public function edit(Request $request, $id)
	{	
		$different = Different::find($id);
		$background = Background::find($different->background_id);
		if (Helpers::checkPermission($background->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$story = Story::find($different->story_id);
		$method = "PUT";
		return view('different/edit',compact('different','story','method','background'));
	}
	
	public function update(Request $request, $id)
	{		
		$different = Different::find($id);
		if (Helpers::checkPermission($different->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$different = $this->save($different, $request);
		return redirect('story/'.$different->story_id.'/background/'.$different->background_id.'/different')->withOk("The different " . $different->name . " has been saved .");
	}
	
	public function destroy($different_id)
	{	
		$different = Different::find($different_id);
		if (Helpers::checkPermission($different->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		Different::destroy($different_id);
		return redirect()->back();
	}	
	
	public function show (Request $request, $story_id, $background_id){
		$background = Background::find($background_id);
		if (Helpers::checkPermission($background->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$differents = $background->differents();
          $different_id = "0";
          if ($request->input("action_id") != "0"){
               $action = Action::find($request->input("action_id"));
               if ($action->parameters != ""){
                    $params = json_decode($action->parameters,true);
                    $different_id = $params["info"];
               }
          }
		return view('different/show',compact('differents','different_id'));
	}
}
