<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

function p2p5_vc_openagenda_main_init() {

	vc_map( array(
			'name'        => __( 'Main Openagenda', 'vc-openagenda' ),
			'base'        => 'p2p5-vc-openagenda-main',
			'description' => __( 'Display Openagenda Script', 'vc-openagenda' ),
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
					'description' => __( 'URL to the OpenAgenda Agenda', 'vc-openagenda' ),
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', 'vc-openagenda' ),
				),

				array(
					'type'        => 'textfield',
					'holder'      => 'a',
					'class'       => 'url-class',
					'heading'     => __( 'Agenda Code', 'vc-openagenda' ),
					'param_name'  => 'agenda_code',
					'value'       => 'YYYYYYYY',
					'description' => __( 'OPenAgenda will give you an iframe code with this: data-oabdy src="//openagenda.com/agendas/XXXXXXXX/embeds/YYYYYYYY/events?lang=fr". What you need is to copy/past the YYYYYYYY (8 digits) ', 'vc-openagenda' ),
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', 'vc-openagenda' ),
				),

				array(
					'type'        => 'dropdown',
					'holder'      => 'p',
					'class'       => 'title-class',
					'heading'     => __( 'Widget Type', 'vc-openagenda' ),
					'param_name'  => 'openagenda_type',
					'value'       => array(
						__( 'Please select an OpenAgenda Widget', 'vc-openagenda' ) => 'nothing',
						__( 'General', 'vc-openagenda' )                            => 'general',
						__( 'Map', 'vc-openagenda' )                                => 'map',
						__( 'Search', 'vc-openagenda' )                             => 'search',
						__( 'Categories', 'vc-openagenda' )                         => 'categories',
						__( 'Tags', 'vc-openagenda' )                               => 'tags',
						__( 'Calendrier', 'vc-openagenda' )                         => 'calendrier',
						__( 'Preview Widget', 'vc-openagenda' )                     => 'preview',
						__( 'localisation', 'vc-openagenda' )                       => 'localisation',
					),
					'description' => __( 'Select the widget to display', 'vc-openagenda' ),
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', 'vc-openagenda' ),
				),
				array(
					'type'        => 'vc_link',
					'holder'      => 'p',
					'class'       => 'title-class',
					'heading'     => __( 'Agenda page in your Website', 'vc-openagenda' ),
					'param_name'  => 'openagenda_url',
					'description' => __( 'Select the page where the main agenda is', 'vc-openagenda' ),
					'admin_label' => false,
					'weight'      => 0,
					'group'       => __( 'Settings', 'vc-openagenda' ),
				),


			),
		)
	);

}

add_action( 'init', 'p2p5_vc_openagenda_main_init' );

function p2p5_vc_openagenda_main( $atts ) {
	$atts = shortcode_atts( array(
		'agenda_url'      => '',
		'title'           => '',
		'agenda_code'     => '',
		'openagenda_type' => 'nothing',
		'openagenda_url'  => '',
        'tags' => '',
        ),
		$atts, 'p2p5-vc-openagenda-main'
	);
	$widget = $atts['agenda_code'];
    $tags = '';
    if ( ! empty( $atts['tags'] ) ){
        $tags = '&oaq%5Btags%5D%5B0%5D=' . $atts['tags'];
    }

	$uid = get_option( 'openagenda_uid' );

	if ( $uid ) {

		ob_start();

		?>

        <div class="openagenda-main openagenda-main-<?php echo $atts['openagenda_type'] ?>">
            <?php
            if ( ! empty( $atts['title'] ) ) {
	            ?>
                <h2><?php echo $atts['title']; ?></h2>

	            <?php
            }
		switch ( $atts['openagenda_type'] ) {
			case 'general':
				?>
				<iframe style="width:100%;" frameborder="0" scrolling="no" allowtransparency="allowtransparency" class="cibulFrame cbpgbdy" data-oabdy src="//openagenda.com/agendas/<?php echo $uid ?>/embeds/<?php echo $widget?>/events?lang=fr<?php echo $tags; ?>"></iframe><script type="text/javascript" src="//openagenda.com/js/embed/cibulBodyWidget.js"></script>
				<?php
				break;
			case 'map':
				?>
                <div class="cbpgmp cibulMap widget-oa" data-oamp data-cbctl="<?php echo $uid ?>/<?php echo $widget ?>" data-lang="fr" ></div><script type="text/javascript" src="//openagenda.com/js/embed/cibulMapWidget.js"></script>
				<?php
				break;
			case 'search':
			    ?>
				<div class="cbpgsc cibulSearch widget-oa" data-oasc data-cbctl="<?php echo $uid ?>/<?php echo $widget;?>|fr" data-lang="fr"></div><script type="text/javascript" src="<?php echo P2P5_OPENAGENDA_URL ?>/assets/js/cibulSearchWidget.js"></script>
				<?php
				break;
			case 'categories':
			    ?>
				<div class="cbpgct cibulCategories widget-oa" data-oact data-cbctl="<?php echo $uid ?>/<?php echo $widget;?>" data-lang="fr"></div><script type="text/javascript" src="//openagenda.com/js/embed/cibulCategoriesWidget.js"></script>
				<?php
				break;
			case 'tags':
			    ?>
			<div class="cbpgtg cibulTags widget-oa" data-oatg data-cbctl="<?php echo $uid ?>/<?php echo $widget;?>"></div><script type="text/javascript" src="//openagenda.com/js/embed/cibulTagsWidget.js"></script>
				<?php
				break;

			case 'calendrier':
			    ?>
				<div class="cbpgcl cibulCalendar widget-oa" data-oacl data-cbctl="<?php echo $uid ?>/<?php echo $widget;?>|fr" data-lang="fr"></div><script type="text/javascript" src="//openagenda.com/js/embed/cibulCalendarWidget.js"></script>
				<?php
				break;
            }
		?>
        </div>
<?php

		return ob_get_clean();

	}
}

add_shortcode( 'p2p5-vc-openagenda-main', 'p2p5_vc_openagenda_main' );
