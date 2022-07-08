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
	public function openwp_get_slug( $slug ) {
		$re = '/[a-zA-Z\.\/:]*\/([a-zA-Z\.\/:\0-_9]*)/';

		preg_match( $re, $slug, $matches, PREG_OFFSET_CAPTURE, 0 );

		$slug = untrailingslashit( $matches[1][0] );

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
	public function thfo_openwp_retrieve_data( $slug, $nb = 10, $past = 0 ) {
		if ( empty( $slug ) ) {
			return '<p>' . __( 'You forgot to add a slug of agenda to retrieve', 'vc-openagenda' ) . '</p>';
		}
		if ( empty( $nb ) ) {
			$nb = 10;
		}
        $key = $this->thfo_openwp_get_api_key();

		$uid = $this->openwp_get_uid( $slug );
		if ( $uid ) {
			$url = add_query_arg(
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

		return $decoded_body;
	}

	/**
	 * Retrieve OpenAgenda UID.
	 *
	 * @param mixed|string $slug OpenAgenda Agenda URL.
	 *
	 * @return mixed
	 */
	public function openwp_get_uid( $slug ) {
		$slug = $this->openwp_get_slug( $slug );
		$uid  = get_option( 'openagenda_uid' );
		if ( ! empty( $uid ) ) {
			return $uid;
		}

		return false;
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

	public function format_date( $start, $end ){
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

}

new OpenAgendaApi();
