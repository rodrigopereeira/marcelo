<?php
 if ( ! class_exists( 'Redux_Validation_js' ) ) { class Redux_Validation_js { function __construct( $parent, $field, $value, $current ) { $this->parent = $parent; $this->field = $field; $this->value = $value; $this->current = $current; $this->validate(); } function validate() { $this->value = esc_js( $this->value ); } } }