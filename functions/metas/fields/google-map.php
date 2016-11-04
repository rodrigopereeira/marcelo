<?php
 if( ! class_exists('acf_field_google_map') ) : class acf_field_google_map extends acf_field { function __construct() { $this->name = 'google_map'; $this->label = __("Google Map",'acf'); $this->category = 'jquery'; $this->defaults = array( 'height' => '', 'center_lat' => '', 'center_lng' => '', 'zoom' => '' ); $this->default_values = array( 'height' => '400', 'center_lat' => '-37.81411', 'center_lng' => '144.96328', 'zoom' => '14' ); $this->l10n = array( 'locating' => __("Locating",'acf'), 'browser_support' => __("Sorry, this browser does not support geolocation",'acf'), ); parent::__construct(); } function render_field( $field ) { if( empty($field['value']) ) { $field['value'] = array(); } $field['value'] = acf_parse_args($field['value'], array( 'address' => '', 'lat' => '', 'lng' => '' )); foreach( $this->default_values as $k => $v ) { if( empty($field[ $k ]) ) { $field[ $k ] = $v; } } $atts = array( 'id' => $field['id'], 'class' => $field['class'], 'data-id' => $field['id'] . '-' . uniqid(), 'data-lat' => $field['center_lat'], 'data-lng' => $field['center_lng'], 'data-zoom' => $field['zoom'] ); $atts['class'] .= ' acf-google-map'; if( $field['value']['address'] ) { $atts['class'] .= ' active'; } ?>
<div <?php acf_esc_attr_e($atts); ?>>
	
	<div class="acf-hidden">
		<?php foreach( $field['value'] as $k => $v ): ?>
			<input type="hidden" class="input-<?php echo $k; ?>" name="<?php echo esc_attr($field['name']); ?>[<?php echo $k; ?>]" value="<?php echo esc_attr( $v ); ?>" />
		<?php endforeach; ?>
	</div>
	
	<div class="title acf-soh">
		
		<div class="has-value">
			<a href="#" data-name="clear-location" class="acf-icon acf-icon-cancel grey acf-soh-target" title="<?php _e("Clear location", 'acf'); ?>"></a>
			<h4><?php echo $field['value']['address']; ?></h4>
		</div>
		
		<div class="no-value">
			<a href="#" data-name="find-location" class="acf-icon acf-icon-location grey acf-soh-target" title="<?php _e("Find current location", 'acf'); ?>"></a>
			<input type="text" placeholder="<?php _e("Search for address...",'acf'); ?>" class="search" />
		</div>
		
	</div>
	
	<div class="canvas" style="height: <?php echo $field['height']; ?>px">
		
	</div>
	
</div>
<?php
 } function render_field_settings( $field ) { acf_render_field_setting( $field, array( 'label' => __('Center','acf'), 'instructions' => __('Center the initial map','acf'), 'type' => 'text', 'name' => 'center_lat', 'prepend' => 'lat', 'placeholder' => $this->default_values['center_lat'] )); acf_render_field_setting( $field, array( 'label' => __('Center','acf'), 'instructions' => __('Center the initial map','acf'), 'type' => 'text', 'name' => 'center_lng', 'prepend' => 'lng', 'placeholder' => $this->default_values['center_lng'], 'wrapper' => array( 'data-append' => 'center_lat' ) )); acf_render_field_setting( $field, array( 'label' => __('Zoom','acf'), 'instructions' => __('Set the initial zoom level','acf'), 'type' => 'text', 'name' => 'zoom', 'placeholder' => $this->default_values['zoom'] )); acf_render_field_setting( $field, array( 'label' => __('Height','acf'), 'instructions' => __('Customise the map height','acf'), 'type' => 'text', 'name' => 'height', 'append' => 'px', 'placeholder' => $this->default_values['height'] )); } function validate_value( $valid, $value, $field, $input ){ if( ! $field['required'] ) { return $valid; } if( empty($value) || empty($value['lat']) || empty($value['lng']) ) { return false; } return $valid; } function update_value( $value, $post_id, $field ) { if( empty($value) || empty($value['lat']) || empty($value['lng']) ) { return false; } return $value; } } new acf_field_google_map(); endif; ?>
