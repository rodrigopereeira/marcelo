<?php
 if ( ! class_exists( 'Redux_Validation_date' ) ) { class Redux_Validation_date { function __construct( $parent, $field, $value, $current ) { $this->parent = $parent; $this->field = $field; $this->field['msg'] = ( isset( $this->field['msg'] ) ) ? $this->field['msg'] : __( 'This field must be a valid date.', 'redux-framework' ); $this->value = $value; $this->current = $current; $this->validate(); } function validate() { $string = str_replace( '/', '', $this->value ); if ( ! is_numeric( $string ) ) { $this->value = ( isset( $this->current ) ) ? $this->current : ''; $this->error = $this->field; return; } if ( $this->value[2] != '/' ) { $this->value = ( isset( $this->current ) ) ? $this->current : ''; $this->error = $this->field; return; } if ( $this->value[5] != '/' ) { $this->value = ( isset( $this->current ) ) ? $this->current : ''; $this->error = $this->field; } } } }