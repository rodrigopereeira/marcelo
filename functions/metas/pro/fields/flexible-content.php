<?php
 if( ! class_exists('acf_field_flexible_content') ) : class acf_field_flexible_content extends acf_field { function __construct() { $this->name = 'flexible_content'; $this->label = __("Flexible Content",'acf'); $this->category = 'layout'; $this->defaults = array( 'layouts' => array(), 'min' => '', 'max' => '', 'button_label' => __("Add Row",'acf'), ); $this->l10n = array( 'layout' => __("layout", 'acf'), 'layouts' => __("layouts", 'acf'), 'remove' => __("remove {layout}?", 'acf'), 'min' => __("This field requires at least {min} {identifier}",'acf'), 'max' => __("This field has a limit of {max} {identifier}",'acf'), 'min_layout' => __("This field requires at least {min} {label} {identifier}",'acf'), 'max_layout' => __("Maximum {label} limit reached ({max} {identifier})",'acf'), 'available' => __("{available} {label} {identifier} available (max {max})",'acf'), 'required' => __("{required} {label} {identifier} required (min {min})",'acf'), ); parent::__construct(); } function get_valid_layout( $layout = array() ) { $layout = wp_parse_args($layout, array( 'key' => uniqid(), 'name' => '', 'label' => '', 'display' => 'block', 'sub_fields' => array(), 'min' => '', 'max' => '', )); return $layout; } function load_field( $field ) { if( empty($field['layouts']) ) { return $field; } $sub_fields = acf_get_fields($field); foreach( array_keys($field['layouts']) as $i ) { $layout = acf_extract_var( $field['layouts'], $i ); $layout = $this->get_valid_layout( $layout ); if( !empty($sub_fields) ) { foreach( array_keys($sub_fields) as $k ) { if( empty($sub_fields[ $k ]['parent_layout']) ) { $sub_fields[ $k ]['parent_layout'] = $layout['key']; } if( $sub_fields[ $k ]['parent_layout'] == $layout['key'] ) { $layout['sub_fields'][] = acf_extract_var( $sub_fields, $k ); } } } $field['layouts'][ $i ] = $layout; } return $field; } function render_field( $field ) { if( empty($field['button_label']) ) { $field['button_label'] = $this->defaults['button_label']; } $layouts = array(); foreach( $field['layouts'] as $k => $layout ) { $layouts[ $layout['name'] ] = acf_extract_var( $field['layouts'], $k ); } acf_hidden_input(array( 'type' => 'hidden', 'name' => $field['name'], )); $no_value_message = __('Click the "%s" button below to start creating your layout','acf'); $no_value_message = apply_filters('acf/fields/flexible_content/no_value_message', $no_value_message, $field); ?>
<div <?php acf_esc_attr_e(array( 'class' => 'acf-flexible-content', 'data-min' => $field['min'], 'data-max' => $field['max'] )); ?>>
	
	<div class="no-value-message" <?php if( $field['value'] ){ echo 'style="display:none;"'; } ?>>
		<?php printf( $no_value_message, $field['button_label'] ); ?>
	</div>
	
	<div class="clones">
		<?php foreach( $layouts as $layout ): ?>
			<?php $this->render_layout( $field, $layout, 'acfcloneindex', array() ); ?>
		<?php endforeach; ?>
	</div>
	<div class="values">
		<?php if( !empty($field['value']) ): ?>
			<?php foreach( $field['value'] as $i => $value ): ?>
				<?php  if( empty($layouts[ $value['acf_fc_layout'] ]) ) { continue; } $this->render_layout( $field, $layouts[ $value['acf_fc_layout'] ], $i, $value ); ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<ul class="acf-hl acf-clearfix">
		<li class="acf-fr">
			<a href="#" class="acf-button blue acf-fc-add"><?php echo $field['button_label']; ?></a>
		</li>
	</ul>
	
	<script type="text-html" class="tmpl-popup"><?php  ?><div class="acf-fc-popup">
			<ul>
				<?php foreach( $layouts as $layout ): $atts = array( 'data-layout' => $layout['name'], 'data-min' => $layout['min'], 'data-max' => $layout['max'], ); ?>
					<li>
						<a href="#" <?php acf_esc_attr_e( $atts ); ?>><?php echo $layout['label']; ?><span class="status"></span></a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="bit"></div>
			<a href="#" class="focus"></a>
		</div>
	</script>
	
</div>
<?php
 } function render_layout( $field, $layout, $i, $value ) { $order = 0; $layout_atts = array( 'class' => 'layout', 'data-layout' => $layout['name'], 'data-toggle' => 'open', ); $table_atts = array( 'class' => "acf-table acf-input-table {$layout['display']}-layout", ); if( !empty($_COOKIE[ "acf_collapsed_{$field['key']}" ]) ) { $collapsed = $_COOKIE[ "acf_collapsed_{$field['key']}" ]; $collapsed = explode('|', $collapsed); if( in_array($i, $collapsed) ) { $layout_atts['data-toggle'] = 'closed'; $table_atts['style'] = 'display:none;'; } } if( is_numeric($i) ) { $order = $i + 1; } else { $layout_atts['class'] .= ' acf-clone'; } $el = 'td'; $before_fields = ''; $after_fields = ''; if( $layout['display'] == 'row' ) { $el = 'tr'; $before_fields = '<td class="acf-table-wrap"><table class="acf-table">'; $after_fields = '</table></td>'; } elseif( $layout['display'] == 'block' ) { $el = 'div'; $before_fields = '<td class="acf-fields">'; $after_fields = '</td>'; } ?>
<div <?php acf_esc_attr_e($layout_atts); ?>>
			
	<div style="display:none">
		<?php acf_hidden_input(array( 'name' => "{$field['name']}[{$i}][acf_fc_layout]", 'value' => $layout['name'] )); ?>
	</div>
	
	<div class="acf-fc-layout-handle">
		<span class="fc-layout-order"><?php echo $order; ?></span> <?php echo $layout['label']; ?>
	</div>
	
	<ul class="acf-fc-layout-controlls acf-hl acf-clearfix">
		<li>
			<a class="acf-icon acf-icon-plus small acf-fc-add" href="#" data-before="1" title="<?php _e('Add layout','acf'); ?>"></a>
		</li>
		<li>
			<a class="acf-icon acf-icon-minus small acf-fc-remove" href="#" title="<?php _e('Remove layout','acf'); ?>"></a>
		</li>
	</ul>
	
<?php if( !empty($layout['sub_fields']) ): ?>
		
	<table <?php acf_esc_attr_e($table_atts); ?>>
		
		<?php if( $layout['display'] == 'table' ): ?>
		
			<thead>
				<tr>
				
					<?php foreach( $layout['sub_fields'] as $sub_field ): $atts = array( 'class' => "acf-th acf-th-{$sub_field['name']}", 'data-key' => $sub_field['key'], ); if( $sub_field['wrapper']['width'] ) { $atts['data-width'] = $sub_field['wrapper']['width']; } ?>
						
						<th <?php acf_esc_attr_e( $atts ); ?>>
							<?php acf_the_field_label( $sub_field ); ?>
							<?php if( $sub_field['instructions'] ): ?>
								<p class="description"><?php echo $sub_field['instructions']; ?></p>
							<?php endif; ?>
						</th>
						
					<?php endforeach; ?> 

				</tr>
			</thead>
			
		<?php endif; ?>
		
		<tbody>
			<tr>
			<?php
 echo $before_fields; foreach( $layout['sub_fields'] as $sub_field ) { if( $i !== 'acfcloneindex' ) { $sub_field['conditional_logic'] = 0; } if( isset($value[ $sub_field['key'] ]) ) { $sub_field['value'] = $value[ $sub_field['key'] ]; } elseif( isset($sub_field['default_value']) ) { $sub_field['value'] = $sub_field['default_value']; } $sub_field['prefix'] = "{$field['name']}[{$i}]"; acf_render_field_wrap( $sub_field, $el ); } echo $after_fields; ?>							
			</tr>
		</tbody>
		
	</table>

<?php endif; ?>

</div>
<?php
 } function render_field_settings( $field ) { if( empty($field['layouts']) ) { $field['layouts'] = array(); $field['layouts'][] = $this->get_valid_layout(); } foreach( $field['layouts'] as $layout ) { $layout = $this->get_valid_layout( $layout ); $layout_prefix = "{$field['prefix']}[layouts][{$layout['key']}]"; ?><tr class="acf-field" data-name="fc_layout" data-setting="flexible_content" data-key="<?php echo $layout['key']; ?>">
	<td class="acf-label">
		<label><?php _e("Layout",'acf'); ?></label>
		<p class="description acf-fl-actions">
			<a data-name="acf-fc-reorder" title="<?php _e("Reorder Layout",'acf'); ?>" ><?php _e("Reorder",'acf'); ?></a>
			<a data-name="acf-fc-delete" title="<?php _e("Delete Layout",'acf'); ?>" href="#"><?php _e("Delete",'acf'); ?></a>
			<a data-name="acf-fc-duplicate" title="<?php _e("Duplicate Layout",'acf'); ?>" href="#"><?php _e("Duplicate",'acf'); ?></a>
			<a data-name="acf-fc-add" title="<?php _e("Add New Layout",'acf'); ?>" href="#"><?php _e("Add New",'acf'); ?></a>
		</p>
	</td>
	<td class="acf-input">
		<div class="acf-hidden">
			<?php  acf_hidden_input(array( 'name' => "{$layout_prefix}[key]", 'data-name' => 'layout-key', 'value' => $layout['key'] )); ?>
		</div>
		<ul class="acf-hl acf-fc-meta">
			<li class="acf-fc-meta-label" style="float: none;">
				<?php  acf_render_field(array( 'type' => 'text', 'name' => 'label', 'prefix' => $layout_prefix, 'value' => $layout['label'], 'prepend' => __('Label','acf') )); ?>
			</li>
			<li class="acf-fc-meta-name" style="float: none;">
				<?php  acf_render_field(array( 'type' => 'text', 'name' => 'name', 'prefix' => $layout_prefix, 'value' => $layout['name'], 'prepend' => __('Name','acf') )); ?>
			</li>
			<li class="acf-fc-meta-display" style="width:33%; padding-right:15px;">
				<div class="acf-input-prepend">
					<?php _e('Display','acf'); ?>
				</div>
				<div class="acf-input-wrap select">
					<?php  acf_render_field(array( 'type' => 'select', 'name' => 'display', 'prefix' => $layout_prefix, 'value' => $layout['display'], 'choices' => array( 'table' => __('Table','acf'), 'block' => __('Block','acf'), 'row' => __('Row','acf') ), )); ?>
				</div>
			</li>
			<li class="acf-fc-meta-min" style="width:33%; padding-right:15px;">
				<?php
 acf_render_field(array( 'type' => 'text', 'name' => 'min', 'prefix' => $layout_prefix, 'value' => $layout['min'], 'prepend' => __('Min','acf') )); ?>
			</li>
			<li class="acf-fc-meta-max" style="float: none;">
				<?php  acf_render_field(array( 'type' => 'text', 'name' => 'max', 'prefix' => $layout_prefix, 'value' => $layout['max'], 'prepend' => __('Max','acf') )); ?>
			</li>
		</ul>
		<?php  $args = array( 'fields' => $layout['sub_fields'], 'layout' => $layout['display'], 'parent' => $field['ID'] ); acf_get_view('field-group-fields', $args); ?>
	</td>
</tr>
<?php
 } acf_render_field_setting( $field, array( 'label' => __('Button Label','acf'), 'instructions' => '', 'type' => 'text', 'name' => 'button_label', )); acf_render_field_setting( $field, array( 'label' => __('Minimum Layouts','acf'), 'instructions' => '', 'type' => 'number', 'name' => 'min', )); acf_render_field_setting( $field, array( 'label' => __('Maximum Layouts','acf'), 'instructions' => '', 'type' => 'number', 'name' => 'max', )); } function load_value( $value, $post_id, $field ) { if( empty($value) || empty($field['layouts']) ) { return $value; } $value = acf_get_array( $value ); $rows = array(); $layouts = array(); foreach( array_keys($field['layouts']) as $i ) { $layout = $field['layouts'][ $i ]; $layouts[ $layout['name'] ] = $layout['sub_fields']; } foreach( $value as $i => $l ) { $rows[ $i ] = array(); $rows[ $i ]['acf_fc_layout'] = $l; if( empty($layouts[ $l ]) ) { continue; } $layout = $layouts[ $l ]; foreach( array_keys($layout) as $j ) { $sub_field = $layout[ $j ]; $sub_field['name'] = "{$field['name']}_{$i}_{$sub_field['name']}"; $sub_value = acf_get_value( $post_id, $sub_field ); $rows[ $i ][ $sub_field['key'] ] = $sub_value; } } return $rows; } function format_value( $value, $post_id, $field ) { if( empty($value) || empty($field['layouts']) ) { return $value; } $layouts = array(); foreach( array_keys($field['layouts']) as $i ) { $layout = $field['layouts'][ $i ]; $layouts[ $layout['name'] ] = $layout['sub_fields']; } foreach( array_keys($value) as $i ) { $l = $value[ $i ]['acf_fc_layout']; if( empty($layouts[ $l ]) ) { continue; } $layout = $layouts[ $l ]; foreach( array_keys($layout) as $j ) { $sub_field = $layout[ $j ]; $sub_value = acf_extract_var( $value[ $i ], $sub_field['key'] ); $sub_value = acf_format_value( $sub_value, $post_id, $sub_field ); $value[ $i ][ $sub_field['name'] ] = $sub_value; } } return $value; } function validate_value( $valid, $value, $field, $input ){ if( isset($value['acfcloneindex']) ) { unset($value['acfcloneindex']); } if( $field['required'] && empty($value) ) { $valid = false; } $layouts = array(); foreach( array_keys($field['layouts']) as $i ) { $layout = acf_extract_var($field['layouts'], $i); $layouts[ $layout['name'] ] = $layout['sub_fields']; } if( !empty($value) ) { foreach( $value as $i => $row ) { $l = $row['acf_fc_layout']; if( !empty($layouts[ $l ]) ) { foreach( $layouts[ $l ] as $sub_field ) { $k = $sub_field['key']; if( ! isset($value[ $i ][ $k ]) ) { continue; } acf_validate_value( $value[ $i ][ $k ], $sub_field, "{$input}[{$i}][{$k}]" ); } } } } return $valid; } function update_value( $value, $post_id, $field ) { if( isset($value['acfcloneindex']) ) { unset($value['acfcloneindex']); } $order = array(); $layouts = array(); foreach( $field['layouts'] as $layout ) { $layouts[ $layout['name'] ] = $layout['sub_fields']; } if( !empty($value) ) { $i = -1; foreach( $value as $row ) { $i++; $l = $row['acf_fc_layout']; $order[] = $l; if( !empty($layouts[ $l ]) ) { foreach( $layouts[ $l ] as $sub_field ) { $v = false; if( isset($row[ $sub_field['key'] ]) ) { $v = $row[ $sub_field['key'] ]; } elseif( isset($row[ $sub_field['name'] ]) ) { $v = $row[ $sub_field['name'] ]; } else { continue; } $sub_field['name'] = "{$field['name']}_{$i}_{$sub_field['name']}"; acf_update_value( $v, $post_id, $sub_field ); } } } } $old_order = acf_get_value( $post_id, $field, true ); $old_count = empty($old_order) ? 0 : count($old_order); $new_count = empty($order) ? 0 : count($order); if( $old_count > $new_count ) { for( $i = $new_count; $i < $old_count; $i++ ) { $l = $old_order[ $i ]; if( !empty($layouts[ $l ]) ) { foreach( $layouts[ $l ] as $sub_field ) { $sub_field['name'] = "{$field['name']}_{$i}_{$sub_field['name']}"; acf_delete_value( $post_id, $sub_field ); } } } } if( empty($order) ) { $order = false; } return $order; } function update_field( $field ) { $layouts = acf_extract_var($field, 'layouts'); $field['layouts'] = array(); if( !empty($layouts) ) { foreach( $layouts as $layout ) { unset($layout['sub_fields']); $field['layouts'][] = $layout; } } return $field; } function delete_field( $field ) { if( !empty($field['layouts']) ) { foreach( $field['layouts'] as $layout ) { if( !empty($layout['sub_fields']) ) { foreach( $layout['sub_fields'] as $sub_field ) { acf_delete_field( $sub_field['ID'] ); } } } } } function duplicate_field( $field ) { $sub_fields = array(); if( !empty($field['layouts']) ) { foreach( $field['layouts'] as $layout ) { $extra = acf_extract_var( $layout, 'sub_fields' ); if( !empty($extra) ) { $sub_fields = array_merge($sub_fields, $extra); } } } $field = acf_update_field( $field ); acf_duplicate_fields( $sub_fields, $field['ID'] ); return $field; } } new acf_field_flexible_content(); endif; ?>
