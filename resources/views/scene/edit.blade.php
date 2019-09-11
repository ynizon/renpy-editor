@extends('layouts.app')

@section('content')
<?php
$params = $scene->getParams();
?>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="card">
                <h2 class="card-header">Edit</h2>
                <div class="card-body">
					{!! Form::model($scene, ['route' => ['scene.update', $scene->id], 'method' => $method, 'class' => 'form-horizontal panel']) !!}
                        {{ csrf_field() }}
						<input type="hidden"  name="story_id" value="{!! $story->id !!}" />

						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">								
                                <input id="name" type="text" class="form-control" name="name" value="{!! $scene->name !!}" required autofocus />

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="form-group{{ $errors->has('background') ? ' has-error' : '' }}">
                            <label for="background" class="col-md-4 control-label">Background</label>

                            <div class="col-md-6">								
                                <select id="background" class="form-control" name="background" >
									<option value="0">-</option>
									<?php
									foreach ($story->backgrounds() as $background){
										?>
										<option <?php if ($background->id == $params["background_id"]){echo "selected";} ?> value="<?php echo $background->id;?>"><?php echo $background->name;?></option>
										<?php
									}
									?>									
								</select>
                            </div>
                        </div>
						
						<div class="form-group{{ $errors->has('music') ? ' has-error' : '' }}">
                            <label for="music" class="col-md-4 control-label">Music</label>

                            <div class="col-md-6">								
                                <select id="music" class="form-control" name="music" >
									<option value="0">-</option>
									<?php
									foreach ($story->musics() as $music){
										?>
										<option <?php if ($music->id == $params["music_id"]){echo "selected";} ?> value="<?php echo $music->id;?>"><?php echo $music->name;?></option>
										<?php
									}
									?>									
								</select>
                            </div>
                        </div>
						
						<div class="form-group{{ $errors->has('characters') ? ' has-error' : '' }}">
                            <label for="characters" class="col-md-4 control-label">Characters</label>

                            <div class="col-md-6">								                                
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

                            <div class="col-md-6">								
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

                            </div>
                        </div>                    
					{!! Form::close() !!}
					
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
