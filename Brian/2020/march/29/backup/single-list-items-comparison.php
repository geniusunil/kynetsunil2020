<?php
get_header();

?>
<div class="content-area mv-single-list-comparison" id="primary">

    <?php
    // Start the loop.
    $compareId =get_query_var('cid');
    while ( have_posts() ) : the_post(); ?>

    <div class="site-content" id="comparison-content" role="main">
            <?php   
            if(!empty($_GET['alternative']) || !empty($_GET['itemid']) || !empty($compareId)){
                do_action( 'custom_comparison_content');     
            } else {
                 the_content();
            } ?>

    </div>


</div>


<?php endwhile; ?>


<div class="clr"> </div>
<?php do_action( 'comment_form_after' ); ?>
</div>


<?php
get_footer();

?>
