<?php
 if( ! class_exists('acf_form_widget') ) : class acf_form_widget { function __construct() { add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts')); add_action('in_widget_form', array($this, 'edit_widget'), 10, 3); add_filter('widget_update_callback', array($this, 'widget_update_callback'), 10, 4); add_action('wp_ajax_update-widget', array($this, 'ajax_update_widget'), 0, 1); } function admin_enqueue_scripts() { if( acf_is_screen('widgets') || acf_is_screen('customize') ) { } else { return; } acf_enqueue_scripts(); add_action('acf/input/admin_footer', array($this, 'admin_footer')); } function edit_widget( $widget, $return, $instance ) { $post_id = 0; if( $widget->number !== '__i__' ) { $post_id = "widget_{$widget->id}"; } $field_groups = acf_get_field_groups(array( 'widget' => $widget->id_base )); if( !empty($field_groups) ) { acf_form_data(array( 'post_id' => $post_id, 'nonce' => 'widget' )); foreach( $field_groups as $field_group ) { $fields = acf_get_fields( $field_group ); acf_render_fields( $post_id, $fields, 'div', 'field' ); } if( $widget->updated ): ?>
			<script type="text/javascript">
			(function($) {
				
				acf.do_action('append', $('[id^="widget"][id$="<?php echo $widget->id; ?>"]') );
				
			})(jQuery);	
			</script>
			<?php endif; } } function ajax_update_widget() { remove_filter('widget_update_callback', array($this, 'widget_update_callback'), 10, 4); if( !acf_verify_nonce('widget') ) { return; } $id = acf_maybe_get($_POST, 'widget-id'); if( $id && acf_validate_save_post() ) { acf_save_post( "widget_{$id}" ); } } function widget_update_callback( $instance, $new_instance, $old_instance, $widget ) { if( !acf_verify_nonce('widget') ) { return $instance; } if( acf_validate_save_post() ) { acf_save_post( "widget_{$widget->id}" ); } return $instance; } function admin_footer() { ?>
<script type="text/javascript">
(function($) {
	
	 acf.add_filter('get_fields', function( $fields ){
	 	
	 	return $fields.not('#available-widgets .acf-field');

    });
	
	
	$('#widgets-right').on('click', '.widget-control-save', function( e ){
		
		// vars
		var $form = $(this).closest('form');
		
		
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
		if( !$form.find('#acf-form-data').exists() ) {
		
			return true;
		
		}

		
		// stop WP JS validation
		e.stopImmediatePropagation();
		
		
		// store submit trigger so it will be clicked if validation is passed
		acf.validation.$trigger = $(this);
		
		
		// run validation
		acf.validation.fetch( $form );
		
		
		// stop all other click events on this input
		return false;
		
	});
	
	
	$(document).on('click', '.widget-top', function(){
		
		var $el = $(this).parent().children('.widget-inside');
		
		setTimeout(function(){
			
			acf.get_fields('', $el).each(function(){
				
				acf.do_action('show_field', $(this));	
				
			});
			
		}, 250);
				
	});
	
	$(document).on('widget-added', function( e, $widget ){
		
		acf.do_action('append', $widget );
		
	});
	
	$(document).on('widget-saved widget-updated', function( e, $widget ){
		
		// unlock form
		acf.validation.toggle( $widget, 'unlock' );
		
		
		// submit
		acf.do_action('submit', $widget );
		
	});
	
	<?php if( acf_is_screen('customize') ): ?>
	
	// customizer saves widget on any input change, so unload is not needed
	acf.unload.active = 0;

	<?php endif; ?>
		
})(jQuery);	
</script>
<?php
 } } new acf_form_widget(); endif; ?>
