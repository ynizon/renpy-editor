@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><a href='/home'><?php echo $story->name;?></a>&nbsp;&nbsp;>&nbsp;&nbsp;<a href='/story/<?php echo $story->id;?>/character'>Characters</a>&nbsp;&nbsp;>&nbsp;&nbsp;Behaviour&nbsp;&nbsp;<a href='/story/<?php echo $story->id;?>/character/<?php echo $character->id;?>/behaviour/create'><i class="fa fa-plus"></i></a>&nbsp;&nbsp;</div>

                <div class="card-body">                   
                    <ul>
						<?php
						foreach ($behaviours as $behaviour){
							?>
							<li>								
								<a href='/behaviour/<?php echo $behaviour->id;?>/edit'><?php echo $behaviour->name;?></a>
								&nbsp;&nbsp;
								<a href='/behaviour/<?php echo $behaviour->id;?>/edit'><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
								{!! Form::open(['method' => 'DELETE', "style"=>"display:inline",'route' => ['behaviour.destroy', $behaviour->id]]) !!}
									&nbsp;<a href="#" class="pointer" title="Supprimer" onclick="if (confirm('Confirm delete... ?')){$(this).parent().submit();}"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;
								{!! Form::close() !!}
								<div style="width:100px;height:100px;" >
									<?php
									$url = $behaviour->picture;
									if ($url == ""){
										$url = "/stories/".$story->id."/images/".Helpers::encName($behaviour->character->name)."/".Helpers::encName($behaviour->name).".png";
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
