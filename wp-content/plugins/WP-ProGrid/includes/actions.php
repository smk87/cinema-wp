<?php
	//FETCH TAXONOMY IN BUILD QUERY IN ADMIN LIST
	add_action('wp_ajax_pw_general_ad_search_taxonomy_fetch', 'pw_general_ad_search_taxonomy_fetch');
	add_action('wp_ajax_nopriv_pw_general_ad_search_taxonomy_fetch', 'pw_general_ad_search_taxonomy_fetch');
	function pw_general_ad_search_taxonomy_fetch() {
		global $wpdb,$post;
		
		$param_line ='';
		$option_data='';
		$post_name=$_POST['post_selected'];
		$field_id=$_POST['field_id'];
				
		$option_data='';
		$param_line='';
		$in_option_data=$ex_option_data='';
		
		$all_tax=get_object_taxonomies( $post_name );
		//$all_tax = array_diff($all_tax,array('post_tag'));
		$original_query = $post;
		$current_value=array();
		if(is_array($all_tax) && count($all_tax)>0){
			$post_type_label=get_post_type_object( $post_name );
			$label=$post_type_label->label ; 
			$param_line .='<div class="header-lbl" style="display: block !important">'.$label.' '.__('Taxonomies ',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'</div>';
			
			//FETCH TAXONOMY
			foreach ( $all_tax as $tax ) {
				
				//if ('post_tag' === $taxonomy) continue;
				
				$taxonomy=get_taxonomy($tax);	
				$values=$tax;
				$label=$taxonomy->label;
	
				$checked='';
				if (isset($meta['taxonomy_checkbox']) &&  in_array($tax, $meta['taxonomy_checkbox']) ) $checked = ' checked="checked"';
				
				$param_line .=' 
				<div class="full-lbl-cnt more-padding" style="display: block;">
					<label class="full-label" >
						<input type="checkbox" data-input="post_type" value="'.$tax.'" id="pw_checkbox_'.$tax.'" name="'.$field_id.'[taxonomy_checkbox][]" class="pw_taxomomy_checkbox" '.$checked.'> 
						'.$label.'
					</label>';
					
	
					$param_line_exclude =$param_line_include = '<select name="'.$field_id.'[in_'.$tax.'][]" class="chosen-select-build" multiple="multiple" style="width: 531px;" data-placeholder="'.__('Choose Inclulde ',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).' '.$label.' ..." id="pw_'.$tax.'">';
					$param_line_exclude = '<select name="'.$field_id.'[ex_'.$tax.'][]" class="chosen-select-build" multiple="multiple" style="width: 531px;" data-placeholder="'.__('Choose Exclude',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).' '.$label.' ..." id="pw_'.$tax.'">';
					$args = array(
						'orderby'                  => 'name',
						'order'                    => 'ASC',
						'hide_empty'               => 1,
						'hierarchical'             => 1,
						'exclude'                  => '',
						'include'                  => '',
						'child_of'          	   => 0,
						'number'                   => '',
						'pad_counts'               => false 
					
					); 
	
					//$categories = get_categories($args); 
					$categories = get_terms($tax,$args);
					
					foreach ($categories as $category) {
						$selected='';
						if(isset($meta['in_'.$tax]) && is_array($meta['in_'.$tax]))
						{
							$selected=(in_array($category->term_id,$meta['in_'.$tax]) ? "SELECTED":"");
						}
						
						$option = '<option value="'.$category->term_id.'" '.$selected.'>';
						$option .= $category->name;
						$option .= ' ('.$category->count.')';
						$option .= '</option>';
						$param_line_include .= $option;

					}
					$param_line_include .='</select>';
					
					//$categories = get_categories($args); 
					$categories = get_terms($tax,$args);
					
					foreach ($categories as $category) {
						$selected='';
						if(isset($meta['ex_'.$tax]) && is_array($meta['ex_'.$tax]))
						{
							$selected=(in_array($category->term_id,$meta['ex_'.$tax]) ? "SELECTED":"");
						}
						
						$option = '<option value="'.$category->term_id.'" '.$selected.'>';
						$option .= $category->name;
						$option .= ' ('.$category->count.')';
						$option .= '</option>';
						$param_line_exclude .= $option;
					}
					$param_line_exclude .='</select>';
					$param_line .= $param_line_include.$param_line_exclude.'
				</div>';	
			}

		
		
			
		}else{
		//	$param_line=__('There is no Taxonomy/Category for this (Custom) post!',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__);
		}
		
		//CREATE INDIVIDUAL SELECT BOX
		$pw_post_id='';
		$args_post = array('post_type' => $post_name,'posts_per_page'=>-1);
		$loop_post = new WP_Query( $args_post );
		$in_option_data ='<optgroup label="'.$post_name.'">';
		$ex_option_data ='<optgroup label="'.$post_name.'">';
		while ( $loop_post->have_posts() ) : $loop_post->the_post();
			$selected='';
			if(isset($meta['in_ids']))
			{
				$selected=(in_array(get_the_ID(),$meta['in_ids']) ? "SELECTED":"");
			}
			$in_option_data.='<option '.$selected.' value="'.get_the_ID().'">'.get_the_title().'</option>';
			
			$selected='';
			if(isset($meta['ex_ids']))
			{
				$selected=(in_array(get_the_ID(),$meta['ex_ids']) ? "SELECTED":"");
			}
			$ex_option_data.='<option '.$selected.' value="'.get_the_ID().'">'.get_the_title().'</option>';
		endwhile;
		
		$post = $original_query;
		wp_reset_postdata();
		
		$in_option_data.='</optgroup>';
		$ex_option_data.='</optgroup>';
		
		if($ex_option_data!='' || $in_option_data!=''){
			$param_line .='<div class="header-lbl" style="display: block !important;">'.__('Individual Product(s)',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'</div>';
			$param_line .='<div class="full-lbl-cnt more-padding" style="display: block;">
				<select name="'.$field_id.'[in_ids][]" style="width: 531px;" class="chosen-select-build" multiple="multiple" data-placeholder="'.__('Choose Include Product(s) ...',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).' ..." id="pw_post_id">';
					$param_line.= $in_option_data.'
				</select>
						  ';	
			
			$param_line .='
				<select name="'.$field_id.'[ex_ids][]" style="width: 531px;" class="chosen-select-build" multiple="multiple" data-placeholder="'.__('Choose Exclude Product(s) ...',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).' ..." id="pw_post_id">';
					$param_line.= $ex_option_data.'
				</select>
			</div>';	
		}
		if($ex_option_data=='' && $in_option_data=='' && !is_array($all_tax) && count($all_tax)<=0){
			$param_line=__('There is no Taxonomy/Category for this (Custom) post!',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__);
		}
		
		
		$param_line.='
			<script type="text/javascript"> 
				jQuery(document).ready(function(){
					if(jQuery(".chosen-select-build").is(":visible")) {
						setTimeout(function(){
							if(jQuery(".chosen-select-build").is(":visible")) {
								jQuery(".chosen-select-build").chosen();
							}	
						},100);	
					}
				});
			</script>
		';
		
		
		echo $param_line;
		wp_die();
	}
	
	//FETCH TAXONOMY IN BUILD QUERY IN ADMIN LIST
	add_action('wp_ajax_pw_general_taxonomy_search_fields_items', 'pw_general_taxonomy_search_fields_items');
	add_action('wp_ajax_nopriv_pw_general_taxonomy_search_fields_items', 'pw_general_taxonomy_search_fields_items');
	function pw_general_taxonomy_search_fields_items() {
		global $wpdb,$post;
		
		$param_line ='';
		$option_data='';
		$post_name=$_POST['post_selected'];
		$field_id=$_POST['field_id'];
				
		$option_data='';
		$param_line='';
		$in_option_data=$ex_option_data='';
		
		$all_tax=get_object_taxonomies( $post_name );
		//$all_tax = array_diff($all_tax,array('post_tag'));
		$original_query = $post;
		$current_value=array();
		if(is_array($all_tax) && count($all_tax)>0){
			$post_type_label=get_post_type_object( $post_name );
			$label=$post_type_label->label ; 
			$param_line .='<div class="header-lbl" style="display: block !important">'.$label.' '.__('Taxonomies ',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'</div>';
			
			//FETCH TAXONOMY
			foreach ( $all_tax as $tax ) {
				
				if ('attrib' === $tax) continue;
				if ('post_tag' === $tax) continue;
				
				$taxonomy=get_taxonomy($tax);	
				$values=$tax;
				$label=$taxonomy->label;
	
				$checked='';
				if (isset($meta['taxonomy_checkbox']) &&  in_array($tax, $meta['taxonomy_checkbox']) ) $checked = ' checked="checked"';
				
				$param_line .=' 
				
				<label class="pw_showhide pw-magic-grid-fields-lbl" for="displayProduct-outofstock"><input name="'.$field_id.'['.$tax.']"  type="checkbox"  value="'.$tax.'">'.$label.'</label>
				';	
			}

		}else{
		 	$param_line=__('There is no Taxonomy/Category for this (Custom) post!',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__);
		}
		
		echo $param_line;
		wp_die();
	}
	
	//FETCH TAXONOMY IN CUSTOM FIELDS IN ADMIN LIST
	add_action('wp_ajax_pw_general_ad_search_taxonomy_fetch_customfields', 'pw_general_ad_search_taxonomy_fetch_customfields');
	add_action('wp_ajax_nopriv_pw_general_ad_search_taxonomy_fetch_customfields', 'pw_general_ad_search_taxonomy_fetch_customfields');
	function pw_general_ad_search_taxonomy_fetch_customfields() {
		global $wpdb,$post;
		
		$param_line ='';
		$option_data='';
		$post_name=$_POST['post_selected'];
		$field_id=$_POST['field_id'];
				
		$option_data='';
		$param_line='';
		$in_option_data=$ex_option_data='';
		
		
		$original_query = $post;
		
		$attribute_taxonomies_name_lbl=array();						
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			$attribute_taxonomies = wc_get_attribute_taxonomies();
			
			foreach($attribute_taxonomies as $attributes){
				
				$attr_label = ! empty( $attributes->attribute_label ) ? $attributes->attribute_label : $attributes->attribute_name;
				
				$attribute_taxonomies_name_lbl['pa_' .$attributes->attribute_name]['name']='pa_' .$attributes->attribute_name;
				$attribute_taxonomies_name_lbl['pa_' .$attributes->attribute_name]['lbl']=$attr_label;
			}
		}
		
		$all_tax=get_object_taxonomies( $post_name );
		$current_value=array();
		if(is_array($all_tax) && count($all_tax)>0){
			
			$post_type_label=get_post_type_object( $post_name );
			$label=$post_type_label->label ; 
			$param_line .='<div class="header-lbl">'.$label.' Taxonomies</div>';
			
			//FETCH TAXONOMY
			foreach ( $all_tax as $tax ) {
				$taxonomy=get_taxonomy($tax);	
				$values=$tax;
				$label=$taxonomy->label;
	
				$checked='';
				if (isset($meta['taxonomy_checkbox']) &&  in_array($tax, $meta['taxonomy_checkbox']) ) 
					$checked = ' checked="checked"';
				
				//$attribute_taxonomies = wc_get_attribute_taxonomies();
				$pw_display_type='';
				
				
				//if(array_key_exists($tax,$attribute_taxonomies_name_lbl)){
					
					
					$pw_display_type='
					<select id="pw_display_type_'.$tax.'" name="'.$field_id.'[taxonomy_display_type_'.$tax.'][]" class="pw_general_search_taxonomy_display_type" > 
						<option value="">'.__('Choose Display Type',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'</option>
						<optgroup label="'.__('Dropdown Types',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'">
							<option value="pw_tax_display_dropdown_lbl" '.(isset($meta['taxonomy_display_type_'.$tax]) &&  in_array('pw_tax_display_dropdown_lbl', $meta['taxonomy_display_type_'.$tax]) ? "SELECTED":"").'>'.__('Just Label',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'</option>
						
							<option value="pw_tax_display_dropdown_lbl_img" '.(isset($meta['taxonomy_display_type_'.$tax]) &&  in_array('pw_tax_display_dropdown_lbl_img', $meta['taxonomy_display_type_'.$tax]) ? "SELECTED":"").'>'.__('Label & Image',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'</option>
						</optgroup>
						
						<optgroup label="'.__('List Types',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'">
							<option value="pw_tax_display_inline_lbl" '.(isset($meta['taxonomy_display_type_'.$tax]) &&  in_array('pw_tax_display_inline_lbl', $meta['taxonomy_display_type_'.$tax]) ? "SELECTED":"").'>'.__('Just Label - Inline',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'</option>
						
							<option value="pw_tax_display_list_lbl" '.(isset($meta['taxonomy_display_type_'.$tax]) &&  in_array('pw_tax_display_list_lbl', $meta['taxonomy_display_type_'.$tax]) ? "SELECTED":"").'>'.__('Just Label - List',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'</option>
							
							<option value="pw_tax_display_inline_img" '.(isset($meta['taxonomy_display_type_'.$tax]) &&  in_array('pw_tax_display_inline_img', $meta['taxonomy_display_type_'.$tax]) ? "SELECTED":"").'>'.__('Just Image - Inline',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'</option>
						
							<option value="pw_tax_display_list_lbl_img" '.(isset($meta['taxonomy_display_type_'.$tax]) &&  in_array('pw_tax_display_list_lbl_img', $meta['taxonomy_display_type_'.$tax]) ? "SELECTED":"").'>'.__('Label & Image- List',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'</option>
							
						</optgroup>	
						
					</select>
					<input type="text" id="pw_label_'.$tax.'" name="'.$field_id.'[taxonomy_label_'.$tax.'][]" class="pw_general_search_taxonomy_label" placeholder="'.__('Custom Lable',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'"/>
					';
				//}
				
				$param_line .=' 
				<div class="full-lbl-cnt more-padding">
					<label class="full-label">
						<input type="checkbox" data-input="post_type" value="'.$tax.'" id="pw_checkbox_'.$tax.'" name="'.$field_id.'[taxonomy_checkbox][]" class="pw_taxomomy_checkbox" '.$checked.'>
						
						'.$label.'
					</label>
					'.$pw_display_type;
					
	
					$param_line_exclude =$param_line_include = '<select name="'.$field_id.'[in_'.$tax.'][]" class="chosen-select-search" multiple="multiple" style="width: 531px;" data-placeholder="'.__('Choose Inclulde ',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).' '.$label.' ..." id="pw_'.$tax.'">';
					$param_line_exclude = '<select name="'.$field_id.'[ex_'.$tax.'][]" class="chosen-select-search" multiple="multiple" style="width: 531px;" data-placeholder="'.__('Choose Exclude',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).' '.$label.' ..." id="pw_'.$tax.'">';
					$args = array(
						'orderby'                  => 'name',
						'order'                    => 'ASC',
						'hide_empty'               => 0,
						'hierarchical'             => 1,
						'exclude'                  => '',
						'include'                  => '',
						'child_of'          		 => 0,
						'number'                   => '',
						'pad_counts'               => false 
					
					); 
	
					//$categories = get_categories($args); 
					$categories = get_terms($tax,$args);
					
					foreach ($categories as $category) {
						$selected='';
						if(isset($meta['in_'.$tax]) && is_array($meta['in_'.$tax]))
						{
							$selected=(in_array($category->term_id,$meta['in_'.$tax]) ? "SELECTED":"");
						}
						
						$option = '<option value="'.$category->term_id.'" '.$selected.'>';
						$option .= $category->name;
						$option .= ' ('.$category->count.')';
						$option .= '</option>';
						$param_line_include .= $option;

					}
					$param_line_include .='</select>';
					
					//$categories = get_categories($args); 
					$categories = get_terms($tax,$args);
					
					foreach ($categories as $category) {
						$selected='';
						if(isset($meta['ex_'.$tax]) && is_array($meta['ex_'.$tax]))
						{
							$selected=(in_array($category->term_id,$meta['ex_'.$tax]) ? "SELECTED":"");
						}
						
						$option = '<option value="'.$category->term_id.'" '.$selected.'>';
						$option .= $category->name;
						$option .= ' ('.$category->count.')';
						$option .= '</option>';
						$param_line_exclude .= $option;
					}
					$param_line_exclude .='</select>';
					$param_line .= $param_line_include.$param_line_exclude.'
				</div>';	
			}
		
		
			//CREATE INDIVIDUAL SELECT BOX
			$pw_post_id='';
			$args_post = array('post_type' => $post_name,'posts_per_page'=>-1);
			$loop_post = new WP_Query( $args_post );
			$option_data.='<optgroup label="'.$post_name.'">';
			while ( $loop_post->have_posts() ) : $loop_post->the_post();
				$selected='';
				if(isset($meta['ids']))
				{
					$selected=(in_array(get_the_ID(),$meta['ids']) ? "SELECTED":"");
				}
				$option_data.='<option '.$selected.' value="'.get_the_ID().'">'.get_the_title().'</option>';
			endwhile;
			
			$post = $original_query;
			wp_reset_postdata();
			
			$option_data.='</optgroup>';
		}else{
			$param_line=__('There is no Taxonomy/Category for this (Custom) post!',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__);
		}
		
		$param_line.='
			<script type="text/javascript"> 
				jQuery(document).ready(function(){
					
					/*jQuery.when( jQuery(".chosen-select").chosen() ).done(function( x ) {
						jQuery(".chosen-container").each(function(){
							//jQuery(this).css({"width": "500px"});
						});
					});*/
					
				});
			</script>
		';
		
		echo $param_line;
		wp_die();
	
	}
	
	add_action('wp_ajax_pw_general_taxonomy_search_fields', 'pw_general_taxonomy_search_fields');
	add_action('wp_ajax_nopriv_pw_general_taxonomy_search_fields', 'pw_general_taxonomy_search_fields');
	function pw_general_taxonomy_search_fields() {
		extract($_POST);
		
		$all_tax=get_object_taxonomies( $post_selected );
		//$all_tax = array_diff($all_tax,array('post_tag'));
		
		$html ='';
		
		$current_value=array();
		if(is_array($all_tax) && count($all_tax)>0){
			//FETCH TAXONOMY
			foreach ( $all_tax as $tax ) {
				
				$taxonomy=get_taxonomy($tax);	
				$values=$tax;
				$label=$taxonomy->label;
				
				$html .=' 
				<label class="full-label" >
					<input type="checkbox" data-input="post_type" value="'.$tax.'" id="pw_checkbox_'.$tax.'" name="'.$field_id.'[]" class="pw_taxomomy_checkbox"> 
					'.$label.'
				</label>';
			}
		}
		
		echo $html;
		wp_die();
	}
	
	
	add_action('wp_ajax_pw_general_ad_search_preset_frontend', 'pw_general_ad_search_preset_frontend');
	add_action('wp_ajax_nopriv_pw_general_ad_search_preset_frontend', 'pw_general_ad_search_preset_frontend');
	function pw_general_ad_search_preset_frontend(){
		
		global $pw_general_ad_main_class;
		
		$xml_filename=$_POST['xml_filename'];
		
		if($_POST['source_type']=='from_xml'){
			$xml=simplexml_load_file(__PW_ROOT_GENERAL_AD_SEARCH__."/assets/demo-preset/".$xml_filename) or die("Error: Cannot create object");
			
			$xml_result=array();
			foreach($xml->postmeta as $xml_data){
				if($pw_general_ad_main_class->isSerialized($xml_data->meta_value))
				{
					$xml_result["{$xml_data->meta_key}"]=unserialize($xml_data->meta_value); 
				}else{
					$xml_result["{$xml_data->meta_key}"]="{$xml_data->meta_value}"; 
				}
			}
			//print_r($xml_result);
			//echo json_encode($xml_result);
			wp_send_json($xml_result);
		}else{
			
			$post_id=$_POST['xml_filename'];
			
			$xml=simplexml_load_file(__PW_ROOT_GENERAL_AD_SEARCH__."/assets/demo-preset/demo_list_1.xml") or die("Error: Cannot create object");
			
			$xml_result=array();
			foreach($xml->postmeta as $xml_data){
				
				if($xml_data->meta_key!='')
				{
					$meta_value=get_post_meta($post_id, trim($xml_data->meta_key) , true);
					
					if(is_array($meta_value))
						$meta_value=serialize($meta_value);
					
					if($pw_general_ad_main_class->isSerialized($meta_value) )
					{
						$xml_result["{$xml_data->meta_key}"]=unserialize($meta_value); 
					}else{
						$xml_result["{$xml_data->meta_key}"]="{$meta_value}"; 
					}
				}
			}
			//print_r($xml_result);
			wp_send_json($xml_result);
		}
		
		wp_die();
	
		
	}
	
	add_action('wp_ajax_pw_general_ad_search_preset_frontend_delete', 'pw_general_ad_search_preset_frontend_delete');
	add_action('wp_ajax_nopriv_pw_general_ad_search_preset_frontend_delete', 'pw_general_ad_search_preset_frontend_delete');
	function pw_general_ad_search_preset_frontend_delete(){
		$post_id=$_POST['post_id'];
		$current_id=$_POST['current_id'];
		delete_post_meta($post_id, __PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'save_preset');
		if($current_id==$post_id)
			echo 'UNCHECK';
		wp_die();
	}
	
	
	//ADD PROPERTY TO FAVORITS
	add_action('wp_ajax_pw_general_ad_sesarch_add_to_favorit', 'pw_general_ad_sesarch_add_to_favorit');
	add_action('wp_ajax_nopriv_pw_general_ad_sesarch_add_to_favorit', 'pw_general_ad_sesarch_add_to_favorit');
	function pw_general_ad_sesarch_add_to_favorit() {
		global $pw_general_ad_main_class;
		extract($_POST);
		$pw_general_ad_search_favorit_cookie='';
		if($favorite_status=='pw-general-ad-search-favorite')
		{
			if(isset($_COOKIE['pw_general_ad_search_favorit_cookie']))
			{
				//Remove From Favorite
				$pw_general_ad_search_favorit_cookie=str_replace($post_id.',','',$_COOKIE['pw_general_ad_search_favorit_cookie']);
				setcookie("pw_general_ad_search_favorit_cookie", $pw_general_ad_search_favorit_cookie, time()+3600, COOKIEPATH, COOKIE_DOMAIN);
			}else
			{
				setcookie("pw_general_ad_search_favorit_cookie", '', time()+3600, COOKIEPATH, COOKIE_DOMAIN);
				$pw_general_ad_search_favorit_cookie='';
			}
			
		}else
		{
			//Add To Favorite
			if(isset($_COOKIE['pw_general_ad_search_favorit_cookie']))
			{
				$pw_general_ad_search_favorit_cookie=$_COOKIE['pw_general_ad_search_favorit_cookie'].$post_id.',';
				setcookie("pw_general_ad_search_favorit_cookie", $pw_general_ad_search_favorit_cookie, time()+3600, COOKIEPATH, COOKIE_DOMAIN);
			}else
			{
				setcookie("pw_general_ad_search_favorit_cookie", $post_id.',', time()+3600, COOKIEPATH, COOKIE_DOMAIN);
				$pw_general_ad_search_favorit_cookie=$post_id.',';
			}
		}
		
		$fav_sticky_output='';
		$output='';
		$fav_count='';
		$post_name='';
		$post_name=get_post_type( $post_id );
		if($pw_general_ad_search_favorit_cookie)
		{
			$your_favorites = $pw_general_ad_search_favorit_cookie;
			$your_favorites = explode( ',' , $your_favorites );
			$favorite_count=count($your_favorites)-1;
			$fav_count=($favorite_count);
			$query_by_id_in=array();
			foreach($your_favorites as $ids)
			{
				$query_by_id_in[]=$ids;
			}
			
			$fav_sticky_output.='<div id="wizards" style="border:none">';
			$favorite_for = get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'option_enable_favorite_for');
			foreach($favorite_for as $fav_for){
				
				$fav_sticky_output.='
				<h4><i class="fa fa-magic"></i> '.$fav_for.'</h4>
				<section>';
				
				$ars=array(
					'post_type' =>$fav_for,
					'post_status'=>'publish',
					'post__in'=>$query_by_id_in,
				);

				//$original_query = $post;	
				$favorite_post = new WP_Query($ars);
				$rand_id=rand(0,9999);
				
				if($favorite_post->have_posts()){
					$fav_sticky_output .='<ul id="favorite_div_items_'.$fav_for.'" class="woo-bxslider woo-single-car  woo-carousel-layout ">';
					while ( $favorite_post->have_posts() ) : $favorite_post->the_post(); 
						$fetch_post_id=get_the_ID();
						require __PW_ROOT_GENERAL_AD_SEARCH__.'/includes/content-favorite.php';
					endwhile;
					wp_reset_query();
					$fav_sticky_output.='</ul>';
				}else
				{
					$fav_sticky_output.=$pw_general_ad_main_class->alert('error',__('No Favorites Has Been Added!',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__));
				}
				$fav_sticky_output.='</section>';
			}//end for
			$fav_sticky_output.='</div>
			';
			
			
			$favorite_for = get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'option_enable_favorite_for');
			$js='
			<script type="text/javascript">
				jQuery(document).ready(function(jQuery){
					
					jQuery("#wizards").steps({
						headerTag: "h4",
						bodyTag: "section",
						transitionEffect: "slideLeft",
						enableFinishButton: false,
						enablePagination: false,
						enableAllSteps: true,
						titleTemplate: "#title#",
						cssClass: "tabcontrol",
						onInit: function (event, currentIndex) {
							if(currentIndex==0){
								fornt_end_view=true;
								var aaa= jQuery("#favorite_div_items_'.$favorite_for[0].'").bxSlider({ 
									mode : "horizontal" ,
									touchEnabled : true ,
									adaptiveHeight : true ,
									slideMargin : 10 , 
									wrapperClass : "woo-bx-wrapper woo-agent-car " ,
									infiniteLoop: true,
									pager: true,
									controls: false,
									slideWidth:150,
									minSlides: 1,
									maxSlides: 3,
									moveSlides: 1, 
									auto: true,
									speed : 1000,
									autoHover  : true , 
									autoStart: false
								});
								jQuery(".woo-bx-wrapper .woo-bx-controls-direction a").click(function(){
									aaa.startAuto();
								});
							}
						},
						onStepChanged :function (event, currentIndex, priorIndex) { 
							
						';
						
						$i=0;
						$favorite_for = get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'option_enable_favorite_for');
						foreach($favorite_for as $fav_for){
							$js.='
								if(currentIndex=='.$i.'){
									fornt_end_view=true;
									var aaa= jQuery("#favorite_div_items_'.$fav_for.'").bxSlider({ 
										mode : "horizontal" ,
										touchEnabled : true ,
										adaptiveHeight : true ,
										slideMargin : 10 , 
										wrapperClass : "woo-bx-wrapper woo-agent-car " ,
										infiniteLoop: true,
										pager: true,
										controls: false,
										slideWidth:150,
										minSlides: 1,
										maxSlides: 3,
										moveSlides: 1, 
										auto: true,
										speed : 1000,
										autoHover  : true , 
										autoStart: false
									});
									jQuery(".woo-bx-wrapper .woo-bx-controls-direction a").click(function(){
										aaa.startAuto();
									});	
								}	
							';
							$i++;
						}
							
						$js.='
						}
					});
					
				});
			</script>
			';
			$fav_sticky_output.=$js;
			
		}else
		{
			$fav_sticky_output.=$pw_general_ad_main_class->alert('error',__('No Favorites Has Been Added!',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__));
		}
		echo $fav_count.'@#'.$fav_sticky_output;
		
		//echo ($_COOKIE['pw_general_ad_search_favorit_cookie']);
		wp_die();
	}
	
	
	add_action('wp_ajax_pw_general_ad_search_action_build_query_sql_result', 'pw_general_ad_search_action_build_query_sql_result');
	add_action('wp_ajax_nopriv_pw_general_ad_search_action_build_query_sql_result', 'pw_general_ad_search_action_build_query_sql_result');
	function pw_general_ad_search_action_build_query_sql_result() {
		global $wpdb,$pw_general_ad_main_class;
		
	
		$return_value=$pw_general_ad_main_class->build_search_query($_POST);
		
		$post = new WP_Query($return_value['query']);

		//PAGINATION OPTIONS
		$all_page_number=$post->max_num_pages;
		extract($_POST);
		
		//print_r($_POST);
		
		$pw_sf_pagination_type=isset($_POST['pw_sf_pagination_type']) ? $_POST['pw_sf_pagination_type']:'pagination_no';
		$pw_sf_pagination_paged=isset($_POST['pw_sf_pagination_paged']) ? $_POST['pw_sf_pagination_paged']:'1';
		
		$pw_sf_part=isset($_POST['pw_sf_part']) ? $_POST['pw_sf_part']:'';
		
		
		switch($pw_sf_part)
		{
			case "pw_general_ad_grid_search":
			{
				$my_query = new WP_Query($return_value['query']);
				//print_r($return_value['query']);
				//echo $my_query->request;
				$pw_general_ad_main_class->fetch_custom_fields($pw_sf_shortcode_id);
				$shortcode_id=$pw_sf_shortcode_id;
				
				
				if( $my_query->have_posts()):
					require __PW_ROOT_GENERAL_AD_SEARCH__.'/frontend/content-product.php';
				else :
					$search_form_not_found=(get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'search_form_not_found')=='' ? __('No results were found. Please try a different search!',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'search_form_not_found'));
					
					echo $pw_general_ad_main_class->alert('error',$search_form_not_found);	
					//echo $pw_general_ad_main_class->alert('error',__('No results were found. Please try a different search!',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__));	
				endif;	
				
				if(!isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'enable_carousel']))
				{
					require('actions_pagination.php');
					echo $pagination;
				}
			}
			break;
			
			case "pw_general_ad_grid":
			{
				$my_query = new WP_Query($return_value['query']);
				//echo $my_query->request;
				
				$pw_general_ad_main_class->fetch_custom_fields($pw_sf_shortcode_id);
				$shortcode_id=$pw_sf_shortcode_id;
				
				
				if( $my_query->have_posts()):
					require __PW_ROOT_GENERAL_AD_SEARCH__.'/frontend/content-product.php';
				else :
					
					$search_form_not_found=(get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'search_form_not_found')=='' ? __('No results were found. Please try a different search!',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'search_form_not_found'));
					
					echo $pw_general_ad_main_class->alert('error',$search_form_not_found);	
				endif;	
				
				if(!isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'enable_carousel']))
				{
					require('actions_pagination.php');
					echo $pagination;
				}
			}
			break;
			
			case "pw_general_ad_grid_archive_page":
			{
				$products = new WP_Query($return_value['query']);
				 $woocommerce_loop['columns'] = 4;
			
				if ( $products->have_posts() ) : ?>
			
						<?php woocommerce_product_loop_start(); ?>
			
							<?php while ( $products->have_posts() ) : $products->the_post(); ?>
			
								<?php wc_get_template_part( 'content', 'product' ); ?>
			
							<?php endwhile; // end of the loop. ?>
			
						<?php woocommerce_product_loop_end(); ?>
			
					<?php endif;
			
				wp_reset_postdata();
			
				echo '<div class="woocommerce columns-4">' . ob_get_clean() . '</div>';
			}
			break;
			
		}
		
		echo '@#'.implode(' ',$return_value['your_search']);
		wp_die();
	}
	
	
	add_action('wp_ajax_pw_general_ad_sesarch_sendto_form', 'pw_general_ad_sesarch_sendto_form');
	add_action('wp_ajax_nopriv_pw_general_ad_sesarch_sendto_form', 'pw_general_ad_sesarch_sendto_form');
	function pw_general_ad_sesarch_sendto_form() {
		global $pw_general_ad_main_class,$wpdb;
		
		if(isset($_POST['post_id'])){
			$sendto_fields=(get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_fields')=='' ? '':get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_fields'));
			
			$form_fields=$pw_general_ad_main_class->alert('error',__('There are any fields for "Send To" form. Please set it in setting!',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__));	
			
			if($sendto_fields!=''){
				
				$form_fields_array=array("Name_from"=>__("From",__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),"Name_to"=>__("To",__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),"Email"=>__("Email",__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__),"Description"=>__("Description",__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__));
				
				$form_fields='<form id="pw_general_ad_search_sendto_form">
					<div id="pw-ad-woo-search-sendto-result"></div>
				';
				foreach($sendto_fields as $fields){
					
					if($fields=='address' || $fields=='description'){
						$form_fields.='
						<div class="woo-col-md-12">
							<div class="input-group input-group-sm">
								<textarea cols="34" rows="5" class="form-control textarea-input search-field" name="'.$fields.'" placeholder="'.$form_fields_array[ucwords($fields)].'"></textarea>
							</div>
						</div>';
					}else{
						
						$required='';
						$type='search';
						if($fields=='name_from' || $fields=='email')
						{
							$required='required';
						}
						
						if($fields=='email')
						{
							$required='required';
							$type='email';
						}
						
						$form_fields.='
						
						<div class="woo-col-md-12">
							<div class="input-group input-group-sm">
									<input type="'.$type.'"  '.$required.'  name="'.$fields.'" class="form-control title-input search-field" value="" placeholder="'.$form_fields_array[ucwords($fields)].'" data-searchfied="Keywords">
							</div>
						</div>';
					}
				}
				
				$form_fields.='
					<div class="woo-col-md-12">
						<div class="input-group input-group-sm">
							<input type="hidden" name="id_post"  value="'.$_POST['post_id'].'" />
							<input type="hidden" name="post_hidden"  value="Y" />
							<input type="submit" class="search-submit" value="'.__('Send',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'">
						</div>
					</div>
					</form>
				';
			}
			
		
			echo '
						        
				<div class="woo-sendto-title">
					<h3>'.(get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_title')=='' ? __('Send to a friend',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_title')).'</h3>
				</div>	
				
				<div id="woo-row">
					'.$form_fields.'
				</div>
			';
			
		}else{
			
			extract($_POST);
			
			$post_type=get_post_type($id_post );

			$args=array('post_type' => $post_type,
						'post_status'=>'publish',
						'post__in'=>array($id_post),
					 );
			
			$product_name='';
			$product_link='';
			$sendto_post = new WP_Query($args);	
			
			if($post_type=='product')
			{
				if( $sendto_post->have_posts()):
					while ( $sendto_post->have_posts() ) : $sendto_post->the_post(); 
						$product = get_product($sendto_post->post->ID);
						$product_name = $product->get_title();
						$product_link=$product->get_permalink();
						$product_price = $product->get_price_html();
						
						$thumbnail_id = $product->get_image_id();
						$img_url_thumb = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail' );
						$img_url_thumb=$img_url_thumb[0];
						
						$product_desc = $product->post->post_excerpt;
						if($product_desc=='')
							$product_desc = $product->post->post_content;
						
					endwhile;
					wp_reset_query();
				endif;
			}else
			{
				if( $sendto_post->have_posts()):
					while ( $sendto_post->have_posts() ) : $sendto_post->the_post(); 
						
						$postd = get_post($sendto_post->post->ID);

						$product_desc = $postd->post_excerpt;
						if($product_desc=='')
							$product_desc = $postd->post_content;
						
						$product_name= $postd->post_title;
						$product_link=get_permalink($post_id);
						
						$thumbnail_id = get_post_thumbnail_id($post_id);
						$img_url_thumb = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail');
						$img_url_thumb=$img_url_thumb[0];
						
						$product_price='';
						
					endwhile;
					wp_reset_query();
				endif;
			}
			
			
						
			$from = (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_receiver_email')=='' ? get_option('admin_email'):get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_receiver_email'));
			
			$to=$email;
	
			$subject = __( 'Suggest Post :: ' , __PW_GENERAL_AD_SEARCH_TEXTDOMAIN__ );
			$headers = __('From: ',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).' '.__('From',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).' <'. $from . ">\r\n" .
			'Reply-To: ' . $from . "\r\n";
						
			$html='
				<div style="display:inline-block; width:92%;background:#ffffff; border:4px dashed #ccc; padding:20px; background:#f5f5f5;">
					<div style="font-family:Arial,Tahoma; font-size:30px; margin-bottom:10px; font-weight:bold;" >
						<span>'.__('Dear',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).' </span>
						<span style="color:red;">'.(isset($name_to) ? $name_to:'').'</span>, 
					</div>
					<div style="font-family:Arial,Tahoma; font-size:20px;margin-bottom:10px;" >
						<span>'.__('Your friend',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).' <span style="color:red;">'.(isset($name_from) ? $name_from:'').', </span> '.__('has been invited you to see this post',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).' </span>
					</div>
					<div style="font-family:Arial,Tahoma; font-size:20px;margin-bottom:10px;" >
						<span>'.(isset($description) ? $description:'').'</span>
					</div>
					<div style="margin-top:20px;">
						<div style="float:left; width:100px; height:140px; margin-right:10px; padding: 5px 5px 3px 5px;background: #ccc;">
							<img src="'.$img_url_thumb.'" width="100" height="140" >
						</div>
						<div style="font-weight:bold; color:red; font-size:15px; margin-bottom:10px" ><a href="'.$product_link.'" target="_blank">'.$product_name.'</a></div>
						<div style="font-size:13px;margin-bottom:10px" >'.do_shortcode($product_desc).'</div>
						';
						if($post_type=='product')
						{
							$html.='
						<div style="font-weight:bold; color:red; font-size:15px;" >'.(isset($product_price) ? $product_price:__('No Price!',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__)).'</div>';
						}
					$html.='	
					</div>
				</div>
				';
	
			add_filter( 'wp_mail_content_type', 'pw_general_ad_grid_set_html_content_type' );  
			$sent = wp_mail($to, $subject, $html, $headers);
			remove_filter( 'wp_mail_content_type', 'pw_general_ad_grid_set_html_content_type' );
									
			if (isset($sent) && count($sent)) {
				echo '<div class="woo-col-md-12">
						<div class="input-group input-group-sm">'.$pw_general_ad_main_class->alert('success',__('Thanks, Your message has been sent.',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__)).'</div>
					</div>';
						
							
			} 
			else
			{
				echo '<div class="woo-col-md-12">
					<div class="input-group input-group-sm">'.$pw_general_ad_main_class->alert('error',__('Error, Please Try Again!',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__)).'</div>
				 </div>';
			}
		}
		
		wp_die();
	}
	function pw_general_ad_grid_set_html_content_type() {
		return 'text/html';
	}
	
	add_action('wp_ajax_pw_general_ad_sesarch_quickview', 'pw_general_ad_sesarch_quickview');
	add_action('wp_ajax_nopriv_pw_general_ad_sesarch_quickview', 'pw_general_ad_sesarch_quickview');
	function pw_general_ad_sesarch_quickview() {
		global $wpdb,$pw_general_ad_main_class,$post,$woocommerce;
		
								
		$product_id=$_POST['post_id'];
		$post_id=$id=$_POST['post_id'];
		$post_name=get_post_type( $product_id );
		$product_slide='';	
		if($post_name=='product'){
			$product = get_product($product_id);
		
			$title = $product->get_title();
			$excerpt = $product->post->post_excerpt;
			if($excerpt=='')
				$excerpt = $product->post->post_content;
			$postd = get_post($id);
		//	echo $excerpt.'<br/>';
		//	echo $id.'<br/>';
			//print_r($post);
		//	$postd->post_excerpt;
		//	echo '<br/>'.$postd->post_excerpt;
			
			$thumbnail_id = $product->get_image_id();
			
			$src_featured = wp_get_attachment_image( $thumbnail_id, 'thumbnail'); //Featured Image with size
			
			$img=$product->get_image(); //Featured Image 
	 
			$permalink=$product->get_permalink();
			$add_to_cart_url = $product->add_to_cart_url();
			
			$regular_price = $product->get_regular_price();
			$sale_price = $product->get_sale_price();
			$price = $product->get_price_html();
			
			//$rating = $product->get_rating_html();				
			$rating=pw_general_ad_search_rating_grid($id);
			$cat =$product->get_categories();				
			$tag =$product->get_tags();
			$sku =$product->get_sku();
			$stock_status =$product->is_in_stock(); //1 : in ,0 :out
			$stock_quantity =$product->get_stock_quantity();
			
			$featured =$product->is_featured();
			$on_sale =$product->is_on_sale(); // 1:0
			
			$thumb_index=0;
			$product_thumb=$product_slide='';
			$img_url=array();
			$img_url_default='';
			
			if($product->get_image_id() != NULL ){
				$img_url = wp_get_attachment_image_src( $product->get_image_id(), 'large' );
				$img_url_thumb = wp_get_attachment_image_src( $product->get_image_id(), 'thumbnail' );
				$product_slide .='<li><div class="thumb-divback thumb-1-2" style="background:url('. $img_url[0].') no-repeat; overflow: hidden;background-position: 50% 50%!important;background-size: cover!important;" ></div></li>';
				$product_thumb .='<a data-slide-index="'.$thumb_index++.'" href=""><img src="'. $img_url_thumb[0].'" /></a>';
			}
			$this_product_gallery = $product->get_gallery_attachment_ids();
			if( $this_product_gallery != NULL){
				foreach ($this_product_gallery as $this_image){
					$img_url = wp_get_attachment_image_src( $this_image, 'large' );
					$img_url_thumb = wp_get_attachment_image_src( $this_image, 'thumbnail' );
					$product_slide .='<li><div style="background:url('. $img_url[0].') no-repeat; overflow: hidden;background-position: 50% 50%!important;background-size: cover!important;" ></div></li>';
					$product_thumb .='<a data-slide-index="'.$thumb_index++.'" href=""><img src="'. $img_url_thumb[0].'" /></a>';
				}
			}
			if($product->get_image_id() == NULL && $this_product_gallery == NULL ){
				$img_url_default = wc_placeholder_img_src();
			}
			
			$add_to_cart_btn ='';
			$add_to_cart_label= pw_general_ad_search_add_to_cart_grid('label',$id);
			if( $product->is_type( 'simple' ) ){
				$add_to_cart_btn = "<div class='woo-addcard-btn  back-btn'><a href='" . $product->add_to_cart_url() . "' data-quantity='1' data-product_sku='" . $product->get_sku() . "' data-product_id=$id rel='nofollow' ><span>".$add_to_cart_label."</span></a></div>";
			}
			/*elseif( $product->is_type( 'variable' ) ){
				global $product, $post;
				$product = $product;
				$post = get_post($id);
				require( plugin_dir_path( __FILE__ ) . '/add_to_cart/variable.php' );
			}*/
			else{
				$add_to_cart_btn = "<div class='woo-addcard-btn  back-btn'><a href='" . $product->add_to_cart_url() . "' data-quantity='1' data-product_sku='" . $product->get_sku() . "' data-product_id=$id rel='nofollow' ><span>".$add_to_cart_label."</span></a></div>";
			}
			echo '<div class="woo-row">';
				echo '<div class="woo-quick-image-cnt">
						<ul id="woo-quick-car" class="woo-quick-car woo-bxslider woo-single-car  woo-carousel-layout">'.$product_slide.'</ul>
						<div id="woo-pager" class="woo-pager">
						'.$product_thumb.'
						</div>
					  </div>';
				echo '<div class="woo-col-xs-5">
						<div class="woo-quick-detail-cnt">';
					echo '<h3 class="woo-quick-title"><a href="'.$permalink.'" >'.$title.'</a></h3>';
					print_r('<div class="woo-quick-cat">'.$cat.'</div>');
					print_r('<div class="woo-quick-tag">'.$tag.'</div>');
					echo '<div class="woo-quick-rating">'.$rating.'</div>';
					
					echo '<div class="woo-quick-price"><span class="woo-quick-sale-price">' .$price.'</div>';
					echo '<div class="woo-quick-excerpt">'.do_shortcode($excerpt).'</div>';
					
					echo '<div class="woo-quick-add add_to_cart_buttons product_type_simple add-to-cart" data-product_id="'.$id.'" data-quantity="1">'.$add_to_cart_btn.'</div>';
					
				echo '	</div>
					</div>';
			echo '</div>';
		}
		//If Post Name No Product
		else{
		
			$postd = get_post($post_id);

			$excerpt = $postd->post_excerpt;
			if($excerpt=='')
				$excerpt = $postd->post_content;
			
			$title= $postd->post_title;
			$permalink=get_permalink($post_id);
			$cat ='';	
			$category = get_the_category($post_id); 
			//if($category[0]){
			foreach($category as $catt){	
				$cat[]='<a href="'.get_category_link($catt->term_id ).'">'.$catt->cat_name.'</a>';
			}
			
			if(is_array($cat))
				$cat=implode(' / ',$cat);
			
			$tag ='';
			$tags=wp_get_post_tags( $post_id);
			foreach($tags as $tagg){
				$tag[]='<a href="'.get_tag_link($tagg->term_id ).'">'.$tagg->name.'</a>';
			}
			if(is_array($tag))
				$tag=implode(' / ',$tag);
			
			$thumbnail_id = get_post_thumbnail_id($post_id);
			//Featured Image with size
			$src_featured = wp_get_attachment_image_src( $thumbnail_id, 'large'); 
			
			$thumb_index=0;
			$img_url=array();
			$img_url_default='';
			
			if($src_featured==''){
				$img_url_default=get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'default_image');
				$img_url_default=wp_get_attachment_image_src( $img_url_default,'large');
				$src_featured=$img_url_default;
			}	
			
			if($src_featured[0]!='')
				$product_slide='<div class="thumb-divback thumb-1-2" style="background:url('. $src_featured[0].') no-repeat; overflow: hidden;background-position: 50% 50%!important;background-size: cover!important;" ><img src="'.$src_featured[0].'" alt="'.$title.'" /></div>';
			
			echo '<div class="woo-row">';
			
				$col='12';
				if($product_slide!='')
				{
					echo '<div class="woo-quick-image-cnt">
							'.$product_slide.'
						  </div>';
					$col='5';	  
				}
				
				echo '<div class="woo-col-xs-'.$col.'">
						<div class="woo-quick-detail-cnt">';
					//Start Of Scroll
					echo '<div class="wt-scrollbarcnt wt-scroll">
								<div class="scrollbar">
									<div class="track">
										<div class="thumb"><div class="end"></div></div>
									</div>
								</div>';
					   echo '<div class="viewport">
								<div class="overview">';
					
					echo '<h3 class="woo-quick-title"><a href="'.$permalink.'" >'.$title.'</a></h3>';
					print_r('<div class="woo-quick-cat">'.$cat.'</div>');
					print_r('<div class="woo-quick-tag">'.$tag.'</div>');
					echo '<div class="woo-quick-excerpt">'.do_shortcode($excerpt).'</div>';
					
					//end of Scroll
					echo '</div>
							</div>
								</div>';
								
				echo '	</div>
					</div>';
			echo '</div>';			
		}
		
		wp_die();
	}
	function pw_general_ad_search_frontend_add_scripts_popup(){
		
	}
?>