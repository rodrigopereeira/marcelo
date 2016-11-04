<?php

// ==========================================================================
//   Styles
// ==========================================================================
function foxtemas_styles()
	{
		// Raleway
		wp_enqueue_style( 'raleway-dft', 'http://fonts.googleapis.com/css?family=Raleway:400,300,600,500,700', false, null );

		// Font Awesome
		wp_enqueue_style( 'font-awesome', FOXTEMAS_THEME_DIR . 'typefaces/font-awesome/css/font-awesome.min.css', false, null );

		// tooltipster
		wp_enqueue_style( 'tooltipster', FOXTEMAS_THEME_DIR . 'includes/tooltipster/css/tooltipster.css', false, null);

		// Style Geral
		wp_enqueue_style('style', FOXTEMAS_THEME_DIR . 'style.css', false, null);

		// Custom CSS
	    // wp_enqueue_style( 'custom-css', FOXTEMAS_PANEL_DIR . 'options/custom-css.css', array('style'), filemtime(FOXTEMAS_PANEL_PATH.'options/custom-css.css'), 'all' );


	}
	add_action('wp_enqueue_scripts', 'foxtemas_styles', 100);
?>
