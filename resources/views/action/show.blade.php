<?php
foreach ($actions as $action){
	?>
	<option value="<?php echo $action->id;?>"><?php echo $action->name;?></option>
	<?php
}
?>