<?php
 if ( ! class_exists( 'Redux_Customizer_Control' ) ) { class Redux_Customizer_Control extends WP_Customize_Control { public function render() { $this->redux_id = str_replace( 'customize-control-', '', 'customize-control-' . str_replace( '[', '-', str_replace( ']', '', $this->id ) ) ); $class = 'customize-control redux-group-tab redux-field customize-control-' . $this->type; ?>
                <li id="<?php echo esc_attr( $this->redux_id ); ?>" class="<?php echo esc_attr( $class ); ?>">
                    <input type="hidden" data-id="<?php echo $this->id; ?>" class="redux-customizer-input"
                        id="customizer_control_id_<?php echo $this->redux_id; ?>" <?php echo $this->link() ?>
                        value=""/>
                    <?php $this->render_content(); ?>
                </li>
                <?php
 } public function render_content() { do_action( 'redux/advanced_customizer/control/render/' . $this->redux_id, $this ); } public function label() { echo $this->label; } public function description() { if ( ! empty( $this->description ) ) { echo '<span class="description customize-control-description">' . $this->description . '</span>'; } } public function title() { echo '<span class="customize-control-title">'; $this->label(); $this->description(); echo '</span>'; } } } 