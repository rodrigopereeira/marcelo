<?php
 if ( !defined ( 'ABSPATH' ) ) { exit; } if (!class_exists('reduxCoreRequired')){ class reduxCoreRequired { public $parent = null; public function __construct ($parent) { $this->parent = $parent; Redux_Functions::$_parent = $parent; do_action( "redux/page/{$parent->args['opt_name']}/" ); } } }