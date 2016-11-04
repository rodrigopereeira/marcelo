<?php  class acf_revisions { function __construct() { add_action('wp_restore_post_revision', array($this, 'wp_restore_post_revision'), 10, 2 ); add_filter('_wp_post_revision_fields', array($this, 'wp_preview_post_fields') ); add_filter('_wp_post_revision_fields', array($this, 'wp_post_revision_fields') ); add_filter('wp_save_post_revision_check_for_changes', array($this, 'force_save_revision'), 10, 3); } function wp_preview_post_fields( $fields ) { if( empty($_POST['wp-preview']) || $_POST['wp-preview'] != 'dopreview') { return $fields; } if( !empty($_POST['_acfchanged']) ) { $fields['_acfchanged'] = 'different than 1'; } return $fields; } function force_save_revision( $return, $last_revision, $post ) { if( isset($_POST['_acfchanged']) && $_POST['_acfchanged'] == '1' ) { $return = false; } return $return; } function wp_post_revision_fields( $return ) { global $post, $pagenow; $allowed = false; if( $pagenow == 'revision.php' ) { $allowed = true; } if( $pagenow == 'admin-ajax.php' && isset($_POST['action']) && $_POST['action'] == 'get-revision-diffs' ) { $allowed = true; } if( !$allowed ) { return $return; } $post_id = 0; if( isset($_POST['post_id']) ) { $post_id = $_POST['post_id']; } elseif( isset($post->ID) ) { $post_id = $post->ID; } else { return $return; } $GLOBALS['acf_revisions_fields'] = array(); $custom_fields = get_post_custom( $post_id ); if( !empty($custom_fields) ) { foreach( $custom_fields as $k => $v ) { $v = $v[0]; if( !acf_is_field_key($v) ) { continue; } $field_name = substr($k, 1); $return[ $field_name ] = $field_name; add_filter("_wp_post_revision_field_{$field_name}", array($this, 'wp_post_revision_field'), 10, 4); if( isset($_GET['action'], $_GET['left'], $_GET['right']) && $_GET['action'] == 'diff' ) { global $left_revision, $right_revision; $left_revision->$field_name = 'revision_id=' . $_GET['left']; $right_revision->$field_name = 'revision_id=' . $_GET['right']; } } } return $return; } function wp_post_revision_field( $value, $field_name, $post = null, $direction = false) { $post_id = 0; if( isset($post->ID) ) { $post_id = $post->ID; } elseif( isset($_GET['revision']) ) { $post_id = (int) $_GET['revision']; } elseif( strpos($value, 'revision_id=') !== false ) { $post_id = (int) str_replace('revision_id=', '', $value); } $field = acf_maybe_get_field( $field_name, $post_id ); if( is_array($value) ) { $value = implode(', ', $value); } if( !empty($value) ) { if( $field['type'] == 'image' || $field['type'] == 'file' ) { $url = wp_get_attachment_url($value); $value = $value . ' (' . $url . ')'; } } return $value; } function wp_restore_post_revision( $post_id, $revision_id ) { global $wpdb; $custom_fields = get_post_custom( $revision_id ); if( !empty($custom_fields) ) { foreach( $custom_fields as $k => $v ) { $v = $v[0]; if( !acf_is_field_key($v) ) { continue; } $field_name = substr($k, 1); if( !isset($custom_fields[ $field_name ][0]) ) { continue; } update_post_meta( $post_id, $field_name, $custom_fields[ $field_name ][0] ); } } } } new acf_revisions(); ?>
