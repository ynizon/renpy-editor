<?php

namespace App\Http\Controllers;

use App\Story;
use App\Scene;
use App\Music;
use Illuminate\Http\Request;

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
		$musics = $story->musics();
        return view('music/index', compact("musics","story"));
    }
	public function create($story_id)
	{	
		$music = new Music();
		$story = Story::find($story_id);
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
		return view('music/show',compact('music'));
	}

	private function save($music, $request)
	{
		$inputs = $request->all();		
		if (isset($inputs["name"])){
			$music->name = $inputs["name"];			
		}
		
		if (isset($inputs["music"])){
			$music->music = $inputs["music"];			
		}
		
		//TODO: check permission
		if (isset($inputs["story_id"])){
			$music->story_id = $inputs["story_id"];
		}

		$music->save();
		
		return $music;
	}
	
	public function edit(Request $request, $id)
	{	
		$music = Music::find($id);
		$story = Story::find($music->story_id);
		$method = "PUT";
		return view('music/edit',compact('music','story','method'));
	}
	
	public function update(Request $request, $id)
	{		
		$music = Music::find($id);
		$music = $this->save($music, $request);
		return redirect('story/'.$music->story_id.'/music')->withOk("The music " . $music->name . " has been saved .");
	}
	
	public function destroy($music_id)
	{	
		Music::destroy($music_id);
		return redirect()->back();
	}	
}
