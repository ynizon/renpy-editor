@extends('layouts.app')

@section('content')
<?php
use App\Scene;
use App\Action;
use App\Behaviour;
use App\Character;
use App\Music;
use App\Thing;
use App\Background;
?>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Copy this code into your scripts.py, then run your game.
					<br/>
					All your resources have been downloaded into the public/stories/<?php echo $story->id;?> folder .
				</div>
                <div class="panel-body">

					<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
						<textarea class="form-control" rows="60"><?php 
						//Characters
						foreach ($story->characters() as $character){
							echo "define ".Helpers::encName($character->name)." = Character('".$character->name."', color='#".$character->color."')\r\n";
						}
						echo "\r\n";
						
						$old_scene_id = 0;
						$old_character_id = 0;
						$z = 0;
						$start = 0;
						foreach ($story->actions() as $action){
							$action_params = $action->getParams();
							if ($action_params["scene_id"] != 0){
								if ($action_params["scene_id"] != 0){
									$scene = Scene::find($action_params["scene_id"]);
									$params = $scene->getParams();
									
									//If we change scene, we change background and other element
									//If not , its just a dialog
									//if ($old_scene_id != $scene->id){
									echo "\r\n";
									if ($z==0){
										echo "label start:\r\n";
										$start = $action->id;
									}else{
										echo "label action_".Helpers::encName($action->name).":\r\n";
									}
									
									//Show background
									if ($params["background_id"] != 0){
										$background = Background::find($params["background_id"]);
										echo "\t"."scene ".basename($background->picture)."\r\n";
									}
									//}	
									//Show Character
									if ($action_params["behaviour_id"] != 0){
										$behaviour = Behaviour::find($action_params["behaviour_id"]);
										$character = $behaviour->character;
										
										if ($old_character_id != $character->id){
											//Show
											if ($old_character_id != 0){
												echo "\thide ".Helpers::encName($character->name)." with fade\r\n";	
											}
											echo "\tshow ".Helpers::encName($character->name)." with fade\r\n";
										}
									}
									
									//Play Music
									if ($params["music_id"] != 0){
										$music = Music::find($params["music_id"]);								
										echo "\t"."play music '".Helpers::encName(basename($music->name))."' fadeout 1.0 fadein 1.0\r\n";
									}
									
									echo "\t".Helpers::encName($character->name) . ' "'.$action_params["say"]."\"\r\n";
								
									
									
									
									if ($action_params["menu1"] !=""){
										echo "menu:\r\n";
									}
									
									for ($k=1;$k<=4;$k++){
										if ($action_params["menu".$k] !=""){
											echo "\t".$action_params["menu".$k].":\r\n";
											if ($action_params["menu".$k."_to"] == $start){
												echo "\t\t"."jump start\r\n";
											}else{
												$goto_action = Action::find($action_params["menu".$k."_to"]);
												echo "\t\t"."jump action_".Helpers::encName($goto_action->name)."\r\n";
											}											
										}
									}
									
									//Ending
									if ($action->end == 1){
										echo "\t"."return\r\n";	
									}
									$z++;
									
									$old_scene_id = $scene->id;
									$old_character_id = $character->id;
								}
							}
						}
						?></textarea>
					</div>
					
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
