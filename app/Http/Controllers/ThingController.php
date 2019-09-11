<?php

namespace App\Http\Controllers;

use App\Story;
use App\Scene;
use App\Thing;
use Illuminate\Http\Request;

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
		$things = $story->things();
        return view('thing/index', compact("things","story"));
    }
	
	public function create($story_id)
	{	
		$thing = new Thing();
		$story = Story::find($story_id);
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
		return view('thing/show',compact('thing'));
	}

	private function save($thing, $request)
	{
		$inputs = $request->all();		
		if (isset($inputs["name"])){
			$thing->name = $inputs["name"];			
		}
		
		if (isset($inputs["picture"])){
			$thing->picture = $inputs["picture"];			
		}
		
		//TODO: check permission
		if (isset($inputs["story_id"])){
			$thing->story_id = $inputs["story_id"];
		}

		$thing->save();
		
		return $thing;
	}
	
	public function edit(Request $request, $id)
	{	
		$thing = Thing::find($id);
		$story = Story::find($thing->story_id);
		$method = "PUT";
		return view('thing/edit',compact('thing','story','method'));
	}
	
	public function update(Request $request, $id)
	{		
		$thing = Thing::find($id);
		$thing = $this->save($thing, $request);
		return redirect('story/'.$thing->story_id.'/thing')->withOk("The thing " . $thing->name . " has been saved .");
	}
	
	public function destroy($thing_id)
	{	
		Thing::destroy($thing_id);
		return redirect()->back();
	}	
}
