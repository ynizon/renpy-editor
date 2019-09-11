@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="card">
                <h2 class="card-header">Edit</h2>
                <div class="card-body">
					{!! Form::model($music, ['route' => ['music.update', $music->id], 'method' => $method, 'class' => 'form-horizontal panel']) !!}
                        {{ csrf_field() }}
						<input type="hidden"  name="story_id" value="{!! $story->id !!}" />

						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">								
                                <input id="name" type="text" class="form-control" name="name" value="{!! $music->name !!}" required autofocus />

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="form-group{{ $errors->has('music') ? ' has-error' : '' }}">
                            <label for="music" class="col-md-4 control-label">Music&nbsp;&nbsp;<a title="Random music generator" href='http://tones.wolfram.com' target="_blank"><i class="fa fa-link"></i></a></label>

                            <div class="col-md-6">								
                                <input id="music" type="text" class="form-control"  placeholder="https://" name="music" value="{!! $music->music !!}" required />

                                @if ($errors->has('music'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('music') }}</strong>
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
