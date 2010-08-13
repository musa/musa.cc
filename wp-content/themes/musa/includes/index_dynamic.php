<?php $theme_options = get_option('TheUnstandard'); ?>
<?php if (!isset($theme_options["thumbhelp"]) || $theme_options["thumbhelp"] == "timthumbon") { 
    include (TEMPLATEPATH . '/includes/index_timthumbon.php'); 
    } 
    else if (!isset($theme_options["thumbhelp"]) || $theme_options["thumbhelp"] == "timthumboff") { 
        include (TEMPLATEPATH . '/includes/index_timthumboff.php'); 
} ?>