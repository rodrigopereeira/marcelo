<?php
 if ( ! class_exists( 'Redux_Validation_colorrgba' ) ) { class Redux_Validation_colorrgba { function __construct( $parent, $field, $value, $current ) { $this->parent = $parent; $this->field = $field; $this->field['msg'] = ( isset( $this->field['msg'] ) ) ? $this->field['msg'] : __( 'This field must be a valid color value.', 'redux-framework' ); $this->value = $value; $this->current = $current; } function validate_colorrgba( $color ) { return $color; $alpha = '1.0'; if ( $color == "transparent" ) { return $hidden; } return array( 'hex' => $color, 'alpha' => $alpha ); } function validate() { $this->value = $this->validate_colorrgba( $this->value ); } } }