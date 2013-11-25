/**
 * WebMan Slideshow shortcode scripts
 *
 * @package     WebMan Amplifier
 * @subpackage  Shortcodes
 *
 * @since       1.0
 */



jQuery( function() {



	/**
	 * Slideshow
	 */

		if ( jQuery().bxSlider ) {

			if ( 'rtl' != jQuery( 'html' ).attr( 'dir' ) ) {
				var bxSliderAnimationMode = 'horizontal';
			} else {
				var bxSliderAnimationMode = 'fade';
			}

			jQuery( '.wm-slideshow' ).each( function( item ) {
				var $this       = jQuery( this ).find( '.wm-slideshow-container' ),
				    pause       = ( $this.parent().data( 'speed' ) ) ? ( $this.parent().data( 'speed' ) + 500 ) : ( 3500 ),
				    pager       = ( $this.parent().data( 'nav' ) ) ? ( true ) : ( false ),
				    pagerCustom = ( $this.data( 'pager' ) ) ? ( $this.data( 'pager' ) ) : ( null );

				$this.bxSlider( {
						mode           : bxSliderAnimationMode,
						pause          : pause,
						auto           : true,
						autoHover      : true,
						controls       : true,
						pager          : pager,
						pagerCustom    : pagerCustom,
						adaptiveHeight : true,
						useCSS         : false //this prevents CSS3 animation glitches in Chrome, but unfortunatelly adding a bit of overhead
					} );
			} );

		} // /bxSlider



} );