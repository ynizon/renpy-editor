@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h2 class="card-header">Edit</h2>
                <div class="card-body">
					{!! Form::model($different, ['route' => ['different.update', $different->id], 'files'=>true,'method' => $method, 'class' => 'form-horizontal panel']) !!}
                        {{ csrf_field() }}
						<input type="hidden"  name="story_id" value="{!! $story->id !!}" />
						<input type="hidden"  name="background_id" value="{!! $background->id !!}" />

						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">								
                                <input id="name" type="text" class="form-control" name="name" value="{!! $different->name !!}" required autofocus />

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="form-group{{ $errors->has('picture') ? ' has-error' : '' }}">
                            <label for="picture" class="col-md-4 control-label">Picture&nbsp;&nbsp;(import from a url)
								&nbsp;&nbsp;
								<a target="_blank" href='https://cloudnovel.net/browse/free/background/popular'><i class="fa fa-link"></i></a>
							</label>

                            <div class="col-md-6">								
                                <input id="picture" placeholder="https://" type="text" class="form-control" name="picture" value="{!! $different->picture !!}" />
								<br/>Or upload a file (.png, .jpg, .gif)
								<input id="picture_file"  type="file" class="form-control" name="picture_file"  />

                                @if ($errors->has('picture'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('picture') }}</strong>
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
