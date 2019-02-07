<?php 
	global $pw_general_ad_main_class; 
	$custom_item_fields = $pw_general_ad_main_class->check_isset(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'item_fields','custom_field','');

	$btn_option = $pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'set_btn_option'];
	//print_r($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'share_icons']);
?>
<?php 
if (($row_counter==1 && 
 		((($pw_sf_pagination_type=='pagination_showmore' || $pw_sf_pagination_type=='pagination_infinite' ) && $pw_sf_pagination_paged==1)  || ($pw_sf_pagination_type=='pagination_pagenumber_hor' || $pw_sf_pagination_type=='pagination_pagenumber_ver'))) || ($row_counter==1 && $pw_sf_pagination_type=='no_pagination')):
	
 /*if ($row_counter==1 && 
 		(($pw_sf_pagination_type=='pagination_showmore' || $pw_sf_pagination_type=='pagination_infinite' ) && $pw_sf_pagination_paged==1) 
			):*/

//if ($row_counter==1): 
	$post_lbl='';
	if(isset($_POST['pw_sf_post_type'])){
		$obj = get_post_type_object( $_POST['pw_sf_post_type'] );
		$post_lbl=$obj->labels->singular_name;
	}
?>	
    <thead>
    	<?php if (isset($custom_item_fields['thumbnail'])): ?>
	    	<th class="wg-table-thumbnail"></th>
        <?php endif; ?>
        <?php if ( (isset($custom_item_fields['title'])) || (isset($custom_item_fields['category'])) ): ?>
	        <th class="wg-table-name"><?php echo $post_lbl; ?></th>
        <?php endif; ?>
        <?php if (isset($custom_item_fields['star'])): ?>
        	<th class="wg-table-rating"><?php echo __('Rating',__PW_WOO_AD_SEARCH_TEXTDOMAIN__); ?></th>
		<?php endif; ?>
        <?php if (isset($custom_item_fields['sku'])): ?>
	        <th class="wg-table-sku"><?php echo __('SKU',__PW_WOO_AD_SEARCH_TEXTDOMAIN__); ?></th>
        <?php endif; ?>
        <?php if (isset($custom_item_fields['out_stock'])): ?>
        	<th class="wg-table-stock"><?php echo __('STOCK',__PW_WOO_AD_SEARCH_TEXTDOMAIN__); ?></th>
		<?php endif; ?>
        <?php if (isset($custom_item_fields['price'])): ?>
        	<th class="wg-table-price"><?php echo __('Price',__PW_WOO_AD_SEARCH_TEXTDOMAIN__); ?></th>
		<?php endif; ?>
	        <th class="wg-table-button"> </th>
        <?php if (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_enable_favorite_use')=='on' && isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_favorite']) || isset($custom_item_fields['add_cart'])): ?>
        <?php endif; ?>
    </thead>
<?php endif; ?>
    
    <tr>
    	<?php if (isset($custom_item_fields['thumbnail'])): ?>
            <td>
               	<a href="<?php echo get_the_permalink(); ?>" target="<?php echo $link_target;?>">
                	<img src="<?php echo $img1[0] ?>" class="<?php echo $image_eff; ?>" alt="<?php echo $title; ?>" />
                </a>
                <?php if (($price!='') && (isset($custom_item_fields['sale'])) ){ ?>
                    <div class="woo-banner sale-banner" ><?php echo __('sale',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__); ?></div>
                <?php } ?>
                
                <?php if (($featured=='yes')&& (isset($custom_item_fields['featured']))){ ?>
                    <div class="woo-banner feature-banner" ><?php echo __('featured',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__); ?></div>
                <?php } ?>
            </td>
		<?php endif; ?>
         <?php if ( (isset($custom_item_fields['title'])) || (isset($custom_item_fields['category'])) || (isset($custom_item_fields['excerpt']))): ?>
	        <td class="wg-table-td wg-table-td-title">
            	<?php if (isset($custom_item_fields['title'])):?>	
                    <h3 class="woo-product-title"><a href="<?php echo get_the_permalink(); ?>" target="<?php echo $link_target;?>"><?php echo $title; ?></a></h3>
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
            </td> 
		<?php endif; ?>
        <?php if (isset($custom_item_fields['star'])): ?>
			<td class="wg-table-td"><?php echo '<div class="woo-starcnt">'.pw_general_ad_search_rating_grid($my_query->post->ID).'</div>'; ?></td>
        <?php endif ?>
		<?php if (isset($custom_item_fields['sku'])): ?>
        	<td class="wg-table-td"><?php echo $sku ?></td>
        <?php endif; ?>
		<?php if (isset($custom_item_fields['out_stock'])): ?>
        	<td class="wg-table-td">
				<?php 
					if ($stock_status == 'instock' ){
						echo __('In Stock',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__);
					}
					else if($stock_status == 'outstock' ){
						echo __('Out Stock',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__);
					}
				?>
            </td>
        <?php endif; ?>
        <?php if (isset($custom_item_fields['price'])): ?>
			<?php 
             echo '<td class="wg-table-td"><span class="woo-product-price">'.$price.'</span></td>';
            ?>
        <?php endif; ?>
        <?php if ((get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_enable_favorite_use')=='on' && isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_favorite'])) || isset($custom_item_fields['add_cart']) || isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_share_icons']) || isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_send_to']) ): ?>
        	<td class="wg-table-td">
            	<?php if (isset($custom_item_fields['read_more'])): ?>
                    <div class="woo-addcard-btn woo-list-style2-btn <?php echo $btn_option['type']; ?>">
                         <a href="<?php echo get_the_permalink(); ?>" target="<?php echo $link_target;?>"><?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title')=='' ? __('Read More',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'read_more_title')); ?></a>
                    </div>
                <?php endif; ?>
				<?php 
					$favorite_status='pw-general-ad-search-unfavorite';
					if(isset($_COOKIE['pw_general_ad_search_favorit_cookie']))
					{
						$favorites=explode(',',$_COOKIE['pw_general_ad_search_favorit_cookie']);
						if(is_array($favorites) && in_array($my_query->post->ID,$favorites))
							$favorite_status='pw-general-ad-search-favorite';
					}
					
					if (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_enable_favorite_use')=='on' && isset($pw_general_ad_main_class->custom_field[__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'show_favorite'])): ?>
                	<div class="woo-addfav-btn <?php echo $btn_option['type']; ?>"><a href="#"><span><i data-property-id="<?php echo $id?>" class="fa fa-heart <?php echo $favorite_status;?>"></i><?php echo (get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'add_to_favorite_title')=='' ? __('Add to favorite',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__) : get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'add_to_favorite_title')) ?></span></a></div>
        		<?php endif; ?>
                <?php if (isset($custom_item_fields['add_cart'])): ?>
                    <div class="woo-addcard-btn <?php echo $btn_option['type']; ?>">
						<?php if ($add_to_cart_has_tag_a): ?>
                            <?php echo $add_to_cart_link; ?>
                        <?php else: ?>
                                 <a href="<?php echo $add_to_cart_link; ?>" class="add_to_cart_button product_type_simple" data-product_id="<?php echo $id?>" data-quantity="1"><?php echo $add_to_cart_label;?></a>
                        <?php endif;?>    
                    </div>
                <?php endif; ?>
                
                
                <div  class="woo-btns <?php echo $btn_option['type']; ?>"  >
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
                </div>             
            </td>
        <?php endif; ?>   

    </tr>
