<?php
/*
Plugin Name: WP ProGrid : Ajax Post/Custom Post Display+Filter
Plugin URI: http://proword.net/woogrid/
Author: Proword
Author URI: http://proword.net/
Version: 2.4
Description: Create unlimited advanced beautiful grids & lists with professional custom ajax filters , search and sort.
Text Domain: pw_general_ad_search_grid
*/

/*
V 2.4 :
	Update : Compatible with Woocommerce 3.4.x>
	Update : Compatible with PHP 7.2

	V 2.3 :
	Update : Compatible with woocommerc 3.0>
	Added : Add new option for select the category, for appear for each items
	Added : Add new option for dropdown filter issue
	Fixed : Grid Options
*/

if(!defined('__PW_ROOT_GENERAL_AD_SEARCH__')){
	define('plugin_dir_url_pw_general_ad_search', plugin_dir_url( __FILE__ ));
	define( '__PW_ROOT_GENERAL_AD_SEARCH__', dirname(__FILE__));
	define( '__PW_GENERAL_AD_SEARCH_CSS__', plugins_url('assets/css/',__FILE__));
	define( '__PW_GENERAL_AD_SEARCH_JS__', plugins_url('assets/js/',__FILE__));
	define ('__PW_GENERAL_AD_SEARCH_URL__',plugins_url('', __FILE__));
	define ('__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__', 'custom_' );
	define ('__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__', 'pw_general_ad_search_grid' );
	define('__PW_GENERAL_AD_SEARCH_IMG_PLACEHOLDER__', __PW_GENERAL_AD_SEARCH_URL__."/assets/images/default-thumb.gif");

}
if(!class_exists('PW_GENERAL_AD_SEARCH'))
{

	require_once('classes/customepost.php');
	require_once('classes/customefields.php');
	require_once ('classes/func.php');
	require_once ('includes/shortcode/main.php');
	
	//CATEGORY IMAGE
	require_once ('classes/categories_image.php');
	
	//SEARCH FRAMEWORK
	require_once __PW_ROOT_GENERAL_AD_SEARCH__.'/classes/search_framework/search_framework.php';
	
	
	class PW_GENERAL_AD_SEARCH extends PW_GENERAL_SEARCH_FRAMEWORK
	{
		public $custom_field=array();
		var $module_dir;
		public $pw_general_search_post_type='';
		function __construct()
		{
			
			register_activation_hook( __FILE__ , array( $this, 'on_activation' ) );
			
			/////ALWAYS PUT IN FIRST ACTION INIT/////
			add_action('init', array ( $this , 'set_newuser_cookie' ) );
			///////////////
			
			add_action('init',array($this,'pw_general_grid_admin_init'));
			add_action('init',array($this,'frontend_init'));
			
		//	add_action('wp_footer', array($this,'add_to_footer'));
			
			//Widget Register
			add_action( 'widgets_init', array( $this, 'include_widgets' ) );
			
			//ADD TITLE SEARCH
			add_filter( 'posts_where', array($this,'search_title_func'), 10, 2 );

			add_action('admin_menu', array($this,'pw_register_my_custom_submenu_page'));
			//add_filter('add_to_cart_redirect', array($this,'custom_add_to_cart_redirect'));

			
			//USE OUR STYLE AS DEFAULT THEME
			if ( get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__."magic_page_use")== 'on') {
				add_action( 'template_redirect', array($this,'pw_redirect_page_template'),1 );	
				add_filter( 'term_link', array($this,'pw_convert_term_to_type'), 12, 3 );
				add_filter( 'the_title',array($this,'pw_page_title_change' ),10,2);	
				
			}
			//FOR ALL TAXONOMY	
			add_filter('query_vars', array($this,'parameter_queryvars') );	/**/
			
			//LOAD TEXTDOMAIN
			add_action( 'plugins_loaded', array( $this, 'loadTextDomain' ) );
		}	
		
		
		public function loadTextDomain() {
			load_plugin_textdomain( __PW_GENERAL_AD_SEARCH_TEXTDOMAIN__ , false, basename( dirname( __FILE__ ) ) . '/languages/' );
		}
		
		function parameter_queryvars( $qvars )
		{
			$qvars[] = 'pw_general_taxonomy';
			return $qvars;
		}
		
		function pw_convert_term_to_type( $link, $term, $taxonomy ) {
			if ( $term->taxonomy=== 'product_cat' ) {
				
				$pw_general_category_page=get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__."category_page");
				if($pw_general_category_page!=''){
					$pw_general_redirect_link = add_query_arg( array('product_cat'=>$term->slug,'pw_general_page'=>1), get_permalink($pw_general_category_page) );
					if ( !empty( $pw_general_redirect_link ) ) return $pw_general_redirect_link;
				}
			}
			if ( $term->taxonomy=== 'product_tag' ) {
				
				$pw_general_page_tag=get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__."tag_page");
				if($pw_general_page_tag!=''){
					$pw_general_redirect_link = add_query_arg( array('product_tag'=>$term->slug,'pw_general_page'=>1), get_permalink($pw_general_page_tag) );
					if ( !empty( $pw_general_redirect_link ) ) return $pw_general_redirect_link;
				}
			}
			
			return $link;
		}

		
		function pw_redirect_page_template()
		{
			global $wp_query,$woocommerce,$wp,$_chosen_attributes;
		
			$pw_general_post_type=$wp_query->query_vars['post_type'];
			$pw_general_taxonomy=isset($wp_query->query_vars['taxonomy']) ? $wp_query->query_vars['taxonomy'] :'';
			$pw_general_redirect_link='';
			
			if ($pw_general_taxonomy === 'product_cat') {
				$pw_general_category_page=get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__."category_page");
				if($pw_general_category_page!=''){
					$pw_general_query_args=array_merge( $wp_query->query, array( 'pw_general_page' => 1 ) );
					$pw_general_redirect_link = add_query_arg( $pw_general_query_args, get_permalink($pw_general_category_page) );
				}
			}elseif ( $pw_general_post_type === 'product' && is_post_type_archive('product')&& !is_single() ) {
				$pw_general_page_shop=get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__."shop_page");
				if($pw_general_page_shop!=''){
					$pw_general_query_args=array( 'pw_general_page' => 1 );
					$pw_general_redirect_link = add_query_arg( $pw_general_query_args ,get_permalink($pw_general_page_shop));
				}
			}elseif ( $pw_general_taxonomy === 'product_tag' ) {
				$pw_general_page_tag=get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__."tag_page");
				if($pw_general_page_tag!=''){
					$pw_general_query_args=array_merge( $wp_query->query, array( 'pw_general_page' => 1 ) );
					$pw_general_redirect_link = add_query_arg( $pw_general_query_args, get_permalink($pw_general_page_tag) );
				}
			}elseif(!empty($pw_general_taxonomy)){
				$pw_general_page_shop=get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__."taxonomy_page");
				if($pw_general_page_shop!=''){
					$term = get_term_by( 'slug', get_query_var('term'), $pw_general_taxonomy );
					$pw_sf_taxonomy_id=$term->term_id;
					$pw_general_query_args=array_merge( $wp_query->query, array('pw_general_taxonomy' => $pw_general_taxonomy  ,'pw_general_page' => 1) );
					$pw_general_redirect_link = add_query_arg( $pw_general_query_args ,get_permalink($pw_general_page_shop));
				}
			}
			
			if($pw_general_redirect_link){
				wp_redirect($pw_general_redirect_link);
			}	
		}
		
		function pw_page_title_change($title, $id ){
			$pw_general_is_admin=is_admin();
				if( ($id != get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__."search_page")&&
					$id != get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__."category_page")&&
					$id != get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__."tag_page"))||
					$pw_general_is_admin
					){ return $title;}
				if($id==get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__."shop_page")){
					return $title;
				}elseif($id==$cat_id=get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__."category_page") ){
					$pw_general_product_slug_category=get_query_var('product_cat');
					if(!empty($pw_general_product_slug_category) && !is_array($pw_general_product_slug_category))
					{
						$pw_general_cat_name=get_term_by('slug', $pw_general_product_slug_category, 'product_cat');
						return $pw_general_cat_name->name;
					}else
						return $title;
				}elseif($id==$tag_id=get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__."tag_page") ){
					$pw_general_product_slug_tag=get_query_var('product_tag');
					if(!empty($pw_general_product_slug_tag) && !is_array($pw_general_product_slug_tag))
					{
						$pw_general_tag_name=get_term_by('slug', $pw_general_product_slug_tag, 'product_tag');
						return $pw_general_tag_name->name;
					}else
						return $title;
				}elseif($id==get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__."search_page") ){
					return $title.' - '.(isset($_GET['pw_general_page']) ? $_GET['pw_general_page'] : "");
				}
		}

		function custom_add_to_cart_redirect() {
			 return get_permalink(get_option('woocommerce_cart_page_id'));
		}

		function set_newuser_cookie() 
		{
			
			if ( !is_admin() && !$this->is_login_page() && !isset($_COOKIE['pw_general_ad_search_favorit_cookie'])) {
				setcookie("pw_general_ad_search_favorit_cookie", '', time()+3600, COOKIEPATH, COOKIE_DOMAIN);
			}
			//setcookie("pw_general_ad_search_favorit_cookie", '', time()-3600, COOKIEPATH, COOKIE_DOMAIN);
			
		}

		
		function search_title_func( $where, $wp_query )
		{
			global $wpdb;
			if ( $wpse18703_title = $wp_query->get( 'search_title' ) ) {
				$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $wpse18703_title ) ) . '%\'';
			}
			return $where;
		}
		
		function pw_register_my_custom_submenu_page(){
			add_submenu_page('edit.php?post_type=ad_general_search', __('WP ProGrid Settings',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__), __('WP ProGrid Settings',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__), 'manage_options', 'ad_general_search_grid_setting', array($this,'pw_general_ad_search_setting'));

		}
		
		function pw_general_ad_search_setting(){
			include __PW_ROOT_GENERAL_AD_SEARCH__.'/classes/search-grid-options.php';
		}
		
		function is_login_page() {
			return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
		}
		
		function pw_general_grid_admin_init()
		{
			
			// activate addons one by one from modules directory
			/*foreach(glob($this->module_dir."/*.php") as $module)
			{
				require_once($module);
			}*/
			require_once('includes/admin-embed.php');
			
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {	
				require_once('includes/taxonomy_attribute.php');
			}
			
			
			//FAVORITE 
			if(!is_admin() && !$this->is_login_page()){
				add_shortcode('pw-general-ad-search-grid', array($this,'pw_general_ad_search_grid'));
				require_once(__PW_ROOT_GENERAL_AD_SEARCH__.'/includes/frontend-embed.php');
					
			}
		
		}// end aio_init		
		
		public function include_widgets() {
			include_once( 'classes/widget.php' );
			
		}	
		function add_to_footer()
		{
			if(get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_enable_favorite_use')=='on')
			{
				require_once(__PW_ROOT_GENERAL_AD_SEARCH__.'/includes/favorite.php');
			}
		}
		function frontend_init()
		{
			//require_once('includes/frontend-embed.php');
		}// end aio_init		
		
		
		function alert($type,$message)
		{
			switch($type)
			{
				case "error":
					return '<div class="message-cnt woo-err-msg"><i class="fa fa-times "></i><span>'.$message.'</span></div>';
				break;
				
				case "success":
					return '<div class="message-cnt woo-succ-msg"><i class="fa fa-check"></i><span>'.$message.'</span></div>';
				break;
			}
		}
		
		function check_isset($parameter,$type,$alternative_value)
		{
			switch($type)
			{
				case "theme_option":
					return ((isset($this->theme_option[$parameter]) ? $this->theme_option[$parameter]:$alternative_value));
				break;
				
				case "custom_field":
					return ((isset($this->custom_field[$parameter]) ? $this->custom_field[$parameter]:$alternative_value));
				break;
				
				case "taxonomy":
					return ((isset($this->custom_taxonomy[$parameter]) ? $this->custom_taxonomy[$parameter]:$alternative_value));
				break;
			}
			
		}
		
		function check_empty($parameter,$type,$alternative_value)
		{
			switch($type)
			{
				case "theme_option":
					return ((isset($this->theme_option[$parameter]) ? $alternative_value:$this->theme_option[$parameter]));
				break;
				
				case "custom_field":
					return ((isset($this->custom_field[$parameter]) ? $alternative_value:$this->custom_field[$parameter]));
				break;
				
				case "taxonomy":
					return ((isset($this->custom_taxonomy[$parameter]) ? $alternative_value:$this->custom_taxonomy[$parameter]));
				break;
			}
			
		}
		
		
		function isSerialized($str) {
			return ($str == serialize(false) || @unserialize($str) !== false);
		}
		
		function fetch_custom_fields($post_id)
		{
			$this->custom_field=array();
			$custom_fields = get_post_custom($post_id,true);
			if(is_array($custom_fields))
			{
				foreach ( $custom_fields as $key => $value ) {
					$this->custom_field[$key]=($this->isSerialized($value[0]) ? unserialize($value[0]):$value[0]);
				}
			}
			
		}
		
		function pw_general_ad_search_grid( $atts, $content = null ){
			add_action('wp_footer', array($this,'add_to_footer'));			

			/////////Front-End////////
			wp_enqueue_style(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'front-end-css');
			
			//////////LAYOUT//////////
			wp_enqueue_style(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'layout');
			
			//////////BOOTSTRAP//////////
			wp_enqueue_style(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'framework');			
			wp_enqueue_style(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'font-awesome-ccc');
			
			//////////////TOOLTIP//////////
			wp_enqueue_style(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'tooltip-style');
			
			////////////////////////SEARCH FRAMEWORK///////////////////////
			wp_enqueue_style( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'search-framework');
			
			//////////PRETTY MULTI SELECT/////////////
			wp_enqueue_style(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'multiselect');
			//wp_enqueue_style(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'multiselect-pretify',      __PW_GENERAL_AD_SEARCH_CSS__ . 'front-end/multiselect/prettify.css', array() , null);
						
			///////////////////////////////STICKY///////////////////////
			wp_enqueue_style(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'extra-button-style');
			wp_enqueue_style(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'scroller-style');
			
			
			/////////////////////////CSS CHOSEN///////////////////////
			wp_enqueue_style( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'chosen_css_1' );
			
			////////////////////BX- SLider//////////////////////////////
			wp_enqueue_style(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'bx-slider');
			
			////////////////////OWL- SLider//////////////////////////////
			wp_enqueue_style(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'general-owl-slider');
			wp_enqueue_style(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'general-owl-slider-theme');
			
			///////////////////LIGHTBOX//////////////////////////////
			wp_enqueue_style(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'general-lightbox-style');
				
			/////////////////////////PRICE SLIDER///////////////////////
			wp_enqueue_style( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'jquery-ui-style-slider');
			
			///////////////////POPUP STYLE//////////////////////////////
			wp_enqueue_style(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'popup-style');
			
			/////////////////////////CSS FORM STEP WIZARD///////////////////////
			wp_enqueue_style( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'form-step-css' );			
			wp_enqueue_style(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'tab-style');
			
			/////COLOR PICKKER//////
			//wp_enqueue_style( 'wp-color-picker' );
			
			/////JS ENQUEUE////////////
			//wp_enqueue_script('jquery');
			
			wp_enqueue_script('jquery-ui-core');
			//wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('jquery-ui-slider');
			
			//////////////////CHOSEN//////////////////////////
			wp_enqueue_script( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'chosen_js1' );
			
			//////////////////TOOLTIP//////////////////////////
			wp_enqueue_script( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'tooltip-jquery' );
			
			//////////////////DEPENDENCY//////////////////////////
			wp_enqueue_script( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'dependency' );
			
			//////////////////////////STICKY/////////////////////////
			wp_enqueue_script( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'extra-button-script');
			wp_enqueue_script( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'scroller-script');		
			
			//////////////////BOOTSTRAP - MULTI SELECT///////////////////
			wp_enqueue_script( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'boostrap');
			wp_enqueue_script( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'multiselect');
			wp_localize_script(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'multiselect','params',
				array(
					'placeholder' =>__('Search',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
					'select_all' =>__('Select All',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
					'select_none' =>__('None selected',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
					'selected' =>__('selected',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),
				)
			);
			
			//wp_enqueue_script( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'prettify', __PW_GENERAL_AD_SEARCH_JS__.'front-end/multiselect/prettify.js', array( 'jquery' ),true );
			
			//////////////////SVG////////////////////
			wp_enqueue_script(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'svg');
			
			//////////////////////BX- SLider////////////////////////
			wp_enqueue_script( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'bx-slider');
			
			//////////////////////OWL - SLider////////////////////////
			wp_enqueue_script( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'general-owl-slider');
			
			//////////////////LIGHTBOX////////////////////
			wp_enqueue_script(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'general-lightbox-script');
			
			//////////////////COOKIE//////////////////
			wp_enqueue_script( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'jquery-cookie');
			
			//////////////////Masonry////////////////////
			wp_enqueue_script(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'masonry');
			
			//////////////////POPUP SCRIPT////////////////////
			wp_enqueue_script(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'popup-script');
			wp_enqueue_script(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'easing');
	
			//////////////////Masonry////////////////////
			wp_enqueue_script(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'wait-image-load');
	
			/////////////////FORM STEP WIZARD JS//////////////////////////
			wp_enqueue_script( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'form-step-js');
	
			//////////////////EQUAL HEIGHT////////////////////
			wp_enqueue_script(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'js-equalheight');
	
			//////////////////CUSTOM JS////////////////////
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				wp_enqueue_script(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'front-custom-js');
				wp_localize_script( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'front-custom-js', 'parameters', array(
					'ajaxurl' => admin_url( 'admin-ajax.php'),
					'template_url' => __PW_GENERAL_AD_SEARCH_URL__,
					'popup_overlay_color' => (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'popup_overlay_color')!='' ? get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'popup_overlay_color'):"#333"),
					'woo_currency_symbol' 	=> get_woocommerce_currency_symbol(),
					'woo_currency_pos'      => get_option( 'woocommerce_currency_pos' ),
					
				) );
			}else
			{
				wp_enqueue_script(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'front-custom-js');
				wp_localize_script( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'front-custom-js', 'parameters', array(
					'ajaxurl' => admin_url( 'admin-ajax.php'),
					'template_url' => __PW_GENERAL_AD_SEARCH_URL__,
					'popup_overlay_color' => (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'popup_overlay_color')!='' ? get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'popup_overlay_color'):"#333"),
					'woo_currency_symbol' 	=> '$',
					'woo_currency_pos'      => 'left',
					
				) );
			}
			
			extract( shortcode_atts( array(
				'id'   => '',
			), $atts ) );
			$this->fetch_custom_fields($id);
			
			$shortcode_id=$id;
			
			require __PW_ROOT_GENERAL_AD_SEARCH__.'/frontend/search-form.php';
			return $final_output;
		}
		
		public function on_activation() {
			
			if(get_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_favorite_icon_type')=='')
			{
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'loading_type', 'loading_pack' );
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'loading_pack', 'fa-loading-1' );
				
				// -=> set loading image
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'loading_pack_font_icon', '#f7f7f7' );
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'loading_color', '#ffffff' );
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'popup_bg_color', '#ffffff' );
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'popup_overlay_color', '#ffffff' );
				
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_enable_favorite_use', 'on' );
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_enable_favorite_for', array('post'));
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'favorite_cnt_height', '400' );
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'favorite_cnt_width', '500' );
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_favorite_margin', '100' );
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_favorite_position', 'right' );
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_favorite_icon_type', 'fontawesome' );
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_favorite_font_icon', 'fa-heart' );
				
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_search_margin', '100' );
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_search_position', 'left' );
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_search_icon_type', 'fontawesome' );
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_search_font_icon', 'fa-search' );
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_search_height', '500' );
				
				update_option( __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_fields', array('name_from','name_to','email','description'));
				
				
				
				
			}
			
		}
		
		public function excerpt($text,$excerpt_length,$content_type='excerpt') {
			global $post;
			if(trim($excerpt_length)=='') $excerpt_length=10;
			$limit=$excerpt_length;
			if($content_type=='excerpt')	
			{
				$excerpt = explode(' ', $text, $limit);
				if (count($excerpt)>=$limit) {
					array_pop($excerpt);
					$excerpt = implode(" ",$excerpt).'...';
				} else {
					$excerpt = implode(" ",$excerpt);
				}	
				$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
				return $excerpt;
			}else{
				$content = explode(' ', $text, $limit);
				if (count($content)>=$limit) {
					array_pop($content);
					$content = implode(" ",$content).'...';
				} else {
					$content = implode(" ",$content);
				}	
				//REMOVE SHORTCODE
				//$content = preg_replace('/\[.+\]/','', $content);
				
				$content = apply_filters('the_content', $content); 
				$content = str_replace(']]>', ']]&gt;', $content);
				return $content;
			}
		}
		
		public function excerpts($text,$excerpt_length) {
			 global $post;

			  if ( '' == $text ) {
				$text = get_the_content('');
				$text = apply_filters('the_content', $text);
				$text = str_replace('\]\]\>', ']]&gt;', $text);
				$text = strip_tags($text);

				$words = explode(' ', $text, $excerpt_length + 1);

				if (count($words)> $excerpt_length) {
				  array_pop($words);
				  array_push($words, '[...]');
				  $text = implode(' ', $words);
				}
			  }else
			  {
				$text = str_replace('\]\]\>', ']]&gt;', $text);
				$text = strip_tags($text);

				$words = explode(' ', $text, $excerpt_length + 1);

				if (count($words)> $excerpt_length) {
				  array_pop($words);
				  array_push($words, '[...]');
				  $text = implode(' ', $words);
				}
			  }
			return $text;
		}
		
		public function get_category_tag( $id = 0, $taxonomy, $before = '', $sep = '', $after = '', $count='all', $exclude = array() ){
			$terms = get_the_terms( $id, $taxonomy );
	
			if ( is_wp_error( $terms ) )
				return $terms;
		
			if ( empty( $terms ) )
				return false;
		
			$counter=0;
			foreach ( $terms as $term ) {
				if($counter<$count || $count=='all'){	
					
					if(!in_array($term->term_id,$exclude)) {
						$link = get_term_link( $term, $taxonomy );
						if ( is_wp_error( $link ) )
							return $link;
						$term_links[] = '<a href="' . $link . '" rel="tag">' . $term->name . '</a>';
					}
					$counter++;
				}
			}
		
			$term_links = apply_filters( "term_links-$taxonomy", $term_links );
		
			return $before . join( $sep, $term_links ) . $after;
		}
	}
	
	$GLOBALS['pw_general_ad_main_class'] = new PW_GENERAL_AD_SEARCH;

}?>