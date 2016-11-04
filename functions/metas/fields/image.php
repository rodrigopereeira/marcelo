<?php
 if( ! class_exists('acf_field_image') ) : class acf_field_image extends acf_field { function __construct() { $this->name = 'image'; $this->label = __("Image",'acf'); $this->category = 'content'; $this->defaults = array( 'return_format' => 'array', 'preview_size' => 'thumbnail', 'library' => 'all', 'min_width' => 0, 'min_height' => 0, 'min_size' => 0, 'max_width' => 0, 'max_height' => 0, 'max_size' => 0, 'mime_types' => '' ); $this->l10n = array( 'select' => __("Select Image",'acf'), 'edit' => __("Edit Image",'acf'), 'update' => __("Update Image",'acf'), 'uploadedTo' => __("Uploaded to this post",'acf'), 'all' => __("All images",'acf'), ); add_filter('get_media_item_args', array($this, 'get_media_item_args')); add_filter('wp_prepare_attachment_for_js', array($this, 'wp_prepare_attachment_for_js'), 10, 3); parent::__construct(); } function render_field( $field ) { $uploader = acf_get_setting('uploader'); if( $uploader == 'wp' ) { acf_enqueue_uploader(); } $url = ''; $div = array( 'class' => 'acf-image-uploader acf-cf', 'data-preview_size' => $field['preview_size'], 'data-library' => $field['library'], 'data-mime_types' => $field['mime_types'], 'data-uploader' => $uploader ); if( $field['value'] && is_numeric($field['value']) ) { $url = wp_get_attachment_image_src($field['value'], $field['preview_size']); if( $url ) { $url = $url[0]; $div['class'] .= ' has-value'; } } ?>
<div <?php acf_esc_attr_e( $div ); ?>>
	<div class="acf-hidden">
		<?php acf_hidden_input(array( 'name' => $field['name'], 'value' => $field['value'], 'data-name' => 'id' )); ?>
	</div>
	<div class="view show-if-value acf-soh">
		<img data-name="image" src="<?php echo $url; ?>" alt=""/>
		<ul class="acf-hl acf-soh-target">
			<?php if( $uploader != 'basic' ): ?>
				<li><a class="acf-icon acf-icon-pencil dark" data-name="edit" href="#"></a></li>
			<?php endif; ?>
			<li><a class="acf-icon acf-icon-cancel dark" data-name="remove" href="#"></a></li>
		</ul>
	</div>
	<div class="view hide-if-value">
		<?php if( $uploader == 'basic' ): ?>
			
			<?php if( $field['value'] && !is_numeric($field['value']) ): ?>
				<div class="acf-error-message"><p><?php echo $field['value']; ?></p></div>
			<?php endif; ?>
			
			<input type="file" name="<?php echo $field['name']; ?>" id="<?php echo $field['id']; ?>" />
			
		<?php else: ?>
			
			<p style="margin:0;"><?php _e('No image selected','acf'); ?> <a data-name="add" class="acf-button" href="#"><?php _e('Add Image','acf'); ?></a></p>
			
		<?php endif; ?>
	</div>
</div>
<?php
 } function render_field_settings( $field ) { $clear = array( 'min_width', 'min_height', 'min_size', 'max_width', 'max_height', 'max_size' ); foreach( $clear as $k ) { if( empty($field[$k]) ) { $field[$k] = ''; } } acf_render_field_setting( $field, array( 'label' => __('Return Value','acf'), 'instructions' => __('Specify the returned value on front end','acf'), 'type' => 'radio', 'name' => 'return_format', 'layout' => 'horizontal', 'choices' => array( 'array' => __("Image Array",'acf'), 'url' => __("Image URL",'acf'), 'id' => __("Image ID",'acf') ) )); acf_render_field_setting( $field, array( 'label' => __('Preview Size','acf'), 'instructions' => __('Shown when entering data','acf'), 'type' => 'select', 'name' => 'preview_size', 'choices' => acf_get_image_sizes() )); acf_render_field_setting( $field, array( 'label' => __('Library','acf'), 'instructions' => __('Limit the media library choice','acf'), 'type' => 'radio', 'name' => 'library', 'layout' => 'horizontal', 'choices' => array( 'all' => __('All', 'acf'), 'uploadedTo' => __('Uploaded to post', 'acf') ) )); acf_render_field_setting( $field, array( 'label' => __('Minimum','acf'), 'instructions' => __('Restrict which images can be uploaded','acf'), 'type' => 'text', 'name' => 'min_width', 'prepend' => __('Width', 'acf'), 'append' => 'px', )); acf_render_field_setting( $field, array( 'label' => '', 'type' => 'text', 'name' => 'min_height', 'prepend' => __('Height', 'acf'), 'append' => 'px', 'wrapper' => array( 'data-append' => 'min_width' ) )); acf_render_field_setting( $field, array( 'label' => '', 'type' => 'text', 'name' => 'min_size', 'prepend' => __('File size', 'acf'), 'append' => 'MB', 'wrapper' => array( 'data-append' => 'min_width' ) )); acf_render_field_setting( $field, array( 'label' => __('Maximum','acf'), 'instructions' => __('Restrict which images can be uploaded','acf'), 'type' => 'text', 'name' => 'max_width', 'prepend' => __('Width', 'acf'), 'append' => 'px', )); acf_render_field_setting( $field, array( 'label' => '', 'type' => 'text', 'name' => 'max_height', 'prepend' => __('Height', 'acf'), 'append' => 'px', 'wrapper' => array( 'data-append' => 'max_width' ) )); acf_render_field_setting( $field, array( 'label' => '', 'type' => 'text', 'name' => 'max_size', 'prepend' => __('File size', 'acf'), 'append' => 'MB', 'wrapper' => array( 'data-append' => 'max_width' ) )); acf_render_field_setting( $field, array( 'label' => __('Allowed file types','acf'), 'instructions' => __('Comma separated list. Leave blank for all types','acf'), 'type' => 'text', 'name' => 'mime_types', )); } function format_value( $value, $post_id, $field ) { if( empty($value) ) { return $value; } $value = intval($value); if( $field['return_format'] == 'url' ) { return wp_get_attachment_url( $value ); } elseif( $field['return_format'] == 'array' ) { return acf_get_attachment( $value ); } return $value; } function get_media_item_args( $vars ) { $vars['send'] = true; return($vars); } function wp_prepare_attachment_for_js( $response, $attachment, $meta ) { if( $response['type'] != 'image' ) { return $response; } if( !isset($meta['sizes']) ) { return $response; } $attachment_url = $response['url']; $base_url = str_replace( wp_basename( $attachment_url ), '', $attachment_url ); if( isset($meta['sizes']) && is_array($meta['sizes']) ) { foreach( $meta['sizes'] as $k => $v ) { if( !isset($response['sizes'][ $k ]) ) { $response['sizes'][ $k ] = array( 'height' => $v['height'], 'width' => $v['width'], 'url' => $base_url . $v['file'], 'orientation' => $v['height'] > $v['width'] ? 'portrait' : 'landscape', ); } } } return $response; } function update_value( $value, $post_id, $field ) { if( is_array($value) && isset($value['ID']) ) { return $value['ID']; } if( is_object($value) && isset($value->ID) ) { return $value->ID; } return $value; } } new acf_field_image(); endif; ?>
