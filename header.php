<?php global $foxtemas_options; ?>
<?php

if($foxtemas_options['html_minify'] == true) {

    // ==========================================================================
    //   Minify HTML
    // ==========================================================================
    function sanitize_output($buffer) {

        $search = array(
            '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
            '/[^\S ]+\</s',  // strip whitespaces before tags, except space
            '/(\s)+/s'       // shorten multiple whitespace sequences
        );

        $replace = array(
            '>',
            '<',
            '\\1'
        );

        $buffer = preg_replace($search, $replace, $buffer);

        return $buffer;
    }

    ob_start("sanitize_output");


}

?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->

<head>

<meta charset="utf-8">
<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1"> -->
<title><?php wp_title('|', true, 'right'); bloginfo('name');?></title>
<meta name="description" content="<?php bloginfo('description');?>" />

<?php
/* Head WP default itens */
wp_head(); ?>

<?php
/* List link archives wordpress */
wp_get_archives('type=monthly&format=link') ?>

<?php foxtemas_favicons(); ?>
<?php foxtemas_custom_css(); ?>


<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

</head>
<body>

<!-- linha topo -->
<div class="line-top"></div>
<!-- end linha topo -->


<!-- header -->
<header class="header">

    <!-- container header -->
    <div class="wrap-margin container" style="margin-bottom:0;">

        <!-- end container header -->
        <div class="container-header">
            <!-- logo header -->
            <div class="logo-header">
                <?php if(is_home()) {?>
                    <h1 style="margin:0; padding:0;">
                        <span class="no-text"><?php bloginfo('name');?></span>
                        <a href="<?php bloginfo('url');?>" title="<?php bloginfo('name');?>">

                            <?php if($foxtemas_options['logo_header']['url']) {?>
                                <img src="<?php echo $foxtemas_options['logo_header']['url'];?>" alt="logo <?php bloginfo('name');?>">
                            <?php } else {?>
                                <img src="<?php echo FOXTEMAS_THEME_DIR . 'images/logo.png';?>" alt="logo <?php bloginfo('name');?>">
                            <?php } ?>

                        </a>
                    </h1>
                <?php } else {?>
                    <strong>
                        <span class="no-text"><?php bloginfo('name');?></span>
                        <a href="<?php bloginfo('url');?>" title="<?php bloginfo('name');?>">

                            <?php if($foxtemas_options['logo_header']['url']) {?>
                                <img src="<?php echo $foxtemas_options['logo_header']['url'];?>" alt="logo <?php bloginfo('name');?>">
                            <?php } else {?>
                                <img src="<?php echo FOXTEMAS_THEME_DIR . 'images/logo.png';?>" alt="logo <?php bloginfo('name');?>">
                            <?php } ?>

                        </a>
                    </strong>
                <?php } ?>
            </div>
            <!-- end logo header -->

            <?php if($foxtemas_options['hide_search'] != false) : ?>
                <!-- search -->
                <div class="right-header">
                    <div class="search-header">
                        <form class="form-search" method="get" action="<?php echo get_bloginfo('url'); ?>/">
                            <input class="search-input"  type="text" value="Buscar..." name="s"  size="10"  onclick="if (this.value == 'Buscar...') { this.value = ''; }" onblur="if (this.value == '') { this.value = 'Buscar...'; }" />
                            <input type="submit" class="search-btn" value="Buscar">
                        </form>
                    </div>
                  
                </div>
                <!-- end search -->
            <?php endif; ?>

        </div>
        <!-- container header -->
        <div class="clearfix"></div>
    </div>
    <!-- end container header -->

</header>
<!-- end header -->


<!-- wrap shadow -->
<div class="wrap-margin">
    <!-- wrap margem -->
    <div class="wrap-shadow">

        <!-- menus -->
        <nav class="menu-top">

            <?php if(has_nav_menu('menu_topo')) { ?>

                <ul class="sf-menu">
                <?php $args = array(
                    'container'       => '',
                    'fallback_cb'     => 'wp_page_menu',
                    'echo'            => true,
                    'link_before'     => '',
                    'link_after'      => '',
                    'items_wrap'      => '%3$s',
                    'theme_location'    => 'menu_topo',
                );
                wp_nav_menu($args);  ?>
                </ul>

            <?php } else {?>

                <ul class="sf-menu">
                    <li class="current-menu-item"><a href="<?php bloginfo('url');?>" title="Início">Home</a></li>
                    <li><a href="<?php bloginfo('url');?>/?post_type=pornstars" title="Pornstars">Pornstars</a></li>
                </ul>

            <?php }?>


            <div class="clearfix"></div>
        </nav>
        <!-- end menus -->

        <!-- menu top 2 -->
        <?php wpadulto_secundary_menu(); ?>
        <!-- end menu top 2 -->
