<?php
 if ( ! defined( 'ABSPATH' ) ) { exit; } if ( ! class_exists( 'ReduxFramework_import_export' ) ) { class ReduxFramework_import_export extends ReduxFramework { function __construct( $field = array(), $value = '', $parent ) { $this->parent = $parent; $this->field = $field; $this->value = $value; $this->is_field = $this->parent->extensions['import_export']->is_field; $this->extension_dir = ReduxFramework::$_dir . 'inc/extensions/import_export/'; $this->extension_url = ReduxFramework::$_url . 'inc/extensions/import_export/'; $defaults = array( 'options' => array(), 'stylesheet' => '', 'output' => true, 'enqueue' => true, 'enqueue_frontend' => true ); $this->field = wp_parse_args( $this->field, $defaults ); } public function render() { $secret = md5( md5( AUTH_KEY . SECURE_AUTH_KEY ) . '-' . $this->parent->args['opt_name'] ); $defaults = array( 'full_width' => true, 'overflow' => 'inherit', ); $this->field = wp_parse_args( $this->field, $defaults ); $bDoClose = false; $id = $this->parent->args['opt_name'] . '-' . $this->field['id']; ?>
                    <h4><?php _e( 'Import Options', 'redux-framework' ); ?></h4>

                    <p><a href="javascript:void(0);" id="redux-import-code-button" class="button-secondary"><?php _e( 'Import from File', 'redux-framework' ); ?></a> <a href="javascript:void(0);" id="redux-import-link-button" class="button-secondary"><?php _e( 'Import from URL', 'redux-framework' ) ?></a></p>

                    <div id="redux-import-code-wrapper">
                        <p class="description" id="import-code-description"><?php echo esc_html( apply_filters( 'redux-import-file-description', __( 'Input your backup file below and hit Import to restore your sites options from a backup.', 'redux-framework' ) ) ); ?></p>
                        <?php ?>
                        <textarea id="import-code-value" name="<?php echo $this->parent->args['opt_name']; ?>[import_code]" class="large-text noUpdate" rows="2"></textarea>
                    </div>

                    <div id="redux-import-link-wrapper">
                        <p class="description" id="import-link-description"><?php echo esc_html( apply_filters( 'redux-import-link-description', __( 'Input the URL to another sites options set and hit Import to load the options from that site.', 'redux-framework' ) ) ); ?></p>
                        <?php ?>
                        <textarea class="large-text noUpdate" id="import-link-value" name="<?php echo $this->parent->args['opt_name'] ?>[import_link]" rows="2"></textarea>
                    </div>

                    <p id="redux-import-action"><input type="submit" id="redux-import" name="import" class="button-primary" value="<?php _e( 'Import', 'redux-framework' ) ?>">&nbsp;&nbsp;<span><?php echo esc_html( apply_filters( 'redux-import-warning', __( 'WARNING! This will overwrite all existing option values, please proceed with caution!', 'redux-framework' ) ) ) ?></span></p>

                    <div class="hr"/>
                    <div class="inner"><span>&nbsp;</span></div></div>
                    <h4><?php _e( 'Export Options', 'redux-framework' ) ?></h4>

                    <div class="redux-section-desc">
                        <p class="description"><?php echo esc_html( apply_filters( 'redux-backup-description', __( 'Here you can copy/download your current option settings. Keep this safe as you can use it as a backup should anything go wrong, or you can use it to restore your settings on this site (or any other site).', 'redux-framework' ) ) ) ?></p>
                    </div>
                <?php
 $link = esc_url( admin_url( 'admin-ajax.php?action=redux_download_options-' . $this->parent->args['opt_name'] . '&secret=' . $secret ) ); ?>
                    <p>
                        <a href="javascript:void(0);" id="redux-export-code-copy" class="button-secondary"><?php _e( 'Copy Data', 'redux-framework' ) ?></a>
                        <a href="<?php echo $link; ?>" id="redux-export-code-dl" class="button-primary"><?php _e( 'Download Data File', 'redux-framework' ) ?></a>
                        <a href="javascript:void(0);" id="redux-export-link" class="button-secondary"><?php _e( 'Copy Export URL', 'redux-framework' ) ?></a>
                    </p>

                    <p></p>
                    <textarea class="large-text noUpdate" id="redux-export-code" rows="2"></textarea>
                    <textarea class="large-text noUpdate" id="redux-export-link-value" data-url="<?php echo $link; ?>" rows="2"><?php echo $link; ?></textarea>

                <?php
 } public function enqueue() { wp_enqueue_script( 'redux-import-export', $this->extension_url . 'import_export/field_import_export' . Redux_Functions::isMin() . '.js', array( 'jquery' ), ReduxFramework_extension_import_export::$version, true ); wp_enqueue_style( 'redux-import-export', $this->extension_url . 'import_export/field_import_export.css', time(), true ); } public function output() { if ( $this->field['enqueue_frontend'] ) { } } } } 