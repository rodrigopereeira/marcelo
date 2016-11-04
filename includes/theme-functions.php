<?php 
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/*
    FUNÇÕES DO TEMPLATE
    
    @ foxtemas_favicons();
    @ foxtemas_page_title();
    @ foxtemas_resumo();
    @ foxtemas_remove_recent_comments_style();
    @ foxtemas_first_img();
    @ foxtemas_thumbnail();
    @ foxtemas_mce_buttons();
    @ foxtemas_text_sizes();
    @ foxtemas_pagenavi();
    @ foxtemas_count_scenes();
    @ foxtemas_models();
    @ foxtemas_rating_percentage();
    @ foxtemas_rating_bar();
    @ foxtemas_badge_new();
    @ foxtemas_relacionados();
    @ foxtemas_query_changes();
    @ foxtemas_404_msg();
    @ foxtemas_custom_css();
    @ foxtemas_letters_menu();
*/

global $foxtemas_options, $pagenow;

// ==========================================================================
//   Redirect after active theme
// ==========================================================================

if(isset($_GET['activated']) && $pagenow == "themes.php"){
    header('Location: '.admin_url().'admin.php?page=foxtemas_panel');
}   

    
// ==========================================================================
//   Clean Header Itens
// ==========================================================================
$itens_header_wp = '';
$itens_header_wp = $foxtemas_options['clean_wp_head'];

if($itens_header_wp['1'] == 1) {
remove_action('wp_head', 'feed_links', 2);
}

if($itens_header_wp['2'] == 1) {
remove_action('wp_head', 'feed_links_extra', 3);
}

if($itens_header_wp['3'] == 1) {
remove_action('wp_head', 'rsd_link');
}

if($itens_header_wp['4'] == 1) {
remove_action('wp_head', 'wlwmanifest_link');
}

if($itens_header_wp['5'] == 1) {
remove_action('wp_head', 'index_rel_link');
}

if($itens_header_wp['6'] == 1) {
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
}

if($itens_header_wp['7'] == 1) {
remove_action('wp_head', 'start_post_rel_link', 10, 0);
}

if($itens_header_wp['8'] == 1) {
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
}

if($itens_header_wp['9'] == 1) {
remove_action('wp_head', 'wp_generator');
}




// ==========================================================================
//   Suport JWPlayer
// ==========================================================================
add_shortcode('jwplayer', 'JWP6_Plugin::shortcode' );




// ==========================================================================
//   Suport thumbnails
// ==========================================================================
add_theme_support('post-thumbnails' );
add_image_size('capa_video', 150, 110, true );
add_image_size('pornstar_perfil', 150, 200, true);




// ==========================================================================
//   Disable redirect and active paged in singular pornstars
// ==========================================================================
add_action('template_redirect', 'foxtemas_redirect_single_cpt', 0 );
function foxtemas_redirect_single_cpt() {

    if ( is_singular('pornstars') ) {
        global $wp_query;
        $page = (int) $wp_query->get('page');

        if ( $page > 1 ) {
            // convert 'page' to 'paged'
            $query->set( 'page', 1 );
            $query->set( 'paged', $page );
        }
        
    // prevent redirect
    remove_action( 'template_redirect', 'redirect_canonical' );
    }
}




// ==========================================================================
//   Remove /category/ WP No Category Base Plugin
// ==========================================================================
register_activation_hook(__FILE__, 'no_category_base_refresh_rules');
add_action('created_category', 'no_category_base_refresh_rules');
add_action('edited_category', 'no_category_base_refresh_rules');
add_action('delete_category', 'no_category_base_refresh_rules');


function no_category_base_refresh_rules() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}


register_deactivation_hook(__FILE__, 'no_category_base_deactivate');
function no_category_base_deactivate() {
    remove_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
    no_category_base_refresh_rules();
}


add_action('init', 'no_category_base_permastruct');
function no_category_base_permastruct() {
    global $wp_rewrite, $wp_version;
    if (version_compare($wp_version, '3.4', '<')) {
        $wp_rewrite->extra_permastructs['category'][0] = '%category%';
    } else {
        $wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
    }
}


add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
function no_category_base_rewrite_rules($category_rewrite) {
    
    $category_rewrite = array();
    $categories       = get_categories(array(
        'hide_empty' => false
    ));
    foreach ($categories as $category) {
        $category_nicename = $category->slug;
        if ($category->parent == $category->cat_ID)
            $category->parent = 0;
        elseif ($category->parent != 0)
            $category_nicename = get_category_parents($category->parent, false, '/', true) . $category_nicename;
        $category_rewrite['(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
        $category_rewrite['(' . $category_nicename . ')/page/?([0-9]{1,})/?$']                  = 'index.php?category_name=$matches[1]&paged=$matches[2]';
        $category_rewrite['(' . $category_nicename . ')/?$']                                    = 'index.php?category_name=$matches[1]';
    }
    global $wp_rewrite;
    $old_category_base                               = get_option('category_base') ? get_option('category_base') : 'category';
    $old_category_base                               = trim($old_category_base, '/');
    $category_rewrite[$old_category_base . '/(.*)$'] = 'index.php?category_redirect=$matches[1]';
    
    return $category_rewrite;
}


add_filter('query_vars', 'no_category_base_query_vars');
function no_category_base_query_vars($public_query_vars) {
    $public_query_vars[] = 'category_redirect';
    return $public_query_vars;
}


add_filter('request', 'no_category_base_request');
function no_category_base_request($query_vars) {
    if (isset($query_vars['category_redirect'])) {
        $catlink = trailingslashit(get_option('home')) . user_trailingslashit($query_vars['category_redirect'], 'category');
        status_header(301);
        header("Location: $catlink");
        exit();
    }
    return $query_vars;
}



// ==========================================================================
//   Custom Login
// ==========================================================================
if($foxtemas_options['custom_login'] == true) {

    function custom_url() {
        global $foxtemas_options;
        return $foxtemas_options['logo_url_login'];
    }

    // custom url
    function my_login_logo_url() {
        return custom_url();
    }
    add_filter( 'login_headerurl', 'my_login_logo_url' );

    // custom css
    function my_custom_login() { 
        global $foxtemas_options;
        // vars
        $logo_login = $foxtemas_options['logo_login']['url'];
        $logo_height_login  = $foxtemas_options['logo_height_login'];
        $logo_margin_top = $foxtemas_options['logo_margin_login'];
        $background_login = $foxtemas_options['background_login'];
        ?>

        <style type="text/css">
            body.login { 
                background-color: <?php echo $background_login['background-color'];?>;
                background-image: url(<?php echo $background_login['background-image'];?>);
                background-repeat: <?php echo $background_login['background-repeat'];?> ;
                background-position: <?php echo $background_login['background-position'];?> ;
            }

            body #login {
                padding-top: <?php echo $logo_margin_top;?>;
            }
            
            body.login div#login h1 a {
                <?php if($logo_login) {?>
                background-image: url(<?php echo $logo_login;?>);
                <?php }?>
                padding-bottom: 30px;
                <?php if($logo_height_login) {?>
                height: <?php echo $logo_height_login;?>;
                -webkit-background-size: contain;
                background-size: contain;
                width: auto;
                <?php }?>
            }
        </style>
    <?php }
    add_action( 'login_enqueue_scripts', 'my_custom_login' );
}



// ==========================================================================
//   Favicons
// ==========================================================================
function foxtemas_favicons() {
    global $foxtemas_options;

    if($foxtemas_options['favicon']['url']) {
    ?>

<link rel="icon" type="image/png" href="<?php echo $foxtemas_options['favicon']['url'];?>" sizes="32x32">

<?php }
}




// ==========================================================================
//   Fox temas page title
// ==========================================================================
function foxtemas_page_title() {

    global $post, $wpdb, $wp_query;

    $title = "";

    if(is_home()) {
        $title = "Últimas Atualizações";
    }

    if(is_tag()) {
        $title = single_tag_title();
    }

    if(is_category()) {
        $title = single_cat_title();
    }

    if(is_post_type_archive('pornstars') || is_page_template('templates/template-pornstars.php' )) {
        $title = 'Pornstars';
    }

    if(is_tax('letras')) {
        $term = $wp_query->get_queried_object();
        $term_name = $term->name;

        $title = "Pornstars - " . $term_name;
    }
    echo '<span>'.$title.'</span>';
}




// ==========================================================================
//   Fox temas resumo
// ==========================================================================
function foxtemas_resumo($content = null, $limit = null) {

    global $post;

    /*
        @content: var content
        @limit: number
    */

    if(strlen(strip_tags($content)) > $limit) {
        echo substr(strip_tags($content), 0, $limit) .'[...]';
    } else {
        echo strip_tags($content);
    }

}




// ==========================================================================
//   Remove CSS Comments Header style
// ==========================================================================
function foxtemas_remove_recent_comments_style() {
    global $wp_widget_factory;
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}
add_action('widgets_init', 'foxtemas_remove_recent_comments_style');




// ==========================================================================
//   Fox temas First Img
// ==========================================================================
function foxtemas_first_img() {
    global $post, $posts;
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output    = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    
    if($output >= 1) {
        $first_img = $matches[1][0];
    }

    return $first_img;
}




// ==========================================================================
//   Foxtemas Thumbnail
// ==========================================================================
function foxtemas_thumbnail($size = 'medium', $url = false) {
    global $post;

    if($url == true) {
        // 
        // MOSTRA APENAS URL DA IMAGEM DO POST
        // 

        // thumbnail url
        $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id(), $size);
        $thumbnail_url = $thumbnail_url[0];

        if(has_post_thumbnail()) {
            echo $thumbnail_url;
        } elseif(get_field('capa_externa', $post->ID)) {
            echo get_field('capa_externa', $post->ID);
        } elseif(foxtemas_first_img()) {
            echo foxtemas_first_img();
        } else {
            echo 'http://placehold.it/300&text=Sem Imagem';
        }

    } else {
        // 
        // MOSTRA TAG IMG COM URL
        // 

        if(has_post_thumbnail()) {
            the_post_thumbnail($size); 
        } elseif(get_field('capa_externa', $post->ID)) {
            echo '<img src="'.get_field('capa_externa', $post->ID).'" alt="Imagem Post">';
        }
        elseif(foxtemas_first_img()) {
            echo '<img src="'.foxtemas_first_img().'" alt="imagem post">';
        } else {
            echo '<img src="http://placehold.it/300&text=Sem Imagem" alt="sem imagem">';
        }

    }
}




// ==========================================================================
//   Suporte font-size
// ==========================================================================
// Enable font size & font family selects in the editor
if ( ! function_exists( 'foxtemas_mce_buttons' ) ) {
    function foxtemas_mce_buttons( $buttons ) {
        array_unshift( $buttons, 'fontsizeselect' ); // Add Font Size Select
        return $buttons;
    }
}
add_filter( 'mce_buttons_2', 'foxtemas_mce_buttons' );

// Customize mce editor font sizes
if ( ! function_exists( 'foxtemas_text_sizes' ) ) {
    function foxtemas_text_sizes( $initArray ){
        $initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px";
        return $initArray;
    }
}
add_filter( 'tiny_mce_before_init', 'foxtemas_text_sizes' );




// ==========================================================================
//   Navegação
// ==========================================================================
function foxtemas_pagenavi( $args = array() )
{
    global $wp_query;

    $defaults = array(
        'big_number' => 999999999,
        'base'       => str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
        'format'     => '?paged=%#%',
        'current'    => max( 1, get_query_var( 'paged' ) ),
        'total'      => $wp_query->max_num_pages,
        'prev_next'  => true,
        'next_text'  => '<i class="fa fa-angle-right "></i>',
        'prev_text'  => '<i class="fa fa-angle-left "></i>',
        'end_size'   => 0,
        'mid_size'   => 3,
        'type'       => 'plain',
        'next_text'  => '<i class="fa fa-angle-right "></i>',
        'prev_text'  => '<i class="fa fa-angle-left "></i>',
    );

    $args = wp_parse_args( $args, $defaults );

    extract( $args, EXTR_SKIP );

    if ( $total == 1 ) return;

    $paginate_links = apply_filters( 'foxtemas_pagenavi', paginate_links( array(
        'base'      => $base,
        'format'    => $format,
        'current'   => $current,
        'total'     => $total,
        'prev_next' => $prev_next,
        'end_size'  => $end_size,
        'mid_size'  => $mid_size,
        'type'      => $type,
        'next_text' => $next_text,
        'prev_text' => $prev_text,
    ) ) );

    echo '<div class="pagenavi">';
    echo $paginate_links;
    echo '</div>';
}




// ==========================================================================
//   Counter Scenes Pornstars
// ==========================================================================
function foxtemas_count_scenes($id_post_modelo = null) {

    $posts_with_actu = get_posts(array(
        'meta_query' => array(
            array(
                'key' => 'models', 
                'value' => $id_post_modelo, 
                'compare' => 'LIKE'
                )
            ),
        'showposts' => -1,
        ));

    $total_scenes = count($posts_with_actu);

    if($total_scenes == 1) {
        $total_scenes = '<i class="fa fa-video-camera"></i> ' . $total_scenes . ' Vídeo';
    } else {
        $total_scenes = '<i class="fa fa-video-camera"></i> ' . $total_scenes . ' Vídeos';
    }
    echo '<span>'.$total_scenes.'</span>';
}




// ==========================================================================
//   Models
// ==========================================================================
function foxtemas_models() {

    global $post;

    $posts = get_field('models');
    $copy = $posts;

    if( $posts ): ?>
        <li><span>Atrizes: </span>
        <?php foreach( $posts as $post): ?>
            <?php setup_postdata($post); ?>
            <a href="<?php echo get_permalink(); ?>" class="tooltip" title="&lt;img src=&quot;<?php foxtemas_thumbnail('pornstar_perfil', true);?>&quot; /&gt;"><?php echo get_the_title(); ?></a><?php if (next($copy )) {
                echo ' • '; 
            } ?>

        <?php endforeach; ?>
        </li>
        <?php wp_reset_postdata(); wp_reset_query();  ?>
    <?php endif;
}




// ==========================================================================
//   Rating Percentage
// ==========================================================================
function foxtemas_rating_percentage() {

    if(function_exists('the_ratings')) { 

        global $post;

        $post_data = $post;

        /**

        Rating to Thumb 0 / +2 

        */

        $ratings_max = intval(get_option('postratings_max'));   

        if(is_object($post_data)) {
            $post_id = $post_data->ID;
        } else {
            $post_id = $post_data;
        }

        if(isset($post_data->ratings_users)) {
            $post_ratings_users = intval($post_data->ratings_users);
            $post_ratings_score = intval($post_data->ratings_score);
            $post_ratings_average = floatval($post_data->ratings_average);
        // Most Likely coming from the_ratings_vote or the_ratings_rate
        } else if(isset($post_ratings_data->ratings_users)) {
            $post_ratings_users = intval($post_ratings_data->ratings_users);
            $post_ratings_score = intval($post_ratings_data->ratings_score);
            $post_ratings_average = floatval($post_ratings_data->ratings_average);
        } else {
            if(get_the_ID() != $post_id) {
                $post_ratings_data = get_post_custom($post_id);
            } else {
                $post_ratings_data = get_post_custom();
            }

            $post_ratings_users = is_array($post_ratings_data) && array_key_exists('ratings_users', $post_ratings_data) ? intval($post_ratings_data['ratings_users'][0]) : 0;
            $post_ratings_score = is_array($post_ratings_data) && array_key_exists('ratings_score', $post_ratings_data) ? intval($post_ratings_data['ratings_score'][0]) : 0;
            $post_ratings_average = is_array($post_ratings_data) && array_key_exists('ratings_average', $post_ratings_data) ? floatval($post_ratings_data['ratings_average'][0]) : 0;
        }


        if($post_ratings_score == 0 || $post_ratings_users == 0) {
            $post_ratings = 0;
            $post_ratings_average = 0;
            $post_ratings_percentage = 0;
        } else {
            $post_ratings = round($post_ratings_average, 1);
            $post_ratings_percentage = round((($post_ratings_score/$post_ratings_users)/$ratings_max) * 100, 2);
        }

        echo intval($post_ratings_percentage) . '%';

    }
}


// rating bar
function foxtemas_rating_bar() {
    if(function_exists('the_ratings')) { 
        global $post;

        $rating_score = get_post_meta($post->ID, 'ratings_score', true);
        $rating_users_vote = get_post_meta($post->ID, 'ratings_users', true);

        $no_vote = "";

        if(!$rating_score && !$rating_users_vote) {
            $no_vote = "bg-no-vote";
        } 

        ?>
        <!-- percentage -->
        <span class="percentage-bar <?php echo $no_vote;?>">
            <span class="bg-percentage-bar" style="width:<?php foxtemas_rating_percentage();?>;"></span>
        </span>
        <!-- end percentage -->
        <?php 
    }
}




// ==========================================================================
//   Badge New
// ==========================================================================
function foxtemas_badge_new() {
    global $post;

    $currentdate = date('Ymd',mktime(0,0,0,date('m'),date('d'),date('Y')));  
    $postdate = get_the_time('Ymd', $post->ID); 

    if ($postdate>=$currentdate-3) {  
        echo '<div class="new-badge">Novo</div>';
    }
}




// ==========================================================================
//   Foxtemas Relacionados
// ==========================================================================
function foxtemas_relacionados() {

    global $post;
    $orig_post = $post;
    $categories = get_the_category($post->ID);

    if ($categories) {
        $category_ids = array();
        foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;

        $args=array(
            'post__not_in' => array($post->ID),
            'posts_per_page'=> 15, 
            'orderby' => 'rand'
            );

        $my_query = new wp_query( $args );
        if( $my_query->have_posts() ) {
            $i = 1;
            echo '<span class="heading-mini">Vídeos Relacionados</span>';
            echo '<ul class="list-posts">';
            while( $my_query->have_posts() ) {
                $my_query->the_post();

                // vars
                $duration = get_field('duration');
                $video_hd = get_field('video_hd');
                $patrocionado = get_field('modo_patrocionado');
                $url_patrocinado = get_field('url_patrocionada');

                ?>

                <li class="item-<?php echo $i;?>">
                    <!-- thumb post -->
                    <div class="thumb-post">

                        <span class="time"><?php echo $duration;?></span>
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
                        <span>
                            <?php if($patrocionado == true) {?>
                            <a href="<?php echo $url_patrocinado;?>" title="<?php the_title();?>" rel="nofollow" target="_blank">
                            <?php } else {?>
                            <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                            <?php } ?>
                                <?php the_title();?>
                            </a>
                        </span>
                    </div>
                    <!-- end name post -->
                </li>

                <?php if($i%5 == 0) {?>
                    <li class="clearfix"></li>
                <?php }?>

                <?php $i++; 
            }
            echo '</ul>';
            echo '<div class="clearfix"></div>';
        }
    }
    $post = $orig_post;
    wp_reset_query(); 
} 




// ==========================================================================
//   Fox temas tax letters
// ==========================================================================
function foxtemas_query_changes($query) {

    // Pornstars Archive
    if ( (is_post_type_archive( 'pornstars' ) && !is_admin() && $query->is_main_query()) || (is_page_template('archive-pornstars.php') && !is_admin() && $query->is_main_query()) ) {
        if(!empty($_GET['order'])) {

            // Get Order
            if($_GET['order'] == 'views') {

                $query->set('meta_key', 'views');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');

            } elseif($_GET['order'] == 'top') {

                $query->set('meta_key', 'ratings_average');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');

            }

        } else {

            $query->set('orderby', 'title');
            $query->set('order', 'ASC');

        }

        $query->set('showposts', '20');
    }

    // template pornstars
    if( (get_page_template_slug() == 'templates/template-pornstars.php')) {
        $query->set('post_type', 'pornstars');
        $query->set('showposts', '20');
    }

    // letters
    if(!$query->is_admin && is_tax('letras') && $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('showposts', '20');
    }

    // remove pornstar / page from default search query
    if(!$query->is_admin && $query->is_search && $query->is_main_query()) {
        $query->set('post_type', 'post');
    }

    return $query;
}
add_filter('pre_get_posts','foxtemas_query_changes');




// ==========================================================================
//   Comentário Less
// ==========================================================================
function foxtemas_404_msg() {

    global $foxtemas_optionstions;

    ?>
    
    <center>
        <img src="<?php echo $foxtemas_optionstions['logo_header']['url'];?>" alt="logo <?php bloginfo('name');?>">
        <h2>
            Página não encontrada
        </h2>

        <p>
            Desculpe, mas a página não foi encontrada, ela pode ter sido movida ou removida do site.
        </p>
    </center>

    <?php 

}



// ==========================================================================
//   Custom CSS
// ==========================================================================
function foxtemas_custom_css() {
    global $foxtemas_options;

    if($foxtemas_options['background_pattern_remove'] == true) {
        echo '<style>body { background-image: none; }</style>';
    }

    if($foxtemas_options['ativar_css'] == true) {
        echo '<style>'.$foxtemas_options['custom_css'].'</style>';
    }
}



// ==========================================================================
//   Menu Letras
// ==========================================================================
function foxtemas_letters_menu() {?>

    <?php 
    $taxonomy = 'letras';  
    
    // save the terms that have posts in an array as a transient
    // if ( false === ( $alphabet = get_transient( 'foxtemas_archive_letters' ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
        $terms = get_terms($taxonomy);
    
        $alphabet = array();
        if($terms){
            foreach ($terms as $term){
                $alphabet[] = $term->slug;
            }
        }
    //      set_transient( 'foxtemas_archive_letters', $alphabet );
    // }
    
    ?>
    
    <ul class="letters-menu">
        <?php foreach(range('a', 'z') as $i) : 
            $current = ($i == get_query_var($taxonomy)) ? "current-menu-item" : "menu-item";
    
            if (in_array( $i, $alphabet )){ ?>
                <li class="<?php echo $current;?>">
                    <?php printf('<a href="%s"><span>%s</span></a>', get_term_link( $i, $taxonomy ), strtoupper($i) ) ?>
                </li>
            <?php } else { ?>
                <li class="<?php echo $current;?>">
                   <span> <?php echo strtoupper($i); ?></span>
                </li>
            <?php } ?>  
    
        <?php endforeach; ?>
    </ul>

<?php }
?>