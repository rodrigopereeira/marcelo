<?php  add_filter('acf/settings/path', 'my_acf_settings_path'); function my_acf_settings_path( $path ) { $path = FOXTEMAS_METAS_PATH; return $path; } add_filter('acf/settings/dir', 'my_acf_settings_dir'); function my_acf_settings_dir( $dir ) { $dir = FOXTEMAS_METAS_DIR; return $dir; } add_filter('acf/settings/show_admin', '__return_false'); function foxtemas_relationship_query( $args, $field, $post ) { $args['posts_per_page'] = 25; return $args; } add_filter('acf/fields/relationship/query', 'foxtemas_relationship_query', 10, 3); function foxtemas_acf_admin_head() { ?>
	<style type="text/css">
		.acf-relationship .choices .list, .acf-relationship .list {
			height: 300px;
		}
	</style>
	<?php
} add_action('acf/input/admin_head', 'foxtemas_acf_admin_head'); add_filter('acf/settings/load_json', 'my_acf_json_load_point'); function my_acf_json_load_point( $paths ) { unset($paths[0]); $paths[] = FOXTEMAS_METAS_PATH . '/json'; return $paths; } ?>