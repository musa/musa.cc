<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
<?php if ( is_first_post($post->ID) ) { ?>
<img src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo $img_src; ?>&w=593&h=225&zc=1" width="593" height="225" />
<?php } else { ?>
<img src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo $img_src; ?>&w=293&h=150&zc=1" width="293" height="150" />
<?php } ?>
</a>