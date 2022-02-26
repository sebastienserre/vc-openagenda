<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

add_action( 'admin_menu', 'p2p5_vc_openagenda_add_menu' );
function p2p5_vc_openagenda_add_menu() {
	add_options_page( __( 'OpenAgenda Settings', 'vc-openagenda' ), __( 'OpenAgenda Settings', 'vc-openagenda' ), 'manage_options', 'openagenda-settings', 'p2p5_vc_openagenda_options_page' );
}

function p2p5_vc_openagenda_options_page() {
	echo '<h3>' . get_admin_page_title() . '</h3>'; ?>
    <form method="post" action="options.php">
		<?php settings_fields( 'p2p5_openagenda_options' ) ?>
		<?php do_settings_sections( 'p2p5_openagenda_options' ) ?>
		<?php submit_button( __( 'Save' ) ); ?>
    </form>

	<?php
}

add_action('admin_init', 'p2p5_vc_openagenda_register_settings');
function p2p5_vc_openagenda_register_settings(){
	add_settings_section('p2p5_vc_openagenda_section', '', '', 'p2p5_openagenda_options');
	register_setting('p2p5_openagenda_options', 'openagenda_api');
	register_setting('p2p5_openagenda_options', 'openagenda_uid');
	add_settings_field('openagenda_api', __('API Openagenda','vc-openagenda'), 'p2p5_vc_openagenda_api', 'p2p5_openagenda_options', 'p2p5_vc_openagenda_section');
	add_settings_field('openagenda_uid', __('Agenda UID','vc-openagenda'), 'p2p5_vc_openagenda_uid', 'p2p5_openagenda_options', 'p2p5_vc_openagenda_section');

}


function p2p5_vc_openagenda_api(){
	?>
    <input type="text" name="openagenda_api" value="<?php echo get_option('openagenda_api')?>"/>
    <p><?php _e('Create an account on OpenAgenda, and go to your setting page to get your API key.','vc-openagenda') ?></p>
	<?php
}

function p2p5_vc_openagenda_uid(){
	?>
    <input type="text" name="openagenda_uid" value="<?php echo get_option('openagenda_uid')?>"/>
    <p><?php _e('Find your UID key in the bottom right of your Openagenda page.','vc-openagenda') ?></p>
	<?php
}