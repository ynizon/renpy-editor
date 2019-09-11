@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h2 class="card-header">Share</h2>
                <div class="alert alert-danger">
					Warning: if you share your project with other people. They can modify / delete all your components. You should backup your job, with the <a href='/story/<?php echo $story->id;?>/duplicate'>duplicate</a> function before.
				</div>
				<div class="card-body">
					{!! Form::model($story, ['route' => ['share', $story->id], 'method' => "post", 'class' => 'form-horizontal panel']) !!}
                        {{ csrf_field() }}

						<div class="form-group{{ $errors->has('emails') ? ' has-error' : '' }}">
                            <label for="emails" class="col-md-4 control-label">Emails (1 by line)</label>

                            <div class="col-md-6">
                                <textarea rows="10" class="form-control" name="emails" value="" required autofocus><?php foreach ($emails as $email){echo $email->email."\r\n";}?></textarea>

                                @if ($errors->has('emails'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('emails') }}</strong>
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
