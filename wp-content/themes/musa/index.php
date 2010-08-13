<?php get_header(); ?>
<div class="home fix">
    <div class="left">
        <div class="recent-leads fix">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div id="post-<?php the_ID(); ?>" class="<?php echo ( is_first_post($post->ID) ) ? 'main-post-bg' : 'secondary-post-bg left'; ?>">
                <!---<p class="post-comments"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></p>--->
                
                <!--Peda thumb de acordo do o post-->
                <?php if ( is_first_post($post->ID) ) $img_src = get_post_meta($post->ID, 'lead_image', true); 
                    else $img_src = get_post_meta($post->ID, 'secondary_image', true); 
                    if ( $img_src == '' ) $img_src = '/wp-content/themes/theunstandard/images/theunstandard-blank.png';
                ?>
                
                <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
                <?php if ( is_first_post($post->ID) ) { ?>
                        <img src="<?php echo $img_src; ?>" width="593" height="225" />
                <?php } else { ?>
                    <img src="<?php echo $img_src; ?>" width="293" height="150" />
                <?php } ?>
                </a>
                
                <div class="title-insert">
                    <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                </div>
            </div>

            <?php endwhile; ?>

            <div class="entry navigation radius-link fix">
                <br class="clear" />
                <p class="left"><?php previous_posts_link('&laquo; previous'); ?></p><p class="right"><?php next_posts_link('next &raquo;'); ?></p>
            </div>
            <?php else : ?>
            <div class="post single">
                <h2>No matching results</h2>
                <div class="entry">
                    <p>You seem to have found a mis-linked page or search query with no associated or related results.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
  
</div>

    <h3 class="module-title">Blogs</h3>
<div id="blogs">
<?php $blogs = array('http://www.alfakini.cc/blog/', 'http://blog.dinomagri.com/feed/', 'http://gabi.void.cc/', 'http://noblogs.org/rss.php?blogId=1954&profile=rss20', 'http://automata.cc/');
      shuffle($blogs);
?>
<?php foreach ($blogs as $blog): ?>
<div class="item">
    <?php $feed = new SimplePie($blog, $_SERVER['DOCUMENT_ROOT'] . '/cache'); ?>
    <h4 class="title"><a href="<?php echo $blog; ?>"><?php echo $feed->get_title(); ?></a></h4>
    <?php foreach ($feed->get_items(0, 3) as $item): ?>
        <ul class="post">
	        <li><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></li>
	        <!---<?php echo $item->get_description(); ?>
	        <p><small><?php echo $item->get_date('j F Y | g:i a'); ?></small></p>--->
        </ul>
    <?php endforeach; ?>
</div>
<?php endforeach; ?>
</div>


<?php get_footer(); ?>
