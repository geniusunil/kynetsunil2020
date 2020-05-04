<?php
get_header();

if ( is_post_type_archive( 'list_items' ) ) {
	$settings = get_option( 'mv_list_items_settings' );
	$title = $settings['archive_page_title'];
	$description = $settings['archive_page_description'];
}else {
	$tax_id = get_queried_object_id();
	$term = get_term( $tax_id );
	/// $title = get_field('title', $term);
	  $title = $term->name;
	$description = $term->description;

	if ( is_tax( 'list_categories' ) ) {
		$promoted_field = 'promoted_on_category';
	}else if ( is_tax( 'item_tags' ) ) {
			$promoted_field = 'promoted_on_tags';
		}

}

global $wpdb;



?>
<div class="content-area mv-archive" id="primary">

        <div id="page-header">
            <h1>
               <?php echo $title; ?>
            </h1>
            <div>
             <?php echo $description; ?>

            </div>
        </div>
	<div class="site-content" id="content" role="main">

		<div class="zombify-main-section-front zombify-screen" id="zombify-main-section-front">
			<div class="zf-container">
				<div class="zf-list zf-numbered" id="zf-list">
					<ol>
						<?php
							$index =1;
							// echo '<pre>';
							// print_r($GLOBALS['wp_query']->request); die;
							while ( have_posts() ) : the_post();
							//var_dump($GLOBALS['wp_query']->request); die;

							$affiliate_url = get_field( 'affiliate_url', $post->ID );
							$affiliate_button_text = get_field( 'affiliate_button_text', $post->ID ) == '' ?'Download': get_field( 'affiliate_button_text', $post->ID   );
							$source_url = get_field( 'source_url', $post->ID );
							$credit = get_field( 'credit', $post->ID );
							$software = get_field( 'software', $post->ID );
							if ( !empty( $software ) ) {
								$software = str_replace( '_', ' ', implode( ', ', array_map( 'ucfirst', $software ) ) );
							}else {
								$software = 'n/a';
							}
							$promoted_class = '';
							if ( isset( $promoted_field ) ) {
								$promoted_class = get_field( $promoted_field, $post->ID ) == true ? 'promoted-item': '';
							}

							?>
						<li class="zf-list_item <?php echo $promoted_class ?>">
							<div class="zf-list_left">
								<span class="zf-number">
									<?php //echo $index; ?>
								</span>
								<figure class="zf-list_media zf-image">
									<?php echo get_the_post_thumbnail( $post->ID, 'medium' );?>
								</figure>
							</div>
							<div class="zf-list_right">
								<h2 class="zf-list_title ranked_titleeee">
									<a href="<?php echo get_the_permalink( $post->ID ) ?>">
										<?php echo get_the_title( $post->ID ); ?>
									</a>
								</h2>

								<span style="clear: both; display: block;">
								</span>
								<div class="avg-rating-cont">
									<div class="editor-rating-cont">
										Editor Rating
										<br/>
										<?php echo do_shortcode( '[rwp-reviewer-rating-stars id=0 size=20 post='.$post->ID.']' ); ?>
									</div>
									<div class="user-rating-cont">
										User's Rating
										<br/>
											<?php
										echo do_shortcode( '[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=20 post='.$post->ID.']' );
										// $user_score = get_post_meta( $post->ID, 'user_rating_custom', true );
										// if ( $user_score ) {
										// 	$user_score = round( $user_score );
										// }else {
										// 	$user_score = 0;
										// }
										// $obj = new RWP_Rating_Stars_Shortcode();
										// echo $obj->get_stars( $user_score, '20', '5' ) ;
								?>

									</div>
									<div class="clr">
									</div>
								</div>
								<div class="zf-list_description">
									<strong>
										Bottom Line:
									</strong>
									<?php echo strip_tags(substr( $post->post_content, 0, 200 )); ?> ....

									<div class="lists-btm-link-cnt">
										<div class="lists-btm-software">
											Software:
											<?php echo $software; ?>
										</div>
										<div class="lists-btm-link">
										<a class="zf-buy-button zf-buy-button btn btn-primary " href="<?php echo get_the_permalink( $post->ID ); ?>" rel="nofollow" >
												Review
											</a>
											<?php if ( ! empty( $affiliate_url ) ) { ?>
												<a class="zf-buy-button btn btn-affiliate" href="<?php echo $affiliate_url; ?>" rel="nofollow" target="_blank">
													<i class="zf-icon zf-icon-buy_now">
													</i>
													<?php echo $affiliate_button_text ?>
												</a>
											<?php } ?>

										</div>
										<div class="clr"> </div>
									</div>
								</div>
							</div>
							<?php echo !empty($promoted_class)?'<div class="featured-item">Featured</div><div class="clr"></div>':''; ?>
						</li>
						<?php $index++; ?>
						<?php  endwhile;  ?>
					</ol>
				</div>
			</div>
		</div>

			<?php the_posts_pagination( array(
		'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
		'next_text'          => __( 'Next page', 'twentyfifteen' ),
		'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
	) );

?>
	</div>

	<div class="mv-sidebar">
		<?php if ( is_active_sidebar( 'review-archive-sidebar' ) ) : ?>
	<ul id="sidebar">
		<?php dynamic_sidebar( 'review-archive-sidebar' ); ?>
	</ul>
		<?php endif; ?>
	</div>

 <div class="clr"> </div>
</div> 
<?php

get_footer();

?>
