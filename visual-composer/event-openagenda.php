<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

function p2p5_vc_openagenda_element() {

	vc_map( array(
			'name'        => __( 'Event Openagenda', 'vc-openagenda' ),
			'base'        => 'p2p5-vc-openagenda',
			'description' => __( 'Display Event from Openagenda', 'vc-openagenda' ),
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
					'heading'     => __( 'URL to Agenda', 'vc-openagenda' ),
					'param_name'  => 'agenda_url',
					'value'       => esc_url( site_url() ),
					'description' => __( 'URL to Agenda. You must create a page integrating OpenAgenda', 'vc-openagenda' ),
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', 'vc-openagenda' ),
				),

				array(
					'type'        => 'dropdown',
					'holder'      => 'p',
					'class'       => 'layout-mission',
					'heading'     => __( 'Choose your layout', 'vc-openagenda' ),
					'param_name'  => 'openagenda_layout',
					'description' => __( 'Choose your layout', 'vc-openagenda' ),
					'admin_label' => false,
					'value'       => array( 'Horizontal' => 'hor', 'Vertical' => 'ver' ),
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
					'holder'      => 'p',
					'class'       => 'openagenda_cat',
					'heading'     => __( 'Categorie to display', 'vc-openagenda' ),
					'param_name'  => 'openagenda_cat',
					'description' => __( 'Choose the categorie to display. Only one category allowed. If you choose a Category, do not choose a Tag too', 'vc-openagenda' ),
					'admin_label' => false,
					'value'       => '',
					'weight'      => 0,
					'group'       => __( 'Settings', 'vc-openagenda' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'p',
					'class'       => 'openagenda_tag',
					'heading'     => __( 'Tag to display', 'vc-openagenda' ),
					'param_name'  => 'openagenda_tag',
					'description' => __( 'Choose the tag to display. Only one category allowed. If you choose a Tag, do not choose a Category too', 'vc-openagenda' ),
					'admin_label' => false,
					'value'       => '',
					'weight'      => 0,
					'group'       => __( 'Settings', 'vc-openagenda' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'p',
					'class'       => 'title-class',
					'heading'     => __( 'Nb of event', 'vc-openagenda' ),
					'param_name'  => 'nb_event',
					'value'       => '1',
					'description' => __( 'Number of event to display', 'vc-openagenda' ),
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
						__( 'Category', 'vc-openagenda' ) => 'cat',
						__( 'Tag', 'vc-openagenda' )      => 'tag',
						__( 'Date', 'vc-openagenda' )     => 'date',
						__( 'City', 'vc-openagenda' )     => 'venue',
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

add_action( 'init', 'p2p5_vc_openagenda_element' );

function clean( $string ) {
	$string               = str_replace( ' ', '-', $string ); // Replaces all spaces with hyphens.
	$transliterationTable = array(
		'á' => 'a',
		'Á' => 'A',
		'à' => 'a',
		'À' => 'A',
		'ă' => 'a',
		'Ă' => 'A',
		'â' => 'a',
		'Â' => 'A',
		'å' => 'a',
		'Å' => 'A',
		'ã' => 'a',
		'Ã' => 'A',
		'ą' => 'a',
		'Ą' => 'A',
		'ā' => 'a',
		'Ā' => 'A',
		'ä' => 'ae',
		'Ä' => 'AE',
		'æ' => 'ae',
		'Æ' => 'AE',
		'ḃ' => 'b',
		'Ḃ' => 'B',
		'ć' => 'c',
		'Ć' => 'C',
		'ĉ' => 'c',
		'Ĉ' => 'C',
		'č' => 'c',
		'Č' => 'C',
		'ċ' => 'c',
		'Ċ' => 'C',
		'ç' => 'c',
		'Ç' => 'C',
		'ď' => 'd',
		'Ď' => 'D',
		'ḋ' => 'd',
		'Ḋ' => 'D',
		'đ' => 'd',
		'Đ' => 'D',
		'ð' => 'dh',
		'Ð' => 'Dh',
		'é' => 'e',
		'É' => 'E',
		'è' => 'e',
		'È' => 'E',
		'ĕ' => 'e',
		'Ĕ' => 'E',
		'ê' => 'e',
		'Ê' => 'E',
		'ě' => 'e',
		'Ě' => 'E',
		'ë' => 'e',
		'Ë' => 'E',
		'ė' => 'e',
		'Ė' => 'E',
		'ę' => 'e',
		'Ę' => 'E',
		'ē' => 'e',
		'Ē' => 'E',
		'ḟ' => 'f',
		'Ḟ' => 'F',
		'ƒ' => 'f',
		'Ƒ' => 'F',
		'ğ' => 'g',
		'Ğ' => 'G',
		'ĝ' => 'g',
		'Ĝ' => 'G',
		'ġ' => 'g',
		'Ġ' => 'G',
		'ģ' => 'g',
		'Ģ' => 'G',
		'ĥ' => 'h',
		'Ĥ' => 'H',
		'ħ' => 'h',
		'Ħ' => 'H',
		'í' => 'i',
		'Í' => 'I',
		'ì' => 'i',
		'Ì' => 'I',
		'î' => 'i',
		'Î' => 'I',
		'ï' => 'i',
		'Ï' => 'I',
		'ĩ' => 'i',
		'Ĩ' => 'I',
		'į' => 'i',
		'Į' => 'I',
		'ī' => 'i',
		'Ī' => 'I',
		'ĵ' => 'j',
		'Ĵ' => 'J',
		'ķ' => 'k',
		'Ķ' => 'K',
		'ĺ' => 'l',
		'Ĺ' => 'L',
		'ľ' => 'l',
		'Ľ' => 'L',
		'ļ' => 'l',
		'Ļ' => 'L',
		'ł' => 'l',
		'Ł' => 'L',
		'ṁ' => 'm',
		'Ṁ' => 'M',
		'ń' => 'n',
		'Ń' => 'N',
		'ň' => 'n',
		'Ň' => 'N',
		'ñ' => 'n',
		'Ñ' => 'N',
		'ņ' => 'n',
		'Ņ' => 'N',
		'ó' => 'o',
		'Ó' => 'O',
		'ò' => 'o',
		'Ò' => 'O',
		'ô' => 'o',
		'Ô' => 'O',
		'ő' => 'o',
		'Ő' => 'O',
		'õ' => 'o',
		'Õ' => 'O',
		'ø' => 'oe',
		'Ø' => 'OE',
		'ō' => 'o',
		'Ō' => 'O',
		'ơ' => 'o',
		'Ơ' => 'O',
		'ö' => 'oe',
		'Ö' => 'OE',
		'ṗ' => 'p',
		'Ṗ' => 'P',
		'ŕ' => 'r',
		'Ŕ' => 'R',
		'ř' => 'r',
		'Ř' => 'R',
		'ŗ' => 'r',
		'Ŗ' => 'R',
		'ś' => 's',
		'Ś' => 'S',
		'ŝ' => 's',
		'Ŝ' => 'S',
		'š' => 's',
		'Š' => 'S',
		'ṡ' => 's',
		'Ṡ' => 'S',
		'ş' => 's',
		'Ş' => 'S',
		'ș' => 's',
		'Ș' => 'S',
		'ß' => 'SS',
		'ť' => 't',
		'Ť' => 'T',
		'ṫ' => 't',
		'Ṫ' => 'T',
		'ţ' => 't',
		'Ţ' => 'T',
		'ț' => 't',
		'Ț' => 'T',
		'ŧ' => 't',
		'Ŧ' => 'T',
		'ú' => 'u',
		'Ú' => 'U',
		'ù' => 'u',
		'Ù' => 'U',
		'ŭ' => 'u',
		'Ŭ' => 'U',
		'û' => 'u',
		'Û' => 'U',
		'ů' => 'u',
		'Ů' => 'U',
		'ű' => 'u',
		'Ű' => 'U',
		'ũ' => 'u',
		'Ũ' => 'U',
		'ų' => 'u',
		'Ų' => 'U',
		'ū' => 'u',
		'Ū' => 'U',
		'ư' => 'u',
		'Ư' => 'U',
		'ü' => 'ue',
		'Ü' => 'UE',
		'ẃ' => 'w',
		'Ẃ' => 'W',
		'ẁ' => 'w',
		'Ẁ' => 'W',
		'ŵ' => 'w',
		'Ŵ' => 'W',
		'ẅ' => 'w',
		'Ẅ' => 'W',
		'ý' => 'y',
		'Ý' => 'Y',
		'ỳ' => 'y',
		'Ỳ' => 'Y',
		'ŷ' => 'y',
		'Ŷ' => 'Y',
		'ÿ' => 'y',
		'Ÿ' => 'Y',
		'ź' => 'z',
		'Ź' => 'Z',
		'ž' => 'z',
		'Ž' => 'Z',
		'ż' => 'z',
		'Ż' => 'Z',
		'þ' => 'th',
		'Þ' => 'Th',
		'µ' => 'u',
		'а' => 'a',
		'А' => 'a',
		'б' => 'b',
		'Б' => 'b',
		'в' => 'v',
		'В' => 'v',
		'г' => 'g',
		'Г' => 'g',
		'д' => 'd',
		'Д' => 'd',
		'е' => 'e',
		'Е' => 'E',
		'ё' => 'e',
		'Ё' => 'E',
		'ж' => 'zh',
		'Ж' => 'zh',
		'з' => 'z',
		'З' => 'z',
		'и' => 'i',
		'И' => 'i',
		'й' => 'j',
		'Й' => 'j',
		'к' => 'k',
		'К' => 'k',
		'л' => 'l',
		'Л' => 'l',
		'м' => 'm',
		'М' => 'm',
		'н' => 'n',
		'Н' => 'n',
		'о' => 'o',
		'О' => 'o',
		'п' => 'p',
		'П' => 'p',
		'р' => 'r',
		'Р' => 'r',
		'с' => 's',
		'С' => 's',
		'т' => 't',
		'Т' => 't',
		'у' => 'u',
		'У' => 'u',
		'ф' => 'f',
		'Ф' => 'f',
		'х' => 'h',
		'Х' => 'h',
		'ц' => 'c',
		'Ц' => 'c',
		'ч' => 'ch',
		'Ч' => 'ch',
		'ш' => 'sh',
		'Ш' => 'sh',
		'щ' => 'sch',
		'Щ' => 'sch',
		'ъ' => '',
		'Ъ' => '',
		'ы' => 'y',
		'Ы' => 'y',
		'ь' => '',
		'Ь' => '',
		'э' => 'e',
		'Э' => 'e',
		'ю' => 'ju',
		'Ю' => 'ju',
		'я' => 'ja',
		'Я' => 'ja'
	);

	return str_replace( array_keys( $transliterationTable ), array_values( $transliterationTable ), $string );
}


function p2p5_vc_retrieve_info( $atts ) {
	$atts = shortcode_atts( array(
		'agenda_url'        => '',
		'title'             => '',
		'openagenda_layout' => 'hor',
		'openagenda_cat'    => '',
		'openagenda_tag'    => '',
		'nb_event'          => '1',
		'openagenda_option' => '',
		'event-link'        => '',


	),
		$atts, 'p2p5-vc-openagenda'
	);

	$atts['openagenda_cat'] = clean( $atts['openagenda_cat'] );
	$atts['openagenda_tag'] = clean( $atts['openagenda_tag'] );

	$re = '/[a-zA-Z\.\/:]*\/([a-zA-Z\.\/:\0-_9]*)/';

	preg_match( $re, $atts['agenda_url'], $matches, PREG_OFFSET_CAPTURE, 0 );

	$uid = get_option( 'openagenda_uid' );

	if ( $uid ) {

		$url = 'https://openagenda.com/agendas/' . $uid . '/events.json';
		if ( ! empty( $atts['agenda_search'] ) ) {
			$oaq['what'] = $atts['agenda_search'];
		}
		if ( ! empty( $atts['openagenda_cat'] ) ) {
			$oaq['category'] = $atts['openagenda_cat'];
		}
		if ( ! empty( $atts['openagenda_tag'] ) ) {
			$oaq['tags'] = $atts['openagenda_tag'];
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


		if ( empty( $response ) ) {
			$warning = '<p>' . __( 'No events Found', 'vc-openagenda' ) . '</p>';
		}

		$decoded_body = array();

		if ( 200 == (int) wp_remote_retrieve_response_code( $response ) ) {
			$body         = wp_remote_retrieve_body( $response );
			$decoded_body = json_decode( $body, true );
		}


	}

	$options = explode( ',', $atts['openagenda_option'] );
	$events  = $decoded_body['events'];

	if ( empty( $events ) ) {
		$warning = '<p>' . __( 'No events Found', 'vc-openagenda' ) . '</p>';
	}

	ob_start();

	if ( ! empty( $atts['title'] ) ) {
		echo '<h2>' . $atts['title'] . '</h2>';
	}

	echo '<div class="p2p5-vc-element-openagenda">';

	if ( ! empty( $warning ) ) {
		echo $warning;
	}

	$atts['event-link'] = ( ! empty( $atts['event-link'] ) ) ? vc_build_link( $atts['event-link'] ) : '';

	foreach ( $events as $event ) {


		/**
		 * If category is checked to be dispalyed
		 */

		if ( in_array( 'cat', $options ) ) {

			if ( ! empty( $event['category'] ) ) {
				$cat  = $event['category']['label'];
				$slug = $event['category']['slug'];
				$url  = add_query_arg(
					array(
						'oaq' => array(
							'category' => $slug,

						),
					), $atts['event-link']['url'] );
				$cat  = '<p class="p2p5-vc-element-openagenda-details-cat"><a href="' . $url . '" >' . $cat . '</a></p>';
			}
		}

		/**
		 * If tag is checked to be dispalyed
		 */

		if ( in_array( 'tag', $options ) ) {
			$cat = $atts['openagenda_tag'];

			/** @var  $tags
			 *Search for Tag Label
			 */
			foreach ( $event['tags'] as $tags ) {
				$tag = in_array( $cat, $tags );
				if ( $tag ) {
					$cat = $tags['label'];
				}
			}
			$url = add_query_arg(
				array(
					'oaq' => array(
						'tags' => $atts['openagenda_tag'],

					),
				), $atts['event-link']['url'] );

			$url = $atts['event-link']['url'] . '?oaq[tags][]=' . $atts['openagenda_tag'];
			$cat = '<p class="p2p5-vc-element-openagenda-details-cat"><a href="' . $url . '" >' . $cat . '</a></p>';
		}

		/**
		 * If date is checked to be displayed
		 * Several form according lenght of event (1 or more days)
		 */

		if ( in_array( 'date', $options ) ) {
		    $dates = $event['timings'];
		    $nb_day = sizeof( $dates ) - 1;
		    $firstday = date_i18n('d F', strtotime( $dates[0]['start'] ) );
		    $lastday = date_i18n('d F', strtotime( $dates[ $nb_day ]['end'] ) );

		    if( $firstday !== $lastday ) {
			    $date = '<p class="p2p5-vc-element-openagenda-details-date">' . sprintf( __( 'from %1s to %2s', 'vc-openagenda' ), $firstday, $lastday ) . '</p>';
		    } else {
			    $date = '<p class="p2p5-vc-element-openagenda-details-date">' . sprintf( __( 'On %s', 'vc-openagenda' ), $firstday ) . '</p>';

		    }

		}

		/**
		 * If city is checked
		 */

		if ( in_array( 'venue', $options ) ) {
			$city = $event['location']['city'];
			if ( ! empty( $city ) ) {
				$city = '<p class="p2p5-vc-element-openagenda-details-city">' . $city . '</p>';
			}
		}

		/**
		 * Create Event URL
		 */

		$url = add_query_arg(
			array(
				'oaq' => array(
					'uid' => $event['uid'],

				),
			), $atts['event-link']['url'] );

		p2p5_vc_display( $atts, $event, $city, $date, $cat, '', '', $url );

	}

	echo '</div>';

	return ob_get_clean();

}

add_shortcode( 'p2p5-vc-openagenda', 'p2p5_vc_retrieve_info' );

function p2p5_vc_display( $atts, $event, $city, $date, $cat='', $target='', $rel='', $url='' ) { ?>

    <?php
    // Polylang Compat
    $lang = 'fr';
    if ( function_exists( 'pll_current_language' ) ){
        $lang = pll_current_language();
    }
	if ( $atts['openagenda_layout'] == 'ver' ) {
        vc_oa_display_ver( $atts, $event, $city, $date, $cat, $lang, $target, $rel, $url );
	} else {
		vc_oa_display_hor( $atts, $event, $city, $date, $lang , $cat, $target, $rel, $url );

	}
}

function vc_oa_display_hor( $atts, $event, $city, $date, $lang, $cat='', $target='', $rel='', $url='' ){
    ?>
    <div class="p2p5-vc-element-openagenda-single hor">
        <div class="p2p5-vc-element-openagenda-picture left">
            <a href="<?php echo $url ?>" <?php echo $target . $rel; ?>
               class="prefix-no-arrow">
                <img src="<?php echo $event['image'] ?>" width="303" height="178">
            </a>
        </div>
        <div class="p2p5-vc-element-openagenda-details right">
            <h3 class="p2p5-vc-element-openagenda-details-title">
                <a
                        href="<?php echo $url ?>" <?php echo $target . $rel; ?>
                        class="prefix-no-arrow"><?php echo $event['title'][ $lang ]; ?>
                </a>
            </h3>
			<?php echo $date ?>
			<?php echo $city ?>
			<?php echo $cat; ?>
			<p style="clear: both" ">
                <?php echo $event['description'][$lang]; ?>
            </p>
            <a class="readmore"
               href="<?php echo $url ?>"><?php echo $atts['agenda_text']; ?>
            </a>

        </div>

    </div>
    <?php
}

function vc_oa_display_ver( $atts, $event, $city, $date, $cat, $lang, $target='', $rel='', $url='' ){

	?>
    <div class="p2p5-vc-element-openagenda-single ver">
	    <?php echo $cat; ?>
        <div class="p2p5-vc-element-openagenda-picture top">
            <a href="<?php echo $url ?>" <?php echo $target . $rel; ?>
               class="prefix-no-arrow">
                <img src="<?php echo $event['image'] ?>" width="303" height="178">
            </a>
        </div>
        <div class="p2p5-vc-element-openagenda-details bottom">
			<?php echo $date ?>
			<?php echo $city ?>
            <h3 class="p2p5-vc-element-openagenda-details-title"><a
                        href="<?php echo $url ?>" <?php echo $target . $rel; ?>
                        class="prefix-no-arrow"><?php echo $event['title'][ $lang ]; ?></a>
            </h3>
        </div>

    </div>
	<?php
}