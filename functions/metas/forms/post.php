<?php
 if( ! class_exists('acf_form_post') ) : class acf_form_post { var $post_id = 0, $typenow = '', $style = ''; function __construct() { add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts')); add_filter('wp_insert_post_empty_content', array($this, 'wp_insert_post_empty_content'), 10, 2); add_action('save_post', array($this, 'save_post'), 10, 2); add_action('wp_ajax_acf/post/get_field_groups', array($this, 'get_field_groups')); } function validate_page() { global $post, $pagenow, $typenow; $return = false; if( in_array($pagenow, array('post.php', 'post-new.php')) ) { $return = true; } if( !empty($post) ) { $this->post_id = $post->ID; $this->typenow = $typenow; } if( in_array($typenow, array('acf-field-group', 'attachment')) ) { return false; } if( $pagenow == "admin.php" && isset( $_GET['page'] ) && $_GET['page'] == "shopp-products" && isset( $_GET['id'] ) ) { $return = true; $this->post_id = absint( $_GET['id'] ); $this->typenow = 'shopp_product'; } return $return; } function admin_enqueue_scripts() { if( ! $this->validate_page() ) { return; } acf_enqueue_scripts(); add_action('acf/input/admin_head', array($this,'admin_head')); add_action('acf/input/admin_footer', array($this,'admin_footer')); } function admin_head() { $style_found = false; $field_groups = acf_get_field_groups(); if( !empty($field_groups) ) { foreach( $field_groups as $i => $field_group ) { $id = "acf-{$field_group['key']}"; $title = $field_group['title']; $context = $field_group['position']; $priority = 'high'; $args = array( 'field_group' => $field_group, 'visibility' => false ); if( $context == 'side' ) { $priority = 'core'; } $priority = apply_filters('acf/input/meta_box_priority', $priority, $field_group); $args['visibility'] = acf_get_field_group_visibility( $field_group, array( 'post_id' => $this->post_id, 'post_type' => $this->typenow )); add_meta_box( $id, $title, array($this, 'render_meta_box'), $this->typenow, $context, $priority, $args ); if( !$style_found && $args['visibility'] ) { $style_found = true; $this->style = acf_get_field_group_style( $field_group ); } } } add_action('edit_form_after_title', array($this, 'edit_form_after_title')); add_filter('is_protected_meta', array($this, 'is_protected_meta'), 10, 3); } function edit_form_after_title() { global $post, $wp_meta_boxes; acf_form_data(array( 'post_id' => $this->post_id, 'nonce' => 'post', 'ajax' => 1 )); do_meta_boxes( get_current_screen(), 'acf_after_title', $post); unset( $wp_meta_boxes['post']['acf_after_title'] ); } function render_meta_box( $post, $args ) { extract( $args ); extract( $args ); $o = array( 'id' => $id, 'key' => $field_group['key'], 'style' => $field_group['style'], 'edit_url' => '', 'edit_title' => __('Edit field group', 'acf'), 'visibility' => $visibility ); if( $visibility ) { $fields = acf_get_fields( $field_group ); if( $field_group['label_placement'] == 'left' ) { ?>
				<table class="acf-table">
					<tbody>
						<?php acf_render_fields( $this->post_id, $fields, 'tr', $field_group['instruction_placement'] ); ?>
					</tbody>
				</table>
				<?php
 } else { acf_render_fields( $this->post_id, $fields, 'div', $field_group['instruction_placement'] ); } } else { echo '<div class="acf-replace-with-fields"><div class="acf-loading"></div></div>'; } if( $field_group['ID'] && acf_current_user_can_admin() ) { $o['edit_url'] = admin_url('post.php?post=' . $field_group['ID'] . '&action=edit'); } ?>
<script type="text/javascript">
if( typeof acf !== 'undefined' ) {
		
	acf.postbox.render(<?php echo json_encode($o); ?>);	

}
</script>
<?php
 } function admin_footer(){ echo '<style type="text/css" id="acf-style">' . $this->style . '</style>'; } function get_field_groups() { $options = acf_parse_args($_POST, array( 'nonce' => '', 'post_id' => 0, 'ajax' => 1, )); $r = array(); $nonce = acf_extract_var( $options, 'nonce' ); if( ! wp_verify_nonce($nonce, 'acf_nonce') ) { die; } $field_groups = acf_get_field_groups( $options ); if( !empty($field_groups) ) { foreach( $field_groups as $field_group ) { $class = 'acf-postbox ' . $field_group['style']; $fields = acf_get_fields( $field_group ); ob_start(); if( $field_group['label_placement'] == 'left' ) { ?>
					<table class="acf-table">
						<tbody>
							<?php acf_render_fields( $options['post_id'], $fields, 'tr', $field_group['instruction_placement'] ); ?>
						</tbody>
					</table>
					<?php
 } else { acf_render_fields( $options['post_id'], $fields, 'div', $field_group['instruction_placement'] ); } $html = ob_get_clean(); $style = acf_get_field_group_style( $field_group ); $r[] = array( 'key' => $field_group['key'], 'title' => $field_group['title'], 'html' => $html, 'style' => $style, 'class' => $class ); } } wp_send_json_success( $r ); } function wp_insert_post_empty_content( $maybe_empty, $postarr ) { if( $maybe_empty && !empty($_POST['_acfchanged']) ) { $maybe_empty = false; } return $maybe_empty; } function save_post( $post_id, $post ) { if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) { return $post_id; } if( in_array($post->post_type, array('acf-field', 'acf-field-group'))) { return $post_id; } if( !acf_verify_nonce('post', $post_id) ) { return $post_id; } if( get_post_status($post_id) == 'publish' ) { if( acf_validate_save_post(true) ) { acf_save_post( $post_id ); } } else { acf_save_post( $post_id ); } return $post_id; } function is_protected_meta( $protected, $meta_key, $meta_type ) { if( !$protected ) { $reference = acf_get_field_reference( $meta_key, $this->post_id ); if( acf_is_field_key($reference) ) { $protected = true; } } return $protected; } } new acf_form_post(); endif; ?>
