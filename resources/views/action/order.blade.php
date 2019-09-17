<?php
$params = json_decode($action->parameters,true);
$key = $params["element"]."_".$params["subject_id"]."_".$params["verb"];

switch ($order){
     case "up":
          ?>document.getElementById("list_actions").selectedIndex = document.getElementById("list_actions").selectedIndex-1;<?php
          break;
     case "down":
          ?>document.getElementById("list_actions").selectedIndex = document.getElementById("list_actions").selectedIndex+1;<?php
          break;
}
?>
loadSceneActions(<?php echo $action->story_id;?>, <?php echo $action->scene_id;?>);