<?php 
$patrocionado = get_field('modo_patrocionado');
$url_patrocinado = get_field('url_patrocionada');

if($patrocionado == true) {
	header('refresh: 0; url='.$url_patrocinado.''); // redirect the user after 10 seconds
	#exit; // note that exit is not required, HTML can be displayed.
}

global $foxtemas_options; get_header(); ?>

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
			
			<?php if (have_posts()) :  while (have_posts()) : the_post(); 
				// vars
				$embed = get_field('embed');
				$duration = get_field('duration');
				$video_hd = get_field('video_hd');
                $patrocionado = get_field('modo_patrocionado');
                $url_patrocinado = get_field('url_patrocionada');
			?>


			<!-- article -->
			<article class="article article-single">
				
				<!-- embed and content -->
				<div class="entry">
					
					<?php if($embed) {
						echo do_shortcode($embed);
					} ?>

					<?php the_content();?>
					<div class="clearfix"></div>
				</div>
				<!-- end embed end content -->

				<!-- name article -->
				<h1 class="name-article">
					<?php the_title(); ?>
				</h1>
				<!-- end name article -->


				<?php if($foxtemas_options['ads_switch_single'] == true) {?>
				<!-- adsense single -->
				<div class="adsense-single">
					<?php if($foxtemas_options['ads_single'] && $foxtemas_options['ads_single'] != '                                            ') {
						echo $foxtemas_options['ads_single'];
					} else {?>
						<img src="//placehold.it/800x90" alt="publicidade">
					<?php } ?>
					<div class="clearfix"></div>
				</div>
				<!-- end adsens single -->
				<?php } ?>



				<!-- cats  -->
				<div class="cats-tags-models">
					<ul>
						<li><span>Categorias: </span> <?php the_category( ' • ');?></li>
						<?php if(has_tag()) {?><li><?php the_tags('<span>Tags:</span> ', ' • ', '' );?></li><?php }?>
						<?php foxtemas_models(); ?>
					</ul>
				</div>
				<!-- end cats -->

				<!-- ratings -->
				<div class="rating-single">
					<?php if(function_exists('the_ratings')) { the_ratings(); } ?>

					<div class="clearfix"></div>
					
					<?php foxtemas_rating_bar(); ?>
				</div>
				<!-- end ratings -->
				
				<div class="clearfix"></div>
			</article>
			<!-- end article -->
			
			<?php endwhile;  else :  endif; ?>

			<?php foxtemas_relacionados(); ?>
			
			<!-- comentarios -->
			<div class="comentarios">
			<?php comments_template(); ?>
			</div>
			<!-- end comentarios -->
			
		</div>
		<!-- end right container -->

		<div class="clearfix"></div>
	</div>

	<div class="line-footer"></div>

	<div class="clearfix"></div>
</div>
<!-- end container -->

<?php get_footer();?>