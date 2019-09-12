@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h2 class="card-header">Edit</h2>
                <div class="card-body">
					{!! Form::model($story, ['route' => ['story.update', $story->id], 'method' => $method, 'class' => 'form-horizontal panel']) !!}
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
