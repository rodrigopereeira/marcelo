<?php
 class Redux_Customizer_Panel extends WP_Customize_Panel { public $type = 'redux'; public function __construct( $manager, $id, $args = array() ) { $keys = array_keys( get_object_vars( $this ) ); foreach ( $keys as $key ) { if ( isset( $args[ $key ] ) ) { $this->$key = $args[ $key ]; } } $this->manager = $manager; $this->id = $id; if ( empty( $this->active_callback ) ) { $this->active_callback = array( $this, 'active_callback' ); } self::$instance_count += 1; $this->instance_number = self::$instance_count; $this->sections = array(); if ( isset( $args['section'] ) ) { $this->section = $args['section']; $this->description = isset( $this->section['desc'] ) ? $this->section['desc'] : ''; $this->opt_name = isset( $args['opt_name'] ) ? $args['opt_name'] : ''; } } protected function render() { global $wp_version; $version = explode( '-', $wp_version ); if ( version_compare( $version[0], '4.3', '<' ) ) { $this->render_fallback(); } } protected function render_fallback() { $classes = 'accordion-section redux-main redux-panel control-section control-panel control-panel-' . $this->type; ?>
            <li id="accordion-panel-<?php echo esc_attr( $this->id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
                <h3 class="accordion-section-title" tabindex="0">
                    <?php
 echo wp_kses( $this->title, array( 'em' => array(), 'i' => array(), 'strong' => array(), 'span' => array( 'class' => array(), 'style' => array(), ), ) ); ?>
                    <span class="screen-reader-text"><?php _e( 'Press return or enter to open this panel', 'redux-framework' ); ?></span>
                </h3>
                <ul class="accordion-sub-container control-panel-content">
                    <table class="form-table">
                        <tbody><?php $this->render_content(); ?></tbody>
                    </table>
                </ul>
            </li>
            <?php
 } protected function render_content() { ?>
            <li class="panel-meta accordion-section redux-panel redux-panel-meta control-section<?php if ( empty( $this->description ) ) { echo ' cannot-expand'; } ?>">
                <div class="accordion-section-title" tabindex="0">
				<span class="preview-notice"><?php
 echo sprintf( __( 'You are customizing %s', 'redux-framework' ), '<strong class="panel-title">' . esc_html( $this->title ) . '</strong>' ); ?></span>
                </div>
                <?php if ( ! empty( $this->description ) ) : ?>
                    <div class="accordion-section-content description legacy">
                        <?php echo $this->description; ?>
                    </div>
                <?php endif; ?>
            </li>
            <?php
 } protected function content_template() { ?>
            <li class="panel-meta customize-info redux-panel accordion-section <# if ( ! data.description ) { #> cannot-expand<# } #>">
                <button class="customize-panel-back" tabindex="-1">
                    <span class="screen-reader-text"><?php _e( 'Back', 'redux-framework' ); ?></span></button>
                <div class="accordion-section-title">
				<span class="preview-notice"><?php
 echo sprintf( __( 'You are customizing %s', 'redux-framework' ), '<strong class="panel-title">{{ data.title }}</strong>' ); ?></span>
                    <# if ( data.description ) { #>
                        <button class="customize-help-toggle dashicons dashicons-editor-help" tabindex="0" aria-expanded="false">
                            <span class="screen-reader-text"><?php _e( 'Help', 'redux-framework' ); ?></span></button>
                        <# } #>
                </div>
                <# if ( data.description ) { #>
                    <div class="description customize-panel-description">
                        {{{ data.description }}}
                    </div>
                    <# } #>
            </li>
            <?php
 } } 