<?php
 if( ! class_exists('acf_field_oembed') ) : class acf_field_oembed extends acf_field { function __construct() { $this->name = 'oembed'; $this->label = __("oEmbed",'acf'); $this->category = 'content'; $this->defaults = array( 'width' => '', 'height' => '', ); $this->default_values = array( 'width' => 640, 'height' => 390 ); add_action('wp_ajax_acf/fields/oembed/search', array($this, 'ajax_search')); add_action('wp_ajax_nopriv_acf/fields/oembed/search', array($this, 'ajax_search')); parent::__construct(); } function wp_oembed_get( $url = '', $width = 0, $height = 0 ) { $embed = ''; $res = array( 'width' => $width, 'height' => $height ); $embed = @wp_oembed_get( $url, $res ); return $embed; } function ajax_search() { $args = acf_parse_args( $_POST, array( 's' => '', 'nonce' => '', 'width' => 0, 'height' => 0, )); if( !$args['width'] ) { $args['width'] = $this->default_values['width']; } if( !$args['height'] ) { $args['height'] = $this->default_values['height']; } if( ! wp_verify_nonce($args['nonce'], 'acf_nonce') ) { die(); } echo $this->wp_oembed_get($args['s'], $args['width'], $args['height']); die(); } function render_field( $field ) { foreach( $this->default_values as $k => $v ) { if( empty($field[ $k ]) ) { $field[ $k ] = $v; } } $atts = array( 'class' => 'acf-oembed', 'data-width' => $field['width'], 'data-height' => $field['height'] ); if( $field['value'] ) { $atts['class'] .= ' has-value'; } ?>
<div <?php acf_esc_attr_e($atts) ?>>
	<div class="acf-hidden">
		<input type="hidden" data-name="value-input" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo esc_attr($field['value']); ?>" />
	</div>
	<div class="title acf-soh">
		
		<div class="title-value">
			<h4 data-name="value-title"><?php echo $field['value']; ?></h4>
		</div>
		
		<div class="title-search">
			
			<input data-name="search-input" type="text" placeholder="<?php _e("Enter URL", 'acf'); ?>" autocomplete="off" />
		</div>
		
		<a data-name="clear-button" href="#" class="acf-icon acf-icon-cancel grey acf-soh-target"></a>
		
	</div>
	<div class="canvas">
		
		<div class="canvas-loading">
			<i class="acf-loading"></i>
		</div>
		
		<div class="canvas-error">
			<p><strong><?php _e("Error", 'acf'); ?></strong>. <?php _e("No embed found for the given URL", 'acf'); ?></p>
		</div>
		
		<div class="canvas-media" data-name="value-embed">
			<?php if( !empty( $field['value'] ) ): ?>
				<?php echo $this->wp_oembed_get($field['value'], $field['width'], $field['height']); ?>
			<?php endif; ?>
		</div>
		
		<i class="acf-icon acf-icon-picture hide-if-value"></i>
		
	</div>
	
</div>
<?php
 } function render_field_settings( $field ) { acf_render_field_setting( $field, array( 'label' => __('Embed Size','acf'), 'type' => 'text', 'name' => 'width', 'prepend' => __('Width', 'acf'), 'append' => 'px', 'placeholder' => $this->default_values['width'] )); acf_render_field_setting( $field, array( 'label' => __('Embed Size','acf'), 'type' => 'text', 'name' => 'height', 'prepend' => __('Height', 'acf'), 'append' => 'px', 'placeholder' => $this->default_values['height'], 'wrapper' => array( 'data-append' => 'width' ) )); } function format_value( $value, $post_id, $field ) { if( empty($value) ) { return $value; } $value = $this->wp_oembed_get($value, $field['width'], $field['height']); return $value; } } new acf_field_oembed(); endif; ?>
