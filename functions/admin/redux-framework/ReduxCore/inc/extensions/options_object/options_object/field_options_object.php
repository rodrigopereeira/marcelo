<?php
 if ( ! defined( 'ABSPATH' ) ) { exit; } if ( ! class_exists( 'ReduxFramework_options_object' ) ) { class ReduxFramework_options_object { function __construct( $field = array(), $value = '', $parent ) { $this->parent = $parent; $this->field = $field; $this->value = $value; $this->is_field = $this->parent->extensions['options_object']->is_field; $this->extension_dir = ReduxFramework::$_dir . 'inc/extensions/options_object/'; $this->extension_url = ReduxFramework::$_url . 'inc/extensions/options_object/'; $defaults = array( 'options' => array(), 'stylesheet' => '', 'output' => true, 'enqueue' => true, 'enqueue_frontend' => true ); $this->field = wp_parse_args( $this->field, $defaults ); } public function render() { if ( version_compare( phpversion(), "5.3.0", ">=" ) ) { $json = json_encode( $this->parent->options, true ); } else { $json = json_encode( $this->parent->options ); } $defaults = array( 'full_width' => true, 'overflow' => 'inherit', ); $this->field = wp_parse_args( $this->field, $defaults ); if ( $this->is_field ) { $fullWidth = $this->field['full_width']; } $bDoClose = false; $id = $this->parent->args['opt_name'] . '-' . $this->field['id']; if ( ! $this->is_field || ( $this->is_field && false == $fullWidth ) ) { ?>
                    <style>#<?php echo $id; ?> {padding: 0;}</style>
                    </td></tr></table>
                    <table id="<?php echo $id; ?>" class="form-table no-border redux-group-table redux-raw-table" style=" overflow: <?php $this->field['overflow']; ?>;">
                    <tbody><tr><td>
<?php
 $bDoClose = true; } ?>                
                <fieldset id="<?php echo $id; ?>" class="redux-field redux-container-<?php echo $this->field['type'] . ' ' . $this->field['class']; ?>" data-id="<?php echo $this->field['id']; ?>">
                    <h3><?php _e( 'Options Object', 'redux-framework' ); ?></h3>
                    <div id="redux-object-browser"></div>
                    <div id="redux-object-json" class="hide"><?php echo $json; ?></div>
                    <a href="#" id="consolePrintObject" class="button"><?php _e( 'Show Object in Javascript Console Object', 'redux-framework' ); ?></a>
                </div>
                </fieldset>
<?php
 if ( true == $bDoClose ) { ?>
                    </td></tr></table>
                    <table class="form-table no-border" style="margin-top: 0;">
                        <tbody>
                        <tr style="border-bottom: 0;">
                            <th></th>
                            <td>
<?php
 } } public function enqueue() { wp_enqueue_script( 'redux-options-object', $this->extension_url . 'options_object/field_options_object' . Redux_Functions::isMin() . '.js', array( 'jquery' ), ReduxFramework_extension_options_object::$version, true ); wp_enqueue_style( 'redux-options-object', $this->extension_url . 'options_object/field_options_object.css', array(), time(), 'all' ); } public function output() { if ( $this->field['enqueue_frontend'] ) { } } } } 