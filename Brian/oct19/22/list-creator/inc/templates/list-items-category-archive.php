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
    }
    $meta_query = array(
        array(
            'key' => 'total_post_view',
            'value' => 0,
            'compare' => '>='
        ),
    );
    $lists = get_posts(array(
        'post_type' => 'lists',
        'numberposts' => -1,
        'orderby'   => 'meta_value title',
        'meta_key'  => 'total_post_view',
        'order' => 'DESC',
        'meta_query' =>$meta_query,
//        'orderby'     => array( 'meta_value' => 'DESC', 'date' => 'DESC' ),
        'tax_query' => array(
            array(
                'taxonomy' => 'list_categories',
                'field' => 'id',
                'terms' => $tax_id,
                'include_children' => false
            )
        )
    ));

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
    <?php if(!empty($lists) && is_array($lists)) {
        $coun = 1;
        $togglelist = '';
        $mainlist = '';
        foreach ($lists as $post){
            setup_postdata( $post );
            //var_dump($lst);

            $listId = get_the_ID();
            if (has_post_thumbnail( $listId ) ) {
                $featured_img_url = get_the_post_thumbnail_url($listId,array(150,150));
            } else{
                $featured_img_url =  plugins_url( 'images/dummy.png', dirname(dirname(__FILE__) ));
            }
            $bgurl = "url('$featured_img_url')";
            $actTile = $titlepost = do_shortcode(get_the_title());
            if(strlen($titlepost) > 35){
                $titlepost = substr($titlepost,0,35).'...';
            }
            $item = '<li class="ls-name"><a href="'.get_permalink().'" style="background-image:'.$bgurl.'">';
            $item .= "<span class='ls-title'><span class='small-title'>".$titlepost."</span><span class='large-title'>".$actTile."</span></span>";
            $item .= '</a> </li>';
            if($coun >8){
                $togglelist .= $item;
            } else{
                $mainlist .= $item;
            }
            $coun ++;
        }
        ?>
        <div class="category-lists-archive">
            <ul class="category-lis">
                <li class="cat-name"><span><?php echo $title;?></span></li>
                <?php echo $mainlist; ?>
            </ul>
            <?php   if($coun >8){?>
                <div class="category-archive_toggle">
                    <?php ob_start();?>
                    <div id="accordion">
                        <h3>See all Lists</h3>

                        <div>
                            <ul class="category-lis">
                                <?php echo $togglelist; ?>
                            </ul>
                        </div>
                    </div>
                    <?php $toggle_con = ob_get_clean();
                    echo do_shortcode($toggle_con);
                    ?>
                </div>
            <?php } ?>
        </div>
        <?php
        wp_reset_postdata();
    } ?>
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


                         if($index == 10)
							 break;
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
<script type="text/javascript">
    jQuery(document).ready(function($) {
        var icons = {
            header: "ui-icon-circle-arrow-e",
            activeHeader: "ui-icon-circle-arrow-s"
        };
        jQuery( "#accordion" ).accordion({
            collapsible: true,
            active:false,
            icons: false
        });
    });
</script>
<?php

get_footer();

?>
