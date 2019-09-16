<?php
$params = json_decode($action->parameters,true);
$key = $params["element"]."_".$params["subject_id"]."_".$params["verb"];
?>
loadSceneActions(<?php echo $action->story_id;?>, <?php echo $action->scene_id;?>);