<?php if ( is_single() ) { ?>
	<!--<ul class="sidebar-nextprev fix">
		<li class="previous"><?php previous_post_link('%link', '<span>&laquo; Prev</span>') ?></li>
		<li class="next"><?php next_post_link('%link', '<span>Next &raquo;</span>') ?></li>
	</ul>
	<br /><br />
-->	
<!--<div class="search">
		<div id="searchWrap">
<form method="get" id="searchForm" action="<?php bloginfo('home'); ?>/">
	<span><input type="text" value="Buscar nos arquivos" onfocus="if (this.value == 'Buscar nos arquivos') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Buscar nos arquivos';}" name="s" id="s" class="text-input" /></span>
</form>
</div>
	</div>-->
	<br /><br />
   
	<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Sidebar - Single') ) : ?>
	<?php else: ?>
		
	<?php endif; ?>
<!--
<?php } else { ?>
	<div class="search">
		<div id="searchWrap">
<form method="get" id="searchForm" action="<?php bloginfo('home'); ?>/">
	<span><input type="text" value="Search the archives..." onfocus="if (this.value == 'Search the archives...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search the archives...';}" name="s" id="s" class="text-input" /></span>
</form>
</div>
	</div>
	<br /><br />
	<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Sidebar - Main') ) : ?>
	<?php else: ?>
		<h3 class="module-title">Configure Widgets</h3>
		<p class="sidebar-desc">The Unstandard theme utilizes WordPress widgets to populate the main sidebar, single post sidebars, and shared foote area of a page. To remove this message, visit your Admin Dashboard to configure your site widgets. Customize this area by selecting widgets for the 'Sidebar - Main' zone.</p>
	<?php endif; ?>
<?php } ?>
-->
