<?php
$sList = "";
foreach ($behaviours as $behaviour){	
	$sList .= '<option ';
     if($behaviour->id==$behaviour_id){
          $sList .= "selected";
     } 
     $sList .= " value=". $behaviour->id.">". $behaviour->name."</option>";
}

?>

{     
     "behaviours" : <?php echo json_encode($sList);?>,
     "move" : <?php echo json_encode($sMove);?>
}