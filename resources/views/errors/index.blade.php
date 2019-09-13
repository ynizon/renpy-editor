@extends('layouts.noapp')

@section('content')
	<h3 class="margin-0">500 - Erreur serveur</h3>
	<p>
		<br/><br/>
		<p style="text-align:center"><?php echo $exception->getMessage(). "<br/>ligne ".$exception->getLine()."<br/>Fichier ".$exception->getFile();?>
		</p>
		<a style="color:#fff" class="btn btn-primary btn-round btn-block" onclick='window.history.back()'>Retour</a>
	</p>
@endsection

@section('card-content')
<div class="card-plain">							
	<div class="header">
		<h5>Error 500</h5>
		<span>Server error</span>
	</div>
	
</div>
@endsection