<?php
 if ( ! defined( 'ABSPATH' ) ) { exit; } if ( ! class_exists( 'Redux_Admin_Notices' ) ) { class Redux_Admin_Notices { static public $_parent; public static function load() { add_action( 'wp_ajax_redux_hide_admin_notice', array( 'Redux_Admin_Notices', 'dismissAdminNoticeAJAX' ) ); } public static function adminNotices( $notices = array() ) { global $current_user, $pagenow; if ( ! empty( $notices ) ) { foreach ( $notices as $notice ) { $add_style = ''; if ( strpos( $notice['type'], 'redux-message' ) != false ) { $add_style = 'style="border-left: 4px solid ' . $notice['color'] . '!important;"'; } if ( true == $notice['dismiss'] ) { $userid = $current_user->ID; if ( ! get_user_meta( $userid, 'ignore_' . $notice['id'] ) ) { $pageName = ''; $curTab = ''; if ( $pagenow == 'admin.php' || $pagenow == 'themes.php' ) { $pageName = empty( $_GET['page'] ) ? '&amp;page=' . self::$_parent->args['page_slug'] : '&amp;page=' . $_GET['page']; $curTab = empty( $_GET['tab'] ) ? '&amp;tab=0' : '&amp;tab=' . $_GET['tab']; } global $wp_version; if ( version_compare( $wp_version, '4.2', '>' ) ) { $output = ""; $css_id = $notice['id'] . $pageName . $curTab; $css_class = $notice['type'] . 'redux-notice notice is-dismissible redux-notice'; $output .= "<div {$add_style} id='$css_id' class='$css_class'> \n"; $nonce = wp_create_nonce( $notice['id'] . $pageName . $curTab . 'nonce' ); $output .= "<input type='hidden' class='dismiss_data' id='" . $notice['id'] . $pageName . $curTab . "' value='{$nonce}'> \n"; $output .= "<p>{$notice['msg']}</p>"; $output .= "</div> \n"; echo $output; } else { echo '<div ' . $add_style . ' class="' . $notice['type'] . ' notice is-dismissable"><p>' . $notice['msg'] . '&nbsp;&nbsp;<a href="?dismiss=true&amp;id=' . $notice['id'] . $pageName . $curTab . '">' . __( 'Dismiss', 'redux-framework' ) . '</a>.</p></div>'; } } } else { echo '<div ' . $add_style . ' class="' . $notice['type'] . ' notice"><p>' . $notice['msg'] . '</a>.</p></div>'; } ?>
                        <script>
                            jQuery( document ).ready(
                                function( $ ) {
                                    $( '.redux-notice.is-dismissible' ).on(
                                        'click', '.notice-dismiss', function( event ) {
                                            var $data = $( this ).parent( '.redux-notice:first' ).find( '.dismiss_data' );
                                            $.post(
                                                ajaxurl, {
                                                    action: 'redux_hide_admin_notice',
                                                    id: $data.attr( 'id' ),
                                                    nonce: $data.val()
                                                }
                                            );
                                        }
                                    );
                                }
                            );
                        </script>
                        <?php
 } self::$_parent->admin_notices = array(); } } public static function dismissAdminNotice() { global $current_user; if ( isset( $_GET['dismiss'] ) && isset( $_GET['id'] ) ) { if ( 'true' == $_GET['dismiss'] || 'false' == $_GET['dismiss'] ) { $userid = $current_user->ID; $id = $_GET['id']; $val = $_GET['dismiss']; update_user_meta( $userid, 'ignore_' . $id, $val ); } } } public static function dismissAdminNoticeAJAX() { global $current_user; if ( ! wp_verify_nonce( $_POST['nonce'], $_POST['id'] . 'nonce' ) ) { die(0); } else { $userid = $current_user->ID; $id = $_POST['id']; update_user_meta( $userid, 'ignore_' . $id, true ); } } } Redux_Admin_Notices::load(); } 