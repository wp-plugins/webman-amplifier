/**
 * WebMan Posts shortcode scripts
 *
 * @package     WebMan Amplifier
 * @subpackage  Shortcodes
 *
 * @since       1.0
 */



jQuery( function() {



	//Set browser window width on resize
		var WMbrowserWidth = document.body.clientWidth;
		jQuery( window ).on( 'resize orientationchange', function() {
				WMbrowserWidth = document.body.clientWidth;
			} );



	/**
	 * Filterable posts
	 */

		if ( jQuery().isotope ) {

			var $filteredContent  = jQuery( '.filter-this' ),
			    isotopeLayoutMode = $filteredContent.data( 'layout-mode' );

			$filteredContent.each( function( e ) {
					var $this          = jQuery( this ),
					    itemWidthInner = $this.find( 'article:first-child' ).outerWidth();

					$this.width( '105%' ).find( 'article' ).width( itemWidthInner );
				} );

			function runIsotope() {
				$filteredContent.isotope( {
						layoutMode : isotopeLayoutMode
					} );

				//Filter items when filter link is clicked
				$filteredContent.prev( '.wm-filter' ).on( 'click', 'a', function() {
						var $this    = jQuery( this ),
						    selector = $this.data( 'filter' );

						$this.closest( '.wm-posts-wrap' ).find( '.filter-this' ).isotope( { filter: selector } );
						$this.parent( 'li' ).addClass( 'active' ).siblings( 'li' ).removeClass( 'active' );

						return false;
					} );
			} // /runIsotope

				function runIsotopeRTL() {
					$filteredContent.isotope( {
							layoutMode        : isotopeLayoutMode,
							transformsEnabled : false
						} );

					//Filter items when filter link is clicked
					$filteredContent.prev( '.wm-filter' ).on( 'click', 'a', function() {
							var $this    = jQuery( this ),
							    selector = $this.data( 'filter' );

							$this.closest( '.wm-posts-wrap' ).find( '.filter-this' ).isotope( {
									filter            : selector,
									transformsEnabled : false
								} );
							$this.parent( 'li' ).addClass( 'active' ).siblings( 'li' ).removeClass( 'active' );

							return false;
						} );
				} // /runIsotopeRTL


			if ( 'rtl' != jQuery( 'html' ).attr( 'dir' ) ) {
				if ( jQuery( 'html' ).hasClass( 'ie' ) ) {
					runIsotope();
				} else {
					$filteredContent.imagesLoaded( function() {
						runIsotope();
					} );
				}
			} else {
				//Modify Isotope's absolute position method
				jQuery.Isotope.prototype._positionAbs = function( x, y ) {
					return { right: x, top: y };
				}

				if ( jQuery( 'html' ).hasClass( 'ie' ) ) {
					runIsotopeRTL();
				} else {
					$filteredContent.imagesLoaded( function() {
						runIsotopeRTL();
					} );
				}
			} // /Isotope RTL languages support

		} // /isotope



	/**
	 * Masonry posts
	 */

		if ( jQuery().masonry ) {

			var $masonryContent = jQuery( '.masonry-this' );

			$masonryContent.each( function( e ) {
					var $this          = jQuery( this ),
					    itemWidthInner = $this.find( 'article:first-child' ).outerWidth();

					$this.width( '105%' ).find( 'article' ).width( itemWidthInner );
				} );

			$masonryContent.masonry();

		} // /masonry



	/**
	 * Sliding posts
	 */

		if ( jQuery().bxSlider ) {

			//Only desktops and tablets
			if ( 767 < WMbrowserWidth ) {
				jQuery( '[class*="scrollable-"' ).each( function( item ) {
					var $this                = jQuery( this ).find( '.wm-items-container' ),
					    itemScrollableWidth  = $this.children().eq( 0 ).outerWidth( true ),
					    itemScrollableMargin = itemScrollableWidth - $this.children().eq( 0 ).outerWidth(),
					    scrollableColumns    = ( $this.data( 'columns' ) ) ? ( $this.data( 'columns' ) ) : ( 3 ),
					    scrollableMove       = ( $this.hasClass( 'stack' ) ) ? ( scrollableColumns ) : ( 1 ),
					    scrollablePause      = ( $this.data( 'time' ) && 999 < $this.data( 'time' ) ) ? ( $this.data( 'time' ) ) : ( 4000 );

					$this.bxSlider( {
							auto        : $this.closest( '.wm-posts-wrap' ).hasClass( 'scrollable-auto' ),
							pause       : scrollablePause,
							minSlides   : scrollableColumns,
							maxSlides   : scrollableColumns,
							slideWidth  : itemScrollableWidth,
							slideMargin : itemScrollableMargin,
							moveSlides  : scrollableMove,
							pager       : false,
							autoHover   : true,
							useCSS      : false //this prevents CSS3 animation glitches in Chrome, but unfortunatelly adding a bit of overhead
						} );
				} );
			} // /only desktop and tablets

		} // /bxSlider



} );