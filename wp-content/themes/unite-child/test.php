<?php

/**
 * Template Name: Test
 */

get_header();

?>


<?php $args = array(
// Post or Page ID
	"p " => 105,
);

// The Query
$the_query = new WP_Query($args);

// The Loop
if ($the_query->have_posts()) {

	while ($the_query->have_posts()) {
		$the_query->the_post();
		echo get_post_meta(get_the_ID(), "price_list", true);
	}

/* Restore original Post Data */
	wp_reset_postdata();

} else {

	echo "Nothing found";

} ?>

<?php get_footer(); ?>