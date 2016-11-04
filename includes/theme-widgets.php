<?php 
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;


// ==========================================================================
//   Adsense Widget
// ==========================================================================
class FoxTemasWidgetAdsense extends WP_Widget {

    function __construct() {
        parent::__construct(
            'foxtemaswidgetadsense', // Base ID
            __( '#WPAdulto: Publicidade', 'foxtemas_theme' ), // Name
            array( 'description' => __( 'Widget para publicidades', 'foxtemas_theme' ), ) // Args
        );
    }


    public function widget( $args, $instance ) { 

        // widget id
        $widget_id = $args['widget_id'];

        ?>

        <li class="widget">
            
            <?php if(get_field('codigo_publicidade', 'widget_'.$widget_id)) {?>
                <div class="adsense-widget">
                    <?php the_field('codigo_publicidade', 'widget_' . $widget_id); ?>
                </div>
            <?php } ?>
        
            <div class="clearfix"></div>
        </li>

    <?php }


    public function form( $instance ) {
        ?>
        <p>
            Configurações   
        </p>
        <?php 
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        return $instance;
    }

} 


//   Register Widgets 
// ==========================================================================
function register_foo_widget() {

    //  register widget ads 
    register_widget( 'FoxTemasWidgetAdsense' );
}
add_action( 'widgets_init', 'register_foo_widget' );


?>