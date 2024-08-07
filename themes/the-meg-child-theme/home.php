<?php
	get_header();	
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;	    

    // GP Blog Settings
    $gp_blog_settings = get_option( 'generate_blog_settings' );

    // Archive
    $use_infinite_scroll = $gp_blog_settings['masonry'];
    $display_archive_post_thumb = $gp_blog_settings['post_image'];
    $display_date = $gp_blog_settings['date'];
    $display_author = $gp_blog_settings['author'];
    $display_categories = $gp_blog_settings['categories'];
    $display_tags = $gp_blog_settings['tags'];
    $display_comments_count = $gp_blog_settings['comments'];

    // Single Post
    $display_single_post_thumb = $gp_blog_settings['single_post_image'];
    $display_single_date = $gp_blog_settings['single_date'];
    $display_single_author = $gp_blog_settings['single_author'];
    $display_single_categories = $gp_blog_settings['single_categories'];
    $display_single_tags = $gp_blog_settings['single_tags'];
    $single_post_thumb_padding  = $gp_blog_settings['single_post_image_padding'];

    // Page
    $display_page_post_image = $gp_blog_settings['page_post_image'];

    // Blog only
    $blogID = get_option( 'page_for_posts' );    
    $blog_title = get_the_title($blogID);
?>

<?php
/*
    <div class="page-heading-wrapper">
        <div class="container">                                
            <h1 class="page-title"><?php echo $blog_title; ?></h1>
            <?php
                if ( function_exists('yoast_breadcrumb') ) {
                    yoast_breadcrumb( '<div class="yoast-breadcrumbs">','</div>' );
                }
            ?>                                
        </div>
    </div>
*/
?>
    <div id="primary" class="content-area">
        <main class="site-main" id="main">            
            <div class="entry-content">                 

                <?php                                  
                    $blogPage = get_post( $blogID );
                    setup_postdata( $blogPage );
                    echo apply_filters( 'the_content', $blogPage->post_content );
                ?>

                <div class="blog-list-wrapper">
                    <?php
                        /*$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => 8,
                            'paged' => $paged
                        );

                        $query = new WP_Query( $args );

                        if ( $query->have_posts() ) 
                        {
                            $year = '';
                            while ( $query->have_posts() ) 
                            {				
                                $query->the_post();

                                $postThumb = get_the_post_thumbnail_url( $post, 'full' );	
                                $dateLink = get_month_link( get_the_time('Y'), get_the_time('n') );	

                                if ( $year != get_the_date( 'Y' ) ) 
                                {
                                    if ( $year != '' ) {
                                        echo '</div>';
                                    }
                                    $year = get_the_date('Y');
                                    echo '<div class="blog-list-year">' . $year . '</div>';
                                    echo '<div class="blog-list">';
                                }
                                
                                ?>
                                    <div class="blog-list-content-wrapper">
                                        <?php
                                            if ( !empty( $postThumb ) )
                                            {
                                                ?>
                                                    <div class="blog-list-thumb">
                                                        <img src="<?php echo $postThumb; ?>">
                                                    </div>
                                                <?php
                                            }
                                        ?>
                                        <div class="blog-list-content">
                                            <div class="blog-list-date">
                                                <?php echo get_the_date( 'F j, Y' ); ?>
                                            </div>
                                            <div class="blog-list-title">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php echo get_the_title(); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                            }
                            echo '</div>';
                        }                        
                        wp_reset_postdata();*/

                        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

                        // Query for scheduled posts
                        $schedule_args = array(
                            'post_type' => 'post',
                            'posts_per_page' => -1, // Fetch all scheduled posts
                            'post_status' => 'future',
                            'orderby' => 'post_date',
                            'order' => 'ASC',
                        );

                        $schedule_query = new WP_Query( $schedule_args );

                        // Display scheduled posts
                        if ( $schedule_query->have_posts() ) {
                            echo '<div class="blog-list-year">Upcoming Events</div>';
                            echo '<div class="blog-list">'; 

                            while ( $schedule_query->have_posts() ) {
                                $schedule_query->the_post();
                                global $post;
                                $postThumb = get_the_post_thumbnail_url( $post, 'full' );	
                                $show_thumbnail_in_event_page = get_field( 'show_thumbnail_in_event_page' );
                                ?>
                                <div class="blog-list-content-wrapper">
                                    <?php
                                        if ( !empty( $postThumb ) && $show_thumbnail_in_event_page ) {
                                            ?>
                                                <div class="blog-list-thumb">
                                                    <img src="<?php echo $postThumb; ?>">
                                                </div>
                                            <?php
                                        }
                                    ?>
                                    <div class="blog-list-content">
                                        <div class="blog-list-date">
                                            <?php echo get_the_date( 'F j, Y' ); ?>
                                        </div>
                                        <div class="blog-list-title">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php echo get_the_title(); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            echo '</div>';
                        }

                        wp_reset_postdata();

                        // Query for regular posts
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => 8,
                            'paged' => $paged,
                        );

                        $query = new WP_Query( $args );

                        // Display regular posts
                        if ( $query->have_posts() ) {
                            $year = '';
                            while ( $query->have_posts() ) {
                                $query->the_post();
                                global $post;

                                $postThumb = get_the_post_thumbnail_url( $post, 'full' );	
                                $dateLink = get_month_link( get_the_time('Y'), get_the_time('n') );	

                                $show_thumbnail_in_event_page = get_field( 'show_thumbnail_in_event_page' );

                                if ( $year != get_the_date( 'Y' ) ) {
                                    if ( $year != '' ) {
                                        echo '</div>';
                                    }
                                    $year = get_the_date('Y');
                                    echo '<div class="blog-list-year">' . $year . '</div>';
                                    echo '<div class="blog-list">';
                                }

                                ?>
                                <div class="blog-list-content-wrapper">
                                    <?php
                                        if ( !empty( $postThumb ) && $show_thumbnail_in_event_page ) {
                                            ?>
                                            <div class="blog-list-thumb">
                                                <img src="<?php echo $postThumb; ?>">
                                            </div>
                                            <?php
                                        }
                                    ?>
                                    <div class="blog-list-content">
                                        <div class="blog-list-date">
                                            <?php echo get_the_date( 'F j, Y' ); ?>
                                        </div>
                                        <div class="blog-list-title">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php echo get_the_title(); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            echo '</div>';
                        }

                        wp_reset_postdata();
                    ?>
                    <div class="loadmore-wrapper">
                        <button id="loadmore" class="arrow-bottom">Load More</button>                            
                    </div>
                </div>
                    
                    <?php
                        /*$args = array(
                            'post_type' => 'post',
                            'posts_per_page' => get_option( 'posts_per_page' ),
                            'paged' => $paged
                        );

                        $query = new WP_Query( $args );		

                        $postCountArr =  wp_count_posts();
                        $postCount = $postCountArr->publish;  

                        if ( $query->have_posts() ) 
                        {
                            while ( $query->have_posts() ) 
                            {
                                $query->the_post();
                                global $post;

                                // Post Thumb
                                $postThumb = get_the_post_thumbnail_url( $post, 'thumb' );	

                                // Date
                                $dateLink = get_month_link( get_the_time('Y'), get_the_time('n') );	

                                // Cat                                                          
                                $news_url = get_permalink( get_option( 'page_for_posts' ) );                                        

                                $catEl = $catLink = '';
                                $primary_term_id = yoast_get_primary_term_id( 'category', $post );
                                $cat_id = '';
                                if ( $primary_term_id ) {										
                                    $catName = get_term( $primary_term_id )->name;
                                    $catLink = get_term_link( $primary_term_id );	
                                    $cat_id = $primary_term_id;
                                    
                                    $catEl = '<a href="' . $catLink . '" title="' . $catName . '">' . $catName . '</a>';
                                }      
                                else 
                                {                                                               
                                    $cats = get_the_category();
                                    $i = 0;
                                    
                                    foreach( $cats as $cat ) 
                                    {
                                        $catName = $cat->name;
                                        $catLink = get_term_link( $cat );
                                        $cat_id = $cat->term_id;

                                        if ( $i == 0 )
                                        {
                                            $catEl = '<a href="' . $catLink . '" title="' . $catName . '">' . $catName . '</a>';  
                                            break;
                                        }
                                        $i++;                                                                    
                                    }  
                                }  

                                ?>
                                    <div class="blog-post">
                                        <?php
                                            if ( $display_archive_post_thumb )
                                            {
                                                ?>
                                                    <div class="blog-thumb">
                                                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                                            <?php
                                                                if ( !empty( $postThumb ) )
                                                                {
                                                                    ?>																	
                                                                        <img src="<?php echo $postThumb; ?>" alt="<?php the_title(); ?>">																	
                                                                    <?php
                                                                }
                                                            ?>			
                                                        </a>
                                                    </div>
                                                <?php
                                            }
                                        ?>
                                        <div class="blog-content">
                                            <div class="blog-title">
                                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                                    <h3><?php the_title(); ?></h3>
                                                </a>
                                            </div>
                                            <div class="blog-meta">
                                                <?php
                                                    if ( $display_author )
                                                    {
                                                        ?>
                                                            <div class="blog-meta-author">
                                                                By <?php the_author_posts_link(); ?> 
                                                            </div>
                                                        <?php  
                                                    }

                                                    if ( $display_date )
                                                    {
                                                        ?>
                                                            <div class="blog-meta-date">                                        
                                                                <a href="<?php echo esc_url( $dateLink ); ?>" title="<?php the_date(); ?>" rel="bookmark">
                                                                    <time class="entry-date" datetime="<?php echo esc_attr(get_the_date('Y-m-j')); ?>">
                                                                        <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?>
                                                                    </time>
                                                                </a>
                                                            </div>
                                                        <?php
                                                    }

                                                    if ( $display_categories )
                                                    {
                                                        ?>
                                                            <div class="blog-meta-cat">
                                                                <?php echo $catEl; ?>
                                                            </div>
                                                        <?php
                                                    }
                                                ?>
                                            </div>     
                                            <div class="blog-excerpt">
                                                <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                                            </div>      
                                        </div>
                                    </div>
                                <?php
                            }
                        } 
                                    
                        wp_reset_postdata();	*/				
                    ?>


                <?php
                /*

                <div class="paging-numbered">
                    <?php	                
                        echo paginate_links( array(
                            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                            'total'        => $query->max_num_pages,
                            'current'      => max( 1, get_query_var( 'paged' ) ),
                            'format'       => '?paged=%#%',
                            'show_all'     => false,
                            'type'         => 'plain',
                            'end_size'     => 2,
                            'mid_size'     => 1,
                            'prev_next'    => true,
                            'prev_text'    => sprintf( '%1$s', __( '<svg width="100%" height="100%" viewBox="0 0 9 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
        <g transform="matrix(-1,0,0,1,12.05,-1.85)"><path d="M4.111,2.682L10.828,9.4C10.828,9.4 4.111,16.118 4.111,16.118C3.916,16.313 3.916,16.629 4.111,16.825C4.306,17.02 4.623,17.02 4.818,16.825L11.889,9.754C12.084,9.558 12.084,9.242 11.889,9.046L4.818,1.975C4.623,1.78 4.306,1.78 4.111,1.975C3.916,2.171 3.916,2.487 4.111,2.682Z" style="fill:rgb(205,176,125);"/></g></svg>
    ', 'generatepress' ) ),
                            'next_text'    => sprintf( '%1$s', __( '<svg width="100%" height="100%" viewBox="0 0 9 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">    <g transform="matrix(1,0,0,1,-3.95,-1.85)"><path d="M4.111,2.682L10.828,9.4C10.828,9.4 4.111,16.118 4.111,16.118C3.916,16.313 3.916,16.629 4.111,16.825C4.306,17.02 4.623,17.02 4.818,16.825L11.889,9.754C12.084,9.558 12.084,9.242 11.889,9.046L4.818,1.975C4.623,1.78 4.306,1.78 4.111,1.975C3.916,2.171 3.916,2.487 4.111,2.682Z" style="fill:rgb(205,176,125);"/></g></svg>
    ', 'generatepress' ) ),
                            'add_args'     => false,
                            'add_fragment' => '',
                        ) );
                    ?>
                </div>
                */
                ?>
            </div>
        </main>

        <?php
            generate_construct_sidebars();
        ?>
    </div>

<?php
	get_footer();    
?>
