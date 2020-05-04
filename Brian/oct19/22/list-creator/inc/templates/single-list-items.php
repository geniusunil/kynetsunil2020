<?php
get_header();

?>
<div class="content-area mv-single-lists" id="primary">

	<?php
		// Start the loop.
		while ( have_posts() ) : the_post(); ?>

			<div class="site-content" id="content" role="main">

				 <?php echo the_content( ); ?>

			</div>


		<?php endwhile; ?>


 <div class="clr"> </div>
 <?php do_action( 'comment_form_after' ); ?>
</div> <script data-cfasync="false" src="https://inndel.com/wp-content/plugins/wp-usertrack/userTrack/tracker.js"></script>
<?php

get_footer();

?>
