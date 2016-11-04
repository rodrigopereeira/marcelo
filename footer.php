<?php global $foxtemas_options; $blank_fox_op = '                                            ';?>

        <div class="clearfix"></div>
        
        <?php if($foxtemas_options['ads_switch'] == true) {?>
        <!-- container -->
        <div class="container">
            
            <!-- bg ads -->
            <div class="bg-adsense">

                <?php if($foxtemas_options['ads_switch_col'] == 'colunas') {?>
                    <div class="col">
                        <?php if($foxtemas_options['ads_col_1'] && ($foxtemas_options['ads_col_1'] != $blank_fox_op)) { echo $foxtemas_options['ads_col_1']; } else {?><img src="http://placehold.it/300x250" alt="publicidade"><?php } ?>
                    </div>
                    <div class="col">
                        <?php if($foxtemas_options['ads_col_2'] && ($foxtemas_options['ads_col_2'] != $blank_fox_op)) { echo $foxtemas_options['ads_col_2']; } else {?><img src="http://placehold.it/300x250" alt="publicidade"><?php } ?>
                    </div>
                    <div class="col">
                        <?php if($foxtemas_options['ads_col_3'] && ($foxtemas_options['ads_col_3'] != $blank_fox_op)) { echo $foxtemas_options['ads_col_3']; } else {?><img src="http://placehold.it/300x250" alt="publicidade"><?php } ?>
                    </div>
                <?php } else {?>
                    <?php if($foxtemas_options['ads_full'] && ($foxtemas_options['ads_full'] != $blank_fox_op) ) { echo $foxtemas_options['ads_full']; } else {?><img src="http://placehold.it/1100x250" alt="publicidade"><?php } ?>
                <?php } ?>

            </div>
            <!-- end bg ads -->

        </div>
        <!-- end container -->
        <?php }?>
        
        <div class="clearfix"></div>
    </div>
    <!-- end wrap shadow -->
</div>
<!-- end wrap margem -->


<?php if($foxtemas_options['text_footer_top_switch'] == true) {?>
<!-- text footer -->
<div class="container">
    
    <div class="text-footer">

        <?php if($foxtemas_options['text_footer_top'] && $foxtemas_options['text_footer_top'] != $blank_fox_op) {
            echo $foxtemas_options['text_footer_top'];
        } else {?>
            <p class="aligncenter">
                <a href="#">Ut tortor ultrices scelerisque placerat</a>, magna, amet elementum sed integer porttitor ut porta nisi natoque placerat eros ut, lorem, lacus, elementum urna integer magnaMauris nunc, nec adipiscing non, duis, dolor enim placerat turpis, aenean? Tincidunt in! Phasellus. Auctor nunc ut porta, dignissim integer phasellus placerat, sit mus
            </p>
        <?php } ?>
        
        <div class="clearfix"></div>
    </div>

</div>
<!-- end text footer -->
<?php }?>


<!-- footer -->
<footer class="bg-footer">
    
    <div class="container">
        
        <!-- logo footer -->
        <div class="logo-footer">
            <span>
                <?php if($foxtemas_options['logo_footer']['url']) {?>
                    <img src="<?php echo $foxtemas_options['logo_footer']['url'];?>" alt="logo <?php bloginfo('name');?>">
                <?php } else {?>
                    <img src="<?php echo FOXTEMAS_THEME_DIR . 'images/logo-footer.png';?>" alt="logo <?php bloginfo('name');?>">
                <?php } ?>
            </span>
        </div>
        <!-- end logo footer -->
        
        <!-- menu footer -->
        <div class="menu-footer">
            <?php if(has_nav_menu('menu_footer' )) { ?>
            
                <ul class="sf-menu">
                    <?php $args = array(
                        'container'       => '',
                        'fallback_cb'     => 'wp_page_menu', 
                        'echo'            => true,
                        'link_before'     => '',
                        'link_after'      => '',
                        'items_wrap'      => '%3$s',
                        'theme_location'    => 'menu_footer',
                    );
                    wp_nav_menu($args);  ?>
                </ul>

            <?php } else {?>
            
                <ul class="sf-menu">
                    <li class="current-menu-item"><a href="<?php bloginfo('url');?>" title="Início">Home</a></li>
                </ul>
            
            <?php }?>
        </div>
        <!-- end menu footer -->

        <div class="clearfix"></div>


    </div>

</footer>
<!-- end footer -->

<!-- copyright text -->
<div class="copyright-text">
    <div class="container">
        <?php if($foxtemas_options['text_footer']) { ?>
            <div class="copy-text">
                <?php echo $foxtemas_options['text_footer'];?> - Tema Desenvolvido por <a target="_blank" href="http://www.wpadulto.com" title="WP Adulto - Temas Wordpress Adultos">WP Adulto</a>
            </div>
        <?php } else {?>
            <div class="copy-text">
                © <?php bloginfo('name');?> - Tema Desenvolvido por <a target="_blank" href="http://www.wpadulto.com" title="WP Adulto - Temas Wordpress Adultos">WP Adulto</a>
            </div>
        <?php } ?>

    </div>
</div>
<!-- end copyright text -->


<?php wp_footer(); ?>

<?php echo $foxtemas_options['google_analyctis']; ?>

</body>
</html>

