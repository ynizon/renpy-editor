<a title="Manage characters" href='/story/<?php echo $story->id;?>/character'><i class="fa fa-male"></i> (<?php echo count($story->characters());?>)</a>&nbsp;&nbsp;
<a title="Manage backgrounds" href='/story/<?php echo $story->id;?>/background'><i class="fa fa-photo"></i> (<?php echo count($story->backgrounds());?>)</a>&nbsp;&nbsp;
<a title="Manage things" href='/story/<?php echo $story->id;?>/thing'><i class="fa fa-shopping-basket"></i> (<?php echo count($story->things());?>)</a>&nbsp;&nbsp;
<a title="Manage musics" href='/story/<?php echo $story->id;?>/music'><i class="fa fa-music"></i> (<?php echo count($story->musics());?>)</a>&nbsp;&nbsp;
<a title="Manage scenes and actions" href='/story/<?php echo $story->id;?>/scene'><i class="fa fa-tasks"></i> (<?php echo count($story->scenes());?>)</a>&nbsp;&nbsp;
<a title="View the decision tree" href='/story/<?php echo $story->id;?>/tree'><i class="fa fa-tree"></i></a>&nbsp;&nbsp;