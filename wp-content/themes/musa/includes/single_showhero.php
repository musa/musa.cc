<div id="post-<?php the_ID(); ?>" class="main-post-bg showhero">
    <?php $img_src = get_post_meta($post->ID, 'lead_image', true); ?>
    <img src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo $img_src; ?>&w=593&h=225&zc=1" width="593" height="225" />
    <div class="title-insert">
        <h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title() ?></a></h2>
    </div>
</div>