<?php
 if ( ! defined( 'ABSPATH' ) ) { exit; } if ( ! class_exists( 'ReduxFramework_editor' ) ) { class ReduxFramework_editor { function __construct( $field = array(), $value = '', $parent ) { $this->parent = $parent; $this->field = $field; $this->value = $value; } public function render() { if ( ! isset( $this->field['args'] ) ) { $this->field['args'] = array(); } $this->field['args']['onchange_callback'] = "alert('here')"; $defaults = array( 'textarea_name' => $this->field['name'] . $this->field['name_suffix'], 'editor_class' => $this->field['class'], 'textarea_rows' => 10, 'teeny' => true, ); if ( isset( $this->field['editor_options'] ) && empty( $this->field['args'] ) ) { $this->field['args'] = $this->field['editor_options']; unset( $this->field['editor_options'] ); } $this->field['args'] = wp_parse_args( $this->field['args'], $defaults ); wp_editor( $this->value, $this->field['id'], $this->field['args'] ); } public function enqueue() { if ($this->parent->args['dev_mode']) { wp_enqueue_style( 'redux-field-editor-css', ReduxFramework::$_url . 'inc/fields/editor/field_editor.css', array(), time(), 'all' ); } wp_enqueue_script( 'redux-field-editor-js', ReduxFramework::$_url . 'inc/fields/editor/field_editor' . Redux_Functions::isMin() . '.js', array( 'jquery', 'redux-js' ), time(), true ); } } }