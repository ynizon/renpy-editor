<?php
foreach ($behaviours as $behaviour){
	?>
	<option value="<?php echo $behaviour->id;?>"><?php echo $behaviour->name;?></option>
	<?php
}
?>