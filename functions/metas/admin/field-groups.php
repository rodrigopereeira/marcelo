<?php
 if( ! class_exists('acf_admin_field_groups') ) : class acf_admin_field_groups { var $url = 'edit.php?post_type=acf-field-group', $sync = array(); function __construct() { add_action('current_screen', array($this, 'current_screen')); add_action('trashed_post', array($this, 'trashed_post')); add_action('untrashed_post', array($this, 'untrashed_post')); add_action('deleted_post', array($this, 'deleted_post')); } function current_screen() { if( !acf_is_screen('edit-acf-field-group') ) { return; } global $wp_post_statuses; $wp_post_statuses['publish']->label_count = _n_noop( 'Active <span class="count">(%s)</span>', 'Active <span class="count">(%s)</span>', 'acf' ); $wp_post_statuses['trash'] = acf_extract_var( $wp_post_statuses, 'trash' ); $this->check_duplicate(); $this->check_sync(); add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts')); add_action('admin_footer', array($this, 'admin_footer')); add_filter('manage_edit-acf-field-group_columns', array($this, 'field_group_columns'), 10, 1); add_action('manage_acf-field-group_posts_custom_column', array($this, 'field_group_columns_html'), 10, 2); } function admin_enqueue_scripts() { wp_enqueue_script('acf-input'); } function check_duplicate() { if( $ids = acf_maybe_get($_GET, 'acfduplicatecomplete') ) { $ids = explode(',', $ids); $total = count($ids); if( $total == 1 ) { acf_add_admin_notice( sprintf(__('Field group duplicated. %s', 'acf'), '<a href="' . get_edit_post_link($ids[0]) . '">' . get_the_title($ids[0]) . '</a>') ); } else { acf_add_admin_notice( sprintf(_n( '%s field group duplicated.', '%s field groups duplicated.', $total, 'acf' ), $total) ); } } if( $id = acf_maybe_get($_GET, 'acfduplicate') ) { check_admin_referer('bulk-posts'); $field_group = acf_duplicate_field_group( $id ); wp_redirect( admin_url( $this->url . '&acfduplicatecomplete=' . $field_group['ID'] ) ); exit; } elseif( acf_maybe_get($_GET, 'action2') === 'acfduplicate' ) { check_admin_referer('bulk-posts'); $ids = acf_maybe_get($_GET, 'post'); if( !empty($ids) ) { $new_ids = array(); foreach( $ids as $id ) { $field_group = acf_duplicate_field_group( $id ); $new_ids[] = $field_group['ID']; } wp_redirect( admin_url( $this->url . '&acfduplicatecomplete=' . implode(',', $new_ids)) ); exit; } } } function check_sync() { if( $ids = acf_maybe_get($_GET, 'acfsynccomplete') ) { $ids = explode(',', $ids); $total = count($ids); if( $total == 1 ) { acf_add_admin_notice( sprintf(__('Field group synchronised. %s', 'acf'), '<a href="' . get_edit_post_link($ids[0]) . '">' . get_the_title($ids[0]) . '</a>') ); } else { acf_add_admin_notice( sprintf(_n( '%s field group synchronised.', '%s field groups synchronised.', $total, 'acf' ), $total) ); } } $groups = acf_get_field_groups(); if( empty($groups) ) { return; } foreach( $groups as $group ) { $local = acf_maybe_get($group, 'local', false); $modified = acf_maybe_get($group, 'modified', 0); $private = acf_maybe_get($group, 'private', false); if( $local !== 'json' || $private ) { } elseif( !$group['ID'] ) { $this->sync[ $group['key'] ] = $group; } elseif( $modified && $modified > get_post_modified_time('U', true, $group['ID'], true) ) { $this->sync[ $group['key'] ] = $group; } } if( empty($this->sync) ) { return; } if( $key = acf_maybe_get($_GET, 'acfsync') ) { check_admin_referer('bulk-posts'); if( acf_have_local_fields( $key ) ) { $this->sync[ $key ]['fields'] = acf_get_local_fields( $key ); } $field_group = acf_import_field_group( $this->sync[ $key ] ); wp_redirect( admin_url( $this->url . '&acfsynccomplete=' . $field_group['ID'] ) ); exit; } elseif( acf_maybe_get($_GET, 'action2') === 'acfsync' ) { check_admin_referer('bulk-posts'); $keys = acf_maybe_get($_GET, 'post'); if( !empty($keys) ) { $new_ids = array(); foreach( $keys as $key ) { if( acf_have_local_fields( $key ) ) { $this->sync[ $key ]['fields'] = acf_get_local_fields( $key ); } $field_group = acf_import_field_group( $this->sync[ $key ] ); $new_ids[] = $field_group['ID']; } wp_redirect( admin_url( $this->url . '&acfsynccomplete=' . implode(',', $new_ids)) ); exit; } } add_filter('views_edit-acf-field-group', array($this, 'list_table_views')); } function list_table_views( $views ) { $class = ''; $total = count($this->sync); if( acf_maybe_get($_GET, 'post_status') === 'sync' ) { add_action('admin_footer', array($this, 'sync_admin_footer'), 5); $class = ' class="current"'; global $wp_list_table; $wp_list_table->set_pagination_args( array( 'total_items' => $total, 'total_pages' => 1, 'per_page' => $total )); } $views['json'] = '<a' . $class . ' href="' . admin_url($this->url . '&post_status=sync') . '">' . __('Sync available', 'acf') . ' <span class="count">(' . $total . ')</span></a>'; return $views; } function trashed_post( $post_id ) { if( get_post_type($post_id) != 'acf-field-group' ) { return; } acf_trash_field_group( $post_id ); } function untrashed_post( $post_id ) { if( get_post_type($post_id) != 'acf-field-group' ) { return; } acf_untrash_field_group( $post_id ); } function deleted_post( $post_id ) { if( get_post_type($post_id) != 'acf-field-group' ) { return; } acf_delete_field_group( $post_id ); } function field_group_columns( $columns ) { return array( 'cb' => '<input type="checkbox" />', 'title' => __('Title', 'acf'), 'acf-fg-description' => __('Description', 'acf'), 'acf-fg-status' => '<i class="acf-icon acf-icon-dot-3 small acf-js-tooltip" title="' . __('Status', 'acf') . '"></i>', 'acf-fg-count' => __('Fields', 'acf'), ); } function field_group_columns_html( $column, $post_id ) { $field_group = acf_get_field_group( $post_id ); $this->render_column( $column, $field_group ); } function render_column( $column, $field_group ) { if( $column == 'acf-fg-description' ) { if( $field_group['description'] ) { echo '<span class="acf-description">(' . $field_group['description'] . ')</span>'; } } elseif( $column == 'acf-fg-status' ) { if( isset($this->sync[ $field_group['key'] ]) ) { echo '<i class="acf-icon acf-icon-sync grey small acf-js-tooltip" title="' . __('Sync available', 'acf') .'"></i> '; } if( $field_group['active'] ) { } else { echo '<i class="acf-icon acf-icon-minus yellow small acf-js-tooltip" title="' . __('Disabled', 'acf') . '"></i> '; } } elseif( $column == 'acf-fg-count' ) { echo acf_get_field_count( $field_group ); } } function admin_footer() { $www = 'http://www.advancedcustomfields.com/resources/'; ?><script type="text/html" id="tmpl-acf-column-2">
<div class="acf-column-2">
	<div class="acf-box">
		<div class="inner">
			<h2><?php echo acf_get_setting('name'); ?> <?php echo acf_get_setting('version'); ?></h2>

			<h3><?php _e("Changelog",'acf'); ?></h3>
			<p><?php _e("See what's new in",'acf'); ?> <a href="<?php echo admin_url('edit.php?post_type=acf-field-group&page=acf-settings-info&tab=changelog'); ?>"><?php _e("version",'acf'); ?> <?php echo acf_get_setting('version'); ?></a>
			
			<h3><?php _e("Resources",'acf'); ?></h3>
			<ul>
				<li><a href="<?php echo $www; ?>#getting-started" target="_blank"><?php _e("Getting Started",'acf'); ?></a></li>
				<li><a href="<?php echo $www; ?>#updates" target="_blank"><?php _e("Updates",'acf'); ?></a></li>
				<li><a href="<?php echo $www; ?>#field-types" target="_blank"><?php _e("Field Types",'acf'); ?></a></li>
				<li><a href="<?php echo $www; ?>#functions" target="_blank"><?php _e("Functions",'acf'); ?></a></li>
				<li><a href="<?php echo $www; ?>#actions" target="_blank"><?php _e("Actions",'acf'); ?></a></li>
				<li><a href="<?php echo $www; ?>#filters" target="_blank"><?php _e("Filters",'acf'); ?></a></li>
				<li><a href="<?php echo $www; ?>#how-to" target="_blank"><?php _e("'How to' guides",'acf'); ?></a></li>
				<li><a href="<?php echo $www; ?>#tutorials" target="_blank"><?php _e("Tutorials",'acf'); ?></a></li>
			</ul>
		</div>
		<div class="footer footer-blue">
			<ul class="acf-hl">
				<li><?php _e("Created by",'acf'); ?> Elliot Condon</li>
			</ul>
		</div>
	</div>
</div>
<div class="acf-clear"></div>
</script>
<script type="text/javascript">
(function($){
	
	// wrap
	$('#wpbody .wrap').attr('id', 'acf-field-group-wrap');
	
	
	// wrap column main
	$('#acf-field-group-wrap').wrapInner('<div class="acf-columns-2" />');
	
	
	// add column main
	$('#posts-filter').addClass('acf-column-1');
	
	
	// add column side
	$('#posts-filter').after( $('#tmpl-acf-column-2').html() );
	
	
	// modify row actions
	$('#the-list tr').each(function(){
		
		// vars
		var $tr = $(this),
			id = $tr.attr('id'),
			description = $tr.find('.column-acf-fg-description').html();
		
		
		// replace Quick Edit with Duplicate (sync page has no id attribute)
		if( id ) {
			
			// vars
			var post_id	= id.replace('post-', '');
			
			
			// create el
			var $span = $('<span class="acf-duplicate-field-group"><a title="<?php _e('Duplicate this item', 'acf'); ?>" href="<?php echo admin_url($this->url . '&acfduplicate='); ?>' + post_id + '&_wpnonce=<?php echo wp_create_nonce('bulk-posts'); ?>"><?php _e('Duplicate', 'acf'); ?></a> | </span>');
			
			
			// replace
			$tr.find('.column-title .row-actions .inline').replaceWith( $span );
			
		}
		
		
		// add description to title
		$tr.find('.column-title .row-title').after( description );
		
	});
	
	
	// modify bulk actions
	$('#bulk-action-selector-bottom option[value="edit"]').attr('value','acfduplicate').text('<?php _e( 'Duplicate', 'acf' ); ?>');
	
	
	// remove screen option
	$('.metabox-prefs label[for="description-hide"]').remove();
	
	
	// clean up table
	$('.wp-list-table .column-acf-fg-description').remove();
	$('#adv-settings label[for="acf-fg-description-hide"]').remove();
	
})(jQuery);
</script>
<?php
 } function sync_admin_footer() { $i = -1; $columns = array( 'acf-fg-description', 'acf-fg-status', 'acf-fg-count' ); ?>
<script type="text/html" id="tmpl-acf-json-tbody">
<?php foreach( $this->sync as $field_group ): $i++; ?>
	<tr <?php if($i%2 == 0): ?>class="alternate"<?php endif; ?>>
		<th class="check-column" scope="row">
			<label for="cb-select-<?php echo $field_group['key']; ?>" class="screen-reader-text"><?php printf( __( 'Select %s', 'acf' ), $field_group['title'] ); ?></label>
			<input type="checkbox" value="<?php echo $field_group['key']; ?>" name="post[]" id="cb-select-<?php echo $field_group['key']; ?>">
		</th>
		<td class="post-title page-title column-title">
			<strong><span class="row-title"><?php echo $field_group['title']; ?></span></strong>
			<div class="row-actions">
				<span class="import"><a title="<?php echo esc_attr( __('Synchronise field group', 'acf') ); ?>" href="<?php echo admin_url($this->url . '&post_status=sync&acfsync=' . $field_group['key'] . '&_wpnonce=' . wp_create_nonce('bulk-posts')); ?>"><?php _e( 'Sync', 'acf' ); ?></a></span>
			</div>
		</td>
		<?php foreach( $columns as $column ): ?>
			<td class="column-<?php echo $column; ?>"><?php $this->render_column( $column, $field_group ); ?></td>
		<?php endforeach; ?>
	</tr>
<?php endforeach; ?>
</script>
<script type="text/javascript">
(function($){
	
	// update table HTML
	$('#the-list').html( $('#tmpl-acf-json-tbody').html() );
	
	
	// modify bulk actions
	$('#bulk-action-selector-bottom option[value="edit"]').attr('value','acfsync').text('<?php _e('Sync', 'acf'); ?>');
	$('#bulk-action-selector-bottom option[value="trash"]').remove();
		
})(jQuery);
</script>
<?php
 } } new acf_admin_field_groups(); endif; ?>
