<?php
 if( ! class_exists('acf_form_attachment') ) : class acf_form_attachment { function __construct() { add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts')); add_filter('attachment_fields_to_edit', array($this, 'edit_attachment'), 10, 2); add_filter('attachment_fields_to_save', array($this, 'save_attachment'), 10, 2); } function validate_page() { global $pagenow, $typenow, $wp_version; if( $pagenow === 'post.php' && $typenow === 'attachment' ) { return true; } if( $pagenow === 'upload.php' && version_compare($wp_version, '4.0', '>=') ) { add_action('admin_footer', array($this, 'admin_footer'), 0); return true; } return false; } function admin_enqueue_scripts() { if( !$this->validate_page() ) { return; } acf_enqueue_scripts(); } function admin_footer() { acf_form_data(array( 'post_id' => 0, 'nonce' => 'attachment', 'ajax' => 1 )); } function edit_attachment( $form_fields, $post ) { $el = 'tr'; $post_id = $post->ID; $args = array( 'attachment' => 'All' ); if( $this->validate_page() ) { } $field_groups = acf_get_field_groups( $args ); if( !empty($field_groups) ) { ob_start(); acf_form_data(array( 'post_id' => $post_id, 'nonce' => 'attachment', )); if( $this->validate_page() ) { echo '<style type="text/css">
					.compat-attachment-fields,
					.compat-attachment-fields > tbody,
					.compat-attachment-fields > tbody > tr,
					.compat-attachment-fields > tbody > tr > th,
					.compat-attachment-fields > tbody > tr > td {
						display: block;
					}
					tr.acf-field {
						display: block;
						margin: 0 0 13px;
					}
					tr.acf-field td.acf-label {
						display: block;
						margin: 0;
					}
					tr.acf-field td.acf-input {
						display: block;
						margin: 0;
					}
				</style>'; } echo '</td></tr>'; foreach( $field_groups as $field_group ) { $fields = acf_get_fields( $field_group ); acf_render_fields( $post_id, $fields, $el, 'field' ); } echo '<tr class="compat-field-acf-blank"><td>'; $html = ob_get_contents(); ob_end_clean(); $form_fields[ 'acf-form-data' ] = array( 'label' => '', 'input' => 'html', 'html' => $html ); } return $form_fields; } function save_attachment( $post, $attachment ) { if( ! acf_verify_nonce('attachment') ) { return $post; } if( acf_validate_save_post(true) ) { acf_save_post( $post['ID'] ); } return $post; } } new acf_form_attachment(); endif; ?>
