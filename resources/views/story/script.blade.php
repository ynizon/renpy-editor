<?php 
use App\Scene;
use App\Action;
use App\Behaviour;
use App\Character;
use App\Different;
use App\Music;
use App\Thing;
use App\Background;

//Before
echo $story->starting_script."\r\n";

//Characters
foreach ($story->characters() as $character){
	echo "define ".Helpers::encName($character->name)." = Character('".$character->name."', color='#".$character->color."')\r\n";
	
	foreach ($character->behaviours() as $behaviour){
		$image = Helpers::encName($behaviour->name);
		$image = str_replace(".gif","",$image);
		$image = str_replace(".png","",$image);
		$image = str_replace(".jpeg","",$image);
		$image = str_replace(".jpg","",$image);
		
		echo "image ".Helpers::encName($character->name)." ".$image ." = \"".Helpers::encName($character->name)."/".Helpers::encName(basename($behaviour->picture))."\"\r\n";
        echo "image ".Helpers::encName($character->name)." flip_".$image ." = im.Flip(\"".Helpers::encName($character->name)."/".Helpers::encName(basename($behaviour->picture))."\", horizontal=True)\r\n";
	}
	echo "\r\n";
}

//Things
foreach ($story->things() as $thing){
	echo "image ".Helpers::encName($thing->name) ." = \"things/".Helpers::encName(basename($thing->picture))."\"\r\n";
     echo "default ".Helpers::encName($thing->name)." = False\r\n";
	echo "\r\n";
}



echo "\r\n#The game start here\r\nlabel start:\r\n";
$bStart = false;
if (count($story->scenes()) == 0){
	echo $TAB."return\r\n";	
}else{
	foreach ($story->scenes() as $scene){
		if ($bStart == false){
			$bStart = true;
			echo $TAB."jump scene_".Helpers::encName($scene->name)."\r\n";
		}
		
		echo "\r\n";
		echo "label scene_".Helpers::encName($scene->name).":\r\n";
		
		foreach ($scene->actions() as $action){
			echo "\r\n";
			$action_params = $action->getParams();
			
			
			switch ($action_params["element"]){
				case "game":
					switch ($action_params["verb"]){
						case "end":
							echo $TAB."return\r\n";	
							break;
						case "pause":
							echo $TAB."pause\r\n";	
							break;
						case "jump":
							$goto_scene = Scene::find($action_params["info"]);
							echo $TAB."jump scene_".Helpers::encName($goto_scene->name)."\r\n";
							break;
						case "addscript":
                                   $lines = explode("\n",$action_params["info"]);
                                   foreach ($lines as $line){
                                        echo $TAB.$line."\r\n";
                                   }
							break;
					}
					break;
				
				case "background":
					$background = Background::find($action_params["subject_id"]);
					switch ($action_params["verb"]){
						case "show":							
							$different = Different::find($action_params["info"]);
							$file = pathinfo(Helpers::encName($background->name)."-".Helpers::encName(basename($different->picture)), PATHINFO_FILENAME);
							echo $TAB."scene ".$file." with fade\r\n";
							break;
					}
					break;
				
				case "music":
					$music = Music::find($action_params["subject_id"]);
					switch ($action_params["verb"]){
						case "play":						
							echo $TAB."play music '".Helpers::encName(basename($music->music))."' fadeout 1.0 fadein 1.0\r\n";
							break;
						case "queue":													
							echo $TAB."queue music '".Helpers::encName(basename($music->music))."'\r\n";
							break;
						case "stop":
							echo $TAB."stop music fadeout 1.0\r\n";
							break;
					}
					break;
					
				case "thing":
					$thing = Thing::find($action_params["subject_id"]);
					switch ($action_params["verb"]){
						case "show":							
							echo $TAB."show ".Helpers::encName($thing->name)." at right with dissolve\r\n";
							break;
						case "hide":
							echo $TAB."hide ".Helpers::encName($thing->name)."\r\n";	
							break;
                              case "set":
                                   echo $TAB."$ ".Helpers::encName($thing->name)." = ".$action_params["info"]."\r\n";
                                   break;
                              case "iftrue":
                                   $goto_scene = Scene::find($action_params["info"]);
							echo $TAB."if (".Helpers::encName($thing->name).") :\r\n";
                                   echo $TAB.$TAB."jump scene_".Helpers::encName($goto_scene->name)."\r\n";
                                   break;
					}
					break;
					
				case "character":
                         $character = Character::find($action_params["subject_id"]);
					switch ($action_params["verb"]){
						case "show":
							$behaviour = Behaviour::find($action_params["info"]);
							echo $TAB."show ".Helpers::encName($character->name)." ".Helpers::encName($behaviour->name)." with dissolve\r\n";
							break;
                              case "showflip":
							$behaviour = Behaviour::find($action_params["info"]);
							echo $TAB."show ".Helpers::encName($character->name)." flip_".Helpers::encName($behaviour->name)." with dissolve\r\n";
							break;
						case "hide":
							echo $TAB."hide ".Helpers::encName($character->name)."\r\n";	
							break;
						case "say":
                                   if ($action_params["subject_id"] != 0){
                                        echo $TAB.Helpers::encName($character->name) . ' "'.str_replace("\n",'\n',$action_params["info"])."\"\r\n";
                                   }else{
                                        echo $TAB. '"'.str_replace("\n",'\n',$action_params["info"])."\"\r\n";
                                   }
							break;
						case "move":
							echo $TAB."show ".Helpers::encName($character->name)." at ".$action_params["info"]."\r\n";
							break;
						case "menu":
							$info = json_decode($action_params["info"],true);
							echo $TAB."menu:\r\n";
							if ($info["menu_title"] != ""){
                                        echo $TAB.$TAB."\"".$info["menu_title"]."\"\r\n";	
                                   }
							for ($k=1;$k<=4;$k++){
								if ($info["menu".$k] !=""){									
									if ($info["menu".$k."_to"] > 0){
										echo $TAB.$TAB."\"".$info["menu".$k]."\":\r\n";
										$goto_scene = Scene::find($info["menu".$k."_to"]);
										echo $TAB.$TAB.$TAB."jump scene_".Helpers::encName($goto_scene->name)."\r\n";
									}
								}
							}
							break;
					}
					break;
				
			}			
		}
	}
}
?>