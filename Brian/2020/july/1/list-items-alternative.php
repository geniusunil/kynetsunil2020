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
$compareClass = new Mv_List_Comparision( null,true);
$revClass= new RWP_Rating_Stars_Shortcode();;
$ratings = $compareClass->most_compared_rating($id);
$item_fs = get_or_calc_fs_individual($mainId);
foreach($ratings as $rat_item){

foreach($rat_item as  $key => $item_listed){
    
    $pricing_model[$key] = get_field('pricing_model', $key);

    

 }
}
// $highestfs= array_keys($list_findrScore,max($list_findrScore));


$freefs_text ="";
$i=0;
 foreach ($pricing_model as $key=> $pricing ) {
    $freeitem_findrScore = get_or_calc_fs_individual($key); 

foreach( $pricing as $p){    
    $title_singular_list ='';
    // $freefs_text ="";
    
// echo $item_fs;
    
   
    // if( $freeitem_findrScore > $item_fs){
        // $show_text ="";
        if (($p == 'freemium' || $p == 'open_source'  ) && ( $freeitem_findrScore > $item_fs)) {
        // echo "key_fs <br>";
        //     echo $freeitem_findrScore;
        
            $freefs_text = ',some of the best alternatives  '.get_the_title($mainId).'  are actually free!';
            
           
    }
   
    
        }
        $i++;
        if($i == 1)
        break;
        }
   


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
ul.main_container_sidebar_list li {
    margin: 12px 0 0 0;
}
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
.morecontent {
  display: none;
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



$get_list_data =  get_item_ranks($post->ID);
                    
//  print_r($get_list_data);
 foreach ($get_list_data as $list_id => $rank) { 
    $all_items = get_field("list_items", $list_id, false); //count( $listrankord );                
    $all_item_count = count($all_items);
    // $all_award_list[$list_id][$all_item_count] = $rank;
    $ranked_list = ($rank / $all_item_count) * 100;

    // print_r($all_items);
    // echo "<br>";
    $award_items = '';
   $title_plural ='';
    $title_singular ='';
    $voters ='';
     $title_singular = get_field('list_content_title_singular', $list_id);
        if($title_singular == ''){ 
             
               $title_singular = get_the_title($list_id);
               
                 
              }
    if($ranked_list <= 10){

        $voters = do_shortcode("[total_votes id=$list_id]");   
        $title_plural = get_field('list_content_title_plural', $list_id);
        

       
    
              if($title_plural == ''){ 
             
               $title_plural = get_the_title($list_id);
               
                 
              }
               $award_items =   "".get_the_title()."  is one of the best ".$title_plural." on the market 
            according to ".$voters." voters and when you take into account its ease of use and responsiveness,
             it beats most of the competition. ";
                                            
       
    break;
    }
   

}




?>

<div id='loader-animate' style='display: none;'><span>Loading...</span></div>
<div class="content-area mv-alternative" id="primary">
    <div class="alternate-list-items alternate_upper_header">
        <div class="mv-single-lists_sec1">
            <div class="alternate_header row">
                <div class="item_data col-sm-2 col-12">
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
                <div class="item_heading col-sm-7 col-12">
                    <div class="item_heading_inner ttt"><h1><?php echo  $heading;?></h1>
                        <div class="alternative-page-desc">
                            

                        <p><strong>Looking for the best alternatives to <?php echo $post->post_title;?> in <?php echo date('Y');?>?</strong>
                             That is competitive on price, easy to use, better support or a more complete solution?</p>
                            <p>Great, because today will look at the best alternatives worth considering.</p>
                            <div class="more">
                            <p>In this article, we are going to look at the best matched <?php echo $post->post_title;?> replacements side by side based on our community recommendations in your industry.</p>
                          
                           <p> Leverage the power of the crowd and discover useful and reliable <?php echo $post->post_title;?> competitors.</p>
                            <!--<p><strong>Without future ado, here is the 5 best <?php echo $post->post_title;?> alternative to consider:</strong></p>-->

              
                            <p>   Whilst <?php echo $post->post_title;?> as a track record on our system of having great support the same can not be said about the certain features on offer according to our users. </p>
                            <p>
                            <?php                      
                        

                            echo $award_items;
 
                      

                            ?>
                            There are certain flaws that might send you on a long journey, looking for its alternative.
                            </p>
                                    
                            <p>  Luckily,  <?php echo $post->post_title; ?> is not your only choice for <?php echo $title_singular; ?>. These days, you have plenty of options on the market. That might even be a better fit for you<?php echo   $freefs_text; ?> </p>
                             <p>   Before we get to the list of similar solutions, looking at the data gathered on our platform our users love item for:<br>
                            <?php
                            $item_feature_lists = get_field('features_list', $post->ID);
                            $features_ids = array(
                                        'id0' => $post->ID,
                                    );
                            $sorted_feature_list = sort_features($item_feature_lists, $features_ids);
                            $i = 1;
                                    foreach ($sorted_feature_list as $vote => $feature) {
                                        $three = $feature['feature'];
                          					echo $three."<br>";

                                        $i++;
                                        if ($i == 4) {
                                            break;
                                        }
                                    }
                        
                            
                            ?>
                              
                             For their top-notch customer support.</p>
<p>
                                
                                
                                <?php
                                 $rnr2b=get_pros_cons($mainId);

                                // $r2b = $rnr2b['r2b'];
                                $rn2b = $rnr2b['rn2b'];
                                // echo "pros cons <pre>" ;
                                // print_r($rn2b);
                                // echo " </pre>" ;
                                // shuffle($r2b);
                                shuffle($rn2b);
                               
                                // print_r($rn2b);
                                $htmlrn2b = '<ul>';
                                foreach ($rn2b as $r) {
                                    $htmlrn2b .= '<li> ->' . $r . '</li>';
                                }
                                $htmlrn2b .= '</ul>';
                                // $proscons = '<div id="pros-cons" class="row">

                                ?>
                               <p> 
								   <?php 
								   
								 if(!empty($rn2b)){
									echo 'Now let\'s have a look at a few of their lowest-rated features: '.$htmlrn2b;
								 }  
								   ?>
                                 </p>

                            <p>
                                <strong>Cost of  <?php echo $post->post_title;?> </strong></p>
                            <?php 
                          
                            $item_price =  get_field('price_starting_from', $mainId);
                            // $item_price = (float) filter_var($item_price, FILTER_SANITIZE_NUMBER_INT);
                            $item_model =  get_field('pricing_model', $mainId);
                            $plan = get_field('plan', $mainId);
                            
                            foreach ($item_model as $pricing) {

                                $pricing_model_list = $pricing;

                            } 
                            //echo $pricing_model;
                           // $item_model_list = implode(',',$pricing_model);
						   $item_model_list = str_replace( '_', ' ', implode( ', ', array_map( 'ucfirst', $item_model ) ) );
                          
							$item_trial =  get_field('free_trial', $mainId);
							$credit = get_field('card_required', $mainId);
                            if(($item_trial == 1) && ($credit == 1)){
                                $trial = " Available (Credit Card required)";
                            }
                            elseif($item_trial == ""){
                                $trial = " Not Available ";
                            }else{
								$trial = " Available (No Credit Card required) ";
							}

                            $item_couponlist = get_post_meta($mainId, 'coupons_list', true);
                             $finally_selected_categories ="";
                                $selected_categories = wp_get_post_terms($mainId, 'list_categories', array("fields" => "all"));
                                foreach ($selected_categories as $singleselected_categories) {
                                    $finally_selected_categories = $singleselected_categories->slug;
                                    break;
                                }


                            if(!empty($item_couponlist))
                            {
                            
                                $list_coupon = '<a href = '.get_permalink($mainId).'coupon>See '.get_the_title($mainId).' offers </a>';
                                
                            }
                            else{
                                      

                                $list_coupon = '<a href=' . home_url() . '/deals/?cat=' . $finally_selected_categories . '>View offers on similar solutions</a>';
                                
                                          }

                                ?>
                                <p>   <strong>Starting From: </strong> $<?php echo $item_price; ?>/<?php echo $plan;?> </p>
                                <p> <strong>Pricing Model:</strong> <?php echo $item_model_list; ?>  </p>
                                <p>  <strong> Free Trial:</strong><?php echo $trial; ?>  </p>
                                <p>  <strong> Promotional Offer:</strong> <?php echo $list_coupon; ?> </p>
                                <?php 
                                 $compObj = new Mv_List_Comparision(null,true);
                                 $lists = $compObj->most_compared($mainId, 20, true);
                                 
                                
                                 foreach ($lists as $pid) {
                                
                                $item_price = get_field('price_starting_from', $pid);
                                $all_price1[$pid] = (float) filter_var($item_price, FILTER_SANITIZE_NUMBER_INT);
                                $pricing_model = get_field('pricing_model', $pid);
                            
                                 foreach( $pricing_model as $price){
                                 $all_price[] = $price;
                                
                                 }
                                 }
     					 $all_price_frequent=   array_count_values($all_price);
                         	arsort($all_price_frequent);
                           // print_r($all_price_frequent);
                                $i =0;
                                foreach ($all_price_frequent as $k => $list) {

                                $frequent_model[] = $k;
                                $i++;
                                 if($i == 1)
                                break;
                                
                                }
                                // print_r($all_price1);
								$frequent_modellist = str_replace( '_', ' ', implode( ', ', array_map( 'ucfirst', $frequent_model ) ) );

                                $max_price = max($all_price1);                                
                                $min_price = min($all_price1);

                                // echo $min_price;
                                ?>
                                     <p>  When looking for a solution like <?php echo $post->post_title;?>, you can expect to pay anywhere from $<?php echo $min_price;?> - $<?php echo $max_price;?> ( <?php echo $frequent_modellist;?>)
                                </p>

                        </div>
                        
                        


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
                <div class="main_container_sidebar col-sm-3 col-12">
                <div class="table_content">  <p class="toc_container">Top Alternative to <?php echo $post->post_title;?><br>
                <ul class = "main_container_sidebar_list">
               <?php 
            //    echo "list altrntive";
            //    print_r($alterItems); 
                    $i=0;
               foreach($alterItems as $list){
               echo '<li><i class="fa fa-arrow-circle-o-right" style="color: #5478dc; font-size: 13px; float: left; margin: 3px 9px 0 0;"></i>'.get_the_title($list).'</li>';

               $i++;

               if($i == 10)
               break;

               }
               
               ?>
                <!-- <li><i class="fa fa-arrow-circle-o-right" style="color: #ff9500; font-size: 13px; float: left; margin: 3px 9px 0 0;"></i> Ranking</li>
                <li><i class="fa fa-arrow-circle-o-right" style="color: #ff9500; font-size: 13px; float: left; margin: 3px 9px 0 0;"></i>Alternative</li>
                <li><i class="fa fa-arrow-circle-o-right" style="color: #ff9500; font-size: 13px; float: left; margin: 3px 9px 0 0;"></i>Summary</li> -->
                </ul>
                
                </p></div> 
                </div>

            </div>
        </div>
    </div>

<!--    <?php echo $rating_contnet;?> -->


    <div class="alternate-list-items alternate_upper_rank">
    <div class="mv-single-lists_sec1">
    <div id="poll">
               <div class="equalWidth"><span>Cost</span><div class="grey cost"><div class="whiteText" data-voteTo="cost">Click to Vote</div></div></div> <!-- <div class="blue"></div> -->
               <div class="equalWidth"><span>Functionality</span><div class="grey functionality"><div class="whiteText" data-voteTo="functionality">Click to Vote</div></div></div>
               <div class="equalWidth"><span>Services</span><div class="grey services"><div class="whiteText" data-voteTo="services">Click to Vote</div></div></div>
               <div class="equalWidth"><span>Architecture</span><div class="grey architecture"><div class="whiteText" data-voteTo="architecture">Click to Vote</div></div></div>
               <div class="equalWidth"><span>Changing needs</span><div class="grey changing_needs"><div class="whiteText" data-voteTo="changing_needs">Click to Vote</div></div></div>
               <div class="equalWidth"><span>Political Reasons</span><div class="grey political_reasons"><div class="whiteText" data-voteTo="political_reasons">Click to Vote</div></div></div>
               <div class="equalWidth"><span>Vendor Rationalization</span><div class="grey vendor_rationalization"><div class="whiteText" data-voteTo="vendor_rationalization">Click to Vote</div></div></div>
               <div class="equalWidth"><span>Usability</span><div class="grey usability"><div class="whiteText" data-voteTo="usability">Click to Vote</div></div></div>
               <div class="equalWidth"><span>other</span><div class="grey other"><div class="whiteText" data-voteTo="other">Click to Vote</div></div></div>

            </div>
            </div>
        <div class="mv-single-lists">
            
            <div class="section_head">
                <span class="rating_label" id="rating_label">Ranked in these Collections</span>
                </div>
            <div class="rank_list">
                <?php echo $compareClass->get_column_ranking($id ,'');?>
            </div>
            <div class="all_alernative" id="all_alernative">
            
             <?php   if(!empty($alterItems)){
                    //print_r($alterItems);
                
                    
                    $i=0;
                    $html11 =""; 
                    foreach($alterItems as $promotd_list1){
                        //
                        $item_image    = get_the_post_thumbnail_url($promotd_list1,array(50,50));
                        $ratingItem    = get_overall_combined_rating($promotd_list1);
                        $pricing_model = get_field('pricing_model', $promotd_list1);
						$affiliate_url = get_field( 'affiliate_url', $promotd_list1  );
                        $affiliate_button_text = get_field( 'affiliate_button_text', $promotd_list1  ) == '' ?'Download/Demo': get_field( 'affiliate_button_text', $promotd_list1  );                       
                        $promoted = get_field('promoted_on_alternative_page',$promotd_list1);

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
                           
                        $affiliate_button_text = get_field( 'affiliate_button_text', $promotd_list1  ) == '' ?'Download/Demo': get_field( 'affiliate_button_text', $promotd_list1  );
                        
                        $promoted = get_field('promoted_on_alternative_page',$promotd_list1);
                        if (!empty($promoted )){
                           
                            $html11 ='<div class="alter_item" >
                            <div class="alter_left">
                                    <div class="cs-image"><a href="'.get_the_permalink($promotd_list1).'"><img src="'.$item_image.'" class="img-responsive" alt="'.get_the_title($promotd_list1).'" > </a></div>
                            </div>
                            <div class="alter_right">
                                <div class="alter_rt_top">
                                    <h3 class="alter_title cs-title single-line"><a href="'.get_the_permalink($promotd_list1).'"> '.get_the_title($promotd_list1).'</a></h3>
                                    <div class="alter_rating">'.$revClass->get_stars( $score, 20, 5 ).'</div> 
                                    <div class="alter_plan">Promoted <span>'.$p_model.'</span></div></br></br>
                                        <div class="hr">
                                        <p>'.get_excerpt_custom($promotd_list1,130).'...
                                        <a class="mes-lc-li-down btn-affiliate zf-buy-button getbtn d_button"  href="'.$affiliate_url.'" rel="nofollow" >'.$affiliate_button_text.'</a>
                                        <a class="mes-lc-li-link update_list_modified_link button2" data-zf-post-id="'. $promotd_list1.'" data-zf-post-parent-id="'.$main_list_id.'" href="'.get_the_permalink( $promotd_list1 ).'" rel="nofollow">Read Review</a>
                                        </p>
                                        </div>
                                </div>
                            </div>
                            </div>';

                            // echo $promotedd_list;
                           
                        $i++;
                        if($i== 1)
                         break;
                        } 
                   
                         
                }
                if (($key = array_search($promotd_list1, $alterItems)) !== false) {
                    unset($alterItems[$key]);
                }
              
                
                // echo "worked?".$html11;

                    $html ='<h2 id="alternative">'.do_shortcode('[total_items]').' Best Alternatives to '.do_shortcode('[item_name]').'</h2>'; ?>

                   

                    <div  class="fileter_lists" style="float:right;">
                    <select class= "myselsect" name="formGender" onChange="name_click()">
              <option value="<?php echo home_url( $wp->request ).'?alternative = recomended#alternative'?>">Recommended For Me</option>
              <option value="<?php echo home_url( $wp->request ).'?alternative=free#alternative'?>" <?php if(isset($_GET['alternative']) && $_GET['alternative'] == 'free'){?> selected="selected" <?php }?>>Free</option>
              <option value="<?php echo home_url( $wp->request ).'?alternative=price#alternative'?>">Price</option>
              <option value="<?php echo home_url( $wp->request ).'?alternative=Ease_of_use#alternative'?>">Ease of use</option>
            </select>
        
              </div>
                  <?php  $html .='<div class="alternative_lists">'; 
                    $html .= $html11;  
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
                


                    // echo "itemmm";
                    // print_r($alterItems);
                   

                //    echo "anncc";
                //    print_r($alterItems );
                 
                    foreach ( $alterItems as $pid ) {
                        if(isset($_REQUEST['alternative']) && $_REQUEST['alternative'] == 'Ease_of_use'){
                           $pid = $pid['id'];
                        }
                        $item_image    = get_the_post_thumbnail_url($pid,array(50,50));
                        $ratingItem    = get_overall_combined_rating($pid);
                        $pricing_model = get_field('pricing_model', $pid);
						$affiliate_url = get_field( 'affiliate_url', $pid  );
                        $affiliate_button_text = get_field( 'affiliate_button_text', $pid  ) == '' ?'Download/Demo': get_field( 'affiliate_button_text', $pid  );                       
                        $promoted = get_field('promoted_on_alternative_page',$pid);

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


                        //  $promotedd_list  ="";

                                               
						 if(isset($_REQUEST['alternative']) && $_REQUEST['alternative'] == 'free'){
                        if($p_model == "Freemium"){
                      $html .='<div class="alter_item free" >
                            <div class="alter_left">
                                    <div class="cs-image"><a href="'.get_the_permalink($pid).'"><img src="'.$item_image.'" class="img-responsive" alt="'.get_the_title($pid).'" > </a></div>
                            </div>
                            <div class="alter_right">
                                <div class="alter_rt_top">
                                    <h3 class="alter_title cs-title single-line"><a href="'.get_the_permalink($pid).'"> '.get_the_title($pid).'</a></h3>
                                    <div class="alter_rating">'.$revClass->get_stars( $score, 20, 5 ).'</div> 
                                    <div class="alter_plan"><span>'.$p_model.'</span></div></br></br>
									<div class="hr">
									<p>'.get_excerpt_custom($pid,130).'...
									<a class="mes-lc-li-down btn-affiliate zf-buy-button getbtn d_button"  href="'.$affiliate_url.'" rel="nofollow" >'.$affiliate_button_text.'</a>
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
                                    <h3 class="alter_title cs-title single-line"><a href="'.get_the_permalink($pid).'"> '.get_the_title($pid).'</a></h3>
                                    <div class="alter_rating">'.$revClass->get_stars( $score, 20, 5 ).'</div> 
                                    <div class="alter_plan"><span>'.$p_model.'</span></div></br></br>
									<div class="hr">
									<p>'.get_excerpt_custom($pid,130).'...
									<a class="mes-lc-li-down btn-affiliate zf-buy-button getbtn d_button"  href="'.$affiliate_url.'" rel="nofollow" >'.$affiliate_button_text.'</a>
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
                                <h3 class="alter_title cs-title single-line"><a href="'.get_the_permalink($pid).'"> '.get_the_title($pid).'</a></h3>
                                <div class="alter_rating">'.$revClass->get_stars( $score, 20, 5 ).'</div> 
                                <div class="alter_plan"><span>'.$p_model.'</span></div></br></br>
									<div class="hr">
									<p>'.get_excerpt_custom($pid,130).'...
									<a class="mes-lc-li-down btn-affiliate zf-buy-button getbtn d_button"  href="'.$affiliate_url.'" rel="nofollow" >'.$affiliate_button_text.'</a>
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
      <!-- //  -------------------------------------------- Alternative page Faq start---------------------------------------------->
     <?php
// $item_fs
$i=0;
foreach($alterItems as $key)
{
    $top_alterantive = get_the_title($key);
    $top_id = $key;
  
    $topaltrfs = get_or_calc_fs_individual($key);
    $i++;
    if($i == 1){
    break;
    }
}
$comp_link =generate_compare_link(array($mainId, $top_id));

if($item_fs > $topaltrfs){

    $faq1= " but $item_name as a higher FindScore than $top_alterantive which factors in many data points.<a href='$comp_link'>Have a closer look to see why?</a>";
}
else{
    $faq1 = " but $top_alterantive as a higher FindScore than $item_name which factors in many data points.<a href='$comp_link'>Have a closer look to see why?</a>";
}


foreach($alterItems as $listitem)
{        
      $item_free[$listitem] =  get_field('pricing_model', $listitem);
      $all_items_price =  get_field('price_starting_from', $listitem, false);
      $price_altrnative[$listitem] = (float) filter_var($all_items_price, FILTER_SANITIZE_NUMBER_INT);
      $rating_alternative[$listitem] = get_overall_combined_rating($listitem);
      

           
}



$j = 0;
$free_item_title = array();
foreach ($item_free as  $q => $abc) {

    foreach($abc as $free_price){
       
 
         if($free_price == 'freemium' ){  

             $free_item_title[] = get_the_title($q); 
             

           
         }     
      
      }      
     
  }
  $k=0;
  $top1_alternative = array();
  foreach($alterItems as $top_three)
  
{
    $top1_alternative[] = $top_three;
    $k++;
    if($k ==3)
    break;

}
$top1_alternative_item = get_the_title($top1_alternative[0]);
$top2_alternative = get_the_title($top1_alternative[1]);
$top3_alternative = get_the_title($top1_alternative[2]);
 

  $list_item = get_field('add_to_list', $mainId, false);
        foreach ($list_item as $key => $lists) {

            $post_ids = get_field('list_items', $lists);
           
            foreach ($post_ids as $post_id_item) {
                $all_item_id[] = $post_id_item->ID;
            }

        }
        $all_item_result = array_unique($all_item_id);
        // print_r($all_item_result);
        // echo "count ".count($all_item_result);
        foreach($all_item_result as $ii)
        {
            $fs_list[$ii] = get_or_calc_fs_individual($ii);

            $all_price123 = intval(get_field( 'price_starting_from', $ii, false));
            $all_price_list[$ii]= (float) filter_var($all_price123, FILTER_SANITIZE_NUMBER_INT);

        }

       
        arsort($fs_list);
        $all_fs_sum = array_sum($fs_list);       
        $ind_fs = $all_fs_sum / count($all_item_result);
        $industry_fs = round($ind_fs,2);  
        

        $all_price_sum = array_sum($all_price_list);
        $ind_price= $all_price_sum / count($all_item_result);
        // $industry_price = round($ind_price,2);

        $itemprice = get_field( 'price_starting_from', $mainId);
        // echo "<pre>";
        // // $price_altrnative11 = intval($price_altrnative);
        // print_r($price_altrnative);
        // echo "</pre>";
      
        // $two_altr ='';
      foreach( $price_altrnative as $key => $less_price){
        if($itemprice > $less_price){
           
           $two_altr[]= $key;
           
        }
          
        

      }
 /*    echo "two altr";
    print_r($two_altr); */
   $res =array();
foreach($two_altr as $altr){
    $res []= get_the_title($altr);
    if(count($res) == 2){
        break;
    }
}

if(!empty($two_altr))
{
    $lis_altrntiave =  implode("," ,$res);
    
   
}
else{
    $lis_altrntiave = "There is no cheaper alternative for $item_name ";
}

/* echo "industry_price";
echo $ind_price;
echo "item price";
echo $itemprice; */


if($itemprice < $ind_price)
{
    $faq6 = "Currently $item_name is well priced on the market but here are a few similar solutions to consider you can try $lis_altrntiave";
}
elseif(empty($two_altr)){
    $faq6 = $lis_altrntiave;
}
else{

    $faq6 = "There are cheaper alternatives available but with everything, there is a trade-off, you can try $lis_altrntiave. We recommend you use our comparison feature to see how they fare against each other";
}


// echo "item fs $item_fs industry fs $industry_fs";
        if($item_fs > $industry_fs){
            $fs_text = "good";
        }
        else{
            $fs_text = "bad";
        }

        $item_feature_lists = get_field('features_list', $mainId);
        $features_ids = array(
            'id0' => $mainId,
        );
        $sorted_feature_list = sort_features($item_feature_lists, $features_ids);
        // print_r($sorted_feature_list);
        $i = 0;
        $three = array();
        foreach ($sorted_feature_list as $vote => $feature) {
            $three[] = $feature['feature'];
           

            $i++;
            if ($i == 2) {
                break;
            }
        }

        $item_ranks = get_item_ranks($mainId);
       
       
        
        foreach ($item_ranks as $id => $rank) {
            
            $list_name = $id;
            $item_pos = $rank;

            $all_items = get_field("list_items", $id, false);
            $total_items = count($all_items);
            
        break;
        
        }
       


        // echo $list_name;
        
        $faq_list_name = get_field('list_content_title_singular', $list_name);
        if($faq_list_name == ''){
            $faq_list_name = get_the_title($list_name);
        }
        $finalVotes = do_shortcode("[total_votes id=$list_name]");
        // echo "feature";
        // echo $three[0];

     /*    foreach($all_item_result as $post_ids)
        {
            $item_feature_lists = get_field('features_list', $post_ids);
            
            print_r($item_feature_lists);

            foreach($item_feature_lists as $feature){           
                // $all_feature =$feature['feature'];
               
                // if($three[0] == $all_feature){
                  
                //     $res[]= $i;
                    

             
                // }
            }

        } */
        
// print_r($res);

        // $res=array();
        // $listana = array();
        // foreach($sorted_altrntve_list as $i => $list_name){  
           
          
        //     foreach($list_name as $feature){           
        //         $all_feature =$feature['feature'];
               
        //         if($three[0] == $all_feature){
                  
        //             $res[]= $i;
                    

             
        //         }
           
        //     }

        // }

        // print_r($res);
        $show ="";
        // $two_altr_feature = array();
        $listana = array();
        foreach($two_altr as $resultt){
            $features = get_field('features_list',$resultt);
            /* echo "result $resutltt";
            print_r($features); */
            if(is_array($features)){
                if(in_array($three[0],$features)){
                    //    echo "abc";
    
                        // echo $i;
                        $listana[] = get_the_title($resultt);
                    
                        
                }
            }
           

           
         }
        /*  echo "listana";
         print_r($listana); */
         if(!empty($listana)){
            if(!empty($listana[1])){
                $show = 'or '. $listana[1].'';
            }
            $faq7 ="If $three[0] is what is most important to you then you might want to consider ". $listana[0]." ". $show."" ;
        
         }
         else{

            $faq7 = "There is no cheaper alternative with $three[0]";
        }

         //faq8
    //      echo "<pre>";
    // print_R($rating_alternative);
    // echo "</pre>";
         $rating_item = get_overall_combined_rating($mainId);
         $item_ease = $rating_item['list']['easeofuse']['score'];
         $loop="";

        //  echo "ease_of use item";
        //  echo $item_ease;
         $all_item_ease = array();
         foreach($rating_alternative as $key =>$rat){
            $list = $rat['list']['easeofuse']['score'];
            // echo "<br>".$key;
            // echo "listitems ease <br>";
            // echo $list;

          
            if($item_ease < $list){
                // echo "rtaing!23";
                $all_item_ease[] = $key;

        //          echo "<pre>";
        //   print_R($all_item_ease);
        //   echo "</pre>";

                // $ease_item='';
                // if(!empty($all_item_ease[1])){
                //     $ease_item = ' or '.get_the_title($all_item_ease[1]).'';
        
                //   }

                  
                // $faq_9 = 'If you are after a more user-friendly alternative to '.$item_name.' then you might want to consider '.get_the_title($all_item_ease[0]).'
                // '.$ease_item.'  which as a higher ease of use rating than '.$item_name.'.' ;

                // echo "rtaing!23";
            }
            // else{
            //     $faq_9 = 'There is no alternative which has highest ease of use than '.$item_name.'.';
            // }

           

         }
         
        //  echo "alll item  <pre>";
        //   print_R($all_item_ease);
        //   echo "</pre>";
          
          if(!empty($all_item_ease)){
            $ease_item='';
            if(!empty($all_item_ease[1])){
                $ease_item = ' or '.get_the_title($all_item_ease[1]).'';
    
              }

              
            $faq_9 = 'If you are after a more user-friendly alternative to '.$item_name.' then you might want to consider '.get_the_title($all_item_ease[0]).'
            '.$ease_item.'  which as a higher ease of use rating than '.$item_name.'.' ;

          }
          else{
            $faq_9 = 'There is no alternative which has highest ease of use than '.$item_name.'.';
          }



       

        
?>
      <div id="overview">
                    <h2>Frequently Asked Questions</h2>
        
                    <div>
                    <span itemprop="description"> 
                  <?php  
                  $item_name = get_the_title($mainId);
                  $faq = " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                    <h4 itemprop='name'> Q.  Is $top_alterantive better than $item_name ?</h4>
                    <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                        <div itemprop='text'>
                        Ans. This is a tricky question as it all depends on your needs, $faq1 . 

                         </div>
                    </div>
                </div>
                 ";
                 $faq .= "<div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                    <h4 itemprop='name'> Q.  Is there a free version of $item_name? </h4>
                    <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                        <div itemprop='text'>
                        Ans. If you are looking for a free alternative to $item_name, then $free_item_title[0]  might be a good fit.

                         </div>
                    </div>
                </div>
                 ";
                 $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                 <h4 itemprop='name'> Q.  Who are $item_name 's competitors? </h4>
                 <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                     <div itemprop='text'>
                     Ans. Typically when users are looking at $item_name they also look at these competitors $top1_alternative_item , $top2_alternative and $top3_alternative.


                      </div>
                 </div>
             </div>
              ";
              $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                 <h4 itemprop='name'> Q.   Is $item_name a good buy? </h4>
                 <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                     <div itemprop='text'>
                     Ans. This is subjective, however, so far users are having a $fs_text overall satisfaction with $item_name. One of it’s stand out features is $three[0] and $three[1]


                      </div>
                 </div>
             </div>
              ";
              $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                 <h4 itemprop='name'> Q.    Is $item_name a good $faq_list_name?       </h4>
                 <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                     <div itemprop='text'>
                     Ans. When it comes to $faq_list_name, $item_name is ranked  $item_pos out of $total_items in that category according to  $finalVotes voters.

                      </div>
                 </div>
             </div>
              ";

              
              $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                 <h4 itemprop='name'> Q.   What is a cheaper alternative to $item_name?     </h4>
                 <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                     <div itemprop='text'>
                     Ans. $faq6 .
                    

                      </div>
                 </div>
             </div>
              ";

              $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                 <h4 itemprop='name'> Q.   What is a cheaper alternative to $item_name for $three[0]?     </h4>
                 <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                     <div itemprop='text'>
                        Ans. $faq7 .

                      </div>
                 </div>
             </div>
              ";
              $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                 <h4 itemprop='name'> Q.  $item_name alternative for beginners?
                 </h4>
                 <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                     <div itemprop='text'>
                     Ans. $faq_9 .

                      </div>
                 </div>
             </div>
              ";

              echo $faq;

                    ?>
                    
                    
                    </span>
                    </div>
                    </div>
                    <style>
                .equalWidth{
                    width: 10%;
                    display: inline-block;
                }
                @media screen and (max-width:768px){
                
                    .equalWidth{
                        width:32%;
                    }
                }
                .grey {
                    height: 100px;
                    background: grey;
                    position: relative;
                    display:flex;
                    color:white;
                   
                }
                .blue {
                    height: 49%;
                    width: 100%;
                    bottom: 0;
                    position: absolute;
                    background: blue;
                    
                }
                .whiteText{
                    margin:auto;
                    cursor:pointer;
                }
            </style>

     

            
     
     

            
  <!---------------------------------------------- Alternative page Faq end------------------------------------- -->
      
                 <script>
jQuery('.shwmore').click(function(){
    jQuery(".second").removeClass("hidden");
	jQuery(".shwmore").addClass("hidden");
    });

    function name_click() {
        var selected = jQuery('.myselsect').val();
	    window.location.href = selected;	
}
var showChar = 200;
	var ellipsestext = "...";
	var moretext = " Read more";
	var lesstext = "less";
	jQuery('.more').each(function() {
	  var content = jQuery(this).html();
	  var textcontent = jQuery(this).text();

	  if (textcontent.length > showChar) {

	    var c = textcontent.substr(0, showChar);
	    //var h = content.substr(showChar-1, content.length - showChar);

	    var html = '<span class="container"><span>' + c + '</span>' + '<span class="moreelipses">' + ellipsestext + '</span></span><span class="morecontent">' + content + '</span>';

	    jQuery(this).html(html);
        jQuery(this).after('<a href="" class="morelink">' + moretext + '</a>');
	  }

	});

	jQuery(".morelink").click(function() {
	  if (jQuery(this).hasClass("less")) {
        jQuery(this).removeClass("less");
	    jQuery(this).html(moretext);
        jQuery(this).prev().children('.morecontent').fadeToggle(500, function(){
            jQuery(this).prev().fadeToggle(500);
        });
       
	  } else {
	    jQuery(this).addClass("less");
	    jQuery(this).html(lesstext);
        jQuery(this).prev().children('.container').fadeToggle(500, function(){
            jQuery(this).next().fadeToggle(500);
        });
	  }
      //$(this).prev().children().fadeToggle();
	  //$(this).parent().prev().prev().fadeToggle(500);
	  //$(this).parent().prev().delay(600).fadeToggle(500);
	  
	  return false;
	});

   const greyVotes = document.querySelectorAll(".whiteText");
    for (const button of greyVotes) {
        button.addEventListener('click', sendQuitVote );
       //button.addEventListener('click', awardItemSelected);
       
    }

 


    function sendQuitVote(event){
        // alert('hi');
        var voteTo=event.target.getAttribute('data-voteto');
        var post_id= <?php echo $post->ID?>;
        console.log({voteTo});
        jQuery.ajax(
                {
                    url: '<?php echo admin_url('admin-ajax.php') ?>',
                  type: 'POST',
                  data: {post_id: post_id, voteto:voteTo, action: 'send_quit_vote'},
                  dataType: 'json',

                  success: function (data)
                  {
                      console.log(data);
                      const greys = document.querySelectorAll(".grey");
                        var totalVotes=0;
                    for (var k in data){
                        if (typeof data[k] !== 'function') {
                            // alert("Key is " + k + ", value is" + data[k]);
                            totalVotes+=data[k];
                        }
                    }
                    // alert("totalVotes"+totalVotes);
                    for (const grey of greys) {
                    	 var classList = grey.className.split(' ');
                        console.log({classList});
                        var greyVote = classList[1];
                        grey.innerHTML = "";
                        votes = 0;
                        if(typeof data[greyVote] !== 'undefined') {
                            votes = data[greyVote];
                            
                        }

                        console.log({votes});

                        percent = votes*100/totalVotes;
                        grey.innerHTML="<div style='height:"+percent+"%' class="blue"></div>";
                        


                       // if(grey.)
                    }

                  }

        });
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
                            <p id="<?php echo $key;?>"><span class="rating_label"><?php echo $name?></span></p>
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
                                        <?php echo $compareClass->get_column_features($item,'');?>
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
                <p><strong>Here is a summary of a few <?php echo $post->post_title;?> competitors to help you get started:</strong></p><br>
                <table class="table">
                    <thead><tr><th>Name</th><th>Best For</th><th>Pricing Model</th><th>Price</th></tr></thead>
                    <tbody>
                    <?php foreach ($tableArr as $item => $name){

                        $pricing_model = get_field( 'pricing_model', $item );
                            if(!empty(get_field( 'price_starting_from', $item ))){
                            $pricing_start1 = get_field( 'price_starting_from', $item );
                            $pricing_start =(float) filter_var($pricing_start1, FILTER_SANITIZE_NUMBER_INT);
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
        <!--     <div class="rating_headings">
                <?php
                foreach ($ratingLabel as $key => $name){
                    echo "<div class='rat_head'><a href='#$key'>$name</a> </div>";
                }
                ?>
            </div> -->
        </div>
    </div>	
	
</div>
<?php
get_footer();
?>