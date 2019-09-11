@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Stories&nbsp;&nbsp;<a href='/story/create'><i class="fa fa-plus"></i></a>&nbsp;&nbsp;</div>

                <div class="card-body">                   
                    <ul>
						<?php
						foreach ($stories as $story){
							?>
							<li>
								<a href='/story/<?php echo $story->id;?>'><?php echo $story->name;?></a>&nbsp;&nbsp;
								<a href='/story/<?php echo $story->id;?>/edit'><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
								<a href='/story/<?php echo $story->id;?>/duplicate'><i class="fa fa-clone"></i></a>&nbsp;&nbsp;
								<a href='/story/<?php echo $story->id;?>/share'><i class="fa fa-share-alt"></i></a>&nbsp;&nbsp;
								{!! Form::open(['method' => 'DELETE', "style"=>"display:inline",'route' => ['story.destroy', $story->id]]) !!}
									&nbsp;<a href="#" class="pointer" title="Supprimer" onclick="if (confirm('Confirm delete... ?')){$(this).parent().submit();}"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;
								{!! Form::close() !!}
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<a href='/story/<?php echo $story->id;?>/character'><i class="fa fa-male"></i> (<?php echo count($story->characters());?>)</a>&nbsp;&nbsp;
								<a href='/story/<?php echo $story->id;?>/background'><i class="fa fa-photo"></i> (<?php echo count($story->backgrounds());?>)</a>&nbsp;&nbsp;
								<a href='/story/<?php echo $story->id;?>/thing'><i class="fa fa-shopping-basket"></i> (<?php echo count($story->things());?>)</a>&nbsp;&nbsp;
								<a href='/story/<?php echo $story->id;?>/music'><i class="fa fa-music"></i> (<?php echo count($story->musics());?>)</a>&nbsp;&nbsp;
								<a href='/story/<?php echo $story->id;?>/scene'><i class="fa fa-cubes"></i> (<?php echo count($story->scenes());?>)</a>&nbsp;&nbsp;
								<a href='/story/<?php echo $story->id;?>/action'><i class="fa fa-tasks"></i> (<?php echo count($story->actions());?>)</a>&nbsp;&nbsp;<br/>
								<?php echo $story->created_at;?>
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
