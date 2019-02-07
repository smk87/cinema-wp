<?php

add_action('admin_init', 'pw_general_grid_admin_init_cat_img');
function pw_general_grid_admin_init_cat_img() {
	$pw_general_ad_search_cat_img_taxonomies = get_taxonomies();
	if (is_array($pw_general_ad_search_cat_img_taxonomies)) {
		$pw_general_ad_search_options = get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'category_image');

		if(is_array($pw_general_ad_search_options))
		{
			foreach ($pw_general_ad_search_options as $pw_general_ad_search_cat_img_taxonomy) {
				
				add_action($pw_general_ad_search_cat_img_taxonomy.'_add_form_fields', 'pw_general_ad_search_cat_img_add_texonomy_field');
				add_action($pw_general_ad_search_cat_img_taxonomy.'_edit_form_fields', 'pw_general_ad_search_cat_img_edit_texonomy_field');
				add_filter( 'manage_edit-' . $pw_general_ad_search_cat_img_taxonomy . '_columns', 'pw_general_ad_search_cat_img_taxonomy_columns' );
				add_filter( 'manage_' . $pw_general_ad_search_cat_img_taxonomy . '_custom_column', 'pw_general_ad_search_cat_img_taxonomy_column', 10, 3 );
			}
		}
		
	    
	}
}

// add image field in add form
function pw_general_ad_search_cat_img_add_texonomy_field() {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}
	
	echo '
	<div class="form-field">
		<label for="tag-description">' . __('Image', __PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) . '</label>
		<input name="taxonomy_image" id="taxonomy_image" type="hidden" class="custom_upload_image taxonomy_image" value="" /> 
			<img src="'.__PW_GENERAL_AD_SEARCH_IMG_PLACEHOLDER__.'" class="custom_preview_image pw-taxonomy-image" alt="" /><br/>
			<input name="btn" class="pw_general_search_upload_image_button button" type="button" value="'.__('Choose Image',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'" /> 
			<button type="button" class="pw_general_ad_search_remove_image_button button">'.__('Remove image',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'</button>
		<p>The description is not prominent by default; however, some themes may show it.</p>
	</div>';
}

// add image field in edit form
function pw_general_ad_search_cat_img_edit_texonomy_field($taxonomy) {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}
	
	if (pw_general_ad_search_cat_img_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE , 'thumb_src' ) == __PW_GENERAL_AD_SEARCH_IMG_PLACEHOLDER__)
		$image_text = "";
	else
		$image_text = pw_general_ad_search_cat_img_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE , 'thumb_id');
		
	echo '
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="taxonomy_image">' . __('Image', __PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) . '</label>
		</th>
		<td>
			<input name="taxonomy_image" id="taxonomy_image" type="hidden" class="custom_upload_image taxonomy_image" value="'.$image_text.'" /> <br />
			<img src="'.pw_general_ad_search_cat_img_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE ,'thumb_src').'" class="custom_preview_image pw-taxonomy-image" alt="" /><br/>
			<input name="btn" class="pw_general_search_upload_image_button button" type="button" value="'.__('Choose Image',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'" /> 
			<button type="button" class="pw_general_ad_search_remove_image_button button">'.__('Remove image',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'</button></td>
		</td>	
	</tr>';
}

// save our taxonomy image while edit or save term
add_action('edit_term','pw_general_ad_search_cat_img_save_taxonomy_image');
add_action('create_term','pw_general_ad_search_cat_img_save_taxonomy_image');
function pw_general_ad_search_cat_img_save_taxonomy_image($term_id) {
    if(isset($_POST['taxonomy_image']))
        //update_option('thumbnail_id'.$term_id, $_POST['taxonomy_image']);
		update_post_meta($term_id,'thumbnail_id', $_POST['taxonomy_image']);
}


// get taxonomy image url for the given term_id (Place holder image by default)
function pw_general_ad_search_cat_img_taxonomy_image_url($term_id = NULL, $size = NULL, $return_placeholder = FALSE,$type='thumb_id') {
	
	if (!$term_id) {
		if (is_category())
			$term_id = get_query_var('cat');
		elseif (is_tax()) {
			$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$term_id = $current_term->term_id;
		}
	}
	
    //$taxonomy_image_url = get_option('thumbnail_id'.$term_id);
	$taxonomy_image_url = get_post_meta($term_id,'thumbnail_id',true);
	
	
    if(!empty($taxonomy_image_url)) {
	    $attachment_id = get_post_meta($term_id,'thumbnail_id',true);
	    if(!empty($attachment_id)) {
	    	if (empty($size))
	    		$size = 'full';
			
			if($type=='thumb_id')	
				return $attachment_id;
				
	    	$taxonomy_image_url = wp_get_attachment_image_src($attachment_id, $size);
		    $taxonomy_image_url = $taxonomy_image_url[0];
	    }
	}

    if ($return_placeholder)
		return ($taxonomy_image_url != '') ? $taxonomy_image_url : __PW_GENERAL_AD_SEARCH_IMG_PLACEHOLDER__;
	else
		return $taxonomy_image_url;
}


function pw_general_ad_search_cat_img_taxonomy_columns( $columns ) {
	$new_columns = array();
	$new_columns['cb'] = $columns['cb'];
	$new_columns['thumb'] = __('Image', __PW_GENERAL_AD_SEARCH_TEXTDOMAIN__);

	unset( $columns['cb'] );

	return array_merge( $new_columns, $columns );
}


function pw_general_ad_search_cat_img_taxonomy_column( $columns, $column, $id ) {
	if ( $column == 'thumb' )
		$columns = '<span><img src="' . pw_general_ad_search_cat_img_taxonomy_image_url($id, NULL, TRUE,'thumb_src') . '" alt="' . __('Thumbnail', __PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) . '" class="wp-post-image" /></span>';
	
	return $columns;
}