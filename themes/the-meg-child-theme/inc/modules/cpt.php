<?php
    // Custom Post Taxonomy
		// CPT - Program		
			add_action('init', 'register_cpt_program');
			
			function register_cpt_program() {
				register_post_type(
					'program',
					array(
						'labels' => array(
							'name' 			=> _x( 'Programs', 'generatepress' ),
							'singular_name' => _x( 'Program', 'generatepress' ),
							'menu_name' 	=> __( 'Programs', 'generatepress' ),					
							'add_new'		=> __( 'Add New Program', 'generatepress' ),
							'add_new_item'	=> __( 'Add New Program', 'generatepress' ),
							'edit_item'		=> __( 'Edit Program', 'generatepress' ),					
							'search_items'	=> __( 'Search Programs', 'generatepress' ),
							'not_found'		=> __( 'No Program found', 'generatepress' ),
							'not_found_in_trash' => __( 'No Program found in Trash', 'generatepress' ),					
						),
						'public'			=> true,
						'has_archive' 		=> false,
						'rewrite' 			=> array( 
							'slug' 			=> 'program',
							'with_front' 	=> false
						),
						'show_in_rest'		=> true,
						'supports' 			=> array( 
												'title', 
												'editor', 										
												'thumbnail',
												'revisions'
											),
						'menu_position' 	=> 5,
						'menu_icon' 		=> 'dashicons-welcome-learn-more',
						'can_export' 		=> true,
					)
				);
				
				// Add day archive (and pagination)
				add_rewrite_rule("program/([0-9]{4})/([0-9]{2})/([0-9]{2})/page/?([0-9]{1,})/?",'index.php?post_type=program&year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]','top');
				add_rewrite_rule("program/([0-9]{4})/([0-9]{2})/([0-9]{2})/?",'index.php?post_type=program&year=$matches[1]&monthnum=$matches[2]&day=$matches[3]','top');
				
				// Add month archive (and pagination)
				add_rewrite_rule("program/([0-9]{4})/([0-9]{2})/page/?([0-9]{1,})/?",'index.php?post_type=program&year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]','top');
				add_rewrite_rule("program/([0-9]{4})/([0-9]{2})/?",'index.php?post_type=program&year=$matches[1]&monthnum=$matches[2]','top');
				
				// Add year archive (and pagination)
				add_rewrite_rule("program/([0-9]{4})/page/?([0-9]{1,})/?",'index.php?post_type=program&year=$matches[1]&paged=$matches[2]','top');
				add_rewrite_rule("program/([0-9]{4})/?",'index.php?post_type=program&year=$matches[1]','top');
				
			}	
			
			// CPT Program - Columns List
			add_filter( 'manage_program_posts_columns', 'cpt_program_columns' );
			
			function cpt_program_columns( $programColumns )
			{
				$programColumns = array(
					'cb' => '<input type="checkbox">',
					//'article_featured_image' => __( 'Featured Image', 'generatepress' ),
					'title' => __( 'Title', 'generatepress' ),			
					'author' => __( 'Author', 'generatepress' ),
					'comments' => '<span class="vers"><div title="Comments" class="comment-grey-bubble"></div></span>',
					'date' => __( 'Date', 'generatepress' ) 
				);
				
				return $programColumns;
			}	
			
			// CPT Program - Custom Columns
			add_action( 'manage_posts_custom_column', 'cpt_program_custom_columns' );
			
			function cpt_program_custom_columns( $programColumns )
			{
				global $post;
			
				switch ( $programColumns )
				{
					case 'article_featured_image':			
						if ( has_post_thumbnail() )
						{
							the_post_thumbnail( 'medium' );
						}				
						break;
				}		
			} 
			
			// CPT Program - Category
			/*add_action( 'init', 'cpt_program_category', 0 );
		
			function cpt_program_category() {	 
				$labels = array(
					'name' => _x( 'Program Categories', 'generatepress' ),
					'singular_name' => _x( 'Category', 'generatepress' ),
					'search_items' =>  __( 'Search Categories' ),
					'all_items' => __( 'All Categories' ),
					'parent_item' => __( 'Parent Category' ),
					'parent_item_colon' => __( 'Parent Category:' ),
					'edit_item' => __( 'Edit Category' ), 
					'update_item' => __( 'Update Category' ),
					'add_new_item' => __( 'Add New Category' ),
					'new_item_name' => __( 'New Category Name' ),
					'menu_name' => __( 'Categories' ),
				); 	
				
				register_taxonomy(
					'program_category',
					array('program'), 
					array(
						'hierarchical' => true,
						'labels' => $labels,
						'show_ui' => true,
						'show_admin_column' => true,
						'show_in_rest'      => true,
						'query_var' => true,
						'rewrite' => array( 'slug' => 'program-category' ),
					)
				);
			}*/
			
			// CPT Program - Location
			/*add_action( 'init', 'cpt_program_location', 0 );
		
			function cpt_program_location() {	 
				$labels = array(
					'name' => _x( 'Program Locations', 'generatepress' ),
					'singular_name' => _x( 'Location', 'generatepress' ),
					'search_items' =>  __( 'Search Locations' ),
					'all_items' => __( 'All Locations' ),
					'parent_item' => __( 'Parent Location' ),
					'parent_item_colon' => __( 'Parent Location:' ),
					'edit_item' => __( 'Edit Location' ), 
					'update_item' => __( 'Update Location' ),
					'add_new_item' => __( 'Add New Location' ),
					'new_item_name' => __( 'New Location Name' ),
					'menu_name' => __( 'Locations' ),
				); 	
				
				register_taxonomy(
					'program_location',
					array('program'), 
					array(
						'hierarchical' => true,
						'labels' => $labels,
						'show_ui' => true,
						'show_admin_column' => true,
						'show_in_rest'      => true,
						'query_var' => true,
						'rewrite' => array( 'slug' => 'program-location' ),
					)
				);
			}*/
?>