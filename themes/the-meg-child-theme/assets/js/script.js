var $j = jQuery.noConflict();

var vpHeight = $j( window ).height();
var headerHeight = 0;
var headerEl;
var flexslider = { vars:{} };

$j(function(){	
	// Search
		$j( '.search-field' ).attr( 'placeholder', 'Search...' );

		$j( 'body' ).on( 'click', '.search-wrapper svg', function() {
			headerEl.toggleClass( 'searchopen' );
			
			$j( '.search-input' ).focus();
		});

	// Burger bar on click		
		$j( '.burger-wrapper' ).click( function() {
			$j( this )
				.toggleClass( 'active' )
				.siblings( '#site-navigation' ).toggleClass( 'active' );
		});
		
	// Sticky
		headerEl = $j( 'header' );
		if( headerEl.length && headerEl.hasClass( 'stickyheader' ) ) 
		{
			var topBar = $j( '.topbar-wrapper' ).outerHeight();			
			var headerBar = $j( '.header-wrapper' ).outerHeight();
			topBar = topBar ? topBar : 0;
			headerBar = headerBar ? headerBar : 0;
			headerHeight = topBar + headerBar;
			
			if( $j( '.stickyheader:not(.transparentheader)' ).length ) {			
				$j( 'body' ).css({ 'padding-top' : headerHeight });
			}
		}
		

	// To Top	
		$j( '#toTop, .toTop' ).click( function(e) {
			e.preventDefault();
			$j( 'html, body' ).animate({
				scrollTop: 0
			}, 2000);
		});		
	
	// Styling Input File
		$j( '#photo-attachment-trigger' ).each( function()
		{
			var attachmentFileInput = $j( this ).find( '#photo-attachment' );
			var attachmentLabel	= $j( this ).siblings( '#photo-attachment-label' );
			var attachmentLabelValue = attachmentLabel.children( 'span' ).html();

			attachmentFileInput.on( 'change', function( e ) {
				var fileName = '';

				if( this.files && this.files.length > 1 ) 
				{
					fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
				}
				else if( e.target.value )
				{
					fileName = e.target.value.split( '\\' ).pop();
				}

				if( fileName ) 
				{
					attachmentLabel.html( '<span>' + fileName + '</span><span id="photo-attachment-remove"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"></path></svg></span>' );

					var reader = new FileReader();				
					reader.onload = function (e) {
						$j( '#photo-attachment-preview' ).html( '<img src="' + e.target.result + '">').addClass( 'file-added' );
					}

					reader.readAsDataURL( this.files[0] );
				}
				else 
				{
					attachmentLabel.html( attachmentLabelValue );
				}
			});

			// FF fix
			attachmentFileInput
				.on( 'focus', function(){ attachmentFileInput.addClass( 'has-focus' ); })
				.on( 'blur', function(){ attachmentFileInput.removeClass( 'has-focus' ); });
		});

		$j( 'body' ).on( 'click', '#photo-attachment-remove', function() {
			var triggerEl = $j( this ).parent( '#photo-attachment-label' ).siblings( '#photo-attachment-trigger' );

			triggerEl.find( '#photo-attachment-preview' )
				.removeClass( 'file-added' )
				.empty();

			triggerEl.find( '#photo-attachment' ).val( '' );

			$j( this ).siblings( 'span' ).html( 'No file chosen' );
			$j( this ).remove();
		})

	// Styling Input Number 
		$j( '.inputqty-wrapper .input-minus' ).click( function() {
			var parentEl = $j( this ).parent( '.inputqty-wrapper' );
			var numberEl = parentEl.find( 'input[type=number]' );
			var finalNumber = parseInt( numberEl.val(), 10 ) - 1;
			if ( finalNumber < 1 ) {
				finalNumber = 1;
			}

			numberEl.val( finalNumber );
		})

		$j( '.inputqty-wrapper .input-plus' ).click( function() {
			var parentEl = $j( this ).parent( '.inputqty-wrapper' );
			var numberEl = parentEl.find( 'input[type=number]' );
			var finalNumber = parseInt( numberEl.val(), 10 ) + 1;

			numberEl.val( finalNumber );
		})

	// Woocommerce 
		// Checkout - move amazon pay button from woocommerce notification to below
			setTimeout( function() {
				$j( '#pay_with_amazon' ).prependTo( '.checkout-expresscheckout');
			}, 2000 );

	// CF7 
		// Focus on the first invalid input
			document.addEventListener('wpcf7invalid', function (event) {
				setTimeout(function () {
				$j('#' + event.detail.unitTag + ' .wpcf7-not-valid').eq(0).focus();
				}, 1000);
			}, false);

	// Block - Accordion
		$j( '.accordion-item-title' ).click( function() {
			var parentEl = $j( this ).closest( '.accordion-item-wrapper' );			

			if ( parentEl.hasClass( 'active' ) ) {				
				parentEl.find( '.accordion-item-content' ).slideUp(200);				
				parentEl.removeClass( 'active' );
			}
			else {				
				parentEl.find( '.accordion-item-content' ).slideDown(200);				
				parentEl.addClass( 'active' );
			}
		});

	// Block - Testimonial
		// Thumbslider Creation
		$j( '.testimonial-block-wrapper .swiper-container .swiper-wrapper .swiper-slide' ).each( function() {
			let initialName = $j( this ).find( '.testimonial-item-name-inner' ).html().trim().charAt(0);

			$j( this ).closest( '.testimonial-block-wrapper' ).find( '.thumbswiper-container .swiper-wrapper' ).append( '<div class="swiper-slide"><div class="thumbswiper-icon"><div class="thumbswiper-icon-inner">' + initialName + '</div></div></div>');
		});

		// Thumbslider Declaration
		var thumbSlider = new Swiper(".testimonial-block-wrapper .thumbswiper-container", {
			loop: true,
			spaceBetween: 30,
			slidesPerView: 9,
			freeMode: true,
			watchSlidesProgress: true,
			centeredSlides: true,
			centerInsufficientSlides: true
		});

		// Slider Declaration
		var testimonialSlider = new Swiper( '.testimonial-block-wrapper .swiper-container', {
			slidesPerView:1,
			spaceBetween:16,			
			speed:1000,
			loop:true,			
			autoplay:{delay:5000},			
			/*pagination:{
				el:'.swiper-pagination',
				clickable: true,
			},*/
			navigation:{
				enabled:true, 
				nextEl:".swiper-button-next",
				prevEl:".swiper-button-prev"
			},
			breakpoints:{
				/*1200:{					
					slidesPerView:4
				},*/
				992: {
					slidesPerView:3,
					spaceBetween:32,			
				},
				768:{					
					slidesPerView:2,
					spaceBetween:24
				}
			},
			/*thumbs: {
				swiper: thumbSlider,
			},*/
		});	

	// Block - Tab		
		// Tab Nav Creation
			$j( '.tab-block-wrapper:not(.blog-tab)' ).each( function() {
				var tabNavigationWrapper = $j( this ).find( '.tab-block-navigation-wrapper' );
				var tabContentWrapper = $j( this ).find( '.tab-block-content-wrapper' );
				var tabID = tabTitle = tabClass = '';

				tabContentWrapper.children( '.tab-item-wrapper' ).each( function( index ) {
					tabID = $j( this ).attr( 'data-id' );
					tabTitle = $j( this ).attr( 'data-title' );
					tabClass = '';

					if ( index == 0 ) { tabClass = 'active'; }

					tabNavigationWrapper.append( '<div class="tab-navigation ' + tabClass +'" data-id="' + tabID + '">' + tabTitle + '</div>' );
				});

				tabContentWrapper.children( '.tab-item-wrapper:first-child' ).addClass( 'active' );
			});

		// Tab Nav Click
			$j( 'body' ).on( 'click', '.tab-block-navigation-wrapper .tab-navigation:not(.tab-title)', function() {
				var tabWrapper = $j( this ).closest( '.tab-block-wrapper' );
				var tabContentWrapper = tabWrapper.find( '.tab-block-content-wrapper' );
				var tabID = $j( this ).attr( 'data-id' );

				$j( this ).addClass( 'active' ).siblings().removeClass( 'active' );
				tabContentWrapper.find( '.tab-item-wrapper[data-id="' + tabID +'"]').addClass( 'active' ).siblings().removeClass( 'active' );
			});	

	// Swiper		
		const newSlider = new Swiper( '.slider-block-wrapper .swiper-container', {
			slidesPerView:1,
			spaceBetween:40,			
			speed:2000,
			loop:true,			
			autoplay:{
				delay:2000
			},			
			pagination:{
				el:".swiper-pagination",
				type:"fraction",
				clickable: true,
			},
			navigation:{
				nextEl:".swiper-button-next",
				prevEl:".swiper-button-prev"
			},
			breakpoints:{
				767:{
					spaceBetween:40,
					slidesPerView:4
				}
			}
		});	

	// Blog - AJAX		
		var page = 2; 

		$j( '#loadmore' ).click(function() {
			var button = $j( this );
			var data = {
				action: 'blog_loadmore',
				page: page
			};

			$j.ajax({
				url: ajaxUrl,
				type: 'POST',
				data: data,
				beforeSend: function() {
					button.text('Loading...');
				},
				success: function( response ) {
					if( response ) 
					{
						$j( '.loadmore-wrapper' ).before( response );
						button.text( 'Load More' );
						page++;
					} 
					else 
					{
						button.text( 'No more posts' );
						button.prop( 'disabled', true );
					}
				}
			});
		});
}); 

$j( window ).resize( function() {	

});

$j( window ).scroll( function() {
	// Scroll to top	
		/*if ( $j( window ).scrollTop() > vpHeight ) {
			$j( '#toTop' ).fadeIn(400);
		}
		else {
			$j( '#toTop' ).fadeOut(400);	
		}*/
	
	// Sticky Header
		if( $j( '.stickyheader' ).length ) 
		{
			if ( $j( window ).scrollTop() > headerHeight ) {
				headerEl.addClass( 'onScroll' );
			}
			else {
				headerEl.removeClass( 'onScroll' );
			}
		}	
});