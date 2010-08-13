<?php
## control panel options
include(TEMPLATEPATH.'/includes/options/controlpanel.php');
$cpanel = new Panel();

## custom loop for lead and secondary images with pagination support
## provided by spiral web consulting
$postcount = array();
function is_first_post($id)
{
	global $postcount;
	# Add a new id if one has not been added already
	$postcount[$id] = true;
	# If we're on the first page of posts and the first post
	if ( is_home() && !is_paged() && count($postcount) == 1 )
	return true;
	return false;
}

## support for various widget areas throughout the theme
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Sidebar - Main',
		'before_widget' => '<div id="%1$s" class="widgetContainer %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgetTitle">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => 'Sidebar - Single',
		'before_widget' => '<div id="%1$s" class="widgetContainer %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgetTitle">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => 'Footer - Shared',
		'before_widget' => '<div id="%1$s" class="widgetContainer %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgetTitle">',
		'after_title' => '</h3>'
	));
}

## support for legacy comments system used by older versions of wordpress
add_filter('comments_template', 'legacy_comments');
	function legacy_comments($file) {
	if(!function_exists('wp_list_comments')) : // WP 2.7-only check
	$file = TEMPLATEPATH . '/legacy.comments.php';
	endif;
	return $file;
}
?>