<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
<?php if ( is_first_post($post->ID) ) { ?>
<img src="<?php echo $img_src; ?>" width="593" height="225" />
<?php } else { ?>
<img src="<?php echo $img_src; ?>" width="293" height="150" />
<?php } ?>
</a>