@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h2 class="card-header">404 - Not found</h2>
                <div class="card-body">
					<p >Page not found
					</p>
					<a style="color:#fff;cursor:pointer;" class="btn btn-primary btn-round btn-block" onclick='window.history.back()'>Back</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection