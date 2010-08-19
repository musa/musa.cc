
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php if (function_exists('is_tag') && is_tag()) { 
echo 'Posts tagged &quot;'.$tag.'&quot; - '; } 
elseif (is_archive()) { wp_title(''); echo ' Archive - '; } 
elseif (is_search()) { echo 'Search for &quot;'.wp_specialchars($s).'&quot; - '; } 
elseif (!(is_404()) && (is_single()) || (is_page())) { wp_title(''); echo ' - '; } 
elseif (is_404()) { echo 'Not Found - '; } bloginfo('name'); ?></title>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<?php if (isset($theme_options["colorscheme"]) && $theme_options["colorscheme"] != "default") {
$colorScheme = get_bloginfo('template_directory')."/style-".$theme_options["colorscheme"].".css";
?>
<!--[if IE 6]>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/ie6.css" type="text/css" media="screen" />
<script src="<?php bloginfo('template_directory'); ?>/js/DD_belatedPNG_0.0.8a-min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    DD_belatedPNG.fix('img');
</script>
<![endif]-->
<link rel="stylesheet" href="<?php echo $colorScheme; ?>" type="text/css" media="screen" />
<?php } ?>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

<!-- animacao de fundo com processingJS -->
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/processing-0.9.7.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/init.js"></script>
<!-- animacao de fundo com processingJS -->

</head>
<!-- animacao de fundo com processingJS -->
<body onload="init();">
<!-- 
<canvas datasrc="<?php bloginfo('template_directory'); ?>/pjs/linhas.pjs"> </canvas>
-->
<!-- animacao de fundo com processingJS -->

<div id="main-wrapper">

    <div id="leaderboard" class="fix">
        <div id="site-name">
            <h4><a href="<?php echo get_settings('home'); ?>/" title="Home"><?php bloginfo('name'); ?></a></h4>
            <h3 class="description"><?php bloginfo('description'); ?></h3>

        </div>
        <div class="nav-container">
        <ul id="main-nav" class="right">
            <li><a href="<?php echo get_settings('home'); ?>/sobre" title="Sobre">Sobre</a></li>
            <li><a href="<?php echo get_settings('home'); ?>/category/projetos/" title="Projetos">Projetos</a></li>
            <li><a href="<?php echo get_settings('home'); ?>/category/oficinas/" title="Oficinas">Oficinas</a></li>
            <li><a href="<?php echo get_settings('home'); ?>/wiki" title="Wiki">Wiki</a></li>
        </ul>
        </div>
    </div>
