<?php
foreach ($behaviours as $behaviour){
	?>
	<option <?php if($behaviour->id==$behaviour_id){echo "selected";} ?> value="<?php echo $behaviour->id;?>"><?php echo $behaviour->name;?></option>
	<?php
}
?>