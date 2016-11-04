<?php
 function acf_get_field_types() { return apply_filters('acf/get_field_types', array()); } function acf_get_field_type_label( $field_type ) { $field_types = acf_get_field_types(); foreach( $field_types as $category ) { if( isset( $category[ $field_type ] ) ) { return $category[ $field_type ]; } } return false; } function acf_field_type_exists( $field_type ) { $label = acf_get_field_type_label( $field_type ); if( !empty( $label ) ) { return true; } return false; } function acf_is_field_key( $key = '' ) { if( is_string($key) && substr($key, 0, 6) === 'field_' ) { return true; } if( acf_is_local_field($key) ) { return true; } return false; } function acf_get_valid_field_key( $key = '' ) { if( !acf_is_field_key($key) ) { if( !$key ) { $key = uniqid(); } $key = "field_{$key}"; } return $key; } function acf_get_valid_field( $field = false ) { if( !is_array($field) ) { $field = array(); } if( !empty($field['_valid']) ) { return $field; } $field = acf_parse_args($field, array( 'ID' => 0, 'key' => '', 'label' => '', 'name' => '', 'prefix' => '', 'type' => 'text', 'value' => null, 'menu_order' => 0, 'instructions' => '', 'required' => 0, 'id' => '', 'class' => '', 'conditional_logic' => 0, 'parent' => 0, 'wrapper' => array( 'width' => '', 'class' => '', 'id' => '' ), '_name' => '', '_input' => '', '_valid' => 0, )); $field['_name'] = $field['name']; foreach( array('label', 'instructions') as $s ) { $field[ $s ] = __($field[ $s ]); } $field = apply_filters( "acf/get_valid_field", $field ); $field = apply_filters( "acf/get_valid_field/type={$field['type']}", $field ); $field['_valid'] = 1; return $field; } function acf_prepare_field( $field ) { if( !$field['_input'] ) { $field['_input'] = $field['name']; if( $field['key'] ) { $field['_input'] = $field['key']; } if( $field['prefix'] ) { $field['_input'] = "{$field['prefix']}[{$field['_input']}]"; } } if( !$field['id'] ) { $field['id'] = str_replace(array('][', '[', ']'), array('-', '-', ''), $field['_input']); } $field = apply_filters( "acf/prepare_field", $field ); $field = apply_filters( "acf/prepare_field/type={$field['type']}", $field ); $field = apply_filters( "acf/prepare_field/name={$field['name']}", $field ); $field = apply_filters( "acf/prepare_field/key={$field['key']}", $field ); return $field; } function acf_is_sub_field( $field ) { if( acf_is_field_key($field['parent']) ) { return true; } if( acf_get_field($field['parent']) ) { return true; } return false; } function acf_get_field_label( $field ) { $label = $field['label']; if( $field['required'] ) { $label .= ' <span class="acf-required">*</span>'; } return $label; } function acf_the_field_label( $field ) { echo acf_get_field_label( $field ); } function acf_render_fields( $post_id = 0, $fields, $el = 'div', $instruction = 'label' ) { if( empty($fields) ) { return false; } $fields = array_filter($fields); foreach( $fields as $field ) { if( $field['value'] === null ) { $field['value'] = acf_get_value( $post_id, $field ); } $field['prefix'] = 'acf'; acf_render_field_wrap( $field, $el, $instruction ); } } function acf_render_field( $field = false ) { $field = acf_get_valid_field( $field ); $field = acf_prepare_field( $field ); $field['name'] = $field['_input']; do_action( "acf/render_field", $field ); do_action( "acf/render_field/type={$field['type']}", $field ); } function acf_render_field_wrap( $field, $el = 'div', $instruction = 'label' ) { $field = acf_get_valid_field( $field ); $field = acf_prepare_field( $field ); $elements = apply_filters('acf/render_field_wrap/elements', array( 'div' => 'div', 'tr' => 'td', 'ul' => 'li', 'ol' => 'li', 'dl' => 'dt', 'td' => 'div' )); if( !array_key_exists($el, $elements) ) { $el = 'div'; } $wrapper = array( 'id' => '', 'class' => 'acf-field', 'width' => '', 'style' => '', 'data-name' => $field['name'], 'data-type' => $field['type'], 'data-key' => '', ); if( $field['required'] ) { $wrapper['data-required'] = 1; } $wrapper['class'] .= " acf-field-{$field['type']}"; if( $field['key'] ) { $wrapper['class'] .= " acf-field-{$field['key']}"; $wrapper['data-key'] = $field['key']; } $wrapper['class'] = str_replace('_', '-', $wrapper['class']); $wrapper['class'] = str_replace('field-field-', 'field-', $wrapper['class']); $wrapper = acf_merge_atts( $wrapper, $field['wrapper'] ); $width = (int) acf_extract_var( $wrapper, 'width' ); if( $el == 'tr' || $el == 'td' ) { $width = 0; } elseif( $width > 0 && $width < 100 ) { $wrapper['data-width'] = $width; $wrapper['style'] .= " width:{$width}%;"; } foreach( $wrapper as $k => $v ) { if( $v == '' ) { unset($wrapper[$k]); } } $show_label = true; if( $el == 'td' ) { $show_label = false; } ?><<?php echo $el; ?> <?php echo acf_esc_attr($wrapper); ?>>
<?php if( $show_label ): ?>
	<<?php echo $elements[ $el ]; ?> class="acf-label">
		<label for="<?php echo $field['id']; ?>"><?php echo acf_get_field_label($field); ?></label>
<?php if( $instruction == 'label' && $field['instructions'] ): ?>
		<p class="description"><?php echo $field['instructions']; ?></p>
<?php endif; ?>
	</<?php echo $elements[ $el ]; ?>>
<?php endif; ?>
	<<?php echo $elements[ $el ]; ?> class="acf-input">
		<?php acf_render_field( $field ); ?>
		
<?php if( $instruction == 'field' && $field['instructions'] ): ?>
		<p class="description"><?php echo $field['instructions']; ?></p>
<?php endif; ?>
	</<?php echo $elements[ $el ]; ?>>
<?php if( !empty($field['conditional_logic'])): ?>
	<script type="text/javascript">
		if(typeof acf !== 'undefined'){ acf.conditional_logic.add( '<?php echo $field['key']; ?>', <?php echo json_encode($field['conditional_logic']); ?>); }
	</script>
<?php endif; ?>
</<?php echo $el; ?>>
<?php
 } function acf_render_field_setting( $field, $setting, $global = false ) { $setting = acf_get_valid_field( $setting ); if( !$global ) { $setting['wrapper']['data-setting'] = $field['type']; } $setting['prefix'] = $field['prefix']; if( isset($field[ $setting['name'] ]) ) { $setting['value'] = $field[ $setting['name'] ]; } acf_render_field_wrap( $setting, 'tr', 'label' ); } function acf_get_fields( $parent = false ) { if( !is_array($parent) ) { $parent = acf_get_field_group( $parent ); } if( !$parent ) { return false; } $fields = array(); if( acf_have_local_fields( $parent['key'] ) ) { $fields = acf_get_local_fields( $parent['key'] ); } else { $fields = acf_get_fields_by_id( $parent['ID'] ); } return apply_filters('acf/get_fields', $fields, $parent); } function acf_get_fields_by_id( $id = 0 ) { $fields = array(); if( empty($id) ) { return $fields; } $found = false; $cache = wp_cache_get( 'get_fields/parent=' . $id, 'acf', false, $found ); if( $found ) { return $cache; } $args = array( 'posts_per_page' => -1, 'post_type' => 'acf-field', 'orderby' => 'menu_order', 'order' => 'ASC', 'suppress_filters' => true, 'post_parent' => $id, 'post_status' => 'publish, trash', 'update_post_meta_cache' => false ); $posts = get_posts( $args ); if( $posts ) { foreach( $posts as $post ) { $fields[] = acf_get_field( $post->ID ); } } wp_cache_set( 'get_fields/parent=' . $id, $fields, 'acf' ); return $fields; } function acf_get_field( $selector = null, $db_only = false ) { $field = false; $type = 'ID'; if( is_numeric($selector) ) { } elseif( is_string($selector) ) { $type = acf_is_field_key($selector) ? 'key' : 'name'; } elseif( is_object($selector) ) { $selector = $selector->ID; } else { return false; } $cache_key = "get_field/{$type}={$selector}"; if( !$db_only ) { $found = false; $cache = wp_cache_get( $cache_key, 'acf', false, $found ); if( $found ) { return $cache; } } if( $type == 'ID' ) { $field = _acf_get_field_by_id( $selector, $db_only ); } elseif( $type == 'name' ) { $field = _acf_get_field_by_name( $selector, $db_only ); } else { $field = _acf_get_field_by_key( $selector, $db_only ); } if( $db_only ) { return $field; } if( $field ) { $field = apply_filters( "acf/load_field", $field); $field = apply_filters( "acf/load_field/type={$field['type']}", $field ); $field = apply_filters( "acf/load_field/name={$field['name']}", $field ); $field = apply_filters( "acf/load_field/key={$field['key']}", $field ); } wp_cache_set( $cache_key, $field, 'acf' ); return $field; } function _acf_get_field_by_id( $post_id = 0, $db_only = false ) { $post = get_post( $post_id ); if( empty($post) || $post->post_type != 'acf-field' ) { return false; } $field = maybe_unserialize( $post->post_content ); $field['ID'] = $post->ID; $field['key'] = $post->post_name; $field['label'] = $post->post_title; $field['name'] = $post->post_excerpt; $field['menu_order'] = $post->menu_order; $field['parent'] = $post->post_parent; if( !$db_only && acf_is_local_field($field['key']) ) { $backup = acf_extract_vars($field, array( 'ID', 'parent' )); $field = acf_get_local_field( $field['key'] ); $field = array_merge($field, $backup); } $field = acf_get_valid_field( $field ); return $field; } function _acf_get_field_by_key( $key = '', $db_only = false ) { if( !$db_only && acf_is_local_field( $key ) ) { $field = acf_get_local_field( $key ); $field = acf_get_valid_field( $field ); return $field; } $post_id = acf_get_field_id( $key ); if( !$post_id ) { return false; } return _acf_get_field_by_id( $post_id, $db_only ); } function _acf_get_field_by_name( $name = '', $db_only = false ) { $args = array( 'posts_per_page' => 1, 'post_type' => 'acf-field', 'orderby' => 'menu_order title', 'order' => 'ASC', 'suppress_filters' => false, 'acf_field_name' => $name ); $posts = get_posts( $args ); if( empty($posts) ) { return false; } return _acf_get_field_by_id( $posts[0]->ID, $db_only ); } function acf_maybe_get_field( $selector, $post_id = false, $strict = true ) { acf()->complete(); $field_name = false; $post_id = acf_get_valid_post_id( $post_id ); if( !acf_is_field_key($selector) ) { $field_name = $selector; $field_key = acf_get_field_reference( $selector, $post_id ); if( $field_key ) { $selector = $field_key; } elseif( $strict ) { return false; } } $field = acf_get_field( $selector ); if( !$field ) { return false; } if( $field_name ) { $field['name'] = $field_name; } return $field; } function acf_get_field_id( $key = '' ) { $args = array( 'posts_per_page' => 1, 'post_type' => 'acf-field', 'orderby' => 'menu_order title', 'order' => 'ASC', 'suppress_filters' => false, 'acf_field_key' => $key ); $posts = get_posts( $args ); if( empty($posts) ) { return 0; } return $posts[0]->ID; } function acf_update_field( $field = false, $specific = false ) { if( !is_array($field) ) { return false; } $field = acf_get_valid_field( $field ); $field = wp_unslash( $field ); if( !empty($field['conditional_logic']) ) { $groups = acf_extract_var( $field, 'conditional_logic' ); $groups = array_filter($groups); $groups = array_values($groups); foreach( array_keys($groups) as $i ) { $groups[ $i ] = array_filter($groups[ $i ]); $groups[ $i ] = array_values($groups[ $i ]); } $field['conditional_logic'] = $groups; } $field = apply_filters( "acf/update_field", $field); $field = apply_filters( "acf/update_field/type={$field['type']}", $field ); $field = apply_filters( "acf/update_field/name={$field['name']}", $field ); $field = apply_filters( "acf/update_field/key={$field['key']}", $field ); $data = $field; $extract = acf_extract_vars($data, array( 'ID', 'key', 'label', 'name', 'prefix', 'value', 'menu_order', 'id', 'class', 'parent', '_name', '_input', '_valid', )); $data = maybe_serialize( $data ); $save = array( 'ID' => $extract['ID'], 'post_status' => 'publish', 'post_type' => 'acf-field', 'post_title' => $extract['label'], 'post_name' => $extract['key'], 'post_excerpt' => $extract['name'], 'post_content' => $data, 'post_parent' => $extract['parent'], 'menu_order' => $extract['menu_order'], ); if( !empty($specific) ) { array_unshift( $specific, 'ID' ); foreach( $specific as $key ) { $_save[ $key ] = $save[ $key ]; } $save = $_save; unset($_save); } add_filter( 'wp_unique_post_slug', 'acf_update_field_wp_unique_post_slug', 100, 6 ); if( $field['ID'] ) { wp_update_post( $save ); } else { $field['ID'] = wp_insert_post( $save ); } wp_cache_delete( "get_field/ID={$field['ID']}", 'acf' ); wp_cache_delete( "get_field/key={$field['key']}", 'acf' ); wp_cache_delete( "get_fields/parent={$field['parent']}", 'acf' ); return $field; } function acf_update_field_wp_unique_post_slug( $slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug ) { if( $post_type == 'acf-field' ) { $slug = $original_slug; } return $slug; } function acf_duplicate_fields( $fields, $new_parent = 0 ) { if( empty($fields) ) { return; } foreach( $fields as $field ) { usleep(1); acf_update_setting( 'duplicate_key_' . $field['key'] , uniqid('field_') ); } foreach( $fields as $field ) { acf_duplicate_field( $field['ID'], $new_parent ); } } function acf_duplicate_field( $selector = 0, $new_parent = 0 ){ acf_disable_local(); $field = acf_get_field( $selector ); if( empty($field) ) { return false; } $field['ID'] = false; $field['key'] = acf_get_setting( 'duplicate_key_' . $field['key'] ); if( empty($field['key']) ) { $field['key'] = uniqid('field_'); } if( $new_parent ) { $field['parent'] = $new_parent; } if( !empty($field['conditional_logic']) ) { $groups = acf_extract_var( $field, 'conditional_logic' ); foreach( array_keys($groups) as $g ) { $group = acf_extract_var( $groups, $g ); if( empty($group) ) { continue; } foreach( array_keys($group) as $r ) { $rule = acf_extract_var( $group, $r ); $new_key = acf_get_setting( 'duplicate_key_' . $rule['field'] ); if( $new_key ) { $rule['field'] = $new_key; } $group[ $r ] = $rule; } $groups[ $g ] = $group; } $field['conditional_logic'] = $groups; } $field = apply_filters( "acf/duplicate_field", $field); $field = apply_filters( "acf/duplicate_field/type={$field['type']}", $field ); return acf_update_field( $field ); } function acf_delete_field( $selector = 0 ) { acf_disable_local(); $field = acf_get_field( $selector ); if( empty($field) ) { return false; } wp_delete_post( $field['ID'], true ); do_action( "acf/delete_field", $field); do_action( "acf/delete_field/type={$field['type']}", $field ); wp_cache_delete( "get_field/ID={$field['ID']}", 'acf' ); wp_cache_delete( "get_field/key={$field['key']}", 'acf' ); wp_cache_delete( "get_fields/parent={$field['parent']}", 'acf' ); return true; } function acf_trash_field( $selector = 0 ) { acf_disable_local(); $field = acf_get_field( $selector ); if( empty($field) ) { return false; } wp_trash_post( $field['ID'] ); do_action( 'acf/trash_field', $field ); return true; } function acf_untrash_field( $selector = 0 ) { acf_disable_local(); $field = acf_get_field( $selector ); if( empty($field) ) { return false; } wp_untrash_post( $field['ID'] ); do_action( 'acf/untrash_field', $field ); return true; } function acf_prepare_fields_for_export( $fields = false ) { if( empty($fields) ) { return $fields; } $keys = array_keys( $fields ); foreach( $keys as $key ) { $fields[ $key ] = acf_prepare_field_for_export( $fields[ $key ] ); } $fields = apply_filters('acf/prepare_fields_for_export', $fields); return $fields; } function acf_prepare_field_for_export( $field ) { $extract = acf_extract_vars($field, array( 'ID', 'prefix', 'value', 'menu_order', 'id', 'class', 'parent', '_name', '_input', '_valid', )); $field = apply_filters( "acf/prepare_field_for_export", $field ); return $field; } function acf_prepare_fields_for_import( $fields = false ) { if( empty($fields) ) { return $fields; } $fields = array_values($fields); $i = 0; while( $i < count($fields) ) { $field = acf_prepare_field_for_import( $fields[ $i ] ); if( !isset($field['key']) ) { $extra = $field; $field = array_shift($extra); $fields = array_merge($fields, $extra); } $fields[ $i ] = $field; $i++; } $fields = apply_filters('acf/prepare_fields_for_import', $fields); return $fields; } function acf_prepare_field_for_import( $field ) { $extract = acf_extract_vars($field, array( 'value', 'id', 'class', '_name', '_input', '_valid', )); $field = apply_filters( "acf/prepare_field_for_import", $field ); return $field; } function acf_get_sub_field( $selector, $field ) { if( $field['type'] == 'repeater' ) { $sub_fields = acf_extract_var( $field, 'sub_fields'); if( !empty($sub_fields) ) { foreach( $sub_fields as $sub_field ) { if( $sub_field['name'] == $selector || $sub_field['key'] == $selector ) { return $sub_field; } } } } elseif( $field['type'] == 'flexible_content' ) { $layouts = acf_extract_var( $field, 'layouts'); $current = get_row_layout(); if( !empty($layouts) ) { foreach( $layouts as $layout ) { if( $current && $current !== $layout['name'] ) { continue; } $sub_fields = acf_extract_var( $layout, 'sub_fields'); if( !empty($sub_fields) ) { foreach( $sub_fields as $sub_field ) { if( $sub_field['name'] == $selector || $sub_field['key'] == $selector ) { return $sub_field; } } } } } } return false; } ?>
