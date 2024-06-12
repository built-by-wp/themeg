<?php
    /**
    * Slider Block Template.
    *
    * @param   array $block The block settings and attributes.
    * @param   string $content The block inner HTML (empty).
    * @param   bool $is_preview True during AJAX preview.
    * @param   (int|string) $post_id The post ID this block is saved to.
    */

    // Create class attribute allowing for custom "className" and "align" values.
    $classes = '';
    if( !empty($block['className']) ) {
        $classes .= sprintf( ' %s', $block['className'] );
    }
    if( !empty($block['align']) ) {
        $classes .= sprintf( ' align%s', $block['align'] );
    }

    $id = uniqid();
    $allowed_blocks = array( 'acf/slide' );   

    $slider_style = get_field( 'slider_style' ) ?: 'slider';    
    $pagination = get_field('pagination') ?: 'bullets';

    $navigation = get_field( 'navigation' ) ?: true;
    $navigation_position = get_field( 'navigation_position' ) ?: 'middle';

    $slide_per_view_count = get_field( 'slide_per_view_count' );
    $slide_per_view_desktop = $slide_per_view_count['#_slide_per_view_desktop'] ?: 5;
    $slide_per_view_tablet = $slide_per_view_count['#_slide_per_view_tablet'] ?: 3;
    $slide_per_view_mobile = $slide_per_view_count['#_slide_per_view_mobile'] ?: 1;

    $slide_gap = get_field( 'slide_gap' );
    $slide_gap_desktop = $slide_gap['gap_desktop'] ?: 50;
    $slide_gap_tablet = $slide_gap['gap_tablet'] ?: 25;
    $slide_gap_mobile = $slide_gap['gap_mobile'] ?: 16;        
?>
<div id="slider-<?php echo $id; ?>" class="slider-block-wrapper slider-style-<?php echo $slider_style; ?> slider-navigation-<?php echo $navigation_position; ?> <?php echo esc_attr($classes); ?>">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php echo '<InnerBlocks allowedBlocks="' . esc_attr( wp_json_encode( $allowed_blocks ) ) . '" />'; ?>
        </div>

        <div class="navigation-wrapper">  
            <?php                
                if ( $navigation != 'none' )
                {
                    ?>
                        <div class="swiper-button-prev">
                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="24" cy="24" r="24" fill="#FE5924"/>
                                <path d="M15 25.7321C13.6667 24.9623 13.6667 23.0377 15 22.2679L27 15.3397C28.3333 14.5699 30 15.5322 30 17.0718L30 30.9282C30 32.4678 28.3333 33.4301 27 32.6603L15 25.7321Z" fill="white"/>
                            </svg>
                        </div>  
                    <?php
                }
            ?>

            <?php
                if ( $pagination != 'none' )
                {
                    ?>
                        <div class="swiper-pagination <?php echo $classPagination; ?>"></div>
                    <?php
                }
            ?>

            <?php                
                if ( $navigation != 'none' )
                {
                    ?>
                        <div class="swiper-button-next">
                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="24" cy="24" r="24" fill="#FE5924"/>
                                <path d="M33 22.2679C34.3333 23.0377 34.3333 24.9623 33 25.7321L21 32.6603C19.6667 33.4301 18 32.4678 18 30.9282L18 17.0718C18 15.5322 19.6667 14.5699 21 15.3397L33 22.2679Z" fill="white"/>
                            </svg>
                        </div>
                    <?php
                }
            ?>
        </div>
    </div>
</div>

<script>
    var $j = jQuery.noConflict();

    $j(function(){	        
        const newSlider = new Swiper( '#slider-<?php echo $id; ?> .swiper-container', {
            slidesPerView:'auto',/*<?php echo $slide_per_view_mobile; ?>,*/
            spaceBetween:<?php echo $slide_gap_mobile; ?>,			
            speed:2000,
            loop:true,		
            autoplay:{
                delay:2000
            },
            <?php
                if ( $pagination != 'none' )
                {
                    ?>
                        pagination:{
                            el:".swiper-pagination",
                            <?php
                                if ( $pagination == 'fraction' )
                                {
                                    ?>
                                        type:"fraction",
                                    <?php
                                }
                            ?>                
                            clickable: true,
                        },
                    <?php
                }
            ?>

            <?php                
                if ( $navigation != 'none' )
                {
                    ?>
                        navigation:{
                            nextEl:".swiper-button-next",
                            prevEl:".swiper-button-prev"
                        },
                    <?php
                }
            ?>
            breakpoints:{
                767: {
                    spaceBetween:<?php echo $slide_gap_tablet; ?>,	
                    slidesPerView:<?php echo $slide_per_view_tablet; ?>                                       
                },
                991: {
                    spaceBetween:<?php echo $slide_gap_desktop; ?>,	
                    slidesPerView:<?php echo $slide_per_view_desktop; ?>                    
                }
            }
        });	
    });
</script>