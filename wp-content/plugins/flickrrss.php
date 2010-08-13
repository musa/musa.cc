<?php
/*
Plugin Name: flickrRSS
Plugin URI: http://eightface.com/wordpress/flickrrss/
Description: Allows you to integrate the photos from a flickr rss feed into your site.
Version: 4.0
License: GPL
Author: Dave Kellam
Author URI: http://eightface.com
*/

function get_flickrRSS() {

	// the function can accept up to seven parameters, otherwise it uses option panel defaults 	
  	for($i = 0 ; $i < func_num_args(); $i++) {
    	$args[] = func_get_arg($i);
    	}
  	if (!isset($args[0])) $num_items = get_option('flickrRSS_display_numitems'); else $num_items = $args[0];
  	if (!isset($args[1])) $type = get_option('flickrRSS_display_type'); else $type = $args[1];
  	if (!isset($args[2])) $tags = trim(get_option('flickrRSS_tags')); else $tags = trim($args[2]);
  	if (!isset($args[3])) $imagesize = get_option('flickrRSS_display_imagesize'); else $imagesize = $args[3];
  	if (!isset($args[4])) $before_image = stripslashes(get_option('flickrRSS_before')); else $before_image = $args[4];
  	if (!isset($args[5])) $after_image = stripslashes(get_option('flickrRSS_after')); else $after_image = $args[5];
  	if (!isset($args[6])) $id_number = stripslashes(get_option('flickrRSS_flickrid')); else $id_number = $args[6];
  	if (!isset($args[7])) $set_id = stripslashes(get_option('flickrRSS_set')); else $set_id = $args[7];
        
	# use image cache & set location
	$useImageCache = get_option('flickrRSS_use_image_cache');
	$cachePath = get_option('flickrRSS_image_cache_uri');
	$fullPath = get_option('flickrRSS_image_cache_dest'); 

	if (!function_exists('MagpieRSS')) { // Check if another plugin is using RSS, may not work
		include_once (ABSPATH . WPINC . '/rss.php');
		error_reporting(E_ERROR);
	}


	// get the feeds
	if ($type == "user") { $rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?id=' . $id_number . '&tags=' . $tags . '&format=rss_200'; }
	elseif ($type == "favorite") { $rss_url = 'http://api.flickr.com/services/feeds/photos_faves.gne?id=' . $id_number . '&format=rss_200'; }
	elseif ($type == "set") { $rss_url = 'http://api.flickr.com/services/feeds/photoset.gne?set=' . $set_id . '&nsid=' . $id_number . '&format=rss_200'; }
	elseif ($type == "group") { $rss_url = 'http://api.flickr.com/services/feeds/groups_pool.gne?id=' . $id_number . '&format=rss_200'; }
	elseif ($type == "community" || $type == "public") { $rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?tags=' . $tags . '&format=rss_200'; }
	else { print "flickrRSS probably needs to be setup"; }

	# get rss file
	$rss = @ fetch_rss($rss_url);

	if ($rss) {
    	$imgurl = "";
    	# specifies number of pictures
		$items = array_slice($rss->items, 0, $num_items);

	    # builds html from array
    	foreach ( $items as $item ) {
       	 if(preg_match('<img src="([^"]*)" [^/]*/>', $item['description'],$imgUrlMatches)) {
            	$imgurl = $imgUrlMatches[1];
 
            #change image size         
           	if ($imagesize == "square") {
             	$imgurl = str_replace("m.jpg", "s.jpg", $imgurl);
           	} elseif ($imagesize == "thumbnail") {
             $imgurl = str_replace("m.jpg", "t.jpg", $imgurl);
           	} elseif ($imagesize == "medium") {
             $imgurl = str_replace("_m.jpg", ".jpg", $imgurl);
           	}
           
           #check if there is an image title (for html validation purposes)
           if($item['title'] !== "") $title = htmlspecialchars(stripslashes($item['title']));
           else $title = "Flickr photo";          
           
           $url = $item['link'];
	
	       preg_match('<http://farm[0-9]{0,3}\.static.flickr\.com/\d+?\/([^.]*)\.jpg>', $imgurl, $flickrSlugMatches);
	       $flickrSlug = $flickrSlugMatches[1];
	       
	       # cache images 
	       if ($useImageCache) {
                      
               # check if file already exists in cache
               # if not, grab a copy of it
               if (!file_exists("$fullPath$flickrSlug.jpg")) {   
                 if ( function_exists('curl_init') ) { // check for CURL, if not use fopen
                    $curl = curl_init();
                    $localimage = fopen("$fullPath$flickrSlug.jpg", "wb");
                    curl_setopt($curl, CURLOPT_URL, $imgurl);
                    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
                    curl_setopt($curl, CURLOPT_FILE, $localimage);
                    curl_exec($curl);
                    curl_close($curl);
                   } else {
                 	$filedata = "";
                    $remoteimage = fopen($imgurl, 'rb');
                  	if ($remoteimage) {
                    	 while(!feof($remoteimage)) {
                         	$filedata.= fread($remoteimage,1024*8);
                       	 }
                  	}
                	fclose($remoteimage);
                	$localimage = fopen("$fullPath$flickrSlug.jpg", 'wb');
                	fwrite($localimage,$filedata);
                	fclose($localimage);
                 } // end CURL check
                } // end file check
                # use cached image
                print $before_image . "<a href=\"$url\" title=\"$title\"><img src=\"$cachePath$flickrSlug.jpg\" alt=\"$title\" /></a>" . $after_image;
            } else {
                # grab image direct from flickr
                print $before_image . "<a href=\"$url\" title=\"$title\"><img src=\"$imgurl\" alt=\"$title\" /></a>" . $after_image;      
            } // end use imageCache
       } // end pregmatch
     } // end foreach
  } // end if($rss)
} # end get_flickrRSS() function

function widget_flickrRSS_init() {
	if (!function_exists('register_sidebar_widget')) return;

	function widget_flickrRSS($args) {
		
		extract($args);

		$options = get_option('widget_flickrRSS');
		$title = $options['title'];
		$before_images = $options['before_images'];
		$after_images = $options['after_images'];
		
		echo $before_widget . $before_title . $title . $after_title . $before_images;
		get_flickrRSS();
		echo $after_images . $after_widget;
	}

	function widget_flickrRSS_control() {
		$options = get_option('widget_flickrRSS');

		if ( $_POST['flickrRSS-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['flickrRSS-title']));
			$options['before_images'] = $_POST['flickrRSS-beforeimages'];
			$options['after_images'] = $_POST['flickrRSS-afterimages'];
			update_option('widget_flickrRSS', $options);
		}

		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$before_images = htmlspecialchars($options['before_images'], ENT_QUOTES);
		$after_images = htmlspecialchars($options['after_images'], ENT_QUOTES);
		
		echo '<p style="text-align:right;"><label for="flickrRSS-title">Title: <input style="width: 180px;" id="gsearch-title" name="flickrRSS-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="flickrRSS-beforeimages">Before all images: <input style="width: 180px;" id="flickrRSS-beforeimages" name="flickrRSS-beforeimages" type="text" value="'.$before_images.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="flickrRSS-afterimages">After all images: <input style="width: 180px;" id="flickrRSS-afterimages" name="flickrRSS-afterimages" type="text" value="'.$after_images.'" /></label></p>';
		echo '<input type="hidden" id="flickrRSS-submit" name="flickrRSS-submit" value="1" />';
	}		

	register_sidebar_widget('flickrRSS', 'widget_flickrRSS');
	register_widget_control('flickrRSS', 'widget_flickrRSS_control', 300, 100);
}

function flickrRSS_subpanel() {
     if (isset($_POST['save_flickrRSS_settings'])) {
       $option_flickrid = $_POST['flickr_id'];
       $option_tags = $_POST['tags'];
       $option_set = $_POST['set'];
       $option_display_type = $_POST['display_type'];
       $option_display_numitems = $_POST['display_numitems'];
       $option_display_imagesize = $_POST['display_imagesize'];
       $option_before = $_POST['before_image'];
       $option_after = $_POST['after_image'];
       $option_useimagecache = $_POST['use_image_cache'];
       $option_imagecacheuri = $_POST['image_cache_uri'];
       $option_imagecachedest = $_POST['image_cache_dest'];
       update_option('flickrRSS_flickrid', $option_flickrid);
       update_option('flickrRSS_tags', $option_tags);
       update_option('flickrRSS_set', $option_set);
       update_option('flickrRSS_display_type', $option_display_type);
       update_option('flickrRSS_display_numitems', $option_display_numitems);
       update_option('flickrRSS_display_imagesize', $option_display_imagesize);
       update_option('flickrRSS_before', $option_before);
       update_option('flickrRSS_after', $option_after);
       update_option('flickrRSS_use_image_cache', $option_useimagecache);
       update_option('flickrRSS_image_cache_uri', $option_imagecacheuri);
       update_option('flickrRSS_image_cache_dest', $option_imagecachedest);
       ?> <div class="updated"><p>flickrRSS settings saved</p></div> <?php
     }

	?>

	<div class="wrap">
		<h2>flickrRSS Settings</h2>
		
		<form method="post">
		<table class="form-table">
		 <tr valign="top">
		  <th scope="row">ID Number</th>
	      <td><input name="flickr_id" type="text" id="flickr_id" value="<?php echo get_option('flickrRSS_flickrid'); ?>" size="20" />
        		Use the <a href="http://idgettr.com">idGettr</a> to find your user or group id.</p></td>
         </tr>
         <tr valign="top">
          <th scope="row">Display</th>
          <td>
        	<select name="display_type" id="display_type">
        	  <option <?php if(get_option('flickrRSS_display_type') == 'user') { echo 'selected'; } ?> value="user">user</option>
        	  <option <?php if(get_option('flickrRSS_display_type') == 'set') { echo 'selected'; } ?> value="set">set</option>
        	  <option <?php if(get_option('flickrRSS_display_type') == 'favorite') { echo 'selected'; } ?> value="favorite">favorite</option>
		      <option <?php if(get_option('flickrRSS_display_type') == 'group') { echo 'selected'; } ?> value="group">group</option>
		      <option <?php if(get_option('flickrRSS_display_type') == 'community') { echo 'selected'; } ?> value="community">community</option>
		    </select>
		 	items using 
        	<select name="display_numitems" id="display_numitems">
		      <option <?php if(get_option('flickrRSS_display_numitems') == '1') { echo 'selected'; } ?> value="1">1</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '2') { echo 'selected'; } ?> value="2">2</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '3') { echo 'selected'; } ?> value="3">3</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '4') { echo 'selected'; } ?> value="4">4</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '5') { echo 'selected'; } ?> value="5">5</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '6') { echo 'selected'; } ?> value="6">6</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '7') { echo 'selected'; } ?> value="7">7</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '8') { echo 'selected'; } ?> value="8">8</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '9') { echo 'selected'; } ?> value="9">9</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '10') { echo 'selected'; } ?> value="10">10</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '11') { echo 'selected'; } ?> value="11">11</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '12') { echo 'selected'; } ?> value="12">12</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '13') { echo 'selected'; } ?> value="13">13</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '14') { echo 'selected'; } ?> value="14">14</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '15') { echo 'selected'; } ?> value="15">15</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '16') { echo 'selected'; } ?> value="16">16</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '17') { echo 'selected'; } ?> value="17">17</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '18') { echo 'selected'; } ?> value="18">18</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '19') { echo 'selected'; } ?> value="19">19</option>
		      <option <?php if(get_option('flickrRSS_display_numitems') == '20') { echo 'selected'; } ?> value="20">20</option>
		      </select>
            <select name="display_imagesize" id="display_imagesize">
		      <option <?php if(get_option('flickrRSS_display_imagesize') == 'square') { echo 'selected'; } ?> value="square">square</option>
		      <option <?php if(get_option('flickrRSS_display_imagesize') == 'thumbnail') { echo 'selected'; } ?> value="thumbnail">thumbnail</option>
		      <option <?php if(get_option('flickrRSS_display_imagesize') == 'small') { echo 'selected'; } ?> value="small">small</option>
		      <option <?php if(get_option('flickrRSS_display_imagesize') == 'medium') { echo 'selected'; } ?> value="medium">medium</option>
		    </select>
            images</p>
           </td> 
         </tr>
         <tr valign="top">
		  <th scope="row">Set</th>
          <td><input name="set" type="text" id="set" value="<?php echo get_option('flickrRSS_set'); ?>" size="40" /> Use number from the set url</p>
         </tr>
         <tr valign="top">
		  <th scope="row">Tags</th>
          <td><input name="tags" type="text" id="tags" value="<?php echo get_option('flickrRSS_tags'); ?>" size="40" /> Comma separated, no spaces</p>
         </tr>
         <tr valign="top">
          <th scope="row">HTML Wrapper</th>
          <td><label for="before_image">Before Image:</label> <input name="before_image" type="text" id="before_image" value="<?php echo htmlspecialchars(stripslashes(get_option('flickrRSS_before'))); ?>" size="10" />
        	  <label for="after_image">After Image:</label> <input name="after_image" type="text" id="after_image" value="<?php echo htmlspecialchars(stripslashes(get_option('flickrRSS_after'))); ?>" size="10" />
          </td>
         </tr>
         </table>      

        <h3>Cache Settings</h3>
		<p>This allows you to store the images on your server and reduce the load on Flickr. Make sure the plugin works without the cache enabled first. If you're still having
		trouble, try visiting the <a href="http://eightface.com/forum/viewforum.php?id=3">forum</a>.</p>
		<table class="form-table">
         <tr valign="top">
          <th scope="row">URL</th>
          <td><input name="image_cache_uri" type="text" id="image_cache_uri" value="<?php echo get_option('flickrRSS_image_cache_uri'); ?>" size="50" />
          <em>http://yoursite.com/cache/</em></td>
         </tr>
         <tr valign="top">
          <th scope="row">Full Path</th>
          <td><input name="image_cache_dest" type="text" id="image_cache_dest" value="<?php echo get_option('flickrRSS_image_cache_dest'); ?>" size="50" /> 
          <em>/home/path/to/wp-content/flickrrss/cache/</em></td>
         </tr>
		 <tr valign="top">
		  <th scope="row" colspan="2" class="th-full">
		  <input name="use_image_cache" type="checkbox" id="use_image_cache" value="true" <?php if(get_option('flickrRSS_use_image_cache') == 'true') { echo 'checked="checked"'; } ?> />  
		  <label for="use_image_cache">Enable the image cache</label></th>
		 </tr>
        </table>
        <div class="submit">
           <input type="submit" name="save_flickrRSS_settings" value="<?php _e('Save Settings', 'save_flickrRSS_settings') ?>" />
        </div>
        </form>
    </div>

<?php } // end flickrRSS_subpanel()

function flickrRSS_admin_menu() {
   if (function_exists('add_options_page')) {
        add_options_page('flickrRSS Settings', 'flickrRSS', 8, basename(__FILE__), 'flickrRSS_subpanel');
        }
}

add_action('admin_menu', 'flickrRSS_admin_menu'); 
add_action('plugins_loaded', 'widget_flickrRSS_init');
?>