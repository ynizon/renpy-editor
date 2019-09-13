<?php
foreach ($differents as $different){
	?>
	<option value="<?php echo $different->id;?>"><?php echo $different->name;?></option>
	<?php
}
?>