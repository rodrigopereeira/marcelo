<?php
 if( ! class_exists('acf_form_comment') ) : class acf_form_comment { function __construct() { add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) ); add_action( 'comment_form_logged_in_after', array( $this, 'add_comment') ); add_action( 'comment_form_after_fields', array( $this, 'add_comment') ); add_action( 'edit_comment', array( $this, 'save_comment' ), 10, 1 ); add_action( 'comment_post', array( $this, 'save_comment' ), 10, 1 ); } function validate_page() { global $pagenow; if( $pagenow == 'comment.php' ) { return true; } return false; } function admin_enqueue_scripts() { if( ! $this->validate_page() ) { return; } acf_enqueue_scripts(); add_action('admin_footer', array($this, 'admin_footer'), 10, 1); add_action('add_meta_boxes_comment', array($this, 'edit_comment'), 10, 1); } function edit_comment( $comment ) { $post_id = "comment_{$comment->comment_ID}"; $field_groups = acf_get_field_groups(array( 'comment' => $comment->comment_ID )); if( !empty($field_groups) ) { acf_form_data(array( 'post_id' => $post_id, 'nonce' => 'comment' )); foreach( $field_groups as $field_group ) { $fields = acf_get_fields( $field_group ); ?>
				<div id="acf-<?php echo $field_group['ID']; ?>" class="stuffbox editcomment">
					<h3><?php echo $field_group['title']; ?></h3>
					<div class="inside">
						<table class="form-table">
							<tbody>
								<?php acf_render_fields( $post_id, $fields, 'tr', 'field' ); ?>
							</tbody>
						</table>
					</div>
				</div>
				<?php
 } } } function add_comment() { $post_id = "comment_0"; $field_groups = acf_get_field_groups(array( 'comment' => 'new' )); if( !empty($field_groups) ) { acf_form_data(array( 'post_id' => $post_id, 'nonce' => 'comment' )); foreach( $field_groups as $field_group ) { $fields = acf_get_fields( $field_group ); ?>
				<table class="form-table">
					<tbody>
						<?php acf_render_fields( $post_id, $fields, 'tr', 'field' ); ?>
					</tbody>
				</table>
				<?php
 } } } function save_comment( $comment_id ) { if( ! acf_verify_nonce('comment') ) { return $comment_id; } if( acf_validate_save_post(true) ) { acf_save_post( "comment_{$comment_id}" ); } } function admin_footer() { ?>
<script type="text/javascript">
(function($) {
	
	// vars
	var $spinner = $('#publishing-action .spinner');
	
	
	// create spinner if not exists (may exist in future WP versions)
	if( !$spinner.exists() ) {
		
		// create spinner
		$spinner = $('<span class="spinner"></span>');
		
		
		// append
		$('#publishing-action').prepend( $spinner );
		
	}
	
})(jQuery);	
</script>
<?php
 } } new acf_form_comment(); endif; ?>
