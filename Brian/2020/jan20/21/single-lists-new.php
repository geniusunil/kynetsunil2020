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
// print_r($targetCountries);
/*  $targetCountries=array(
    'IN' => 'India',
    'US' => 'United States',
    'GB' => 'United Kingdom',
    'AU' => 'Australia',
    'BR' => 'Brazil',
    'CA' => 'Canada',
    'DE' => 'Germany',
    'FR' => 'France',
    'IT' => 'Italy',
    'ID' => 'Indonesia',
    'PK' => 'Pakistan',
    'PL' => 'Poland',
    'BD' => 'Bangladesh',
    'ES' => 'Spain',	
    'NL' => 'Netherlands'); */ // array of countries for which we want to show modified lists and change url attribute ?lang=IN

// Start the loop.
    while (have_posts()): the_post();
    
    $list_id = $post_id = get_the_ID();

    $main_list_id = $list_id;
    $main_list_permalink = get_the_permalink($list_id);
    $finalVotes = do_shortcode("[total_votes id=$main_list_id]");
    $catobj = get_the_terms($post_id, 'list_categories');
// var_dump($catobj );
	



    $category_names = '';
    if ($catobj && !is_wp_error($catobj)) {
        $categories = $catobj;
        if ($categories) {
            foreach ($categories as $cat) {
              
                $category_names .= '<span class="list-cat-name"><a href="' . esc_url(get_term_link($cat->term_id)) . '" title="' . $cat->name . '">' . $cat->name . '</a></span>&nbsp;';
				$GLOBALS['category_faq'] = $cat->name;
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
  
	console.log(parms);
		// end of get  existing args from url 
                // history.replaceState(null, null, "?lang=<?php //echo $isoCode ?>/");
                var pushurl = "?";
                if(parms != null){
                    pushurl += "sort=" + parms.sort[0] +"&lang=<?php echo $isoCode ?>#filter";
                }
                else{
                    pushurl="?lang=<?php echo $isoCode ?>";
                }
                console.log("singlelistsnew first");
                history.replaceState({name: 'Location'}, "pushState example", pushurl);
                 // if (document.title != newTitle) {
            document.title = "<?php echo $title ?> | SoftwareFindr";
            jQuery('meta[property="og:title"]').attr("content","<?php echo $title ?> | SoftwareFindr");
            // }
                
                    // console.log(jQuery('.sliderheader'));
                    // jQuery('.sliderheader').text = "<?php //echo $title ?>";
                console.log(history.state);

            </script>
        <?php
        }
       
       
    ?>

	    <div class="single-list-data">
	        <div class="container-fluid mainslider">

	            <div class="container">
	                <div class="row" style="
    overflow: visible;
">
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
                              
                    <div id="options" data-input-name="country"></div>
          
    
                                    </li>
	                        </ul>
	                    </div>

	                </div>
	            </div>
	            <div class="container-fluid featured">

	            <div class="container2">
	            <?php
    $attached_items = get_field('list_items', $list_id, true);
    // print_r($attached_items);
    // echo 'hellow';//.$attached_items[0]['ID']
    // echo $attached_items[0]->ID;
    $att_items_id_only = array();
    foreach ($attached_items as $wppostobj) {
        $att_items_id_only[] = $wppostobj->ID;
    }
    // print_r($att_items_id_only);
    $GLOBALS['bestoverall'] = $bestcustomerservice = $bestbudgetoption = $att_items_id_only[0];
    $bestoverallscore = $bestcustomerservicescore = $bestbudgetoptionscore = $bestoverallfreescore = 0;
    $GLOBALS['bestoverallfree'] = "no free option.";
    // $bestoverallfreescore = 5;
    foreach ($att_items_id_only as $product) {
        $reviews = get_overall_combined_rating($product);
        // $overall = $reviews['list']['overallrating']['score'];
        $customerservice = $reviews['list']['customersupport']['score'];
        // $budgetoption = $reviews['list']['valueformoney']['score'];
        // print_r($reviews);
        // echo "product : $product overall customerservice budget option : $overall $customerservice $budgetoption";
        // echo "product : ".get_the_title($product) . " customerservicescore : $customerservice";
        /* if($bestoverallscore < $overall){
        $bestoverallscore = $overall;
        $bestoverall = $product;
        $price_starting_from = get_field( 'price_starting_from', $product);
        if($price_starting_from == "" || $price_starting_from=="$0"){
        $bestoverallfreescore = $overall;
        $bestoverallfree = $product;

        }
        } */
        if ($bestcustomerservicescore < $customerservice) {
            $bestcustomerservicescore = $customerservice;
            $bestcustomerservice = $product;
        }
        /* if($bestbudgetoptionscore < $budgetoption){
    $bestbudgetoptionscore = $budgetoption;
    $bestbudgetoption = $product;
    } */

    }
    // file_put_contents("debug.txt","before function",FILE_APPEND);

    // echo "got list1";//get_list_html($list_id);
    // $cont = get_list_html($list_id);

    // file_put_contents("debug.txt","at the start of function",FILE_APPEND);

    // get_list_html
    global $wp;
    $index = 1;
    $pageID = $list_id;
    $main_list_id = $list_id;
    // $main_list_permalink = get_the_permalink( $list_id );
    // $attached_items      = get_field( 'list_items', $list_id, true );
    // print_r($attached_items);
    // $promoted_list_items = get_field('promoted_list_items', $list_id, true);
    // $items_per_page      = get_field( 'items_per_page', $list_id );
    $items_per_page = 10;
    // $voting_closed       = get_field( 'voting_closed', $list_id );
    $current_page = 1;
    // $total_pages         = ceil( count( $attached_items )/$items_per_page );

    //$items_by_votes = '';

    foreach ($attached_items as $key => $child_post) {
        $total_votes = get_post_meta($child_post->ID, 'votes_given', true);
        if (!isset($total_votes[$main_list_id])) {
            $total_votes[$main_list_id] = 0;
            $attached_items[$key]->votes = 0;
        } else {
            $attached_items[$key]->votes = $total_votes[$main_list_id];
        }

    }

    $index = (($current_page - 1) * $items_per_page) + 1;

    //usort( $attached_items, array( $this, "cmp" ) );

    Mes_Lc_Ext::sort($list_id, $attached_items);
    //Mes_Lc_Ext::sortfree($list_id, $postarr);

    $itmes_with_promoted = $attached_items;

    $temp_array = array();

    // make promoted items at top
    if (!empty($promoted_list_items)) {
        foreach ($attached_items as $key => $item) {
            if (in_array($item->ID, $promoted_list_items)) {
                unset($itmes_with_promoted[$key]);
                $item->promoted = 1;
                $temp_array[] = $item;
            }

        }
    }
    $attached_items = array_merge($temp_array, $itmes_with_promoted);
    // echo "attached items ";
    // print_r($attached_items);
    Mes_Lc_Ext::sortfree($list_id, $attached_items);
    //krsort($items_by_votes);
    $posts = array_slice($attached_items, ($current_page - 1) * $items_per_page, $items_per_page);
    /*echo '<pre>';
    print_r($posts);
    echo '</pre>';  */

    $totalVotes = 0;
    $totalActVOtes = 0;
    $postarr = array();
    $postarr1 = array();
    $postarrOBJ = array();
    if (!empty($posts)) {
        // echo print_r($posts);
        foreach ($posts as $key => $list_post) {
            //$totalVotes += $list_post->votes;
            if ($list_post->votes > 0) {
                // $list_post->votes = $list_post->votes*2.27;
                $totalActVOtes += $list_post->votes;
                $totalVotes += Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);

            }
        }
    }
    if (isset($_GET['sort'])) {
        $sorts = $_GET['sort'];
    }
    $a = array();

    //overall
    $postarr = array();
    $postarr1 = array();
    if (!empty($posts)) {
        foreach ($posts as $key => $list_post) {
            //$totalVotes += $list_post->votes;
            //            if ($list_post->votes > 0) {

            $score = Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);
            $score = ($score / $totalVotes) * 100;
            $score = number_format($score, 2);
            $pricemodel12 = get_field('pricing_model', $list_post->ID);
            $freetrial = get_field('free_trial', $list_post->ID);
            if (empty($pricemodel12)) {
                $pricemodel12 = array();
            }
            if (in_array('open_source', $pricemodel12) || in_array('freemium', $pricemodel12) || $freetrial == 1) {
                $free_items_array[] = "free";
            }
////

            //recommended overall
            $postarr[$list_post->ID] = $score;
            // arsort($postarr);

////

//            }
        }
    }
////

    arsort($postarr);

    //end of overall
    /* echo "overall\n";
    print_r($postarr); */
    $key = $value = null;
    foreach ($postarr as $key => $value) {
        break;
    }

    // echo "$key = $value\n";
    $bestoverall = $key;

////
    /*     $maxsScore = array_keys($postarr, max($postarr));
    $maxsScorePost = $maxsScore[0]; */
////
    // budget
    $postarr = array();
    $postarr1 = array();
    if (!empty($posts)) {
        foreach ($posts as $key => $list_post) {
            //$totalVotes += $list_post->votes;
            //            if ($list_post->votes > 0) {

            $score = Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);
            $score = ($score / $totalVotes) * 100;
            $score = number_format($score, 2);
            $pricemodel12 = get_field('pricing_model', $list_post->ID);
            $freetrial = get_field('free_trial', $list_post->ID);
            if (empty($pricemodel12)) {
                $pricemodel12 = array();
            }
            if (in_array('open_source', $pricemodel12) || in_array('freemium', $pricemodel12) || $freetrial == 1) {
                $free_items_array[] = "free";
            }
////

            //affordable
            $aa = $score;
            $price_starting_from = get_field('price_starting_from', $list_post->ID);
            $price_starting_from = str_replace("$", "", $price_starting_from);
            $postarr[$list_post->ID] = $price_starting_from;

            $counts = array_count_values($postarr);
            $filtered = array_filter($postarr, function ($value) use ($counts) {
                return $counts[$value] > 1;
            });

            if (count($filtered) >= 1) {
                foreach ($postarr as $key => $value) {
                    foreach ($filtered as $key1 => $value1) {
                        if ($key == $key1 && $value == $value1) {
                            unset($postarr[$key]);
                            $postarr1[$key] = $aa;
                            $postarr11[$key] = $value;
                            break;
                        }
                    }
                }

            }
/* asort($postarr);

if(isset($postarr1)){
arsort($postarr1);
foreach($postarr1 as $key => $value) {
foreach($postarr11 as $key1 => $value1) {
if($key == $key1){
$postarr1[$key] = $value1;
break;
}
}
}
$postarr = $postarr + $postarr1;
asort($postarr);
}

// */

////
            // $postarrOBJ[$list_post->ID] = $list_post;

//            }
        }
    }
////

    asort($postarr);

    if (isset($postarr1)) {
        arsort($postarr1);
        foreach ($postarr1 as $key => $value) {
            foreach ($postarr11 as $key1 => $value1) {
                if ($key == $key1) {
                    $postarr1[$key] = $value1;
                    break;
                }
            }
        }
        $postarr = $postarr + $postarr1;

        asort($postarr);
        /* echo "budget\n";
    print_r($postarr); */
    }
    $key = $value = null;
    foreach ($postarr as $key => $value) {
        break;
    }

    // echo "$key = $value\n";
    $bestbudgetoption = $key;

//

    //end of budget

// free
    $postarr = array();
    $postarr1 = array();
    if (!empty($posts)) {
        foreach ($posts as $key => $list_post) {
            //$totalVotes += $list_post->votes;
            //            if ($list_post->votes > 0) {

            $score = Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);
            $score = ($score / $totalVotes) * 100;
            $score = number_format($score, 2);
            $pricemodel12 = get_field('pricing_model', $list_post->ID);
            $freetrial = get_field('free_trial', $list_post->ID);
            if (empty($pricemodel12)) {
                $pricemodel12 = array();
            }
            if (in_array('open_source', $pricemodel12) || in_array('freemium', $pricemodel12) || $freetrial == 1) {
                $free_items_array[] = "free";
            }
////

            // free
            $postarr12[$list_post->ID] = $pricemodel12;
            if (in_array('freemium', $postarr12[$list_post->ID]) || in_array('open_source', $postarr12[$list_post->ID]) || $freetrial == 1) {
                $postarr[$list_post->ID] = $score;
            } else {
                $postarr1[$list_post->ID] = $score;
            }
            /* arsort($postarr);
            arsort($postarr1);
            echo "postarr\n";
            print_r($postarr);

            echo "postarr1\n";
            print_r($postarr1);
            $postarr = $postarr+$postarr1; */

//            }
        }
    }
////

    arsort($postarr);
    arsort($postarr1);
    $postarr = $postarr + $postarr1;
    /* echo "free\n";
    print_r($postarr); */
    $key = $value = null;
    foreach ($postarr as $key => $value) {
        break;
    }

    // echo "$key = $value\n";
    $bestoverallfree = $key;

    //end of free

    // print_r($postarr);
    // $GLOBALS['bestoverallfree']
    // echo "got list";

    // end of get_list_html
    if ($bestoverallfree == null) {
       $GLOBALS['all4features'] = [$bestoverall, $bestcustomerservice, $bestbudgetoption];
    } else {
        $all4features = [$bestoverall, $bestcustomerservice, $bestbudgetoption, $bestoverallfree];
    }
    
    ?>
	                <div class="row">
	                <?php
    $i = 0;
    // $all4featuresexpanded = array();
    foreach ($all4features as $feature) {
        if ($i == 0) {
            $featuretitle = "Best Overall";
        } elseif ($i == 1) {
        $featuretitle = "Best Customer service";

    } elseif ($i == 2) {
        $featuretitle = "Best Budget Option";

    } else {
        $featuretitle = "Best Free Option";

    }
    $img = get_the_post_thumbnail($feature, 'medium', array('itemprop' => 'image'));
    $title = get_the_title($feature);
    $overallscore = get_overall_combined_rating($feature)['list']['overallrating']['score'];
    $reviews = get_overall_combined_rating($feature);
    // print_r($reviews);
    // $i=0;
    $findrScore = 0;
    $findrScore += $reviews['list']['featuresfunctionality']['score'] * 3;
    $findrScore += $reviews['list']['easeofuse']['score'] * 2;
    $findrScore += $reviews['list']['customersupport']['score'] * 2;
    $findrScore += $reviews['list']['valueformoney']['score'] * 3;
    $findrScore += 50;
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
    if ($i == 0) {
        $customClass = "overall";
    } else {
        $customClass = "";
    }

    echo '<div class="col-3">
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
    $i++;
    /* $thisfeature = [$featuretitle, $img, $permalink, $title, $findrScore, $cta];
    $all4featuresexpanded[] = $thisfeature; */
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
$faq2 = do_shortcode("[list_title sop='plural']");
$free_software_id =  $GLOBALS['bestoverallfree'];
$free_software_title = get_the_title($free_software_id );
$faq1_ans ="Our users have voted that $free_software_title is the preferred free webinar software.";
/********************************************************* faq-1 END ***************************************************/
/********************************************************* faq-2 Start ***************************************************/
$all_list_overall =  $GLOBALS['bestoverall'];
$overall_software_title = get_the_title($all_list_overall );
$faq_Cat = $GLOBALS['category_faq'];
/********************************************************* faq-6 Start ***************************************************/
$list_id = get_the_id();
$posts = get_field('list_items',$list_id);
$allEOU = array();
$model = 0;
// print_r($posts);
foreach($posts as $post_object){
    $post_id = $post_object->ID;
    $rating = get_overall_combined_rating($post_id);
    $easeofuse = $rating['list'][easeofuse][score];    
    $allEOU[$post_id]= $easeofuse;
    $pricing = get_field('pricing_model',$post_id);
    
//    $count_model = array_count_values($pricing);
//    echo max($count_model);
   
  
}
echo "<pre>";
var_dump($pricing) ;
echo "</pre>";
print_r($allEOU);
arsort($allEOU);

$highesteou = 0;
foreach($allEOU as $key => $score){
    
    $highesteou = $key;

break;
}
$list_title = get_the_title($highesteou);





/********************************************************* faq-6END ***************************************************/
/********************************************************* faq-7 start ***************************************************/

// foreach($posts as $post){
//     $post_id[] = $post->ID;
//     $pricing[$post_id] =
// }
// print_r($post_id);
// $pricing[$post_id] = get_field('pricing_model' , $post_id);



/********************************************************* faq-7 END ***************************************************/

/********************************************************* faq-2 END ***************************************************/
    $faq = ' <div itemscope itemtype="https://schema.org/FAQPage">';
          $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  What is the best free $faq1 ?</h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. $faq1_ans
                            </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  Which is commonly used for $faq_Cat? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans.Over [x%] over users surveyed recommended $overall_software_title     </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  How much does a $faq_Cat cost? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. Looking at the data collected on our platform the average starting price is [insert average price and pricing model]
                             </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  What is the ".$faq1." with [feature]? (do for top 3 features) </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. The results are in and over a [insert number of users] as cast their votes and  [insert top product with that feature] is the recommended choice.
                             </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  What is the ".$faq1." in [top location]? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. After analyzing the data from users in [top location], the results shows that [top item] is the popular choice locally.                               
                            </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  Which are the best ".$faq1." for a beginner? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. $faq_ans6
                            </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.   Does GoDaddy or ".$faq1." have [top 1 feature]?
                        </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. Both [] and [item] has that feature, 
                            </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.   Does GoDaddy or ".$faq1." have [top 2 feature]?</h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. [item] has it however there is no mention that [item b] has it, double check their site to see if this as changed.
                             </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  Does GoDaddy or ".$faq1." have [top 3 feature]? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. 
                            </div>
                        </div>
                    </div>
          ";
          
          $faq .= "</div>";
             return $faq;

}
$faqs = '<div id="overview"> <h3>Frequently Asked Questions</h3> <div><span itemprop="description">'.get_list_faq().'</span>
 </div>
</div>
';
?>
<!-- /********************************************************FAQ End*********************************************** */  -->
        <div class="container-fluid sectionexplore" id="yarrp_container" data-id="<?php echo $main_list_id; ?>">
 <!-- /**********************************************FAQ cALL*********************************** */ -->
 <div class="container">
            <div class="list-main-content left-content-box">
                 <?php  echo $faqs; ?>    
            </div>
            </div>
<!-- /******************************************faq end********************************************** */ -->
            
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
 
    jQuery('#options').flagStrap({
        countries: <?php echo json_encode($targetCountries) ?>,
        buttonSize: "btn-sm",
        buttonType: "btn-info",
        labelMargin: "10px",
        scrollable: false,
        scrollableHeight: "350px"
    });
    
    var allOptions=jQuery('#options a');
    console.log("alloptions");
    console.log(allOptions.length);
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
  
	console.log(parms);
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
    jQuery('#options a').on('click', function() {
      
        window.open(jQuery(this).attr("href"),"_self");
        
    });

</script>

