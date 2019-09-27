<?php
$params = json_decode($action->parameters,true);
$key = $params["element"]."_".$params["subject_id"]."_".$params["verb"];

switch ($params["verb"]){
     case "set":
		?>
		$("#setthings").val(decodeURIComponent("<?php echo rawurlencode($params["info"]);?>"));
		<?php
		break;
          
	case "addhp":
     case "addmp":
          ?>
		$("#hp").val(decodeURIComponent("<?php echo rawurlencode($params["info"]);?>"));
		<?php
		break;
          
	case "show":
		break;
		
	case "say":
		?>
		$("#say").val(decodeURIComponent("<?php echo rawurlencode($params["info"]);?>"));
		<?php
		break;
	
     case "iftrue":
	case "jump":
		?>
		$("#jump").val(decodeURIComponent("<?php echo rawurlencode($params["info"]);?>"));
		<?php
		break;
		
	case "addscript":
		?>
		$("#addscript").val(decodeURIComponent("<?php echo rawurlencode($params["info"]);?>"));
		<?php
		break;
		
	case "menu":
		$infos = json_decode($params["info"],true);
		?>
		$("#menu_title").val(decodeURIComponent("<?php echo rawurlencode($infos["menu_title"]);?>"));
          <?php
          for ($k=1;$k<=config("app.max_menu_choice");$k++){
               if (isset($infos["menu".$k])){
			   ?>
				$("#menu<?php echo $k;?>").val(decodeURIComponent("<?php echo rawurlencode($infos["menu".$k]);?>"));
				$("#menu<?php echo $k;?>_to").val(decodeURIComponent("<?php echo rawurlencode($infos["menu".$k."_to"]);?>"));
               <?php
			   }
          }
          
		break;
}
?>
$("#action_id").val(<?php echo $action->id;?>);
$("#actions").val('<?php echo $key;?>');
showAction('<?php echo $key;?>');
$("#btn_update").show();