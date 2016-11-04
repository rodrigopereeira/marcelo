<?php
 if( ! class_exists('acf_field_gallery') ) : class acf_field_gallery extends acf_field { function __construct() { $this->name = 'gallery'; $this->label = __("Gallery",'acf'); $this->category = 'content'; $this->defaults = array( 'preview_size' => 'thumbnail', 'library' => 'all', 'min' => 0, 'max' => 0, 'min_width' => 0, 'min_height' => 0, 'min_size' => 0, 'max_width' => 0, 'max_height' => 0, 'max_size' => 0, 'mime_types' => '' ); $this->l10n = array( 'select' => __("Add Image to Gallery",'acf'), 'edit' => __("Edit Image",'acf'), 'update' => __("Update Image",'acf'), 'uploadedTo' => __("uploaded to this post",'acf'), 'max' => __("Maximum selection reached",'acf') ); add_action('wp_ajax_acf/fields/gallery/get_attachment', array($this, 'ajax_get_attachment')); add_action('wp_ajax_nopriv_acf/fields/gallery/get_attachment', array($this, 'ajax_get_attachment')); add_action('wp_ajax_acf/fields/gallery/update_attachment', array($this, 'ajax_update_attachment')); add_action('wp_ajax_nopriv_acf/fields/gallery/update_attachment', array($this, 'ajax_update_attachment')); add_action('wp_ajax_acf/fields/gallery/get_sort_order', array($this, 'ajax_get_sort_order')); add_action('wp_ajax_nopriv_acf/fields/gallery/get_sort_order', array($this, 'ajax_get_sort_order')); parent::__construct(); } function ajax_get_attachment() { $options = acf_parse_args( $_POST, array( 'post_id' => 0, 'id' => 0, 'field_key' => '', 'nonce' => '', )); if( ! wp_verify_nonce($options['nonce'], 'acf_nonce') ) { die(); } if( empty($options['id']) ) { die(); } $field = acf_get_field( $options['field_key'] ); if( !$field ) { die(); } $this->render_attachment( $options['id'], $field ); die; } function ajax_update_attachment() { if( ! wp_verify_nonce($_REQUEST['nonce'], 'acf_nonce') ) { wp_send_json_error(); } if( empty($_REQUEST['attachments']) ) { wp_send_json_error(); } foreach( $_REQUEST['attachments'] as $id => $changes ) { if ( ! current_user_can( 'edit_post', $id ) ) wp_send_json_error(); $post = get_post( $id, ARRAY_A ); if ( 'attachment' != $post['post_type'] ) wp_send_json_error(); if ( isset( $changes['title'] ) ) $post['post_title'] = $changes['title']; if ( isset( $changes['caption'] ) ) $post['post_excerpt'] = $changes['caption']; if ( isset( $changes['description'] ) ) $post['post_content'] = $changes['description']; if ( isset( $changes['alt'] ) ) { $alt = wp_unslash( $changes['alt'] ); if ( $alt != get_post_meta( $id, '_wp_attachment_image_alt', true ) ) { $alt = wp_strip_all_tags( $alt, true ); update_post_meta( $id, '_wp_attachment_image_alt', wp_slash( $alt ) ); } } $post = apply_filters( 'attachment_fields_to_save', $post, $changes ); wp_update_post( $post ); acf_save_post( $id ); } wp_send_json_success(); } function ajax_get_sort_order() { $r = array(); $order = 'DESC'; $args = acf_parse_args( $_POST, array( 'ids' => 0, 'sort' => 'date', 'field_key' => '', 'nonce' => '', )); if( ! wp_verify_nonce($args['nonce'], 'acf_nonce') ) { wp_send_json_error(); } if( $args['sort'] == 'reverse' ) { $ids = array_reverse($args['ids']); wp_send_json_success($ids); } if( $args['sort'] == 'title' ) { $order = 'ASC'; } $ids = get_posts(array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => 'any', 'post__in' => $args['ids'], 'order' => $order, 'orderby' => $args['sort'], 'fields' => 'ids' )); if( !empty($ids) ) { wp_send_json_success($ids); } wp_send_json_error(); } function render_attachment( $id = 0, $field ) { $attachment = wp_prepare_attachment_for_js( $id ); $thumb = ''; $prefix = "attachments[{$id}]"; $compat = get_compat_media_markup( $id ); $dimentions = ''; if( isset($attachment['thumb']['src']) ) { $thumb = $attachment['thumb']['src']; } elseif( isset($attachment['sizes']['thumbnail']['url']) ) { $thumb = $attachment['sizes']['thumbnail']['url']; } elseif( $attachment['type'] === 'image' ) { $thumb = $attachment['url']; } else { $thumb = $attachment['icon']; } if( $attachment['type'] === 'audio' ) { $dimentions = __('Length', 'acf') . ': ' . $attachment['fileLength']; } elseif( !empty($attachment['width']) ) { $dimentions = $attachment['width'] . ' x ' . $attachment['height']; } if( $attachment['filesizeHumanReadable'] ) { $dimentions .= ' (' . $attachment['filesizeHumanReadable'] . ')'; } ?>
		<div class="acf-gallery-side-info acf-cf">
			<img src="<?php echo $thumb; ?>" alt="<?php echo $attachment['alt']; ?>" />
			<p class="filename"><strong><?php echo $attachment['filename']; ?></strong></p>
			<p class="uploaded"><?php echo $attachment['dateFormatted']; ?></p>
			<p class="dimensions"><?php echo $dimentions; ?></p>
			<p class="actions"><a href="#" class="edit-attachment" data-id="<?php echo $id; ?>"><?php _e('Edit', 'acf'); ?></a> <a href="#" class="remove-attachment" data-id="<?php echo $id; ?>"><?php _e('Remove', 'acf'); ?></a></p>
		</div>
		<table class="form-table">
			<tbody>
				<?php  acf_render_field_wrap(array( 'name' => 'title', 'prefix' => $prefix, 'type' => 'text', 'label' => 'Title', 'value' => $attachment['title'] ), 'tr'); acf_render_field_wrap(array( 'name' => 'caption', 'prefix' => $prefix, 'type' => 'textarea', 'label' => 'Caption', 'value' => $attachment['caption'] ), 'tr'); acf_render_field_wrap(array( 'name' => 'alt', 'prefix' => $prefix, 'type' => 'text', 'label' => 'Alt Text', 'value' => $attachment['alt'] ), 'tr'); acf_render_field_wrap(array( 'name' => 'description', 'prefix' => $prefix, 'type' => 'textarea', 'label' => 'Description', 'value' => $attachment['description'] ), 'tr'); ?>
			</tbody>
		</table>
		<?php echo $compat['item']; ?>
		
		<?php
 } function render_field( $field ) { acf_enqueue_uploader(); $posts = array(); $atts = array( 'id' => $field['id'], 'class' => "acf-gallery {$field['class']}", 'data-preview_size' => $field['preview_size'], 'data-library' => $field['library'], 'data-min' => $field['min'], 'data-max' => $field['max'], 'data-mime_types' => $field['mime_types'], ); $height = acf_get_user_setting('gallery_height', 400); $height = max( $height, 200 ); $atts['style'] = "height:{$height}px"; if( !empty($field['value']) ) { $posts = acf_get_posts(array( 'post_type' => 'attachment', 'post__in' => $field['value'] )); } ?>
<div <?php acf_esc_attr_e($atts); ?>>
	
	<div class="acf-hidden">
		<input type="hidden" <?php acf_esc_attr_e(array( 'name' => $field['name'], 'value' => '', 'data-name' => 'ids' )); ?> />
	</div>
	
	<div class="acf-gallery-main">
		
		<div class="acf-gallery-attachments">
			
			<?php if( !empty($posts) ): ?>
			
				<?php foreach( $posts as $post ): $type = acf_maybe_get(explode('/', $post->post_mime_type), 0); $thumb_id = $post->ID; $thumb_url = ''; $thumb_class = 'acf-gallery-attachment acf-soh'; $filename = wp_basename($post->guid); if( $type === 'image' || $type === 'audio' || $type === 'video' ) { if( $type === 'audio' || $type === 'video' ) { $thumb_id = get_post_thumbnail_id( $post->ID ); } if( $thumb_id ) { $thumb_url = wp_get_attachment_image_src( $thumb_id, $field['preview_size'] ); $thumb_url = acf_maybe_get( $thumb_url, 0 ); } } if( !$thumb_url ) { $thumb_url = wp_mime_type_icon( $post->ID ); $thumb_class .= ' is-mime-icon'; } ?>
					<div class="<?php echo $thumb_class; ?>" data-id="<?php echo $post->ID; ?>">
						<input type="hidden" name="<?php echo $field['name']; ?>[]" value="<?php echo $post->ID; ?>" />
						<div class="margin" title="<?php echo $filename; ?>">
							<div class="thumbnail">
								<img src="<?php echo $thumb_url; ?>"/>
							</div>
							<?php if( $type !== 'image' ): ?>
							<div class="filename"><?php echo acf_get_truncated($filename, 18); ?></div>
							<?php endif; ?>
						</div>
						<div class="actions acf-soh-target">
							<a class="acf-icon acf-icon-cancel dark remove-attachment" data-id="<?php echo $post->ID; ?>" href="#"></a>
						</div>
					</div>
					
				<?php endforeach; ?>
				
			<?php endif; ?>
			
			
		</div>
		
		<div class="acf-gallery-toolbar">
			
			<ul class="acf-hl">
				<li>
					<a href="#" class="acf-button blue add-attachment"><?php _e('Add to gallery', 'acf'); ?></a>
				</li>
				<li class="acf-fr">
					<select class="bulk-actions">
						<option value=""><?php _e('Bulk actions', 'acf'); ?></option>
						<option value="date"><?php _e('Sort by date uploaded', 'acf'); ?></option>
						<option value="modified"><?php _e('Sort by date modified', 'acf'); ?></option>
						<option value="title"><?php _e('Sort by title', 'acf'); ?></option>
						<option value="reverse"><?php _e('Reverse current order', 'acf'); ?></option>
					</select>
				</li>
			</ul>
			
		</div>
		
	</div>
	
	<div class="acf-gallery-side">
	<div class="acf-gallery-side-inner">
			
		<div class="acf-gallery-side-data"></div>
						
		<div class="acf-gallery-toolbar">
			
			<ul class="acf-hl">
				<li>
					<a href="#" class="acf-button close-sidebar"><?php _e('Close', 'acf'); ?></a>
				</li>
				<li class="acf-fr">
					<a class="acf-button blue update-attachment"><?php _e('Update', 'acf'); ?></a>
				</li>
			</ul>
			
		</div>
		
	</div>	
	</div>
	
</div>
		<?php
 } function render_field_settings( $field ) { $clear = array( 'min', 'max', 'min_width', 'min_height', 'min_size', 'max_width', 'max_height', 'max_size' ); foreach( $clear as $k ) { if( empty($field[$k]) ) { $field[$k] = ''; } } acf_render_field_setting( $field, array( 'label' => __('Minimum Selection','acf'), 'instructions' => '', 'type' => 'number', 'name' => 'min' )); acf_render_field_setting( $field, array( 'label' => __('Maximum Selection','acf'), 'instructions' => '', 'type' => 'number', 'name' => 'max' )); acf_render_field_setting( $field, array( 'label' => __('Preview Size','acf'), 'instructions' => __('Shown when entering data','acf'), 'type' => 'select', 'name' => 'preview_size', 'choices' => acf_get_image_sizes() )); acf_render_field_setting( $field, array( 'label' => __('Library','acf'), 'instructions' => __('Limit the media library choice','acf'), 'type' => 'radio', 'name' => 'library', 'layout' => 'horizontal', 'choices' => array( 'all' => __('All', 'acf'), 'uploadedTo' => __('Uploaded to post', 'acf') ) )); acf_render_field_setting( $field, array( 'label' => __('Minimum','acf'), 'instructions' => __('Restrict which images can be uploaded','acf'), 'type' => 'text', 'name' => 'min_width', 'prepend' => __('Width', 'acf'), 'append' => 'px', )); acf_render_field_setting( $field, array( 'label' => '', 'type' => 'text', 'name' => 'min_height', 'prepend' => __('Height', 'acf'), 'append' => 'px', 'wrapper' => array( 'data-append' => 'min_width' ) )); acf_render_field_setting( $field, array( 'label' => '', 'type' => 'text', 'name' => 'min_size', 'prepend' => __('File size', 'acf'), 'append' => 'MB', 'wrapper' => array( 'data-append' => 'min_width' ) )); acf_render_field_setting( $field, array( 'label' => __('Maximum','acf'), 'instructions' => __('Restrict which images can be uploaded','acf'), 'type' => 'text', 'name' => 'max_width', 'prepend' => __('Width', 'acf'), 'append' => 'px', )); acf_render_field_setting( $field, array( 'label' => '', 'type' => 'text', 'name' => 'max_height', 'prepend' => __('Height', 'acf'), 'append' => 'px', 'wrapper' => array( 'data-append' => 'max_width' ) )); acf_render_field_setting( $field, array( 'label' => '', 'type' => 'text', 'name' => 'max_size', 'prepend' => __('File size', 'acf'), 'append' => 'MB', 'wrapper' => array( 'data-append' => 'max_width' ) )); acf_render_field_setting( $field, array( 'label' => __('Allowed file types','acf'), 'instructions' => __('Comma separated list. Leave blank for all types','acf'), 'type' => 'text', 'name' => 'mime_types', )); } function format_value( $value, $post_id, $field ) { if( empty($value) ) { return false; } $posts = acf_get_posts(array( 'post_type' => 'attachment', 'post__in' => $value, )); foreach( array_keys($posts) as $i ) { $posts[ $i ] = acf_get_attachment( $posts[ $i ] ); } return $posts; } function validate_value( $valid, $value, $field, $input ){ if( empty($value) || !is_array($value) ) { $value = array(); } if( count($value) < $field['min'] ) { $valid = _n( '%s requires at least %s selection', '%s requires at least %s selections', $field['min'], 'acf' ); $valid = sprintf( $valid, $field['label'], $field['min'] ); } return $valid; } } new acf_field_gallery(); endif; ?>
