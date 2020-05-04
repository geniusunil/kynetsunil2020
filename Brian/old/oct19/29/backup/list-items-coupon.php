<?php
get_header();
global $wp_query;
$item_name = get_query_var( 'item_name' );
$settings = get_option( 'mv_list_items_settings' );

$title = $settings['alternative_page_title'];
$mainId = 0;

if ( $post = get_page_by_path( $item_name, OBJECT, 'list_items' ) )
    $mainId = $id = $post->ID;
else
    $id = 0;

$alternate_tags = get_field( 'alternate_tag', $post->ID  );
$compareClass = new Mv_List_Comparision();
$revClass= new RWP_Rating_Stars_Shortcode();;
$ratings = $compareClass->most_compared_rating($id);
$alterItems = $compareClass->most_compared($id,100,true);

if(isset($_REQUEST['alternative']) && $_REQUEST['alternative'] == 'free'){
	echo $_REQUEST['alternative'];
?>
<style>
.shwmore{
display:none;	
}
</style>
<?php
}elseif(isset($_REQUEST['alternative']) && $_REQUEST['alternative'] == 'price'){
	$alterItems = $compareClass->price_filter($alterItems);
}elseif(isset($_REQUEST['alternative']) && $_REQUEST['alternative'] == 'recomended'){?>
	<style>
      .first{
         display:block;
             }
         .shwmore{
         display:block;
        }
        .free{
        display:none	
         }
        </style>
            <?php
}
?>
<style>
.d_button {
    display: block;
	background:#00aeef;
	color:#FFF;
    width: 118px;
    height: 37px;
    padding: 7px;
    text-align: center;
    border-radius: 5px;
    font-weight: bold;
	float:right;
	margin-top: 22px;
}
.button2 {
    display: block;
	background:#ff9500;
	color:#FFF;
    width: 102px;
    height: 37px;
    padding: 7px;
    text-align: center;
    border-radius: 5px;
    font-weight: bold;
	float:right;
	margin-right: 20px;
	margin-top: 22px;
}
.d_button:hover {
    color: #fff;
	background:#00395d;
}
</style>
<?php
/*$i=0;
$alterItems1 = array();
foreach ( $alterItems as $pid ) {
 $a = get_field( 'price_starting_from', $pid);
 $a = str_replace("$","",$a);
 $alterItems1[$pid]= $a;
}
asort($alterItems1);
//print_r($alterItems1);
foreach ( $alterItems1 as $key=>$pid ) {
    $alterItems[$i]= $key;
   $i++;
   }*/
  // print_r($alterItems);
$ratingLabel = array(
    'overallrating'=>'Overall Rating',
    'valueformoney'=>'Value for money',
    'easeofuse'=>'Ease of use',
    'featuresfunctionality'=>'Features & Functionality',
    'customersupport'=>'Customer support',
);

//$headings = str_replace( '[item_name]', $post->post_title, $title );
//$heading = $title;
//$total = do_shortcode('[total_items]');
//$heading = str_replace( '[total_items]', $total, $headings );
$title = str_replace( '[Year]', date('Y'), $title );
$heading = do_shortcode( $title );
$item_title = $post->post_title;
$tableArr = array();

?>

<div id='loader-animate' style='display: none;'><span>Loading...</span></div>
<div class="content-area mv-alternative" id="primary">
    <div class="alternate-list-items alternate_upper_header">
        <div class="mv-single-lists">
            <div class="alternate_header row">
                <div class="item_data col-sm-2 col-xs-12">
                    <div class="image_data_inner">
                        <?php //echo $img = get_thumbnail_small($post->ID,array(150,150));
					 $featured_img_url = get_the_post_thumbnail_url($post->ID,array(150,150)); 
					 $alt = basename($featured_img_url);
					 $pos = strrpos($alt, ".");
					 $alt = substr($alt,0,$pos);
					 ?>
					 <img src="<?php echo $featured_img_url?>" class="img-responsive" alt="<?php echo  $alt?>" >
                      <?php  echo get_item_batch($post->ID);;

                        ?>

                    </div>
                    <div class="rat">
                        <?php
                        $ratingItem = get_overall_combined_rating($id);
                        $votes = $ratingItem['count'];
                        $score = isset($ratingItem['list']['overallrating'])?$ratingItem['list']['overallrating']['score']:0;
                        $count_label = $votes==1?'vote':'votes';
                        echo $revClass->get_stars( $score, 20, 5 );
                        echo '<div class="rwp-rating-stars-count">('. $votes .' '. $count_label .')</div>';

                     ?>
                    </div>
                </div>
                <div class="item_heading col-sm-10">
                    <div class="item_heading_inner ttt"><h1><?php echo  $heading;?></h1>
                        <div class="alternative-page-desc">
                            <p><strong>Looking for the best alternatives to <?php echo $post->post_title;?> in <?php echo date('Y');?>?</strong> That is competitive on price, easy to use, better support or a more complete solution?</p>
                            <p>Great, because today will look at the best alternatives worth considering.</p>
                            <p>In this article, we are going to look at the best matched <?php echo $post->post_title;?> replacements side by side based on our community recommendations in your industry.</p>
                            <p> Leverage the power of the crowd and discover useful and reliable <?php echo $post->post_title;?> competitors.</p>
                            <!--<p><strong>Without future ado, here is the 5 best <?php echo $post->post_title;?> alternative to consider:</strong></p>-->

                        </div>
                        <div class="review-bck-btn"><a  href="<?php echo get_the_permalink( $post->ID ); ?>" class="btn ls_referal_link" data-parameter="alternative" data-id="<?php echo $mainId;?>">Back to Review</a>
                        <?php
                        $affiliate_url = get_field( 'affiliate_url', $id  );
                        $availability = get_post_meta( $id, '_item_availbility', true );

                        $affiliate_button_text = get_field( 'affiliate_button_text', $id  ) == '' ?'Download/Demo': get_field( 'affiliate_button_text', $id  );

                        $source_url    = get_field( 'source_url', $id  );
                          if($availability == 'no') { 
                          //remove for now
                          } else {  ?>

                            <?php if ( ! empty( $affiliate_url ) ) {
								//echo $affiliate_url; 
								if(substr_count($affiliate_url, "?") >= 1){
															$affiliate_url.="&utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=alternative".$id;
														}
														else{
															$affiliate_url.="?utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=alternative".$id;
														}
								
							?>
                          <a class="mes-lc-li-down btn-affiliate zf-buy-button btn btn-primary" href="<?php echo $affiliate_url; ?>" rel="nofollow" target="_blank"><i class="zf-icon zf-icon-buy_now"></i><?php echo $affiliate_button_text ?></a>

                            <?php } 
                            }?>
                        </div> <div class="clr"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="alternate-list-items alternate_upper_table">
        <div class="mv-single-lists">
            <?php
            ob_start();
            foreach ($ratingLabel as $key => $name){
                $count = 0;
                ?>
                <div class="alternate-list-items alternate_upper_<?php echo $key;?>">
                    <div class="wider-contnet">
                        <div class="section_head">
                            <h3 id="<?php echo $key;?>"><span class="rating_label"><?php echo $name?></span></h3>
                            <span class="rating_desc"><?php echo $item_title?> alternatives according to reviews</span>
                        </div>
                        <div class="section_items">
                            <?php
                            foreach ($ratings[$key] as $item => $rat){
                                if(!array_key_exists($item,$tableArr) && $count == 0){
                                    $tableArr[$item] = $name ;
                                    $count = 1;
                                }
                                ?>
                                <div class="sec_item">
                                    <div class="item_image" data-mh="equal_h_image_<?php echo $key;?>">
                                        <div class="itme_image_inner"> <?php 
										//echo get_thumbnail_small($item);
										 $item_image = get_the_post_thumbnail_url($item); 
					// $itemim = basename($item_image);
					 //$pos = strrpos($itemim, ".");
					 //$itemim = substr($itemim,0,$pos);
					 ?>
					 <img src="<?php echo $item_image?>" class="img-responsive" alt="<?php echo get_the_title($item) ?>" >

                                            <?php echo get_item_batch($item);

                                            ?></div>

                                    </div>
                                    <div class="items_name" data-mh="equal_h_name_<?php echo $key;?>">
                                        <div class="item_title"><?php echo get_the_title($item) ?></div>
                                        <div class="item_rating">
                                            <?php
                                                $ratingSingle = get_overall_combined_rating($item);
                                                $votes = $ratingSingle['count'];

                                                $score = isset($ratingSingle['list']['overallrating'])?$ratingSingle['list']['overallrating']['score']:0;
                                                $count_label = $votes==1?'vote':'votes';

                                                echo $revClass->get_stars( $rat, 20, 5 );
                                                echo '<div class="rwp-rating-stars-count">('. $votes .' '. $count_label .')</div>';

                                            ?>
                                            <?php  // echo do_shortcode( '[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=20 post='.$item.']' );?>
                                        </div>
                                    </div>
                                    <div class="item_features" data-mh="equal_h_features_<?php echo $key;?>">
                                        <?php echo $compareClass->get_column_features($item);?>
                                    </div>

                                    <div class="item_actions" data-mh="equal_h_actions_<?php echo $key;?>">
                                        <a href="<?php echo get_permalink($item)?>" class="action_link action_view ls_referal_link" data-parameter="alternative" data-id="<?php echo $mainId;?>">View</a>
                                        <a href="<?php echo generate_compare_link(array($id,$item))?>/" class="new-comparison-btn action_link action_compare ls_referal_link" data-parameter="alternative" data-id="<?php echo $id;?>" data-secondary="<?php echo $item;?>">Compare</a>
                                        <a href="<?php echo get_the_permalink( $item )?>alternative/" class="ls_referal_link alt2-btn" data-parameter="itemid" data-id="<?php echo $item ?>" >Alernative to <?php echo get_the_title($item) ?></a>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            $rating_contnet = ob_get_contents();
            ob_get_clean();
            ?>
            <div class="alternate-table">
                <p><strong>Without future ado, here is the 5 best <?php echo $post->post_title;?> alternative to consider:</strong></p><br>
                <table class="table">
                    <thead><tr><th>Name</th><th>Best For</th><th>Pricing Model</th><th>Price</th></tr></thead>
                    <tbody>
                    <?php foreach ($tableArr as $item => $name){

                        $pricing_model = get_field( 'pricing_model', $item );
                            if(!empty(get_field( 'price_starting_from', $item ))){
                            $pricing_start = get_field( 'price_starting_from', $item );
                            }else{
                                $pricing_start ='-';
                            }

                       $pricing_text =  str_replace( '_', ' ', implode( ', ', array_map( 'ucfirst', $pricing_model ) ) );

echo "<tr><td><a href='".get_the_permalink($item)."' class='ls_referal_link' data-parameter='alternative' data-id='$mainId'>".get_the_title($item)."</a> </td><td>$name</td><td>$pricing_text</td><td>$pricing_start</td></tr>";
                    }?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="wider-contnet">
            <div class="rating_headings">
                <?php
                foreach ($ratingLabel as $key => $name){
                    echo "<div class='rat_head'><a href='#$key'>$name</a> </div>";
                }
                ?>
            </div>
        </div>
    </div>
    <?php echo $rating_contnet;?>
    <div class="alternate-list-items alternate_upper_rank">
        <div class="mv-single-lists">
            <div class="section_head">
                <span class="rating_label">Ranked in these Collections</span>
                </div>
            <div class="rank_list">
                <?php echo $compareClass->get_column_ranking($id);?>
            </div>
            <div class="all_alernative">
            
             <?php   if(!empty($alterItems)){
                    //print_r($alterItems);
				
                    $html ='<h3 id="alternative">'.do_shortcode('[total_items]').' Similar solutions to '.do_shortcode('[item_name]').'</h3>'; ?>
                    <div  class="fileter_lists" style="float:right;">
                    <select class= "myselsect" name="formGender" onChange="name_click()">
              <option value="<?php echo home_url( $wp->request ).'?alternative = recomended#alternative'?>">Recommended For Me</option>
              <option value="<?php echo home_url( $wp->request ).'?alternative=free#alternative'?>" <?php if(isset($_GET['alternative']) && $_GET['alternative'] == 'free'){?> selected="selected" <?php }?>>Free</option>
              <option value="<?php echo home_url( $wp->request ).'?alternative=price#alternative'?>">Price</option>
              <option value="<?php echo home_url( $wp->request ).'?alternative=Ease_of_use#alternative'?>">Ease of use</option>
            </select>
        
              </div>
                  <?php  $html .='<div class="alternative_lists">';   
                    $count_alternative = count($alterItems);
                   // $alterItems = array_slice($alterItems,0,20);
                    $count_itemss = 0;
                    $arra11 = array();
                  $t=0;
                    foreach ( $alterItems as $pid ) {
                        $ratingItem    = get_overall_combined_rating($pid);
						if(array_key_exists('easeofuse',$ratingItem['list'])){
                        $ese_of_use = $ratingItem['list']['easeofuse']['score'];
						}else{
						$ese_of_use="";	
						}
                        $arra11[$t]['id'] = $pid ;
                        $arra11[$t]['ease_use'] = $ese_of_use ;
                        $t++;
                    }
                    rsort($arra11);
                    if(isset($_REQUEST['alternative']) && $_REQUEST['alternative'] == 'Ease_of_use'){
                        $alterItems = $arra11;
                    }
                    
                    foreach ( $alterItems as $pid ) {
                        if(isset($_REQUEST['alternative']) && $_REQUEST['alternative'] == 'Ease_of_use'){
                           $pid = $pid['id'];
                        }
                        $item_image    = get_the_post_thumbnail_url($pid,array(50,50));
                        $ratingItem    = get_overall_combined_rating($pid);
                        $pricing_model = get_field('pricing_model', $pid);
						$affiliate_url = get_field( 'affiliate_url', $pid  );
                        $affiliate_button_text = get_field( 'affiliate_button_text', $pid  ) == '' ?'Download/Demo': get_field( 'affiliate_button_text', $pid  );
                        if(count($pricing_model) > 0) {
                            if(!empty($pricing_model[0]))
                             $p_model = ucwords(str_replace("_", " ", $pricing_model[0]));
                            else
                             $p_model = "Not available";
                        } else {
                            $p_model = "Not available";
                        }
                        
                         $score = isset($ratingItem['list']['overallrating'])?$ratingItem['list']['overallrating']['score']:0;
                         $main_list_id = "";

						 if(isset($_REQUEST['alternative']) && $_REQUEST['alternative'] == 'free'){
                        if($p_model == "Freemium"){
                      $html .='<div class="alter_item free" >
                            <div class="alter_left">
                                    <div class="cs-image"><a href="'.get_the_permalink($pid).'"><img src="'.$item_image.'" class="img-responsive" alt="'.get_the_title($pid).'" > </a></div>
                            </div>
                            <div class="alter_right">
                                <div class="alter_rt_top">
                                    <div class="alter_title cs-title single-line"><a href="'.get_the_permalink($pid).'"> '.get_the_title($pid).'</a></div>
                                    <div class="alter_rating">'.$revClass->get_stars( $score, 20, 5 ).'</div> 
                                    <div class="alter_plan"><span>'.$p_model.'</span></div></br></br>
									<div class="hr">
									<p>'.get_excerpt_custom($pid,130).'...
									<a class="mes-lc-li-down btn-affiliate zf-buy-button getbtn d_button"  href="'.$affiliate_url.'>" rel="nofollow" >'.$affiliate_button_text.'</a>
									<a class="mes-lc-li-link update_list_modified_link button2" data-zf-post-id="'. $pid.'" data-zf-post-parent-id="'.$main_list_id.'" href="'.get_the_permalink( $pid ).'" rel="nofollow">Read Review</a>
									</p>
									</div>
                                </div>
                            </div>
							
                            </div>'; 
                             } 
					}else{
                         if($count_itemss < 20){ 
                            $html .='<div class="alter_item first" >
                            <div class="alter_left">
                                    <div class="cs-image"><a href="'.get_the_permalink($pid).'"><img src="'.$item_image.'" class="img-responsive" alt="'.get_the_title($pid).'" > </a></div>
                            </div>
                            <div class="alter_right">
                                <div class="alter_rt_top">
                                    <div class="alter_title cs-title single-line"><a href="'.get_the_permalink($pid).'"> '.get_the_title($pid).'</a></div>
                                    <div class="alter_rating">'.$revClass->get_stars( $score, 20, 5 ).'</div> 
                                    <div class="alter_plan"><span>'.$p_model.'</span></div></br></br>
									<div class="hr">
									<p>'.get_excerpt_custom($pid,130).'...
									<a class="mes-lc-li-down btn-affiliate zf-buy-button getbtn d_button"  href="'.$affiliate_url.'>" rel="nofollow" >'.$affiliate_button_text.'</a>
									<a class="mes-lc-li-link update_list_modified_link button2" data-zf-post-id="'. $pid.'" data-zf-post-parent-id="'.$main_list_id.'" href="'.get_the_permalink( $pid ).'" rel="nofollow">Read Review</a>
									</p>
									</div>
                                </div>
                            </div>
							
                            </div>'; 
                           
                        }else{
                        $html .='<div class="alter_item second hidden">
                        <div class="alter_left">
                                <div class="cs-image"><a href="'.get_the_permalink($pid).'"><img src="'.$item_image.'" class="img-responsive" alt="'.get_the_title($pid).'" > </a></div>
                        </div>
                        <div class="alter_right">
                            <div class="alter_rt_top">
                                <div class="alter_title cs-title single-line"><a href="'.get_the_permalink($pid).'"> '.get_the_title($pid).'</a></div>
                                <div class="alter_rating">'.$revClass->get_stars( $score, 20, 5 ).'</div> 
                                <div class="alter_plan"><span>'.$p_model.'</span></div></br></br>
									<div class="hr">
									<p>'.get_excerpt_custom($pid,130).'...
									<a class="mes-lc-li-down btn-affiliate zf-buy-button getbtn d_button"  href="'.$affiliate_url.'>" rel="nofollow" >'.$affiliate_button_text.'</a>
									<a class="mes-lc-li-link update_list_modified_link button2" data-zf-post-id="'. $pid.'" data-zf-post-parent-id="'.$main_list_id.'" href="'.get_the_permalink( $pid ).'" rel="nofollow">Read Review</a>
									</p>
									</div>
                            </div>
                        </div>
                        </div>';
						
                        
                    } 
					}
                        $count_itemss++;                                     
                    }
                    $html .='</div>';
             }
             echo $html;
            ?>
                <?php  if($count_alternative>20){ ?>
                                <button type="button" class="shwmore" style="background-color:#ff9500; color:#000">Show More</button> 
                               
                                <?php } ?> 
                 <script>
jQuery('.shwmore').click(function(){
    jQuery(".second").removeClass("hidden");
	jQuery(".shwmore").addClass("hidden");
    });

    function name_click() {
        var selected = jQuery('.myselsect').val();
	    window.location.href = selected;	
}

                 </script>
          <?php 
             ?>
            </div>
           	<p>
	Was this comparison helpful?
	</p>
	<?php if(function_exists('the_ratings')) { the_ratings(); } ?>
        </div>
    </div>
</div>
<?php
get_footer();
?>