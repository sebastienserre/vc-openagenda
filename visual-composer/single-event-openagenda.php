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
						'holder'      => 'a',
						'class'       => 'url-class',
						'heading'     => __( 'URL to Event', 'vc-openagenda' ),
						'param_name'  => 'agenda_url',
						'value'       => esc_url( site_url() ),
						'description' => __( 'URL to the event in OpenAgenda', 'vc-openagenda' ),
						'admin_label' => false,
						'weight'      => 0,
						'group'       => __( 'Settings', 'vc-openagenda' ),
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

					array(
						'type'        => 'checkbox',
						'holder'      => 'p',
						'class'       => 'title-class',
						'heading'     => __( 'Options', 'vc-openagenda' ),
						'param_name'  => 'openagenda_option',
						'value'       => array(
							__( 'Date', 'vc-openagenda' )        => 'date',
							__( 'City', 'vc-openagenda' )        => 'venue',
							__( 'Description', 'vc-openagenda' ) => 'description',
						),
						'description' => __( 'Check the options to display', 'vc-openagenda' ),
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
		$atts = shortcode_atts( array(
			'agenda_url'        => '',
			'title'             => '',
			'agenda_text'       => '',
			'openagenda_option' => '',
			'event-link'        => '',


		),
			$atts, 'p2p5-vc-openagenda-single-event'
		);

		$re = '/events\/([a-zA-Z\.\/:\0-_9]*)/';

		preg_match( $re, $atts['agenda_url'], $matches, PREG_OFFSET_CAPTURE, 0 );

		$slug = untrailingslashit( $matches[1][0] );

		$key = get_option( 'openagenda_api' );


		if ( ! empty( $key ) ) {

			$response = wp_remote_get( 'https://api.openagenda.com/v1/events/uid/' . $slug . '?key=' . $key );
			if ( 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
				$body         = wp_remote_retrieve_body( $response );
				$decoded_body = json_decode( $body, true );
				$uid          = $decoded_body['data']['uid'];
			}

		} else {
			$warning = '<p>' . __( 'Please add an OpenAgenda API Key in Settings / OpenAgenda Settings', 'vc-openagenda' ) . '</p>';
		}

		if ( $uid ) {

			$url = 'https://api.openagenda.com/v1/events/' . $uid . '?key=' . $key;

			$oaq = [];
			if (! empty( $atts['agenda_tag'] ) ) {
				if ( ! is_null( $atts['agenda_tag'] ) ) {
					$oaq['tags'] = $atts['agenda_tag'];
				}
			}

			if ( ! empty( $atts['openagenda_cat'] ) ) {
			    if ( ! is_null( $atts['openagenda_cat'] ) ) {
					$oaq['category'] = $atts['openagenda_cat'];
				}
			}

			if ( ! empty( $atts['agenda_search'] ) ) {
				if ( ! is_null( $atts['agenda_search'] ) ) {
					$oaq['what'] = $atts['agenda_search'];
				}
			}

			if ( empty( $atts['nb_event'] ) ) {
				$atts['nb_event'] = 10;
			}

			$url = add_query_arg(
				array(
					'oaq'   => $oaq,
					'limit' => $atts['nb_event'],
				), $url );

			$response = wp_remote_get( $url );


			$decoded_body = array();

			if ( 200 == (int) wp_remote_retrieve_response_code( $response ) ) {
				$body         = wp_remote_retrieve_body( $response );
				$decoded_body = json_decode( $body, true );
			}


		}

		$options = explode( ',', $atts['openagenda_option'] );

		$nb_day = sizeof( $decoded_body['data']['locations'][0]['dates'] );
		$start  = strtotime( $decoded_body['data']['locations'][0]['dates'][0]['date'] );
		$end    = strtotime( $decoded_body['data']['locations'][0]['dates'][ $nb_day - 1 ]['date'] );

		ob_start();

		if ( ! empty( $atts['title'] ) ) {
			echo '<h2>' . $atts['title'] . '</h2>';
		}

		echo '<div class="p2p5-vc-element-openagenda">';


		/**
		 * If date is checked to be displayed
		 * Several form according lenght of event (1 or more days)
		 */

		if ( in_array( 'date', $options ) ) {

			if ( $start == $end ) {
				$date = date_i18n( 'd F', $start );
				$date = '<p class="p2p5-vc-element-openagenda-details-date">' . sprintf( __( 'On %s', 'vc-openagenda' ), $date ) . '</p>';
			}

			if ( $start != $end ) {

				$start = date_i18n( 'd F', $start );
				$end   = date_i18n( 'd F', $end );

				$date = '<p class="p2p5-vc-element-openagenda-details-date">' . sprintf( __( 'from %1s to %2s', 'vc-openagenda' ), $start, $end ) . '</p>';
			}

		}

		/**
		 * If city is checked
		 */

		if ( in_array( 'venue', $options ) ) {
			$city = $decoded_body['data']['locations'][0]['city'];
			if ( ! empty( $city ) ) {
				$city = '<p class="p2p5-vc-element-openagenda-details-city">' . $city . '</p>';
			}
		}

		/**
		 * If description is checked, then display description
		 */
		if ( in_array( 'description', $options ) ) {
			$description = $decoded_body['data']['description']['fr'];
			if ( ! empty( $description ) ) {
				$description = '<p class="p2p5-vc-element-openagenda-details-description">' . $description . '</p>';
			}
		}

		/**
		 * create link readmore
		 */
		$atts['event-link'] = ( ! empty( $atts['event-link'] ) ) ? vc_build_link( $atts['event-link'] ) : '';

		$url = add_query_arg(
			array(
				'oaq' => array(
					'uid' => $decoded_body['data']['uid'],

				),
			), $atts['event-link']['url'] );


		if ( $atts['event-link']['target'] == ' _blank' ) {
			$target = 'target="_blank"';
		}
		if ( $atts['event-link']['rel'] == 'nofollow' ) {
			$rel = 'rel="nofollow"';
		}

		$img_size = get_image_size('featured-post');

		p2p5_vc_display_single( $atts, $city, $date, $cat = '', $description, $target ='', $rel='', $decoded_body, $url,
            $img_size );

		echo '</div>';

		return ob_get_clean();

	}

	add_shortcode( 'p2p5-vc-openagenda-single-event', 'p2p5_vc_retrieve_info_single' );

	function p2p5_vc_display_single( $atts, $city, $date, $cat, $description, $target, $rel, $decoded_body, $url,
        $img_size ) { ?>

        <div class="p2p5-vc-element-openagenda-single hor">
            <div class="p2p5-vc-element-openagenda-picture left">
                <a href="<?php echo $url ?>" <?php echo $target . $rel; ?> ><img src="<?php echo $decoded_body['data']['image'] ?>" width=<?php echo $img_size['width'] ?> height=<?php echo $img_size['height'] ?>></a>
				<?php if ( ! empty( $atts['openagenda_layout'] ) && $atts['openagenda_layout'] == 'ver' ) {
					echo $cat;
				} ?>
            </div>
            <div class="p2p5-vc-element-openagenda-details right">
				<?php echo $city ?>
				<?php echo $date ?>
				<?php if ( ! empty( $atts['openagenda_layout'] ) && $atts['openagenda_layout'] == 'hor' ) {
					echo $cat;
				} ?>

                <h2 class="p2p5-vc-element-openagenda-details-title"><a href="<?php echo $url ?>" <?php echo $target . $rel; ?> ><?php echo $decoded_body['data']['title']['fr']; ?></a></h2>

				<?php echo $description ?>
            </div>
            <a href="<?php echo $url ?>" <?php echo $target . $rel; ?>
               class="p2p5-vc-element-openagenda-details-readmore ">
				<?php echo $atts['agenda_text'] ?>
            </a>
        </div>
		<?php
	}