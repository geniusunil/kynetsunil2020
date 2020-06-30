<?php
//file_put_contents("debug.txt","at the start of file",FILE_APPEND);
wpb_track_post_views();
get_header();

$list_setting = get_option( 'mv_list_items_settings' );
$target_countries = $list_setting['list_page_target_countries'];
// print_r($target_countries);
$countryPair = explode(',',$target_countries);
// print_r($countryPair);
foreach($countryPair as $cp){
    $pair = explode('=>',$cp);
    foreach($pair as $key=>$value){
        $pair[$key]=trim(trim($value),"'");
    }
    $targetCountries[$pair[0]] = $pair[1];
}
// echo "test";
// print_r($targetCountries);

// Start the loop.
    while (have_posts()): the_post();
    
    $list_id = $post_id = get_the_ID();

    $main_list_id = $list_id;

    $dp = get_data_points_list($post_id);
   
    $main_list_permalink = get_the_permalink($list_id);
    $finalVotes = do_shortcode("[total_votes id=$main_list_id]");
    add_datapoints_databse($main_list_id,'list',$dp,$finalVotes);
    $catobj = get_the_terms($post_id, 'list_categories');
// var_dump($catobj );
	



    $category_names = '';
    if ($catobj && !is_wp_error($catobj)) {
        $categories = $catobj;
        if ($categories) {
            foreach ($categories as $cat) {
              
                $category_names .= '<span class="list-cat-name"><a href="' . esc_url(get_term_link($cat->term_id)) . '" title="' . $cat->name . '">' . $cat->name . '</a></span>&nbsp;';
				$_SESSION['category_faq'] = $cat->name;
                file_put_contents("single_list.txt", $category_names,FILE_APPEND);
                // $queried_object = get_queried_object(); 
                // var_dump($queried_object);
                
               
            }
        }
    }


    // echo $_SERVER['REQUEST_URI'];
    $langPos = strrpos($_SERVER['REQUEST_URI'],"?lang=");
    if($langPos == false)
        {
            $isoCode = do_shortcode('[geoip_detect2 property="country.isoCode"]');
        // echo $isoCode;
        // print_r($this->targetCountries);
        $country = do_shortcode('[geoip_detect2 property="country.name"]');
        }
        else{ //get it from the url
            $isoCode = substr($_SERVER['REQUEST_URI'],$langPos+6,2);
            // echo "isocode is $isoCode";
            $country = get_country_name($isoCode);
        }
        
    
        $title = get_the_title();
        $origTitle = $title;
        $_SESSION['lang']="none";
        ?>
        <script>
            var isoCode = "none";
        </script>
        <?php
        if(array_key_exists($isoCode,$targetCountries)){
            $title .=  " in $country";
            $_SESSION['lang']=$isoCode;
            ?>

            <script>
            isoCode = '<?php echo $isoCode?>';
            //get existing args from url
			// console.log("current url from footer : "+window.location.href);
			var url = window.location.href;
			var queryStart = url.indexOf("?") + 1,
        queryEnd   = url.indexOf("#") + 1 || url.length + 1,
        query = url.slice(queryStart, queryEnd - 1),
        pairs = query.replace(/\+/g, " ").split("&"),
        parms = {}, i, n, v, nv;

            if (query === url || query === ""){
                parms = null;
            }
        
            else{
                for (i = 0; i < pairs.length; i++) {
                    nv = pairs[i].split("=", 2);
                    n = decodeURIComponent(nv[0]);
                    v = decodeURIComponent(nv[1]);

                    if (!parms.hasOwnProperty(n)) parms[n] = [];
                    parms[n].push(nv.length === 2 ? v : null);
                }
            }  
                var pushurl = "?";
                if(parms != null){
                    pushurl += "sort=" + parms.sort[0] +"&lang=<?php echo $isoCode ?>#filter";
                }
                else{
                    pushurl="?lang=<?php echo $isoCode ?>";
                }
                history.replaceState({name: 'Location'}, "pushState example", pushurl);
            document.title = "<?php echo $title ?> | SoftwareFindr";
            jQuery('meta[property="og:title"]').attr("content","<?php echo $title ?> | SoftwareFindr");
            </script>
        <?php
        }
    
    ?>

	    <div class="single-list-data zf-item-vote" >
            <div class="container-fluid mainslider">
	            <div class="container">
	                <div class="row" style="overflow: visible;">
	                    <div class="col-md-12">
	                        <p class="slidersubtop"><?php echo $category_names; ?></p>
	                        <h1 class="sliderheader">
	                            <?php    
                                
                                    echo $title;
                                    
                                 ?>
	                        </h1>
	                        <p class="slidertext">
	                            A comprehensive list of <?php the_title();?> according to <?php echo $finalVotes; ?> users.
	                            <br>
	                            With <?php echo do_shortcode('[list_number]'); ?> options to consider you are sure to find the right one for you.
	                        </p>
	                    </div>
	                    <div class="col-md-12">
	                        <ul class="contributers">
	                            <li>
	                                <img class="reuse" src="<?php echo image_path_single('images/reuseicon.jpg'); ?>">
	                            </li>
	                            <li class="bold">
	                                <?php echo get_post_modified_time('j F Y'); ?>
	                            </li>
	                            <li class="sep">
	                                /
	                            </li>
	                            <li class="contimage">
	                                <img src="<?php echo image_path_single('images/contributers.jpg'); ?>">
	                            </li>
	                            <li class="contnumbs">
	                                <?php echo $finalVotes; ?> Contributors
	                            </li>

                                <li class="region-label">
                                Select Country
    </li>
    <li class="region">
                              
                    <div class="options" data-input-name="country"></div>
          
    
                                    </li>
	                        </ul>
	                    </div>

	                </div>
	            </div>
	            <div class="container-fluid featured">

	            <div class="container2">
	            <?php
                // wp_infinitepaginate();
                $_SESSION['gen_list'][$list_id]=$generated_list = generate_list($list_id);
                
                // print_r($generated_list);
                $posts = get_field('list_items',$list_id);

                $i=0;
                $item_feature_lists = array();
                foreach($posts as $post_object){  
                    $post_id = $post_object->ID; 
                    $this_features = get_field('features_list', $post_id);     
                    if(is_array($this_features)){
                        $item_feature_lists = array_unique(array_merge($item_feature_lists,$this_features));
                        $features_ids['id'.$i] = $post_id;
                    }
                    $i++;
                }

                $sorted_feature_list = sort_features($item_feature_lists, $features_ids);
           /*      echo "sorted feature list";
                print_r($sorted_feature_list); */
                $top3features = array();
                $j=0;
                foreach($sorted_feature_list as $sfl){
                    $top3features[]=$sfl['feature'];
                    if($j>=2){
                        break;
                    }
                    $j++;
                    
                }
   /*              echo "top3 features";
                print_r($top3features); */
                $_SESSION['top3features']=$top3features;
                
                
                
              
    // print_r($generated_list);

    foreach($generated_list[''] as $key=>$value){
        $bestoverall = $key;
    break;
    }
    // echo $bestoverall;
    foreach($generated_list['user_friendly'] as $key=>$value){
        $bestcustomerservice = $key;
    break;
    }
    foreach($generated_list['affordable_price'] as $key=>$value){
        $bestbudgetoption = $key;
    break;
    }
    foreach($generated_list['free'] as $key=>$value){
        $bestoverallfree = $key;
    break;
    }
    $list_promoted =  get_field('promoted_list_items',$list_id);
    $bestPromotedRank = count($generated_list[''])+1;
    /* echo "gen list dash";
    print_r($generated_list['']);
    echo "promoted list";
    print_r($list_promoted); */
    foreach($list_promoted as $item){
        if($generated_list[''][$item]['rank'] < $bestPromotedRank){
            $bestPromoted = $item;
            $bestPromotedRank =$generated_list[''][$item]['rank'];
        }
        
    }
    if(!is_array($list_promoted) || $list_promoted == null){
        $bestPromoted = null;
    }
    /* echo "promoted items";
    print_r($list_promoted); */
  
    $all5features = array($bestPromoted, $bestoverall, $bestcustomerservice, $bestbudgetoption, $bestoverallfree);
    // print_r($all5features);
    ?>
	                <div class="row">

<?php

                

?>
                
	                <?php
                    $widthClass = "col-3";
    $i = 0;
    // $all5featuresexpanded = array();
    foreach ($all5features as $feature) {
        // echo "feature $feature";
        if($feature!==null){
        if ($i == 0) {
            $featuretitle = "Promoted";
        }
        elseif ($i == 1) {
            $featuretitle = "Best Overall";
        } elseif ($i == 2) {
        $featuretitle = "Best Customer service";

    } elseif ($i == 3) {
        $featuretitle = "Best Budget Option";

    } else {
        $featuretitle = "Best Free Option";

    }
    $img = get_the_post_thumbnail($feature, 'medium', array('itemprop' => 'image'));
    $title = get_the_title($feature);
    $overallscore = 0;
    $findrScore = 0;
    $reviews = get_overall_combined_rating($feature);
    if(isset($reviews['list']['overallrating']['score'])){
        $overallscore=$reviews['list']['overallrating']['score'];
        $findrScore += $reviews['list']['featuresfunctionality']['score'] * 3;
        $findrScore += $reviews['list']['easeofuse']['score'] * 2;
        $findrScore += $reviews['list']['customersupport']['score'] * 2;
        $findrScore += $reviews['list']['valueformoney']['score'] * 3;
    }
    
    // print_r($reviews);
    // $i=0;
    
   
    
    // echo "fs before rank".$findrScore;
    
    $findrScore += 50/($generated_list[''][$feature]['rank']);
    // echo "rank ".$generated_list[''][$feature]['rank'];
    // echo "fs after rank".$findrScore;
    $findrScore = round($findrScore);

    $permalink = get_the_permalink($feature);
    //cta
    $affiliate_url = get_field('affiliate_url', $feature);

    $availability = get_post_meta($feature, '_item_availbility', true);

    if (!empty($affiliate_url)) {
        $comaprd = $_SERVER['REQUEST_URI'];
        $comaprd = str_replace('compare/', '', $comaprd);
        $comaprd = str_replace('/', '', $comaprd);

        if (substr_count($affiliate_url, "?") >= 1) {
            $affiliate_url .= "&utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=" . $comaprd;
        } else {
            $affiliate_url .= "?utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=" . $comaprd;
        }

    }
    $affiliate_button_text = get_field('affiliate_button_text', $feature) == '' ? 'Download/Demo' : get_field('affiliate_button_text', $feature);
    $source_url = get_field('source_url', $feature);
    // echo "post_id".$feature;

    // echo "source_url".$source_url;
    $credit_text = get_field('credit', $feature);

    $afflink = empty($affiliate_url) ? $source_url : $affiliate_url;
    // echo "afflink".$afflink;
    $btntext = empty($affiliate_button_text) ? $credit_text : $affiliate_button_text;
    ?>
        <?php if ($availability == 'no') {
        $cta = '<a href="' . $permalink . 'alternative/" class="alter-btn aff-link" data-parameter="itemid" data-id="' . $feature . '" >Alernative</a>';
    } else {
        $cta = '<a class="mes-lc-li-down aff-link" href="' . $afflink . '" rel="nofollow" target="_blank">' . $btntext . '</a>';
    }
    if ($i == 1) {
        $customClass = "overall";
    } else {
        $customClass = "";
    }
    
    
        if($i==0){
            $widthClass = "col-2_4";
        }
        
        echo '<div class="'.$widthClass.'">
                    <div class="wrapper ' . $customClass . '">
                        <div class="featured-title ' . $customClass . '">' . $featuretitle . '</div>
                        <div class="featured-image">' . $img . '</div>
                        <div class="featured-text1"><a href="' . $permalink . '">' . $title . '</a></div>
                        <hr>
                        <div class="featured-text2">findrScore : ' . $findrScore . '</div>
                        <div class="featured-text3">' . $cta . '</div>
                        <div class="featured-text4"><a href="' . $permalink . '">Read Review</a></div>
                    </div>
                </div>';
    
}
    $i++;
    /* $thisfeature = [$featuretitle, $img, $permalink, $title, $findrScore, $cta];
    $all5featuresexpanded[] = $thisfeature; */
}


?>

        </div><!-- end of row -->

        </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 msanim">
                                <a href="#bargraph"> <img src="<?php echo image_path_single('images/mousescroll.jpg'); ?>"></a>
                    </div>
            </div></div>
        </div>

        <div class="container-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 partners">
                        <img src="<?php echo image_path_single('images/partnerstrip.png'); ?>">
                    </div>
                </div>
            </div>
        </div>

       <?php 
       remove_filter('the_content', 'wpautop');
 the_content();
add_filter('the_content', 'wpautop');






// <!-- /********************************************************FAQ Start*********************************************** */  -->
function get_list_faq()
{
    
/****************************************************** * faq-1 start **************************************************/

    $faq1 = do_shortcode("[list_title sop='singular']");//get_the_title();
    if($faq1 == ''){
        $faq1_best = get_the_title();
    }
    else{
        $faq1_best = 'best '.$faq1;
    }
    $faq2 = do_shortcode("[list_title sop='plural']");
    $free_software_id =  $GLOBALS['bestoverallfree'];
    $free_software_title = get_the_title($free_software_id );
    $faq1_ans ="Our users have voted that $free_software_title is the preferred free $faq1.";
    /********************************************************* faq-1 END ***************************************************/
    /********************************************************* faq-2 Start ***************************************************/
    // $all_list_overall =  $GLOBALS['bestoverall'];
    $list_id = get_the_ID();
    
    // print_r($generated_list);
  
// print_r($generated_list);

/* foreach($_SESSION['gen_list'][$list_id][''] as $key=>$value){
    $bestoverall = $key;
    break;
} */   
    $bestoverall = $GLOBALS['bestoverall'];
    $overall_software_title = get_the_title($bestoverall );
    $faq_Cat = $_SESSION['category_faq'];
    $list_id = get_the_id();
    $overall_software_votes = get_post_meta($bestoverall, 'votes_given', true);
    if(isset($overall_software_votes[$list_id])){
        $overall_software_votes = $overall_software_votes[$list_id];
    }else{
        $overall_software_votes=0;
    }
    
    

    /********************************************************* faq-6 & 7Start ***************************************************/
    

    $allEOU = array();
    $posts = get_field('list_items',$list_id);

    $post_id_count = count($posts);
    // print_r($posts);

    $total_votes=0;
    $item_rating_feature = array();
    foreach($posts as $post_object){    
        
        $post_id = $post_object->ID; 
        $this_votes = get_post_meta($post_id, 'votes_given', true);
        if(isset($this_votes[$list_id])){
            $total_votes += $this_votes[$list_id];
        }
        
       /*  echo "total votes";
        print_r($total_votes);


        echo "soting123"; 
        echo $post_id;  */ 
        $rating = get_overall_combined_rating($post_id);
        if(isset($rating['list']['easeofuse']['score'])){
            $easeofuse = $rating['list']['easeofuse']['score'];  
        }
          
        $allEOU[$post_id]= $easeofuse; 
        $pricing= get_field('pricing_model',$post_id);

        $item_rating_feature[$post_id] = get_field( 'features_list_ratings', $post_id );

        $average[] = get_field('price_starting_from',$post_id); 
        $plan_field[$post_id] = get_field('plan' ,$post_id);
            foreach($pricing as $price){
                $total_model[] = $price;   
                }
      
    
        }
     /*    echo "overall sf votes";
        echo $overall_software_votes;
        echo "total_votes";
        echo $total_votes; */
        $overall_software_votes_percentage = round($overall_software_votes*100/$total_votes,2);
        
        // $item_rating_feature == $item1;
        
        // print_r($item_rating_feature);


    
        


        // $i=0;
        $topfeature_avg = array();
        foreach($item_rating_feature as $key=> $item_key_feature_rat){  
            if(is_array($item_key_feature_rat)){
                foreach($item_key_feature_rat as $key2 =>$item_feature){
            
                    if($item_key_feature_rat[$key2]['average'] != 0 ){
                        if(!isset($topfeature_avg[$key2])){
                            $topfeature_avg[$key2] = array();
                        }
                        $topfeature_avg[$key2] []= round($item_key_feature_rat[$key2]['average'],2);
    
                    }
    
                }
            }
           
        }
    $topfeature_avg_single = array();
    foreach($topfeature_avg as $key => $array){

        $topfeature_avg_single [$key]= array_sum($array)/count($array);


    }
    
   arsort($topfeature_avg_single );
// echo "topfeature_avg_single";
//    print_r($topfeature_avg_single);



      
       
    $plan_field_result = array_filter($plan_field);
    // print_r($plan_field_result) ;
    $count_value_plan = array_count_values($plan_field_result);
  
    
    foreach($count_value_plan as $key=> $plan_field_res)
    { 
        $plan_field_total = $key;
        break;
       
        // echo $plan_field_res;
        // $total_plan = round($plan_field_res / $post_id_count ,2);
       
    }
    // print_r($plan_field);
    // echo $plan_field_total;



        $result = array_filter($average);

        $sum = 0;  
        foreach($result as $res)
        {  
        $price_value = floatval($res);//trim($res , '$');
        $sum +=  $price_value;
        $total_price = round($sum / $post_id_count ,2);
        }

        $count_value_model = array_count_values($total_model);
        arsort($count_value_model);
        $model_post_id = 0;
        foreach($count_value_model as $key=> $result)
        {
            $model_post_result = $key;
            $model_post_id = $result;
            break;

        }
        arsort($allEOU);
        $highesteou = 0;
        foreach($allEOU as $key => $score){   
            $highesteou = $key;
        break;
        }
    $list_title = get_the_title($highesteou);
    $faq_ans6 = "The provider with the best score for ease of use is $list_title.";

/********************************************************* faq-6 & 7END ***************************************************/

/******************************************************** faq- top location **************************************************/
//  print_r($posts);        
    global $targetCountries;

        $downloads_in_countries = array();
        foreach($targetCountries as $key=>$value){
            
        $downloads_in_countries[$value] = get_post_meta( $list_id, "downloads_in_".$key, true );
        }
        arsort($downloads_in_countries);
        $top_loc = 0;
        foreach($downloads_in_countries as $key =>$loc){
            $top_loc_count = $loc;
            $top_loc_key = $key;
           
        break;
        }

        foreach($posts as $post_object){ 
            $post_id = $post_object->ID;   
            $down_item[$post_id] = get_post_meta($post_id,'downloads_in_', true);    
            // echo $down_item;
        }
      arsort($down_item);
      $download_item=0;
    foreach($down_item as $key=> $down_contry){
    $download_item =  $key;
    break;
    }
 
    $down_max_item = get_the_title($download_item);


/******************************************************** faq top location **************************************************/


/********************************************************* faq-2 END ***************************************************/
$faq = ' <div itemscope itemtype="https://schema.org/FAQPage">';
$qalist  = get_post_meta($list_id, 'qas_list', true);
// print_r($qalist);
if(is_array($qalist)){
    foreach($qalist as $faq_ls){
        $custfaq = "<div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <b itemprop='name'> Q. ".$faq_ls['question']."</b>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans.".$faq_ls['answer']."
                            </div>
                        </div>
                    </div>";
        $faq.=$custfaq;

        
    }
}
    



          $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <b itemprop='name'> Q.  What is the best free ".$faq1."?</b>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. $faq1_ans
                            </div>
                        </div>
                    </div>
          ";
        $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <b itemprop='name'> Q.  Which is commonly used for ".$_SESSION['top3features'][0]." ? </b>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans.Over $overall_software_votes_percentage% over users surveyed recommended $overall_software_title.     </div>
                        </div>
                    </div>
          "; 
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <b itemprop='name'> Q.  How much does a $faq1 cost? </b>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. Looking at the data collected on our platform the average starting price is $total_price USD billed per $plan_field_total as a  $model_post_result.
                             </div>
                        </div>
                    </div>
          ";

        foreach($_SESSION['top3features'] as $t3f){
            foreach($_SESSION['gen_list'][$list_id][''] as $key=>$value){
                $this_features = get_field('features_list', $key);  
                if(is_array($this_features)){
                    if(in_array($t3f,$this_features)){
                        $topProductWithFeature = $key;
                        break;
                    }
                    
                }
               
                
            }   
            
            $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
            <b itemprop='name'> Q.  What is the ".$faq1_best." with $t3f? </b>
            <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                <div itemprop='text'>
                    Ans. The results are in and over a ".$GLOBALS['finalVotes']." as cast their votes and ".get_the_title($topProductWithFeature)." is the recommended choice.
                 </div>
            </div>
        </div>
"; 

        }
       


          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <b itemprop='name'> Q.  What is the ".$faq1." in $top_loc_key? </b>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. After analyzing the data from users in  $top_loc_key, the result show that $down_max_item  is the popular choice locally.                               
                            </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <b itemprop='name'> Q.  Which are the best ".$faq1." for a beginner? </b>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. $faq_ans6
                            </div>
                        </div>
                    </div>
          ";
//           $item1_id = '126168';
// $item2_id = '126170';
$sorts='';
if(isset($_GET['sort'])){
    $sorts = $_GET['sort'];
}

$compareitems= array();
foreach($_SESSION['gen_list'][$list_id][$sorts] as $key=>$value){
    $compareitems[]=$key;
    if(count($compareitems)>1)
        break;
    
    
} 
$this_features1 = get_field('features_list', $compareitems[0]);  
$this_features2 = get_field('features_list', $compareitems[1]);  
foreach($_SESSION['top3features'] as $t3f){
        $item1 = get_the_title($compareitems[0]);
        $item2 = get_the_title($compareitems[1]);
       
        $one=false;
        $two=false;
        if(is_array($this_features1)){
            if(in_array($t3f,$this_features1)){
                $one = true;
                
            }
        }
        
        if(is_array($this_features2)){
            if(in_array($t3f,$this_features2)){
                $two = true;   
            }
        }
        if($one && $two){
            $ans6 = "Both $item1 and $item2 have that feature.";  
        }
        elseif($one && !$two){
            $ans6 = "$item1 has it however there is no mention that $item2 has it, double check their site to see if this as changed. ";
        }
        elseif($two && !$one){
            $ans6 = "$item2 has it however there is no mention that $item1 has it, double check their site to see if this as changed. ";
        }
        else{
            $ans6 = "None of the items have this feature.";
        }
        
        
      
    
    $faq.= " <div  itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <b itemprop='name'> Q.   Does ".$item1."  or ".$item2." have  ".$t3f."?
                        </b>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. $ans6
                            </div>
                        </div>
                    </div>
          ";

}
       
    
          
          $faq .= "</div>";
             return $faq;

}
$faqs = '<div class="item-overview-content"><div id="overview"> <h4>Frequently Asked Questions</h4> <div><span itemprop="description">'.get_list_faq().'</span>
 </div>
</div></div>
';
?>
<div class="container">
            <div class="list-main-content left-content-box">
                 <?php  echo $faqs; ?>    
            </div>
            </div></div></div>
<!-- /********************************************************FAQ End*********************************************** */  -->
        <div class="container-fluid sectionexplore" id="yarrp_container" data-id="<?php echo $main_list_id; ?>" >
 <!-- /**********************************************FAQ cALL*********************************** */ -->
 
            
<!-- /******************************************faq end********************************************** */ -->
<div class="full_width" data-id="<?php echo $main_list_id; ?>">      
            <?php yarpp_related(array('post_type' => 'lists', 'p' => $main_list_id), $main_list_id);?>
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-md-6">
                        <div class="fbcomments">
                            <div id="social_fb_comments" class="link">
                                <button class="toggle">
                                    <i class="fa fa-commenting-o" aria-hidden="true"></i>
                                    <a href="#" class="fb-comments-text txt crux-label-style">Questions & answers</a>
                                    <i class="fa fa-chevron-up" aria-hidden="true"></i>

                                </button>
                                <div class="fb-comments fb_iframe_widget fb_iframe_widget_fluid">
                                    <?php comments_template('/commentslist.php', true);?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           

</div>
        </div>
    </div>
<?php endwhile;?>







<?php


function get_country_name($isoCode){
    global $targetCountries;
    $country = '';
    $country = $targetCountries[$isoCode];
    // echo "targetCountries[isoCode] $targetCountries[$isoCode]";
    return $country;
}
get_footer();

?>
<script>
 
    jQuery('.options').flagStrap({
        countries: <?php echo json_encode($targetCountries) ?>,
        buttonSize: "btn-sm",
        buttonType: "btn-info",
        labelMargin: "10px",
        scrollable: false,
        scrollableHeight: "350px"
    });
    
    var allOptions=jQuery('.options a');
    // console.log("alloptions");
    // console.log(allOptions.length);
    for(var j=0;j<allOptions.length;j++){
        var current = allOptions[j];
        var newC=current.getAttribute("data-val");
        // var newCname=current.text();    
        
        //get existing args from url

			// console.log("current url from footer : "+window.location.href);
			var url = window.location.href;
			var queryStart = url.indexOf("?") + 1,
        queryEnd   = url.indexOf("#") + 1 || url.length + 1,
        query = url.slice(queryStart, queryEnd - 1),
        pairs = query.replace(/\+/g, " ").split("&"),
        parms = {}, i, n, v, nv;

            if (query === url || query === ""){
                parms = null;
            }
        
            else{
                for (i = 0; i < pairs.length; i++) {
                    nv = pairs[i].split("=", 2);
                    n = decodeURIComponent(nv[0]);
                    v = decodeURIComponent(nv[1]);

                    if (!parms.hasOwnProperty(n)) parms[n] = [];
                    parms[n].push(nv.length === 2 ? v : null);
                }
            }
  
	// console.log(parms);
		// end of get  existing args from url 
                // history.replaceState(null, null, "?lang=<?php //echo $isoCode ?>/");
                var pushurl = "?";
                if(parms.sort != null){
                    pushurl += "sort=" + parms.sort[0] +"&lang="+newC;
                }
                else{
                    pushurl="?lang="+newC;
                }
        
        // var url = window.location.href;
        // var current = allOptions[i];
        var baseurl = url.substr(0,url.indexOf("?"));
        if(newC != ''){
            current.setAttribute("href",baseurl+pushurl);
        }
        current.style.textDecoration = "none";
        // console.log(allOptions[i]);
    }
    jQuery('.options a').on('click', function() {
      
        window.open(jQuery(this).attr("href"),"_self");
        
    });

</script>
<style>
    .status{
        text-align:center;
    }
    .col-2_4{
        width:20%;
        padding:0 10px;
    }
</style>

