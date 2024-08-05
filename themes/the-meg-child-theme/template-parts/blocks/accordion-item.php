<?php
/**
 * Accordion Item Block Template.
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

$template = array(
    array( 'core/paragraph', array(
        'placeholder' => 'Add Title',
        'className' => 'accordion-item-title',
        'lock' => array(
            'move'   => true,
            'remove' => true,
        ),        
    ) )
);

$title = get_field('title') ?: '';
$open = get_field('open') ?: false;

$classOpen = '';
$block = '';
if ( $open ) { 
    $classOpen = 'active'; 
    $block="style='display:block;'"; 
}
?>
<div class="accordion-item-wrapper <?php echo esc_attr($classes); ?> <?php echo $classOpen; ?>">    
    <div class="accordion-item-title">
        <?php echo $title; ?>
        <div class="accordion-plusminus">
        </div>
    </div>
    <div class="accordion-item-content" <?php echo $block; ?>>
        <?php echo '<InnerBlocks />'; ?>  
    </div>  
</div>