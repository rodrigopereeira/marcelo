<?php
 if( ! class_exists('acf_form_taxonomy') ) : class acf_form_taxonomy { var $form = '#addtag'; function __construct() { add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts')); add_action('create_term', array($this, 'save_term'), 10, 3); add_action('edit_term', array($this, 'save_term'), 10, 3); add_action('delete_term', array($this, 'delete_term'), 10, 4); } function validate_page() { global $pagenow; if( $pagenow == 'edit-tags.php' ) { return true; } return false; } function admin_enqueue_scripts() { if( !$this->validate_page() ) { return; } $screen = get_current_screen(); $taxonomy = $screen->taxonomy; acf_enqueue_scripts(); add_action('admin_footer', array($this, 'admin_footer'), 10, 1); add_action("{$taxonomy}_add_form_fields", array($this, 'add_term'), 10, 1); add_action("{$taxonomy}_edit_form", array($this, 'edit_term'), 10, 2); } function add_term( $taxonomy ) { $post_id = "{$taxonomy}_0"; $args = array( 'taxonomy' => $taxonomy ); $this->form = '#addtag'; $field_groups = acf_get_field_groups( $args ); if( !empty($field_groups) ) { acf_form_data(array( 'post_id' => $post_id, 'nonce' => 'taxonomy', )); foreach( $field_groups as $field_group ) { $fields = acf_get_fields( $field_group ); acf_render_fields( $post_id, $fields, 'div', 'field' ); } } } function edit_term( $term, $taxonomy ) { $post_id = "{$taxonomy}_{$term->term_id}"; $args = array( 'taxonomy' => $taxonomy ); $this->form = '#edittag'; $field_groups = acf_get_field_groups( $args ); if( !empty($field_groups) ) { acf_form_data(array( 'post_id' => $post_id, 'nonce' => 'taxonomy' )); foreach( $field_groups as $field_group ) { $fields = acf_get_fields( $field_group ); ?>
				<?php if( $field_group['style'] == 'default' ): ?>
					<h3><?php echo $field_group['title']; ?></h3>
				<?php endif; ?>
				<table class="form-table">
					<tbody>
						<?php acf_render_fields( $post_id, $fields, 'tr', 'field' ); ?>
					</tbody>
				</table>
				<?php  } } } function admin_footer() { ?>
<script type="text/javascript">
(function($) {
	
	// vars
	var $spinner = $('<?php echo $this->form; ?> p.submit .spinner');
	
	
	// create spinner if not exists (may exist in future WP versions)
	if( !$spinner.exists() ) {
		
		// create spinner
		$spinner = $('<span class="spinner"></span>');
		
		
		// append
		$('<?php echo $this->form; ?> p.submit').append( $spinner );
		
	}
	
	
	// update acf validation class
	acf.validation.error_class = 'form-invalid';
		
		
<?php if( $this->form == '#addtag' ): ?>

	// store origional HTML
	var $orig = $('#addtag').children('.acf-field').clone();
	
	
	// events
	$('#submit').on('click', function( e ){
		
		// bail early if not active
		if( !acf.validation.active ) {
		
			return true;
			
		}
		
		
		// ignore validation (only ignore once)
		if( acf.validation.ignore ) {
		
			acf.validation.ignore = 0;
			return true;
			
		}
		
		
		// bail early if this form does not contain ACF data
		if( !$('#addtag').find('#acf-form-data').exists() ) {
			
			return true;
		
		}
		
		
		// stop WP JS validation
		e.stopImmediatePropagation();
		
		
		// store submit trigger so it will be clicked if validation is passed
		acf.validation.$trigger = $(this);
		
					
		// run validation
		acf.validation.fetch( $('#addtag') );
		
		
		// stop all other click events on this input
		return false;
		
	});
	

	$(document).ajaxComplete(function(event, xhr, settings) {
		
		// bail early if is other ajax call
		if( settings.data.indexOf('action=add-tag') == -1 ) {
			
			return;
			
		}
		
		
		// unlock form
		acf.validation.toggle( $('#addtag'), 'unlock' );
		
		
		// bail early if response contains error
		if( xhr.responseText.indexOf('wp_error') !== -1 ) {
			
			return;
			
		}
		
		
		// action for 3rd party customization
		acf.do_action('remove', $('#addtag'));
		
		
		// remove old fields
		$('#addtag').find('.acf-field').remove();
		
		
		// add orig fields
		$('#acf-form-data').after( $orig.clone() );
		
		
		// action for 3rd party customization
		acf.do_action('append', $('#addtag'));
		
	});
	
<?php endif; ?>
	
})(jQuery);	
</script>
<?php
 } function save_term( $term_id, $tt_id, $taxonomy ) { if( ! acf_verify_nonce('taxonomy') ) { return $term_id; } if( acf_validate_save_post(true) ) { acf_save_post("{$taxonomy}_{$term_id}"); } } function delete_term( $term, $tt_id, $taxonomy, $deleted_term ) { global $wpdb; $values = $wpdb->query($wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s", '%' . $taxonomy . '_' . $term . '%' )); } } new acf_form_taxonomy(); endif; ?>
