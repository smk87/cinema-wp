<?php 
	global $pw_general_ad_main_class; 
	$custom_item_fields = $pw_general_ad_main_class->check_isset(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'item_fields','custom_field','');
	$col_array=array(); $counter_end=0;
	if ( $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'diff_size_pattern']=='pattern-1'){
		$col_array=array(3,6,3,3,3,3,3,3,3,6,3,3,3,3,3,3,3,3,3,3,6,3,3,3,3,3,3);
		$counter_end=26;
	}
	if ( $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'diff_size_pattern']=='pattern-2'){
		$col_array=array(3,6,3,3,3);
		$counter_end=5;
	}
	if ( $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'diff_size_pattern']=='pattern-3'){
		$col_array=array(3,6,3,3,3,3,3,3,3);
		//$col_array=array(6,3,3,3,3,3,3,3,3,3,3,3,3);
		//$counter_end=13;
		$counter_end=9;
	}
	
	
	
	if ($counter==$counter_end) {
		$counter=0;
	}
	else if($counter>$counter_end)
		$counter=$counter%($counter_end);
	
	//echo $counter.'--'.$col_array[$counter];	
	
	$box_shadow_set = $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'box_shadow_set'];
?>
<?php if ($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'grid_box_effect_type']=='effect1'): ?>
	<div class="woo-col-md-<?php echo $col_array[$counter]; ?> woo-col-xs-12">
    <div class="woo-product-cnt woo-boxed-eff-one <?php echo $box_shadow_set['type']; ?> ">
        <?php if ( $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'image_effect_type']=='second_image'): ?>
        	<div class="woo-secondimg <?php echo $image_ratio ?>" style="background:url(<?php echo $img2[0] ?>);">
                <img src="<?php echo $img2[0] ?>"  />
            </div>
        <?php endif; ?>
		<div class="thumb-divback <?php echo $image_ratio .' '.$image_eff; ?>" style="background:url(<?php echo $img1[0] ?>);">
                <img src="<?php echo $img1[0] ?>" alt="<?php echo $title; ?>" />
        </div>
        <?php if (isset($custom_item_fields['excerpt'])): ?>
            <div class="woo-product-desc"><?php echo  $pw_general_ad_main_class->excerpt(get_the_excerpt(),$pw_general_ad_main_class->check_isset(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'excerpt_len','custom_field','10')); ?></div>
        <?php endif; ?>
        <div class="woo-overlay-cnt">
            <?php if (isset($custom_item_fields['author'])): ?>
                <div class="woo-banner sale-banner" ><i class="fa fa-user"></i><a href="<?php echo $author_link ?>"><?php echo $author; ?></a></div>
            <?php endif; ?>
            
            <?php if (isset($custom_item_fields['date'])): ?>
                <div class="woo-banner feature-banner" ><i class="fa fa-calendar"></i><?php echo $date; ?></div>
            <?php endif; ?>
            
            
		<?php if (isset($custom_item_fields['title'])): ?>
            <h3 class="woo-product-title"><a href="<?php echo get_the_permalink(); ?>"  target="<?php echo $link_target;?>"><?php echo $title; ?></a></h3>
        <?php endif; ?>
        <?php 
			$show_tags_cats='';
			if (isset($custom_item_fields['category']) && ($cat!='')): 
				$show_tags_cats=$cat;
			endif;	
			
			if (isset($custom_item_fields['tag']) && ($tag!='')): 
				if($show_tags_cats=='')
					$show_tags_cats=$tag;			
				else
					$show_tags_cats.='/'.$tag;	
			endif;
			
			if($show_tags_cats!='')	:	
		?>
            <div class="woo-meta woo-product-category"><span class="woo-meta-title"><i class="fa fa-tags"></i></span><?php echo $show_tags_cats; ?></div>
        <?php endif; ?>
        <?php if (isset($custom_item_fields['comment'])): ?>
            <div class="woo-meta woo-meta-comment"><span class="woo-meta-title"><i class="fa fa-comment"></i></span><a href="<?php echo $comment_link; ?>"><?php echo $comment; ?></a></div>
        <?php endif; ?>
        <?php if (isset($custom_item_fields['price'])): ?>
            <?php 
             echo '<div class="woo-product-price">'.$price.'</div>';
            ?>
        <?php endif; ?>
        <?php if (isset($custom_item_fields['star'])): ?>
            <?php echo pw_general_ad_search_rating_grid($my_query->post->ID); ?>
        <?php endif ?>
        <div  class="woo-btns" >
            <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_read_more'])): ?>
                <div class="woo-readmore" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title')=='' ? __('Read More',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title'));?>"><a href="<?php echo get_the_permalink(); ?>"  target="<?php echo $link_target;?>"><i class="fa fa-link"  ></i></a></div>
            <?php endif; ?>
			<?php 
				$favorite_status='pw-general-ad-search-unfavorite';
				if(isset($_COOKIE['pw_general_ad_search_favorit_cookie']))
				{
					$favorites=explode(',',$_COOKIE['pw_general_ad_search_favorit_cookie']);
					if(is_array($favorites) && in_array($id,$favorites))
						$favorite_status='pw-general-ad-search-favorite';
				}
				
				if (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_enable_favorite_use')=='on' && isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_favorite'])): ?>
                <div class="woo-addfav" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'add_to_favorite_title')=='' ? __('Add to favorite',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'add_to_favorite_title'));?>"><i data-property-id="<?php echo $id?>" class="fa fa-heart <?php echo $favorite_status;?>" ></i></div>
            <?php endif; ?>
            <?php if (isset($custom_item_fields['add_cart'])): ?>
                <div class="woo-addcart" title="<?php echo $add_to_cart_label;?>">
					<?php if ($add_to_cart_has_tag_a): ?>
                        <?php echo $add_to_cart_link; ?>
                    <?php else: ?>
                             <a href="<?php echo $add_to_cart_link; ?>" class="add_to_cart_button product_type_simple" data-product_id="<?php echo $id?>" data-quantity="1"></a>
                    <?php endif;?>    
                </div>
            <?php endif; ?>
            <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_share_icons']) && isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && is_array($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && count($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'])>0): ?>
                <div class="woo-sharebtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'share_product_title')=='' ? __('Share This',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'share_product_title'));?>" >
                    <div class="woo-shareicon-cnt">
                                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('facebook' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink(); ?>"><i class="fa fa-facebook"></i></a>
                                <?php endif; ?>
                                
								<?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('twitter' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="https://twitter.com/home?status=Currentlyreading<?php echo get_the_permalink(); ?>"><i class="fa fa-twitter"></i></a>
                                <?php endif; ?>
                                
                                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('google_plus' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="https://plus.google.com/share?url=<?php echo get_the_permalink(); ?>"><i class="fa fa-google-plus"></i></a>
                                <?php endif; ?>
                                
                                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('pinterest' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="http://www.pinterest.com/pin/create/button/?url=<?php echo get_the_permalink(); ?>&description=<?php echo get_the_title(); ?>"><i class="fa fa-pinterest"></i></a>
                                <?php endif; ?>
                                
                                
                            </div>
                    <i class="fa fa-share"></i>
                </div>
            <?php endif; ?>
            <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'quickview_style'])): ?>
                <div class="woo-quickviewbtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'quick_view_title')=='' ? __('Quick View',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'quick_view_title'));?>"><i class="fa fa-eye" data-property-id="<?php echo $id?>" ></i></div>
            <?php endif; ?>
            <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_send_to'])): ?>
                <div class="woo-sendbtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_title')=='' ? __('Send to a friend',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_title'));?>"><i class="fa fa-envelope" data-property-id="<?php echo $id?>" ></i></div>
            <?php endif; ?>
            <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_zoom'])): ?>
                <div class="woo-icon" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'zoom_title')=='' ? __('Big Image',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'zoom_title'));?>" ><a class="example-image-link" data-lightbox="example-<?php echo rand(0,2000); ?>" href="<?php echo $img1[0]; ?>"><i class="fa fa-search-plus"></i></a></div>
            <?php endif; ?>
            </div> 
        </div>
    </div><!-- woo-product-cnt-->
</div>
<?php endif; ?>

<?php if ($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'grid_box_effect_type']=='effect2'): ?>
	<div class="woo-col-md-<?php echo $col_array[$counter]; ?> woo-col-xs-12">
    <div class="woo-product-cnt woo-boxed-eff-two">
        <?php if ( $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'image_effect_type']=='second_image'): ?>
        	<div class="woo-secondimg <?php echo $image_ratio ?>" style="background:url(<?php echo $img2[0] ?>);">
                <img src="<?php echo $img2[0] ?>"  />
            </div>
        <?php endif; ?>
		<div class="thumb-divback <?php echo $image_ratio .' '.$image_eff; ?>" style="background:url(<?php echo $img1[0] ?>);">
                <img src="<?php echo $img1[0] ?>" alt="<?php echo $title; ?>" />
        </div>
        <div class="woo-overlay-cnt">
            <?php if (isset($custom_item_fields['title'])): ?>
                <h3 class="woo-product-title"><a href="<?php echo get_the_permalink(); ?>"  target="<?php echo $link_target;?>"><?php echo $title; ?></a></h3>
            <?php endif; ?>
            <?php 
				$show_tags_cats='';
				if (isset($custom_item_fields['category']) && ($cat!='')): 
					$show_tags_cats=$cat;
				endif;	
				
				if (isset($custom_item_fields['tag']) && ($tag!='')): 
					if($show_tags_cats=='')
						$show_tags_cats=$tag;			
					else
						$show_tags_cats.='/'.$tag;	
				endif;
				
				if($show_tags_cats!='')	:	
			?>
                <div class="woo-meta woo-product-category"><span class="woo-meta-title"><i class="fa fa-tags"></i></span><?php echo $show_tags_cats; ?></div>
            <?php endif; ?>
            <?php if (isset($custom_item_fields['comment'])): ?>
                <div class="woo-meta woo-meta-comment"><span class="woo-meta-title"><i class="fa fa-comment"></i></span><a href="<?php echo $comment_link; ?>"><?php echo $comment; ?></a></div>
            <?php endif; ?>
            
            <?php if (isset($custom_item_fields['excerpt'])): ?>
                <div class="woo-product-desc"><?php echo  $pw_general_ad_main_class->excerpt(get_the_excerpt(),$pw_general_ad_main_class->check_isset(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'excerpt_len','custom_field','10')); ?></div>
            <?php endif; ?>
            <?php if (isset($custom_item_fields['price'])): ?>
				<?php 
                 	echo '<div class="woo-product-price">'.$price.'</div>';
                ?>
            <?php endif; ?>
            
            <?php if (isset($custom_item_fields['author'])): ?>
                <div class="woo-banner sale-banner" ><i class="fa fa-user"></i><a href="<?php echo $author_link ?>"><?php echo $author; ?></a></div>
            <?php endif; ?>
            
            <?php if (isset($custom_item_fields['date'])): ?>
                <div class="woo-banner feature-banner" ><i class="fa fa-calendar"></i><?php echo $date; ?></div>
            <?php endif; ?>
           
            <?php if (isset($custom_item_fields['star'])): ?>
				<?php echo '<div class="woo-starcnt">'. pw_general_ad_search_rating_grid($my_query->post->ID) .'</div>'; ?>
            <?php endif ?>
            <div  class="woo-btns <?php echo $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'icon_style']; ?>"  >
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_read_more'])): ?>
                    <div class="woo-readmore" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title')=='' ? __('Read More',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title'));?>"><a href="<?php echo get_the_permalink(); ?>"  target="<?php echo $link_target;?>"><i class="fa fa-link"  ></i></a></div>
                <?php endif; ?>
				<?php 
					$favorite_status='pw-general-ad-search-unfavorite';
					if(isset($_COOKIE['pw_general_ad_search_favorit_cookie']))
					{
						$favorites=explode(',',$_COOKIE['pw_general_ad_search_favorit_cookie']);
						if(is_array($favorites) && in_array($id,$favorites))
							$favorite_status='pw-general-ad-search-favorite';
					}	
					
					if (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_enable_favorite_use')=='on' && isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_favorite'])): ?>
                	<div class="woo-addfav" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'add_to_favorite_title')=='' ? __('Add to favorite',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'add_to_favorite_title'));?>"><i data-property-id="<?php echo $id?>" class="fa fa-heart <?php echo $favorite_status;?>"></i></div>
                <?php endif; ?>
                <?php if (isset($custom_item_fields['add_cart'])): ?>
                	<div class="woo-addcart" title="<?php echo $add_to_cart_label;?>">
						<?php if ($add_to_cart_has_tag_a): ?>
                            <?php echo $add_to_cart_link; ?>
                        <?php else: ?>
                                 <a href="<?php echo $add_to_cart_link; ?>" class="add_to_cart_button product_type_simple" data-product_id="<?php echo $id?>" data-quantity="1"></a>
                        <?php endif;?>    
                    </div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_share_icons']) && isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && is_array($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && count($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'])>0): ?>
                    <div class="woo-sharebtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'share_product_title')=='' ? __('Share This',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'share_product_title'));?>" >
                        <div class="woo-shareicon-cnt">
                                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('facebook' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink(); ?>"><i class="fa fa-facebook"></i></a>
                                <?php endif; ?>
                                
								<?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('twitter' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="https://twitter.com/home?status=Currentlyreading<?php echo get_the_permalink(); ?>"><i class="fa fa-twitter"></i></a>
                                <?php endif; ?>
                                
                                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('google_plus' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="https://plus.google.com/share?url=<?php echo get_the_permalink(); ?>"><i class="fa fa-google-plus"></i></a>
                                <?php endif; ?>
                                
                                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('pinterest' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="http://www.pinterest.com/pin/create/button/?url=<?php echo get_the_permalink(); ?>&description=<?php echo get_the_title(); ?>"><i class="fa fa-pinterest"></i></a>
                                <?php endif; ?>
                                
                                
                            </div>
                        <i class="fa fa-share"></i>
                    </div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'quickview_style'])): ?>
	                <div class="woo-quickviewbtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'quick_view_title')=='' ? __('Quick View',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'quick_view_title'));?>"><i class="fa fa-eye" data-property-id="<?php echo $id?>" ></i></div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_send_to'])): ?>
	                <div class="woo-sendbtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_title')=='' ? __('Send to a friend',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_title'));?>"><i class="fa fa-envelope" data-property-id="<?php echo $id?>" ></i></div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_zoom'])): ?>
                    <div class="woo-icon" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'zoom_title')=='' ? __('Big Image',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'zoom_title'));?>" ><a class="example-image-link" data-lightbox="example-<?php echo rand(0,2000); ?>" href="<?php echo $img1[0]; ?>"><i class="fa fa-search-plus"></i></a></div>
                <?php endif; ?>
            </div>
        </div>
    </div><!-- woo-product-cnt-->
</div>
<?php endif; ?>

<?php if ($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'grid_box_effect_type']=='effect3'): ?>
	<div class="woo-col-md-<?php echo $col_array[$counter]; ?> woo-col-xs-12 wg-svg-col"  data-path-hover="m 0,0 0,47.7775 c 24.580441,3.12569 55.897012,-8.199417 90,-8.199417 34.10299,0 65.41956,11.325107 90,8.199417 L 180,0 z">
        <div class="woo-product-cnt woo-boxed-eff-three">
            <?php if ( $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'image_effect_type']=='second_image'): ?>
                <div class="woo-secondimg <?php echo $image_ratio ?>" style="background:url(<?php echo $img2[0] ?>);">
                    <img src="<?php echo $img2[0] ?>"  />
                </div>
            <?php endif; ?>
            <div class="thumb-divback <?php echo $image_ratio .' '.$image_eff; ?>" style="background:url(<?php echo $img1[0] ?>);">
                    <img src="<?php echo $img1[0] ?>" alt="<?php echo $title; ?>" />
            </div>
            <div class="svg-cnt">
	            <svg viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M0,0C0,0,0,171.14385,0,171.14385C24.580441,186.61523,55.897012,195.90157,90,195.90157C124.10299,195.90157,155.41956,186.61523,180,171.14385C180,171.14385,180,0,180,0C180,0,0,0,0,0C0,0,0,0,0,0"></path><desc>Created with Snap</desc><defs></defs></svg>
            </div>
            <div class="woo-overlay-cnt">
                <?php if (isset($custom_item_fields['title'])): ?>
                    <h3 class="woo-product-title"><a href="<?php echo get_the_permalink(); ?>"  target="<?php echo $link_target;?>"><?php echo $title; ?></a></h3>
                <?php endif; ?>
                <?php
					$show_tags_cats='';
					if (isset($custom_item_fields['category']) && ($cat!='')): 
						$show_tags_cats=$cat;
					endif;	
					
					if (isset($custom_item_fields['tag']) && ($tag!='')): 
						if($show_tags_cats=='')
							$show_tags_cats=$tag;			
						else
							$show_tags_cats.='/'.$tag;	
					endif;
					
					if($show_tags_cats!='')	:	
				?>
                    <div class="woo-meta woo-product-category"><span class="woo-meta-title"><i class="fa fa-tags"></i></span><?php echo $show_tags_cats; ?></div>
                <?php endif; ?>
                <?php if (isset($custom_item_fields['comment'])): ?>
                    <div class="woo-meta woo-meta-comment"><span class="woo-meta-title"><i class="fa fa-comment"></i></span><a href="<?php echo $comment_link; ?>"><?php echo $comment; ?></a></div>
                <?php endif; ?>
                <?php if (isset($custom_item_fields['price'])): ?>
				<?php 
						 echo '<div class="woo-product-price">'.$price.'</div>';
					?>
				<?php endif; ?>
                
				<?php if (isset($custom_item_fields['excerpt'])): ?>
                    <div class="woo-product-desc"><?php echo  $pw_general_ad_main_class->excerpt(get_the_excerpt(),$pw_general_ad_main_class->check_isset(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'excerpt_len','custom_field','10')); ?></div>
                <?php endif; ?>
                <?php if (isset($custom_item_fields['star'])): ?>
					<?php echo '<div class="woo-starcnt">'. pw_general_ad_search_rating_grid($my_query->post->ID) .'</div>'; ?>
                <?php endif ?>
                <div  class="woo-btns <?php echo $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'icon_style']; ?>"  >
                	<?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_read_more'])): ?>
                        <div class="woo-readmore" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title')=='' ? __('Read More',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title'));?>"><a href="<?php echo get_the_permalink(); ?>"  target="<?php echo $link_target;?>"><i class="fa fa-link"  ></i></a></div>
                    <?php endif; ?>
				<?php 
					$favorite_status='pw-general-ad-search-unfavorite';
					if(isset($_COOKIE['pw_general_ad_search_favorit_cookie']))
					{
						$favorites=explode(',',$_COOKIE['pw_general_ad_search_favorit_cookie']);
						if(is_array($favorites) && in_array($id,$favorites))
							$favorite_status='pw-general-ad-search-favorite';
					}
					
					if (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_enable_favorite_use')=='on' && isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_favorite'])): ?>
                	<div class="woo-addfav" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'add_to_favorite_title')=='' ? __('Add to favorite',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'add_to_favorite_title')); ?>"><i data-property-id="<?php echo $id?>" class="fa fa-heart <?php echo $favorite_status;?>"></i></div>
                <?php endif; ?>
                <?php if (isset($custom_item_fields['add_cart'])): ?>
                	<div class="woo-addcart" title="<?php echo $add_to_cart_label;?>">
						<?php if ($add_to_cart_has_tag_a): ?>
                            <?php echo $add_to_cart_link; ?>
                        <?php else: ?>
                                 <a href="<?php echo $add_to_cart_link; ?>" class="add_to_cart_button product_type_simple" data-product_id="<?php echo $id?>" data-quantity="1"></a>
                        <?php endif;?>    
                    </div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_share_icons']) && isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && is_array($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && count($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'])>0): ?>
                    <div class="woo-sharebtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'share_product_title')=='' ? __('Share This',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'share_product_title'));?>" >
                        <div class="woo-shareicon-cnt">
                                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('facebook' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink(); ?>"><i class="fa fa-facebook"></i></a>
                                <?php endif; ?>
                                
								<?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('twitter' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="https://twitter.com/home?status=Currentlyreading<?php echo get_the_permalink(); ?>"><i class="fa fa-twitter"></i></a>
                                <?php endif; ?>
                                
                                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('google_plus' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="https://plus.google.com/share?url=<?php echo get_the_permalink(); ?>"><i class="fa fa-google-plus"></i></a>
                                <?php endif; ?>
                                
                                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('pinterest' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="http://www.pinterest.com/pin/create/button/?url=<?php echo get_the_permalink(); ?>&description=<?php echo get_the_title(); ?>"><i class="fa fa-pinterest"></i></a>
                                <?php endif; ?>
                                
                                
                            </div>
                        <i class="fa fa-share"></i>
                    </div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'quickview_style'])): ?>
	                <div class="woo-quickviewbtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'quick_view_title')=='' ? __('Quick View',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'quick_view_title'));?>"><i class="fa fa-eye" data-property-id="<?php echo $id?>" ></i></div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_send_to'])): ?>
	                <div class="woo-sendbtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_title')=='' ? __('Send to a friend',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_title'));?>"><i class="fa fa-envelope" data-property-id="<?php echo $id?>" ></i></div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_zoom'])): ?>
                    <div class="woo-icon" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'zoom_title')=='' ? __('Big Image',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'zoom_title'));?>" ><a class="example-image-link" data-lightbox="example-<?php echo rand(0,2000); ?>" href="<?php echo $img1[0]; ?>"><i class="fa fa-search-plus"></i></a></div>
                <?php endif; ?>
            </div>
            </div>
            <?php if (isset($custom_item_fields['author'])): ?>
                <div class="woo-banner sale-banner" ><i class="fa fa-user"></i><a href="<?php echo $author_link ?>"><?php echo $author; ?></a></div>
            <?php endif; ?>
            
            <?php if (isset($custom_item_fields['date'])): ?>
                <div class="woo-banner feature-banner" ><i class="fa fa-calendar"></i><?php echo $date; ?></div>
            <?php endif; ?>
            
        </div>
    </div>
<?php endif; ?>

<?php if ($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'grid_box_effect_type']=='effect4'): ?>
	<div class="woo-col-md-<?php echo $col_array[$counter]; ?> woo-col-xs-12 wg-svg-col"   data-path-hover="m 180,34.57627 -180,0 L 0,0 180,0 z">
        <div class="woo-product-cnt woo-boxed-eff-three">
            <?php if ( $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'image_effect_type']=='second_image'): ?>
                <div class="woo-secondimg <?php echo $image_ratio ?>" style="background:url(<?php echo $img2[0] ?>);">
                    <img src="<?php echo $img2[0] ?>"  />
                </div>
            <?php endif; ?>
            <div class="thumb-divback <?php echo $image_ratio .' '.$image_eff; ?>" style="background:url(<?php echo $img1[0] ?>);">
                    <img src="<?php echo $img1[0] ?>" alt="<?php echo $title; ?>" />
            </div>
            <div class="svg-cnt">
            	<svg viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M180,160C180,160,0,218,0,218C0,218,0,0,0,0C0,0,180,0,180,0C180,0,180,160,180,160"></path><desc>Created with Snap</desc><defs></defs></svg>
            </div>
            <div class="woo-overlay-cnt">
                <?php if (isset($custom_item_fields['title'])): ?>
                    <h3 class="woo-product-title"><a href="<?php echo get_the_permalink(); ?>" target="<?php echo $link_target;?>"><?php echo $title; ?></a></h3>
                <?php endif; ?>
                <?php 
					$show_tags_cats='';
					if (isset($custom_item_fields['category']) && ($cat!='')): 
						$show_tags_cats=$cat;
					endif;	
					
					if (isset($custom_item_fields['tag']) && ($tag!='')): 
						if($show_tags_cats=='')
							$show_tags_cats=$tag;			
						else
							$show_tags_cats.='/'.$tag;	
					endif;
					
					if($show_tags_cats!='')	:	
				?>
                    <div class="woo-meta woo-product-category"><span class="woo-meta-title"><i class="fa fa-tags"></i></span><?php echo $show_tags_cats; ?></div>
                <?php endif; ?>
                <?php if (isset($custom_item_fields['comment'])): ?>
                    <div class="woo-meta woo-meta-comment"><span class="woo-meta-title"><i class="fa fa-comment"></i></span><a href="<?php echo $comment_link; ?>"><?php echo $comment; ?></a></div>
                <?php endif; ?>
                <?php if (isset($custom_item_fields['price'])): ?>
				<?php 
					 echo '<div class="woo-product-price">'.$price.'</div>';
					?>
				<?php endif; ?>
                
				<?php if (isset($custom_item_fields['excerpt'])): ?>
                    <div class="woo-product-desc"><?php echo  $pw_general_ad_main_class->excerpt(get_the_excerpt(),$pw_general_ad_main_class->check_isset(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'excerpt_len','custom_field','10')); ?></div>
                <?php endif; ?>
                <?php if (isset($custom_item_fields['star'])): ?>
					<?php echo '<div class="woo-starcnt">'. pw_general_ad_search_rating_grid($my_query->post->ID) .'</div>'; ?>
                <?php endif ?>
                <div  class="woo-btns <?php echo $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'icon_style']; ?>"  >
                	<?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_read_more'])): ?>
                        <div class="woo-readmore" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title')=='' ? __('Read More',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title'));?>"><a href="<?php echo get_the_permalink(); ?>" target="<?php echo $link_target;?>"><i class="fa fa-link"  ></i></a></div>
                    <?php endif; ?>
					<?php 
					$favorite_status='pw-general-ad-search-unfavorite';
					if(isset($_COOKIE['pw_general_ad_search_favorit_cookie']))
					{
						$favorites=explode(',',$_COOKIE['pw_general_ad_search_favorit_cookie']);
						if(is_array($favorites) && in_array($id,$favorites))
							$favorite_status='pw-general-ad-search-favorite';
					}
					if (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_enable_favorite_use')=='on' && isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_favorite'])): ?>
                	<div class="woo-addfav" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'add_to_favorite_title')=='' ? __('Add to favorite',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'add_to_favorite_title')); ?>"><i data-property-id="<?php echo $id?>" class="fa fa-heart <?php echo $favorite_status;?>"></i></div>
                <?php endif; ?>
                <?php if (isset($custom_item_fields['add_cart'])): ?>
                     <div class="woo-addcart" title="<?php echo $add_to_cart_label;?>">
                        <?php if ($add_to_cart_has_tag_a): ?>
                            <?php echo $add_to_cart_link; ?>
                        <?php else: ?>
                                 <a href="<?php echo $add_to_cart_link; ?>" class="add_to_cart_button product_type_simple" data-product_id="<?php echo $id?>" data-quantity="1"></a>
                        <?php endif;?>    
                    </div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_share_icons']) && isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && is_array($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && count($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'])>0): ?>
                    <div class="woo-sharebtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'share_product_title')=='' ? __('Share This',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'share_product_title'));?>" >
                        <div class="woo-shareicon-cnt">
                                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('facebook' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink(); ?>"><i class="fa fa-facebook"></i></a>
                                <?php endif; ?>
                                
								<?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('twitter' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="https://twitter.com/home?status=Currentlyreading<?php echo get_the_permalink(); ?>"><i class="fa fa-twitter"></i></a>
                                <?php endif; ?>
                                
                                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('google_plus' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="https://plus.google.com/share?url=<?php echo get_the_permalink(); ?>"><i class="fa fa-google-plus"></i></a>
                                <?php endif; ?>
                                
                                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('pinterest' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="http://www.pinterest.com/pin/create/button/?url=<?php echo get_the_permalink(); ?>&description=<?php echo get_the_title(); ?>"><i class="fa fa-pinterest"></i></a>
                                <?php endif; ?>
                                
                                
                            </div>
                        <i class="fa fa-share"></i>
                    </div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'quickview_style'])): ?>
	                <div class="woo-quickviewbtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'quick_view_title')=='' ? __('Quick View',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'quick_view_title'));?>"><i class="fa fa-eye" data-property-id="<?php echo $id?>" ></i></div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_send_to'])): ?>
	                <div class="woo-sendbtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_title')=='' ? __('Send to a friend',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_title'));?>"><i class="fa fa-envelope" data-property-id="<?php echo $id?>" ></i></div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_zoom'])): ?>
                    <div class="woo-icon" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'zoom_title')=='' ? __('Big Image',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'zoom_title'));?>" ><a class="example-image-link" data-lightbox="example-<?php echo rand(0,2000); ?>" href="<?php echo $img1[0]; ?>"><i class="fa fa-search-plus"></i></a></div>
                <?php endif; ?>
            </div>
            </div>
            <?php if (isset($custom_item_fields['author'])): ?>
                <div class="woo-banner sale-banner" ><i class="fa fa-user"></i><a href="<?php echo $author_link ?>"><?php echo $author; ?></a></div>
            <?php endif; ?>
            
            <?php if (isset($custom_item_fields['date'])): ?>
                <div class="woo-banner feature-banner" ><i class="fa fa-calendar"></i><?php echo $date; ?></div>
            <?php endif; ?>
            
        </div>
    </div>
<?php endif; ?>
<?php if ($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'grid_box_effect_type']=='effect5'): ?>
	<div class="woo-col-md-<?php echo $col_array[$counter]; ?> woo-col-xs-12 wg-svg-col"  data-path-hover="M 0,0 0,38 90,58 180.5,38 180,0 z">
        <div class="woo-product-cnt woo-boxed-eff-three">
            <?php if ( $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'image_effect_type']=='second_image'): ?>
                <div class="woo-secondimg <?php echo $image_ratio ?>" style="background:url(<?php echo $img2[0] ?>);">
                    <img src="<?php echo $img2[0] ?>"  />
                </div>
            <?php endif; ?>
            <div class="thumb-divback <?php echo $image_ratio .' '.$image_eff; ?>" style="background:url(<?php echo $img1[0] ?>);">
                    <img src="<?php echo $img1[0] ?>" alt="<?php echo $title; ?>" />
            </div>
            <div class="svg-cnt">
	            <svg viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M0,0C0,0,0,182,0,182C0,182,90,126.5,90,126.5C90,126.5,180,182,180,182C180,182,180,0,180,0C180,0,0,0,0,0C0,0,0,0,0,0"></path><desc>Created with Snap</desc><defs></defs></svg>
            </div>
            <div class="woo-overlay-cnt">
                <?php if (isset($custom_item_fields['title'])): ?>
                    <h3 class="woo-product-title"><a href="<?php echo get_the_permalink(); ?>" target="<?php echo $link_target;?>"><?php echo $title; ?></a></h3>
                <?php endif; ?>
                <?php 
					$show_tags_cats='';
					if (isset($custom_item_fields['category']) && ($cat!='')): 
						$show_tags_cats=$cat;
					endif;	
					
					if (isset($custom_item_fields['tag']) && ($tag!='')): 
						if($show_tags_cats=='')
							$show_tags_cats=$tag;			
						else
							$show_tags_cats.='/'.$tag;	
					endif;
					
					if($show_tags_cats!='')	:	
				?>
                    <div class="woo-meta woo-product-category"><span class="woo-meta-title"><i class="fa fa-tags"></i></span><?php echo $show_tags_cats; ?></div>
                <?php endif; ?>
                <?php if (isset($custom_item_fields['comment'])): ?>
                    <div class="woo-meta woo-meta-comment"><span class="woo-meta-title"><i class="fa fa-comment"></i></span><a href="<?php echo $comment_link; ?>"><?php echo $comment; ?></a></div>
                <?php endif; ?>
                <?php if (isset($custom_item_fields['price'])): ?>
				<?php 
					 echo '<div class="woo-product-price">'.$price.'</div>';
					?>
				<?php endif; ?>
                
				<?php if (isset($custom_item_fields['excerpt'])): ?>
                    <div class="woo-product-desc"><?php echo  $pw_general_ad_main_class->excerpt(get_the_excerpt(),$pw_general_ad_main_class->check_isset(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'excerpt_len','custom_field','10')); ?></div>
                <?php endif; ?>
                <?php if (isset($custom_item_fields['star'])): ?>
					<?php echo '<div class="woo-starcnt">'. pw_general_ad_search_rating_grid($my_query->post->ID) .'</div>'; ?>
                <?php endif ?>
                <div  class="woo-btns <?php echo $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'icon_style']; ?>"  >
                	<?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_read_more'])): ?>
                        <div class="woo-readmore" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title')=='' ? __('Read More',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title'));?>"><a href="<?php echo get_the_permalink(); ?>" target="<?php echo $link_target;?>"><i class="fa fa-link"  ></i></a></div>
                    <?php endif; ?>
					<?php 
					$favorite_status='pw-general-ad-search-unfavorite';
					if(isset($_COOKIE['pw_general_ad_search_favorit_cookie']))
					{
						$favorites=explode(',',$_COOKIE['pw_general_ad_search_favorit_cookie']);
						if(is_array($favorites) && in_array($id,$favorites))
							$favorite_status='pw-general-ad-search-favorite';
					}
					if (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_enable_favorite_use')=='on' && isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_favorite'])): ?>
                	<div class="woo-addfav" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'add_to_favorite_title')=='' ? __('Add to favorite',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'add_to_favorite_title')); ?>"><i data-property-id="<?php echo $id?>" class="fa fa-heart <?php echo $favorite_status;?>"></i></div>
                <?php endif; ?>
                <?php if (isset($custom_item_fields['add_cart'])): ?>
                	<div class="woo-addcart" title="<?php echo $add_to_cart_label;?>">
						<?php if ($add_to_cart_has_tag_a): ?>
                            <?php echo $add_to_cart_link; ?>
                        <?php else: ?>
                                 <a href="<?php echo $add_to_cart_link; ?>" class="add_to_cart_button product_type_simple" data-product_id="<?php echo $id?>" data-quantity="1"></a>
                        <?php endif;?>    
                    </div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_share_icons']) && isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && is_array($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && count($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'])>0): ?>
                    <div class="woo-sharebtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'share_product_title')=='' ? __('Share This',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'share_product_title'));?>" >
                        <div class="woo-shareicon-cnt">
                                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('facebook' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink(); ?>"><i class="fa fa-facebook"></i></a>
                                <?php endif; ?>
                                
								<?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('twitter' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="https://twitter.com/home?status=Currentlyreading<?php echo get_the_permalink(); ?>"><i class="fa fa-twitter"></i></a>
                                <?php endif; ?>
                                
                                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('google_plus' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="https://plus.google.com/share?url=<?php echo get_the_permalink(); ?>"><i class="fa fa-google-plus"></i></a>
                                <?php endif; ?>
                                
                                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('pinterest' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
	                                	<a target="_blank" href="http://www.pinterest.com/pin/create/button/?url=<?php echo get_the_permalink(); ?>&description=<?php echo get_the_title(); ?>"><i class="fa fa-pinterest"></i></a>
                                <?php endif; ?>
                                
                                
                            </div>
                        <i class="fa fa-share"></i>
                    </div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'quickview_style'])): ?>
	                <div class="woo-quickviewbtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'quick_view_title')=='' ? __('Quick View',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'quick_view_title'));?>"><i class="fa fa-eye" data-property-id="<?php echo $id?>" ></i></div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_send_to'])): ?>
	                <div class="woo-sendbtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_title')=='' ? __('Send to a friend',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_title'));?>"><i class="fa fa-envelope" data-property-id="<?php echo $id?>" ></i></div>
                <?php endif; ?>
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_zoom'])): ?>
                    <div class="woo-icon" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'zoom_title')=='' ? __('Big Image',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'zoom_title'));?>" ><a class="example-image-link" data-lightbox="example-<?php echo rand(0,2000); ?>" href="<?php echo $img1[0]; ?>"><i class="fa fa-search-plus"></i></a></div>
                <?php endif; ?>
            </div>
            </div>
            <?php if (isset($custom_item_fields['author'])): ?>
                <div class="woo-banner sale-banner" ><i class="fa fa-user"></i><a href="<?php echo $author_link ?>"><?php echo $author; ?></a></div>
            <?php endif; ?>
            
            <?php if (isset($custom_item_fields['date'])): ?>
                <div class="woo-banner feature-banner" ><i class="fa fa-calendar"></i><?php echo $date; ?></div>
            <?php endif; ?>
            
        </div>
    </div>
<?php endif; ?>
<?php 
	$effect_type=$pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'grid_box_effect_type'];
	if ($effect_type=='effect6' || $effect_type=='effect7' || $effect_type=='effect8' || $effect_type=='effect9' || $effect_type=='effect10' || $effect_type=='effect11' || $effect_type=='effect12' || $effect_type=='effect13' || $effect_type=='effect14'): ?>
	<div class="woo-col-md-<?php echo $col_array[$counter]; ?> woo-col-xs-12">
    
        <div class="woo-product-cnt woo-boxed-view woo-gst-effect-<?php echo $effect_type ?>">
            <?php if ( $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'image_effect_type']=='second_image'): ?>
                <div class="woo-secondimg <?php echo $image_ratio ?>" style="background:url(<?php echo $img2[0] ?>);">
                    <img src="<?php echo $img2[0] ?>"  />
                </div>
            <?php endif; ?>
            <div class="thumb-divback <?php echo $image_ratio .' '.$image_eff; ?>" style="background:url(<?php echo $img1[0] ?>);">
                    <img src="<?php echo $img1[0] ?>" alt="<?php echo $title; ?>" />
            </div>
            
            <div class="woo-mask">
					<?php if (isset($custom_item_fields['title'])): ?>
                        <h3 class="woo-product-title"><a  href="<?php echo get_the_permalink(); ?>" target="<?php echo $link_target;?>"><?php echo $title; ?></a></h3>
                    <?php endif; ?>
                
                <div class="woo-title-meta">
                	<?php if (isset($custom_item_fields['author'])): ?>
                        <div class="woo-meta" ><span class="woo-meta-title"><i class="fa fa-user"></i></span><a href="<?php echo $author_link ?>"><?php echo $author; ?></a></div>
                    <?php endif; ?>
                    
                    <?php if (isset($custom_item_fields['date'])): ?>
                        <div class="woo-meta" ><span class="woo-meta-title"><i class="fa fa-calendar"></i></span><?php echo $date; ?></div>
                    <?php endif; ?>
                    
                    <?php
						$show_tags_cats='';
						if (isset($custom_item_fields['category']) && ($cat!='')): 
							$show_tags_cats=$cat;
						endif;	
						
						if (isset($custom_item_fields['tag']) && ($tag!='')): 
							if($show_tags_cats=='')
								$show_tags_cats=$tag;			
							else
								$show_tags_cats.='/'.$tag;	
						endif;
						
						if($show_tags_cats!='')	:	
					?>
                        <div class="woo-meta woo-product-category"><span class="woo-meta-title"><i class="fa fa-tags"></i></span><?php echo $show_tags_cats; ?></div>
                    <?php endif; ?>
                    <?php if (isset($custom_item_fields['comment'])): ?>
                        <div class="woo-meta woo-meta-comment"><span class="woo-meta-title"><i class="fa fa-comment"></i></span><a href="<?php echo $comment_link; ?>"><?php echo $comment; ?></a></div>
                    <?php endif; ?>
                    
					<?php if (isset($custom_item_fields['excerpt'])): ?>
                        <div class="woo-product-desc">
							<?php echo  $pw_general_ad_main_class->excerpt(get_the_excerpt(),$pw_general_ad_main_class->check_isset(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'excerpt_len','custom_field','10')); ?>
                        </div>
                    <?php endif; ?>
                    <div  class="woo-btns <?php echo $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'icon_style']; ?>"  >
						<?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_read_more'])): ?>
                            <div class="woo-readmore" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title')=='' ? __('Read More',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title'));?>"><a href="<?php echo get_the_permalink(); ?>" target="<?php echo $link_target;?>"><i class="fa fa-link"  ></i></a></div>
                        <?php endif; ?>
						<?php 
                        $favorite_status='pw-general-ad-search-unfavorite';
                        if(isset($_COOKIE['pw_general_ad_search_favorit_cookie']))
                        {
                            $favorites=explode(',',$_COOKIE['pw_general_ad_search_favorit_cookie']);
                            if(is_array($favorites) && in_array($id,$favorites))
                                $favorite_status='pw-general-ad-search-favorite';
                        }
                        if (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_enable_favorite_use')=='on' && isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_favorite'])): ?>
                        <div class="woo-addfav" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'add_to_favorite_title')=='' ? __('Add to favorite',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'add_to_favorite_title')); ?>"><i data-property-id="<?php echo $id?>" class="fa fa-heart <?php echo $favorite_status;?>"></i></div>
                    <?php endif; ?>
                    <?php if (isset($custom_item_fields['add_cart'])): ?>
                        <div class="woo-addcart" title="<?php echo $add_to_cart_label;?>">
                            <?php if ($add_to_cart_has_tag_a): ?>
                                <?php echo $add_to_cart_link; ?>
                            <?php else: ?>
                                     <a href="<?php echo $add_to_cart_link; ?>" class="add_to_cart_button product_type_simple" data-product_id="<?php echo $id?>" data-quantity="1"></a>
                            <?php endif;?>    
                        </div>
                    <?php endif; ?>
                    <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_share_icons']) && isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && is_array($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && count($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'])>0): ?>
                        <div class="woo-sharebtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'share_product_title')=='' ? __('Share This',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'share_product_title'));?>" >
                            <div class="woo-shareicon-cnt">
                                    <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('facebook' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
                                            <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink(); ?>"><i class="fa fa-facebook"></i></a>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('twitter' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
                                            <a target="_blank" href="https://twitter.com/home?status=Currentlyreading<?php echo get_the_permalink(); ?>"><i class="fa fa-twitter"></i></a>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('google_plus' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
                                            <a target="_blank" href="https://plus.google.com/share?url=<?php echo get_the_permalink(); ?>"><i class="fa fa-google-plus"></i></a>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']) && (in_array('pinterest' , $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons'] ))): ?>
                                            <a target="_blank" href="http://www.pinterest.com/pin/create/button/?url=<?php echo get_the_permalink(); ?>&description=<?php echo get_the_title(); ?>"><i class="fa fa-pinterest"></i></a>
                                    <?php endif; ?>
                                    
                                    
                                </div>
                            <i class="fa fa-share"></i>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'quickview_style'])): ?>
                        <div class="woo-quickviewbtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'quick_view_title')=='' ? __('Quick View',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'quick_view_title'));?>"><i class="fa fa-eye" data-property-id="<?php echo $id?>" ></i></div>
                    <?php endif; ?>
                    <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_send_to'])): ?>
                        <div class="woo-sendbtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_title')=='' ? __('Send to a friend',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'sendto_form_title'));?>"><i class="fa fa-envelope" data-property-id="<?php echo $id?>" ></i></div>
                    <?php endif; ?>
                    <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_zoom'])): ?>
                        <div class="woo-icon" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'zoom_title')=='' ? __('Big Image',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'zoom_title'));?>" ><a class="example-image-link" data-lightbox="example-<?php echo rand(0,2000); ?>" href="<?php echo $img1[0]; ?>"><i class="fa fa-search-plus"></i></a></div>
                    <?php endif; ?>
                </div>
                </div><!-- woo-title-meta -->
            </div><!--woo-mask -->
        </div>
    </div>
<?php endif; ?>


