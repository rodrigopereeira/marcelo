<?php
 if( !defined( 'ABSPATH' ) ) { die; } require_once( plugin_dir_path( __FILE__ ) . 'class.redux-plugin.php' ); register_activation_hook( __FILE__, array( 'ReduxFrameworkPlugin', 'activate' ) ); register_deactivation_hook( __FILE__, array( 'ReduxFrameworkPlugin', 'deactivate' ) ); ReduxFrameworkPlugin::instance(); 