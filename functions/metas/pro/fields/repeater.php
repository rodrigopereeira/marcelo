<?php
 if( ! class_exists('acf_field_repeater') ) : class acf_field_repeater extends acf_field { function __construct() { $this->name = 'repeater'; $this->label = __("Repeater",'acf'); $this->category = 'layout'; $this->defaults = array( 'sub_fields' => array(), 'min' => 0, 'max' => 0, 'layout' => 'table', 'button_label' => __("Add Row",'acf'), ); $this->l10n = array( 'min' => __("Minimum rows reached ({min} rows)",'acf'), 'max' => __("Maximum rows reached ({max} rows)",'acf'), ); parent::__construct(); } function load_field( $field ) { $field['sub_fields'] = acf_get_fields( $field ); return $field; } function render_field( $field ) { if( empty($field['value']) ) { $field['value'] = array(); } $field['min'] = empty($field['min']) ? 0 : $field['min']; $field['max'] = empty($field['max']) ? 0 : $field['max']; $empty_row = array(); foreach( $field['sub_fields'] as $f ) { $empty_row[ $f['key'] ] = isset( $f['default_value'] ) ? $f['default_value'] : null; } if( $field['min'] ) { for( $i = 0; $i < $field['min']; $i++ ) { if( array_key_exists($i, $field['value']) ) { continue; } $field['value'][ $i ] = $empty_row; } } if( $field['max'] ) { for( $i = 0; $i < count($field['value']); $i++ ) { if( $i >= $field['max'] ) { unset( $field['value'][ $i ] ); } } } $field['value']['acfcloneindex'] = $empty_row; $show_order = true; $show_add = true; $show_remove = true; if( $field['max'] ) { if( $field['max'] == 1 ) { $show_order = false; } if( $field['max'] <= $field['min'] ) { $show_remove = false; $show_add = false; } } $el = 'td'; $before_fields = ''; $after_fields = ''; if( $field['layout'] == 'row' ) { $el = 'tr'; $before_fields = '<td class="acf-table-wrap"><table class="acf-table">'; $after_fields = '</table></td>'; } elseif( $field['layout'] == 'block' ) { $el = 'div'; $before_fields = '<td class="acf-fields">'; $after_fields = '</td>'; } acf_hidden_input(array( 'type' => 'hidden', 'name' => $field['name'], )); ?>
<div <?php acf_esc_attr_e(array( 'class' => 'acf-repeater', 'data-min' => $field['min'], 'data-max' => $field['max'] )); ?>>
<table <?php acf_esc_attr_e(array( 'class' => "acf-table acf-input-table {$field['layout']}-layout" )); ?>>
	
	<?php if( $field['layout'] == 'table' ): ?>
		<thead>
			<tr>
				<?php if( $show_order ): ?>
					<th class="order"><span class="order-spacer"></span></th>
				<?php endif; ?>
				
				<?php foreach( $field['sub_fields'] as $sub_field ): $atts = array( 'class' => "acf-th acf-th-{$sub_field['name']}", 'data-key' => $sub_field['key'], ); if( $sub_field['wrapper']['width'] ) { $atts['data-width'] = $sub_field['wrapper']['width']; } ?>
					
					<th <?php acf_esc_attr_e( $atts ); ?>>
						<?php acf_the_field_label( $sub_field ); ?>
						<?php if( $sub_field['instructions'] ): ?>
							<p class="description"><?php echo $sub_field['instructions']; ?></p>
						<?php endif; ?>
					</th>
					
				<?php endforeach; ?>

				<?php if( $show_remove ): ?>
					<th class="remove"><span class="remove-spacer"></span></th>
				<?php endif; ?>
			</tr>
		</thead>
	<?php endif; ?>
	
	<tbody>
		<?php foreach( $field['value'] as $i => $row ): ?>
			<tr class="acf-row<?php echo ($i === 'acfcloneindex') ? ' acf-clone' : ''; ?>">
				
				<?php if( $show_order ): ?>
					<td class="order" title="<?php _e('Drag to reorder','acf'); ?>"><?php echo intval($i) + 1; ?></td>
				<?php endif; ?>
				
				<?php echo $before_fields; ?>
				
				<?php foreach( $field['sub_fields'] as $sub_field ): if( $i !== 'acfcloneindex' ) { $sub_field['conditional_logic'] = 0; } if( isset($row[ $sub_field['key'] ]) ) { $sub_field['value'] = $row[ $sub_field['key'] ]; } elseif( isset($sub_field['default_value']) ) { $sub_field['value'] = $sub_field['default_value']; } $sub_field['prefix'] = "{$field['name']}[{$i}]"; acf_render_field_wrap( $sub_field, $el ); ?>
					
				<?php endforeach; ?>
				
				<?php echo $after_fields; ?>
				
				<?php if( $show_remove ): ?>
					<td class="remove">
						<a class="acf-icon acf-icon-plus small acf-repeater-add-row" href="#" data-before="1" title="<?php _e('Add row','acf'); ?>"></a>
						<a class="acf-icon acf-icon-minus small acf-repeater-remove-row" href="#" title="<?php _e('Remove row','acf'); ?>"></a>
					</td>
				<?php endif; ?>
				
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php if( $show_add ): ?>
	
	<ul class="acf-hl acf-clearfix">
		<li class="acf-fr">
			<a href="#" class="acf-button blue acf-repeater-add-row"><?php echo $field['button_label']; ?></a>
		</li>
	</ul>
			
<?php endif; ?>
</div>
<?php
 } function render_field_settings( $field ) { $args = array( 'fields' => $field['sub_fields'], 'layout' => $field['layout'], 'parent' => $field['ID'] ); ?><tr class="acf-field" data-setting="repeater" data-name="sub_fields">
			<td class="acf-label">
				<label><?php _e("Sub Fields",'acf'); ?></label>
				<p class="description"></p>		
			</td>
			<td class="acf-input">
				<?php  acf_get_view('field-group-fields', $args); ?>
			</td>
		</tr>
		<?php
 $field['min'] = empty($field['min']) ? '' : $field['min']; $field['max'] = empty($field['max']) ? '' : $field['max']; acf_render_field_setting( $field, array( 'label' => __('Minimum Rows','acf'), 'instructions' => '', 'type' => 'number', 'name' => 'min', 'placeholder' => '0', )); acf_render_field_setting( $field, array( 'label' => __('Maximum Rows','acf'), 'instructions' => '', 'type' => 'number', 'name' => 'max', 'placeholder' => '0', )); acf_render_field_setting( $field, array( 'label' => __('Layout','acf'), 'instructions' => '', 'class' => 'acf-repeater-layout', 'type' => 'radio', 'name' => 'layout', 'layout' => 'horizontal', 'choices' => array( 'table' => __('Table','acf'), 'block' => __('Block','acf'), 'row' => __('Row','acf') ) )); acf_render_field_setting( $field, array( 'label' => __('Button Label','acf'), 'instructions' => '', 'type' => 'text', 'name' => 'button_label', )); } function load_value( $value, $post_id, $field ) { if( empty($value) || empty($field['sub_fields']) ) { return $value; } $value = intval( $value ); $rows = array(); if( $value > 0 ) { for( $i = 0; $i < $value; $i++ ) { $rows[ $i ] = array(); foreach( array_keys($field['sub_fields']) as $j ) { $sub_field = $field['sub_fields'][ $j ]; $sub_field['name'] = "{$field['name']}_{$i}_{$sub_field['name']}"; $sub_value = acf_get_value( $post_id, $sub_field ); $rows[ $i ][ $sub_field['key'] ] = $sub_value; } } } return $rows; } function format_value( $value, $post_id, $field ) { if( empty($value) || empty($field['sub_fields']) ) { return $value; } foreach( array_keys($value) as $i ) { foreach( array_keys($field['sub_fields']) as $j ) { $sub_field = $field['sub_fields'][ $j ]; $sub_value = acf_extract_var( $value[ $i ], $sub_field['key'] ); $sub_value = acf_format_value( $sub_value, $post_id, $sub_field ); $value[ $i ][ $sub_field['name'] ] = $sub_value; } } return $value; } function validate_value( $valid, $value, $field, $input ){ if( isset($value['acfcloneindex']) ) { unset($value['acfcloneindex']); } if( $field['required'] && empty($value) ) { $valid = false; } if( !empty($field['sub_fields']) && !empty($value) ) { $keys = array_keys($value); foreach( $keys as $i ) { foreach( $field['sub_fields'] as $sub_field ) { $k = $sub_field['key']; if( !isset($value[ $i ][ $k ]) ) { continue; } acf_validate_value( $value[ $i ][ $k ], $sub_field, "{$input}[{$i}][{$k}]" ); } } } return $valid; } function update_value( $value, $post_id, $field ) { $total = 0; if( isset($value['acfcloneindex']) ) { unset($value['acfcloneindex']); } if( !empty($value) ) { $i = -1; foreach( $value as $row ) { $i++; $total++; if( !$field['sub_fields'] ) { continue; } foreach( $field['sub_fields'] as $sub_field ) { $v = false; if( isset($row[ $sub_field['key'] ]) ) { $v = $row[ $sub_field['key'] ]; } elseif( isset($row[ $sub_field['name'] ]) ) { $v = $row[ $sub_field['name'] ]; } else { continue; } $sub_field['name'] = "{$field['name']}_{$i}_{$sub_field['name']}"; acf_update_value( $v, $post_id, $sub_field ); } } } $old_total = intval( acf_get_value( $post_id, $field, true ) ); if( $old_total > $total ) { for( $i = $total; $i < $old_total; $i++ ) { foreach( $field['sub_fields'] as $sub_field ) { $sub_field['name'] = "{$field['name']}_{$i}_{$sub_field['name']}"; acf_delete_value( $post_id, $sub_field ); } } } $value = $total; return $value; } function delete_value( $post_id, $key, $field ) { $old_total = intval( acf_get_value( $post_id, $field, true ) ); if( !$old_total || !$field['sub_fields'] ) { return; } for( $i = 0; $i < $old_total; $i++ ) { foreach( $field['sub_fields'] as $sub_field ) { $sub_field['name'] = "{$key}_{$i}_{$sub_field['name']}"; acf_delete_value( $post_id, $sub_field ); } } } function delete_field( $field ) { if( !empty($field['sub_fields']) ) { foreach( $field['sub_fields'] as $sub_field ) { acf_delete_field( $sub_field['ID'] ); } } } function update_field( $field ) { unset($field['sub_fields']); return $field; } function duplicate_field( $field ) { $sub_fields = acf_extract_var( $field, 'sub_fields' ); $field = acf_update_field( $field ); acf_duplicate_fields( $sub_fields, $field['ID'] ); return $field; } } new acf_field_repeater(); endif; ?>
