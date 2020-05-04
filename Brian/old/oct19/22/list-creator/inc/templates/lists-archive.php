<?php
get_header();
global $wp_query;
$item_name = get_query_var( 'item_name' );
$settings = get_option( 'mv_list_items_settings' );

$title = $settings['list_archive_page_title'];
$description = $settings['list_archive_page_description'];


$title = str_replace( '[Year]', date('Y'), $title );
$title = do_shortcode($title);

$description = str_replace( '[Year]', date('Y'), $description );
$description = do_shortcode($description);

?>

<div class="content-area mv-archive rr" id="primary">

        <div id="page-header">
            <h1>
               <?php echo $title; ?>
            </h1>
            <div>
             <?php echo $description; ?>

            </div>
        </div>


	<div class="site-content" id="content" role="main">

 	<div class="clr"></div>
		<?php
				$html='';
				$i =1;
				query_posts($query_string . '&post_type=lists&orderby=modified&order=desc&posts_per_page=0');
				
				while ( have_posts() ) : the_post();

				$i = ( $i > 3 )? 1: $i;
				$post_id = get_the_ID();
				$affiliate_url = get_field( 'affiliate_url', $post_id );
				$affiliate_button_text = get_field( 'affiliate_button_text', $post_id );
				$source_url = get_field( 'source_url', $post_id );
				$credit = get_field( 'credit', $post_id );

				$html .='<section class="grid-cols col'.$i.'">
						<a class="grid-thumb" href="'.get_the_permalink( $post_id ).'">
						'.get_the_post_thumbnail( $post_id, array( '230', '200' ) ).'
						</a>
						<h4><a href="'.get_the_permalink( $post_id ).'">'.get_the_title( $post_id ).'</a></h4>
						<div style="min-height:84px">'.substr( wp_strip_all_tags($post->post_content), 0, 100 ).'</div>

						<div class="alternative-btm-links">';
				if ( ! empty( $affiliate_url ) ) {
					$html .='<div><a class="zf-buy-button btn btn-primary" href="'.esc_url( $affiliate_url ).'" rel="nofollow" target="_blank"><i class="zf-icon zf-icon-buy_now"></i>'.$affiliate_button_text.'</a></div>';
				}
				if ( ! empty( $source_url ) ) {
					$html .='<a class="" href="'. esc_url( $source_url ) .'" rel="nofollow" target="_blank">'.$credit.'</a>';
				}
				$html .='</div>';
				$html .='</section>';

				if ( $i == 3 ) {
					$html.='<div class="clr"></div>';
				}
				$i++;


				endwhile;

				$html.='<div class="clr"></div><br/>';
				echo $html; ?>
				<?php the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
				'next_text'          => __( 'Next page', 'twentyfifteen' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
				) );
			?>

	</div>

	<div class="mv-sidebar">
		<?php if ( is_active_sidebar( 'lists-archive-sidebar' ) ) : ?>
	<ul id="sidebar">
		<?php dynamic_sidebar( 'lists-archive-sidebar' ); ?>
	</ul>
		<?php endif; ?>
	</div>

 <div class="clr"> </div>
</div>
<?php

get_footer();

?>
