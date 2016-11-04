<?php 

/*
	Template Name: Pornstar Lista
*/

get_header(); ?>

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
			
			<!-- top page -->
			<div class="top-page">
				
				<h1 class="heading-page alignleft">
					<?php foxtemas_page_title();?>
				</h1>
								
				<div class="clearfix"></div>
			</div>
			<!-- end top page -->

			<!-- glossary -->
			<?php foxtemas_letters_menu(); ?>
			<!-- end glossary -->


			<div class="clearfix"></div>
	
			<?php 
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

			$args_pornstars = array(
				'post_type' => 'pornstars',
				'paged' => $paged,
				'orderby' => 'title',
				'order' => 'ASC',
				);

			$wp_query = new WP_Query( $args_pornstars ); ?>
			
			<?php if ($wp_query->have_posts()) : $i = 1;?>
			
				<!-- list pornstars -->
				<ul class="list-pornstars">
					<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
					
						<li class="item-<?php echo $i;?>">
							<!-- thumb post -->
							<div class="photo-pornstar">
								
								<div class="scene-badge">
									<?php foxtemas_count_scenes(get_the_id()); ?>
								</div>

								<span class="rating">
								<span class="fa fa-thumbs-up"></span>
									<?php foxtemas_rating_percentage(); ?>								
								</span>

								<div class="cover">
									<a href="<?php the_permalink();?>">
										<?php foxtemas_thumbnail('pornstar_perfil', false); ?>
									</a>
								</div>

							</div>
							<!-- end thumb post -->

							<!-- name post -->
							<div class="name-pornstar">
								<h2>
									<a href="<?php the_permalink();?>" title="<?php the_title();?>">
										<?php the_title();?>
									</a>
								</h2>
							</div>
							<!-- end name post -->
						</li>
						

						<?php if($i%5 == 0) {?>
						<li class="clearfix"></li>
						<?php }?>

					<?php $i++; endwhile; ?>
				</ul>
				<!-- end list pornstars -->
				
				<div class="clearfix"></div>

				<?php if (function_exists('foxtemas_pagenavi')) foxtemas_pagenavi(); ?>

				<?php wp_reset_postdata(); wp_reset_query(); ?>
			
			<?php else : ?>
			
			<?php endif; ?>
			
		</div>
		<!-- end right container -->

		<div class="clearfix"></div>
	</div>

	<div class="line-footer"></div>

	<div class="clearfix"></div>
</div>
<!-- end container -->

<?php get_footer();?>