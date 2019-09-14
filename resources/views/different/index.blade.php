@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><a href='/home'><?php echo $story->name;?></a>&nbsp;&nbsp;>&nbsp;&nbsp;<a href='/story/<?php echo $story->id;?>/background'>Backgrounds</a>&nbsp;&nbsp;>&nbsp;&nbsp;Different&nbsp;&nbsp;<a href='/story/<?php echo $story->id;?>/background/<?php echo $background->id;?>/different/create'><i class="fa fa-plus"></i></a>&nbsp;&nbsp;</div>

                <div class="card-body">                   
                    <ul>
						<?php
						foreach ($differents as $different){
							?>
							<li>								
								<a href='/different/<?php echo $different->id;?>/edit'><?php echo $different->name;?></a>
								&nbsp;&nbsp;
								<a href='/different/<?php echo $different->id;?>/edit'><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
								{!! Form::open(['method' => 'DELETE', "style"=>"display:inline",'route' => ['different.destroy', $different->id]]) !!}
									&nbsp;<a href="#" class="pointer" title="Supprimer" onclick="if (confirm('Confirm delete... ?')){$(this).parent().submit();}"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;
								{!! Form::close() !!}
								<div style="width:100px;height:100px;" >
									<?php
									$url = $different->picture;
									if ($url == ""){
										$url = "/stories/".$story->id."/images/".Helpers::encName($different->background->name)."-".Helpers::encName($different->name).".png";
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
