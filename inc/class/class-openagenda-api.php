<?php
/**
 * Set of methods to retrieve data from OpenAgenda
 *
 * @author  : sebastienserre
 * @package Openagenda-api
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

/**
 * Retrieve Event data from OpenAgenda.com
 *
 * @package: Openagenda-api.
 */
class OpenAgendaApi {

	/**
	 * Get API stored in Options
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function thfo_openwp_get_api_key() {
		$key = get_option( 'openagenda_api' );

		return $key;
	}

	/**
	 * Get Slug from an URL.
	 *
	 * @param string $slug URL of openagenda.com Agenda.
	 *
	 * @return string
	 */
	public function get_slug( $slug, $agenda = false ) {

        if( ! $agenda ) {
	        $re = '/[a-zA-Z\.\/:]*\/([a-zA-Z\.\/:\0-_9]*)/';
	        preg_match( $re, $slug, $matches, PREG_OFFSET_CAPTURE, 0 );
	        $slug = untrailingslashit( $matches[1][0] );
        } else {
	        $re = '/[a-z0-9]+(?:-[a-z0-9-]+)/m';
	        //$str = 'https://openagenda.com/saint-jean-sainte-therese-murat/events/camp-montagne-priere-5608494?lang=fr';

	        preg_match_all($re, $slug, $matches, PREG_SET_ORDER, 0);
	        $slug = $matches[0][0];
        }

		return $slug;
	}

	/**
	 * Retrieve data from Openagenda
	 *
	 * @param string $slug Slug of your agenda.
	 * @param int    $nb   Number of event to retrieve. Default 10.
	 *
	 * @return array|mixed|object|string
	 */
	public function thfo_openwp_retrieve_data( $slug, $nb = 10, $past = 0, $single = false ) {

        if ( empty( $slug ) ) {
			return '<p>' . __( 'You forgot to add a slug of agenda to retrieve', 'vc-openagenda' ) . '</p>';
		}

		if ( ! $single ) {
			if ( empty( $nb ) ) {
				$nb = 10;
			}
			$key = $this->thfo_openwp_get_api_key();

			$uid = $this->openwp_get_uid( $slug );
			if ( $uid ) {
				$url          = add_query_arg(
					array(
						'key'         => $key,
						'limit'       => $nb,
						'oaq[passed]' => $past,
					),
					"https://openagenda.com/agendas/$uid/events.json"
				);
				$response     = wp_remote_get( $url );
				$decoded_body = array();

				if ( 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
					$body         = wp_remote_retrieve_body( $response );
					$decoded_body = json_decode( $body, true );
				} else {
					$decoded_body = '<p>' . __( 'Impossible to retrieve Events Data', 'vc-openagenda' ) . '</p>';
				}
			} else {
				$decoded_body = '<p>' . __( 'Impossible to retrieve Events Data', 'vc-openagenda' ) . '</p>';
			}
		} else {
            $agenda_slug = $this->get_slug( $slug, true );
            $agenda_uid = $this->get_agenda_uid( $agenda_slug );
            $event_uid = $this->get_event_uid( $this->get_event_slug( $slug ), $agenda_uid );
            $decoded_body = $this->get_event_details( $agenda_uid, $event_uid );

        }

		return $decoded_body;
	}

    public function get_event_details( $agenda_uid, $event_uid ){
	    $url          = add_query_arg(
		    array(
			    'key'         => $this->thfo_openwp_get_api_key(),
		    ),
		    "https://api.openagenda.com/v2/agendas/$agenda_uid/events/$event_uid"
	    );
	    $response     = wp_remote_get( $url );
	    $decoded_body = array();

	    if ( 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
		    $body         = wp_remote_retrieve_body( $response );
		    $decoded_body = json_decode( $body, true );
	    }
        return $decoded_body;
    }

    public function get_image( $image ){
        $image_url = $image['base'] . $image['filename'];
        return $image_url;
    }

	/**
	 * Retrieve OpenAgenda UID.
	 *
	 * @param mixed|string $slug OpenAgenda Agenda URL.
	 *
	 * @return mixed
	 */
	public function openwp_get_uid( $slug ) {
        if (empty( $slug ) ) {
	        $slug = $this->get_slug( $slug );
        }
        $slug = $this->get_slug( $slug, true );


		$uid  = get_option( 'openagenda_uid' );
		if ( ! empty( $uid ) ) {
			return $uid;
		}

		return false;
	}

    public function get_agenda_uid( $slug ){
        $uid = get_transient( "agenda_uid_$slug" );
        if ( ! empty( $uid ) ){
            return $uid;
        }
	    $url          = add_query_arg(
		    array(
			    'key'         => $this->thfo_openwp_get_api_key(),
                'slug[]' => $slug,
		    ),
		    'https://api.openagenda.com/v2/agendas'
	    );
	    $response     = wp_remote_get( $url );
	    $decoded_body = array();

	    if ( 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
		    $body         = wp_remote_retrieve_body( $response );
		    $decoded_body = json_decode( $body, true );
            foreach ( $decoded_body['agendas'] as $agendas ) {
	            if ( $slug === $agendas['slug']) {
		            $uid = $agendas['uid'];
                    set_transient( "agenda_uid_$slug", $uid, DAY_IN_SECONDS*180 );
	            }
            }
	    } else {
		    $uid = '<p>' . __( 'Impossible to retrieve Events Data', 'vc-openagenda' ) . '</p>';
	    }
        return $uid;
    }

    public function get_event_uid( $event_slug, $agenda_uid ){
// https://api.openagenda.com/v2/agendas/1202466/events/?key=6723b4fbe81f4758a778b06598e23839&slug[]=retraite-des-enfants-du-cp-au-cm2-8179853
        $event_uid = get_transient( "event_uid_$event_slug" );
	    if ( ! empty( $event_uid ) ){
		    return $event_uid;
	    }
	    $url          = add_query_arg(
		    array(
			    'key'         => $this->thfo_openwp_get_api_key(),
			    'slug[]' => $event_slug,
		    ),
		    "https://api.openagenda.com/v2/agendas/$agenda_uid/events/"
	    );
	    $response     = wp_remote_get( $url );
	    $decoded_body = array();

	    if ( 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
		    $body         = wp_remote_retrieve_body( $response );
		    $decoded_body = json_decode( $body, true );
            foreach ( $decoded_body['events'] as $event ){
                $event_uid = $event['uid'];
            }
            set_transient( "event_uid_$event_slug", $event_uid );
	    }
        return $event_uid;
    }
	/**
	 * Display a basic WordPress Widget.
	 *
	 * @param   object $openwp_data Object with OPenagenda Events data.
	 * @param   string $lang        Language code to display.
	 * @param   string $slug        OpenAgenda Agenda URL.
	 */
	public function openwp_basic_html( $openwp_data, $lang, $slug ) {
		?>
		<div class="openwp-events">
			<?php
			do_action( 'openwp_before_html' );
			$parsedown = new Parsedown();

			foreach ( $openwp_data['events'] as $events ) {
				?>
				<div class="openwp-event">
					<a class="openwp-event-link" href="<?php echo esc_url( $events['canonicalUrl'] ); ?>"
					   target="_blank">
						<p class="openwp-event-range"><?php echo esc_attr( $events['range'][ $lang ] ); ?></p>
						<?php
						if ( false !== $events['image'] ) {
							?>
							<img class="openwp-event-img" src="<?php echo esc_attr( $events['image'] ); ?>">
							<?php
						}
						?>
						<h3 class="openwp-event-title"><?php echo esc_attr( $events['title'][ $lang ] ); ?></h3>
						<p class="openwp-event-description"><?php echo $parsedown->text( esc_textarea( $events['description'][ $lang ] ) ); ?></p>
					</a>

				</div>
				<?php
			}
			do_action( 'openwp_after_html' );
			// translators: this is a link to add events in Openagenda.com.
			$text = sprintf( wp_kses( __( 'Have an Event to display here? <a href="%s">Add it!</a>', 'vc-openagenda' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $slug ) );
			$text = apply_filters( 'openwp_custom_add_event_text', $text );
			echo $text;

			?>
		</div>
		<?php
	}

	/**
	 * Method to display OpenAgenda Widget.
	 *
	 * @param int   $embed Embeds uid.
	 * @param int   $uid   Agenda UID.
	 * @param array $atts  Shortcode attributs.
	 *
	 * @return string
	 */
	public function openwp_main_widget_html__premium_only( $embed, $uid, $atts ) {
		switch ( $atts['widget'] ) {
			case 'general':
				$openagenda_code = '<iframe style="width:100%;" frameborder="0" scrolling="no" allowtransparency="allowtransparency" class="cibulFrame cbpgbdy" data-oabdy src="//openagenda.com/agendas/' . $uid . '/embeds/' . $embed . '/events?lang=fr"></iframe><script type="text/javascript" src="//openagenda.com/js/embed/cibulBodyWidget.js"></script>';
				break;
			case 'map':
				$openagenda_code = '<div class="cbpgmp cibulMap" data-oamp data-cbctl="' . $uid . '/' . $embed . '" data-lang="fr" data-count="' . $atts['agenda_nb'] . '" ></div><script type="text/javascript" src="//openagenda.com/js/embed/cibulMapWidget.js"></script>';
				break;
			case 'search':
				$openagenda_code = '<div class="cbpgsc cibulSearch" data-oasc data-cbctl="' . $uid . '/' . $embed . '|fr" data-lang="fr"></div><script type="text/javascript" src="//openagenda.com/js/embed/cibulSearchWidget.js"></script>';
				break;
			case 'categories':
				$openagenda_code = '<div class="cbpgct cibulCategories" data-oact data-cbctl="' . $uid . '/' . $embed . '" data-lang="fr"></div><script type="text/javascript" src="//openagenda.com/js/embed/cibulCategoriesWidget.js"></script>';
				break;
			case 'tags':
				$openagenda_code = '<div class="cbpgtg cibulTags" data-oatg data-cbctl="' . $uid . '/' . $embed . '"></div><script type="text/javascript" src="//openagenda.com/js/embed/cibulTagsWidget.js"></script>';
				break;
			case 'calendrier':
				$openagenda_code = '<div class="cbpgcl cibulCalendar" data-oacl data-cbctl="' . $uid . '/' . $embed . '|fr" data-lang="fr"></div><script type="text/javascript" src="//openagenda.com/js/embed/cibulCalendarWidget.js"></script>';

				break;
			case 'preview':
				$openagenda_code = '<div class="oa-preview cbpgpr" data-oapr data-cbctl="' . $uid . '|' . $atts['lang'] . '"><a href="' . $atts['url'] . '">Voir l\'agenda</a> 
</div><script src="//openagenda.com/js/embed/oaPreviewWidget.js"></script>';
				break;
		}

		ob_start();

		echo $openagenda_code;

		return ob_get_clean();
	}

	/**
	 * Retrieved OpenAgenda embed Code
	 *
	 * @param   int    $uid OpenAgenda ID.
	 * @param   string $key OpenAgenda API Key.
	 *
	 * @return array|WP_Error
	 */
	public function openwp_get_embed( $uid, $key ) {
		$embed = wp_remote_get( 'https://openagenda.com/agendas/' . $uid . '/settings.json?key=' . $key );
		if ( 200 === (int) wp_remote_retrieve_response_code( $embed ) ) {
			$body         = wp_remote_retrieve_body( $embed );
			$decoded_body = json_decode( $body, true );
		}
		$embed = $decoded_body['embeds'][0];

		return $embed;
	}

	/**
	 * Get OA Agenda slug name
	 *
	 * @param $uid
	 * @param $key
	 *
	 * @return array
	 */
	public function openwp_get_oa_slug( $uid, $key ) {
		$agenda = wp_remote_get( 'https://openagenda.com/agendas/' . $uid . '/events.json?lang=fr&key=' . $key );
		if ( 200 === (int) wp_remote_retrieve_response_code( $agenda ) ) {
			$body         = wp_remote_retrieve_body( $agenda );
			$decoded_body = json_decode( $body, true );
			foreach ( $decoded_body['events'] as $event ) {
				$slug          = $event['origin']['slug'];
				$org           = $event['origin']['uid'];
					$slugs[ $org ] = $slug;
			}

			$slugs = array_unique( $slugs );
		}

		return $slugs;
	}

	public function format_date( $event ){

		$nb_day = sizeof( $event['timings'] );
		$start  = strtotime( $event['timings'][0]['begin'] );
		$end    = strtotime( $event['timings'][ $nb_day - 1 ]['end'] );

		// check if same day
		$day_start = date_i18n( 'd', $start );
		$day_end = date_i18n( 'd', $end );

		if ( $day_start === $day_end ) {
			$date = date_i18n( 'd F', $start );
			$date = '<p class="p2p5-vc-element-openagenda-details-date">' . sprintf( __( 'On %s', 'vc-openagenda' ), $date ) . '</p>';
		}

		if ( $day_start !== $day_end ) {

			$start = date_i18n( 'd F', $start );
			$end   = date_i18n( 'd F', $end );

			$date = '<p class="p2p5-vc-element-openagenda-details-date">' . sprintf( __( 'from %1s to %2s', 'vc-openagenda' ), $start, $end ) . '</p>';
		}
        return $date;
	}

    public function get_event_slug( $url ){
	    $re = '/events\/([a-zA-Z\.\/:\0-_9]*)(\?\S)/';
	    preg_match( $re, $url, $matches, PREG_OFFSET_CAPTURE, 0 );
	    if ( empty( $matches ) ) {
		    $re = '/events\/([a-zA-Z\.\/:\0-_9]*)/';
		    preg_match( $re, $url, $matches, PREG_OFFSET_CAPTURE, 0 );
	    }

	    return untrailingslashit( $matches[1][0] );
    }

}

new OpenAgendaApi();
