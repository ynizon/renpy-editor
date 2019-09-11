<?php

namespace App\Http\Controllers;

use App\Story;
use App\Scene;
use App\Background;
use Illuminate\Http\Request;

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
		$backgrounds = $story->backgrounds();
        return view('background/index', compact("backgrounds","story"));
    }
	
	public function create($story_id)
	{	
		$background = new Background();
		$story = Story::find($story_id);
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
		return view('background/show',compact('background'));
	}

	private function save($background, $request)
	{
		$inputs = $request->all();		
		if (isset($inputs["name"])){
			$background->name = $inputs["name"];			
		}
		
		if (isset($inputs["picture"])){
			$background->picture = $inputs["picture"];			
		}
		
		//TODO: check permission
		if (isset($inputs["story_id"])){
			$background->story_id = $inputs["story_id"];
		}

		$background->save();
		
		return $background;
	}
	
	public function edit(Request $request, $id)
	{	
		$background = Background::find($id);
		$story = Story::find($background->story_id);
		$method = "PUT";
		return view('background/edit',compact('background','story','method'));
	}
	
	public function update(Request $request, $id)
	{		
		$background = Background::find($id);
		$background = $this->save($background, $request);
		return redirect('story/'.$background->story_id.'/background')->withOk("The background " . $background->name . " has been saved .");
	}
	
	public function destroy($background_id)
	{	
		Background::destroy($background_id);
		return redirect()->back();
	}	
}
