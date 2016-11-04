<?php  if( !defined('ABSPATH') ) exit; global $wpdb; $ofgs = get_posts(array( 'numberposts' => -1, 'post_type' => 'acf', 'orderby' => 'menu_order title', 'order' => 'asc', 'suppress_filters' => true, )); if( $ofgs ){ foreach( $ofgs as $ofg ){ $nfg = _migrate_field_group_500( $ofg ); $rows = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE post_id = %d AND meta_key LIKE %s", $ofg->ID, 'field_%'), ARRAY_A); if( $rows ) { $nfg['fields'] = array(); foreach( $rows as $row ) { $field = $row['meta_value']; $field = maybe_unserialize( $field ); $field = maybe_unserialize( $field ); $field['parent'] = $nfg['ID']; $field = _migrate_field_500( $field ); } } }} function _migrate_field_group_500( $ofg ) { global $wpdb; $post_status = $ofg->post_status; $nfg = array( 'ID' => 0, 'title' => $ofg->post_title, 'menu_order' => $ofg->menu_order, ); $groups = array(); $rules = get_post_meta($ofg->ID, 'rule', false); if( is_array($rules) ) { $group_no = 0; foreach( $rules as $rule ) { $rule = maybe_unserialize($rule); if( !isset($rule['group_no']) ) { $rule['group_no'] = $group_no; if( get_post_meta($ofg->ID, 'allorany', true) == 'any' ) { $group_no++; } } $group = acf_extract_var( $rule, 'group_no' ); $order = acf_extract_var( $rule, 'order_no' ); $groups[ $group ][ $order ] = $rule; ksort( $groups[ $group ] ); } ksort( $groups ); } $nfg['location'] = $groups; if( $position = get_post_meta($ofg->ID, 'position', true) ) { $nfg['position'] = $position; } if( $layout = get_post_meta($ofg->ID, 'layout', true) ) { $nfg['layout'] = $layout; } if( $hide_on_screen = get_post_meta($ofg->ID, 'hide_on_screen', true) ) { $nfg['hide_on_screen'] = maybe_unserialize($hide_on_screen); } $nfg['old_ID'] = $ofg->ID; $nfg = acf_update_field_group( $nfg ); if( $post_status == 'trash' ) { acf_trash_field_group( $nfg['ID'] ); } return $nfg; } function _migrate_field_500( $field ) { $orig = $field; $field['menu_order'] = acf_extract_var( $field, 'order_no' ); if( substr($field['key'], 0, 6) !== 'field_' ) { $field['key'] = 'field_' . str_replace('field', '', $field['key']); } $field = acf_get_valid_field( $field ); $field = acf_update_field( $field ); if( $field['type'] == 'repeater' ) { $sub_fields = acf_extract_var( $orig, 'sub_fields' ); if( !empty($sub_fields) ) { $keys = array_keys($sub_fields); foreach( $keys as $key ) { $sub_field = acf_extract_var($sub_fields, $key); $sub_field['parent'] = $field['ID']; _migrate_field_500( $sub_field ); } } } elseif( $field['type'] == 'flexible_content' ) { $layouts = acf_extract_var( $orig, 'layouts' ); $field['layouts'] = array(); if( !empty($layouts) ) { foreach( $layouts as $layout ) { $layout_key = uniqid(); $layout['key'] = $layout_key; $sub_fields = acf_extract_var($layout, 'sub_fields'); if( !empty($sub_fields) ) { $keys = array_keys($sub_fields); foreach( $keys as $key ) { $sub_field = acf_extract_var($sub_fields, $key); $sub_field['parent'] = $field['ID']; $sub_field['parent_layout'] = $layout_key; _migrate_field_500( $sub_field ); } } $field['layouts'][] = $layout; } } $field = acf_update_field( $field ); } return $field; } ?>
