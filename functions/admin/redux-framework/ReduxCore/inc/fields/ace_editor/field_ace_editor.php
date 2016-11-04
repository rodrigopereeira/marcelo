<?php
 if ( ! defined( 'ABSPATH' ) ) { exit; } if ( ! class_exists( 'ReduxFramework_ace_editor' ) ) { class ReduxFramework_ace_editor { function __construct( $field = array(), $value = '', $parent ) { $this->parent = $parent; $this->field = $field; $this->value = $value; if ( is_array( $this->value ) ) { $this->value = ''; } else { $this->value = trim( $this->value ); } if ( ! empty( $this->field['options'] ) ) { $this->field['args'] = $this->field['options']; unset( $this->field['options'] ); } } function render() { if ( ! isset( $this->field['mode'] ) ) { $this->field['mode'] = 'javascript'; } if ( ! isset( $this->field['theme'] ) ) { $this->field['theme'] = 'monokai'; } $params = array( 'minLines' => 10, 'maxLines' => 30, ); if ( isset( $this->field['args'] ) && ! empty( $this->field['args'] ) && is_array( $this->field['args'] ) ) { $params = wp_parse_args( $this->field['args'], $params ); } ?>
                <div class="ace-wrapper">
                    <input type="hidden" class="localize_data"
                        value="<?php echo htmlspecialchars( json_encode( $params ) ); ?>"/>
                <textarea name="<?php echo $this->field['name'] . $this->field['name_suffix']; ?>"
                    id="<?php echo $this->field['id']; ?>-textarea"
                    class="ace-editor hide <?php echo $this->field['class']; ?>"
                    data-editor="<?php echo $this->field['id']; ?>-editor"
                    data-mode="<?php echo $this->field['mode']; ?>"
                    data-theme="<?php echo $this->field['theme']; ?>">
                    <?php echo $this->value; ?>
                </textarea>
                <pre id="<?php echo $this->field['id']; ?>-editor"
                    class="ace-editor-area"><?php echo htmlspecialchars( $this->value ); ?></pre>
                </div>
            <?php
 } public function enqueue() { if ( $this->parent->args['dev_mode'] ) { if ( ! wp_style_is( 'redux-field-ace-editor-css' ) ) { wp_enqueue_style( 'redux-field-ace-editor-css', ReduxFramework::$_url . 'inc/fields/ace_editor/field_ace_editor.css', array(), time(), 'all' ); } } if ( ! wp_script_is( 'ace-editor-js' ) ) { Redux_CDN::enqueue_script( 'ace-editor-js', '//cdn.jsdelivr.net/ace/1.1.9/min/ace.js', array( 'jquery' ), '1.1.9', true ); } if ( ! wp_script_is( 'redux-field-ace-editor-js' ) ) { wp_enqueue_script( 'redux-field-ace-editor-js', ReduxFramework::$_url . 'inc/fields/ace_editor/field_ace_editor' . Redux_Functions::isMin() . '.js', array( 'jquery', 'ace-editor-js', 'redux-js' ), time(), true ); } } } }