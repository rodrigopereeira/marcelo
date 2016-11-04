<?php  if( !class_exists('acf_pro') ): class acf_pro { function __construct() { acf_update_setting( 'pro', true ); acf_update_setting( 'name', __('Advanced Custom Fields PRO', 'acf') ); acf_include('pro/api/api-pro.php'); acf_include('pro/api/api-options-page.php'); if( is_admin() ) { acf_include('pro/admin/options-page.php'); acf_include('pro/admin/settings-updates.php'); } acf_include('pro/fields/repeater.php'); acf_include('pro/fields/flexible-content.php'); acf_include('pro/fields/gallery.php'); add_action('init', array($this, 'wp_init')); add_action('acf/input/admin_enqueue_scripts', array($this, 'input_admin_enqueue_scripts')); add_action('acf/field_group/admin_enqueue_scripts', array($this, 'field_group_admin_enqueue_scripts')); add_action('acf/field_group/admin_l10n', array($this, 'field_group_admin_l10n')); add_filter('acf/get_valid_field', array($this, 'get_valid_field'), 11, 1); add_filter('acf/update_field', array($this, 'update_field'), 1, 1); add_filter('acf/prepare_field_for_export', array($this, 'prepare_field_for_export')); add_filter('acf/prepare_field_for_import', array($this, 'prepare_field_for_import')); } function get_valid_field( $field ) { $width = acf_extract_var( $field, 'column_width' ); if( $width ) { $field['wrapper']['width'] = $width; } return $field; } function wp_init() { $min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min'; wp_register_script( 'acf-pro-input', acf_get_dir( "pro/assets/js/acf-pro-input{$min}.js" ), false, acf_get_setting('version') ); wp_register_script( 'acf-pro-field-group', acf_get_dir( "pro/assets/js/acf-pro-field-group{$min}.js" ), false, acf_get_setting('version') ); wp_register_style( 'acf-pro-input', acf_get_dir( 'pro/assets/css/acf-pro-input.css' ), false, acf_get_setting('version') ); wp_register_style( 'acf-pro-field-group', acf_get_dir( 'pro/assets/css/acf-pro-field-group.css' ), false, acf_get_setting('version') ); } function input_admin_enqueue_scripts() { wp_enqueue_script('acf-pro-input'); wp_enqueue_style('acf-pro-input'); } function field_group_admin_l10n( $l10n ) { $l10n['flexible_content'] = array( 'layout_warning' => __('Flexible Content requires at least 1 layout','acf') ); return $l10n; } function field_group_admin_enqueue_scripts() { wp_enqueue_script('acf-pro-field-group'); wp_enqueue_style('acf-pro-field-group'); } function update_field( $field ) { if( !$field['parent'] || !acf_is_field_key($field['parent']) ) { return $field; } $ref = 0; if( empty($this->ref) ) { $this->ref = array(); } if( isset($this->ref[ $field['parent'] ]) ) { $ref = $this->ref[ $field['parent'] ]; } else { $parent = acf_get_field( $field['parent'], true ); if( !$parent ) { return $field; } $ref = $parent['ID'] ? $parent['ID'] : $parent['key']; $this->ref[ $field['parent'] ] = $ref; } $field['parent'] = $ref; return $field; } function prepare_field_for_export( $field ) { acf_extract_var( $field, 'parent_layout'); if( $field['type'] == 'repeater' ) { $field['sub_fields'] = acf_prepare_fields_for_export( $field['sub_fields'] ); } elseif( $field['type'] == 'flexible_content' ) { foreach( $field['layouts'] as $l => $layout ) { $field['layouts'][ $l ]['sub_fields'] = acf_prepare_fields_for_export( $layout['sub_fields'] ); } } return $field; } function prepare_field_for_import( $field ) { $extra = array(); if( $field['type'] == 'repeater' ) { $sub_fields = acf_extract_var( $field, 'sub_fields'); $field['sub_fields'] = array(); if( !empty($sub_fields) ) { foreach( array_keys($sub_fields) as $i ) { $sub_field = acf_extract_var( $sub_fields, $i ); $sub_field['parent'] = $field['key']; $extra[] = $sub_field; } } } elseif( $field['type'] == 'flexible_content' ) { $layouts = acf_extract_var( $field, 'layouts'); $field['layouts'] = array(); if( !empty($layouts) ) { foreach( array_keys($layouts) as $i ) { $layout = acf_extract_var( $layouts, $i ); if( empty($layout['key']) ) { $layout['key'] = uniqid(); } $sub_fields = acf_extract_var( $layout, 'sub_fields'); if( !empty($sub_fields) ) { foreach( array_keys($sub_fields) as $j ) { $sub_field = acf_extract_var( $sub_fields, $j ); $sub_field['parent'] = $field['key']; $sub_field['parent_layout'] = $layout['key']; $extra[] = $sub_field; } } $field['layouts'][] = $layout; } } } if( !empty($extra) ) { array_unshift($extra, $field); return $extra; } return $field; } } new acf_pro(); endif; ?>