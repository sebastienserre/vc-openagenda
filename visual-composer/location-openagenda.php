<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

add_action( 'init', 'openagenda_location_init' );
function openagenda_location_init() {
	vc_map( array(
			'name'        => __( 'Location Openagenda', '5p2p-vc-openagenda' ),
			'base'        => 'p2p5-vc-openagenda-location',
			'description' => __( 'Filter by location from Openagenda', '5p2p-vc-openagenda' ),
			'category'    => __( '5 Pains & 2  Poissons', '5p2p-vc-openagenda' ),
			'icon'        => P2P5_OPENAGENDA_URL . '/assets/img/icon.jpg',
			'params'      => array(

				array(
					'type'        => 'textfield',
					'holder'      => 'a',
					'class'       => 'url-class',
					'heading'     => __( 'URL to Agenda', '5p2p-vc-openagenda' ),
					'param_name'  => 'agenda_url',
					'value'       => esc_url( site_url() ),
					'description' => __( 'URL to Agenda in OpenAgenda.', '5p2p-vc-openagenda' ),
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', '5p2p-vc-openagenda' ),
				),
				array(
					'type'        => 'vc_link',
					'holder'      => 'p',
					'class'       => 'title-class',
					'heading'     => __( 'Event Link', '5p2p-vc-openagenda' ),
					'param_name'  => 'event-link',
					'value'       => '',
					'description' => '',
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', '5p2p-vc-openagenda' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'p',
					'class'       => 'title-class',
					'heading'     => __( 'Event Link', '5p2p-vc-openagenda' ),
					'param_name'  => 'layout',
					'value'       => array(
						__( 'List', '5p2p-vc-openagenda' )   => 'list',
						__( 'Select', '5p2p-vc-openagenda' ) => 'select',

					),
					'description' => '',
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', '5p2p-vc-openagenda' ),
				),
			),
		)
	);
}

add_shortcode( 'p2p5-vc-openagenda-location', 'openagenda_location' );
function openagenda_location( $atts ) {
	/**
	 * Load JS to make a masonry Grid
	 */
	wp_enqueue_script( 'isotopeJS', P2P5_OPENAGENDA_URL . 'assets/js/isotope.pkgd.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'isotopeinit', P2P5_OPENAGENDA_URL . 'assets/js/isotope-init.js', array( 'isotopeJS' ) );

	$atts   = shortcode_atts( array(
		'agenda_url' => '',
		'event-link' => '',
		'layout'     => 'list',
	), $atts, 'p2p5-vc-openagenda-location' );
	$openwp = new OpenAgendaApi();
	$url    = $atts['agenda_url'];
	$events = $openwp->thfo_openwp_retrieve_data( $url, 200 );

	foreach ( $events['events'] as $event ) {
		$locations[ $event['locationUid'] ] = $event['locationName'];
	}

	$locations = array_unique( $locations );
	asort( $locations );

	if ( 'list' === $atts['layout'] ) {
		return openagenda_location_list_html( $locations, $atts );
	}

	if ( 'select' === $atts['layout'] ) {
		return openagenda_location_select_html( $locations, $atts );
	}


}

function openagenda_location_select_html( $locations, $atts ) {
	ob_start();
	?>
	<form>
		<select class="auto-select" name="oaq[location]">
			<option><?php _e( 'All', '5p2p-vc-openagenda' ) ?></option>
			<?php
			foreach ( $locations as $key => $value ) {
				$key      = strval( $key );
				$get      = $_GET['oaq']['location'];
				$selected = selected( $get, $key, false );
				?>
				<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
				<?php
			}
			?>
		</select>
		<script>
            jQuery("select.auto-select").change(function () {
                jQuery(this).parents("form").eq(0).submit();
            });
		</script>
	</form>
	<?php
	$html = ob_get_clean();
	return $html;
}


function openagenda_location_list_html( $locations, $atts ) {

	/**
	 * create link to event
	 */
	$atts['event-link'] = ( ! empty( $atts['event-link'] ) ) ? vc_build_link( $atts['event-link'] ) : '';

	if ( $atts['event-link']['target'] == ' _blank' ) {
		$target = 'target="_blank"';
	}
	if ( $atts['event-link']['rel'] == 'nofollow' ) {
		$rel = 'rel="nofollow"';
	}

	ob_start();

	echo '<div class="p2p5-vc-element-openagenda-location"><ul class="location-grid">';

	foreach ( $locations as $key => $value ) {
		$url = add_query_arg( 'oaq[location]', $key, $atts['event-link']['url'] );
		?>
		<li class="item"><a href="<?php echo $url; ?>" <?php echo $target . $rel ?>><?php echo $value ?></a></li>
		<?php
	}

	echo '</ul></div>';

	return ob_get_clean();
}
