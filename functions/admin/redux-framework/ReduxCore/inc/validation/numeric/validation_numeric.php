<?php
 if ( ! class_exists( 'Redux_Validation_numeric' ) ) { class Redux_Validation_numeric { function __construct( $parent, $field, $value, $current ) { $this->parent = $parent; $this->field = $field; $this->field['msg'] = ( isset( $this->field['msg'] ) ) ? $this->field['msg'] : __( 'You must provide a numerical value for this option.', 'redux-framework' ); $this->value = $value; $this->current = $current; $this->validate(); } function validate() { if ( ! is_numeric( $this->value ) ) { $this->value = ( isset( $this->current ) ) ? $this->current : ''; $this->error = $this->field; } } } }