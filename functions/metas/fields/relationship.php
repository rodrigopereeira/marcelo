<?php
 if( ! class_exists('acf_field_relationship') ) : class acf_field_relationship extends acf_field { function __construct() { $this->name = 'relationship'; $this->label = __("Relationship",'acf'); $this->category = 'relational'; $this->defaults = array( 'post_type' => array(), 'taxonomy' => array(), 'min' => 0, 'max' => 0, 'filters' => array('search', 'post_type', 'taxonomy'), 'elements' => array(), 'return_format' => 'object' ); $this->l10n = array( 'min' => __("Minimum values reached ( {min} values )",'acf'), 'max' => __("Maximum values reached ( {max} values )",'acf'), 'loading' => __('Loading','acf'), 'empty' => __('No matches found','acf'), ); add_action('wp_ajax_acf/fields/relationship/query', array($this, 'ajax_query')); add_action('wp_ajax_nopriv_acf/fields/relationship/query', array($this, 'ajax_query')); parent::__construct(); } function get_choices( $options = array() ) { $options = acf_parse_args($options, array( 'post_id' => 0, 's' => '', 'post_type' => '', 'taxonomy' => '', 'lang' => false, 'field_key' => '', 'paged' => 1 )); $r = array(); $args = array(); $args['posts_per_page'] = 20; $args['paged'] = $options['paged']; $field = acf_get_field( $options['field_key'] ); if( !$field ) { return false; } if( !empty($options['post_type']) ) { $args['post_type'] = acf_get_array( $options['post_type'] ); } elseif( !empty($field['post_type']) ) { $args['post_type'] = acf_get_array( $field['post_type'] ); } else { $args['post_type'] = acf_get_post_types(); } $taxonomies = array(); if( !empty($options['taxonomy']) ) { $term = acf_decode_taxonomy_term($options['taxonomy']); $args['tax_query'] = array( array( 'taxonomy' => $term['taxonomy'], 'field' => 'slug', 'terms' => $term['term'], ) ); } elseif( !empty($field['taxonomy']) ) { $taxonomies = acf_decode_taxonomy_terms( $field['taxonomy'] ); $args['tax_query'] = array(); foreach( $taxonomies as $taxonomy => $terms ) { $args['tax_query'][] = array( 'taxonomy' => $taxonomy, 'field' => 'slug', 'terms' => $terms, ); } } if( $options['s'] ) { $args['s'] = $options['s']; } $args = apply_filters('acf/fields/relationship/query', $args, $field, $options['post_id']); $args = apply_filters('acf/fields/relationship/query/name=' . $field['name'], $args, $field, $options['post_id'] ); $args = apply_filters('acf/fields/relationship/query/key=' . $field['key'], $args, $field, $options['post_id'] ); $groups = acf_get_grouped_posts( $args ); if( !empty($groups) ) { foreach( array_keys($groups) as $group_title ) { $posts = acf_extract_var( $groups, $group_title ); $titles = array(); $data = array( 'text' => $group_title, 'children' => array() ); foreach( array_keys($posts) as $post_id ) { $posts[ $post_id ] = $this->get_post_title( $posts[ $post_id ], $field, $options['post_id'] ); }; if( !empty($args['s']) ) { $posts = acf_order_by_search( $posts, $args['s'] ); } foreach( array_keys($posts) as $post_id ) { $data['children'][] = array( 'id' => $post_id, 'text' => $posts[ $post_id ] ); } $r[] = $data; } if( count($args['post_type']) == 1 ) { $r = $r[0]['children']; } } return $r; } function ajax_query() { if( !acf_verify_ajax() ) { die(); } $posts = $this->get_choices( $_POST ); if( !$posts ) { die(); } echo json_encode( $posts ); die(); } function get_post_title( $post, $field, $post_id = 0 ) { if( !$post_id ) { $post_id = acf_get_setting('form_data/post_id', get_the_ID()); } $title = acf_get_post_title( $post ); if( !empty($field['elements']) ) { if( in_array('featured_image', $field['elements']) ) { $image = ''; if( $post->post_type == 'attachment' ) { $image = wp_get_attachment_image( $post->ID, array(17, 17) ); } else { $image = get_the_post_thumbnail( $post->ID, array(17, 17) ); } $title = '<div class="thumbnail">' . $image . '</div>' . $title; } } $title = apply_filters('acf/fields/relationship/result', $title, $post, $field, $post_id); $title = apply_filters('acf/fields/relationship/result/name=' . $field['_name'], $title, $post, $field, $post_id); $title = apply_filters('acf/fields/relationship/result/key=' . $field['key'], $title, $post, $field, $post_id); return $title; } function render_field( $field ) { $values = array(); $atts = array( 'id' => $field['id'], 'class' => "acf-relationship {$field['class']}", 'data-min' => $field['min'], 'data-max' => $field['max'], 'data-s' => '', 'data-post_type' => '', 'data-taxonomy' => '', 'data-paged' => 1, ); if( defined('ICL_LANGUAGE_CODE') ) { $atts['data-lang'] = ICL_LANGUAGE_CODE; } $field['post_type'] = acf_get_array( $field['post_type'] ); $field['taxonomy'] = acf_get_array( $field['taxonomy'] ); $post_types = array(); if( !empty($field['post_type']) ) { $post_types = $field['post_type']; } else { $post_types = acf_get_post_types(); } $post_types = acf_get_pretty_post_types($post_types); $taxonomies = array(); if( !empty($field['taxonomy']) ) { $term_groups = acf_get_array( $field['taxonomy'] ); $term_groups = acf_decode_taxonomy_terms( $term_groups ); $taxonomies = array_keys($term_groups); } elseif( !empty($field['post_type']) ) { foreach( $field['post_type'] as $post_type ) { $post_taxonomies = get_object_taxonomies( $post_type ); if( empty($post_taxonomies) ) { continue; } foreach( $post_taxonomies as $post_taxonomy ) { if( !in_array($post_taxonomy, $taxonomies) ) { $taxonomies[] = $post_taxonomy; } } } } else { $taxonomies = acf_get_taxonomies(); } $term_groups = acf_get_taxonomy_terms( $taxonomies ); if( !empty($field['taxonomy']) ) { foreach( array_keys($term_groups) as $taxonomy ) { foreach( array_keys($term_groups[ $taxonomy ]) as $term ) { if( ! in_array($term, $field['taxonomy']) ) { unset($term_groups[ $taxonomy ][ $term ]); } } } } $width = array( 'search' => 0, 'post_type' => 0, 'taxonomy' => 0 ); if( !empty($field['filters']) ) { $width = array( 'search' => 50, 'post_type' => 25, 'taxonomy' => 25 ); foreach( array_keys($width) as $k ) { if( ! in_array($k, $field['filters']) ) { $width[ $k ] = 0; } } if( $width['search'] == 0 ) { $width['post_type'] = ( $width['post_type'] == 0 ) ? 0 : 50; $width['taxonomy'] = ( $width['taxonomy'] == 0 ) ? 0 : 50; } if( $width['post_type'] == 0 ) { $width['taxonomy'] = ( $width['taxonomy'] == 0 ) ? 0 : 50; } if( $width['taxonomy'] == 0 ) { $width['post_type'] = ( $width['post_type'] == 0 ) ? 0 : 50; } if( $width['post_type'] == 0 && $width['taxonomy'] == 0 ) { $width['search'] = ( $width['search'] == 0 ) ? 0 : 100; } } ?>
<div <?php acf_esc_attr_e($atts); ?>>
	
	<div class="acf-hidden">
		<input type="hidden" name="<?php echo $field['name']; ?>" value="" />
	</div>
	
	<?php if( $width['search'] > 0 || $width['post_type'] > 0 || $width['taxonomy'] > 0 ): ?>
	<div class="filters">
		
		<ul class="acf-hl">
		
			<?php if( $width['search'] > 0 ): ?>
			<li style="width:<?php echo $width['search']; ?>%;">
				<div class="inner">
				<input class="filter" data-filter="s" placeholder="<?php _e("Search...",'acf'); ?>" type="text" />
				</div>
			</li>
			<?php endif; ?>
			
			<?php if( $width['post_type'] > 0 ): ?>
			<li style="width:<?php echo $width['post_type']; ?>%;">
				<div class="inner">
				<select class="filter" data-filter="post_type">
					<option value=""><?php _e('Select post type','acf'); ?></option>
					<?php foreach( $post_types as $k => $v ): ?>
						<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
					<?php endforeach; ?>
				</select>
				</div>
			</li>
			<?php endif; ?>
			
			<?php if( $width['taxonomy'] > 0 ): ?>
			<li style="width:<?php echo $width['taxonomy']; ?>%;">
				<div class="inner">
				<select class="filter" data-filter="taxonomy">
					<option value=""><?php _e('Select taxonomy','acf'); ?></option>
					<?php foreach( $term_groups as $k_opt => $v_opt ): ?>
						<optgroup label="<?php echo $k_opt; ?>">
							<?php foreach( $v_opt as $k => $v ): ?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
				</select>
				</div>
			</li>
			<?php endif; ?>
		</ul>
		
	</div>
	<?php endif; ?>
	
	<div class="selection acf-cf">
	
		<div class="choices">
		
			<ul class="acf-bl list"></ul>
			
		</div>
		
		<div class="values">
		
			<ul class="acf-bl list">
			
				<?php if( !empty($field['value']) ): $posts = acf_get_posts(array( 'post__in' => $field['value'], 'post_type' => $field['post_type'] )); if( !empty($posts) ): foreach( array_keys($posts) as $i ): $post = acf_extract_var( $posts, $i ); ?><li>
								<input type="hidden" name="<?php echo $field['name']; ?>[]" value="<?php echo $post->ID; ?>" />
								<span data-id="<?php echo $post->ID; ?>" class="acf-rel-item">
									<?php echo $this->get_post_title( $post, $field ); ?>
									<a href="#" class="acf-icon acf-icon-minus small dark" data-name="remove_item"></a>
								</span>
							</li><?php
 endforeach; endif; endif; ?>
				
			</ul>
			
			
			
		</div>
		
	</div>
	
</div>
		<?php
 } function render_field_settings( $field ) { $field['min'] = empty($field['min']) ? '' : $field['min']; $field['max'] = empty($field['max']) ? '' : $field['max']; acf_render_field_setting( $field, array( 'label' => __('Filter by Post Type','acf'), 'instructions' => '', 'type' => 'select', 'name' => 'post_type', 'choices' => acf_get_pretty_post_types(), 'multiple' => 1, 'ui' => 1, 'allow_null' => 1, 'placeholder' => __("All post types",'acf'), )); acf_render_field_setting( $field, array( 'label' => __('Filter by Taxonomy','acf'), 'instructions' => '', 'type' => 'select', 'name' => 'taxonomy', 'choices' => acf_get_taxonomy_terms(), 'multiple' => 1, 'ui' => 1, 'allow_null' => 1, 'placeholder' => __("All taxonomies",'acf'), )); acf_render_field_setting( $field, array( 'label' => __('Filters','acf'), 'instructions' => '', 'type' => 'checkbox', 'name' => 'filters', 'choices' => array( 'search' => __("Search",'acf'), 'post_type' => __("Post Type",'acf'), 'taxonomy' => __("Taxonomy",'acf'), ), )); acf_render_field_setting( $field, array( 'label' => __('Elements','acf'), 'instructions' => __('Selected elements will be displayed in each result','acf'), 'type' => 'checkbox', 'name' => 'elements', 'choices' => array( 'featured_image' => __("Featured Image",'acf'), ), )); acf_render_field_setting( $field, array( 'label' => __('Minimum posts','acf'), 'instructions' => '', 'type' => 'number', 'name' => 'min', )); acf_render_field_setting( $field, array( 'label' => __('Maximum posts','acf'), 'instructions' => '', 'type' => 'number', 'name' => 'max', )); acf_render_field_setting( $field, array( 'label' => __('Return Format','acf'), 'instructions' => '', 'type' => 'radio', 'name' => 'return_format', 'choices' => array( 'object' => __("Post Object",'acf'), 'id' => __("Post ID",'acf'), ), 'layout' => 'horizontal', )); } function format_value( $value, $post_id, $field ) { if( empty($value) ) { return $value; } $value = acf_get_array( $value ); $value = array_map('intval', $value); if( $field['return_format'] == 'object' ) { $value = acf_get_posts(array( 'post__in' => $value, 'post_type' => $field['post_type'] )); } return $value; } function update_value( $value, $post_id, $field ) { if( empty($value) ) { return $value; } $value = acf_get_array( $value ); foreach( $value as $k => $v ){ if( is_object($v) && isset($v->ID) ) { $value[ $k ] = $v->ID; } } $value = array_map('strval', $value); return $value; } } new acf_field_relationship(); endif; ?>
