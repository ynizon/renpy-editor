@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h2 class="card-header">Edit</h2>
                <div class="card-body">
					{!! Form::model($story, ['route' => ['story.update', $story->id],'files'=>true, 'method' => $method, 'class' => 'form-horizontal panel']) !!}
                        {{ csrf_field() }}

					<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{!! $story->name !!}" required autofocus />

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('picture') ? ' has-error' : '' }}">
                            <label for="picture" class="col-md-4 control-label">Cover&nbsp;&nbsp;(import from a url)								
							</label>

                            <div class="col-md-6">								
                                <input id="picture" placeholder="https://" type="text" class="form-control" name="picture" value="{!! $story->picture !!}" />
                                   <br/>Or upload a file (.png)
                                   <input id="picture_file"  type="file" class="form-control" name="picture_file"  />

                                @if ($errors->has('picture'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('picture') }}</strong>
                                    </span>
                                @endif
                            </div>
                         </div>
                         
                        <div class="form-group{{ $errors->has('lang') ? ' has-error' : '' }}">
                            <label for="lang" class="col-md-4 control-label">Lang</label>

                            <div class="col-md-6">
                                <input type="text" id="country"  value="{!! $story->lang !!}" name="lang" required />

                                @if ($errors->has('lang'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lang') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
					<div class="form-group{{ $errors->has('width') ? ' has-error' : '' }}">
                            <label for="width" class="col-md-4 control-label">Width (for picture resizing)</label>

                            <div class="col-md-6">
                                <input id="width" type="text" class="form-control" name="width" value="{!! $story->width !!}" required />

                                @if ($errors->has('width'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('width') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
					<div class="form-group{{ $errors->has('height') ? ' has-error' : '' }}">
                            <label for="height" class="col-md-4 control-label">Height (for picture resizing)</label>

                            <div class="col-md-6">
                                <input id="height" type="text" class="form-control" name="height" value="{!! $story->height !!}" required />

                                @if ($errors->has('height'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('height') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('inventory') ? ' has-error' : '' }}">
                            <label for="inventory" class="col-md-4 control-label">Inventory</label>

                            <div class="col-md-6">
                                <select id="inventory" class="form-control" name="inventory">
                                     <option <?php if ($story->inventory == 1){echo "selected";} ?>  value="0">Without</option>
                                     <option <?php if ($story->inventory == 1){echo "selected";} ?> value="1">With</option>
                                </select>

                                @if ($errors->has('inventory'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('inventory') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
					<div class="form-group{{ $errors->has('starting_script') ? ' has-error' : '' }}">
                            <label for="starting_script" class="col-md-4 control-label">Starting Script</label>

                            <div class="col-md-6">
                                <textarea placeholder="default my_var = 0" id="starting_script" rows="6" class="form-control" name="starting_script" value="{!! $story->starting_script !!}" ></textarea>

                                @if ($errors->has('starting_script'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('starting_script') }}</strong>
                                    </span>
                                @endif
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
