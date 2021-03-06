@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><a href='/home'><?php echo $story->name;?></a>&nbsp;&nbsp;>&nbsp;&nbsp;Background&nbsp;&nbsp;<a href='/story/<?php echo $story->id;?>/background/create'><i class="fa fa-plus"></i></a>&nbsp;&nbsp;</div>

                <div class="card-body">                   
                    <ul>
						<?php
						foreach ($backgrounds as $background){
							?>
							<li>
								<a href='/background/<?php echo $background->id;?>/edit'><?php echo $background->name;?></a>
								&nbsp;&nbsp;
								<a href='/background/<?php echo $background->id;?>/edit'><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
								{!! Form::open(['method' => 'DELETE', "style"=>"display:inline",'route' => ['background.destroy', $background->id]]) !!}
									&nbsp;<a href="#" class="pointer" title="Supprimer" onclick="if (confirm('Confirm delete... ?')){$(this).parent().submit();}"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;
								{!! Form::close() !!}
								<a href='/story/<?php echo $background->story_id;?>/background/<?php echo $background->id;?>/different'><i class="fa fa-sitemap"></i>(<?php echo count($background->differents());?>)</a>&nbsp;&nbsp;
								<div style="width:100px;height:100px;" >
								<?php
									$differents = $background->differents();
									$url = "";
									if ($differents->first() != null){
										$url = $differents->first()->picture;	
										if ($url == ""){
											$url = "/stories/".$story->id."/images/".Helpers::encName($background->name)."-".Helpers::encName($differents->first()->name).".png";
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
