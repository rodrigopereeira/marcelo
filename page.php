<?php get_header(); ?>

<!-- container -->
<div class="container">
	
	<div class="bg-container">
			
		<!-- left container -->
		<div class="left-container">
			<?php get_sidebar('sidebar'); ?>
		</div>
		<!-- end left container -->


		<!-- right container -->
		<div class="right-container">
			
			<?php if (have_posts()) :  while (have_posts()) : the_post(); ?>

			<!-- article -->
			<article class="article article-single">
				<!-- name article -->
				<h1 class="name-article" style="margin-top: 0;">
					<?php the_title(); ?>
				</h1>
				<!-- end name article -->
				
				<!-- embed and content -->
				<div class="entry">
					<?php the_content();?>
					<div class="clearfix"></div>
				</div>
				<!-- end embed end content -->

				<div class="clearfix"></div>
			</article>
			<!-- end article -->
			
			<?php endwhile;  else :  endif; ?>

		</div>
		<!-- end right container -->

		<div class="clearfix"></div>
	</div>

	<div class="line-footer"></div>

	<div class="clearfix"></div>
</div>
<!-- end container -->

<?php get_footer();?>