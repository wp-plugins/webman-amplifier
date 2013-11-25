<?php
/**
 * Countdown timer
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 *
 * @uses   $codes_globals['sizes']
 *
 * @param  string class
 * @param  string size
 * @param  string time
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'class' => '',
			'size'  => '',
			'time'  => ''
		) );
	$atts = shortcode_atts( $defaults, $atts, $this->prefix_shortcode . $shortcode );

//Validation
	//size
		$atts['size'] = trim( $atts['size'] );
		if ( in_array( $atts['size'], array_keys( $codes_globals['sizes'] ) ) ) {
			$atts['class'] .= ' size-' . $codes_globals['sizes'][$atts['size']];
		}
	//url
		$atts['time'] = strtotime( trim( $atts['time'] ) );
		if ( ! $atts['time'] || strtotime( 'now' ) > $atts['time'] ) {
			$atts['time'] = '';
		}
	//class
		$atts['class'] = trim( 'wm-countdown-timer ' . trim( $atts['class'] ) );
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', esc_attr( $atts['class'] ) );
	//labels
		$atts['labels'] = array(
				'weeks'   => __( 'Weeks', 'wm_domain' ),
				'days'    => __( 'Days', 'wm_domain' ),
				'hours'   => __( 'Hours', 'wm_domain' ),
				'minutes' => __( 'Minutes', 'wm_domain' ),
				'seconds' => __( 'Seconds', 'wm_domain' ),
			);
		$atts['labels'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_labels', $atts['labels'] );

//Helper variables
	$wm_countdown_timer_id = rand( 100, 999 );

//Output
	if ( $atts['time'] ) {

		$output = '<div class="' . $atts['class'] . '">
					<div id="wm-countdown-timer-' . $wm_countdown_timer_id . '">
						<div class="dash weeks_dash">
							<span class="dash_title">' . $atts['labels']['weeks'] . '</span>
							<div class="digit first">0</div>
							<div class="digit">0</div>
						</div>

						<div class="dash days_dash">
							<span class="dash_title">' . $atts['labels']['days'] . '</span>
							<div class="digit first">0</div>
							<div class="digit">0</div>
						</div>

						<div class="dash hours_dash">
							<span class="dash_title">' . $atts['labels']['hours'] . '</span>
							<div class="digit first">0</div>
							<div class="digit">0</div>
						</div>

						<div class="dash minutes_dash">
							<span class="dash_title">' . $atts['labels']['minutes'] . '</span>
							<div class="digit first">0</div>
							<div class="digit">0</div>
						</div>

						<div class="dash seconds_dash">
							<span class="dash_title">' . $atts['labels']['seconds'] . '</span>
							<div class="digit first">0</div>
							<div class="digit">0</div>
						</div>
					</div>
				</div>

			<script><!--
			jQuery( function() {
				if ( jQuery().countDown ) {
					jQuery( "#wm-countdown-timer-' . $wm_countdown_timer_id . '" ).countDown( {
						targetDate: {
							"day"   : ' . date( 'j', $atts['time'] ) . ',
							"month" : ' . date( 'n', $atts['time'] ) . ',
							"year"  : ' . date( 'Y', $atts['time'] ) . ',
							"hour"  : ' . date( 'G', $atts['time'] ) . ',
							"min"   : ' . intval( date( 'i', $atts['time'] ) ) . ',
							"sec"   : 0
						}
					} );
				}
			} );
			//--></script>';

		//Enqueue scripts
			if ( apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_enqueue_scripts', true ) ) {
				wp_enqueue_script( 'wm-shortcodes-lwtCountdown' );
			}
			do_action( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_enqueue_scripts' );

	} // /if $atts['time']

?>