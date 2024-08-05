<?php
	/*	 	  
	 * WORDPRESS: Functions
	 * Extend Wordpress built in functions and GeneratePress's functions.
	 */
	 
	/* 
	 * Table of contents:
	 * 1. Constants
	 * 2. Register Style/Script	
	 * 3. Modules
	 * 4. Prevent Render Blocking 
	 * 5. Quick Edit Link
	 * 6. GeneratePress - Default Settings 
	 * 7. GeneratePress - Change Burger Icon
	 * 8. Theme Setup	  
	 *    a. Dequeue Leaks
	 *	  b. Theme Support
	 *    c. Image Size
	 *    d. Sidebar
	 *	  e. Excerpt
	 * 9. Theme Customizer
	 * 10. Gutenberg - Register Block Style
	 * 11. Blog - AJAX
	 */

	// Constants
		define( 'THEMEDIR', get_template_directory() . '/' );
		define( 'THEMEURI', get_stylesheet_directory_uri() . '/' );
		
	// Register Style/Script
		// Frontend
			add_action( 'wp_enqueue_scripts', 'enqueue_list', 20 );
		
			function enqueue_list() {
				// Style											
					wp_enqueue_style( 'swiper-css',  THEMEURI . ( $css_path = 'assets/vendor/swiper/swiper-bundle.min.css' ) );										
					wp_enqueue_style( 'font-lato', 'https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap' );					
					wp_enqueue_style( 'default-css',  THEMEURI . ( $css_path = '/assets/scss/style.min.css' ), array( 'generate-style' ), get_version( $css_path ) );					
					
				// Script			
					wp_enqueue_script( 'jquery' );					
					wp_enqueue_script( 'swiper-js', THEMEURI . 'assets/vendor/swiper/swiper-bundle.min.js',  array( 'jquery' ), null, true );				
					wp_enqueue_script( 'default-js', THEMEURI . ( $js_path = '/assets/js/script.js' ), array( 'jquery' ), get_version( $js_path ), true );

					wp_add_inline_script('default-js', 'const HC =' . json_encode(array(
						'ajaxurl' => admin_url('admin-ajax.php'),
						'nonce' => wp_create_nonce('ajax-search-nonce'),
					)), 'before');	
			}		
	
		// Backend
			add_action('admin_enqueue_scripts', 'admin_enqueue_list', 22);
			
			function admin_enqueue_list( $hook_suffix ) 
			{		
				wp_enqueue_style( 'font-lato', 'https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap' );										
				wp_enqueue_style( 'admin-css', THEMEURI . ( $css_path_admin = '/assets/scss/style-admin.min.css' ), array(), get_version( $css_path_admin ) );

				if( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix || 'widgets.php' == $hook_suffix ) 
				{						
					wp_enqueue_style( 'gutenberg-css', THEMEURI . ( $css_path_gutenberg = '/assets/scss/style-gutenberg.min.css' ), array(), get_version( $css_path_gutenberg ) );
				}
			}

			add_action( 'enqueue_block_editor_assets', 'block_editor_scripts' );

			function block_editor_scripts() {
				wp_enqueue_script( 'block-editor', THEMEURI . 'assets/js/editor.js', array( 'wp-blocks', 'wp-dom' ) );
			}			

		// Replaces cache busting versioning
			function get_version($relative_file_path) {
				return date("ymd-Gis", filemtime( get_stylesheet_directory() . $relative_file_path ));
			}

	// Modules
		include_once get_stylesheet_directory() . "/inc/modules/acf.php";
		include_once get_stylesheet_directory() . "/inc/modules/cpt.php";
			
	// Prevent Render Blocking
		add_filter( 'style_loader_tag', 'add_google_font_stylesheet_attributes', 10, 2 );
		
		function add_google_font_stylesheet_attributes( $html, $handle ) {
			if ( 'font-lato' === $handle ) 
			{
				return str_replace( "rel='stylesheet'", "rel='stylesheet' media='print' onload=\"this.media='all'\"", $html );
			}
			
			return $html;
		}

	// Quick Edit Link
		add_action('wp_footer', 'add_quick_edit_link');

		function add_quick_edit_link() {
			if ( ! current_user_can('administrator') || ( ! is_single() && ! is_front_page() ) ) {
				return;
			}

			global $post;
			$edit_link = html_entity_decode(get_edit_post_link($post->ID));

			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					var editLink = $('<a></a>');
					editLink.attr('href', '<?php echo $edit_link; ?>');
					editLink.text('Quick Edit');
					editLink.css({
						'float': 'right',
						'backgroundColor': '#fff',
						'border': '1px solid #ccc'
					});

					$('#wpadminbar .quicklinks').prepend(editLink);
				});
			</script>
			<?php
		}		
		
	// GeneratePress - Default Settings
		if ( ! function_exists( 'generate_get_defaults' ) ) {
			/**
			 * Set default options
			 *
			 * @since 0.1
			 */
			 
			function generate_get_defaults() {
				
				return apply_filters(
					'generate_option_defaults',
					array(
						'hide_title' => true,
						'hide_tagline' => true,
						'logo' => '',
						'inline_logo_site_branding' => false,
						'retina_logo' => '',
						'logo_width' => '',
						'top_bar_width' => 'full',
						'top_bar_inner_width' => 'contained',
						'top_bar_alignment' => 'right',
						'container_width' => '924',
						'container_alignment' => 'text',
						'header_layout_setting' => 'fluid-header',
						'header_inner_width' => 'contained',
						'nav_alignment_setting' => is_rtl() ? 'right' : 'left',
						'header_alignment_setting' => is_rtl() ? 'right' : 'left',
						'nav_layout_setting' => 'fluid-nav',
						'nav_inner_width' => 'contained',
						'nav_position_setting' => 'nav-float-right',
						'nav_drop_point' => '',
						'nav_dropdown_type' => 'hover',
						'nav_dropdown_direction' => is_rtl() ? 'left' : 'right',
						'nav_search' => 'disable',
						'nav_search_modal' => true,
						'content_layout_setting' => 'separate-containers',
						'layout_setting' => 'right-sidebar',
						'blog_layout_setting' => 'right-sidebar',
						'single_layout_setting' => 'right-sidebar',
						'post_content' => 'excerpt',
						'footer_layout_setting' => 'fluid-footer',
						'footer_inner_width' => 'contained',
						'footer_widget_setting' => '3',
						'footer_bar_alignment' => 'right',
						'back_to_top' => '',
						'background_color' => 'var(--base-2)',
						'text_color' => 'var(--contrast)',
						'link_color' => 'var(--accent)',
						'link_color_hover' => 'var(--contrast)',
						'link_color_visited' => '',
						'font_awesome_essentials' => true,
						'icons' => 'svg',
						'combine_css' => true,
						'dynamic_css_cache' => true,
						'structure' => 'flexbox',
						'underline_links' => 'always',
						'font_manager' => array(),
						'typography' => array(),
						'google_font_display' => 'auto',
						'use_dynamic_typography' => true,
						'global_colors' => array(
							array(
								'name' => __( 'Blue Blues', 'generatepress' ),
								'slug' => 'primary',
								'color' => '#203AAF',
							),
							array(
								'name' => __( 'Blue Blues 80%', 'generatepress' ),
								'slug' => 'primary80',
								'color' => '#4d61bf',
							),
							array(
								'name' => __( 'Blue Blues 60%', 'generatepress' ),
								'slug' => 'primary60',
								'color' => '#7989cf',
							),
							array(
								'name' => __( 'Blue Blues 20%', 'generatepress' ),
								'slug' => 'primary20',
								'color' => '#d2d8ef',
							),
							array(
								'name' => __( 'Orange Jazz', 'generatepress' ),
								'slug' => 'secondary',
								'color' => '#FE5924',
							),
							array(
								'name' => __( 'Orange Jazz 80%', 'generatepress' ),
								'slug' => 'secondary80',
								'color' => '#fe7a50',
							),
							array(
								'name' => __( 'Orange Jazz 40%', 'generatepress' ),
								'slug' => 'secondary40',
								'color' => '#ffbda7',
							),
							array(
								'name' => __( 'Sky Disco', 'generatepress' ),
								'slug' => 'tertiary',
								'color' => '#82C5F5',
							),
							array(
								'name' => __( 'Sky Disco 60%', 'generatepress' ),
								'slug' => 'tertiary60',
								'color' => '#b4dcf9',
							),
							array(
								'name' => __( 'Sky Disco 20%', 'generatepress' ),
								'slug' => 'tertiary20',
								'color' => '#e6f3fd',
							),		
							array(
								'name' => __( 'Pale Hip Hop', 'generatepress' ),
								'slug' => 'quaternary',
								'color' => '#F4E6D6',
							),
							array(
								'name' => __( 'Pale Hip Hop 20%', 'generatepress' ),
								'slug' => 'quaternary20',
								'color' => '#fdfaf7',
							),
							array(
								'name' => __( 'Purple Rock', 'generatepress' ),
								'slug' => 'quintenary',
								'color' => '#722B7E',
							),
							array(
								'name' => __( 'Classic Country', 'generatepress' ),
								'slug' => 'foreground',
								'color' => '#252422',
							),
							array(
								'name' => __( 'Classic Country 60', 'generatepress' ),
								'slug' => 'foreground60',
								'color' => '#7c7c7a',
							),
							array(
								'name' => __( 'Background', 'generatepress' ),
								'slug' => 'background',
								'color' => '#f4f4f4',
							),

							array(
								'name' => __( 'Border', 'generatepress' ),
								'slug' => 'border',
								'color' => '#eeeeee',
							),	

							array(
								'name' => __( 'White Rock', 'generatepress' ),
								'slug' => 'white',
								'color' => '#ffffff',
							),			
							array(
								'name' => __( 'Black', 'generatepress' ),
								'slug' => 'black',
								'color' => '#000000',
							)																						
						)
					)
				);
			}
		}

	// Generate Press - Change Search and Burger Icon
		add_filter( 'generate_svg_icon', function( $output, $icon ) {
			if ( 'menu-bars' === $icon ) {
				//$output = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>';
				$output = '<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M3 12.5H21" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M3 6.5H21" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M3 18.5H21" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>';
			}
			else if ( 'search' === $icon ) {
				$output = '<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M29.6337 27.8662L22.1725 20.405C24.2058 17.9182 25.2054 14.7451 24.9648 11.5419C24.7241 8.33876 23.2615 5.35064 20.8795 3.19562C18.4974 1.04061 15.3782 -0.116413 12.167 -0.0361233C8.95584 0.0441664 5.89835 1.35563 3.62699 3.62699C1.35563 5.89835 0.0441664 8.95584 -0.0361233 12.167C-0.116413 15.3782 1.04061 18.4974 3.19562 20.8795C5.35064 23.2615 8.33876 24.7241 11.5419 24.9648C14.7451 25.2054 17.9182 24.2058 20.405 22.1725L27.8662 29.6337C28.102 29.8614 28.4177 29.9874 28.7455 29.9846C29.0732 29.9817 29.3867 29.8503 29.6185 29.6185C29.8503 29.3867 29.9817 29.0732 29.9846 28.7455C29.9874 28.4177 29.8614 28.102 29.6337 27.8662ZM12.5 22.5C10.5222 22.5 8.58876 21.9135 6.94427 20.8147C5.29978 19.7159 4.01805 18.1541 3.26118 16.3268C2.5043 14.4995 2.30627 12.4889 2.69212 10.5491C3.07797 8.60926 4.03038 6.82743 5.4289 5.4289C6.82743 4.03038 8.60926 3.07797 10.5491 2.69212C12.4889 2.30627 14.4995 2.5043 16.3268 3.26118C18.1541 4.01805 19.7159 5.29978 20.8147 6.94427C21.9135 8.58876 22.5 10.5222 22.5 12.5C22.497 15.1512 21.4425 17.693 19.5678 19.5678C17.693 21.4425 15.1512 22.497 12.5 22.5Z" fill="#222222"/></svg>';
			}			

			return $output;
		}, 10, 2 );

	// Theme Setup
		add_action( 'after_setup_theme', 'initial_setup', 12 );
	
		function initial_setup()
		{	
			// Dequeue Leaks
				if ( !is_admin() ) {
					add_action( 'wp_enqueue_scripts', 'dequeue_leaks');
					
					function dequeue_leaks() {
						wp_deregister_style('common');
					}				
				}

			// Remove GP inline styling - good tapi bermasalah di mobile menu
				//remove_action( 'wp_enqueue_scripts', 'generate_enqueue_dynamic_css', 50 );

			// Remove Comments
				remove_action( 'generate_after_do_template_part', 'generate_do_comments_template', 15);
		
			// Theme Support
				if ( function_exists( 'add_theme_support' ) ) {		
					add_theme_support( 'post-thumbnails', array( 'post' ) );
					add_theme_support( 'editor-styles' );
					add_theme_support( 'align-wide' );	
					
					// Non square custom logo
					add_theme_support( 'custom-logo', array(
						 'flex-height' => true,
						 'flex-width' => true
					) );						
			
					// Editor - Font Size
					add_theme_support(
						'editor-font-sizes',
						array(							
							array(
								'name'      => __( 'H6', 'generatepress' ),
								'shortName' => __( 'H6', 'generatepress' ),
								'size'      => 14,
								'slug'      => 'H6',
							),
							array(
								'name'      => __( 'H5', 'generatepress' ),
								'shortName' => __( 'H5', 'generatepress' ),
								'size'      => 18,
								'slug'      => 'H5',
							),
							array(
								'name'      => __( 'H4', 'generatepress' ),
								'shortName' => __( 'H4', 'generatepress' ),
								'size'      => 24,
								'slug'      => 'H4',
							),
							array(
								'name'      => __( 'H3', 'generatepress' ),
								'shortName' => __( 'H3', 'generatepress' ),
								'size'      => 32,
								'slug'      => 'H3',
							),
							array(
								'name'      => __( 'H2', 'generatepress' ),
								'shortName' => __( 'H2', 'generatepress' ),
								'size'      => 40,
								'slug'      => 'H2',
							),
							array(
								'name'      => __( 'H1', 'generatepress' ),
								'shortName' => __( 'H1', 'generatepress' ),
								'size'      => 50,
								'slug'      => 'H1',
							),
							array(
								'name'      => __( 'H0', 'generatepress' ),
								'shortName' => __( 'H0', 'generatepress' ),
								'size'      => 60,
								'slug'      => 'H0',
							),
							array(
								'name'      => __( 'H00', 'generatepress' ),
								'shortName' => __( 'H00', 'generatepress' ),
								'size'      => 80,
								'slug'      => 'H00',
							)
						)
					);
				}
		
			// Image Size
				if ( function_exists( 'add_image_size' ) ) {
					add_image_size( 'post', 1440 );
					add_image_size( 'thumb', 720 );
				}
				
			// Sidebar
				register_sidebar( array(
					'name'          => 'Donate Menu',
					'id'            => 'donate-sidebar',
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '<h4>',
					'after_title'   => '</h4>',
				) );

				// Remove GeneratePress Sidebar
				add_action( 'widgets_init', 'remove_generatepress_sidebars', 11 );

				function remove_generatepress_sidebars(){
					//unregister_sidebar( 'sidebar-1' );
					//unregister_sidebar( 'sidebar-2' );
					unregister_sidebar( 'header' );
					/*unregister_sidebar( 'footer-1' );
					unregister_sidebar( 'footer-2' );
					unregister_sidebar( 'footer-3' );
					unregister_sidebar( 'footer-4' );
					unregister_sidebar( 'footer-5' );
					unregister_sidebar( 'footer-bar' );
					unregister_sidebar( 'top-bar' );*/
				}
				
			// Excerpt
				function custom_excerpt_length( $length ) 
				{					
					return 15;
				}
				add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
		}	

	// Theme Customizer		
		// Alternate Logo
		add_action( 'customize_register', 'themecustomizer_alternate_logo' );
		
		function themecustomizer_alternate_logo($wp_customize)
		{ 
			$wp_customize->add_setting( 'alternate_logo', array(
				'default' => get_theme_file_uri('assets/img/selectlogo.png'), // Default
				'sanitize_callback' => 'esc_url_raw'
			));
		 
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'alternate_logo_control', array(
				'label' => 'Alternate Logo',
				'priority' => 9,
				'section' => 'title_tagline',
				'settings' => 'alternate_logo',
				'button_labels' => array(
									  'select' => 'Select logo',
									  'remove' => 'Remove',
									  'change' => 'Change logo',
								   )
			)));		 
		}		

	// Metabox		
		// PAGE Metabox
		add_action( 'add_meta_boxes', 'add_page_metabox' );
		add_action( 'save_post', 'page_metabox_save' );	
		
		function add_page_metabox() 
		{		
			add_meta_box(
				'page_metabox', 
				'Page Options',
				'page_metabox_html', 
				'page',
				'side',
				'low'
			);		
		}	
		
		function page_metabox_html( $post ) 
		{
			//$show_page_title = get_post_meta( $post->ID, '_show_page_title_meta_key', true );			
			//$transparent_menu = get_post_meta( $post->ID, '_transparent_menu_meta_key', true );
			//$header_style = get_post_meta( $post->ID, '_header_style_meta_key', true );
			$hide_margin = get_post_meta( $post->ID, '_hide_margin_meta_key', true );
			
			/*if ( empty( $transparent_menu ) ) {
				$transparent_menu = 'no';	
			}*/
			if ( empty( $hide_margin ) ) {
				$hide_margin = 'no';	
			}
			
			/*
				<div class="metabox-row">
					<label for="show_page_title_field">Show Page Title</label>
					<select name="show_page_title_field" id="show_page_title_field" class="postbox">
						<option value="yes" <?php selected( $show_page_title, 'yes' ); ?>>Yes</option>
						<option value="no" <?php selected( $show_page_title, 'no' ); ?>>No</option>
					</select>
				</div>	
			
				<div class="metabox-row">
					<label for="transparent_menu_field">Transparent Header</label>
					<select name="transparent_menu_field" id="transparent_menu_field">
						<option value="yes" <?php selected( $transparent_menu, 'yes' ); ?>>Yes</option>
						<option value="no" <?php selected( $transparent_menu, 'no' ); ?>>No</option>
					</select>
					<div class="metabox-description">
						Make header in this page transparent without background color<br>
						<?php //Alternate logo will be used if you upload it in theme customizer (Appearance - Customize - Site identity). ?>
					</div>
				</div>			
					

				<div class="metabox-row">
					<label for="header_style_field">Header style</label>
					<select name="header_style_field" id="header_style_field">
						<option value="default" <?php selected( $header_style, 'default' ); ?>>Default</option>
						<option value="transparent" <?php selected( $header_style, 'transparent' ); ?>>Transparent</option>
					</select>
					<div class="metabox-description">
						Choosing transparent will remove default title and make header floating.
						It's recommended to put an image/cover on wide/full width alignment as top most element.
					</div>
				</div>	
				*/
			?>
					
				<div class="metabox-row">
                    <label for="hide_margin_field">Remove top and bottom margin on this page</label>
                    <select name="hide_margin_field" id="hide_margin_field">
                        <option value="yes" <?php selected( $hide_margin, 'yes' ); ?>>Yes</option>
                        <option value="no" <?php selected( $hide_margin, 'no' ); ?>>No</option>
                    </select>	
					<div class="metabox-description">
						For more seamless page design. Use block cover as first and/or last block.
                    </div>					
                </div>
			<?php
		}		
		
		function page_metabox_save( $post_id ) 
		{			
			/*if ( array_key_exists( 'show_page_title_field', $_POST ) ) {
				update_post_meta(
					$post_id,
					'_show_page_title_meta_key',
					$_POST['show_page_title_field']
				);
			}
			
			if ( array_key_exists( 'transparent_menu_field', $_POST ) ) {
				update_post_meta(
					$post_id,
					'_transparent_menu_meta_key',
					$_POST['transparent_menu_field']
				);
			}

			if ( array_key_exists( 'header_style_field', $_POST ) ) {
				update_post_meta(
					$post_id,
					'_header_style_meta_key',
					$_POST['header_style_field']
				);
			}*/

			if ( array_key_exists( 'hide_margin_field', $_POST ) ) {
				update_post_meta(
					$post_id,
					'_hide_margin_meta_key',
					$_POST['hide_margin_field']
				);
			}
		}

		// Body Class 
		add_filter( 'body_class','page_options' );

		function page_options( $classes ) 
		{	
			if ( is_page() )
			{
				global $post;	
				$disable_header = get_post_meta( $post->ID, '_generate-disable-header', true );
				$disable_nav = get_post_meta( $post->ID, '_generate-disable-nav', true );
				$disable_secondary_nav = get_post_meta( $post->ID, '_generate-disable-secondary-nav', true );
				$disable_post_image = get_post_meta( $post->ID, '_generate-disable-post-image', true );
				$disable_headline = get_post_meta( $post->ID, '_generate-disable-headline', true );
				$disable_footer = get_post_meta( $post->ID, '_generate-disable-footer', true );	

				$transparent_menu = get_post_meta( $post->ID, '_transparent_menu_meta_key', true );
				$hide_margin = get_post_meta( $post->ID, '_hide_margin_meta_key', true );				

				if ( empty( $transparent_menu ) ) { $transparent_menu = 'no'; }
				if ( empty( $hide_margin ) ) { $hide_margin = 'no'; }
				
				if ( $disable_headline == true ) { $classes[] = 'no-page-title'; }
				if ( $transparent_menu == 'yes' ) {	$classes[] = 'transparent-header'; }	
				if ( $hide_margin == 'yes' ) {	$classes[] = 'hide-margin'; }
			}			
			
			return $classes;					
		}

	// Gutenberg - Register Block Style
		// Block - Column
		/*register_block_style(
			'core/list',
			array(
				'name'         => 'stylized',
				'label'        => 'Stylized',
				'style_handle' => 'stylized-style',
			)
		);*/

		// Block - Button
		register_block_style(
			'core/button',
			array(
				'name'         => 'outline-alt',
				'label'        => 'Outline',
				'style_handle' => 'outline-style',
			)
		);

		// Block - Paragraph
		register_block_style(
			'core/paragraph',
			array(
				'name'         => 'nomargin',
				'label'        => 'No Margin',
				'style_handle' => 'nomargin-style',
			)
		);	

	// Blog - AJAX
		add_action('wp_ajax_blog_loadmore', 'blog_loadmore');
		add_action('wp_ajax_nopriv_blog_loadmore', 'blog_loadmore');

		function blog_loadmore() {
			$paged = $_POST['page'];
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

			wp_reset_postdata();
			die();
		}	

	// ACF
		function my_acf_input_admin_footer() 
		{    
			?>
				<script type="text/javascript">
					(function($) {
						
						acf.add_filter('color_picker_args', function( args, $field ){		
							args.palettes = ['#203AAF','#4d61bf','#7989cf','#d2d8ef','#FE5924','#fe7a50','#ffbda7','#82C5F5','#b4dcf9' ,'#e6f3fd','#F4E6D6','#fdfaf7','#722B7E'];
							
							
							// return
							return args;
									
						});
						
					})(jQuery); 
				</script>
			<?php				
		}

		add_action('acf/input/admin_footer', 'my_acf_input_admin_footer');
?>
