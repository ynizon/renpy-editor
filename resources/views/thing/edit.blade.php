@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h2 class="card-header">Edit</h2>
                <div class="card-body">
					{!! Form::model($thing, ['route' => ['thing.update', $thing->id], 'files'=>true,'method' => $method, 'class' => 'form-horizontal panel']) !!}
                         {{ csrf_field() }}
						<input type="hidden"  name="story_id" value="{!! $story->id !!}" />

					<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">								
                                <input id="name" type="text" class="form-control" name="name" value="{!! $thing->name !!}" required autofocus />

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
					<div class="form-group{{ $errors->has('picture') ? ' has-error' : '' }}">
                            <label for="picture" class="col-md-4 control-label">Picture&nbsp;&nbsp;(import from a url)<a href='https://cloudnovel.net/browse/free/object/popular' target="_blank"><i class="fa fa-link"></i></a></label>

                            <div class="col-md-6">								
                                   <input id="picture" type="text" class="form-control"  placeholder="https://" name="picture" value="{!! $thing->picture !!}"  />
                                   <br/>Or upload a file (.png, .jpg, .gif)
                                   <input id="picture_file"  type="file" class="form-control" name="picture_file"  />

                                @if ($errors->has('picture'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('picture') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                         
                         <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-4 control-label">Description</label>

                            <div class="col-md-6">								
                                <textarea id="description" class="form-control" name="description" >{!! $thing->description !!}</textarea>

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('money') ? ' has-error' : '' }}">
                            <label for="money" class="col-md-4 control-label">Cost</label>

                            <div class="col-md-6">								
                                <input id="money" type="text" class="form-control" name="money" value="{!! $thing->money !!}" required  />

                                @if ($errors->has('money'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('money') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('hp') ? ' has-error' : '' }}">
                            <label for="hp" class="col-md-4 control-label">Health Point</label>

                            <div class="col-md-6">								
                                <input id="hp" type="text" class="form-control" name="hp" value="{!! $thing->hp !!}" required  />

                                @if ($errors->has('hp'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('hp') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('mp') ? ' has-error' : '' }}">
                            <label for="mp" class="col-md-4 control-label">Magic Point</label>

                            <div class="col-md-6">								
                                <input id="mp" type="text" class="form-control" name="mp" value="{!! $thing->mp !!}" required  />

                                @if ($errors->has('mp'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mp') }}</strong>
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
