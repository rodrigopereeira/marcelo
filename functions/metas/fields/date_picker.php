<?php
 if( ! class_exists('acf_field_date_picker') ) : class acf_field_date_picker extends acf_field { function __construct() { $this->name = 'date_picker'; $this->label = __("Date Picker",'acf'); $this->category = 'jquery'; $this->defaults = array( 'display_format' => 'd/m/Y', 'return_format' => 'd/m/Y', 'first_day' => 1 ); add_action('init', array($this, 'init')); parent::__construct(); } function init() { global $wp_locale; $this->l10n = array( 'closeText' => __( 'Done', 'acf' ), 'currentText' => __( 'Today', 'acf' ), 'monthNames' => array_values( $wp_locale->month ), 'monthNamesShort' => array_values( $wp_locale->month_abbrev ), 'monthStatus' => __( 'Show a different month', 'acf' ), 'dayNames' => array_values( $wp_locale->weekday ), 'dayNamesShort' => array_values( $wp_locale->weekday_abbrev ), 'dayNamesMin' => array_values( $wp_locale->weekday_initial ), 'isRTL' => isset($wp_locale->is_rtl) ? $wp_locale->is_rtl : false, ); } function render_field( $field ) { $e = ''; $div = array( 'class' => 'acf-date_picker acf-input-wrap', 'data-display_format' => acf_convert_date_to_js($field['display_format']), 'data-first_day' => $field['first_day'], ); $input = array( 'id' => $field['id'], 'class' => 'input-alt', 'type' => 'hidden', 'name' => $field['name'], 'value' => $field['value'], ); $e .= '<div ' . acf_esc_attr($div) . '>'; $e .= '<input ' . acf_esc_attr($input). '/>'; $e .= '<input type="text" value="" class="input" />'; $e .= '</div>'; echo $e; } function render_field_settings( $field ) { global $wp_locale; acf_render_field_setting( $field, array( 'label' => __('Display format','acf'), 'instructions' => __('The format displayed when editing a post','acf'), 'type' => 'radio', 'name' => 'display_format', 'other_choice' => 1, 'choices' => array( 'd/m/Y' => date('d/m/Y'), 'm/d/Y' => date('m/d/Y'), 'F j, Y' => date('F j, Y'), ) )); acf_render_field_setting( $field, array( 'label' => __('Return format','acf'), 'instructions' => __('The format returned via template functions','acf'), 'type' => 'radio', 'name' => 'return_format', 'other_choice' => 1, 'choices' => array( 'd/m/Y' => date('d/m/Y'), 'm/d/Y' => date('m/d/Y'), 'F j, Y' => date('F j, Y'), 'Ymd' => date('Ymd'), ) )); acf_render_field_setting( $field, array( 'label' => __('Week Starts On','acf'), 'instructions' => '', 'type' => 'select', 'name' => 'first_day', 'choices' => array_values( $wp_locale->weekday ) )); } function format_value( $value, $post_id, $field ) { if( empty($value) ) { return $value; } $unixtimestamp = strtotime( $value ); if( !$unixtimestamp ) { return $value; } $value = date_i18n($field['return_format'], $unixtimestamp); return $value; } } new acf_field_date_picker(); endif; ?>
