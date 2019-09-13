@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<a href='/home'><?php echo $story->name;?></a>&nbsp;&nbsp;>&nbsp;&nbsp;
				Characters&nbsp;&nbsp;<a href='/story/<?php echo $story->id;?>/character/create'><i class="fa fa-plus"></i></a>&nbsp;&nbsp;</div>

                <div class="card-body">                   
                    <ul>
						<?php
						foreach ($characters as $character){
							?>
							<li>
								<a href='/character/<?php echo $character->id;?>/edit'><?php echo $character->name;?></a>
								&nbsp;&nbsp;								
								<a href='/character/<?php echo $character->id;?>/edit'><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
								{!! Form::open(['method' => 'DELETE', "style"=>"display:inline",'route' => ['character.destroy', $character->id]]) !!}
									&nbsp;<a href="#" class="pointer" title="Supprimer" onclick="if (confirm('Confirm delete... ?')){$(this).parent().submit();}"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;
								{!! Form::close() !!}
								<a href='/story/<?php echo $character->story_id;?>/character/<?php echo $character->id;?>/behaviour'><i class="fa fa-sitemap"></i>(<?php echo count($character->behaviours());?>)</a>&nbsp;&nbsp;
								<div style="width:100px;height:100px;" >
								<?php
									$behaviours = $character->behaviours();
									$url = "";
									if ($behaviours->first() != null){
										$url = $behaviours->first()->picture;	
										if ($url == ""){
											$url = "/stories/".$story->id."/images/".Helpers::encName($character->name)."/".Helpers::encName($behaviours->first()->name).".png";
										}
									}
									?>
									<img style="max-width: 100%;max-height: 100%;margin: auto;display: block" src="<?php echo $url;?>" />								
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
