<?php

	function add_custom_meta_box_general_ad_search_grid() {  
		
		add_meta_box(  
			'ad_search_grid_main_page', // $id  
			'<i class="fa fa-th-list"></i> '.__('WP ProGrid',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__), // $title   
			'general_ad_search_grid_main_page', // $callback  
			'ad_general_search', // $page  
			'normal', // $context  
			'high');
	
		/*add_meta_box(  
			'ad_general_search_grid_general_setting', // $id  
			'<i class="fa fa-th-list"></i> '.__('General Setting',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__), // $title   
			'ad_general_search_grid_general_setting', // $callback  
			'ad_general_search', // $page  
			'normal', // $context  
			'high');
		
		add_meta_box( 
			'ad_general_search_grid_build_query', 
			'<i class="fa fa-clipboard"></i> '.__('Build Query',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'', 
			'ad_general_search_grid_build_query',
			'ad_general_search',
			'normal',
			'high');		
			
		add_meta_box( 
			'ad_general_search_grid_fields_order_setting', 
			'<i class="fa fa-clipboard"></i> '.__('Custom Fields & Orders',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'', 
			'ad_general_search_grid_fields_order_setting',
			'ad_general_search',
			'normal',
			'high');
		
		add_meta_box( 
			'ad_general_search_grid_advence_setting', 
			'<i class="fa fa-clipboard"></i> '.__('Advanced Setting',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'', 
			'ad_general_search_grid_advence_setting',
			'ad_general_search',
			'normal',
			'high');
		
		add_meta_box( 
			'ad_general_search_grid_layout_setting', 
			'<i class="fa fa-clipboard"></i> '.__('Front-End Layout',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'', 
			'ad_general_search_grid_layout_setting',
			'ad_general_search',
			'normal',
			'high');		
		*/		
	}  
	add_action('add_meta_boxes', 'add_custom_meta_box_general_ad_search_grid');  
	
	///////////////////LIST OF FILDS VARIABLES//////////////////////////////////
		
	include  'customfields-fields-variables.php';
	
	///////////////////END LIST OF FIELDS VARIABLES/////////////////////////////

	
	/////////////////SHOW CUSTOM FIELD////////////////////////
	
	include  'customfields-fields-functions.php';	
	
	/////////////////END SHOW CUSTOM FIELD////////////////////
	
	
?>