@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><a href='/home'><?php echo $story->name;?></a>&nbsp;&nbsp;>&nbsp;&nbsp;Scene&nbsp;&nbsp;<a href='/story/<?php echo $story->id;?>/scene/create'><i class="fa fa-plus"></i></a>&nbsp;&nbsp;</div>

               
                <div class="card-body">                   
                    <div class="col-md-12">							
                         @include('story/part_line', ['story' => $story])
                        <hr/>
                   </div>
                    <ul>
						<?php
						foreach ($scenes as $scene){
							?>
							<li>
								<a href='/scene/<?php echo $scene->id;?>/edit'><?php echo $scene->name;?></a>
								&nbsp;&nbsp;
								<a href='/scene/<?php echo $scene->id;?>/edit'><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
								<a title="Duplicate the scene" href='/scene/<?php echo $scene->id;?>/duplicate'><i class="fa fa-clone"></i></a>&nbsp;&nbsp;
								<?php
								if ($scene->noremove != 1){
								?>
									{!! Form::open(['method' => 'DELETE', "style"=>"display:inline",'route' => ['scene.destroy', $scene->id]]) !!}
										&nbsp;<a href="#" class="pointer" title="Supprimer" onclick="if (confirm('Confirm delete... ?')){$(this).parent().submit();}"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;
									{!! Form::close() !!}
								<?php
								}
								?>								
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
