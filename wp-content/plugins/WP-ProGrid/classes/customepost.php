<?php
	if ( ! function_exists( 'register_general_ad_search' ) ) 
	{
		add_action('init', 'register_general_ad_search');
		function register_general_ad_search() {
			$args = array(
				'description' => __('WP ProGrid',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
				'show_ui' => true,
				//'menu_position' => 25,
				'exclude_from_search' => true,
				'menu_icon' => 'dashicons-schedule',
				'labels' => array(
					'name'=> __('WP ProGrid',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
					'singular_name' => __('WP ProGrid',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
					'add_new' => __('Add WP ProGrid',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
					'add_new_item' => __('Add WP ProGrid',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
					'edit' => __('Edit WP ProGrid',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
					'edit_item' => __('Edit WP ProGrid',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
					'new-item' => 'New WP ProGrid',
					'view' => __('View WP ProGrid',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
					'view_item' => __('View WP ProGrid',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
					'search_items' => __('Search WP ProGrid',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
					'not_found' => __('No WP ProGrid Found',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
					'not_found_in_trash' => __('No WP ProGrid Found in Trash',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
					'parent' => __('Parent WP ProGrid',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__)
				),
				'public' => true,
				//'taxonomies' => array('propertytype'),
				'capability_type' => 'post',
				'hierarchical' => false,
				'rewrite' => true,
				'supports' => array('title'),
				'has_archive' => true,
				
			);
		
			register_post_type( 'ad_general_search' , $args );
		}
	}
	
	
	if ( ! function_exists( 'custom_add_property_columns_general_search' ) ) 
	{
		add_filter('manage_ad_general_search_posts_columns', 'custom_add_property_columns_general_search');
		function custom_add_property_columns_general_search($columns) {
			//unset($columns['title']);	
			unset($columns['date']);	
			//$columns['custom_tracking_no']= 'Tracking Number' ;
			$columns['pw_general_search_shortcode_result']= __('Shortcode',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__);
			return $columns;
		}
	}
	

	if ( ! function_exists( 'custom_render_post_columns_general_search' ) ) 
	{
		add_action('manage_posts_custom_column', 'custom_render_post_columns_general_search', 10, 2);
		function custom_render_post_columns_general_search($column_name, $id) {
	
			switch ($column_name) {
				
				case 'pw_general_search_shortcode_result':
					echo '[pw-general-ad-search-grid id="'.$id.'"]';
				break;	
		
			}
		}
	}
?>