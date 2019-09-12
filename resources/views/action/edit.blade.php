<?php
$params = json_decode($action->parameters,true);
$key = $params["element"]."_".$params["subject_id"]."_".$params["verb"];

switch ($params["verb"]){
	case "move":
		?>
		$("#move").val(("<?php echo ($params["info"]);?>"));
		<?php
		break;
		
	case "show":
		?>
		<?php
		break;
		
	case "say":
		?>
		$("#say").val(("<?php echo ($params["info"]);?>"));
		<?php
		break;
		
	case "menu":
		$infos = json_decode($params["info"],true);
		?>
		$("#menu1").val("<?php echo ($infos["menu1"]);?>");
		$("#menu2").val("<?php echo ($infos["menu2"]);?>");
		$("#menu3").val("<?php echo ($infos["menu3"]);?>");
		$("#menu4").val("<?php echo ($infos["menu4"]);?>");
		$("#menu1_to").val("<?php echo ($infos["menu1_to"]);?>");
		$("#menu2_to").val("<?php echo ($infos["menu2_to"]);?>");
		$("#menu3_to").val("<?php echo ($infos["menu3_to"]);?>");
		$("#menu4_to").val("<?php echo ($infos["menu4_to"]);?>");
		
		<?php
		break;
}
?>
$("#action_id").val(<?php echo $action->id;?>);
$("#actions").val('<?php echo $key;?>');
showAction('<?php echo $key;?>');
$("#btn_update").show();