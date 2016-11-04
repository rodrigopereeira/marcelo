<?php
 if ( ! defined( 'ABSPATH' ) ) { exit; } if ( ! class_exists( 'ReduxFramework_info' ) ) { class ReduxFramework_info { function __construct( $field = array(), $value = '', $parent ) { $this->parent = $parent; $this->field = $field; $this->value = $value; } public function render() { $defaults = array( 'title' => '', 'desc' => '', 'notice' => true, 'style' => '', 'color' => '', ); $this->field = wp_parse_args( $this->field, $defaults ); $styles = array( 'normal', 'info', 'warning', 'success', 'critical', 'custom' ); if (!in_array($this->field['style'], $styles)) { $this->field['style'] = 'normal'; } if ($this->field['style'] == "custom") { if (!empty($this->field['color']) ) { $this->field['color'] = "border-color:".$this->field['color'].';'; } else { $this->field['style'] = 'normal'; $this->field['color'] = ""; } } else { $this->field['color'] = ""; } if ( empty( $this->field['desc'] ) && ! empty( $this->field['default'] ) ) { $this->field['desc'] = $this->field['default']; unset( $this->field['default'] ); } if ( empty( $this->field['desc'] ) && ! empty( $this->field['subtitle'] ) ) { $this->field['desc'] = $this->field['subtitle']; unset( $this->field['subtitle'] ); } if ( empty( $this->field['desc'] ) ) { $this->field['desc'] = ""; } if ( empty( $this->field['raw_html'] ) ) { if ( $this->field['notice'] == true ) { $this->field['class'] .= ' redux-notice-field'; } else { $this->field['class'] .= ' redux-info-field'; } $this->field['style'] = 'redux-' . $this->field['style'] . ' '; } $indent = ( isset( $this->field['sectionIndent'] ) && $this->field['sectionIndent'] ) ? ' form-table-section-indented' : ''; echo '</td></tr></table><div id="info-' . $this->field['id'] . '" class="' . ( isset( $this->field['icon'] ) && ! empty( $this->field['icon'] ) && $this->field['icon'] !== true ? "hasIcon " : "") . $this->field['style'] . ' ' . $this->field['class'] . ' redux-field-' . $this->field['type'] . $indent . '"'.( !empty($this->field['color']) ? ' style="'.$this->field['color'].'"' : '' ).'>'; if ( ! empty( $this->field['raw_html'] ) && $this->field['raw_html'] ) { echo $this->field['desc']; } else { if ( isset( $this->field['title'] ) && ! empty( $this->field['title'] ) ) { $this->field['title'] = '<b>' . $this->field['title'] . '</b><br/>'; } if ( isset( $this->field['icon'] ) && ! empty( $this->field['icon'] ) && $this->field['icon'] !== true ) { echo '<p class="redux-info-icon"><i class="' . $this->field['icon'] . ' icon-large"></i></p>'; } if ( isset( $this->field['raw'] ) && ! empty( $this->field['raw'] ) ) { echo $this->field['raw']; } if ( ! empty( $this->field['title'] ) || ! empty( $this->field['desc'] ) ) { echo '<p class="redux-info-desc">' . $this->field['title'] . $this->field['desc'] . '</p>'; } } echo '</div><table class="form-table no-border" style="margin-top: 0;"><tbody><tr style="border-bottom:0; display:none;"><th style="padding-top:0;"></th><td style="padding-top:0;">'; } public function enqueue() { if ($this->parent->args['dev_mode']) { wp_enqueue_style( 'redux-field-info-css', ReduxFramework::$_url . 'inc/fields/info/field_info.css', array(), time(), 'all' ); } } } }