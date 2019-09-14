@extends('layouts.app')

@section('content')
<?php
$params = $scene->getParams();
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h2 class="card-header">Edit</h2>
                <div class="card-body">					
					<div class="row">
						<div class="col-md-12">							
						</div>
                        <div class="col-md-4">							
							{!! Form::model($scene, ['route' => ['scene.update', $scene->id], 'method' => $method, 'class' => 'form-horizontal panel']) !!}
							<h3>Design</h3>
							{{ csrf_field() }}
							<input type="hidden"  name="story_id" value="{!! $story->id !!}" />

							<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
								<label for="name" class="col-md-10 control-label">Name</label>

								<div class="col-md-12">								
									<input id="name" type="text" class="form-control" name="name" value="{!! $scene->name !!}" required autofocus />

									@if ($errors->has('name'))
										<span class="help-block">
											<strong>{{ $errors->first('name') }}</strong>
										</span>
									@endif
								</div>
							</div>
							
							<div class="form-group{{ $errors->has('background') ? ' has-error' : '' }}">
								<label for="background" class="col-md-5 control-label">Backgrounds</label>

								<div class="col-md-12">								
									<select id="backgrounds" multiple class="form-control" name="backgrounds[]" >
										<option value="0">-</option>
										<?php
										foreach ($story->backgrounds() as $background){
											?>
											<option <?php if (in_array($background->id,$params["backgrounds_id"])){echo "selected";} ?> value="<?php echo $background->id;?>"><?php echo $background->name;?></option>
											<?php
										}
										?>									
									</select>
								</div>
							</div>
							
							<div class="form-group{{ $errors->has('music') ? ' has-error' : '' }}">
								<label for="music" class="col-md-4 control-label">Musics</label>

								<div class="col-md-12">								
									<select id="musics" multiple class="form-control" name="musics[]" >
										<option value="0">-</option>
										<?php
										foreach ($story->musics() as $music){
											?>
											<option <?php if (in_array($music->id,$params["musics_id"])){echo "selected";} ?> value="<?php echo $music->id;?>"><?php echo $music->name;?></option>
											<?php
										}
										?>									
									</select>
								</div>
							</div>
							
							<div class="form-group{{ $errors->has('characters') ? ' has-error' : '' }}">
								<label for="characters" class="col-md-4 control-label">Characters</label>

								<div class="col-md-12">								                                
									<select id="characters" multiple class="form-control" name="characters[]" >									
										<option value="0">-</option>
										<?php
										foreach ($story->characters() as $character){
											?>
											<option <?php if (in_array($character->id,$params["characters_id"])){echo "selected";} ?> value="<?php echo $character->id;?>"><?php echo $character->name;?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>
							
							<div class="form-group{{ $errors->has('things') ? ' has-error' : '' }}">
								<label for="things" class="col-md-4 control-label">Things</label>

								<div class="col-md-12">								
									<select id="things" multiple class="form-control" name="things[]" >
										<option value="0">-</option>
										<?php
										foreach ($story->things() as $thing){
											?>
											<option <?php if (in_array($thing->id,$params["things_id"])){echo "selected";} ?> value="<?php echo $thing->id;?>"><?php echo $thing->name;?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-md-8 col-md-offset-4">
									<button type="submit" class="btn btn-primary">
										Save
									</button>
									<br/><br/>
									<i class="fa fa-info"></i>&nbsp;&nbsp;The label of the scene is:<br/>scene_<?php echo Helpers::encName($scene->name);?>
								</div>
							</div>      
							
							{!! Form::close() !!}
						</div>
						<div class="col-md-4">
							<h3>Actions</h3>
							<?php
							if (count($params["characters_id"])==0 and count($params["backgrounds_id"])==0 and count($params["things_id"])==0 and count($params["musics_id"])==0){
							?>
								<div class="form-group{{ $errors->has('actions') ? ' has-error' : '' }}">
									<p>
										<br/>
										Select your scene components on the left, save, then you can add actions. Use control key to add many components.
									</p>
								</div>
							<?php
							}else{
							?>
								<div class="form-group{{ $errors->has('actions') ? ' has-error' : '' }}">
								
									<label for="actions" class="col-md-4 control-label">Do actions</label>

									<div class="col-md-12">
										<select id="actions" class="form-control" name="actions" id="actions" onchange="showAction(this.value);" >
											<option value="">-</option>
											
											<optgroup label="Game">
												<option value="game_0_addscript">add script</option>
												<option value="game_0_end">end</option>
												<option value="game_0_pause">pause</option>
												<option value="game_0_jump">go to</option>
											</optgroup>
											
											<?php
											if (count($story->characters())>0){
											?>
												<optgroup label="Characters">
													<?php
													foreach ($story->characters() as $character){
														if (in_array($character->id,$params["characters_id"])){
														?>
															<option value="character_<?php echo $character->id;?>_hide"><?php echo $character->name;?> hide</option>
															<option value="character_<?php echo $character->id;?>_menu"><?php echo $character->name;?> menu</option>
															<option value="character_<?php echo $character->id;?>_move"><?php echo $character->name;?> move</option>
															<option value="character_<?php echo $character->id;?>_say"><?php echo $character->name;?> say</option>
															<option value="character_<?php echo $character->id;?>_show"><?php echo $character->name;?> show</option>
														<?php
														}
													}
													?>											
												</optgroup>										
											<?php
											}
											
											if (count($story->backgrounds())>0){
											?>
												<optgroup label="Backgrounds">
													<?php
													foreach ($story->backgrounds() as $background){
														if (in_array($background->id,$params["backgrounds_id"])){
														?>
															<option value="background_<?php echo $background->id;?>_hide"><?php echo $background->name;?> hide</option>												
															<option value="background_<?php echo $background->id;?>_show"><?php echo $background->name;?> show</option>
														<?php
														}
													}
													?>											
												</optgroup>										
											<?php
											}
											
											if (count($story->things())>0){
											?>
												<optgroup label="Things">
													<?php
													foreach ($story->things() as $thing){
														if (in_array($thing->id,$params["things_id"])){
														?>
															<option value="thing_<?php echo $thing->id;?>_hide"><?php echo $thing->name;?> hide</option>
															<option value="thing_<?php echo $thing->id;?>_show"><?php echo $thing->name;?> show</option>
														<?php
														}
													}
													?>											
												</optgroup>										
											<?php
											}
											
											if (count($story->musics())>0){
											?>
												<optgroup label="Musics">
													<?php
													foreach ($story->musics() as $music){
														?>
														<option value="music_<?php echo $music->id;?>_play"><?php echo $music->name;?> play</option>												
														<option value="music_<?php echo $music->id;?>_stop"><?php echo $music->name;?> stop</option>
														<option value="music_<?php echo $music->id;?>_queue"><?php echo $music->name;?> queue</option>
														<?php
													}
													?>											
												</optgroup>
											<?php
											}
											?>
										</select>
									</div>									
								</div>	
								
								<div id="bloc_say" class="action_type form-group">
									<label for="say" class="col-md-4 control-label">Say</label>

									<div class="col-md-12">
										<textarea class="action_info form-control" type="text" name="say" id="say" rows="10" ></textarea>
									</div>
								</div>
								
								<div id="bloc_addscript" class="action_type form-group">
									<label for="addscript" class="col-md-4 control-label">Script</label>

									<div class="col-md-12">
										<textarea class="action_info form-control" type="text" name="addscript" id="addscript" rows="10" ></textarea>
                                                  <br/>
                                                  <i class="fa fa-info"></i>&nbsp;Informations<br/>Add 5 spaces for indent.<br/>Use $my_var=my_var+1 to affect it<br/>Use [my_var] to use it.
									</div>
								</div>
								
								<div id="bloc_behaviour" class="action_type form-group">
									<label for="behaviour" class="col-md-4 control-label">Behaviour</label>

									<div class="col-md-12">
										<select class="action_info form-control" name="behaviours" id="behaviours">
											<option value=""></option>
										</select>									
									</div>
								</div>
								
								<div id="bloc_different" class="action_type form-group">
									<label for="different" class="col-md-4 control-label">Different</label>

									<div class="col-md-12">
										<select class="action_info form-control" name="differents" id="differents">
											<option value=""></option>
										</select>									
									</div>
								</div>
								
								<div id="bloc_menu" class="action_type form-group">
									<label for="say" class="col-md-4 control-label">Menu</label>

									<div class="col-md-12">
										<ul>
											<li style="list-style-type:none">
												<input style="margin-bottom:5px;" class="action_info form-control" type="text" name="menu_title" value="" id="menu_title" placeholder="Sentence" />
											</li>
											<?php
											for ($k=1;$k<=4;$k++){
												?>
												<li>
													<input style="margin-bottom:5px;" class="action_info form-control" type="text" name="menu<?php echo $k;?>" value="" id="menu<?php echo $k;?>" placeHolder="Choice <?php echo $k;?>" />
													<div>
														<select style="width:90%;display:inline-block;" class="action_info menus form-control" name="menu<?php echo $k;?>_to" id="menu<?php echo $k;?>_to">
															<option value="0">-</option>
															<?php
															foreach ($story->scenes() as $scen){
																?>
																<option value="<?php echo $scen->id;?>">Go to <?php echo $scen->name;?></option>
																<?php
															}
															?>									
														</select>
														&nbsp;<i title="Add scene" class="fa fa-plus" onclick="addScene(<?php echo $story->id;?>,'menu<?php echo $k;?>_to')"></i>
													</div>
													<br/>
												</li>
												<?php
											}
											?>		
										</ul>
									</div>
								</div>
								
								<div id="bloc_jump" class="action_type form-group">
									<label for="jump" class="col-md-4 control-label">Go to scene</label>

									<div class="col-md-12">
										<ul>
											<li>
												<div>
													<select style="width:90%;display:inline-block;" class="action_info menus form-control" name="jump" id="jump">
														<option value="0">-</option>
														<?php
														foreach ($story->scenes() as $scen){
															?>
															<option value="<?php echo $scen->id;?>">Go to <?php echo $scen->name;?></option>
															<?php
														}
														?>									
													</select>
													&nbsp;<i title="Add scene" class="fa fa-plus" onclick="addScene(<?php echo $story->id;?>,'jump')"></i>
												</div>
												<br/>
											</li>												
										</ul>
									</div>
								</div>
								
								<div id="bloc_move" class="action_type form-group">
									<label for="say" class="col-md-4 control-label">Move</label>

									<div class="col-md-12">
										<select class="action_info form-control" name="move" id="move">
											<option value="left"><-</option>
											<option value="right">-></option>
											<option value="center"><-></option>
											<option value="truecenter">^</option>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-8 col-md-offset-4">
										<input type="hidden" id="element" name="element" value=""/>
										<input type="hidden" id="action_id" name="action_id" value="0"/>
										<input type="hidden" id="subject_id" name="subject_id" value=""/>
										<input type="hidden" id="story_id" name="story_id" value="<?php echo $story->id;?>"/>
										<input type="hidden" id="scene_id" name="scene_id" value="<?php echo $scene->id;?>"/>
										<input type="hidden" id="verb" name="verb" value=""/>
										<input type="hidden" id="info" name="info" value=""/>
										
										<button id="btn_add" class="btn btn-primary" onclick="$('#action_id').val(0);addAction(<?php echo $scene->story_id;?>,<?php echo $scene->id;?>)">
											Add
										</button>
										
										<button id="btn_update" style="display:none" class="btn btn-primary" onclick="addAction(<?php echo $scene->story_id;?>,<?php echo $scene->id;?>)">
											Update
										</button>	
									</div>
								</div>
							<?php
							}
							?>
						</div>
						
						<div class="col-md-4">
							<h3>Summary</h3>
							
							<div class="form-group">
								<label for="say" class="col-md-4 control-label">Actions list</label>
								<select id="list_actions" multiple class="form-control" style="height:600px">
								
								</select>
							</div>
							
							<div class="form-group">
								<button class="btn btn-primary" onclick="deleteAction(<?php echo $scene->story_id;?>,<?php echo $scene->id;?>, $('#list_actions').val())">
									Delete
								</button>
							</div>
						</div>
					</div>						
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
