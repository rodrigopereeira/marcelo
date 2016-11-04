<?php
 if ( ! defined( 'ABSPATH' ) ) { exit; } if ( ! class_exists( 'ReduxFramework_switch' ) ) { class ReduxFramework_switch { function __construct( $field = array(), $value = '', $parent ) { $this->parent = $parent; $this->field = $field; $this->value = $value; } function render() { $cb_enabled = $cb_disabled = ''; if ( (int) $this->value == 1 ) { $cb_enabled = ' selected'; } else { $cb_disabled = ' selected'; } $this->field['on'] = isset( $this->field['on'] ) ? $this->field['on'] : __( 'On', 'redux-framework' ); $this->field['off'] = isset( $this->field['off'] ) ? $this->field['off'] : __( 'Off', 'redux-framework' ); echo '<div class="switch-options">'; echo '<label class="cb-enable' . $cb_enabled . '" data-id="' . $this->field['id'] . '"><span>' . $this->field['on'] . '</span></label>'; echo '<label class="cb-disable' . $cb_disabled . '" data-id="' . $this->field['id'] . '"><span>' . $this->field['off'] . '</span></label>'; echo '<input type="hidden" class="checkbox checkbox-input ' . $this->field['class'] . '" id="' . $this->field['id'] . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '" value="' . $this->value . '" />'; echo '</div>'; } function enqueue() { wp_enqueue_script( 'redux-field-switch-js', ReduxFramework::$_url . 'inc/fields/switch/field_switch' . Redux_Functions::isMin() . '.js', array( 'jquery', 'redux-js' ), time(), true ); if ($this->parent->args['dev_mode']) { wp_enqueue_style( 'redux-field-switch-css', ReduxFramework::$_url . 'inc/fields/switch/field_switch.css', array(), time(), 'all' ); } } } }