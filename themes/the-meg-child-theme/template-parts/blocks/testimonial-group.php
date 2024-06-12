<?php
/**
 * Testimonial Group Block Template.
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

$allowed_blocks = array( 'acf/testimonial-item' );
?>
<div class="testimonial-block-wrapper <?php echo esc_attr($classes); ?>">
    <div class="swiper-outer">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php echo '<InnerBlocks allowedBlocks="' . esc_attr( wp_json_encode( $allowed_blocks ) ) . '" />'; ?>
            </div>            
        </div>

        <div class="navigation-wrapper">  
            <div class="swiper-button-prev">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="24" cy="24" r="24" fill="#FE5924"/>
                    <path d="M15 25.7321C13.6667 24.9623 13.6667 23.0377 15 22.2679L27 15.3397C28.3333 14.5699 30 15.5322 30 17.0718L30 30.9282C30 32.4678 28.3333 33.4301 27 32.6603L15 25.7321Z" fill="white"/>
                </svg>
            </div>
            <div class="swiper-pagination <?php echo $classPagination; ?>"></div>
            <div class="swiper-button-next">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="24" cy="24" r="24" fill="#FE5924"/>
                    <path d="M33 22.2679C34.3333 23.0377 34.3333 24.9623 33 25.7321L21 32.6603C19.6667 33.4301 18 32.4678 18 30.9282L18 17.0718C18 15.5322 19.6667 14.5699 21 15.3397L33 22.2679Z" fill="white"/>
                </svg>
            </div>
        </div>
    </div>

    <?php
    /*
    <div class="thumbswiper-container">
        <div class="swiper-wrapper">
        </div>
    </div>
    */
    ?>
</div>