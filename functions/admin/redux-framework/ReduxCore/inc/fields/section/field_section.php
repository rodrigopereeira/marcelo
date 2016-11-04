<?php
 if ( ! defined( 'ABSPATH' ) ) { exit; } if ( ! class_exists( 'ReduxFramework_section' ) ) { class ReduxFramework_section { public function __construct( $field = array(), $value = '', $parent ) { $this->parent = $parent; $this->field = $field; $this->value = $value; } public function render() { $defaults = array( 'indent' => '', 'style' => '', 'class' => '', 'title' => '', 'subtitle' => '', ); $this->field = wp_parse_args( $this->field, $defaults ); $guid = uniqid(); $add_class = ''; if ( isset( $this->field['indent'] ) && true === $this->field['indent'] ) { $add_class = ' form-table-section-indented'; } elseif( !isset( $this->field['indent'] ) || ( isset( $this->field['indent'] ) && false !== $this->field['indent'] ) ) { $add_class = " hide"; } echo '<input type="hidden" id="' . $this->field['id'] . '-marker"></td></tr></table>'; echo '<div id="section-' . $this->field['id'] . '" class="redux-section-field redux-field ' . $this->field['style'] . ' ' . $this->field['class'] . '">'; if ( ! empty( $this->field['title'] ) ) { echo '<h3>' . $this->field['title'] . '</h3>'; } if ( ! empty( $this->field['subtitle'] ) ) { echo '<div class="redux-section-desc">' . $this->field['subtitle'] . '</div>'; } echo '</div><table id="section-table-' . $this->field['id'] . '" data-id="' . $this->field['id'] . '" class="form-table form-table-section no-border' . $add_class . '"><tbody><tr><th></th><td id="' . $guid . '">'; ?>
                <script type="text/javascript">
                    jQuery( document ).ready(
                        function() {
                            jQuery( '#<?php echo $this->field['id']; ?>-marker' ).parents( 'tr:first' ).css( {display: 'none'} ).prev('tr' ).css('border-bottom','none');;
                            var group = jQuery( '#<?php echo $this->field['id']; ?>-marker' ).parents( '.redux-group-tab:first' );
                            if ( !group.hasClass( 'sectionsChecked' ) ) {
                                group.addClass( 'sectionsChecked' );
                                var test = group.find( '.redux-section-indent-start h3' );
                                jQuery.each(
                                    test, function( key, value ) {
                                        jQuery( value ).css( 'margin-top', '20px' )
                                    }
                                );
                                if ( group.find( 'h3:first' ).css( 'margin-top' ) == "20px" ) {
                                    group.find( 'h3:first' ).css( 'margin-top', '0' );
                                }
                            }
                        }
                    );
                </script>
            <?php
 } public function enqueue() { if ( $this->parent->args['dev_mode'] ) { wp_enqueue_style( 'redux-field-section-css', ReduxFramework::$_url . 'inc/fields/section/field_section.css', array(), time(), 'all' ); } } } }