<?php
 if( ! class_exists('acf_field_tab') ) : class acf_field_tab extends acf_field { function __construct() { $this->name = 'tab'; $this->label = __("Tab",'acf'); $this->category = 'layout'; $this->defaults = array( 'value' => false, 'placement' => 'top', 'endpoint' => 0 ); parent::__construct(); } function render_field( $field ) { $atts = array( 'class' => 'acf-tab', 'data-placement' => $field['placement'], 'data-endpoint' => $field['endpoint'] ); ?>
		<div <?php acf_esc_attr_e( $atts ); ?>><?php echo $field['label']; ?></div>
		<?php
 } function render_field_settings( $field ) { ?><tr class="acf-field" data-setting="tab" data-name="warning">
			<td class="acf-label">
				<label><?php _e("Warning",'acf'); ?></label>
			</td>
			<td class="acf-input">
				<p style="margin:0;">
					<span class="acf-error-message" style="margin:0; padding:8px !important;">
					<?php _e("The tab field will display incorrectly when added to a Table style repeater field or flexible content field layout",'acf'); ?>
					</span>
				</p>
			</td>
		</tr>
		<?php
 acf_render_field_setting( $field, array( 'label' => __('Instructions','acf'), 'instructions' => '', 'type' => 'message', 'message' => __( 'Use "Tab Fields" to better organize your edit screen by grouping fields together.','acf') . '<br /><br />' . __( 'All fields following this "tab field" (or until another "tab field" is defined) will be grouped together using this field\'s label as the tab heading.','acf') )); acf_render_field_setting( $field, array( 'label' => __('Placement','acf'), 'type' => 'select', 'name' => 'placement', 'choices' => array( 'top' => __("Top aligned",'acf'), 'left' => __("Left Aligned",'acf'), ) )); acf_render_field_setting( $field, array( 'label' => __('End-point','acf'), 'instructions' => __('Use this field as an end-point and start a new group of tabs','acf'), 'type' => 'radio', 'name' => 'endpoint', 'choices' => array( 1 => __("Yes",'acf'), 0 => __("No",'acf'), ), 'layout' => 'horizontal', )); } } new acf_field_tab(); endif; ?>
