<div class="sidebar visible-lg visible-md">

	<ul>
		<?php if(is_singular('post') || is_singular('post')) {?>
			<?php dynamic_sidebar('sidebar-single');?>
		<?php } else {?>
			<?php dynamic_sidebar('sidebar');?>
		<?php }?>
	</ul>
</div>
