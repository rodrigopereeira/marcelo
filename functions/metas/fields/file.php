<?php
 if( ! class_exists('acf_field_file') ) : class acf_field_file extends acf_field { function __construct() { $this->name = 'file'; $this->label = __("File",'acf'); $this->category = 'content'; $this->defaults = array( 'return_format' => 'array', 'library' => 'all', 'min_size' => 0, 'max_size' => 0, 'mime_types' => '' ); $this->l10n = array( 'select' => __("Select File",'acf'), 'edit' => __("Edit File",'acf'), 'update' => __("Update File",'acf'), 'uploadedTo' => __("uploaded to this post",'acf'), ); add_filter('get_media_item_args', array($this, 'get_media_item_args')); add_filter('wp_prepare_attachment_for_js', array($this, 'wp_prepare_attachment_for_js'), 10, 3); parent::__construct(); } function render_field( $field ) { $uploader = acf_get_setting('uploader'); if( $uploader == 'wp' ) { acf_enqueue_uploader(); } $o = array( 'icon' => '', 'title' => '', 'size' => '', 'url' => '', 'name' => '', ); $div = array( 'class' => 'acf-file-uploader acf-cf', 'data-library' => $field['library'], 'data-mime_types' => $field['mime_types'], 'data-uploader' => $uploader ); if( $field['value'] && is_numeric($field['value']) ) { $file = get_post( $field['value'] ); if( $file ) { $div['class'] .= ' has-value'; $o['icon'] = wp_mime_type_icon( $file->ID ); $o['title'] = $file->post_title; $o['size'] = @size_format(filesize( get_attached_file( $file->ID ) )); $o['url'] = wp_get_attachment_url( $file->ID ); $explode = explode('/', $o['url']); $o['name'] = end( $explode ); } } ?>
<div <?php acf_esc_attr_e($div); ?>>
	<div class="acf-hidden">
		<?php acf_hidden_input(array( 'name' => $field['name'], 'value' => $field['value'], 'data-name' => 'id' )); ?>
	</div>
	<div class="show-if-value file-wrap acf-soh">
		<div class="file-icon">
			<img data-name="icon" src="<?php echo $o['icon']; ?>" alt=""/>
		</div>
		<div class="file-info">
			<p>
				<strong data-name="title"><?php echo $o['title']; ?></strong>
			</p>
			<p>
				<strong><?php _e('File Name', 'acf'); ?>:</strong>
				<a data-name="name" href="<?php echo $o['url']; ?>" target="_blank"><?php echo $o['name']; ?></a>
			</p>
			<p>
				<strong><?php _e('File Size', 'acf'); ?>:</strong>
				<span data-name="size"><?php echo $o['size']; ?></span>
			</p>
			
			<ul class="acf-hl acf-soh-target">
				<?php if( $uploader != 'basic' ): ?>
					<li><a class="acf-icon acf-icon-pencil dark" data-name="edit" href="#"></a></li>
				<?php endif; ?>
				<li><a class="acf-icon acf-icon-cancel dark" data-name="remove" href="#"></a></li>
			</ul>
		</div>
	</div>
	<div class="hide-if-value">
		<?php if( $uploader == 'basic' ): ?>
			
			<?php if( $field['value'] && !is_numeric($field['value']) ): ?>
				<div class="acf-error-message"><p><?php echo $field['value']; ?></p></div>
			<?php endif; ?>
			
			<input type="file" name="<?php echo $field['name']; ?>" id="<?php echo $field['id']; ?>" />
			
		<?php else: ?>
			
			<p style="margin:0;"><?php _e('No File selected','acf'); ?> <a data-name="add" class="acf-button" href="#"><?php _e('Add File','acf'); ?></a></p>
			
		<?php endif; ?>
		
	</div>
</div>
<?php
 } function render_field_settings( $field ) { $clear = array( 'min_size', 'max_size' ); foreach( $clear as $k ) { if( empty($field[$k]) ) { $field[$k] = ''; } } acf_render_field_setting( $field, array( 'label' => __('Return Value','acf'), 'instructions' => __('Specify the returned value on front end','acf'), 'type' => 'radio', 'name' => 'return_format', 'layout' => 'horizontal', 'choices' => array( 'array' => __("File Array",'acf'), 'url' => __("File URL",'acf'), 'id' => __("File ID",'acf') ) )); acf_render_field_setting( $field, array( 'label' => __('Library','acf'), 'instructions' => __('Limit the media library choice','acf'), 'type' => 'radio', 'name' => 'library', 'layout' => 'horizontal', 'choices' => array( 'all' => __('All', 'acf'), 'uploadedTo' => __('Uploaded to post', 'acf') ) )); acf_render_field_setting( $field, array( 'label' => __('Minimum','acf'), 'instructions' => __('Restrict which files can be uploaded','acf'), 'type' => 'text', 'name' => 'min_size', 'prepend' => __('File size', 'acf'), 'append' => 'MB', )); acf_render_field_setting( $field, array( 'label' => __('Maximum','acf'), 'instructions' => __('Restrict which files can be uploaded','acf'), 'type' => 'text', 'name' => 'max_size', 'prepend' => __('File size', 'acf'), 'append' => 'MB', )); acf_render_field_setting( $field, array( 'label' => __('Allowed file types','acf'), 'instructions' => __('Comma separated list. Leave blank for all types','acf'), 'type' => 'text', 'name' => 'mime_types', )); } function format_value( $value, $post_id, $field ) { if( empty($value) ) { return $value; } $value = intval($value); if( $field['return_format'] == 'url' ) { return wp_get_attachment_url($value); } elseif( $field['return_format'] == 'array' ) { return acf_get_attachment( $value ); } return $value; } function get_media_item_args( $vars ) { $vars['send'] = true; return($vars); } function update_value( $value, $post_id, $field ) { if( is_array($value) && isset($value['ID']) ) { return $value['ID']; } if( is_object($value) && isset($value->ID) ) { return $value->ID; } return $value; } function wp_prepare_attachment_for_js( $response, $attachment, $meta ) { $fs = '0 kb'; if( $i = @filesize( get_attached_file( $attachment->ID ) ) ) { $fs = size_format( $i ); } $response['filesize'] = $fs; return $response; } } new acf_field_file(); endif; ?>
