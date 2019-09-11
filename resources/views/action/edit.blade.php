@extends('layouts.app')

@section('content')
<?php
use App\Character;
use App\Music;
$params = $scene->getParams();
$action_params = $action->getParams();
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h2 class="card-header">Edit</h2>
                <div class="card-body">
					{!! Form::model($action, ['route' => ['action.update', $action->id], 'method' => $method, 'class' => 'form-horizontal panel']) !!}
                        {{ csrf_field() }}
						<input type="hidden"  name="story_id" value="{!! $story->id !!}" />

						<div class="form-group{{ $errors->has('scene') ? ' has-error' : '' }}">
							<label for="name" class="col-md-4 control-label">Scene</label>
							 
							<div class="col-md-6">								
								<select id="scene" class="form-control" name="scene" onchange="window.location = '?scene_id='+this.value">
									<option value="0">-</option>
									<?php
									foreach ($story->scenes() as $scen){
										?>
										<option <?php if ($scen->id==$scene->id){echo "selected";} ;?> value="<?php echo $scen->id;?>"><?php echo $scen->name;?></option>
										<?php
									}
									?>									
								</select>
							</div> 
						</div>
						
						<div class="form-group{{ $errors->has('end') ? ' has-error' : '' }}">
							<label for="end" class="col-md-4 control-label">End after the dialog ?</label>
							 
							<div class="col-md-6">								
								<select id="end" class="form-control" name="end" >
									<option <?php if (0==$action->end){echo "selected";} ;?> value="0">No</option>
									<option <?php if (1==$action->end){echo "selected";} ;?> value="1">Yes</option>
								</select>
							</div> 
						</div>
						
						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name (prefix with a number like 001 for ordering actions)</label>

                            <div class="col-md-6">								
                                <input id="name" type="text" class="form-control" name="name" value="{!! $action->name !!}" required autofocus />

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="form-group{{ $errors->has('behaviour') ? ' has-error' : '' }}">
							 <label for="behaviour" class="col-md-4 control-label">Character and behaviour</label>
							 
							<div class="col-md-6">								
								<select id="behaviour" class="form-control" name="behaviour" >
									<option value="0">-</option>
									
									<?php					
									foreach ($params["characters_id"] as $character_id){
										$character = Character::find($character_id);
										?>
										<optgroup label="<?php echo $character->name;?>">
											<?php
											foreach ($character->behaviours() as $behaviour){
												?>
												<option <?php if($behaviour->id == $action_params["behaviour_id"]){echo "selected";} ?> value="<?php echo $behaviour->id;?>"><?php echo $behaviour->name;?></option>
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
						
						<div class="form-group{{ $errors->has('say') ? ' has-error' : '' }}">
                            <label for="say" class="col-md-4 control-label">Say</label>

                            <div class="col-md-6">								
                                <textarea id="say" type="text" class="form-control" name="say" ><?php echo $action_params["say"];?></textarea>
                            </div>
                        </div>
						
						<?php
						for ($k=1;$k<=4;$k++){
						?>
							<div class="form-group{{ $errors->has('menu<?php echo $k;?>') ? ' has-error' : '' }}">
								<label for="menu<?php echo $k;?>" class="col-md-4 control-label">Menu item<?php echo $k;?></label>

								<div class="col-md-6">								
						<input id="menu<?php echo $k;?>" type="text" class="form-control" name="menu<?php echo $k;?>" value="<?php if (isset($action_params["menu".$k])){echo $action_params["menu".$k];}?>" />
									<select  class="form-control" name="menu<?php echo $k;?>_to" >
										<option value="0">-</option>
										<?php									
										foreach ($story->scenes() as $scen){
											?>
											<option <?php if ($action_params["menu".$k."_to"] == $scen->id){echo "selected";} ?> value="<?php echo $scen->id;?>">Go to <?php echo $scen->name;?></option>
											<?php
										}
										?>									
									</select>
								</div>
							</div>
						<?php
						}
						?>
						
						<div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>

                            </div>
                        </div>                    
					{!! Form::close() !!}
					
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
