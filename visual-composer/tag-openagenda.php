<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

function p2p5_vc_openagenda_tag() {

	vc_map( array(
			'name'        => __( 'Tags Openagenda', 'vc-openagenda' ),
			'base'        => 'p2p5-vc-openagenda-tag',
			'description' => __( 'Display Tags from Openagenda', 'vc-openagenda' ),
			'category'    => __( '5 Pains & 2  Poissons', 'vc-openagenda' ),
			'icon'        => P2P5_OPENAGENDA_URL . '/assets/img/icon.jpg',
			'params'      => array(

				array(
					'type'        => 'textfield',
					'holder'      => 'a',
					'class'       => 'url-class',
					'heading'     => __( 'URL to Agenda', 'vc-openagenda' ),
					'param_name'  => 'agenda_url',
					'value'       => esc_url( site_url() ),
					'description' => __( 'URL to Agenda in OpenAgenda.', 'vc-openagenda' ),
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', 'vc-openagenda' ),
				),
				array(
					'type'        => 'vc_link',
					'holder'      => 'p',
					'class'       => 'title-class',
					'heading'     => __( 'Event Link', 'vc-openagenda' ),
					'param_name'  => 'event-link',
					'value'       => '',
					'description' => '',
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', 'vc-openagenda' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'a',
					'class'       => 'url-class',
					'heading'     => __( 'Hide background', 'vc-openagenda' ),
					'param_name'  => 'background',
					'description' => __( 'Write name of Tags to hide the background as it is on Openagenda.', 'vc-openagenda' ),
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', 'vc-openagenda' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'p',
					'class'       => 'hide-group-name',
					'heading'     => __( 'Hide Group Name', 'vc-openagenda' ),
					'param_name'  => 'hide-group-name',
					'value'       => 'yes',
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', 'vc-openagenda' ),
				),

			),
		)
	);

}

add_action( 'init', 'p2p5_vc_openagenda_tag' );

function p2p5_vc_display_openagenda_tag( $atts ) {
	$atts = shortcode_atts( array(
		'agenda_url'      => '',
		'event-link'      => '',
		'background'      => '',
		'hide-group-name' => '',
	),
		$atts, 'p2p5-vc-openagenda-tag'
	);

	$atts['background'] = sanitize_title( $atts['background'] );

    $uid = get_option( 'openagenda_uid' );

	$re = '/[a-zA-Z\.\/:]*\/([a-zA-Z\.\/:\0-_9]*)/';

	preg_match( $re, $atts['agenda_url'], $matches, PREG_OFFSET_CAPTURE, 0 );

	$slug = untrailingslashit( $matches[1][0] );
	$key  = get_option( 'openagenda_api' );

	if ( $uid ) {

		$url = 'https://openagenda.com/agendas/' . $uid . '/events.json?key=' . $key . '&limit=999';

		$response = wp_remote_get( $url );


		$decoded_body = array();

		if ( 200 == (int) wp_remote_retrieve_response_code( $response ) ) {
			$body         = wp_remote_retrieve_body( $response );
			$decoded_body = json_decode( $body, true );
		}
	}

	$events = $decoded_body['events'];

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

	$group = array();
	foreach ( $events as $event ) {


		foreach ( $event['tagGroups'] as $tagGroup ) {

			if ( empty( $group[ $tagGroup['slug'] ] ) ) {
				$group[ $tagGroup['slug'] ] = array();
			}

			foreach ( $tagGroup['tags'] as $tags ) {
				array_push( $group[ $tagGroup['slug'] ], $tags );
			}


		}

	}

	foreach ( $group as $key => $groupe ) {
		if ( empty( $tagslist[ $key ] ) ) {
			$tagslist[ $key ] = array();
		}
		$tagslist[ $key ] = wp_list_pluck( $groupe, 'label' );

		foreach ( $tagslist as $t ) {
			$new_tag_list[ $key ] = array_unique( $t );
		}

	}

	ob_start();
	echo '<div class="p2p5-vc-element-openagenda-categ"><ul>';

	foreach ( $new_tag_list as $key => $value ) {
		if ( $key === $atts['background'] ) {
			$class = 'no-bg';
		}
		?>
		<div class="openagenda-tags tags-<?php echo $key; ?> <?php echo $class; ?>">
			<?php if ( empty( $atts['hide-group-name'] ) || 'true' == ! $atts['hide-group-name'] ) { ?>
				<h3><?php echo $key ?></h3>
			<?php } ?>
			<ul>
				<?php
				foreach ( $new_tag_list[ $key ] as $list ) {
					$slug = sanitize_title( $list );
					$url  = $atts['event-link']['url'] . '?oaq[tags][]=' . $slug;
					?>
					<li>
						<a href="<?php echo $url; ?>" <?php echo $target . $rel ?>>
							<?php echo $list; ?>
						</a>
					</li>

					<?php
					unset( $class );
				}
				?>
			</ul>

		</div>
		<div class="clear"></div>
		<?php
	}

	echo '</ul></div>';


	return ob_get_clean();

}

add_shortcode( 'p2p5-vc-openagenda-tag', 'p2p5_vc_display_openagenda_tag' );
