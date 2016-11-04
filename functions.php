<?php
// ==========================================================================
//   File Security Check
// ==========================================================================// 
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'Você não tem permissão suficiente para acessar esse arquivo!' );
}


/*-----------------------------------------------------------------------------------*/
/* Inicio FoxTemas - Por favor evite editar essa parte */
/*-----------------------------------------------------------------------------------*/
require_once ( get_template_directory() . '/functions/functions-init.php' );



/*-----------------------------------------------------------------------------------*/
/* Carrega arquivos específicos para o template
/*-----------------------------------------------------------------------------------*/
$includes = array(
			'includes/theme-css.php',
			'includes/theme-js.php',
			'includes/theme-functions.php',
			'includes/theme-menus.php',
			'includes/theme-post-types.php',
			'includes/theme-widgets.php',
			'includes/theme-comments.php',
			'includes/sidebar-init.php',
			);


foreach ( $includes as $i ) {
	locate_template( $i, true );
}


/*-----------------------------------------------------------------------------------*/
/* Você pode adicionar funções personalizadas a partir daqui*/
/*-----------------------------------------------------------------------------------*/






/*-----------------------------------------------------------------------------------*/
/* Não adicione nenhum código depois daqui ou o céu vai cair :D */
/*-----------------------------------------------------------------------------------*/
?>