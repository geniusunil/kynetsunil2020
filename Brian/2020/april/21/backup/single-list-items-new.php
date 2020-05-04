<?php
get_header();

?>
<div id='loader-animate' style='display: none;'><span>Loading...</span></div><div class="content-area mv-single-lists_item" id="primary" itemscope itemtype="http://schema.org/Product">

	<?php
		// Start the loop.
		while ( have_posts() ) : the_post(); ?>

			<div class="site-content" id="content" role="main">

				 <?php echo the_content( ); ?>

			</div>


		<?php endwhile; ?>


 <div class="clr"> </div>
 <?php do_action( 'comment_form_after' ); ?>
</div>

<?php

get_footer();

?>
