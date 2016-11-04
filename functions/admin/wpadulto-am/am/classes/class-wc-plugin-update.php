<?php
 if ( ! defined( 'ABSPATH' ) ) exit; class API_Manager_FoxTemas_Update_API_Check { protected static $_instance = null; public static function instance( $upgrade_url, $plugin_name, $product_id, $api_key, $activation_email, $renew_license_url, $instance, $domain, $software_version, $plugin_or_theme, $text_domain, $extra = '' ) { if ( is_null( self::$_instance ) ) { self::$_instance = new self( $upgrade_url, $plugin_name, $product_id, $api_key, $activation_email, $renew_license_url, $instance, $domain, $software_version, $plugin_or_theme, $text_domain, $extra ); } return self::$_instance; } private $upgrade_url; private $plugin_name; private $product_id; private $api_key; private $activation_email; private $renew_license_url; private $instance; private $domain; private $software_version; private $plugin_or_theme; private $text_domain; private $extra; public function __construct( $upgrade_url, $plugin_name, $product_id, $api_key, $activation_email, $renew_license_url, $instance, $domain, $software_version, $plugin_or_theme, $text_domain, $extra ) { $this->upgrade_url = $upgrade_url; $this->plugin_name = $plugin_name; $this->product_id = $product_id; $this->api_key = $api_key; $this->activation_email = $activation_email; $this->renew_license_url = $renew_license_url; $this->instance = $instance; $this->domain = $domain; $this->software_version = $software_version; $this->text_domain = $text_domain; $this->extra = $extra; $this->plugin_or_theme = $plugin_or_theme; if ( $this->plugin_or_theme == 'plugin' ) { add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'update_check' ) ); add_filter( 'plugins_api', array( $this, 'request' ), 10, 3 ); } else if ( $this->plugin_or_theme == 'theme' ) { add_filter( 'pre_set_site_transient_update_themes', array( $this, 'update_check' ) ); } } private function create_upgrade_api_url( $args ) { $upgrade_url = add_query_arg( 'wc-api', 'upgrade-api', $this->upgrade_url ); return $upgrade_url . '&' . http_build_query( $args ); } public function update_check( $transient ) { if( empty( $transient->checked ) ) { return $transient; } $args = array( 'request' => 'pluginupdatecheck', 'plugin_name' => $this->plugin_name, 'version' => $this->software_version, 'product_id' => $this->product_id, 'api_key' => $this->api_key, 'activation_email' => $this->activation_email, 'instance' => $this->instance, 'domain' => $this->domain, 'software_version' => $this->software_version, 'extra' => $this->extra, ); $response = $this->plugin_information( $args ); $this->check_response_for_errors( $response ); if ( isset( $response ) && is_object( $response ) && $response !== false ) { $new_ver = (string)$response->new_version; $curr_ver = (string)$this->software_version; } if ( isset( $new_ver ) && isset( $curr_ver ) ) { if( $response !== false && version_compare( $new_ver, $curr_ver, '>' ) ) { if ( $this->plugin_or_theme == 'plugin' ) { $transient->response[$this->plugin_name] = $response; } else if ( $this->plugin_or_theme == 'theme' ) { $transient->response[$this->plugin_name]['new_version'] = $response->new_version; $transient->response[$this->plugin_name]['url'] = $response->url; $transient->response[$this->plugin_name]['package'] = $response->package; } } } return $transient; } public function plugin_information( $args ) { $target_url = $this->create_upgrade_api_url( $args ); $request = wp_remote_get( $target_url ); if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) { return false; } $response = unserialize( wp_remote_retrieve_body( $request ) ); if( is_object( $response ) ) { return $response; } else { return false; } } public function request( $false, $action, $args ) { if ( $this->plugin_or_theme == 'plugin' ) { $version = get_site_transient( 'update_plugins' ); } else if ( $this->plugin_or_theme == 'theme' ) { $version = get_site_transient( 'update_themes' ); } if ( isset( $args->slug ) ) { if( $args->slug != $this->plugin_name ) { return $false; } } else { return $false; } $args = array( 'request' => 'plugininformation', 'plugin_name' => $this->plugin_name, 'version' => $this->software_version, 'product_id' => $this->product_id, 'api_key' => $this->api_key, 'activation_email' => $this->activation_email, 'instance' => $this->instance, 'domain' => $this->domain, 'software_version' => $this->software_version, 'extra' => $this->extra, ); $response = $this->plugin_information( $args ); if ( isset( $response ) && is_object( $response ) && $response !== false ) { return $response; } } public function check_response_for_errors( $response ) { if ( ! empty( $response ) ) { if ( isset( $response->errors['no_key'] ) && $response->errors['no_key'] == 'no_key' && isset( $response->errors['no_subscription'] ) && $response->errors['no_subscription'] == 'no_subscription' ) { add_action('admin_notices', array( $this, 'no_key_error_notice') ); add_action('admin_notices', array( $this, 'no_subscription_error_notice') ); } else if ( isset( $response->errors['exp_license'] ) && $response->errors['exp_license'] == 'exp_license' ) { add_action('admin_notices', array( $this, 'expired_license_error_notice') ); } else if ( isset( $response->errors['hold_subscription'] ) && $response->errors['hold_subscription'] == 'hold_subscription' ) { add_action('admin_notices', array( $this, 'on_hold_subscription_error_notice') ); } else if ( isset( $response->errors['cancelled_subscription'] ) && $response->errors['cancelled_subscription'] == 'cancelled_subscription' ) { add_action('admin_notices', array( $this, 'cancelled_subscription_error_notice') ); } else if ( isset( $response->errors['exp_subscription'] ) && $response->errors['exp_subscription'] == 'exp_subscription' ) { add_action('admin_notices', array( $this, 'expired_subscription_error_notice') ); } else if ( isset( $response->errors['suspended_subscription'] ) && $response->errors['suspended_subscription'] == 'suspended_subscription' ) { add_action('admin_notices', array( $this, 'suspended_subscription_error_notice') ); } else if ( isset( $response->errors['pending_subscription'] ) && $response->errors['pending_subscription'] == 'pending_subscription' ) { add_action('admin_notices', array( $this, 'pending_subscription_error_notice') ); } else if ( isset( $response->errors['trash_subscription'] ) && $response->errors['trash_subscription'] == 'trash_subscription' ) { add_action('admin_notices', array( $this, 'trash_subscription_error_notice') ); } else if ( isset( $response->errors['no_subscription'] ) && $response->errors['no_subscription'] == 'no_subscription' ) { add_action('admin_notices', array( $this, 'no_subscription_error_notice') ); } else if ( isset( $response->errors['no_activation'] ) && $response->errors['no_activation'] == 'no_activation' ) { add_action('admin_notices', array( $this, 'no_activation_error_notice') ); } else if ( isset( $response->errors['no_key'] ) && $response->errors['no_key'] == 'no_key' ) { add_action('admin_notices', array( $this, 'no_key_error_notice') ); } else if ( isset( $response->errors['download_revoked'] ) && $response->errors['download_revoked'] == 'download_revoked' ) { add_action('admin_notices', array( $this, 'download_revoked_error_notice') ); } else if ( isset( $response->errors['switched_subscription'] ) && $response->errors['switched_subscription'] == 'switched_subscription' ) { add_action('admin_notices', array( $this, 'switched_subscription_error_notice') ); } } } public function expired_license_error_notice( $message ){ $plugins = get_plugins(); $plugin_name = isset( $plugins[$this->product_id] ) ? $plugins[$this->product_id]['Name'] : $this->product_id; echo sprintf( '<div id="message" class="error"><p>' . __( 'A licença do %s está expirada. Você pode reativar, ou comprar uma Licença através do <a href="%s" target="_blank">painel de sua conta</a>.', $this->text_domain ) . '</p></div>', $plugin_name, $this->renew_license_url ) ; } public function on_hold_subscription_error_notice( $message ){ $plugins = get_plugins(); $plugin_name = isset( $plugins[$this->product_id] ) ? $plugins[$this->product_id]['Name'] : $this->product_id; echo sprintf( '<div id="message" class="error"><p>' . __( 'A inscrição para $s está em espera. Você pode reativar sua inscrição através do <a href="%s" target="_blank">painel de sua conta</a>.', $this->text_domain ) . '</p></div>', $plugin_name, $this->renew_license_url ) ; } public function cancelled_subscription_error_notice( $message ){ $plugins = get_plugins(); $plugin_name = isset( $plugins[$this->product_id] ) ? $plugins[$this->product_id]['Name'] : $this->product_id; echo sprintf( '<div id="message" class="error"><p>' . __( 'Sua inscrição para %s foi cancelada. Você pode renovar sua inscrição através do <a href="%s" target="_blank">painel de sua conta</a>. Uma nova licença será enviada para seu e-mail após o pedido ser concluído.', $this->text_domain ) . '</p></div>', $plugin_name, $this->renew_license_url ) ; } public function expired_subscription_error_notice( $message ){ $plugins = get_plugins(); $plugin_name = isset( $plugins[$this->product_id] ) ? $plugins[$this->product_id]['Name'] : $this->product_id; echo sprintf( '<div id="message" class="error"><p>' . __( 'A inscrição para %s está expirada. Você pode reativar sua inscrição através do <a href="%s" target="_blank">painel de sua conta</a>.', $this->text_domain ) . '</p></div>', $plugin_name, $this->renew_license_url ) ; } public function suspended_subscription_error_notice( $message ){ $plugins = get_plugins(); $plugin_name = isset( $plugins[$this->product_id] ) ? $plugins[$this->product_id]['Name'] : $this->product_id; echo sprintf( '<div id="message" class="error"><p>' . __( 'Sua inscrição para %s foi suspensa. Você pode reativar sua inscrição através do <a href="%s" target="_blank">painel de sua conta</a>.', $this->text_domain ) . '</p></div>', $plugin_name, $this->renew_license_url ) ; } public function pending_subscription_error_notice( $message ){ $plugins = get_plugins(); $plugin_name = isset( $plugins[$this->product_id] ) ? $plugins[$this->product_id]['Name'] : $this->product_id; echo sprintf( '<div id="message" class="error"><p>' . __( 'Sua inscrição para %s está pendente. Você pode verificar o status de sua inscrição através do <a href="%s" target="_blank">painel de sua conta</a>.', $this->text_domain ) . '</p></div>', $plugin_name, $this->renew_license_url ) ; } public function trash_subscription_error_notice( $message ){ $plugins = get_plugins(); $plugin_name = isset( $plugins[$this->product_id] ) ? $plugins[$this->product_id]['Name'] : $this->product_id; echo sprintf( '<div id="message" class="error"><p>' . __( 'Sua inscrição para %s foi colocada no lixo e será deletada em breve. Você pode comprar uma nova inscrição através do <a href="%s" target="_blank">painel de sua conta</a>.', $this->text_domain ) . '</p></div>', $plugin_name, $this->renew_license_url ) ; } public function no_subscription_error_notice( $message ){ $plugins = get_plugins(); $plugin_name = isset( $plugins[$this->product_id] ) ? $plugins[$this->product_id]['Name'] : $this->product_id; echo sprintf( '<div id="message" class="error"><p>' . __( 'A inscrição para %s não pode ser encontrada. Você pode comprar uma nova inscrição através do <a href="%s" target="_blank">painel de sua conta</a>.', $this->text_domain ) . '</p></div>', $plugin_name, $this->renew_license_url ) ; } public function no_key_error_notice( $message ){ $plugins = get_plugins(); $plugin_name = isset( $plugins[$this->product_id] ) ? $plugins[$this->product_id]['Name'] : $this->product_id; echo sprintf( '<div id="message" class="error"><p>' . __( 'A licença para %s não pode ser encontrada. Talvez você tenha esquecido de inserir a licença no painel de licença do  %s, ou a chave foi desativada em sua conta. Você pode reativar ou comprar uma nova licença através do <a href="%s" target="_blank">painel de sua conta</a>.', $this->text_domain ) . '</p></div>', $plugin_name, $plugin_name, $this->renew_license_url ) ; } public function download_revoked_error_notice( $message ){ $plugins = get_plugins(); $plugin_name = isset( $plugins[$this->product_id] ) ? $plugins[$this->product_id]['Name'] : $this->product_id; echo sprintf( '<div id="message" class="error"><p>' . __( 'As permissões de download possívelmente foram revogadas, possivelmente uma licença ou inscrição está expirada. Você pode reativar ou comprar uma nova licença através do <a href="%s" target="_blank">painel de sua conta</a>.', $this->text_domain ) . '</p></div>', $plugin_name, $this->renew_license_url ) ; } public function no_activation_error_notice( $message ){ $plugins = get_plugins(); $plugin_name = isset( $plugins[$this->product_id] ) ? $plugins[$this->product_id]['Name'] : $this->product_id; echo sprintf( '<div id="message" class="error"><p>' . __( '%s não está ativo. Vá até o menu de ativação de licença, e entre com o número e o e-mail de licença para ativar o %s.', $this->text_domain ) . '</p></div>', $plugin_name, $plugin_name ) ; } public function switched_subscription_error_notice( $message ){ $plugins = get_plugins(); $plugin_name = isset( $plugins[$this->product_id] ) ? $plugins[$this->product_id]['Name'] : $this->product_id; echo sprintf( '<div id="message" class="error"><p>' . __( 'Você pode trocar a inscrição para %s, então você precisa inserir uma nova licença na página de ativar licença. A licença deve ter chegado em sua caixa de e-mail, se não chegou, você obte-la logando no <a href="%s" target="_blank">painel de sua conta</a>.', $this->text_domain ) . '</p></div>', $plugin_name, $this->renew_license_url ) ; } } 