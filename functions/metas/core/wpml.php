<?php  class acf_wpml_compatibility { var $lang = ''; function __construct() { global $sitepress; $this->lang = ICL_LANGUAGE_CODE; acf_update_setting('default_language', $sitepress->get_default_language()); acf_update_setting('current_language', $this->lang); add_action('acf/verify_ajax', array($this, 'verify_ajax')); add_action('acf/field_group/admin_head', array($this, 'admin_head')); add_action('acf/input/admin_head', array($this, 'admin_head')); if( !$this->is_translatable() ) { return; } add_action('acf/upgrade_start/5.0.0', array($this, 'upgrade_start_5')); add_action('acf/upgrade_finish/5.0.0', array($this, 'upgrade_finish_5')); add_action('acf/update_field_group', array($this, 'update_field_group'), 2, 1); add_action('icl_make_duplicate', array($this, 'icl_make_duplicate'), 10, 4); add_filter('acf/settings/save_json', array($this, 'settings_save_json')); add_filter('acf/settings/load_json', array($this, 'settings_load_json')); } function is_translatable() { global $sitepress, $sitepress_settings; $post_types = acf_maybe_get($sitepress_settings, 'custom_posts_sync_option', array()); if( !empty($post_types['acf-field-group']) ) { return true; } if( !empty($post_types['acf']) && !isset($post_types['acf-field-group']) ) { return true; } return false; } function upgrade_start_5() { add_action('acf/update_field_group', array($this, 'update_field_group_5'), 1, 1); global $sitepress, $sitepress_settings; $icl_settings = array(); $post_types = $sitepress_settings['custom_posts_sync_option']; if( !empty($post_types['acf']) ) { $post_types['acf-field-group'] = 1; } $icl_settings['custom_posts_sync_option'] = $post_types; $sitepress->save_settings( $icl_settings ); } function upgrade_finish_5() { remove_action('acf/update_field_group', array($this, 'update_field_group_5'), 1, 1); } function update_field_group_5( $field_group ) { global $wpdb, $sitepress; if( empty($field_group['old_ID']) ) { return; } $old_row = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}icl_translations WHERE element_type=%s AND element_id=%d", 'post_acf', $field_group['old_ID'] ), ARRAY_A); $new_row = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}icl_translations WHERE element_type=%s AND element_id=%d", 'post_acf-field-group', $field_group['ID'] ), ARRAY_A); if( !$old_row || !$new_row ) { return; } if( empty($this->trid_ref) ) { $this->trid_ref = array(); } if( isset($this->trid_ref[ $old_row['trid'] ]) ) { $new_row['trid'] = $this->trid_ref[ $old_row['trid'] ]; } else { $this->trid_ref[ $old_row['trid'] ] = $new_row['trid']; } $table = "{$wpdb->prefix}icl_translations"; $data = array( 'trid' => $new_row['trid'], 'language_code' => $old_row['language_code'] ); $where = array( 'translation_id' => $new_row['translation_id'] ); $data_format = array( '%d', '%s' ); $where_format = array( '%d' ); if( $old_row['source_language_code'] ) { $data['source_language_code'] = $old_row['source_language_code']; $data_format[] = '%s'; } $result = $wpdb->update( $table, $data, $where, $data_format, $where_format ); } function update_field_group( $field_group ) { global $sitepress; $this->lang = $sitepress->get_language_for_element($field_group['ID'], 'post_acf-field-group'); } function settings_save_json( $path ) { if( !is_writable($path) ) { return $path; } $path = untrailingslashit( $path ); $path = $path . '/' . $this->lang; if( !file_exists($path) ) { mkdir($path, 0777, true); } return $path; } function settings_load_json( $paths ) { if( !empty($paths) ) { foreach( $paths as $i => $path ) { $path = untrailingslashit( $path ); $paths[ $i ] = $path . '/' . $this->lang; } } return $paths; } function icl_make_duplicate( $master_post_id, $lang, $postarr, $id ) { if( $postarr['post_type'] != 'acf-field-group' ) { return; } acf_duplicate_field_group( $master_post_id, $id ); global $iclTranslationManagement; $iclTranslationManagement->reset_duplicate_flag( $id ); } function admin_head() { ?>
		<script type="text/javascript">
				
		acf.add_filter('prepare_for_ajax', function( args ){
			
			if( typeof icl_this_lang != 'undefined' ) {
			
				args.lang = icl_this_lang;
				
			}
			
			return args;
			
		});
		
		</script>
		<?php
 } function verify_ajax() { global $sitepress; if( isset($_REQUEST['lang']) ) { $sitepress->switch_lang( $_REQUEST['lang'] ); } if( isset($_REQUEST['post_id']) && !is_numeric($_REQUEST['post_id']) ) { unset( $_REQUEST['post_id'] ); } } } new acf_wpml_compatibility(); ?>
