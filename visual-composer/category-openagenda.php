<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly

	function p2p5_vc_openagenda_category() {

		vc_map( array(
				'name'        => __( 'Category Openagenda', '5p2p-vc-openagenda' ),
				'base'        => 'p2p5-vc-openagenda-cat',
				'description' => __( 'Display Category from Openagenda', '5p2p-vc-openagenda' ),
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
				),
			)
		);

	}

	add_action( 'init', 'p2p5_vc_openagenda_category' );

	function p2p5_vc_display_openagenda_cat( $atts ) {
		$atts = shortcode_atts( array(
			'agenda_url'  => '',
			'event-link'  => '',


		),
			$atts, 'p2p5-vc-openagenda-cat'
		);

		$re = '/[a-zA-Z\.\/:]*\/([a-zA-Z\.\/:\0-_9]*)/';

		preg_match( $re, $atts['agenda_url'], $matches, PREG_OFFSET_CAPTURE, 0 );

		$slug = untrailingslashit( $matches[1][0] );
		$key  = get_option( 'openagenda_api' );


		if ( ! empty( $key ) ) {

			$response = wp_remote_get( 'https://api.openagenda.com/v1/agendas/uid/' . $slug . '?key=' . $key );
			if ( 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
				$body         = wp_remote_retrieve_body( $response );
				$decoded_body = json_decode( $body, true );
				$uid          = $decoded_body['data']['uid'];
			}

		} else {
			$warning = '<p>' . __( 'Please add an OpenAgenda API Key in Settings / OpenAgenda Settings', '5p2p-vc-openagenda' ) . '</p>';
		}

		if ( $uid ) {

			$url = 'https://openagenda.com/agendas/' . $uid . '/events.json?key=' . $key . '&limit=200';

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

		$cat_list = array();

		foreach ( $events as $event ) {

			$cat  = $event['category']['label'];
			$slug = $event['category']['slug'];


			/**
			 * If a slug is empty (no cat to event)
			 */
			if ( empty( $slug ) ) {
				continue;
			}

			$cat_list[ $slug ] = $cat;
		}

		$cat_list = array_unique( $cat_list );
		unset( $cat_list[1] );
		ksort( $cat_list );

		ob_start();
		echo '<div class="p2p5-vc-element-openagenda-categ"><ul>';

		foreach ( $cat_list as $key => $value ) {
			$url = add_query_arg(
				array(
					'oaq'   => array(
						'category' => $key,

					),
				), $atts['event-link']['url'] );
			echo '<li><a href="'. $url .'" '. $target. $rel . '>' . $value . '</a></li>';
		}

		echo '</ul></div>';





		return ob_get_clean();

	}

	add_shortcode( 'p2p5-vc-openagenda-cat', 'p2p5_vc_display_openagenda_cat' );
