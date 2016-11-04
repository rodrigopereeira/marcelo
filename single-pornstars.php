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

			<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
			
				<!-- top page -->
				<div class="top-page">
					
					<?php if(get_field('featured_content')) {?>
					<!-- featured content -->
					<div class="featured-content-pornstar">
						<?php the_field('featured_content') ?>
					</div>
					<!-- end featured content -->
					<?php }?>

					<!-- photo pornstar -->
					<div class="photo-pornstar-single">
						<?php foxtemas_thumbnail('pornstar_profile', false); ?>
					</div>
					<!-- end photo pornstar -->

					<!-- infos pornstar single -->
					<div class="infos-pornstar-single">
						
						<!-- name and filter pornstar -->
						<div class="name-filter-pornstar">
							
							<h1 class="heading-page alignleft heading-pornstar">
								<span><?php the_title();?></span>
							</h1>
							
							<?php foxtemas_menu_filter(); ?>
							
							<div class="clearfix"></div>
						</div>
						<!-- end filter pornstar -->

						<div class="clearfix"></div>
						
						<!-- description pornstar -->
						<div class="description-pornstar">

							<span class="scene-badge">
								<?php foxtemas_count_scenes(get_the_id()); ?>
							</span>

							<div class="text"><?php the_field('description_pornstar'); ?></div>					
						</div>
						<!-- end description pornstar -->
						
						<!-- rating single pornstar -->
						<div class="rating-single-pornstar">
							<?php if(function_exists('the_ratings')) { the_ratings(); } ?>

							<div class="clearfix"></div>
							
							<?php foxtemas_rating_bar(); ?>
						</div>
						<!-- end rating single pornstar -->
						
					</div>
					<!-- end infos pornstar single -->
										
					<div class="clearfix"></div>
				</div>
				<!-- end top page -->


				<?php 
				/**



				LOOP PERSONALIZADO 



				**/

				// paged
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				// ==========================================================================
				//   Filtros
				// ==========================================================================

				/* Filtro Dias */

				if(!empty($_GET['filter'])) {
					// Dia / semana / Mês filter
					function filter_where( $where = '' ) {

						$filter_days = '';

						if($_GET['filter'] == 'day') {
							$filter_days = '1';
						} 
						if ($_GET['filter'] == 'week') {
							$filter_days = '7';
						}
						if($_GET['filter'] == 'month') {
							$filter_days = '30';
						}

					    // posts in the last 30 days
					    $where .= " AND post_date > '" . date('Y-m-d', strtotime('-'.$filter_days.' days')) . "'";
					    return $where;
					}

					add_filter( 'posts_where', 'filter_where' );
				}


				// Filtro order mais votados e mais visualizados (wp-postviews | wp-postratings - plugins )
				if(!empty($_GET['order'])) {

					if($_GET['order'] == 'top') {
						// 
						// mais votados
						// 
						$args_main = array(
							'post_type' => 'post',
							'paged' => $paged,
							'meta_key' => 'ratings_average', 
							'orderby' => 'meta_value_num', 
							'order' => 'DESC',
							'meta_query' => array(
								array(
									'key' => 'models', 
									'value' => get_the_ID(), 
									'compare' => 'LIKE'
									),
								),
							);
					} elseif($_GET['order'] == 'views') {
						// 
						// mais visualizados
						//
						$args_main = array(
							'post_type' => 'post',
							'paged' => $paged,
							'meta_key' => 'views', 
							'orderby' => 'meta_value_num', 
							'order' => 'DESC',
							'meta_query' => array(
								array(
									'key' => 'models', 
									'value' => get_the_ID(), 
									'compare' => 'LIKE'
									),
								),
							);
					} 

				} else {
					$args_main = array(
						'post_type' => 'post',
						'paged' => $paged,

						'meta_query' => array(
							array(
								'key' => 'models', 
								'value' => get_the_ID(), 
								'compare' => 'LIKE'
								),
							),
						);
				}


				$wp_query = new WP_Query( $args_main ); ?>

				<?php remove_filter( 'posts_where', 'filter_where' ); // Remove filtro dias ?>
				
				<?php if ( $wp_query->have_posts() ) : $i = 1; ?>
				
					<ul class="list-posts">

					<!-- the loop -->
					<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); 

						// vars
						$duration = get_field('duration');
						$video_hd = get_field('video_hd');

					?>
					
						<li class="item-<?php echo $i;?>">
							<!-- thumb post -->
							<div class="thumb-post">
								
								<?php if($duration) {?>
								<span class="time">
									<?php echo $duration;?>
								</span>
								<?php }?>
								
								<span class="rating"><span class="fa fa-thumbs-up"></span> <?php foxtemas_rating_percentage(); ?></span>

								<?php if($video_hd == '1') {?>
								<span class="video-hd">HD</span>
								<?php }?>

								<?php foxtemas_badge_new(); ?>

								<a href="<?php the_permalink();?>" title="<?php the_title();?>" class="absolute-thumb-post no-text"><?php the_title();?></a>

								<div class="cover">
									<?php foxtemas_thumbnail('capa_video', false); ?>
								</div>

							</div>
							<!-- end thumb post -->

							<!-- name post -->
							<div class="name-post">
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
					<!-- end of the loop -->

					</ul>

					<div class="clearfix"></div>
					
					
					<?php if(function_exists('foxtemas_pagenavi')) foxtemas_pagenavi(); ?>
					
					
					<?php wp_reset_postdata(); wp_reset_query(); ?>
				
				<?php else:  ?>
					<p class="aligncenter"><?php _e( 'Sorry, no posts matched your criteria.', 'onixxx' ); ?></p>
				<?php endif; 
				/**



				FIM LOOP PERSONALIZADO 



				**/?>	


			<?php endwhile; ?>
			
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