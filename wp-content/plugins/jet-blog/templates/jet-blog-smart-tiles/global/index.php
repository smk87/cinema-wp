<?php
/**
 * Smart tiles main template
 */

global $post;

$settings = $this->get_settings();
$excerpt  = '';

if ( 'yes' === $settings['excerpt_on_hover'] ) {
	$excerpt = ' jet-hide-excerpt';
}
?>
<div class="<?php $this->__tiles_wrap_classes(); ?>" <?php $this->__slider_atts(); ?> dir="ltr"><?php

	foreach ( $this->__get_query() as $post ) {

		setup_postdata( $post );

		$this->__maybe_open_slide_wrapper( $settings );
		include $this->__get_global_template( 'post' );
		$this->__maybe_close_slide_wrapper( $settings );

	}

	$this->__reset_data();
?></div>