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

			<!-- top page -->
			<div class="top-page">
				<span style="float:left; margin-right: 5px;" class="heading-page">Resultados de: </span>
				<h1 class="heading-page alignleft">
					<?php the_search_query(); ?>
				</h1>
								
				<div class="clearfix"></div>
			</div>
			<!-- end top page -->

			<?php if (have_posts()) : $i = 1; ?>

				<ul class="list-posts">

				<?php while (have_posts()) : the_post(); 
					// vars
					$duration = get_field('duration');
					$video_hd = get_field('video_hd');
                    $patrocionado = get_field('modo_patrocionado');
                    $url_patrocinado = get_field('url_patrocionada');
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

							<?php if($patrocionado == true) {?>
                            <a href="<?php echo $url_patrocinado;?>" title="<?php the_title();?>" rel="nofollow" target="_blank" class="absolute-thumb-post no-text">
                            <?php } else {?>
                            <a href="<?php the_permalink();?>" title="<?php the_title();?>" class="absolute-thumb-post no-text">
                            <?php }?>
                                <?php the_title();?>
                            </a>

							<div class="cover">
								<?php foxtemas_thumbnail('capa_video', false); ?>
							</div>

						</div>
						<!-- end thumb post -->

						<!-- name post -->
						<div class="name-post">
							<h2>
								<?php if($patrocionado == true) {?>
                                <a href="<?php echo $url_patrocinado;?>" title="<?php the_title();?>" rel="nofollow" target="_blank">
                                <?php } else {?>
                                <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                                <?php } ?>
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

				<div class="clearfix"></div>
				
				<?php if(function_exists('foxtemas_pagenavi')) foxtemas_pagenavi(); ?>

			<?php else : ?>

				<div class="entry">
					<center>
						<img src="<?php echo $foxtemas_options['logo_header']['url'];?>" alt="logo <?php bloginfo('name');?>">
						<h2>
							Busca não encontrada
						</h2>
					
						<p class="aligncenter">
							Desculpe, mas não podemos encontrar nenhum resultado relacionado a sua busca: "<b><?php the_search_query();?></b>".<br>
							Tente fazer sua busca novamente.
						</p>


						<div class="search-404">
							<form class="form-search" method="get" action="<?php echo get_bloginfo('url'); ?>/">
								<input class="search-input"  type="text" value="Buscar..." name="s"  size="10"  onclick="if (this.value == 'Buscar...') { this.value = ''; }" onblur="if (this.value == '') { this.value = 'Buscar...'; }" />
								<input type="submit" class="search-btn" value="Buscar">
							</form>
						</div>
					</center>

					<div class="clearfix"></div>
				</div>

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