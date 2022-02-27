<?php
	/*
	* Plugin name: VC OpenAgenda
	* Author: Sébastien Serre
	* Author URI: https://thivinfo.com/
	* Description: This plugin allows you to display an openagenda in your WP via a Visual Composer element
	* Version: 1.1
	* License: GPLv2 or later
	* License URI: http://www.gnu.org/licenses/gpl-2.0.html
	* Text Domain: vc-openagenda
	* Domain Path: /languages
	*/

// don't load directly
	if ( ! defined( 'ABSPATH' ) ) {
		die( '-1' );
	}

	//define( 'P2P5_OPENAGENDA_URL', plugins_url( '/', __FILE__ ) );
	define('P2P5_OPENAGENDA_URL', plugin_dir_url( __FILE__ ) );
	define('P2P5_OPENAGENDA_PATH', plugin_dir_path( __FILE__ ) );
	define('P2P5_OPENAGENDA_DIR', untrailingslashit( P2P5_OPENAGENDA_PATH ));


	add_action( 'plugins_loaded', 'p2p5_vc_openagenda_load_files' );
	function p2p5_vc_openagenda_load_files() {
		include_once plugin_dir_path( __FILE__ ) . '/inc/class/class-openagenda-api.php';
		include_once plugin_dir_path( __FILE__ ) . '/admin/register_settings.php';
		include_once plugin_dir_path( __FILE__ ) . '/visual-composer/visual-slide.php';
		include_once plugin_dir_path( __FILE__ ) . '/visual-composer/event-openagenda.php';
		include_once plugin_dir_path( __FILE__ ) . '/visual-composer/single-event-openagenda.php';
		include_once plugin_dir_path( __FILE__ ) . '/visual-composer/category-openagenda.php';
		include_once plugin_dir_path( __FILE__ ) . '/visual-composer/tag-openagenda.php';
		include_once plugin_dir_path( __FILE__ ) . '/visual-composer/openagenda-main.php';
		include_once plugin_dir_path( __FILE__ ) . '/visual-composer/class-openagenda-search.php';
		include_once plugin_dir_path( __FILE__ ) . '/visual-composer/location-openagenda.php';
	}


	add_action( 'admin_notices', 'p2p5_vc_check_openagenda' );
	function p2p5_vc_check_openagenda() {
		if ( ! function_exists( 'vc_map' ) ) {

			echo '<div class="notice notice-error"><p>' . __( 'WPBakery Visual Composer is not activated. 5P2P VC OpenAgenda need it activated to work properly', 'p2p5-openagenda' ) . '</p></div>';

			deactivate_plugins( plugin_basename( __FILE__ ) );

		}
	}

	add_action( 'plugins_loaded', 'p2p5_vc_openagenda_load_textdomain' );
	function p2p5_vc_openagenda_load_textdomain() {
		load_plugin_textdomain( 'vc-openagenda', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	add_action( 'wp_enqueue_scripts', 'p2p5_vc_openagenda_enqueue' );
	function p2p5_vc_openagenda_enqueue() {
		wp_enqueue_script( 'slick-js', plugins_url( '/assets/slick/slick.min.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'openagenda-js', plugins_url( '/assets/js/p2p5_openagenda_main.js', __FILE__ ), array( 'slick-js' ) );
		wp_enqueue_style( 'slick-css', plugins_url( '/assets/slick/slick.css', __FILE__ ) );
		wp_enqueue_style( 'slick-css-theme', plugins_url( '/assets/slick/slick-theme.css', __FILE__ ), array( 'slick-css' ) );
		wp_enqueue_style( 'openagenda-css', plugins_url( '/assets/css/p2p5-openagenda.css', __FILE__ ) );
		wp_enqueue_style( 'marck-script-font', 'https://fonts.googleapis.com/css?family=Marck+Script' );
	}
