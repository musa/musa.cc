<?php get_header(); ?>
<div class="home is-single fix">
	<div class="left">
		<div class="recent-leads fix">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<div class="post single fix" id="post-<?php the_ID(); ?>">
				    <?php include (TEMPLATEPATH . '/includes/single_dynamic.php'); ?>
						<p class="first"><?php the_time('M jS Y') ?> <?php edit_post_link('edit', '(', ')'); ?> | <?php the_category( ', ',$post->ID ); ?>  </p>
					<div class="entry">
						<?php the_content('<p>Leia o restante &raquo;</p>'); ?>
						<?php wp_link_pages(); ?>
						<br />
						<p class="for-tags">Tags: <?php the_tags( '', ', ', ''); ?></p>
					</div>
				</div>
				<?php comments_template(); ?>
			<?php endwhile; else : ?>
			<?php endif; ?>
		</div>
	</div>
	<div class="right">
		<?php include (TEMPLATEPATH . '/sidebar.php'); ?>
	</div>
</div>

<?php get_footer(); ?>
