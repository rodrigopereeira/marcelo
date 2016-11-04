<?php
 if( ! class_exists('acf_field_select') ) : class acf_field_select extends acf_field { function __construct() { $this->name = 'select'; $this->label = __("Select",'acf'); $this->category = 'choice'; $this->defaults = array( 'multiple' => 0, 'allow_null' => 0, 'choices' => array(), 'default_value' => '', 'ui' => 0, 'ajax' => 0, 'placeholder' => '', 'disabled' => 0, 'readonly' => 0, ); add_action('wp_ajax_acf/fields/select/query', array($this, 'ajax_query')); add_action('wp_ajax_nopriv_acf/fields/select/query', array($this, 'ajax_query')); parent::__construct(); } function ajax_query() { $options = acf_parse_args( $_POST, array( 'post_id' => 0, 's' => '', 'field_key' => '', 'nonce' => '', )); $field = acf_get_field( $options['field_key'] ); if( !$field ) { die(); } $r = array(); $s = false; if( $options['s'] !== '' ) { $s = strval($options['s']); $s = wp_unslash($s); } if( !empty($field['choices']) ) { foreach( $field['choices'] as $k => $v ) { if( $s !== false && stripos($v, $s) === false ) { continue; } $r[] = array( 'id' => $k, 'text' => strval( $v ) ); } } echo json_encode( $r ); die(); } function render_field( $field ) { $field['value'] = acf_get_array($field['value'], false); if( empty($field['value']) ){ $field['value'][''] = ''; } if( empty($field['placeholder']) ) { $field['placeholder'] = __("Select",'acf'); } $atts = array( 'id' => $field['id'], 'class' => $field['class'], 'name' => $field['name'], 'data-ui' => $field['ui'], 'data-ajax' => $field['ajax'], 'data-multiple' => $field['multiple'], 'data-placeholder' => $field['placeholder'], 'data-allow_null' => $field['allow_null'] ); if( $field['ui'] ) { $atts['disabled'] = 'disabled'; $atts['class'] .= ' acf-hidden'; } if( $field['multiple'] ) { $atts['multiple'] = 'multiple'; $atts['size'] = 5; $atts['name'] .= '[]'; } foreach( array( 'readonly', 'disabled' ) as $k ) { if( !empty($field[ $k ]) ) { $atts[ $k ] = $k; } } $els = array(); $choices = array(); if( !empty($field['choices']) ) { foreach( $field['choices'] as $k => $v ) { if( is_array($v) ){ $els[] = array( 'type' => 'optgroup', 'label' => $k ); if( !empty($v) ) { foreach( $v as $k2 => $v2 ) { $els[] = array( 'type' => 'option', 'value' => $k2, 'label' => $v2, 'selected' => in_array($k2, $field['value']) ); $choices[] = $k2; } } $els[] = array( 'type' => '/optgroup' ); } else { $els[] = array( 'type' => 'option', 'value' => $k, 'label' => $v, 'selected' => in_array($k, $field['value']) ); $choices[] = $k; } } } if( $field['ui'] ) { $real_value = array_intersect($field['value'], $choices); acf_hidden_input(array( 'type' => 'hidden', 'id' => $field['id'], 'name' => $field['name'], 'value' => implode(',', $real_value) )); } elseif( $field['multiple'] ) { acf_hidden_input(array( 'type' => 'hidden', 'name' => $field['name'], )); } if( $field['allow_null'] ) { array_unshift( $els, array( 'type' => 'option', 'value' => '', 'label' => '- ' . $field['placeholder'] . ' -' ) ); } echo '<select ' . acf_esc_attr( $atts ) . '>'; if( !empty($els) ) { foreach( $els as $el ) { $type = acf_extract_var($el, 'type'); if( $type == 'option' ) { $label = acf_extract_var($el, 'label'); if( acf_extract_var($el, 'selected') ) { $el['selected'] = 'selected'; } echo '<option ' . acf_esc_attr( $el ) . '>' . $label . '</option>'; } else { echo '<' . $type . ' ' . acf_esc_attr( $el ) . '>'; } } } echo '</select>'; } function render_field_settings( $field ) { $field['choices'] = acf_encode_choices($field['choices']); $field['default_value'] = acf_encode_choices($field['default_value']); acf_render_field_setting( $field, array( 'label' => __('Choices','acf'), 'instructions' => __('Enter each choice on a new line.','acf') . '<br /><br />' . __('For more control, you may specify both a value and label like this:','acf'). '<br /><br />' . __('red : Red','acf'), 'type' => 'textarea', 'name' => 'choices', )); acf_render_field_setting( $field, array( 'label' => __('Default Value','acf'), 'instructions' => __('Enter each default value on a new line','acf'), 'type' => 'textarea', 'name' => 'default_value', )); acf_render_field_setting( $field, array( 'label' => __('Allow Null?','acf'), 'instructions' => '', 'type' => 'radio', 'name' => 'allow_null', 'choices' => array( 1 => __("Yes",'acf'), 0 => __("No",'acf'), ), 'layout' => 'horizontal', )); acf_render_field_setting( $field, array( 'label' => __('Select multiple values?','acf'), 'instructions' => '', 'type' => 'radio', 'name' => 'multiple', 'choices' => array( 1 => __("Yes",'acf'), 0 => __("No",'acf'), ), 'layout' => 'horizontal', )); acf_render_field_setting( $field, array( 'label' => __('Stylised UI','acf'), 'instructions' => '', 'type' => 'radio', 'name' => 'ui', 'choices' => array( 1 => __("Yes",'acf'), 0 => __("No",'acf'), ), 'layout' => 'horizontal', )); acf_render_field_setting( $field, array( 'label' => __('Use AJAX to lazy load choices?','acf'), 'instructions' => '', 'type' => 'radio', 'name' => 'ajax', 'choices' => array( 1 => __("Yes",'acf'), 0 => __("No",'acf'), ), 'layout' => 'horizontal', )); } function load_value( $value, $post_id, $field ) { if( $value === 'null' ) { return false; } return $value; } function update_field( $field ) { $field['choices'] = acf_decode_choices($field['choices']); $field['default_value'] = acf_decode_choices($field['default_value']); return $field; } function update_value( $value, $post_id, $field ) { if( empty($value) ) { return $value; } if( is_array($value) ) { $value = array_map('strval', $value); } return $value; } } new acf_field_select(); endif; ?>
