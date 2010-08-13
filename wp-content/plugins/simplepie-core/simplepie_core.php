<?php
/*
Plugin Name: SimplePie Core
Version: 1.1.1
Plugin URI: http://simplepie.org/wiki/plugins/wordpress/simplepie_core
Description: Does little else but load the core SimplePie API library for any extension that wants to utilize it. Go to <a href="options-general.php?page=simplepie_core">Options&rarr;SimplePie Core</a> for more details.
Author: Ryan Parman and Geoffrey Sneddon
Author URI: http://simplepie.org/
*/

/**
 * Stores whether simplepie.inc has already been loaded by another extension.
 */
$simplepie_loaded = null;

/**
 * What is the SIMPLEPIE_BUILD for the version that we're loading?
 */
$simplepie_core_build = 20080315205903;

/**
 * Ensure that the SimplePie class hasn't been loaded before trying to load it again.
 */
if (!class_exists('SimplePie'))
{
	require_once('simplepie.inc');
}
else
{
	$simplepie_loaded = true;
}

/**
 * Ensure that the idna_convert class hasn't been loaded before trying to load it again.
 */
if (!class_exists('idna_convert'))
{
	require_once('idn/idna_convert.class.php');
}

/**
 * Namespace for functions
 */
class SimplePie_Core
{
	/**
	 * version_compare() is being stupid, so let's work around it.
	 */
	function convert_to_version($s)
	{
		$s = strval($s);
		$s = str_split($s);
		$s = implode('.', $s);
		return $s;
	}
}

/**
 * Add menu item to Options menu.
 */
function simplepie_core_options()
{
	if (function_exists('add_options_page'))
	{
		add_options_page('SimplePie Core', 'SimplePie Core', 8, 'simplepie_core', 'simplepie_core_options_page');
	}
}

/**
 * Trigger the adding of the menu option.
 */
add_action('admin_menu', 'simplepie_core_options');

/**
 * Draw normal options page.
 */
function simplepie_core_options_page()
{
	global $simplepie_loaded;

	if ($simplepie_loaded)
	{
		if ($e = version_compare(SimplePie_Core::convert_to_version(SIMPLEPIE_BUILD), SimplePie_Core::convert_to_version($simplepie_core_build)) > -1)
		{
			echo '<div id="message" class="updated fade">Another extension has already loaded the SimplePie API, but it\'s loading a version that is the same or newer than what we\'re trying to load. It would be better, however, if that extension loaded this version of the SimplePie API instead. Contact the author of the conflicting plugin, and let them know about this plugin.</div>';
		}
		else
		{
			echo '<div id="message" class="updated fade">Another extension has already loaded an older version of the SimplePie API, so to avoid conflicts we will not load our version. This means that you don\'t have the latest version of SimplePie available to your extensions. Contact the author of the conflicting plugin, and let them know about this plugin.</div>';
		}
	}
?>

<div style="margin:50px auto; text-align:center;">
	<h3>SimplePie core API library, version <?php echo SIMPLEPIE_VERSION; ?></h3>
	<p>(Built <?php echo date('D, j M Y \a\t H:i:s T', strtotime(SIMPLEPIE_BUILD)); ?>) is available to your other extensions. Read the <a href="http://simplepie.org/wiki/misc/release_notes/simplepie_1.1">Release Notes</a>.</p>
</div>

<?php
}
?>