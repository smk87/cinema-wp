<?php 
	global $pw_general_ad_main_class; 
	$custom_item_fields = $pw_general_ad_main_class->check_isset(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'item_fields','custom_field','');
	
	$color_set = $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'color_set'];
	$rand_num = $color_number%9;
	$color_set_selected = $color_set['c'.$rand_num];
	$color_layout = $pw_general_ad_main_class->check_isset(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'colored_type','custom_field','woo-colored-layout1-cnt');
?>
<div class="<?php echo (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'enable_carousel']))?'woo-car-item':$desktop_col.' '.$tablet_col.' '.$mobile_col; ?> <?php echo $color_layout; ?>">
    <div class="woo-product-cnt">
    	
    </div>
    
    <div class="woo-product-cnt">
		<?php if ( $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'image_effect_type']=='second_image'): ?>
        	<a href="<?php echo get_the_permalink(); ?>"  target="<?php echo $link_target;?>">
                <div class="woo-secondimg <?php echo $image_ratio ?>" style="background:url(<?php echo $img2[0] ?>);">
                    <img src="<?php echo $img2[0] ?>"  />
                </div>
            </a>
        <?php endif; ?>
		<a href="<?php echo get_the_permalink(); ?>"  target="<?php echo $link_target;?>">
        	<div class="thumb-divback <?php echo $image_ratio .' '.$image_eff; ?>" style="background:url(<?php echo $img1[0] ?>);">
                <img src="<?php echo $img1[0] ?>" alt="<?php echo $title; ?>" />
            </div>
        </a>
        <?php if (($price!='') && (isset($custom_item_fields['sale'])) ){ ?>
        	<div class="woo-banner sale-banner" style="background:<?php echo $color_set_selected; ?>"><?php echo __('sale',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__); ?></div>
        <?php } ?>
        
		<?php if (($featured=='yes')&& (isset($custom_item_fields['featured']))){ ?>
        	<div class="woo-banner feature-banner" style="background:<?php echo $color_set_selected; ?>"><?php echo __('featured',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__); ?></div>
        <?php } ?>
        
        <div class="woo-overlay-cnt" style="background:<?php echo $color_set_selected; ?>" >
            <div  class="woo-btns" style="background:<?php echo $color_set_selected; ?>" >
                <?php if (isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_read_more'])): ?>
                    <div class="woo-sharebtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title')=='' ? __('Read More',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title'));?>"><a href="<?php echo get_the_permalink(); ?>"  target="<?php echo $link_target;?>"><i class="fa fa-link" ></i></a></div>
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
                    <div class="woo-sharebtn" title="<?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'zoom_title')=='' ? __('Big Image',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'zoom_title'));?>" ><a class="example-image-link" data-lightbox="example-<?php echo rand(0,2000); ?>" href="<?php echo $img1[0]; ?>"><i class="fa fa-search-plus"></i></a></div>
                <?php endif; ?>
            </div>     
            <?php if (isset($custom_item_fields['title'])): ?>
	            <h3 class="woo-product-title"><a href="<?php echo get_the_permalink(); ?>"  target="<?php echo $link_target;?>"><?php echo $title; ?></a></h3>
            <?php endif; ?>
            
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
                <div class="woo-meta woo-meta-date"><span class="woo-meta-title"><i class="fa fa-comment"></i></span><a href="<?php echo $comment_link; ?>"><?php echo $comment; ?></a></div>
            <?php endif; ?>
            <?php if (isset($custom_item_fields['excerpt'])): ?>
            	<div class="woo-product-desc"><?php echo  $pw_general_ad_main_class->excerpt(get_the_excerpt(),$pw_general_ad_main_class->check_isset(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'excerpt_len','custom_field','10')); ?></div>
            <?php endif; ?>
			<?php if (isset($custom_item_fields['price'])): ?>
            	<?php 
              		echo '<span class="woo-product-price">'.$price.'</span>';
				?>
			<?php endif; ?>
			<?php if (isset($custom_item_fields['star'])): ?>
				<?php echo pw_general_ad_search_rating_grid($my_query->post->ID); ?>
            <?php endif ?>
        </div>
    </div>
</div>
