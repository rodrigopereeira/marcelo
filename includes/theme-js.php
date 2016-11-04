<?php
function foxtemas_scripts() 
	{	
		// modernizr
		wp_enqueue_script( 'modernizr', FOXTEMAS_THEME_DIR . 'includes/js/modernizr.custom.js', '', null, false);

		// tooltipster
		wp_enqueue_script( 'tooltipster', FOXTEMAS_THEME_DIR . 'includes/tooltipster/js/jquery.tooltipster.min.js', array('jquery'), null, true );

		// superfis
		wp_enqueue_script( 'superfish', FOXTEMAS_THEME_DIR . 'includes/superfish/js/superfish.min.js', array('jquery'), null, true );

		// geral
		wp_enqueue_script( 'geral', FOXTEMAS_THEME_DIR . 'includes/js/geral.js', array('jquery'), null, true);
	}
	add_action('wp_enqueue_scripts', 'foxtemas_scripts', 100);
?>