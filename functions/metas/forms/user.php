<?php
 if( ! class_exists('acf_form_user') ) : class acf_form_user { var $form = '#createuser'; function __construct() { add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts')); add_action('login_form_register', array($this, 'admin_enqueue_scripts')); add_action('show_user_profile', array($this, 'edit_user')); add_action('edit_user_profile', array($this, 'edit_user')); add_action('user_new_form', array($this, 'user_new_form')); add_action('register_form', array($this, 'register_user')); add_action('user_register', array($this, 'save_user')); add_action('profile_update', array($this, 'save_user')); } function validate_page() { global $pagenow; if( in_array( $pagenow, array('profile.php', 'user-edit.php', 'user-new.php', 'wp-login.php') ) ) { return true; } return false; } function admin_enqueue_scripts() { if( ! $this->validate_page() ) { return; } acf_enqueue_scripts(); add_action('acf/input/admin_footer', array($this, 'admin_footer'), 10, 1); } function register_user() { $this->form = '#registerform'; $this->render( 0, 'register', 'div' ); } function edit_user( $user ) { $this->form = '#your-profile'; $this->render( $user->ID, 'edit', 'tr' ); } function user_new_form() { $this->form = '#createuser'; $this->render( 0, 'add', 'tr' ); } function render( $user_id, $user_form, $el = 'tr' ) { $post_id = "user_{$user_id}"; $show_title = true; if( $user_form == 'register' ) { $show_title = false; } $args = array( 'user_id' => 'new', 'user_form' => $user_form ); if( $user_id ) { $args['user_id'] = $user_id; } $field_groups = acf_get_field_groups( $args ); if( !empty($field_groups) ) { acf_form_data(array( 'post_id' => $post_id, 'nonce' => 'user' )); foreach( $field_groups as $field_group ) { $fields = acf_get_fields( $field_group ); ?>
				<?php if( $show_title && $field_group['style'] == 'default' ): ?>
					<h3><?php echo $field_group['title']; ?></h3>
				<?php endif; ?>
				
				<?php if( $el == 'tr' ): ?>
					<table class="form-table">
						<tbody>
				<?php endif; ?>
				
					<?php acf_render_fields( $post_id, $fields, $el, 'field' ); ?>
				
				<?php if( $el == 'tr' ): ?>
						</tbody>
					</table>
				<?php endif; ?>
				<?php  } } } function admin_footer() { ?>
<style type="text/css">

<?php if( is_admin() ): ?>

/* override for user css */
.acf-field input[type="text"],
.acf-field input[type="password"],
.acf-field input[type="number"],
.acf-field input[type="search"],
.acf-field input[type="email"],
.acf-field input[type="url"],
.acf-field select {
    width: 25em;
}

.acf-field textarea {
	width: 500px;
}


/* allow sub fields to display correctly */
.acf-field .acf-field input[type="text"],
.acf-field .acf-field input[type="password"],
.acf-field .acf-field input[type="number"],
.acf-field .acf-field input[type="search"],
.acf-field .acf-field input[type="email"],
.acf-field .acf-field input[type="url"],
.acf-field .acf-field textarea,
.acf-field .acf-field select {
    width: 100%;
}

<?php else: ?>

#registerform p.submit {
	text-align: right;
}

<?php endif; ?>

</style>
<script type="text/javascript">
(function($) {
	
	// vars
	var $spinner = $('<?php echo $this->form; ?> p.submit .spinner');
	
	
	// create spinner if not exists (may exist in future WP versions)
	if( !$spinner.exists() ) {
		
		// create spinner (use .acf-spinner becuase .spinner CSS not included on register page)
		$spinner = $('<span class="acf-spinner"></span>');
		
		
		// append
		$('<?php echo $this->form; ?> p.submit').append( $spinner );
		
	}
	
})(jQuery);	
</script>
<?php
 } function save_user( $user_id ) { if( ! acf_verify_nonce('user') ) { return $user_id; } if( acf_validate_save_post(true) ) { acf_save_post( "user_{$user_id}" ); } } } new acf_form_user(); endif; ?>
