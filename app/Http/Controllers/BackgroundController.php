<?php

namespace App\Http\Controllers;

use App\Story;
use App\Scene;
use App\Background;
use App\Different;
use Illuminate\Http\Request;
use Sunra\PhpSimple\HtmlDomParser;
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
		
		$name = str_replace(".png","",$background->name);
		$name = str_replace(".gif","",$name);
		$name = str_replace(".jpg","",$name);
		$name = str_replace(".jpeg","",$name);
		$background->name = $name;
		
		if (isset($inputs["story_id"])){
			$background->story_id = $inputs["story_id"];
		}

		if (Helpers::checkPermission($background->story_id) == false){
			return view('errors/403',  array());
			exit();		
		}
		
		$background->save();
		
		if (isset($inputs["url_import"])){
			if ("" != $inputs["url_import"]){
				if (substr($inputs["url_import"],0,22) == "https://cloudnovel.net"){
					$str =  file_get_contents($inputs["url_import"]);
					$dom = HtmlDomParser::str_get_html($str);
					$elems = $dom->find("ul[id='responsive'] li img");
					foreach ($elems as $elem){
						$name = strtolower(basename($elem->src));
						$name = str_replace(".png","",$name);
						
						if(!Different::isExist($background->id,$name)){
							$different = new Different();
							$different->name = $name;
							$different->picture = $elem->src;
							$different->background_id = $background->id;
							$different->story_id = $background->story_id;
							$different->save();
						}
					}
				}
			}
		}
		
		//Default different
		$differents = $background->differents();
		if(!Different::isExist($background->id,"Default") and count($differents)==0){
			$different = new Different();
			$different->name = "default";
			$different->picture = "";
			if (isset($inputs["picture"])){
				$different->picture = $inputs["picture"];
			}
			
			//Upload file
			if ($request->file("picture_file") != ""){
				if (substr(strtolower($request->file("picture_file")->getClientOriginalName()),-4) == ".png"){					
					if (!is_dir("stories")){
						mkdir ("stories");
					}
					if (!is_dir("stories/".$different->story_id)){
						mkdir ("stories/".$different->story_id);
					}
					if (!is_dir("stories/".$different->story_id."/images")){
						mkdir ("stories/".$different->story_id."/images");
					}
					
					Storage::disk('public')->put("stories/".$different->story_id."/images/".Helpers::encName($background->name)."-".Helpers::encName($different->name).".png", file_get_contents($request->file("picture_file")));			
				}
			}
			$different->background_id = $background->id;
			$different->story_id = $different->story_id;
			$different->save();
		}
		
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
