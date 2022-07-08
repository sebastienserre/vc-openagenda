<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

function p2p5_vc_openagenda_single_event() {

	vc_map( array(
			'name'        => __( 'Single Event Openagenda', 'vc-openagenda' ),
			'base'        => 'p2p5-vc-openagenda-single-event',
			'description' => __( 'Display 1 Event from Openagenda', 'vc-openagenda' ),
			'category'    => __( '5 Pains & 2  Poissons', 'vc-openagenda' ),
			'icon'        => P2P5_OPENAGENDA_URL . '/assets/img/icon.jpg',
			'params'      => array(

				array(
					'type'        => 'textfield',
					'holder'      => 'h3',
					'class'       => 'title-class',
					'heading'     => __( 'Title', 'vc-openagenda' ),
					'param_name'  => 'title',
					'value'       => __( 'Title', 'vc-openagenda' ),
					'description' => __( 'Add a word between % to add a different style. Only 1 allowed', 'vc-openagenda' ),
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', 'vc-openagenda' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'h3',
					'class'       => 'title-class',
					'heading'     => __( 'OpenAgenda URL', '5p2p-vc-media-text' ),
					'param_name'  => 'agenda_url',
					'value'       => __( 'OpenAgenda URL', '5p2p-vc-media-text' ),
					'description' => __( '', '5p2p-vc-media-text' ),
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', '5p2p-vc-media-text' ),
				),

				array(
					'type'        => 'textfield',
					'holder'      => 'p',
					'class'       => 'title-class',
					'heading'     => __( 'Link Text', 'vc-openagenda' ),
					'param_name'  => 'agenda_text',
					'value'       => __( 'Link Text', 'vc-openagenda' ),
					'description' => __( 'Text for the link', 'vc-openagenda' ),
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', 'vc-openagenda' ),
				),
				array(
					'type'        => 'vc_link',
					'holder'      => 'p',
					'class'       => 'title-class',
					'heading'     => __( 'Event Link in Openagenda or in your website if you created a special agenda page', 'vc-openagenda' ),
					'param_name'  => 'event-link',
					'value'       => '',
					'description' => '',
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', 'vc-openagenda' ),
				),
			),
		)
	);

}

add_action( 'init', 'p2p5_vc_openagenda_single_event' );

function p2p5_vc_retrieve_info_single( $atts ) {
	$oa           = new OpenAgendaApi();
	$atts = shortcode_atts( array(
		'title'       => '',
		'agenda_text' => '',
		'event-link'  => '',
        'agenda_url' => '',
	),
		$atts, 'p2p5-vc-openagenda-single-event'
	);
	$atts['event-link'] = ( ! empty( $atts['event-link'] ) ) ? vc_build_link( $atts['event-link'] ) : '';

    $slug = $oa->get_slug( $atts['agenda_url'] );

	$re = '/lang=([a-z]*)/';
	preg_match( $re, $atts['agenda_url'], $langs, PREG_OFFSET_CAPTURE, 0 );
	$atts['lang'] = 'fr';
	if ( ! empty( $langs ) ) {
		$atts['lang'] = $langs[1][0];
	}

	$decoded_body = $oa->thfo_openwp_retrieve_data( $slug, 200, 1 );
	$event        = array();

	foreach ( $decoded_body['events'] as $event_key => $events ) {
		if ( $slug === $events['slug'] ) {
			$event = $decoded_body['events'][ $event_key ];
		}
	}

	$nb_day = sizeof( $event['timings'] );
	$start  = strtotime( $event['timings'][0]['start'] );
	$end    = strtotime( $event['timings'][ $nb_day - 1 ]['end'] );

    $date = $oa->format_date( $start, $end );
	$city = $event['location']['city'];
	if ( ! empty( $city ) ) {
		$city = '<p class="p2p5-vc-element-openagenda-details-city">' . $city . '</p>';
	}

	$description = $event['html'][ $atts['lang'] ];
	if ( ! empty( $description ) ) {
		$description = explode( '<br />', $description );
		$description = substr( strip_tags( $description[0] ), 0, 180 );
		$description = '<p class="p2p5-vc-element-openagenda-details-description">' . $description . '...</p>';
	}

	$img_size = get_image_size( 'featured-post' );

	if ( empty( $atts['title'] ) ) {
		$atts['title'] = $event['title'][ $atts['lang'] ];
	}

	if ( empty( $atts['agenda_text'] ) ) {
		$atts['agenda_text'] = __( 'Read more', 'vc-openagenda' );
	}

	ob_start();

	p2p5_vc_display_single( $atts, $city, $date, $cat = '', $description, $event, $img_size, $decoded_body );

	return ob_get_clean();

}

add_shortcode( 'p2p5-vc-openagenda-single-event', 'p2p5_vc_retrieve_info_single' );

function p2p5_vc_display_single(
	$atts, $city, $date, $cat, $description, $event, $img_size, $decoded_body
) {
	if ( $atts['event-link']['target'] == '_blank' ) {
		$target = 'target="_blank"';
	}

	if ( $atts['event-link']['rel'] == 'nofollow' ) {
		$rel = 'rel="nofollow"';
	}
    if ( empty( $atts['agenda_url'] ) || empty( $event ) ){
        ?>
            <div class="p2p5-vc-element-openagenda-single hor p2p5-vc-element-openagenda error">
                <p><?php esc_html_e('Please set an OpenAgenda URL or check your event on OpenAgenda (dates)', 'vc-openagenda'); ?></p>
            </div>
            <?php
    } else {
	?>
    <div class="p2p5-vc-element-openagenda-single hor p2p5-vc-element-openagenda">
        <div class="p2p5-vc-element-openagenda-picture left">
            <a href="<?php echo $atts['event-link']['url'] ?>" <?php echo $target . $rel; ?> ><img
                        src="<?php echo $event["image"] ?>"
                        width=<?php echo $img_size['width'] ?> height=<?php echo $img_size['height'] ?>>
            </a>
        </div>
        <div class="p2p5-vc-element-openagenda-details right">
			<?php
			echo '<h2><a href="' . $atts['event-link']['url'] . '"' . $target . $rel . '>' . $atts['title'] . '</a></h2>';
			echo $city;
			echo $date;
			echo $cat;
			?>

            <h2 class="p2p5-vc-element-openagenda-details-title">
                <a href="<?php echo $atts['event-link']['url'] ?>" <?php echo $target . $rel; ?> >
					<?php echo $decoded_body['data']['title'][ $atts['lang'] ]; ?>
                </a>
            </h2>
			<?php echo $description ?>
        </div>
        <a href="<?php echo $atts['event-link']['url'] ?>" <?php echo $target . $rel; ?>
           class="p2p5-vc-element-openagenda-details-readmore ">
			<?php echo $atts['agenda_text'] ?>
        </a>
    </div>
	<?php
    }
}