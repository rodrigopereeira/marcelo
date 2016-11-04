<?php
 $expanded = ( $this->parent->args['open_expanded'] ) ? ' fully-expanded' : '' . ( ! empty( $this->parent->args['class'] ) ? ' ' . esc_attr( $this->parent->args['class'] ) : '' ); $nonce = wp_create_nonce( "redux_ajax_nonce" ); ?>
<div class="redux-container<?php echo esc_attr( $expanded ); ?>">
    <?php $action = ( $this->parent->args['database'] == "network" && $this->parent->args['network_admin'] && is_network_admin() ? './edit.php?action=redux_' . $this->parent->args['opt_name'] : './options.php' ) ?>
    <form method="post" action="<?php echo $action; ?>" data-nonce="<?php echo $nonce; ?>" enctype="multipart/form-data"
        id="redux-form-wrapper">
        <?php ?>
        <input type="hidden" id="redux-compiler-hook"
            name="<?php echo $this->parent->args['opt_name']; ?>[compiler]"
            value=""/>
        <?php ?>
        <input type="hidden" id="currentSection"
            name="<?php echo $this->parent->args['opt_name']; ?>[redux-section]"
            value=""/>
        <?php ?>
        <?php if ( ! empty( $this->parent->no_panel ) ) : ?>
            <input type="hidden" name="<?php echo $this->parent->args['opt_name']; ?>[redux-no_panel]"
                value="<?php echo implode( '|', $this->parent->no_panel ); ?>"/>
        <?php endif; ?>
        <?php
 $this->init_settings_fields(); $this->parent->options['last_tab'] = ( isset( $_GET['tab'] ) && ! isset( $this->parent->transients['last_save_mode'] ) ) ? $_GET['tab'] : ''; ?>
        <?php ?>
        <input type="hidden" id="last_tab" name="<?php echo $this->parent->args['opt_name']; ?>[last_tab]"
            value="<?php echo esc_attr( $this->parent->options['last_tab'] ); ?>"/>

        <?php $this->get_template( 'content.tpl.php' ); ?>

    </form>
</div>

<?php if ( isset( $this->parent->args['footer_text'] ) ) : ?>
    <div id="redux-sub-footer"><?php echo wp_kses_post( $this->parent->args['footer_text'] ); ?></div>
<?php endif; ?>
