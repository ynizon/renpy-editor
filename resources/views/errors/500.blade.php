@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h2 class="card-header">500 - Server Error</h2>
                <div class="card-body">
					<p><?php echo $exception->getMessage(). "<br/>Line ".$exception->getLine()."<br/>File ".$exception->getFile();?>
					</p>
					<a style="color:#fff;cursor:pointer;" class="btn btn-primary btn-round btn-block" onclick='window.history.back()'>Back</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection