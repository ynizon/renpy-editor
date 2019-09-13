@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h2 class="card-header">403 - Forbidden</h2>
                <div class="card-body">
					<p >Access forbidden
					</p>
					<a style="color:#fff;cursor:pointer;" class="btn btn-primary btn-round btn-block" onclick='window.history.back()'>Back</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection