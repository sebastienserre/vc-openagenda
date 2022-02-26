<?php

	if ( ! defined( 'ABSPATH' ) ) {
		die( '-1' );
	}

	/*
	Element Description: VC Info Box
	*/

// Element Class
	class VcOpenAgenda {


		// Element Init
		function __construct() {
			add_action( 'init', array( $this, 'vc_openagenda_mapping' ) );
			add_shortcode( 'vc_openagenda', array( $this, 'vc_openagenda_html' ) );
		}

		// Element Mapping
		public function vc_openagenda_mapping() {
			vc_map(

				array(
					'name'        => __( 'OpenAgenda Slider', '5p2p-vc-openagenda' ),
					'base'        => 'vc_openagenda_slider',
					'description' => __( 'Display your openAgenda in a slider view', '5p2p-vc-openagenda' ),
					'category'    => __( '5 Pains & 2  Poissons', '5p2p-vc-openagenda' ),
					'icon'        => P2P5_OPENAGENDA_URL . '/assets/img/icon.jpg',
					'params'      => array(

						array(
							'type'        => 'textfield',
							'holder'      => 'h3',
							'class'       => 'title-class',
							'heading'     => __( 'Agenda Slug', '5p2p-vc-openagenda' ),
							'param_name'  => 'agenda_slug',
							'value'       => __( 'my-agenda-slug', '5p2p-vc-openagenda' ),
							'description' => __( 'The Slug of your agenda in openagenda. For example, if your Openagenda URL is https://openagenda.com/my-great-calendar, then the slug is my-great-calendar', '5p2p-vc-openagenda' ),
							'admin_label' => false,
							'weight'      => 0,
							'group'       => 'Agenda',
						),

						array(
							'type'        => 'textfield',
							'holder'      => 'h3',
							'class'       => 'title-class',
							'heading'     => __( 'Title', '5p2p-vc-openagenda' ),
							'param_name'  => 'title',
							'value'       => __( 'Title', '5p2p-vc-openagenda' ),
							'description' => __( 'Add a word between % to add a different style. Only 1 allowed', '5p2p-vc-openagenda' ),
							'admin_label' => false,
							'weight'      => 0,
							'group'       => 'Agenda',
						),

						array(
							'type'        => 'textfield',
							'holder'      => 'a',
							'class'       => 'url-class',
							'heading'     => __( 'URL to Agenda', '5p2p-vc-openagenda' ),
							'param_name'  => 'agenda_url',
							'value'       => esc_url( site_url() ),
							'description' => __( 'URL to Agenda. You must create a page integrating OpenAgenda', '5p2p-vc-openagenda' ),
							'admin_label' => false,
							'weight'      => 0,
							'group'       => 'Agenda',
						),

						array(
							'type'        => 'textfield',
							'holder'      => 'p',
							'class'       => 'title-class',
							'heading'     => __( 'Link Text', '5p2p-vc-openagenda' ),
							'param_name'  => 'agenda_text',
							'value'       => __( 'Link Text', '5p2p-vc-openagenda' ),
							'description' => __( 'Text for the link', '5p2p-vc-openagenda' ),
							'admin_label' => false,
							'weight'      => 0,
							'group'       => 'Agenda',
						),

						array(
							'type'        => 'colorpicker',
							'holder'      => 'p',
							'class'       => 'title-class',
							'heading'     => __( 'Color to Title', '5p2p-vc-openagenda' ),
							'param_name'  => 'agenda_title_color',
							'value'       => '#000000',
							'description' => __( 'Select the color for the  title part between %', '5p2p-vc-openagenda' ),
							'admin_label' => false,
							'weight'      => 0,
							'group'       => 'Agenda',
						),

						array(
							'type'        => 'colorpicker',
							'holder'      => 'p',
							'class'       => 'title-class',
							'heading'     => __( 'Background color', '5p2p-vc-openagenda' ),
							'param_name'  => 'agenda_date_color',
							'value'       => '#00bfff',
							'description' => __( 'Date Box background color', '5p2p-vc-openagenda' ),
							'admin_label' => false,
							'weight'      => 0,
							'group'       => 'Agenda',
						),

						array(
							'type'        => 'colorpicker',
							'holder'      => 'p',
							'class'       => 'title-class',
							'heading'     => __( 'text color', '5p2p-vc-openagenda' ),
							'param_name'  => 'agenda_date_text_color',
							'value'       => '#000000',
							'description' => __( 'Date Box text color', '5p2p-vc-openagenda' ),
							'admin_label' => false,
							'weight'      => 0,
							'group'       => 'Agenda',
						),

						array(
							'type'        => 'textfield',
							'holder'      => 'p',
							'class'       => 'title-class',
							'heading'     => __( 'tag Filter', '5p2p-vc-openagenda' ),
							'param_name'  => 'agenda_tag',
							'value'       => __( '', '5p2p-vc-openagenda' ),
							'description' => __( 'Tag Filter', '5p2p-vc-openagenda' ),
							'admin_label' => false,
							'weight'      => 0,
							'group'       => 'Agenda',
						),

						array(
							'type'        => 'textfield',
							'holder'      => 'p',
							'class'       => 'title-class',
							'heading'     => __( 'Cat Filter', '5p2p-vc-openagenda' ),
							'param_name'  => 'agenda_cat',
							'value'       => __( '', '5p2p-vc-openagenda' ),
							'description' => __( 'Cat Filter', '5p2p-vc-openagenda' ),
							'admin_label' => false,
							'weight'      => 0,
							'group'       => 'Agenda',
						),

						array(
							'type'        => 'textfield',
							'holder'      => 'p',
							'class'       => 'title-class',
							'heading'     => __( 'search', '5p2p-vc-openagenda' ),
							'param_name'  => 'agenda_search',
							'value'       => __( '', '5p2p-vc-openagenda' ),
							'description' => __( 'search Filter', '5p2p-vc-openagenda' ),
							'admin_label' => false,
							'weight'      => 0,
							'group'       => 'Agenda',
						),

						array(
							'type'        => 'checkbox',
							'holder'      => 'p',
							'class'       => 'title-class',
							'heading'     => __( 'Display venue', '5p2p-vc-openagenda' ),
							'param_name'  => 'agenda_lieu',
							'value'       => __( '', '5p2p-vc-openagenda' ),
							'description' => __( 'Display Venue', '5p2p-vc-openagenda' ),
							'admin_label' => false,
							'weight'      => 0,
							'group'       => 'Agenda',
						),

					)
				)
			);

		}


		// Element HTML
		public function vc_openagenda_html( $atts ) {

			$atts = shortcode_atts( array(
				'agenda_slug'            => '',
				'title'                  => '',
				'agenda_url'             => '',
				'agenda_text'            => '',
				'agenda_title_color'     => '',
				'agenda_date_color'      => '',
				'agenda_date_text_color' => '',
				'agenda_tag'             => '',
				'agenda_cat'             => '',
				'agenda_search'          => '',
				'agenda_lieu'            => '',
			),
				$atts

			);

			$atts['agenda_search']          = str_replace( ' ', '%20', $atts['agenda_search'] );


			return $this->p2p5_openagenda_retrieve( $atts );
		}

		/**
		 * Retrieve infos from Openagenda.com
		 *
		 * @param $transient_slug
		 * @param $atts
		 */

		public function p2p5_openagenda_retrieve( $atts ) {

			$key = get_option( 'openagenda_api' );

			$uid = get_transient( 'openagenda_uid_' . $atts['agenda_slug'] );

			/**
			 * L'uid n'existe pas
			 */

			if ( ! $uid ) {
				if ( ! empty( $key ) ) {
					$response = wp_remote_get( 'https://api.openagenda.com/v1/agendas/uid/' . $atts['agenda_slug'] . '?key=' . $key );
					if ( 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
						$body         = wp_remote_retrieve_body( $response );
						$decoded_body = json_decode( $body, true );
						$uid          = $decoded_body['data']['uid'];
					}

					set_transient( 'openagenda_uid_' . $atts['agenda_slug'], $uid, DAY_IN_SECONDS );
				}
			}


			if ( $uid ) {



				$url = 'https://openagenda.com/agendas/' . $uid . '/events.json';

				$url = add_query_arg(
					array( 'oaq' => array(
						'tags' => $atts['agenda_tag'],
						'category' => $atts['agenda_cat'],
						'what' => $atts['agenda_search'],
					),
					), $url );

				$response = wp_remote_get( $url );



				$decoded_body = array();

				if ( 200 == (int) wp_remote_retrieve_response_code( $response ) ) {
					$body         = wp_remote_retrieve_body( $response );
					$decoded_body = json_decode( $body, true );
				}


			}
			ob_start();
			$this->p2p5_openagenda_view($decoded_body, $atts);
			return ob_get_clean();
		}


		public function p2p5_openagenda_view( $decoded_body, $atts ) {
			$car_exist = strpos( $atts['title'], '%' );

			if ( $car_exist ) {
				$title = explode( '%', $atts['title'] );

				$title = $title[0] . '<span class="bloc-openagenda__title" >' . $title[1] . '</span> ' . $title[2];
			} else {
				$title = $atts['title'];
			}


			$html = '<div class="bloc-openagenda"><h2 class="bloc-openagenda__title openagenda" style="color:' . $atts['agenda_title_color'] . ';">' . $title . '</h2>';

			if ( $decoded_body['total'] == 0 ) {

				$html .= '<p>' . __( 'Sorry, no events found', '5p2p-vc-openagenda' ) . '</p>';
			} else {

				$html .= '<div class="bloc-openagenda__slider">';

				foreach ( $decoded_body as $event ) {
					foreach ( $event as $ev ) {
						$url        = $atts['agenda_url'] . '?oaq%5Buid%5D=' . $atts['event_id'];
						$start_test = strtotime( date( 'd-F-Y', strtotime( $ev['timings'][0]['start'] ) ) );
						$today      = date( 'U' );

						$date = $ev['range']['fr'];

						if ( strlen( $ev['title']['fr'] ) > '59' ) {
							$ev['title']['fr'] = substr( $ev['title']['fr'], 0, 59 ) . '...';
						}

						if ( $start_test >= $today ) {

							$html .= '<div class="bloc-openagenda__slide" >';
							$html .= '<a class="bloc-openagenda__link" href="' . $url . '" style="color:' . $atts['agenda_date_text_color'] . ';" >';
							$html .= '<div class="bloc-openagenda__pic">';
							$html .= '<img src="' . $ev['image'] . '"  />';
							$html .= '</div>';

							$html .= '<div class="bloc-openagenda__date" style="background: ' . $atts['agenda_date_color'] . '; color:' . $atts['agenda_date_text_color'] . ';">';
							$html .= '<div class="bloc-openagenda__box-title bloc-openagenda__box-title--uppercase" style=" color:' . $atts['agenda_date_text_color'] . ';"> ' . $date . '</div>';

							if ( $atts['agenda_lieu'] == 'true' ) {
								$html .= '<p class="bloc-openagenda__lieu">' . $ev['locationName'] . '</p>';
							}


							$html .= '</div>';
							$html .= '</a>';
							$html .= '</div>';

						}
					}
				}
				$html .= '</div><div class="arrows-bottom"></div></div><a href="' . $atts['agenda_url'] . '">' . $atts['agenda_url_text'] . '</a>';


			}

			echo $html;

			//return $html;
		}


	}


// End Element Class

// Element Class Init
	new VcOpenAgenda();