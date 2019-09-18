<?php

namespace App\Http\Controllers;

use Storage;
use App\Story;
use App\Scene;
use App\Music;
use Illuminate\Http\Request;
use App\Providers\HelperServiceProvider as Helpers;

class MusicController extends Controller
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
		$musics = $story->musics();
        return view('music/index', compact("musics","story"));
    }
	public function create($story_id)
	{	
		$music = new Music();
		$story = Story::find($story_id);
		if (Helpers::checkPermission($story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$method = "POST";
		return view('music/edit',compact('music','method','story'));
	}
	
	public function store(Request $request)
    {
		$music = new Music();
		$music = $this->save($music, $request);
		return redirect('story/'.$music->story_id.'/music')->withOk("The music " . $music->name . " has been saved .");
    }
	
	public function show($id)
	{
		$music = Music::find($id);
		if (Helpers::checkPermission($music->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		return view('music/show',compact('music'));
	}

	private function save($music, $request)
	{
		$inputs = $request->all();		
		if (isset($inputs["name"])){
			$music->name = $inputs["name"];			
		}
		$name = str_replace(".ogg","",$music->name);
		$name = str_replace(".mp3","",$name);
		
		$music->name = $name;
		
		$music->music = "";
		if (isset($inputs["music"])){
			$music->music = $inputs["music"];			
		}
		
		if (isset($inputs["story_id"])){
			$music->story_id = $inputs["story_id"];
		}

		if (Helpers::checkPermission($music->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		
		//Upload file
		if ($request->file("music_file") != ""){
			$extension = substr(strtolower($request->file("music_file")->getClientOriginalName()),-4);
			if (in_array($extension, [".ogg",".mp3"])){
				if (!is_dir("stories")){
					mkdir ("stories");
				}
				if (!is_dir("stories/".$music->story_id)){
					mkdir ("stories/".$music->story_id);
				}
				
				Storage::disk('public')->put("stories/".$music->story_id."/".Helpers::encName($music->name).$extension, file_get_contents($request->file("music_file")));			
				$music->music = env("APP_URL")."/stories/".$music->story_id."/".Helpers::encName($music->name).$extension;
               }else{
                    $music->music = "";
			}
		}
		
		$music->save();
		
		return $music;
	}
	
	public function edit(Request $request, $id)
	{	
		$music = Music::find($id);
		$story = Story::find($music->story_id);
		if (Helpers::checkPermission($music->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$method = "PUT";
		return view('music/edit',compact('music','story','method'));
	}
	
	public function update(Request $request, $id)
	{		
		$music = Music::find($id);
		if (Helpers::checkPermission($music->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$music = $this->save($music, $request);
		return redirect('story/'.$music->story_id.'/music')->withOk("The music " . $music->name . " has been saved .");
	}
	
	public function destroy($music_id)
	{	
		$music = Music::find($music_id);
		if (Helpers::checkPermission($music->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		Music::destroy($music_id);
		return redirect()->back();
	}	
}
