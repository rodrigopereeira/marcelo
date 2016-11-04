<?php 
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;




// ==========================================================================
//   Theme Menus
// ==========================================================================
if (function_exists('register_nav_menus')) {
	register_nav_menus(array(
		'menu_topo' => 'Menu Topo',
		'menu_footer' => 'Menu Rodapé',
	));
}
add_theme_support('menus');





// ==========================================================================
//   Menu Secundário
// ==========================================================================
function wpadulto_secundary_menu() {
	?>

		<?php 
		// verify if permalink is default
		if(get_option( 'permalink_structure') == '') {
			$get_on_off = '&';
		} else {
			$get_on_off = '?';
		} ?>

		<div class="menu-top-secundary">
			<ul>
				<?php 
				/*
				Se em home ou single 
				*/
				if(is_home() || is_singular('post')) {?>
					<li><a href="<?php bloginfo('url');?>"><span class="fa fa-refresh"></span> Vídeos Recentes</a></li>
					<li><a href="<?php bloginfo('url');?>/?order=views"><span class="fa fa-eye"></span> Vídeos Mais Visualizados</a></li>
					<li><a href="<?php bloginfo('url');?>/?order=top"><span class="fa fa-star"></span> Vídeos Mais Populares</a></li>

				<?php } 
				/*
				Se na página categoria
				*/
				elseif(is_category()) {
					// cat
					$category = get_category(get_query_var('cat'));
    				$cat_id = $category->cat_ID;
					?>

					<li><a href="<?php echo get_category_link( $cat_id );?>"><span class="fa fa-refresh"></span> Recentes em <?php single_cat_title();?></a></li>
					<li><a href="<?php echo get_category_link( $cat_id );?><?php echo $get_on_off;?>order=views"><span class="fa fa-eye"></span> Mais Visualizados em <?php single_cat_title();?></a></li>
					<li><a href="<?php echo get_category_link( $cat_id );?><?php echo $get_on_off;?>order=top"><span class="fa fa-star"></span> Mais Populares em <?php single_cat_title();?></a></li>

				<?php } 
				/*
				Se na página tags
				*/
				elseif(is_tag()) {
					// tag id
					$tag_id = get_queried_object()->term_id;

					?>
					<li><a href="<?php echo get_tag_link( $tag_id );?>"><span class="fa fa-refresh"></span> Recentes em <?php single_tag_title();?></a></li>
					<li><a href="<?php echo get_tag_link( $tag_id );?><?php echo $get_on_off;?>order=views"><span class="fa fa-eye"></span> Mais Visualizados em <?php single_tag_title();?></a></li>
					<li><a href="<?php echo get_tag_link( $tag_id );?><?php echo $get_on_off;?>order=top"><span class="fa fa-star"></span> Mais Populares em <?php single_tag_title();?></a></li>
				<?php }
				/*
				Se arquivo de pornstars
				*/
				elseif(is_post_type_archive('pornstars') || is_tax('letras') || is_singular('pornstars' ) || is_page_template('my-templates/template-pornstars.php' )) {?>
					<li><a href="<?php echo get_post_type_archive_link('pornstars'); ?>"><span class="fa fa-refresh"></span> Pornstars Inicial</a></li>
					<li><a href="<?php echo get_post_type_archive_link('pornstars'); echo $get_on_off;?>order=views"><span class="fa fa-eye"></span> Pornstars Mais Acessadas</a></li>
					<li><a href="<?php echo get_post_type_archive_link('pornstars'); echo $get_on_off;?>order=top"><span class="fa fa-star"></span> Pornstars Mais Populares</a></li>
					
					
					<?php if(is_singular('pornstars' )) {?>
					<li style="float:right;"><a href="<?php echo get_permalink(get_the_ID()); echo $get_on_off;?>order=top"><span class="fa fa-arrow-down"></span> Mais Populares</a></li>
					<li style="float:right;"><a href="<?php echo get_permalink(get_the_ID()); echo $get_on_off;?>order=views"><span class="fa fa-arrow-down"></span> Mais Visualizados</a></li>
					<li style="float:right;"><a href="<?php echo get_permalink(get_the_ID()); ?>"><span class="fa fa-arrow-down"></span> Recentes</a></li>
					<?php } ?>

				<?php } ?>
			</ul>

			<div class="clearfix"></div>
		</div>
	<?php

}




// ==========================================================================
//   Menu Filter
// ==========================================================================
function foxtemas_menu_filter() {
	global $post, $wpbd;

	if(!empty($_GET['order'])) {

		$name_menu = 'Filtrar';

		if(!empty($_GET['filter'])) {
			

			if($_GET['filter'] == 'day') {
				$name_menu = 'Do dia';
			} 
			if($_GET['filter'] == 'week') {
				$name_menu = 'Da semana';
			}

			if($_GET['filter'] == 'month') {
				$name_menu = 'Do mês';
			}
		}

		?>
	<!-- sort by -->
	<div class="sort-by">
		<div id="dd" class="menu-filter" tabindex="1"><?php echo $name_menu; ?>
			<ul class="dropdown">
				<li><a href="?order=<?php echo $_GET['order'];?>"><i class="fa fa-angle-right icon"></i> Todos</a></li>
				<li><a href="?order=<?php echo $_GET['order'];?>&filter=day"><i class="fa fa-angle-right icon"></i> Do dia</a></li>
				<li><a href="?order=<?php echo $_GET['order'];?>&filter=week"><i class="fa fa-angle-right icon"></i> Da Semana</a></li>
				<li><a href="?order=<?php echo $_GET['order'];?>&filter=month"><i class="fa fa-angle-right icon"></i> Do Mês</a></li>
			</ul>
		</div>
	</div>
	<!-- end sort by -->
	<?php }
}

?>