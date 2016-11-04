<?php 
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;



/**


Post type pornstars


*/
add_action( 'init', 'register_cpt_pornstars' );

function register_cpt_pornstars() {

    $labels = array( 
        'name' => _x( 'Pornstar', 'pornstars' ),
        'singular_name' => _x( 'Pornstars', 'pornstars' ),
        'add_new' => _x( 'Adicionar Nova', 'pornstars' ),
        'add_new_item' => _x( 'Adicionar Nova Pornstar', 'pornstars' ),
        'edit_item' => _x( 'Editar Pornstar', 'pornstars' ),
        'new_item' => _x( 'Nova Pornstar', 'pornstars' ),
        'view_item' => _x( 'Visualizar Pornstar', 'pornstars' ),
        'search_items' => _x( 'Buscar Pornstar', 'pornstars' ),
        'not_found' => _x( 'Não encontrado', 'pornstars' ),
        'not_found_in_trash' => _x( 'Não encontrado na lixeira', 'pornstars' ),
        'parent_item_colon' => _x( 'Parent Pornstars:', 'pornstars' ),
        'menu_name' => _x( 'Pornstars', 'pornstars' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        
        'supports' => array( 'title', 'thumbnail', 'revisions', 'custom-fields' ),
        'taxonomies' => array( 'letras' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-star-filled',
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'pornstars', $args );
}




// ==========================================================================
//   Change name featured image
// ==========================================================================
add_action('do_meta_boxes', 'foxtemas_change_image_box');
function foxtemas_change_image_box()
{
    remove_meta_box( 'postimagediv', 'pornstars', 'side' );
    add_meta_box('postimagediv', __('Foto da pornstar'), 'post_thumbnail_meta_box', 'pornstars', 'side', 'low');

    remove_meta_box( 'postimagediv', 'post', 'side' );
    add_meta_box('postimagediv', __('Capa vídeo'), 'post_thumbnail_meta_box', 'post', 'side', 'low');
}




// ==========================================================================
//   Glossário
// ==========================================================================
// Add new taxonomy, NOT hierarchical (like tags)
add_action( 'init', 'register_taxonomy_letras' );

function register_taxonomy_letras() {

    $labels = array( 
        'name' => _x( 'Letras', 'letras' ),
        'singular_name' => _x( 'Letra', 'letras' ),
        'search_items' => _x( 'Buscar Letras', 'letras' ),
        'popular_items' => _x( 'Letras populares', 'letras' ),
        'all_items' => _x( 'Todas Letras', 'letras' ),
        'parent_item' => _x( 'Parent Letra', 'letras' ),
        'parent_item_colon' => _x( 'Parent Letra:', 'letras' ),
        'edit_item' => _x( 'Editar Letra', 'letras' ),
        'update_item' => _x( 'Atualizar Letra', 'letras' ),
        'add_new_item' => _x( 'Adicionar Nova Letra', 'letras' ),
        'new_item_name' => _x( 'Nova Letra', 'letras' ),
        'separate_items_with_commas' => _x( 'Separate letras with commas', 'letras' ),
        'add_or_remove_items' => _x( 'Adicionar ou Remover Letra', 'letras' ),
        'choose_from_most_used' => _x( 'Escolha entre as letras mais usadas', 'letras' ),
        'menu_name' => _x( 'Letras', 'letras' ),
    );

    $args = array( 
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => false,
        'show_ui' => false,
        'show_tagcloud' => false,
        'show_admin_column' => false,
        'hierarchical' => false,

        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'letras', array('pornstars'), $args );
}




/* When the post is saved, saves our custom data */
function foxtemas_save_first_letter( $post_id ) {

    $screen = get_current_screen();

    // verify if this is an auto save routine. 
    // If it is our form has not been submitted, so we dont want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

    //check location (only run for posts)
    $limitPostTypes = array('pornstars');
    if (!in_array($_POST['post_type'], $limitPostTypes)) 
        return;

    // Check permissions
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;


    // OK, we're authenticated: we need to find and save the data
    $taxonomy = 'letras';

    //set term as first letter of post title, lower case
    wp_set_post_terms( $post_id, strtoupper(substr($_POST['post_title'], 0, 1)), $taxonomy );

    //delete the transient that is storing the alphabet letters
    delete_transient( 'kia_archive_alphabet');
 
}
add_action( 'save_post', 'foxtemas_save_first_letter' );




//create array from existing posts
function foxtemas_run_letters_generator(){
    $taxonomy = 'letras';

    $alphabet = array();
    $posts = get_posts(array('numberposts' => -1) );

    foreach($posts as $p) :  
        //set term as first letter of post title, lower case
        wp_set_post_terms( $p->ID, strtoupper(substr($p->post_title, 0, 1)), $taxonomy );
    endforeach;     
}
add_action('after_switch_theme','foxtemas_run_letters_generator');







/**


Colunas Post type Pornstars


*/
/* Adiciona e remove colunas */
function foxtemas_add_remove_column_pornstars($columns) {
    // remove coluna
    unset( $columns['date'] );
    unset( $columns['title'] );
    unset( $columns['ratings'] );
    unset( $columns['views'] );


    // adiciona coluna
    $columns['photo_model'] = 'Foto modelo';
    $columns['title'] = 'Nome modelo';
    $columns['views'] = 'Views';
 
    return $columns;
}
add_filter( 'manage_edit-pornstars_columns', 'foxtemas_add_remove_column_pornstars' );



/* Conteudo das colunas */
function foxtemas_custom_column_pornstars( $column, $post_id ) {
    switch ( $column ) {
 
        case 'photo_model' :
            
            ?>
                <img src="<?php echo foxtemas_thumbnail('thumbnail', true);?>" style="height:50px; width:50px;" alt="Modelo <?php the_title();?>">
            <?php
            break;
    }
}
add_action( 'manage_pornstars_posts_custom_column' , 'foxtemas_custom_column_pornstars', 10, 2 );



/* Tamanho colunas */
function foxtemas_column_width() {

    $screen = get_current_screen();

    if($screen->post_type == 'pornstars') {

        echo '<style type="text/css">';
        echo '.column-photo_model { width:110px !important; overflow:hidden }';
        echo '</style>';

    }
}
add_action('admin_head', 'foxtemas_column_width');




/**


Colunas Post type Post


*/
/* Adiciona e remove colunas */
function foxtemas_add_remove_column_post($columns) {
    // remove coluna
    
    // adiciona coluna
    $columns['photo_post'] = 'Capa';
    $columns['models'] = 'Modelos';
 
    return $columns;
}
add_filter( 'manage_edit-post_columns', 'foxtemas_add_remove_column_post' );



/* Conteudo das colunas */
function foxtemas_custom_column_post( $column, $post_id ) {

    global $post;

    switch ( $column ) {
    
        case 'photo_post' :
            
            ?>
                <img src="<?php echo foxtemas_thumbnail('thumbnail', true);?>" style="height:80px; width:80px;" alt="Capa <?php the_title();?>">
            <?php

            break; // end photo post

        case 'models' :


            $posts = get_field('models');
            $copy = $posts;

            if( $posts ): ?>

                <?php foreach( $posts as $p ): // variable must NOT be called $post (IMPORTANT) ?>
               
                <a href="<?php bloginfo('url');?>/wp-admin/post.php?post=<?php echo $p->ID; ?>&action=edit"><?php echo get_the_title( $p->ID ); ?></a><?php if (next($copy )) { echo ','; } ?>
                        
                <?php 

                endforeach; 

                endif; 

            break; // end models
    }
}
add_action( 'manage_post_posts_custom_column' , 'foxtemas_custom_column_post', 10, 2 );







/**

Change Post Title

**/
function foxtemas_change_default_title( $title ){
     $screen = get_current_screen();
     if  ( 'pornstars' == $screen->post_type ) {
          $title = 'Nome da Modelo';
     }
     return $title;
}
 
add_filter( 'enter_title_here', 'foxtemas_change_default_title' );






/**

Flush rewrite rules for custom post types.

**/
add_action( 'after_switch_theme', 'bt_flush_rewrite_rules' );

/* Flush your rewrite rules */
function bt_flush_rewrite_rules() {
     flush_rewrite_rules();
}
?>