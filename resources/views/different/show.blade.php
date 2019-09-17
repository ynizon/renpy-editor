<?php
foreach ($differents as $different){
	?>
	<option <?php if($different->id==$different_id){echo "selected";} ?> value="<?php echo $different->id;?>"><?php echo $different->name;?></option>
	<?php
}
?>