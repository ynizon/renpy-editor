<?php
$params = json_decode($action->parameters,true);
$key = $params["element"]."_".$params["subject_id"]."_".$params["verb"];

switch ($params["verb"]){
	case "move":
		?>
		$("#move").val(decodeURIComponent("<?php echo rawurlencode($params["info"]);?>"));
		<?php
		break;
		
	case "show":
		?>
		<?php
		break;
		
	case "say":
		?>
		$("#say").val(decodeURIComponent("<?php echo rawurlencode($params["info"]);?>"));
		<?php
		break;
		
	case "menu":
		$infos = json_decode($params["info"],true);
		?>
		$("#menu1").val(decodeURIComponent("<?php echo rawurlencode($infos["menu1"]);?>");
		$("#menu2").val(decodeURIComponent("<?php echo rawurlencode($infos["menu2"]);?>");
		$("#menu3").val(decodeURIComponent("<?php echo rawurlencode($infos["menu3"]);?>");
		$("#menu4").val(decodeURIComponent("<?php echo rawurlencode($infos["menu4"]);?>");
		$("#menu1_to").val(decodeURIComponent("<?php echo rawurlencode($infos["menu1_to"]);?>");
		$("#menu2_to").val(decodeURIComponent("<?php echo rawurlencode($infos["menu2_to"]);?>");
		$("#menu3_to").val(decodeURIComponent("<?php echo rawurlencode($infos["menu3_to"]);?>");
		$("#menu4_to").val(decodeURIComponent("<?php echo rawurlencode($infos["menu4_to"]);?>");
		
		<?php
		break;
}
?>
$("#action_id").val(<?php echo $action->id;?>);
$("#actions").val('<?php echo $key;?>');
showAction('<?php echo $key;?>');
$("#btn_update").show();