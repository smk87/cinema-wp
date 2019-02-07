<?php
	global $pw_general_ad_main_class,$wpdb,$post;
	$fav_count='';
	$output='';
	$fav_sticky_output='';
	//echo (isset($_COOKIE['pw_general_ad_search_favorit_cookie']) ? $_COOKIE['pw_general_ad_search_favorit_cookie']:"NO FAVORITS!");
	if(isset($_COOKIE['pw_general_ad_search_favorit_cookie']))
	{
		$your_favorites = $_COOKIE['pw_general_ad_search_favorit_cookie'];
		$your_favorites = explode( ',' , $your_favorites );
		$favorite_count=count($your_favorites)-1;
		$fav_count = $favorite_count;
		if(count($your_favorites)>1)
		{
			$query_by_id_in=array();
			foreach($your_favorites as $ids)
			{
				$query_by_id_in[]=$ids;
			}
			$fav_sticky_output.='<div id="wizards" style="border:none">';
			$favorite_for = get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'option_enable_favorite_for');
			foreach($favorite_for as $fav_for){
				$post_type_label=get_post_type_object( $fav_for );
				$post_type_label=$post_type_label->label ; 
				$fav_sticky_output.='
				<h4><i class="fa fa-magic"></i> '.$post_type_label.'</h4>
				<section>';
				
				$ars=array(
					'post_type' =>$fav_for,
					'post_status'=>'publish',
					'post__in'=>$query_by_id_in,
				);

				$original_query = $post;	
				$querys = new WP_Query($ars);
				$rand_id=rand(0,9999);
				
				if($querys->have_posts()){
					$fav_sticky_output .='<ul id="favorite_div_items_'.$fav_for.'" class="woo-bxslider woo-single-car  woo-carousel-layout ">';
					while ( $querys->have_posts() ) : $querys->the_post(); 
						$fetch_post_id=get_the_ID();
						require __PW_ROOT_GENERAL_AD_SEARCH__.'/includes/content-favorite.php';
					endwhile;
					$post = $original_query;
					wp_reset_postdata();
					
					$fav_sticky_output.='</ul>
						<script type="text/javascript">
							jQuery(document).ready(function(jQuery){
								
								
							});
						</script>';
				}else
				{
					$fav_sticky_output.=$pw_general_ad_main_class->alert('error',__('No Favorites Has Been Added!',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__));
				}
				$fav_sticky_output.='</section>';
			}//end for
			$fav_sticky_output.='</div>';
		
		}else
		{
			$fav_sticky_output.=$pw_general_ad_main_class->alert('error',__('No Favorites Has Been Added!',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__));
		}
		
	}else
	{
		$fav_sticky_output.=$pw_general_ad_main_class->alert('error',__('No Favorites Has Been Added!',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__));
	}
	
	$favorite_sticky_margin_top=(get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_favorite_margin')=='' ? '400':get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_favorite_margin'));
	
	$favorite_sticky_align=(get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_favorite_position')=='' ? 'right':get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_favorite_position'));
	
	$favorite_sticky_icon='<i class="fa fa-heart"></i>';
	if(get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_favorite_icon_type')!='')
	{
		$icon_type=get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_favorite_icon_type');
		
		if($icon_type=='fontawesome'){
			
		
			$favorite_sticky_icon='<i class="fa '.(get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_favorite_font_icon')!='' ? get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_favorite_font_icon'):"fa-heart").'"></i>';
		}else if($icon_type=='upload'){
			$icon_value=get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__.'option_favorite_upload_icon');
			
			$favorite_sticky_icon=wp_get_attachment_image( $icon_value , array(50,50),false,array("style"=>"margin:5px"));
		}
	}
	  
	
	$favorite_for = get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'option_enable_favorite_for');
	$js='
	<script type="text/javascript">
		jQuery(document).ready(function(){
			
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
				if(is_array($favorite_for))
				{
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
				}
					
				$js.='
				}
			});
			
		});
	</script>
	';
	
	$sticky_width = get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'option_favorite_cnt_width');
	$sticky_height = get_option(__PW_GENERAL_AD_SEARCH_FIELDS_PERFIX__ . 'option_favorite_cnt_height');
	
	$output='<div id="favorite_div_s" class="favorite-cnt">
		<div id="fav" data-id="fav" class="wt-pw-stick  pulsegrow-eff wt-pw-stick-light pw-'.$favorite_sticky_align.'-stick ltooltip" style="top:'.$favorite_sticky_margin_top.'px" title="'.__('Favorites',__PW_GENERAL_AD_SEARCH_TEXTDOMAIN__).'">
			<span class="wt-pw-title" rel="tipsye" >'.$favorite_sticky_icon.'</span>
			<div id="favorite_div_count" class="fav-count">
			 '.$fav_count.'
			</div>  
		</div>
		
		<div class="wt-pw-content wt-pw-content-light pw-content-fav wt-pw-content-'.$favorite_sticky_align.'  dis-fav" style="width:'.$sticky_width.'px;height:'.$sticky_height.'px;" >
			<div class="wt-pw-content-close"></div>
			<div id="favorite_div_content">
			   
				'.$fav_sticky_output.'
				
			    
			</div>    
		</div>
	</div>
	
	
	
	
	
	
	
	';

	echo $output.$js;
	
?>