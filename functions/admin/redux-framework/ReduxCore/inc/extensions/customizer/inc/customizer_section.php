<?php
 class Redux_Customizer_Section extends WP_Customize_Section { public $type = 'redux'; public function __construct( $manager, $id, $args = array() ) { $keys = array_keys( get_object_vars( $this ) ); foreach ( $keys as $key ) { if ( isset( $args[ $key ] ) ) { $this->$key = $args[ $key ]; } } $this->manager = $manager; $this->id = $id; if ( empty( $this->active_callback ) ) { $this->active_callback = array( $this, 'active_callback' ); } self::$instance_count += 1; $this->instance_number = self::$instance_count; $this->controls = array(); if ( isset( $args['section'] ) ) { $this->section = $args['section']; $this->description = isset( $this->section['desc'] ) ? $this->section['desc'] : ''; $this->opt_name = isset( $args['opt_name'] ) ? $args['opt_name'] : ''; } } protected function render_template() { ?>
            <li id="accordion-section-{{ data.id }}" class="redux-section accordion-section control-section control-section-{{ data.type }}">
                <h3 class="accordion-section-title" tabindex="0">
                    {{ data.title }}
                    <span class="screen-reader-text"><?php _e( 'Press return or enter to open', 'redux-framework' ); ?></span>
                </h3>
                <ul class="accordion-section-content redux-main">

                    <li class="customize-section-description-container">
                        <div class="customize-section-title">
                            <button class="customize-section-back" tabindex="-1">
                                <span class="screen-reader-text"><?php _e( 'Back', 'redux-framework' ); ?></span>
                            </button>
                            <h3>
							<span class="customize-action">
								{{{ data.customizeAction }}}
							</span> {{ data.title }}
                            </h3>
                        </div>
                        <# if ( data.description ) { #>
                            <p class="description customize-section-description">{{{ data.description }}}</p>
                            <# } #>
                                <?php
 if ( isset( $this->opt_name ) && isset( $this->section ) ) { do_action( "redux/page/{$this->opt_name}/section/before", $this->section ); } ?>
                    </li>
                </ul>
            </li>
            <?php
 } protected function render_fallback() { $classes = 'accordion-section redux-section control-section control-section-' . $this->type; ?>
            <li id="accordion-section-<?php echo esc_attr( $this->id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
                <h3 class="accordion-section-title" tabindex="0">
                    <?php
 echo wp_kses( $this->title, array( 'em' => array(), 'i' => array(), 'strong' => array(), 'span' => array( 'class' => array(), 'style' => array(), ), ) ); ?>
                    <span class="screen-reader-text"><?php _e( 'Press return or enter to expand', 'redux-framework' ); ?></span>
                </h3>
                <ul class="accordion-section-content redux-main">
                    <?php
 if ( isset( $this->opt_name ) && isset( $this->section ) ) { do_action( "redux/page/{$this->opt_name}/section/before", $this->section ); } ?>
                    <?php if ( ! empty( $this->description ) ) : ?>
                        <li class="customize-section-description-container">
                            <p class="description customize-section-description legacy"><?php echo $this->description; ?></p>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php
 } protected function render() { global $wp_version; $version = explode( '-', $wp_version ); if ( version_compare( $version[0], '4.3', '<' ) ) { $this->render_fallback(); } } } 