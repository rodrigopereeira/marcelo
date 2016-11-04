<?php 
	// File Security Check
	if ( ! defined( 'ABSPATH' ) ) exit;



	// ==========================================================================
	//   Register Sidebar
	// ==========================================================================
	register_sidebar(array(
		'name' => 'Sidebar',
		'id' => 'sidebar',
		'description' => 'Insira aqui as widgets da lateral do site',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '<div class="clearfix"></div></li>',
		'before_title' => '<h3 class="widgettitle"><span class="fa fa-arrow-down"></span> <span class="title">',
		'after_title' => '</span></h3>',
	));


	register_sidebar(array(
		'name' => 'Sidebar Single',
		'id' => 'sidebar-single',
		'description' => 'Insira aqui as widgets da lateral do site',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '<div class="clearfix"></div></li>',
		'before_title' => '<h3 class="widgettitle"><span class="fa fa-arrow-down"></span> <span class="title">',
		'after_title' => '</span></h3>',
	));

?>