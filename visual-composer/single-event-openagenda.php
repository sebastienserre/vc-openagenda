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
	function p2p5_vc_display_single( $atts, $city, $date, $cat, $description, $target, $rel, $decoded_body, $url,
        $img_size='' ) { ?>

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