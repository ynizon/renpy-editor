@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><a href='/home'><?php echo $story->name;?></a>&nbsp;&nbsp;>&nbsp;&nbsp;Thing&nbsp;&nbsp;<a href='/story/<?php echo $story->id;?>/thing/create'><i class="fa fa-plus"></i></a>&nbsp;&nbsp;</div>

                <div class="card-body">                   
                    <ul>
						<?php
						foreach ($things as $thing){
							?>
							<li>
								<a href='/thing/<?php echo $thing->id;?>/edit'><?php echo $thing->name;?></a>
								&nbsp;&nbsp;
								<a href='/thing/<?php echo $thing->id;?>/edit'><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
								{!! Form::open(['method' => 'DELETE', "style"=>"display:inline",'route' => ['thing.destroy', $thing->id]]) !!}
									&nbsp;<a href="#" class="pointer" title="Supprimer" onclick="if (confirm('Confirm delete... ?')){$(this).parent().submit();}"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;
								{!! Form::close() !!}
								<div style="width:100px;height:100px;" >
									<img style="max-width: 100%;max-height: 100%;margin: auto;display: block" src="<?php echo $thing->picture;?>" />
								</div>
							</li>
							<?php
						}
						?>
						
					</ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
