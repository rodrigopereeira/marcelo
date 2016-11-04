<?php 

global $foxtemas_options;

if($foxtemas_options['404_switch'] == true) {
    /*
        Redirecionamento Página inicial
    */
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".get_bloginfo('url'));
    exit();

}


get_header(); ?>

<!-- container -->
<div class="container">
    
    <!-- 404 container -->
    <div class="container-404">

        <?php if($foxtemas_options['404_title']) {?>
            <h1 class="name-page"><?php echo $foxtemas_options['404_title'];?></h1>
        <?php } else {?>
            <p class="aligncenter" style="margin-bottom:20px;">
                <?php if($foxtemas_options['logo_header']['url']) {?>
                    <img src="<?php echo $foxtemas_options['logo_header']['url'];?>" alt="logo <?php bloginfo('name');?>">
                <?php } else {?>
                    <img src="<?php echo FOXTEMAS_THEME_DIR . 'images/logo.png';?>" alt="logo <?php bloginfo('name');?>">
                <?php } ?>
            </p>
            
            <h1 class="name-page">
                404 - Página não encontrada
            </h1>
        <?php } ?>

        

        <div class="entry">
            
            <?php if($foxtemas_options['404_content']) {?>
                <?php echo $foxtemas_options['404_content']; ?>
            <?php } else {?>
                <p class="aligncenter">
                    Desculpe, mas sua página não pode ser encontrada, ela pode ter sido movida ou removida do site.<br>
                    Tente realizar uma busca novamente.
                </p>

                <div class="search-404">
                    <form class="form-search" method="get" action="<?php echo get_bloginfo('url'); ?>/">
                        <input class="search-input"  type="text" value="Buscar..." name="s"  size="10"  onclick="if (this.value == 'Buscar...') { this.value = ''; }" onblur="if (this.value == '') { this.value = 'Buscar...'; }" />
                        <input type="submit" class="search-btn" value="Buscar">
                    </form>
                </div>
            <?php } ?>

            

            <div class="clearfix"></div>
        </div>

    </div>
    <!-- end 404 container -->

    <div class="line-footer"></div>

    <div class="clearfix"></div>
</div>
<!-- end container -->

<?php get_footer();?>