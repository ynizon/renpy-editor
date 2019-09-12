@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
				<div class="card-header"><a href='/home'><?php echo $story->name;?></a>&nbsp;&nbsp;>&nbsp;&nbsp;Scripting success</div>

                <div class="card-body">
					All resources have been downloaded into the public/stories/<?php echo $story->id;?> folder.
					Copy them into your novel ren'py folder, then run the game
					<ul>
						<?php
						//Root
						$rfiles = scandir("stories/".$story->id);
						foreach ($rfiles as $rfile){
							if ($rfile != ".." and $rfile != "."){
								if (!is_dir("stories/".$story->id."/".$rfile)){
									?>
									<li><a href='/stories/<?php echo $story->id;?>/<?php echo $rfile;?>'><?php echo $rfile;?></a></li>
									<?php
								}else{
									?>
									<li>
										<?php echo $rfile;?>
										<ul>
											<?php
											$files = scandir("stories/".$story->id."/". $rfile);
											foreach ($files as $file){
												if ($file != ".." and $file != "."){
													if ($file != ".." and $file != "."){
														if (!is_dir("stories/".$story->id."/".$rfile."/".$file)){
															?><li><a href='/stories/<?php echo $story->id;?>/<?php echo $rfile;?>/<?php echo $file;?>'><?php echo $file;?></a></li><?php
														}else{
															?>
															<li>
																<?php echo $file;?>
																<ul>
																	<?php
																	$sfiles = scandir("stories/".$story->id."/". $rfile."/".$file);
																	foreach ($sfiles as $sfile){
																		if ($sfile != ".." and $sfile != "."){
																			?>
																			<li><a href='/stories/<?php echo $story->id;?>/<?php echo $rfile;?>/<?php echo $file;?>/<?php echo $sfile;?>'><?php echo $sfile;?></a></li>
																			<?php
																		}
																	}
																	?>
																</ul>
															</li>
															<?php
														}													
													}
												}
											}
											?>
										</ul>
									</li>
									<?php
								}
							}
						}						
						?>						
					</ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
