<?php
class Panel {
	var $default_settings = Array(
	    'colorscheme' => 'default',
		'logotype' => 'text',
		'fancypost' => 'hidehero',
		'thumbhelp' => 'timthumbon',
	);

	function Panel() {
		add_action('admin_menu', array(&$this, 'admin_menu'));
		add_action('admin_head', array(&$this, 'admin_head'));
		if (!is_array(get_option('TheUnstandard')))
			add_option('TheUnstandard', $this->default_settings);
			$this->options = get_option('TheUnstandard');
	}

	function admin_menu() {
		add_theme_page('TheUnstandard', 'Theme Options', 'edit_themes', 'TheUnstandard', array(&$this, 'optionsmenu'));
	}

	function admin_head() {}

	function optionsmenu() {
    	if ($_POST['act1'] == 'save') {
    	    
    		if (isset($_POST['colorscheme_act2'])) {
    			$this->options['colorscheme'] = $_POST['colorscheme_act2'];
    		}
    		else { $this->options['colorscheme'] = 'default'; }
    		
    		if (isset($_POST['logotype_act2'])) {
    			$this->options['logotype'] = $_POST['logotype_act2'];
    		}
    		else { $this->options["logotype"] = "text"; }
    		
    		if (isset($_POST['fancypost_act2'])) {
    			$this->options['fancypost'] = $_POST['fancypost_act2'];
    		}
    		else { $this->options['fancypost'] = 'hidehero'; }
    		
    		if (isset($_POST['thumbhelp_act2'])) {
    			$this->options['thumbhelp'] = $_POST['thumbhelp_act2'];
    		}
    		else { $this->options["thumbhelp"] = "timthumbon"; }
    		
    		update_option('TheUnstandard', $this->options);
	}
?>

<div class="wrap">
<script>
    function fieldSwitch(switcher,target) {
        if (document.getElementById(switcher).checked==true) {
            document.getElementById(target).disabled=false;
        }
        else {
            document.getElementById(target).disabled=true;
        }
    }
</script>
<h2>TheUnstandard Theme Options</h2>
<form action="" method="post" class="themeform" name="Form" id="Form">
    <input type="hidden" id="act1" name="act1" value="save">
    <table class="widefat">
        <thead>
            <tr>
                <th scope="col" style="width: 300px;">Layout Options</th>
                <th scope="col">Settings</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>Select a color</strong><br />
                    Different flavors to match your mood.
                </td>
                <td>
                    <select name="colorscheme_act2" id="colorscheme_act2">
						<option value="default" <?php if ($this->options["colorscheme"] == "default") { echo "selected"; }?>>Original</option>

						<?php
						$colorSchemes = array (
						    array ('Sherbet','sherbet'),
							array ('Lime','lime'),
							array ('Licorice','licorice')
							);

						$totalSchemes = count($colorSchemes);
						$limitSchemes = $totalSchemes - 1;

						for ($scheme = 0; $scheme < $totalSchemes; $scheme++ ) {
							if (file_exists('../wp-content/themes/'.get_option('template').'/style-'.$colorSchemes[$scheme][1].'.css')) { ?>
							<option value="<?php echo $colorSchemes[$scheme][1];?>" <?php if ($this->options["colorscheme"] == $colorSchemes[$scheme][1]) { echo "selected"; }?>><?php echo $colorSchemes[$scheme][0];?></option>
							<?php }
						} ?>
					</select>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Logo style</strong><br />
                    The default text header inherits <a href="../wp-admin/options-general.php">your Blog Title</a>. For image headers, replace header.png in the images directory using the sthe PSD as your guide. Image should be no wider than 595px.
                </td>
                <td>
                    <select name="logotype_act2" id="logotype_act2">
                        <option value="text" <?php if ($this->options["logotype"] == "text") { echo "selected"; }?>>Use blog title</option>
                        <option value="image" <?php if ($this->options["logotype"] == "image") { echo "selected"; }?>>Use custom image</option>
                    </select>
                </td>
            </tr>
            <!-- -->
            <tr>
                <td>
                    <strong>Fancy post headers</strong><br />
                    Show or hide the lead_image when viewing a single post (outside the home page).
                </td>
                <td>
                    <select name="fancypost_act2" id="fancypost_act2">
                        <option value="hidehero" <?php if ($this->options["fancypost"] == "hidehero") { echo "selected"; }?>>Hide lead_image</option>
                        <option value="showhero" <?php if ($this->options["fancypost"] == "showhero") { echo "selected"; }?>>Show lead_image</option>
                    </select>
                </td>
            </tr>
            <!-- -->
            <tr>
                <td>
                    <strong>Automatic Thumbnails</strong><br />
                    Enable or disable timthumb.php support
                </td>
                <td>
                    <select name="thumbhelp_act2" id="thumbhelp_act2">
                        <option value="timthumbon" <?php if ($this->options["thumbhelp"] == "timthumbon") { echo "selected"; }?>>TimThumb Enabled</option>
                        <option value="timthumboff" <?php if ($this->options["thumbhelp"] == "timthumboff") { echo "selected"; }?>>TimThumb Disabled</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit" style="text-align: right; border: none; margin: 0 0 20px 0;"><input type="submit" value="Update TheUnstandard" name="save" /></p>
</form>
</div>
<?php }
} ?>