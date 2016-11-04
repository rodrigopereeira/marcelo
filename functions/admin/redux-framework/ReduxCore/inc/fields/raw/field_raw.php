<?php
 if ( ! defined( 'ABSPATH' ) ) { exit; } if ( ! class_exists( 'ReduxFramework_raw' ) ) { class ReduxFramework_raw { function __construct( $field = array(), $value = '', $parent ) { $this->parent = $parent; $this->field = $field; $this->value = $value; } function render() { if ( ! empty( $this->field['include'] ) && file_exists( $this->field['include'] ) ) { require_once $this->field['include']; } if ( isset( $this->field['content_path'] ) && ! empty( $this->field['content_path'] ) && file_exists( $this->field['content_path'] ) ) { $this->field['content'] = $this->parent->filesystem->execute( 'get_contents', $this->field['content_path'] ); } if ( ! empty( $this->field['content'] ) && isset( $this->field['content'] ) ) { if ( isset( $this->field['markdown'] ) && $this->field['markdown'] == true ) { require_once dirname( __FILE__ ) . "/parsedown.php"; $Parsedown = new Parsedown(); echo $Parsedown->text( $this->field['content'] ); } else { echo $this->field['content']; } } do_action( 'redux-field-raw-' . $this->parent->args['opt_name'] . '-' . $this->field['id'] ); } } }