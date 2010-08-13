<?php $theme_options = get_option('TheUnstandard'); ?>
<?php if (!isset($theme_options["fancypost"]) || $theme_options["fancypost"] == "hidehero") { 
    include (TEMPLATEPATH . '/includes/single_hidehero.php'); 
    } 
    else if (!isset($theme_options["fancypost"]) || $theme_options["fancypost"] == "showhero") { 
        include (TEMPLATEPATH . '/includes/single_showhero.php'); 
} ?>