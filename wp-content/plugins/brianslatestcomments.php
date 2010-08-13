<?
/*
Plugin Name: Brian's Latest Comments
Plugin URI: http://meidell.dk/archives/category/wordpress/latest-comments/
Version: 1.5.8
Description: This shows an overview of the recently active articles and the last people to comment on them. Original idea and code fixes contributed by <a href="http://binarybonsai.com">Michael Heilemann</a>.<br />If you have <a href="http://binarybonsai.com/archives/2004/08/17/time-since-plugin/">Dunstan's Time Since</a> installed, this plugin uses it for the title="" attributes on the comments and posts. (For WordPress 1.5)
Author: Brian Meidell
Author URI: http://meidell.dk/

Version 1.5: 	Now works without LOCK TABLE and CREATE TEMPORARY TABLE priviledges.
Version 1.5.1:  Can't remember what I did here
Version 1.5.2: 	Fixed count select statement to not include spammy comments
Version 1.5.3: 	Properly excludes track- and pingbacks
Version 1.5.4:  Excludes posts that are not published, even if they have comments
Version 1.5.5:	Fade old comments, fixed bug that wreaked havoc with Time Since
Version 1.5.6:	Bugfix from Jonas Rabbe (http://www.jonas.rabbe.com/) pertaining to timesince
Version 1.5.7:	Bugfix so old colors can be darker than new colors (stupid oversight), thanks to http://spiri.dk for spotting it.
				Bugfix where single digit hex would cause invalid colors, thanks to http://www.wereldkeuken.be/ for the fix.
Version 1.5.8:	Updated to work with WordPress 2.1 alpha by M. Heilemann.

*/ 

function blc_latest_comments($num_posts = 5, $num_comments = 6, $hide_pingbacks_and_trackbacks = true, $prefix = "<li class='alternate'>", $postfix = "</li>", $fade_old = true, $range_in_days = 10, $new_col = "#dddddd", $old_col = "#666666")
{
	global $wpdb;

	function clamp($min, $max, $val) 
	{
		return max($min,min($max,$val));
	}

	$usetimesince = function_exists('time_since'); // Work nicely with Dunstan's Time Since plugin (adapted by Michael Heilemann)

	// This is compensating for the lack of subqueries in mysql 3.x
	// The approach used in previous versions needed the user to
	// have database lock and create tmp table priviledges. 
	// This uses more queries and manual DISTINCT code, but it works with just select privs.
	if(!$hide_pingbacks_and_trackbacks)
		$ping = "";
	else
		$ping = "AND comment_type<>'pingback' AND comment_type<>'trackback'";
	$posts = $wpdb->get_results("SELECT 
		comment_post_ID, post_title 
		FROM ($wpdb->comments LEFT JOIN $wpdb->posts ON (comment_post_ID = ID))
		WHERE comment_approved = '1' 
		AND $wpdb->posts.post_status='publish'
		$ping
		ORDER BY comment_date DESC;");
		
	$seen = array();	
	$num = 0;

	if($fade_old)
	{
		$max_time = $range_in_days * 24 * 60 * 60 ; 

		$r_new = hexdec(substr($new_col, 1, 2));
		$r_old = hexdec(substr($old_col, 1, 2));
		//$r_min = min($min, $max);
		//$r_max = max($min, $max);
		$r_range = ($r_old-$r_new);
		
		$g_new = hexdec(substr($new_col, 3, 2));
		$g_old = hexdec(substr($old_col, 3, 2));
		//$g_min = min($min, $max);
		//$g_max = max($min, $max);
		$g_range = ($g_old-$g_new);

		$b_new = hexdec(substr($new_col, 5, 2));
		$b_old = hexdec(substr($old_col, 5, 2));
		//$b_min = min($min, $max);
		//$b_max = max($min, $max);
		$b_range = ($b_old-$b_new);
	}
	
//	print "ranges: $r_range, $g_range, $b_range<br>";
//	print "r: ".(0.5*$r_range+$r_new)."<br>";
	
	foreach($posts as $post)
	{
		// The following 5 lines is a manual DISTINCT and LIMIT,
		// since mysql 3.x doesn't allow you to control which way a DISTINCT
		// select merges multiple entries.
		if(array_key_exists($post->comment_post_ID, $seen))
			continue;
		$seen[$post->comment_post_ID] = true;	
		if($num++ > $num_posts)
			break;
		
		$commenters = $wpdb->get_results("SELECT *, UNIX_TIMESTAMP(comment_date) AS unixdate FROM $wpdb->comments
	       			WHERE comment_approved = '1'
				AND comment_post_ID = '".$post->comment_post_ID."'
				$ping
				ORDER BY comment_date DESC
				LIMIT $num_comments;");

		$count = $wpdb->get_var("SELECT COUNT(comment_ID) AS c FROM $wpdb->comments WHERE comment_post_ID = $post->comment_post_ID AND comment_approved = '1' ".$ping);
		$i = 0;
		$link = get_permalink($post->comment_post_ID);
		if($usetimesince)
			$title = " title=\"Last comment was ".time_since($comment->unixdate)." ago\"";
		else
			$title  = "";
		echo $prefix."<a href=\"".$link."\"$title class=\"activityentry\">".stripslashes($post->post_title). "</a>&nbsp;&nbsp;<a href=\"$link#comments\" title=\"Go to the comments of this entry\">{".$count."}</a><br />\n<small>";
		foreach($commenters as $commenter)
		{
			if($usetimesince)
				$title = " title=\"Posted ".time_since($commenter->unixdate)." ago\"";

			if($fade_old)
			{
				$diff = time() - $commenter->unixdate;
				$r = round($diff/$max_time*($r_range))+$r_new; 
				$r = clamp(min($r_new, $r_old), max($r_new, $r_old), $r);
				$g = round($diff/$max_time*($g_range))+$g_new; 
				$g = clamp(min($g_new, $g_old), max($g_new, $g_old), $g);
				$b = round($diff/$max_time*($b_range))+$b_new; 
				$b = clamp(min($b_new, $b_old), max($b_new, $b_old), $b);
				$r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
				$g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
				$b_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
				$colstr = " style=\"color: #".$r_hex.$g_hex.$b_hex.";\"";
			}

			if($i++ > 0)
				echo ", ";
		
			echo "<a$colstr href=\"".$link . "#comment-" . $commenter->comment_ID."\"$title>".stripslashes($commenter->comment_author)."</a>";
		}
		if($count > $num_comments) 
			echo " <a href=\"$link#comments\" title=\"Go to the comments of this entry\">[...]</a>";
		echo "</small>".$postfix."\n";

	}
}

?>
