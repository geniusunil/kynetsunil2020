<?php 
/*
YARPP Template: Slider
Author: Renu
Description: A simple example YARPP template.
*/

?>
    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <h4 class="explorehead" style="font-size: 2.5rem;">
                Explore Related reccomendations
            </h4>
            <hr class="customhr">
        </div>
    </div>

    <div class="row prodpur">
        <div class="col-md-12 nopadding">
            <?php if (have_posts()):
                $rowArr = array();
                ?>

                <?php while (have_posts()) : the_post();
                    $post_id = get_the_ID();
                   $votes =  do_shortcode("[total_votes id=$post_id]");
                   ob_start();
   ?>
                <div class="item">
                    <h4 class="bold"><a href="<?php the_permalink();?>" class="ls_referal_link owlitemlink" data-parameter="listid" data-id=""><?php echo get_the_title()?></a></h4>
                    <ul class="displayinline greycol">
                        <li>
                            <?php echo "According to $votes users";?>
                        </li>
                    </ul>
                </div>
                <?php
                $rowArr[] = ob_get_contents();
                ob_get_clean();
                endwhile;
                $total = count($rowArr);
                $half = round($total/2);
                ?>

                <div class="owl-carousel car1 owl-theme">
                    <?php
                    foreach ($rowArr as $ind => $item){
                        echo $item;
                        if($total >= 8  && $ind+1 == $half){
                            echo '</div> <div class="owl-carousel car2 owl-theme">';
                        }
                    }?>

            </div>
            <?php

                ?>
            <?php else: ?>
                <p class="no-related-post">No related posts.</p>
            <?php endif; ?>

        </div>
    </div>

