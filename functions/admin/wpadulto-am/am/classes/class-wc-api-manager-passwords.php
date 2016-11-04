<?php
 if ( ! defined( 'ABSPATH' ) ) exit; class API_Manager_FoxTemas_Password_Management { private function rand( $min = 0, $max = 0 ) { global $rnd_value; if ( strlen($rnd_value) < 8 ) { if ( defined( 'WP_SETUP_CONFIG' ) ) static $seed = ''; else $seed = get_transient('random_seed'); $rnd_value = md5( uniqid(microtime() . mt_rand(), true ) . $seed ); $rnd_value .= sha1($rnd_value); $rnd_value .= sha1($rnd_value . $seed); $seed = md5($seed . $rnd_value); if ( ! defined( 'WP_SETUP_CONFIG' ) ) set_transient('random_seed', $seed); } $value = substr($rnd_value, 0, 8); $rnd_value = substr($rnd_value, 8); $value = abs(hexdec($value)); $max_random_number = 3000000000 === 2147483647 ? (float) "4294967295" : 4294967295; if ( $max != 0 ) $value = $min + ( $max - $min + 1 ) * $value / ( $max_random_number + 1 ); return abs(intval($value)); } public function generate_password( $length = 12, $special_chars = true, $extra_special_chars = false ) { $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; if ( $special_chars ) $chars .= '!@#$%^&*()'; if ( $extra_special_chars ) $chars .= '-_ []{}<>~`+=,.;:/?|'; $password = ''; for ( $i = 0; $i < $length; $i++ ) { $password .= substr($chars, self::rand(0, strlen($chars) - 1), 1); } return $password; } } 