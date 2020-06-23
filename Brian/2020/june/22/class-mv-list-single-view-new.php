<?php
class Mv_List_Single_View_New
{
    private $affiliate_url;
    private $affiliate_button_text;
    private $source_url;
    private $credit_text;
    private $tags;
    private $categories;
    private $comp_categories;
    private $editor_choice;
    private $product_model;
    private $product_type;
    private $alternate_tag;
    private $software;
    private $findrScore;
    private $total_score_feature_ind_avg;
    private $ranklist;
    private $industry_items;
    private $array_head;
    private $price_item;
    private $ease_dis;
    private $bestForCustSupport;
    private $industryFeaturesSorted;
    private $datapoints;
    private $availability;
    private $btntext;
    private $overallindustryratings;
    public function __construct()
    {
        remove_filter('the_content', 'wpautop'); //remove unwanted p tags in the wordpress content
        add_filter('the_content', array($this, 'show_list'), 1);
        add_action('wp_ajax_list_creator_post_vote', array($this, 'update_vote'));
        add_action('wp_ajax_nopriv_list_creator_post_vote', array($this, 'update_vote'));
        add_shortcode('show_list', array($this, 'show_list_shortcode')); // shortcode
        add_filter('manage_lists_posts_columns', array($this, 'add_shortcode_column')); // shortcode coloumn
        add_action('manage_lists_posts_custom_column', array($this, 'shortcode_column'), 10, 2); // shortcode coloumn
        // add_action( 'comment_form_after', array( $this, 'also_mentioned_in' ) );//mentioned in
    }

    public function show_list($content)
    {
        // echo "checkpoint2";
        // echo "inside show list";
        if (!in_the_loop()) {
            // echo "in the loop";
            return $content;
        }

        if (!is_singular()) {
            // echo "is singular";
            return $content;
        }

        if (!is_main_query()) {
            // echo "in main query";
            return $content;
        }
        // if (is_singular('lists')){
        //     echo "is singular lists ";
        // }
        // if(!has_shortcode($content, 'show_list')){
        //     echo "has not sc show list";
        // }
        if (is_singular('lists') && !has_shortcode($content, 'show_list')) {
            global $post;
            // echo "checkpoint1";
            if (!has_shortcode($post->post_content, 'show_list')) {
                $post_id= get_the_ID();
                // $content = "section list content";
                $content .= $this->list_contnet(get_the_ID());
                // $content .= "section list_geo_map";
                $content .= '<div class="map-div">' . $this->list_geo_map(get_the_ID()) . ' </div></div>';
                // $content .= "section get_list_html";
                $content .= $this->get_list_html(get_the_ID());
              
                // $content .= "section list_market_radar_chart";
                $content .= '<div class="container item-overview-content">' . $this->list_market_radar_chart(get_the_ID()) . '</div>';
                // $content .= "section list_price_performance_chart";
                $content .= '<div class="container item-overview-content">' . $this->list_price_performance_chart(get_the_ID()) . '</div>';
                // $content .= "section list_avg_cat_fndrscore";
                $content .='<div class="container item-overview-content">' .$this->list_avg_cat_fndrscore($post_id). '</div>';
                // $content .= "section list_comparion_data_show";
                $content .= '<div class="container item-overview-content">' . $this->list_comparion_data_show($post_id) . '</div>';
                // $content .= "section list_trust_guid_sec";
                $content .= '<div class="container item-overview-content">' . $this->list_trust_guid_sec(get_the_ID()) . '</div>';

            }
        } elseif (is_singular('list_items')) {
            $currentId = get_the_ID();
            // echo time();
            $this->increment_visit_count($currentId);
            // echo time();
            $this->set_single_list_item_vars($currentId);
            // echo time();
            $this->ranklist = get_item_ranks($currentId);
            // echo "before full content".time();
            // $this->ranklist($currentId);
            $content = $this->full_content($currentId);
            // echo "after full content".time();
            // $content = $this->get_single_list_item_content( get_the_ID(), $content );

        }
        remove_filter('the_content', array($this, 'show_list'), 1);

        // remove_filter( 'the_content', 'show_list' );

        return $content;
    }
    public function list_compare_table($postarr)
    {

        $post_id = get_the_id();
        $lists = get_field('list_items', $post_id, false);
        $i = 0;
        $list_comparion_data = '<p style=" font-size: 1.75rem; font-family: ProximaNova !important;">
       The ' . get_the_title($post_id) . ' Summed Up
        </p>';
        // $list_comparion_data .= ' <div class="row-1">';
        // $list_comparion_data .= '<p align="center"> When evaluating '.get_the_title($post_id).' our users also give serious thoughts to these other solutions:- </p>';

        ?>
<!--
        <div class="container">
        <div class="row">
            <div class="col-md-12" style="
    overflow: auto;
">





<span><?php echo $list_comparion_data; ?></span>

  <table class="table table-striped" style="box-shadow: 0 0 22px rgba(0,0,0,0.20);margin-top: 1rem;">
  <thead>
    <tr>
      <th scope="col">Features</th>
        <?php
$k = 0;
        foreach ($postarr as $item_id=>$score) {

            $item_title = get_the_title($item_id);
            $item_link_title = "<a href='".get_the_permalink($item_id)."'>$item_title</a>";
            ?>
                        <th scope="col"><?php echo $item_link_title; ?></th>
                      <?php $price_data = get_field('price_starting_from', $item_id, false);

            $item_price[$item_id] = intval($price_data);
            $per_user = get_field('per_user',$item_id,false);
            // var_dump($per_user);
            $pu=null;
            if($per_user == "1"){
                $pu = '/per user';
            }
            $frequencies[$item_id] = get_field('plan',$item_id,false).$pu;
            $fs_list[$item_id] = get_or_calc_fs_individual($item_id);

            $feature_list[$item_id] = get_field('features_list', $item_id);
            //   $features =    get_field( 'features_list', $post_id );

            $k++;

            if ($k == 10) {
                break;
            }

        }

        //    $item_price_data = implode(" " , $item_price);

        ?>

    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">price</th>
     <?php foreach ($item_price as $key => $price) {
            echo '<td> ' . $price . '</td> ';

        }

        ?>


    </tr>
    <tr>
      <th scope="row">frequency</th>
     <?php foreach ($frequencies as $key => $f) {
            echo '<td> ' . $f . '</td> ';

        }

        ?>


    </tr>
    <tr>
      <th scope="row">Findrscore</th>
      <?php
foreach ($fs_list as $key => $fs) {
            echo ' <td>' . $fs . '</td>';

        }
        ?>
    </tr>
    <tr>
    <?php

        foreach ($feature_list as $key => $features_list) {

            foreach ($features_list as $list) {

                $all_feature_list[] = $list;

            }
        }

        $frequent_used = array_count_values($all_feature_list);
        arsort($frequent_used);

        $ab = 0;
        // foreach($feature_list as $key =>$features_list){

        foreach ($frequent_used as $k => $list) {
            // foreach($list as $k =>$list_11){
            // echo $list;
            echo '<tr><th>' . $k . '</th>';
            foreach ($feature_list as $j => $items) {
                if (in_array($k, $items)) {

                    echo "<td><i class='fa fa-check' aria-hidden='true' style='color: green;' ></i></td>";
                } else {
                    echo "<td><i class='fa fa-minus' aria-hidden='true' style='color: grey;' ></i></td>";

                }

            }

            echo "</tr>";

            $ab++;
            if ($ab == 10) {
                break;
            }

            // }
        }

        // }
        ?>


    </tr>
  </tbody>
</table>


</div>
                </div>
            </div> -->














        <?php

        // return $data;
    }

//get_faqs fxn only for faq
    public function get_faqs($post_id)
    {
        $post_id = get_the_id();
        $item_feature_lists = get_field('features_list', $post_id);
        $item_rating_feature = get_or_create_feature_ratings($post_id);//get_field('features_list_ratings', $post_id,true);

        // get_field( 'features_list', $value );
       /*    echo "ïtem rating _feature";
        print_r($item_rating_feature); 
        echo "ïtem feature list";
        print_r($item_feature_lists);  */
        $features_ids = array(
            'id0' => $post_id,
        );

        // echo "sorted array?";
        $sorted_feature_list = sort_features($item_feature_lists, $features_ids);
        // print_r( $sorted_feature_list);

        $i = 0;
        foreach ($sorted_feature_list as $vote => $feature) {
            $three = $feature['feature'];

            $i++;
            if ($i == 1) {
                break;
            }
        }

// echo $three;
        // echo $votes;
        if(is_array($item_rating_feature)){
        foreach ($item_rating_feature as $key => $item_key_feature_rat) {
// echo "item_key feture<br>";
            //             print_r($item_key_feature_rat);
            //             echo "key<br>";
            //             print_r($key);
            if ($key == $three) {
// echo "item_key feture<br>";
                //             print_r($item_key_feature_rat);
                //             echo "key<br>";
                //             print_r($key);
                $topfeature_avg = round($item_key_feature_rat['average'], 2);
                $topfeature_score = $item_key_feature_rat['total_score'];
                $topfeature_name = $three;

            }

                }    
                }
                if(isset($topfeature_score)){

                
        $top_ind_avg = round($this->total_score_feature_ind_avg[$topfeature_name], 2);
        if ($top_ind_avg < $topfeature_score) {
            $hig_low_data = " high ";
        } else {
            $hig_low_data = "low ";
        }
   
        
        /* echo "t s f i a";
        print_r($this->total_score_feature_ind_avg); */
        $topfeature_name = str_replace("_", ' ', $topfeature_name);
    }
        if ($post_id == 0) {
            $post_id = get_the_ID();
        }

        $iem_name = get_the_title($post_id);
        $beginners = '';
        $rating = get_overall_combined_rating($post_id);
        /******************************************8* faq-3 start ***********************************************/
        $pricing_model = get_field('pricing_model', $post_id);
        $plan = get_field('plan', $post_id);
        $starting_price = get_field('price_starting_from', $post_id);
        foreach ($pricing_model as $pricing) {
            if ($pricing == 'freemium') {
                $pric = "Free to use.";
                $free = "Yes there is a free trial available which should give you enough time to test out $iem_name to see if it’s the right fit for you.";
            } else {
                $pric = "You can expect to pay around </label><span itemprop='priceCurrency' content='USD'>$</span><span itemprop='price'>" . preg_replace("/[^0-9.]/", "", $starting_price) . " USD per <span itemprop='price_plan'>" . $plan . "</span></p>";
                $free = "The isn’t a mention of a free trial on our system but you can always check to see it a money back guarantee is on offer with $iem_name .";
            }
        }
        /********************************************** * end of faq-3 *******************************************/
        /******************************************** * faq-1 start ***************************************/
        $easeofuse=0;
        if(isset($rating['list']['easeofuse']['score'])){
            $easeofuse = $rating['list']['easeofuse']['score'];
        }
        
        if ($easeofuse > 2.5) {
            $beginners = "Users who have used $iem_name as reported that it’s fairly easy to grasp.";

        } else {
            $beginners = "Users who have used $iem_name as reported that there is a learning curve which you should keep in mind..";
        }
/****************************************************end of faq-1*****************************************/
/******************************************** faq-2 start ***********************************************/
        $list_setting = get_option('mv_list_items_settings');
        $target_countries = $list_setting['list_page_target_countries'];
        $countryPair = explode(',', $target_countries);
        $download_count = array();
        $max_downloads_in = '';
        $max_downloads_value = 0;
        foreach ($countryPair as $cp) {
            $pair = explode('=>', $cp);
            foreach ($pair as $key => $value) {
                $pair[$key] = trim(trim($value), "'");
            }
            $targetCountries[$pair[0]] = $pair[1];
            $download_count[$pair[0]] = get_field('downloads_in_' . $pair[0], $post_id);
            if ($max_downloads_value < $download_count[$pair[0]]) {
                $max_downloads_value = $download_count[$pair[0]];
                $max_downloads_in = $pair[0];
            }

        }
        $county='';
        if(!empty($max_downloads_in)){
            $county = $targetCountries[$max_downloads_in];
            $faqans = "Out of all the users on our platform $iem_name as the highest adaptation rate in $county";
        }
      
        /******************************************** faq-2 end **************************************************/
        /****************************************************start of faq-4*****************************************/
        $itm_support = get_field('support', $post_id);
        $overalrating = 0;
        if(isset($rating['list']['overallrating']['score'])){
            $overalrating = $rating['list']['overallrating']['score'];
        }
        
        // $ratings = $compareClass->most_compared_rating($post_id);
        $rating = get_overall_combined_rating($post_id);
        $map = array("comments" => "Live Chat", "envelope" => "Mail", "phone" => "Phone", "wpforms" => "Forum");
        $mapped_support = array();
        $rat = $rating['list'];
        if(is_array($itm_support)){
            foreach ($itm_support as $item_supports) {
                $mapped_support[] = $map[$item_supports];
            }
        }
        $item_supports = implode(", ", $mapped_support);
        $cust_rat=0;
        if(isset($rat['customersupport']['score'])){
            $cust_rat = $rat['customersupport']['score'];
        }
        
        if ($cust_rat > 2.5) {
            $itm_support_ans = "The majority of users who are sharing their experience with us on $iem_name are experiencing a positive experience with the support offered through $item_supports.";
        } else {
            $itm_support_ans = "The majority of users who are sharing their experience with us on $iem_name are experiencing a poor experience with the support offered through $item_supports.";
        }

        /****************************************************end of faq-4*****************************************/
        /******************************************** * faq-5 & faq-7 start ***************************************/
        $link = get_permalink($post_id);
        $link_coupon = '<a href="' . $link . 'coupon">coupon</a>';
        $couponlist = get_post_meta($post_id, 'coupons_list', true);
        if (!empty($couponlist)) {
            $link_coupon_not = 'Yes there are a few coupons you can try here: ' . $link_coupon . '';
        } else {
            $link_coupon_not = "There are no record on our system of $iem_name offering coupons historically";
        }
        /******************************************** * faq-5 & faq-7 end ******************************************/
        /******************************************** * faq-8start *************************************************/
        $compObj = new Mv_List_Comparision(null,true);
        $lists = $compObj->most_compared($post_id, 20, true);

        $pricing_model = get_field('pricing_model', $post_id);
        $plan = get_field('plan', $post_id);

        if (!empty($lists)) {
            $ac = 0;
            foreach ($lists as $pid) {
                $rating_comp = get_overall_combined_rating($pid);
                $pricing_model = get_field('pricing_model', $pid);
                $plan = get_field('plan', $pid);
                foreach ($pricing_model as $pricing) {
                    if ($pricing == 'freemium') {
                        $overalrating_comp[] = $rating_comp['list']['overallrating']['score'];
                        $rat_alternte = max($overalrating_comp);
                        $alternate_title = get_the_title($pid);
                        $altr_link = generate_compare_link(array($post_id, $pid));
                        // $altr_link =  get_the_permalink($pid);
                        $alternate_ans = "Yes, $alternate_title is proving quite popular with our users, <a href='$altr_link'>see how it stacks up against $iem_name</a>";

                    }
                    $ac++;
                }
            }
        }

        /******************************************** * faq-8end *****************************************************/
        /******************************************** * faq-9 start **************************************************/

        $listrankord = $this->ranklist;

        // print_r( $listrankord);
        $ab = 0;
        if (!empty($listrankord)) {
            foreach ($listrankord as $id => $rank) {

                // $post_id = $id;
                $main_list_id = $id;

                $catobj = get_the_terms($id, 'list_categories');
                $finalVotes = do_shortcode("[total_votes id=$main_list_id]");
                $faq9 = get_field('list_content_title_singular', $main_list_id);
                $ans_faq9 = "$iem_name is ranked $rank on that list according to $finalVotes users";
                if ($faq9 == '') {
                    $faq9 = get_the_title($main_list_id);

                }

                // echo $main_list_id;
                $ab++;
                if ($ab == 1) {
                    break;
                }

            }

        }

        /******************************************** * faq-9 start **************************************************/
        /******************************************** * faq-10 start ************************************************/
        $list_altr = $compObj->most_compared($post_id, 20, true);
        $overalrating_1=array();
        foreach ($list_altr as $lists) {
            $rating = get_overall_combined_rating($lists);
            if(isset($rating['list']['overallrating']['score'])){
                $overalrating_1[$lists] = $rating['list']['overallrating']['score'];
            }
            

        }
        arsort($overalrating_1);
        foreach ($overalrating_1 as $key => $top_alter) {
            $top_alternative = $key;
            break;
        }
        if(isset($top_alternative)){

        
        $top_alternative_title = get_the_title($top_alternative);
        $altr_link_top = generate_compare_link(array($post_id, $top_alternative));
        $better_item = "This is a tricky question as it all depends on your needs, but $iem_name as a higher FindScore than $top_alternative_title which factors in many data points. <a href='$altr_link_top'>Have a closer look to see why?</a>";
        }
        /******************************************** * faq-10 end *************************************************/
        $faq = ' <div itemscope itemtype="https://schema.org/FAQPage">';
        $qalist = get_post_meta($post_id, 'qas_list', true);
        // print_r($qalist);
        if(is_array($qalist)){
            foreach ($qalist as $faq_ls) {
                $custfaq = "<div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                                <h4 itemprop='name'> Q. " . $faq_ls['question'] . "</h4>
                                <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                                    <div itemprop='text'>
                                        Ans." . $faq_ls['answer'] . "
                                    </div>
                                </div>
                            </div>";
                $faq .= $custfaq;
            }
        }
        $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h4 itemprop='name'> Q.  Is $iem_name good for beginners?</h4>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. $beginners
                             </div>
                        </div>
                    </div>
          ";
        if (!empty($county)) {
            $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h4 itemprop='name'> Q.  How good is $iem_name in $county? </h4>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans.$faqans       </div>
                        </div>
                    </div>
          ";
        }
        $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h4 itemprop='name'> Q.  How much does $iem_name cost? </h4>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. $pric
                             </div>
                        </div>
                    </div>
          ";
        $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h4 itemprop='name'> Q.  What type of support can I expect with $iem_name? </h4>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. $itm_support_ans
                             </div>
                        </div>
                    </div>
          ";
        $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h4 itemprop='name'> Q.  Are there any coupons for $iem_name? </h4>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. $link_coupon_not
                            </div>
                        </div>
                    </div>
          ";

        if (!empty($topfeature_score)) {
            $faq .= " <div  itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h4 itemprop='name'> Q.  Is $iem_name any good for $three? </h4>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. $iem_name as a  $hig_low_data rating when it comes to  $three, scoring  $topfeature_score with a category average of $top_ind_avg .

                            </div>
                        </div>
                    </div>
          ";
        }
        $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h4 itemprop='name'> Q.   Can I try $iem_name for free? </h4>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. $free
                            </div>
                        </div>
                    </div>
          ";
        if (!empty($alternate_ans)) {
            $faq .= "
          <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h4 itemprop='name'> Q.  Are there any Free alternative to $iem_name?</h4>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. $alternate_ans
                             </div>
                        </div>
                    </div>

          ";
        }
        if(isset($faq9)){

       
        $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h4 itemprop='name'> Q.  Is $iem_name the best $faq9 ?</h4>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans.  $ans_faq9.
                            </div>
                        </div>
                    </div>
          ";
        }
          if(isset($top_alternative)){
        $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h4 itemprop='name'> Q. Which is better $iem_name or $top_alternative_title?</h4>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. $better_item
                            </div>
                        </div>
                    </div>
          ";
          }
        $faq .= "</div>";
        return $faq;

    } //get_faqs fxn only for faq end

    //------------------------------getting select relationship  id ---------------------------------------
//     public function get_item_ranks($pId)
//     {

//         $lists = get_field('add_to_list', $pId, false);
//         $itemiid = $pId;
//         $listrankord = array();
// //    $i=0;
//         if (!empty($lists) && is_array($lists)) {

//             foreach ($lists as $id) {
//                 if ($this->acme_post_exists($id)) {
//                     $rank = get_item_rank($id, $itemiid);
//                     $listrankord[$id] = $rank;

//                 }
//                 //
//                 //         $i++;

//                 //         if ($i ==23)
//                 // break;
//             }

//             asort($listrankord);

//         }
//         return $listrankord;

//     }
    public function list_contnet($listId)
    {

        $adddata = '';
        $postDat = get_post($listId);
        $contentP = $postDat->post_content;
        $addInform = get_field('additional_information', $listId);
        if (!empty($addInform)):
            $adddata = '<div class="container-fluid section3">
	                        <div class="container">
	                            <div class="row justify-content-md-center">
	                                <div class="col-md-8 center">
	                                    <p class="quote" >' . $addInform . '</p>
	                                </div>
	                            </div>
	                        </div>
	                    </div> ';
        endif;
        $options = get_option('mv_list_items_settings');
        $googleContnet = !empty($options['list_page_google_add']) ? $options['list_page_google_add'] : '';
        $total_items = count(get_field('list_items', $listId));
                
        $title_plural = get_field('list_content_title_plural', $listId);
        $title_singular = get_field('list_content_title_singular', $listId);

        $finalVotes = do_shortcode("[total_votes id=$listId]");
        $this->datapoints = get_data_points_list($listId);
        $deals = get_page_by_path("deals", OBJECT, 'page');
        $dealsPermalink = get_permalink($deals->ID);
        $catobj = get_the_terms( $listId, 'list_categories' );
        if ( $catobj && ! is_wp_error( $catobj ) ) {
            $this->categories = $catobj;
        }
        /* echo "category";
        print_r($this->categories); */
        $popCat = '';
        $popCatCount = 0;
        foreach($this->categories as $cats){
            if($cats->count > $popCatCount){
                $popCatCount = $cats->count;
                $popCat = $cats->name;
            }
            
        }
        $htm = '<div class="container headr_Sec_div" style="margin: 0 auto;"><divstyle="
        min-height: 358px;
    "><div class="list_overview_sec">
            <div class="abc">
                <div class="row">
                    <div class="col-md-10 col-sm-12 contenttext"><div id="list_main_contnet">' . $contentP . '
                    <p class="p1"><span style="
                    color: black;
                    font-weight: 100;
                    display: block;
                ">
                    
         Over users have rated and voted on '.$total_items.' of the best solutions '.$title_plural.' available in '.date("Y").', put them through a stringent series of examinations with the help of '.$finalVotes.' voters like you, and assigned them FindrScores, all to help you make a smarter decision.  
</span></p>
<p class="p1"> Our algorithm looks at over '.$this->datapoints.' such as user satisfaction, price, geographical quirks, how the must-have functionality like '.implode(', ',$_SESSION['top3features']).' compare against these '.$total_items.' solutions.
</p> 
<p class="p1">     So whether you\'re looking for the cheapest, easiest to use, the best overall we ‘ve got you covered. 
         </p>
         <p class="p1"> 
         Narrow down your search</p>
         <p class="p1">    Find the best '.$title_singular.' for you, Tailor the data to find the best '.$title_singular.' for your needs. See which software offers the best '.implode(', ',$_SESSION['top3features']).' or which is the easiest to use.
         </p>
         <p class="p1">   <a href="'.get_permalink().'?lang=GB" >Filter by top '.$title_plural.' in United Kingdom</a> – See what users from this country recommends
         </p> <p class="p1"> <a href="'.get_permalink().'?lang=IN" >Filter by '.$title_plural.' in India</a> – See what users from this country recommends
         </p> <p class="p1"> <a href="'.get_permalink().'?sort=free#filter" >Filter by free '.$title_plural.'</a> – See which is the best free solution.
         
         </p> <p class="p1"> <a href="'.get_permalink().'?sort=affordable_price#filter" >Filter by cheapest '.$title_plural.'</a> – See which is the most affordable solution.
         
         </p>  <p class="p1"> <a href="'.$dealsPermalink.'?cat='.$popCat.'" > Looking for '.$popCat.' deals?</a> – See today\'s best '.$popCat.' discount codes.
         
         
                    </p></div><div class="list-read-more"><a href="javascript:;" class="readbutton">Read More >>></a>
                ';
             
                $htm .= '</div>
                </div>
                </div>
                </div>
            </div>
                <div classs="right_sidebar">                                              
                                <div class="col-md-4 contentimage" align="center">' . $googleContnet . '
                                </div> 

                                <div class="table_content">  <p class="toc_container">[toc]</p></div> ' . $adddata .'
                 </div></div>';

        return $htm;
    }
    public function get_country_name($isoCode)
    {
        global $targetCountries;
        $country = '';
        $country = $targetCountries[$isoCode];
        /*   echo "line 623";
        echo "targetCountries[isoCode] <br>$targetCountries[$isoCode]"; */
        return $country;
    }

    public function set_single_list_item_vars($post_id)
    {
        $this->affiliate_url = get_field('affiliate_url', $post_id);
        if ( ! empty( $this->affiliate_url ) ) { 
            if(substr_count($this->affiliate_url, "?")>=1){
                $this->affiliate_url.="&utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=".$post_id;
            }
            else{
                $this->affiliate_url.="?utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=".$post_id;
            }
            
        }
        $this->source_url = get_field('source_url', $post_id);
        $this->afflink = empty( $this->affiliate_url )? $this->source_url:$this->affiliate_url;

        $this->affiliate_button_text = get_field('affiliate_button_text', $post_id) == '' ? 'Download/Demo' : get_field('affiliate_button_text', $post_id);
        $this->credit_text = get_field('credit', $post_id);
        $this->btntext = empty($this->affiliate_button_text) ? $this->credit_text : $this->affiliate_button_text;
        $this->availability = get_post_meta($post_id, '_item_availbility', true);
        
        $this->features = get_field('features_list', $post_id);
        $this->pricing_model = get_field('pricing_model', $post_id);
        $this->free_trial = get_field('free_trial', $post_id);
        $this->free_trial_card = get_field('card_required', $post_id);
        $this->price_starting_from = get_field('price_starting_from', $post_id);
        $this->additional_price_info = get_field('additional_price_info', $post_id);
        $this->product_type = get_field('product_type', $post_id);
        $this->editor_choice = get_field('editor_choice', $post_id);
        $this->alternate_tag = get_field('alternate_tag', $post_id);
        $this->software = get_field('software', $post_id);
        $this->plan = get_field('plan', $post_id);
        $catobj = get_the_terms($post_id, 'list_categories');
        if ($catobj && !is_wp_error($catobj)) {
            $this->categories = $catobj;
        }
        $campobj = get_the_terms($post_id, 'list_comp_categories');
        if ($campobj && !is_wp_error($campobj)) {
            $this->comp_categories = $campobj;
        }
        $tagsobj = get_the_terms($post_id, 'item_tags');
        if ($tagsobj && !is_wp_error($tagsobj)) {
            $this->tags = $tagsobj;
        }
        $this->findrScore = get_or_calc_fs_individual($post_id);

        $list_item = get_field('add_to_list', $post_id, false);

        foreach ($list_item as $key => $lists) {

            $post_ids = get_field('list_items', $lists);
            // echo "post_idss";
            // print_r($post_ids);
            if(is_array($post_ids)){
                foreach ($post_ids as $post_id_item) {
                    $all_item_id[] = $post_id_item->ID;
                }
            }
            

        }
        $this->industry_items = array_unique($all_item_id);
       /*  echo "industry items ";
        print_r($this->industry_items); */
        $this->reviewClass = new RWP_Rating_Stars_Shortcode();
    }

    public function list_decision_sec($post_id)
    {
        $post_id = get_the_id();
        $main_list_id = $list_id = $post_id;
        $lists = get_field('list_items', $post_id, false);
        foreach ($lists as $pid) {
            $list_item_price[] = intval(get_field('price_starting_from', $pid));
            // $list_feature[$pid] = get_field('features_list_ratings', $pid);

        }

        $item_max_price = max($list_item_price);
        $item_min_price = min($list_item_price);
        // arsort($list_feature);
        // $i = 0;
    /*     echo "list_feature";
        print_r($list_feature); */
     /*    foreach ($list_feature as $key => $item_key_feature_rat) {
            echo "key ".$key;
            print_r($item_key_feature_rat);
            foreach($item_key_feature_rat as $feature=>$scoreArray){
                $all_feature_score[] = $scoreArray['average'];
                $all_feature_name[] = get_the_title($key);
            }
       

            $i++;
            if ($i == 3) {
                break;
            }

        } */

        $finalVotes = do_shortcode("[total_votes id=$main_list_id]");

        ?>
            <div class="container decision_sec" style="width:  85%;margin:  0 auto;" >
            <div class="container-fluid">
            <div class="row" >
            <div class="col-sm-12"><strong align="center">HOW TO MAKE YOUR DECISION</strong></div>
            <div class="col-sm-4" >



            <strong align="center">  1 </strong>
            <strong align="center"> PRICE EXPECTATIONS  </strong><br>
              <?php
$abc = ' <span> Expect to pay anywhere from  $' . $item_min_price . ' - ' . $item_max_price . '  for a competent solution with this category. Don\'t fall prey to the prey to using price as a proxy for quality</span>';
        echo $abc;
        ?>
               </div>
            <div class="col-sm-4">
            <strong align="center">  2     </strong>
            <strong align="center"> FUNCTIONALITY</strong><br>
            <span>  You should take some time to figure out what features are mission-critical to your project. Our users tend to look for a provider that offers <?php echo $_SESSION['top3features'][0]; ?> and <?php echo $_SESSION['top3features'][1]; ?>, as well as a <?php echo $_SESSION['top3features'][2]; ?>.
            </div>
            <div class="col-sm-4">
            <strong align="center"> 3 </strong>
            <?php $list_data_points = $this->datapoints; 

        ?>
            <strong align="center"> FINDRSCORE  </strong><br>
            <span> Pay cost attention to the FindrScore given as it incorporates over <?php echo $list_data_points; ?> data points powered by <?php echo $finalVotes; ?> user voices. Narrow down and use the comparison feature to figure out which is right for you.</span>
            </div>
            </div>
            </div>
            </div>

<!--   webinar section   -->

<?php
$sub_heading = get_field('sub_heading', $main_list_id);
        $get_list_name = get_field('list_content_title_singular', $main_list_id);
        $finalVotes111 = do_shortcode("[[year] id=$main_list_id]");

        if (!empty($sub_heading)) {
            $list_title = $sub_heading;
        } elseif (!empty($get_list_name)) {
            $list_title = "What is the Best  " . $get_list_name . " right now?";

        } else {
            $list_title = "What is the " . get_the_title($main_list_id) . "?";

        }?>
            <div class=" container">
            <div class="row" >
            <div class="col-sm-12"> <h2 align="center">    <?php echo $list_title;?>  </h2>




<?php

        $year = do_shortcode('[year]');
    
        $plural_title = get_field('list_content_title_plural', $main_list_id);
        if ($plural_title == ''){
            $plural_title = get_the_title($main_list_id);
        }

        echo '<p align="center">Without further adieu, here are our picks for the '.$plural_title.' in ' . $year . '  according to ' . $finalVotes . ' users.</p>';

        ?>
            <!-- <p align="center"> What is the <?php //echo $list_title; ?>?  </p>    -->
           </div>
            </div>
            </div>
<?php

    }
    public function show_list_shortcode($atts)
    {
        return $this->get_list_html($atts['id']);
    }
    //getlist filter================/
    public function get_list_html($list_id)
    {
        // global $wp;
        $current_page        = get_query_var( "page" ) ? get_query_var( "page" ) : 1;
        $items_per_page      = get_field( 'items_per_page', $list_id );
        $site_url=get_site_url();
        $main_list_uri = get_page_uri( $list_id );
        ?>

         
            <?php

        $this_list = $_SESSION['gen_list'][$list_id];
        /* echo "this list ";
        print_r($this_list); */
        // echo "<script>console.log(gen_list);</script>";
       /*  echo "get2";
        print_r($_GET); */
        
        $sorts = '';
        if(isset($_GET['sort'])){
            $sorts=$_GET['sort'];
        }
      
        $postarr = $this_list[$sorts];
        foreach($postarr as $key=>$value){
            $compareItems[]=$key;
            if(count($compareItems) > 1){
                break;
            }
        }
        
        $total_pages = ceil(count($postarr) / $items_per_page);
        $current_page        = get_query_var( "page" ) ? get_query_var( "page" ) : 1;
    
        ob_start();
      
        $static_val = get_field('list_items', $list_id, false);
        // echo "<br>checkpoint1";
        if (!empty($static_val)) {
            
            // echo "start inside";
            ?>


                        <div class="zombify-main-section-front zombify-screen" id="zombify-main-section-front-new" style="overflow: hidden;display: block;
                width: 100%;
            ">
                            <div class="container-fluid section4" id="bargraph">
                    <div class="container">
                        <div class="row justify-content-md-center">
                            <div class="col-md-8 center">

                                <p>
                                    <strong>
                                        Current Users Recommendation Distributions

                                    </strong>
                                </p>
                                <?php if (!empty($static_val)) {?>




                                    <div class="zf-container">
                                        <div class="zf-list zf-numbered" id="zf-list">
                                            <div class="graph_section">
                                                <div class="graph_section_inner">

                                                <div id="morrisjs_graph" style="height: 300px;"></div>
                                                    <div id="morrisjs_graph_data" ></div>

                                                </div>
                                            </div>
                                            <script type="text/javascript">
                                                jQuery(document).ready(function(){

                                                    load_graph('<?php echo $list_id; ?>',1);
                                                    // $('[data-toggle="tooltip"]').tooltip();
                                                });

                                            </script>

                                            <div class="tooltip">Scoring methodology <i class="fa fa-info-circle"></i>
                                                <span class="tooltiptext">The Scoring is based on our unique algorithm that looks at reviews, votes, behavior, social signals and more. <a rel="nofollow" href="/scoring-methodology/">Learn more</a></span>
                                            </div>
                                            <div class="votes_calculatr"></div>
                                        </div>
                                    </div>
								<?php }?>

                            </div>
                        </div>
                    </div>
                </div>

               <!-- decision function call -->

             <?php $decisin_sec = $this->list_decision_sec($list_id);?>
         <section id="filter" >
         </section>

                <div class="row" style="overflow:visible;">
                    <div  class="fileter_list" style="">
                        <div class="col-sm-7" style="text-align: right;">
                            <?php $a = get_permalink();
            // forcountry data
         /*    echo "sorts ";
            echo $sorts; */
            ?>
                            <a href="<?php echo get_permalink() ?>" class="filterlist <?php if ((isset($sorts) && $sorts == "free") || (isset($sorts) && $sorts == "affordable_price") || (isset($sorts) && $sorts == "user_friendly")) {} else {echo 'ractive';}?>">Recommended For Me </a>
                            <?php if (count($this_list['free']) !== 0) {?>
                            <a href="<?php echo get_permalink() . '?sort=free#filter' ?>" class="filterlist <?php if (isset($sorts) && $sorts == "free") {echo 'ractive';}?>" >Free</a>
                            <?php }?>
                            <a rel="nofollow" href="<?php echo get_permalink() . '?sort=affordable_price#filter' ?>" class="filterlist <?php if (isset($sorts) && $sorts == "affordable_price") {echo 'ractive';}?>">Most affordable</a>
                            <a rel="nofollow" href="<?php echo get_permalink() . '?sort=user_friendly#filter' ?>" class="filterlist <?php if (isset($sorts) && $sorts == "user_friendly") {echo 'ractive';}?>">Most User friendly</a>
                        </div>
                       
                        <div class="col-sm-4" style="text-align: left;">
                            <!-- for selectcountry -->
                            <ul style="display: -webkit-inline-box;">
                            <li class="region-label">
                            Select Country
                            </li>&nbsp; &nbsp;
                            <li class="region">
                            <div class="options" data-input-name="country"></div>
                            </li>
                            </ul>
                        </div>
                    </div>
                </div>
                 
                <?php   //echo "hello ";
                $html=ob_get_contents();
                ob_end_clean();
                ob_start();
        echo generate_list_html(false,$sorts,$current_page); ?>      
                
                <?php
$big = 999999999; // need an unlikely integer
            /* echo paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?page=%#%',
            'current' => $current_page,
            'total' =>  $total_pages
            ) ); */
            $pagelink = get_permalink();
            $options = get_option('mv_list_items_settings');
            $scroll = !empty($options['scroll_setting']) ? $options['scroll_setting'] : '';
            if(isset($_GET['sort'])){
                $sort=$_GET['sort'];
            }
            
            if ($scroll == "numeric") {
                if ($total_pages > 1) {
                    for ($i = 1; $i <= $total_pages; $i++) {
                        if ($i == $current_page) {
                            $pagLink .= "<a class='page-numbers current' href='"
                                . $pagelink . $i . "/'>" . $i . "</a>";
                        } else {
                            $pagLink .= "<a class='page-numbers' href='" . $pagelink . $i . "/'>
                                                                    " . $i . "</a>";
                        }
                    };
                    echo '<div class="container"><div class="row"><div class="col-md-12" >';
                    echo "<nav class='navigation pagination' role='navigation'><div class='nav-links' style='margin: 0px auto;padding-bottom:40px'>";
                    echo $pagLink;
                    echo "</div></nav>";
                    echo "</div></div></div>";
                }
            } elseif ($scroll == "loadbutton") {?>
                        <div id="ajax-response"></div>
                        <div id="nopostmessage"></div>
                        <div class="ajax-ele" style="text-align: center;">
                          <button id="loadmore" data-startpage="<?php echo $current_page + 1 ?>"  data-uri="<?php echo $_SERVER['REQUEST_URI']; ?>"  class="getbtn" style="border:none">Load More</button>
                          <div class="loader-container" style="text-align: center;padding: 40px;">
                            <img id="ajax-loader"  data-uri="<?php echo $_SERVER['REQUEST_URI']; ?>" src="<?php echo $site_url; ?>/wp-content/uploads/2019/04/ajax-loader-1.gif" style="display:none;"/>
                          </div>
                      </div>
            <?php	} else {?>
                        <div id="ajax-response"></div>
                         <div id="nopostmessage"></div>
                         <div class="ajax-ele" style="text-align: center;">
                            <img id="infinite-loader" data-startpage="<?php echo $current_page + 1 ?>"  data-uri="<?php echo $_SERVER['REQUEST_URI']; ?>" src="<?php echo $site_url; ?>/wp-content/uploads/2019/04/ajax-loader-1.gif" />
                         </div>
            <?php	}
            ?>
                  <input type="hidden" id="total-pages" value="<?php echo $total_pages ?>" />



            </div>
            <div class="container-fluid section6">
                <div class="container">
                    <div class="row justify-content-md-center">
                        <div class="col-md-7 center">
                            <b style="color: #fff; font-size: 1.75rem; font-family: ProximaNova !important;">
                            DON'T SEE YOUR PRODUCT
                            </b>
                            <p align="center" style="color: #fff;">Submit it, to show up here.</p>
                          <a href="<?php echo get_home_url() . '/login'; ?>">  <button style="background: #00a1e4;padding: 2%;border-radius: 30px;width: 47%;color: #fff;">Submit your suggestion</button>
                          </a>
                            <!-- <ul class="addrec">
                                <li class="photocam">
                                    <img width="25px" src="<?php echo image_path_single('images/photocam.jpg') ?>">
                                </li>
                                <li class="">
                                    <form>
                                        <input type="text" placeholder="Something missing? add it here...">
                                        <img src="<?php //echo image_path_single('images/searchicon.png'); ?>">
                                    </form>
                                </li>
                            </ul> -->
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="container">
                <div class="container">
                    <div class="row justify-content-md-center">
                        <div class="col-md-10 center">
                            <?php if (!empty($postarr) && count($postarr) > 0) {?>
                                <p class="center greycol">
                                    <h4><?php     $plural_title = get_field('list_content_title_plural', $list_id);
        if ($plural_title == ''){
            $plural_title = get_the_title($list_id);
        }
        echo $plural_title . " Pricing Guide and Cost Comparison"; ?></h4>
									<br><br>
									<span>The table below should help you gain an understanding of how the top voted solutions are priced. Click on the solution name to get read the full review and get the full list of features offered, user reviews, and product videos.</span>
                                </p>
                                <div class="top-list_item_table" style="overflow: scroll;">
                                  <!--  <?php

                $index = 1;
                echo "<table class='list-tab'><thead><th>Rank</th><th>Name</th><th>Rating</th><th>Price</th><th>Pricing Model</th></thead>";
                $cont = 0;
                foreach ($postarr as $key1 => $score): // variable must be called $post (IMPORTANT)
                    if ($index > 5) {

                        break;
                    }
                    $cont++;
                    $main_list_idn = get_the_ID();
                    //setup_postdata( $post );
                    // $list_postii = $postarrOBJ[$key1];
                    $list_idn = $key1;
                    $pricing_model = get_field('pricing_model', $list_idn);
                    ////field get Price Starting From//////////
                    if (!empty(get_field('price_starting_from', $list_idn))) {
                        $pricing_start = get_field('price_starting_from', $list_idn);
                    } else {
                        $pricing_start = '-';
                    }
                    $score = RWP_API::get_review($list_idn);
                    echo "<tr><td>" . $cont . "</td><td><a class='update_list_modified_link' data-zf-post-id='$list_idn' data-zf-post-parent-id='$main_list_idn' href='" . get_permalink($list_idn) . "'>" . get_the_title($list_idn) . "</a> </td><td>" . round($score['review_overall_score'], 1) . "/5</td><td>" . $pricing_start . "</td><td>" . str_replace('_', ' ', implode(', ', array_map('ucfirst', $pricing_model))) . "</td></tr>";
                    $index++;
                endforeach;
                echo "</table>";

                ?> -->
				
				
	

			  <table class="table table-striped" style="box-shadow: 0 0 22px rgba(0,0,0,0.20);margin-top: 1rem;">
			  <thead>
				<tr>
				  <th scope="col">Features</th>
					<?php
			$k = 0;
					foreach ($postarr as $item_id=>$score) {

						$item_title = get_the_title($item_id);
						$item_link_title = "<a href='".get_the_permalink($item_id)."'>$item_title</a>";
						?>
									<th scope="col"><?php echo $item_link_title; ?></th>
								  <?php $price_data = get_field('price_starting_from', $item_id, false);

						$item_price[$item_id] = intval($price_data);
						$per_user = get_field('per_user',$item_id,false);
						// var_dump($per_user);
						$pu=null;
						if($per_user == "1"){
							$pu = '/per user';
						}
						$frequencies[$item_id] = get_field('plan',$item_id,false).$pu;
						$fs_list[$item_id] = get_or_calc_fs_individual($item_id);

						$feature_list[$item_id] = get_field('features_list', $item_id);
						//   $features =    get_field( 'features_list', $post_id );

						$k++;

						if ($k == 10) {
							break;
						}

					}

					//    $item_price_data = implode(" " , $item_price);

					?>

				</tr>
			  </thead>
			  <tbody>
				<tr>
				  <th scope="row">price</th>
				 <?php foreach ($item_price as $key => $price) {
						echo '<td> ' . $price . '</td> ';

					}

					?>


				</tr>
				<tr>
				  <th scope="row">frequency</th>
				 <?php foreach ($frequencies as $key => $f) {
						echo '<td> ' . $f . '</td> ';

					}

					?>


				</tr>
				<tr>
				  <th scope="row">Findrscore</th>
				  <?php
			foreach ($fs_list as $key => $fs) {
						echo ' <td>' . $fs . '</td>';

					}
					?>
				</tr>
				<tr>
				<?php
              /*   echo "feature_list";
                print_r($feature_list); */
                $all_feature_list=array();
					foreach ($feature_list as $key => $features_list) {
                        // var_dump($features_list);
                        if(is_array($features_list)){
                            foreach ($features_list as $list) {

                                $all_feature_list[] = $list;
    
                            }
                        }
						
					}

					$frequent_used = array_count_values($all_feature_list);
					arsort($frequent_used);

					$ab = 0;
					// foreach($feature_list as $key =>$features_list){

					foreach ($frequent_used as $k => $list) {
						// foreach($list as $k =>$list_11){
						// echo $list;
						echo '<tr><th>' . $k . '</th>';
						foreach ($feature_list as $j => $items) {
                            if(is_array($items)){
                                if (in_array($k, $items)) {

                                    echo "<td><i class='fa fa-check' aria-hidden='true' style='color: green;' ></i></td>";
                                } else {
                                    echo "<td><i class='fa fa-minus' aria-hidden='true' style='color: grey;' ></i></td>";
    
                                }
                            }
                            else{
                                echo "<td><i class='fa fa-minus' aria-hidden='true' style='color: grey;' ></i></td>";

                            }
							

						}

						echo "</tr>";

						$ab++;
						if ($ab == 10) {
							break;
						}

						// }
					}

					// }
					?>


				</tr>
			  </tbody>
			</table>
                                </div>
                            <?php }?>
                           
                            <div class="helpful">
                                <p>
                                    Was this helpful?
                                </p>
                                <ul>
                                    <li><?php if (function_exists('the_ratings')) {the_ratings();}?></li>
                                    <li class="pull-right">
                                        <p>
                                <span class="greycol">
                                    Last Updated:
                                </span>
                                            <?php echo get_post_modified_time('j M Y');
            ?>
                                        </p>
                                    </li>
                                </ul>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           
            <?php echo $this->list_compare_table($postarr); ?>

           

            <?php
            //  echo "<br>checkpoint2";
            //  print_r($compareItems);
if (!empty($compareItems) && count($compareItems) > 1) {
                echo ' <div class="comparison_fix_footer">
            <a href="javascript:;" class="compare_close"><i class="fa fa-times-circle"></i> </a>
            <div class="compare_items"> <div style="display:table" ><div style="display:table-row" ><div class="compare_text"  >Compare the top two solutions: </div>';
                foreach ($compareItems as $ind => $cmp) {
                    echo '<div style="display:table-cell; vertical-align:middle" ><span class="cp-item cp-item-foot-' . ($ind + 1) . '" title="' . get_the_title($cmp) . '">' . get_thumbnail_small($cmp, array(50, 50)) . ' </span></div>';


                    if ($ind < 1) {
                        echo '<div style="display:table-cell; vertical-align:middle" ><span class="cp-vs-inner2">vs</span> </div>';
                    }
                }

                echo '</div></div></div><div class="compare_btn"> <a href="' . generate_compare_link($compareItems) . '/" class="new-comparison-btn getbtn ls_referal_link" data-parameter="listid" data-rid="' . $list_id . '" data-id="' . $compareItems[0] . '" data-secondary="' . $compareItems[1] . '">Compare Now</a></div></div>';
            }?>
            <div id="reportModal" class="p-5 animated-modal" style="display: none;max-width:600px;">
                <button data-fancybox-close="" class="fancybox-close-small" title="Close"><svg viewBox="0 0 32 32"><path d="M10,10 L22,22 M22,10 L10,22"></path></svg></button>
                <form method="post" id="report-submit">
                    <input type="hidden" name="list_id" value="<?php echo $main_list_id; ?>">
                    <input type="hidden" name="list_item" id="report_list_item" value="">
                    <p class="report-head">
                        Report <span id="item_name"></span>

                    </p>
                    <div id="report-success"></div>
                    <div class="report-field">
                        <label>Reason</label>
                    <select name="reason" class="required" required>
                        <option value="">Select</option>
                    <option value="Irrelevant to list">Irrelevant to list</option>
                    <option value="Broken link">Broken link</option>
                    <option value="No longer available">No longer available</option>
                    <option value="Other">Other</option>
                    </select>
                        <div class="rederror"></div>

                    </div>
                    <div class="report-field">
                        <label>Comment</label>
                      <textarea name="comment"></textarea>

                    </div>
                    <div class="report-field">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>

                </form>


            </div>

            <?php
$html .= ob_get_contents();
            ob_end_clean();
            wp_reset_postdata();

            //var_dump($current_page+1 ); die;

        }

        ?>



        <?php

        // return "hello";
        // echo "get_list_html_complete";
        return $html;

    }

    public function update_vote()
    {
        $post_id = $_POST['post_id'];
        $post_parent_id = $_POST['post_parent_id'];
        $vote_type = $_POST['vote_type'];

        if (isset($_COOKIE["list_creator_post_vote_" . (int) $_POST["post_id"]])) {

            $voted = 1;
            $voted_type = $_COOKIE["list_creator_post_vote_" . (int) $_POST["post_id"]];

        }

        $total_votes = get_post_meta($post_id, 'votes_given', true);
        if (!isset($total_votes[$post_parent_id])) {
            $total_votes[$post_parent_id] = 0;
        }
        $votes_count = $total_votes[$post_parent_id];

        if ($voted == 1) {

            if ($_POST["vote_type"] == 'up') {

                if ($voted_type == 'up') {
                    $votes_count--;

                    setcookie("list_creator_post_vote_" . (int) $_POST["post_id"], "up", time() - 60 * 60 * 24, '/');
                } else {
                    $votes_count += 2;

                    setcookie("list_creator_post_vote_" . (int) $_POST["post_id"], "up", time() + 60 * 60 * 24, '/');
                }

            } else {

                if ($voted_type == 'down') {
                    $votes_count++;

                    setcookie("list_creator_post_vote_" . (int) $_POST["post_id"], "down", time() - 60 * 60 * 24, '/');
                } else {
                    $votes_count -= 2;

                    setcookie("list_creator_post_vote_" . (int) $_POST["post_id"], "down", time() + 60 * 60 * 24, '/');
                }

            }

        } else {

            if ($_POST["vote_type"] == 'up') {
                $votes_count++;
                setcookie("list_creator_post_vote_" . (int) $_POST["post_id"], "up", time() + 60 * 60 * 24, '/');
            } else {
                $votes_count--;
                setcookie("list_creator_post_vote_" . (int) $_POST["post_id"], "down", time() + 60 * 60 * 24, '/');
            }

        }
        $total_votes[$post_parent_id] = $votes_count;
        update_post_meta($post_id, 'votes_given', $total_votes);

        echo wp_json_encode(array('post_id' =>
            $post_id, 'votes' => $votes_count));
        die;
    }

    public function cmp($a, $b)
    {
        return $a->votes <= $b->votes;
        //  return strcmp($b->votes,$a->votes);
    }
    public function acme_post_exists($id)
    {
        return is_string(get_post_status($id));
    }

    // function also_mentioned_in() {
    //     if ( is_singular( 'list_items' ) ) {

    //         $itemiid = get_the_ID();
    //         if ( !empty($this->ranklist) && is_array($this->ranklist) ) {
    //             $listrankord = $this->ranklist;
    //             echo '<div><h3 id="awards">'.get_the_title().' is Featured In:</h3>';
    //             foreach ( $listrankord as $id => $rank ) {
    //                 $post_id = $id;
    //                 $rankhtm = '';
    //                 if($rank > 0){
    //                     $rankhtm = '<span class="rank-ts"><span class="rr-val">'.$rank.'</span> </span>';
    //                 }
    //                 $image1 = get_the_post_thumbnail_url($post_id, array( '230', '200' ));

    //                 echo '<div class="zombify_mentioned_in" data-mh="equal-height-mentioned">
    //                 <h4 data-mh="equal-height-mentioned-title"><a href="'.get_the_permalink( $post_id ).'">'.do_shortcode(get_the_title( $post_id )
    //                     ).'</a></h4>
    //                 <a href="'.get_the_permalink( $post_id ).'">
    //                 '.$rankhtm.'<img src="'.$image1.'" class="img-responsive sss" alt="'.get_the_title($post_id).'" >
    //                 </a>
    //                 </div>';
    //             }
    //             echo '</div>';
    //         }
    //     }
    // }

    public function get_alternate_items()
    {
        $review_id = get_the_ID();
        $html = '';
        $compObj = new Mv_List_Comparision(null,true);
        $lists = $compObj->most_compared($review_id, 20, true);

        // echo '<pre>';
        // var_dump($lists);
        if (!empty($lists)) {
            $html = '<div class="alternate-carousel"><h3 id="alternative">Alternatives to ' . get_the_title($review_id) . '</h3>';
            $html .= '<div class="see-more-btn" style=""><a href="' . get_the_permalink($review_id) . 'alternative/" class="ls_referal_link" data-parameter="itemid" data-id="' . $review_id . '">See More</a></div> <div class="clr"></div>';
            $html .= '<div class="full-width"><div class="flexslider carousel cr-alternate">
            <ul class="slides">';
            $ac = 0;
            foreach ($lists as $pid) {
                $images = get_the_post_thumbnail_url($pid, array(150, 150));?>

              <?php $html .= '<li><div class="cs-title single-line"><a href="' . get_the_permalink($pid) . '"> ' . get_the_title($pid) . '</a></div> <div class="cs-image"><a href="' . get_the_permalink($pid) . '"> <img src="' . $images . '" class="img-responsive sss" alt="' . get_the_title($pid) . '" ></a></div> </li>';

                $ac++;
            }
            $html .= '</ul></div></div></div>';
        }

        return $html;
    }

    public function add_shortcode_column($columns)
    {
        // unset($columns['author']);
        return array_merge($columns,
            array('shortcode' => __('Shortcode')));
    }
    public function shortcode_column($column, $post_id)
    {
        switch ($column) {
            case 'shortcode':
                echo "[show_list id=$post_id]";
                break;
        }

    }

    public function get_single_list_item_header($post_id)
    {
        $affiliate_url = $this->affiliate_url;
        $affiliate_button_text = $this->affiliate_button_text;
        $source_url = $this->source_url;
        $credit_text = $this->credit_text;
        $pricing_model = $this->pricing_model;
        $img = get_the_post_thumbnail($post_id, 'medium');
        $availability = get_post_meta($post_id, '_item_availbility', true);
        $coupon_availability = get_post_meta($post_id, 'coupons_list', true);
        $unique_slice = array_slice($this->ranklist, 0, 1);
        $fisrt = array_shift($unique_slice);
        if (!empty($fisrt)) {
            $friends_count = array_count_values($this->ranklist);
            $batchCount = $friends_count[$fisrt];
        }
        ob_start();
        ?>
        <div class="item-header-cnt"
            <?php
if (isset($_GET['listid']) && !empty($_GET['listid'])) {
            $list_id = $_GET['listid'];
            echo "data-zf-post-id=\"$post_id\" data-zf-post-parent-id=\"$list_id\" ";
        }
        ?>
       >
        <?php if ($availability == 'no') {?>
        <div class="notavailable-notice"><p><center><b>This product is no longer available.  <a href="<?php echo get_the_permalink($post_id) ?>alternative" class="alter-btn plain-link" data-parameter="itemid" data-id="<?php echo $post_id ?>" >Check out top</a> that works similar or better!</b></center></p></div>
        <?php }?>
            <div class="title-rating-cont" id="review-title">
                <?php if (!empty($batchCount)): ?>
                    <div class="batch-id">
                        <a href=""><div class="batch-data"><span class="rank-val">#<?php echo $fisrt ?></span><span class="rank-in">in</span><span class="rank-items"><?php echo $batchCount ?></span></div>
                            <div class="batch-image"></div></a>

                    </div>
                <?php endif;?>
                <div class="title-container" >
                    <h1 class="entry-title1"><span itemprop="name"><?php echo get_the_title($post_id) . ":Pricing and Features"; ?></span></h1>
                    <div class="pricing-mdl">Pricing Model: <?php echo str_replace('_', ' ', implode(', ', array_map('ucfirst', $pricing_model))); ?></div>

                </div>
                <div class="clr"></div>
                <div class="title-other-container">

                    <div class="avg-rating-cont">
                        <div class="editor-rating-cont">
                            Editor Rating
                            <br/>
                            <?php //$users_rating = RWP_API::get_reviews_box_users_rating($post_id,-1);
        //echo do_shortcode( '[rwp-users-rating-stars  id=0 size=20 post='.$post_id.']' );
        ?><?php echo do_shortcode('[rwp_reviewer_rating_stars id=0 size=20 post=' . $post_id . ']'); ?>

                        </div>
                        <div class="user-rating-cont">
                            User's Rating
                            <br/>
                            <?php
echo do_shortcode('[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=20 post=' . $post_id . ']');
        // $user_score = get_post_meta( $post_id, 'user_rating_custom', true );
        // if ( $user_score ) {
        //     $user_score = round( $user_score );
        // }else {
        //     $user_score = 0;
        // }
        // $obj = new RWP_Rating_Stars_Shortcode();
        // echo $obj->get_stars( $user_score, '20', '5' ) ;
         ?>


                        </div>
                        <div class="clr"></div>
                    </div>
                    <div class="item-links">
                        <?php

        if (!empty($affiliate_url)) {
            if (substr_count($affiliate_url, "?") >= 1) {
                $affiliate_url .= "&utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=" . $post_id;
            } else {
                $affiliate_url .= "?utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=" . $post_id;
            }

        }

        $afflink = empty($affiliate_url) ? $source_url : $affiliate_url;
        $btntext =$this->btntext;
        ?>
                         <?php if ($availability == 'no') {?>
                            <a href="<?php echo get_the_permalink($post_id) ?>alternative/" class="alter-btn head-links" data-parameter="itemid" data-id="<?php echo $post_id ?>" >Alernative</a>

						<?php } else {?><a class="mes-lc-li-down aff-link head-links" href="<?php echo $this->afflink; ?>" rel="nofollow" target="_blank"><?php echo $this->btntext; ?></a><?php }?><a id="go-to-user-comments" class="custom-link head-links" href="javascript:void(0)" rel="nofollow" target="_blank">Write a Review</a><?php if (!empty($coupon_availability)) {?><a href="<?php echo get_the_permalink($post_id) ?>coupon/" class="alter-btn head-links" data-parameter="itemid" data-id="<?php echo $post_id ?>" >Coupon</a><?php }?>
                    </div>
                </div>

            </div>

            <div class="clr"></div>
        </div>
        <?php
$html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    /********************************************************Sidebar function******************************************************** */
    public function get_item_sidebar($post_id)
    {

        // print_r($this->array_head);
        $rating = get_overall_combined_rating($post_id);
        $overall = 0;
        $votes = 0;
        if(isset($rating['list']['overallrating']['score']))
		{
            $overall = $rating['list']['overallrating']['score'];
            $votes = $rating['count'];
        }
        $rating_item = $this->reviewClass->get_stars( $overall, 20, 5 );//do_shortcode('[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=20 post=' . $post_id . ']');
        $sidebar = '<div class ="main_div_sidebar"><div class="right_sidebar_style">';
        $sidebar .= '<div class ="main_container_sidebar">';
        $sidebar .= '<div class="it-ot-title-new sidebar-content sidebar-item-title "><span itemprop="name"> In this review</span></div>';
        $sidebar .= '<ul class = "sidebar-content-list sidebar-content">';
     
         
        foreach ($this->array_head as $hkey => $head) {
            $sidebar .= '<li><i class="fa fa-square"></i><a href="#' . $hkey . '"> ' . $head . '</a></li>';
            $votes = $rating['count'];
        }
        $sidebar .= '</ul></div>';
        $sidebar .= '<div class="sidebar-section-2">';
        $sidebar .= '<p class="visit_btn_sec">';
        if($this->availability == 'no') { 
            $sidebar .=  '<a style="color: white;" href="'. get_the_permalink( $post_id ).'alternative/" class="alter-btn head-links" data-parameter="itemid" data-id="'. $post_id .'" >Alernative</a>';
          
           } else { 
               $sidebar.='<a style="
               color: white;
           " class="mes-lc-li-down head-links" href="'. $this->afflink.'" rel="nofollow" target="_blank">'.$this->btntext.'</a>'; 
          } 
        
        $sidebar.='</p>';
        $sidebar .= '<div class= "reviw-sec" <a id="go-to-user-comments" class="custom-link head-links" href="javascript:void(0)" rel="nofollow" target="_blank">Write a Review</a>';
        // $sidebar .=' | ';// See Coupon';

        $coupon_availability = get_post_meta($post_id, 'coupons_list', true);

        if (!empty($coupon_availability)) {
        $sidebar .= ' | <a href="' . get_the_permalink($post_id) . 'coupon/" class="alter-btn head-links" data-parameter="itemid" data-id="' . $post_id . '" >see coupons</a>';
         }
        $sidebar .= '<div class="title-review" style="width: 100%;margin: 0 auto;"> ' . $rating_item . '( '.$votes.' User Rating)</div>';
        $sidebar .= '</div></div></div></div>';
        $sidebar .= ' <script>
        var s = jQuery(".right_sidebar_style");
        var pos = s.position();
        jQuery(window).scroll(function() {
        var windowpos = jQuery(window).scrollTop();
        if (windowpos >= pos.top & windowpos >= 1000) {
        s.addClass("stick");
        } else {
        s.removeClass("stick");
        }
        });
        </script>';
        return $sidebar;

    }

    /********************************************************Sidebar function******************************************************** */
    public function get_award_list($post_id)
    {
        $post_id = get_the_id();

        $listrankord = $this->ranklist;
        $award_list=array();
        if (!empty($listrankord)) {
            foreach ($listrankord as $id => $rank) {

                // echo "idd";
                // echo $id;

                $all_items = get_field("list_items", $id, false); //count( $listrankord );

                // echo " all_items ";
                // print_r($all_items);

                $all_item_count = count($all_items);

                $all_award_list[$id][$all_item_count] = $rank;
                $ranked_list = ($rank / $all_item_count) * 100;
                // echo "ranked list";
                // print_r($ranked_list);
                if ($ranked_list <= 20) {
                    $award_list[$id] = $rank;
                    // echo "awaard list";
                    // print_r($award_list);
                }
                $all_awardcount[$id] = count($all_items);

            }

// $item_count = implode('',$all_awardcount);
            // echo "awaard list";/

            $total_award = $this->total_award = count($award_list);
            // echo "loine 1600 lines";
            // echo $total_award;

            // $i=1;
            foreach ($all_award_list as $key => $item_ind_list) {
                $award_items[] = '<a href =' . get_permalink($key) . '><h4 style=\'color: #fff;\'>' . get_the_title($key) . '</h4></a>';
                foreach ($item_ind_list as $p => $list) {
                    $award_items[] = "<p style='color: #40ca1a;'>&nbsp; &nbsp; # " . $list . " position out of " . $p . " items</p>";
                    // print_R($list);

                }

                //  $i++;
                //  if($i>=20)
                //    break;
            }

        }

        // $all_item_count = count( $all_items ); // count items from all_items
        $award_list = implode(" ", $award_items);
        // $award_position_text = implode(" ",$award_text);
        //  print_r($award_list);
        //  echo "count";
        //  echo count($award_items);
        //  var_dump( $award_items);
        $ranked_result = '<div id="awards" class = "ranking_sec">';
        $ranked_result .= '<div class = "row ranking_sec">';
        $ranked_result .= '<div class = "col-sm-6 award_text" style="margin: auto;width: 60%;font-size: 23px;
        ">';
        if (!empty($total_award)) {
            $ranked_result .= '<img src="' . esc_url(plugins_url('image/leaf4a.png', dirname(__FILE__))) . '" class="award_leaf">' . $total_award . '  Awards<img src="' . esc_url(plugins_url('image/leaf4b.png', dirname(__FILE__))) . '" class="award_leaf">';
        }
        $ranked_result .= '</div>';
        $ranked_result .= '<div class = "col-sm-6"> ';

        //col-sm - 4 close
        if (count($award_items) > 10) {
            $style_list = 'style="
                    height: 648px;
                    overflow: auto;


                "';
        } else {
            $style_list = 'style="
                    height: auto;
                    overflow: visible;


                "';
        }

        $ranked_result .= '<div class = "dd" ' . $style_list . '> ';

        $ranked_result .= $award_list;
        $ranked_result .= '</div>'; //colsn-6 close

        $ranked_result .= '</div>'; //row ranking

        $ranked_result .= '</div>'; //item-ranking-position
        $ranked_result .= '  </div>';

        return $ranked_result;
    }

    public function keyfeature_pricing($post_id)
    {
        $post_id = get_the_id();
        $key_feature_result = '<div class = "item-overview-content">';
        $key_feature_result .= '<div class = "item-key-feature item-sec-div" id="key-feature">';
        $key_feature_result .= '<h3>Key Features </h3>';
        $item_rating_feature = get_or_create_feature_ratings($post_id);//get_field('features_list_ratings', $post_id);
        if(is_array($item_rating_feature)){
        foreach ($item_rating_feature as $f => $r) {
            if(trim($f)==''){
                $item_rating_feature = get_or_create_feature_ratings($post_id,true);
            }
        }
        /* echo "item rating feature";
        var_dump($item_rating_feature);
        echo "end item rating feature"; */
        $all_feature_name = $all_feature_score = array();
        if(is_array($item_rating_feature)){
            foreach ($item_rating_feature as $key => $item_key_feature_rat) {
                $all_feature_score[] = $item_key_feature_rat['total_score'];
                $all_feature_name[] = $key;

                $feature_list_three[$key] = $item_key_feature_rat['average'];

            }
        }
        $item_title = get_the_title($post_id);
        //   echo "post id ".$post_id;
        $arrToPass = array(
            array(
                'label' =>  $item_title,
                'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                'borderColor' => 'rgb(54, 162, 235)',
                'data' => $all_feature_score,
            ));
            // $post_id = get_the_id();

            // $item_feature_lists = get_field('features_list', $post_id);
            // $item_rating_feature = get_field('features_list_ratings', $post_id);
            $i=0;
        //    echo "industry items";
        //     print_r($this->industry_items);
            $item_feature_lists = array();
            foreach($this->industry_items as $key=>$id){
                $this_item_features = get_field('features_list', $id);

               

                if(!is_array($this_item_features)){
                    // echo "fine";
                    // $this_item_features=array();
                    $this_item_features = array();
                }
                // echo "features $id";
                // print_r($this_item_features);
                $item_feature_lists = array_unique(array_merge($item_feature_lists,$this_item_features));
                // echo "item f l";
            // print_r($item_feature_lists);
                $features_ids['id'.$i] = $id;
                $i++;
                if(count($item_feature_lists) > 40){ // page crashes error 500 memory exhausted
                    break;
                }
            }
            // $item_feature_lists = array_unique($item_feature_lists);
           /*  $features_ids = array(
                'id0' => $post_id,
            ); */
           /*  echo "item f l";
            print_r($item_feature_lists);
            echo "f ids";
            print_r($features_ids); */
          

        $sorted_feature_list = sort_features($item_feature_lists, $features_ids);
      /*   echo "sfl";
        print_r( $sorted_feature_list);
        echo "sfl"; */
        $i = 0;
        foreach ($sorted_feature_list as $vote => $feature) {
            $three[] = $feature['feature'];

            $i++;
            if ($i == 3) {
                break;
            }
        }
      
       $featuress= implode(",", $three);

        $key_feature_result .= '  <div>
        When looking at a solution like '. $item_title.', users on our platform are wanting these top three
        features  '.$featuress.'.
        Below you will find some of the top feature highlights of '. $item_title.' ranked and rated by your peers.

                    
                    <!-- <canvas id="keyfeature_chart" width="600" height="400"></canvas> -->
                   <canvas id="marksChart" width="600" height="400"></canvas>
                    <script>
                        var marksCanvas = document.getElementById("marksChart");
                         var marksData = {
                            labels: ' . json_encode($all_feature_name) . ',
                            datasets:  ' . json_encode($arrToPass) . '
                        };
                        var radarChart = new Chart(marksCanvas, {
                          type: "radar",
                          data: marksData
                        });
                    </script>
                </div>';
        // echo "post id 2242 ".$post_id;
        $key_feature_result .= '  <div class="single-list-data zf-item-vote" data-zf-post-id="' . $post_id . '"> ';
        $key_feature_result .= ' <div id="app">
            <v-app>';?>
        	<?php
if (isset($_SESSION['act'])) {
            $acts = $_SESSION['act'];
        }
        if (isset($_SESSION['feature_btn_actions'])) {
            $feature_btn_name = $_SESSION['feature_btn_actions'];
        } else {
            $feature_btn_name = array();
        }
        if (isset($_SESSION['post_ids'])) {
            $session_post_ids = $_SESSION['post_ids'];
        } else {
            $session_post_ids = array();
        }
        $k = 1;
        $l = 0;?>
             <?php
$key_feature_result .= '<div class="col-sm-12">';
if(is_array($item_rating_feature)){
    

        foreach ($item_rating_feature as $f => $r) {
            if(trim($f)!=''){
                $key_feature_result .= '<div class="col-sm-5 featurre2" style=" margin: 0 5% 3% 0; background-color:#fff;  border: 1px solid #e6e6e6;">';
                $f_ = validate_var_name($f);
                $key_feature_result .= '<b>  ' . $f . ' <span> <i class="fa fa-question-circle qmark" style=" color:grey; margin-top: 2px;"></i></span> </b>';
                $key_feature_result .= '<div class="info hidden">This information is based on what our users have shared with us, in some cases, the solution in question could update its feature list which may not reflect here immediately.</div>';
                $key_feature_result .= '<v-slider
                v-on:click="greet"
                v-model="' . $f_ . '.val"
                :color="' . $f_ . '.color"
                :thumb-size="15"
                data-obj="' . $f . '"
                data-validated-obj="' . $f_ . '"
                step="1"
                thumb-label="always"
                ticks
                tick-size="10"
                min=1
                max=10
                ></v-slider>';
                $key_feature_result .= '<span class="status">{{ ' . $f_ . '.status}}</span> <br>';
                $key_feature_result .= '<button class="relevent" id="relavant_' . $k . '" value="' . $f . '" style="color: gray;" ';
                if (in_array("relavant_$k", $feature_btn_name) && in_array($post_id, $session_post_ids)) {
                    $key_feature_result .= ' disabled style="color: rgb(190, 190, 190);"';
                }
                $key_feature_result .= '><i class="fa fa-caret-up" style="padding-top:2px;"></i> ';
                $key_feature_result .= ' Relevent</button>  <button class="irrelevent" id="irrelavant_' . $k . '" value="' . $f . ' "';
                $key_feature_result .= ' style="color: gray;" ';
                if (in_array("irrelavant_$k.'", $feature_btn_name) && in_array($post_id, $session_post_ids)) {
                    $key_feature_result .= 'disabled style="color: rgb(190, 190, 190);" ';

                }
                $key_feature_result .= '> <i class="fa fa-caret-down" style="padding-top:3px;"></i> Irrelevent</button>';
                $key_feature_result .= '  <input type="hidden" class="f_id" value="' . htmlspecialchars(json_encode($post_id)) . '"/> ';
                $key_feature_result .= '<input type="hidden" class="f_name" value="' . $f_ . '"/> ';
                $key_feature_result .= '</div>';
            }
            
        }
    }
        $key_feature_result .= '</div>';
        $key_feature_result .= '   </v-app>
                </div>';
        ?>
            <?php
// echo "2item rating feature";
        // var_dump($item_rating_feature);
        // echo "2end item rating feature";
        $key_feature_result .= '<script>

                 var vm = new Vue({
                 el: \'#app\',
                 vuetify: new Vuetify(),
                 data: {';
                    if(is_array($item_rating_feature)){
        foreach ($item_rating_feature as $f => $r) {
            if(trim($f) != ''){
                $label = $f;

                $f_ = validate_var_name($f); //str_replace(" ","_",$f);
                $score = $r['average'];
                if(!is_numeric($score)){
                    $score = 0;
                }
                //    $item_result .= (\''.$f_.'\' : {label : \''.$label.'\', val : '.$score.', color: \'green\' ,status:\'\'},');
            
                $key_feature_result .= ('' . $f_ . ' : {label : \'' . $label . '\', val : ' . $score . ', color: \'green\' ,status:\'\'},');

            }

        }
    }
        $key_feature_result .= '},
                   methods: {
                   greet: function (event) {
                   var input = event.target.closest(\'.v-input__control\').querySelectorAll(\'input\')[0];//event.target.querySelectorAll(\'input\');
                 /*   if(input.length === 0){
                        alert(\'Failed! Please try again\');
                        return false;
                    } */
                    var feature_name = input.getAttribute("data-obj");//(jQuery(event.target).find(\'input\')[0]).getAttribute("data-obj");
                    var feature_name_validated = input.getAttribute("data-validated-obj");
                    //(jQuery(event.target).find(\'input\')[0]).getAttribute("data-validated-obj");
                    if(feature_name_validated === null || feature_name ===null){
                        (vm._data[feature_name_validated]).status = \'Failed! Please try again\';
                    }
                    (vm._data[feature_name_validated]).color = \'yellow\';
                    (vm._data[feature_name_validated]).status = \'Please wait....\';
                    var vote= (vm._data[feature_name_validated]).val;';

        $key_feature_result .= '  var zf_post_id = document.querySelectorAll(\'.zf-item-vote\')[0].getAttribute("data-zf-post-id");
            console.log(zf_post_id);
            // var zf_post_parent_id = jQuery(this).closest(\'.zf-item-vote\').attr("data-zf-post-parent-id");
            console.log("before ajax vote up");
            console.log({zf_post_id},{feature_name},{vote});
            jQuery.ajax({
           url: \'' . admin_url('admin-ajax.php') . '\',
           type: \'POST\',
           data: {post_id: zf_post_id, feature_name : feature_name, vote_size : vote, action: \'mes-lc-feature-rate\'},
           dataType: \'json\',
           success: function (data) {
               console.log(data);
               (vm._data[feature_name_validated]).val = data[\'rating\'];
               (vm._data[feature_name_validated]).color = \'green\';
               (vm._data[feature_name_validated]).status = \'Thanks for voting!\';
               console.log("success vote up");
               // if (data.voted) setVoteCount(data, true);
               jQuery(".zf-vote_count[data-zf-post-id=\'"+data.post_id+"\']").closest(\'.zf-item-vote\').removeClass(\'zf-loading\');
                }
                    });
                }
                }
                });  </script>';

        $key_feature_result .= '<script>
               
            jQuery(\'.qmark\').mouseenter(function(){

            jQuery(this).closest(".featurre2").find(\'.info\').removeClass(\'hidden\');
            })
            jQuery(\'.qmark\').mouseleave(function(){
                jQuery(this).closest(".featurre2").find(\'.info\').addClass(\'hidden\');
            })</script>';
        $key_feature_result .= '<script> jQuery(\'.relevent\').click(function(){
            jQuery(this).css(\'color\',\'rgb(190, 190, 190)\');
            var xx = jQuery(this).attr("value");
            var idss = jQuery(\'.f_id\').val();
            var obj = JSON.parse(idss);
            var arr = [];
                i = 0;
                jQuery.each(obj, function (key, data1) {
                arr[i++]  =data1;
                });
            var f_name = jQuery(\'.f_name\').val();
            var btn_id = this.id;
            var data1 = {
                \'action\': \'my_action\',
                \'post_ids\': arr,
                \'feature_name\': xx,
                \'votes\': 1,
                \'act\' : \'relevent\',
                \'btn_id\': btn_id

            };';

        $key_feature_result .= ' var ajaxurl = \'' . admin_url('admin-ajax.php') . ' \'';
        $key_feature_result .= '	jQuery.post(ajaxurl, data1, function(response) {
            });
            var btn_id = \'#ir\'+btn_id;
            jQuery(this).prop(\'disabled\',true);
            jQuery(btn_id).prop(\'disabled\',false);
        });';

        $key_feature_result .= '  jQuery(\'.irrelevent\').click(function(){
            jQuery(this).css(\'color\',\'rgb(190, 190, 190)\');
            var xx = jQuery(this).attr("value");
            var idss = jQuery(\'.f_id\').val();
            var obj = JSON.parse(idss);
            var arr = [];
                i = 0;
                jQuery.each(obj, function (key, data1) {
                arr[i++]  =data1;
                });
            var f_name = jQuery(\'.f_name\').val();
            var btn_id = this.id;
            var data1 = {
                \'action\': \'my_action\',
			\'post_ids\': arr,
            \'feature_name\': xx,
            \'votes\': 1,
            \'act\' : \'irrelevent\',
            \'btn_id\': btn_id

        };';

        $key_feature_result .= ' var ajaxurl = \'' . admin_url('admin-ajax.php') . '\';';

        $key_feature_result .= ' 	jQuery.post(ajaxurl, data1, function(response) {
                });

                var itemId = btn_id.substring(2, btn_id.length);
                var btn_id = \'#\'+ itemId;
                jQuery(this).prop(\'disabled\',true);
                jQuery(btn_id).prop(\'disabled\',false);
            });';
        $key_feature_result .= ' </script>';
        $key_feature_result .= '</div>'; //item-ranking-position
        // $ranked_result .= '  </div>';
        
        }else{
            $key_feature_result.="No features listed!";
        }
        $key_feature_result .= '</div>'; //item-key-feature
        //integrate_with
        $all_integrated_list = get_field('integrate_with_item', $post_id, false);
        $all_ind_int = array();
        foreach($this->industry_items as $ii){
            $this_integrated_list = get_field('integrate_with_item', $ii, false);  
           /*  echo " $ii ";
            print_r($this_integrated_list);
            echo "count til";

            echo count($this_integrated_list); */
            if(is_array($this_integrated_list)){
                
                $countIndInt += count($this_integrated_list);   
                foreach($this_integrated_list as $til){
                    $all_ind_int[$til]++; 
                }  
                    
            }
        }
        /* echo "all ind int before";
        print_r($all_ind_int); */
        if(is_array($all_ind_int)){
            arsort($all_ind_int);
        }
        
  /*       echo "all ind int";
        print_r($all_ind_int);
        echo "countindint is : ".$countIndInt; */
        if (!empty($all_integrated_list)) {
            $key_feature_result .= '<div class = "item-integrateswell-with item-sec-div" id="integration">';
            $key_feature_result .= '<h3> Integrates well with  </h3>';

            // $all_integrated_list =   $this->all_integrateddata;
            // echo "integrate data";
            // print_r( $all_integrated_list);

            // foreach ($all_integrated_list as $integrated_item11) {


                // $all_integrateddata = get_field('integrate_with_item', $integrated_item, false);
            //     $total_integrate_list[] = $integrated_item11;
            // }
            $i = 0;
            if(is_array($all_ind_int)){
                foreach ($all_ind_int as $intergate_single_item=>$count) {
                    /*  echo "int single item";
                     echo $intergate_single_item;
                     echo get_the_title($intergate_single_item); */
                     $items_names[] = "<a href='".get_the_permalink($intergate_single_item)."'>".get_the_title($intergate_single_item)."</a>";
                   /*   echo "item names in the ways";
                     print_r($item_names); */
                     $i++;
                     if ($i == 3) {
                         break;
                     }
         
                 }
            }
            

            /* echo "item names";
            print_r($item_names); */


            $items_names_3 = implode(", ", $items_names);
            $integrate_Avg = round($countIndInt / count($this->industry_items), 2);
             if (!empty($all_integrated_list)) {
                 $integrate_text = "Looking at the data collected on our platform, the average amount of integrations confirmed by similar solutions is $integrate_Avg.
             The top 3 integrations are $items_names_3 .  ";
             } else {
                 $integrate_text = "This item is not integrate with anyone";
     
             }
             
             $key_feature_result .= '<div class="col-sm-12">'.$integrate_text.'</div>';

       
            // print_r($all_integrated_list);
            // echo"industery end";
            $key_feature_result .= '<div class="roiiw"><div class="col-sm-12 more_data">';
           
           
            foreach ($all_integrated_list as $key=> $idd) {
            //  print_R( array_unique($item)) ;
           // foreach($item as $idd){
            //   echo $key;
                    $integarte_item_name = get_the_title($idd);
                    $integarte_item_img = get_the_post_thumbnail($idd, array(100, 100));
                   $class1="";
                   $class2="";
                    if($key < 4){
                        $class1="";
                        $class2="show";
                            } else{
                        $class1="hide_li_1";
                        $class2="show";
 
                    }
                    $key_feature_result .= '<div class="col-sm-6 '.$class1.'" > <a href="'.get_the_permalink($idd).'"><div class="integrate_img">';
                    $key_feature_result .= '<div class="'.$class2.'" >'.$integarte_item_img;
                    $key_feature_result .= ' </div >';
                    $key_feature_result .= ' <div class="integrate_name">';
                    $key_feature_result .= "<strong><span>" . $integarte_item_name . "</strong></span></div></a>";
           
                   
                    $key_feature_result .= '</div> </div>';




              


                


                    }
    
                // }
                // $key_feature_result .= ''; 
            
       
          
  
            $key_feature_result .= '</div >
            

       </div>';
       if(count($all_integrated_list) > 4){
        $key_feature_result .= '<h5 style="font-weight:600; cursor: pointer;" id="seeMore_1">See More</h5>';
       }

    //    $key_feature_result .= '<div class="col-sm-12">'.$integrate_text.'</div>';
            $key_feature_result .= '</div>'; //item-integrateswell with"
        }
        $key_feature_result.='<script>
        jQuery("#seeMore_1").click(function()
        {
            jQuery(".hide_li_1").toggle();
        });
        
    
    </script>
';

        //pricing result

        // <!-- /************************************************************chart******************************************************************* */ -->

        // print_r($result);
        // <!-- /************************************************************chart******************************************************************* */ -->
        $key_feature_result .= '<div class="pricing-item-new left-content-box style="
        background: white;"><h3 id="pricing"><div class="pricing-title left-title"><span>' . get_the_title($post_id) . ' Pricing Overview</span></div></h3> ';
        $key_feature_result .= "<ul class='pricing-details'>";
        if (!empty($this->price_starting_from)) {
            $new_plan = $this->plan;
            $key_feature_result .= "<li itemprop='offers' itemscope itemtype='http://schema.org/Offer'><label>Starting From:</label><span itemprop='priceCurrency' content='USD'>$</span><span itemprop='price'>" . preg_replace("/[^0-9.]/", "", $this->price_starting_from) . "</span><span itemprop='price_plan'>&nbsp;/&nbsp;" . $new_plan . "</span></li>";

        }
        $key_feature_result .= "<li><label>Pricing Model:</label>" . str_replace('_', ' ', implode(', ', array_map('ucfirst', $this->pricing_model))) . "</li>";
        if (!empty($this->free_trial)) {
            $card = 'No Credit Card required';
            if ($this->free_trial_card) {
                $card = 'Credit Card required';
            }
            $key_feature_result .= "<li><label>Free Trial:</label>Available ($card)</li>";
        }
        if (!empty($this->additional_price_info)) {
            $key_feature_result .= "<li>$this->additional_price_info</li>";
        }

        $couponlist = get_post_meta($post_id, 'coupons_list', true);

        $selected_categories = wp_get_post_terms($post_id, 'list_categories', array("fields" => "all"));
        $finally_selected_categories = '';
        foreach ($selected_categories as $singleselected_categories) {
            $finally_selected_categories = $singleselected_categories->slug;
            break;
        }

       
        if (!empty($couponlist)) {
            $offer_list = '<a href=' . get_permalink($post_id) . 'coupon>See ' . get_the_title($post_id) . ' offers</a>';
        } else {
            $offer_list = '<a href=' . home_url() . '/deals/?cat=' . $finally_selected_categories . '>View offers on similar solutions</a>';
        }
        $key_feature_result .= "<li><label>Promotional Offer: " . $offer_list . "</label>";

        $key_feature_result .= '</ul></div>';

        $key_feature_result .= '</div>'; //item-pricing"

        return $key_feature_result;
    }

    public function pricing_chart($post_id)
    {

        /*   $list_item = get_field('add_to_list',$post_id,false);
        foreach($list_item as $key => $lists){
        $post_ids = get_field('list_items' ,$lists);
        foreach($post_ids as $post_id_item){
        $all_item_id[] = $post_id_item->ID;
        }
        } */
        $all_item_id = $this->industry_items;
/*     echo "all item id pricing chart";
print_r($all_item_id);
echo "all item id pricing chart end"; */

        foreach ($all_item_id as $all_items) {
            $pricing_model_check[$all_items] = get_field('pricing_model', $all_items);
            $item_coupon_all[$all_items] = get_post_meta($all_items, 'coupons_list', true);
            $free_trial_all[$all_items] = get_field('free_trial', $all_items);
            // $rating[] = get_overall_combined_rating($all_items); //Overall combined rating
            $all_support = get_overall_combined_rating($all_items);
            if(isset($all_support['list']['customersupport']['score'])){
                $all_support_score[$all_items] = $all_support['list']['customersupport']['score'] * 20;
            }
            

            //  print_R($all_support);
        }

        $this->all_support_score_avg = array_sum($all_support_score) / count($all_item_id);

        $this->all_support_score_per = array_sum($all_support_score) * 100 / count($all_item_id);
        $free=0;
        foreach ($free_trial_all as $key => $free_trial) {
            if ($free_trial == 1) {
                $free += $free_trial;

            }
            // print_r($key);
        }
        $free_trail_percentage = $free * 100 / count($all_item_id);
        $coupon_filter = array_filter($item_coupon_all);
        $all_list=array();
        foreach ($coupon_filter as $key => $coupon_one) {

            foreach ($coupon_one as $single_coupon) {
                //    print_r($single_coupon);
                $all_list[] = $single_coupon;
            }

        }

        $coupn_count = count($all_list);
        $coupon_all_list = $coupn_count * 100 / count($all_item_id); //coupon percentage
        foreach ($pricing_model_check as $key => $price) {
            foreach ($price as $pric_list) {
                $list_price[] = $pric_list;
            }

        }

       
        $list_item_count = array_count_values($list_price);
/*         echo "list item count";
        print_r($list_item_count); */
        // $one_time_lince = $list_item_count['one_time_license'];
        $freemium = 0;
        $subscription = 0;
        $one_time_license = 0;
        $open_source = 0;
        
        if(isset($list_item_count['freemium'])){
            $freemium = $list_item_count['freemium'];
        }
        if(isset($list_item_count['subscription'])){
            $subscription = $list_item_count['subscription'];
        }
        if(isset($list_item_count['one_time_license'])){
            $one_time_license = $list_item_count['one_time_license'];
        }
        if(isset($list_item_count['open_source'])){
            $open_source = $list_item_count['open_source'];
        }
        $total_models = $freemium + $subscription + $one_time_license + $open_source;
        // $license = $one_time_lince * 100;
        // $license_percentage = round($license / count($all_item_id));
        $freemium_list = $freemium * 100;
        $freemium_percentage = round($freemium_list / $total_models);
        $subscription_list = $subscription * 100;
        $subscription_percentage = round($subscription_list / $total_models);
        $one_time_license_list = $one_time_license * 100;
        $one_time_license_percentage = round($one_time_license_list / $total_models);
        $open_source_list = $open_source * 100;
        $open_source_percentage = round($open_source_list / $total_models);
        if(($open_source_percentage + $one_time_license_percentage + $subscription_percentage + $freemium_percentage) > 100){
            $open_source_percentage--;
            if(($open_source_percentage + $one_time_license_percentage + $subscription_percentage + $freemium_percentage) > 100){
                $one_time_license_percentage--;
                if(($open_source_percentage + $one_time_license_percentage + $subscription_percentage + $freemium_percentage) > 100){
                    $subscription_percentage--;
                    if(($open_source_percentage + $one_time_license_percentage + $subscription_percentage + $freemium_percentage) > 100){
                        $freemium_percentage--;
                    }
                }
            }
               
        }
        $post_id = get_the_id();
        // $compObj = new Mv_List_Comparision(null,true);
        $comared_data = $this->industry_items;
        $item_price = get_field('price_starting_from', $post_id);
        $itemfs = get_or_calc_fs_individual($post_id);

        foreach ($comared_data as $post_idss) {
            $all_items_fs["_" . $post_idss] = get_or_calc_fs_individual($post_idss);
            $all_price = get_field('price_starting_from', $post_idss);
            $all_price_trim["_" . $post_idss] = intval($all_price);
            $ids_title["_" . $post_idss] = get_the_title($post_idss);
        }
        // print_R( $all_price_trim);
        // echo "count".count($comared_data);

        $avg_price = array_sum($all_price_trim) / count($all_price_trim);
        // echo "all fs avg ".$this->all_fs_avg;
        $fs_result='';
        if ($itemfs > $this->all_fs_avg) {

            $fs_result = 'that been said users are loving it';
        }

        if ($item_price > $avg_price) {

            $show_data = '' . get_the_title($post_id) . ' is on the higher end ' . $fs_result . ' ';
        } else {

            $show_data = '' . get_the_title($post_id) . ' is on the lower end so price wise it’s a great offer. ';
        }

        $pricing_result = '<div class = "item-pricing item-sec-div " id="price" style="    position: relative; background:#2898f7;    overflow: hidden;">';
        $pricing_result .= '<div class="col-sm-12 pie_outer" > ';
        $pricing_result .= '<div class="col-sm-12 col-md-6"> ';
        // $one_time_license_percentage = 25;
        $colorOfOneTimePercentage = "LIGHTSALMON";
        $one_time_license_percentage_title = "One-time License";
        $p2show_freemium = $freemium_percentage;
        $freemium_percentage = $one_time_license_percentage + $p2show_freemium;
        $colorOfFreemiumPercentage = "indianred";
        $freemium_title = "Freemium";
        $p2show_subscription = $subscription_percentage;
        $subscription_percentage = $freemium_percentage + $p2show_subscription;
        $colorOfSubscriptionPercentage = "pink";
        $subscription_percentage_title = "Subscription";
        $p2show_open_source = $open_source_percentage;
        $open_source_percentage = $subscription_percentage + $p2show_open_source;
        $colorOfOpenSourcePercentage = "olive";
        $open_source_percentage_title = "Open Source";
       
        $PiR2 = 276.32;
        $strokeDashSubscription = ($subscription_percentage*$PiR2/100)." $PiR2";
        $strokeDashFreemium = ($freemium_percentage*$PiR2/100)." $PiR2";
        $strokeDashOneTime = ($one_time_license_percentage*$PiR2/100)." $PiR2";
        $strokeDashOpenSource = ($open_source_percentage*$PiR2/100)." $PiR2";
        $strokeDashCoupon = $coupon_all_list * 357.9 / 100;
        $strokeDashFreeTrial = $free_trail_percentage * 414.48 / 100;
        $pricing_result .= '<div id="circles" >
            <div class="viewport inview">
            <svg width="100%" height="666px" viewBox="217 116 554 666" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">';
        $pricing_result .= '<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(218.000000, 116.000000)"><g><g transform="translate(336.000000, 98.000000)"></g></g><g transform="translate(250.000000, 169.000000)" stroke="#FFFFFF" stroke-width="2"><path d="M0,0 L101,67"></path><path d="M6,224 L107,291" transform="translate(56.500000, 257.500000) scale(-1, 1) translate(-56.500000, -257.500000) "></path>';
        $pricing_result .= '</g><text font-size="20" line-spacing="25" fill="#FFFFFF"><tspan x="100" y="19">Pricing Model</tspan></text><g transform="translate(89.000000, 32.000000)"><g><circle r="88" cx="88" cy="88" fill="#eee" />';
        $pricing_result .= '<circle r="44" cx="88" cy="88" fill="transparent"stroke="' . $colorOfOpenSourcePercentage . '" stroke-width="88" stroke-dasharray="'.$strokeDashOpenSource.'" transform="rotate(-90) translate(-176)" />';
        $pricing_result .= '<rect x="200" y="20" width="20" height="20" rx="5" fill="' . $colorOfOpenSourcePercentage . '" /><text class="detail-text" font-size="20" line-spacing="20" fill="#5BD49C"><tspan x="230" y="40">' . $open_source_percentage_title . ' ' . $p2show_open_source . '%</tspan></text>';

        $pricing_result .= '<circle r="44" cx="88" cy="88" fill="transparent" stroke="' . $colorOfSubscriptionPercentage . '" stroke-width="88" transform="rotate(-90) translate(-176)"stroke-dasharray="'.$strokeDashSubscription.'"/>';
        $pricing_result .= '<rect x="200" y="45" width="20" height="20" rx="5" fill="' . $colorOfSubscriptionPercentage . '" /><text class="detail-text" font-size="20" line-spacing="20" fill="#5BD49C"><tspan x="230" y="65">' . $subscription_percentage_title . ' ' . $p2show_subscription . '%</tspan></text>';
        $pricing_result .= '<circle r="44" cx="88" cy="88" fill="transparent"stroke="' . $colorOfFreemiumPercentage . '"stroke-width="88"stroke-dasharray="'.$strokeDashFreemium.'"transform="rotate(-90) translate(-176)" />';
        $pricing_result .= '<rect x="200" y="70" width="20" height="20" rx="5" fill="' . $colorOfFreemiumPercentage . '" /><text class="detail-text" font-size="20" line-spacing="20" fill="#5BD49C"><tspan x="230" y="90">' . $freemium_title . ' ' . $p2show_freemium . '%</tspan></text>';
     
        $pricing_result .= '<circle r="44" cx="88" cy="88" fill="transparent"stroke="' . $colorOfOneTimePercentage . '" stroke-width="88" stroke-dasharray="'.$strokeDashOneTime.'" transform="rotate(-90) translate(-176)" />';
        $pricing_result .= '<rect x="200" y="95" width="20" height="20" rx="5" fill="' . $colorOfOneTimePercentage . '" /><text class="detail-text" font-size="20" line-spacing="20" fill="#5BD49C"><tspan x="230" y="115">' . $one_time_license_percentage_title . ' ' . $one_time_license_percentage . '%</tspan></text></g></g>';


        $pricing_result .= '<text font-size="20" line-spacing="25" fill="#FFFFFF"><tspan x="366" y="185">Promotional Offers</tspan></text><g transform="translate(324.000000, 197.000000)"><circle fill="#b2e8d6" stroke="#fff" stroke-width="2" cx="114" cy="114" r="114" class="circ-fill" id="r15"></circle>';
        $pricing_result .= '<circle r="57" cx="62" cy="114" fill="transparent" stroke="#bbb0b0" stroke-width="114" stroke-dasharray="'.$strokeDashCoupon.' 357.9" transform="rotate(-90) translate(-176)" />';
        $pricing_result .= '<text class="detail-text" font-size="20" line-spacing="25" fill="forestgreen"><tspan x="135" y="86">' . round($coupon_all_list) . '%</tspan></text> </g><text font-size="20" line-spacing="25" fill="#FFFFFF"><tspan x="80" y="364">Free Trials</tspan></text>';
        $pricing_result .= '<g transform="translate(0.000000, 377.000000)"><circle fill="#c2d2e0" stroke="#fff" stroke-width="2" cx="132" cy="132" r="132" class="circ-fill-circle" id="r16"></circle><circle r="66" cx="44" cy="132" fill="transparent" stroke="#bbb0b0" stroke-width="131" stroke-dasharray="'.$strokeDashFreeTrial.' 414.48" transform="rotate(-90) translate(-176)" /> ';
        $pricing_result .= '<text class="detail-text" font-size="20" line-spacing="25" fill="forestgreen"><tspan x="141" y="76">' . round($free_trail_percentage) . '%</tspan></text>';
        $pricing_result .= '</g></svg></div></div>';

        $pricing_result .= '</div>';

        $pricing_result .= '<div class="col-md-6 col-sm-12" style="
        margin: auto;
        display:flex;
        height:inherit;
    "><div style="margin:auto;"> ';
        $pricing_result .= '<span style="color: #fff;font-size: 33px;"> $<span style="color: #fff;font-size:50px;">' . round($avg_price, 2) . '</span></span>
            <p style="color: #fff;">That is the average price for a solution in this field and ' . $show_data . '</p>';
        $pricing_result .= '</div></div>';

        $pricing_result .= '</div></div>';

        return $pricing_result;
    }

    public function price_performance_chart($post_id)
    {
        $alternateinfo = get_alternate_items_info($post_id);
        if (empty($alternateinfo)) {
            $alternateinfo = array();
        }
        $total = count($alternateinfo);
        if($total>0){
            $maximum = max(array_column($alternateinfo, 'price'));
            $minimum = min(array_column($alternateinfo, 'price'));
            // $max = array_keys($alternateinfo, $maximum);
        }
       

        foreach ($alternateinfo as $alternateinfos) {
            /* echo "infos";
            print_r($alternateinfos); */
            // foreach ($alternateinfos as $key => $value) {

                if ($alternateinfos['price'] == $maximum) {
                    $namemax = get_the_title($alternateinfos['id']);
                    $max_id = $alternateinfos['id'];
                }
                if ($alternateinfos['price'] == $minimum) {
                    $namemin = get_the_title($alternateinfos['id']);
                    $min_id = $alternateinfos['id'];
                }
            // }
        }
        $performance_chart_result = '  <div class="item-overview-content">';
        /*           $performance_chart_result .= '<div class="charts_graph doughnut">
        <div id="canvas-holder" style="width: 33%; float: left;" >
        <canvas id="chart-area" width="220"></canvas>
        </div>
        <div id="canvas-holder" style="width: 33%; float: left;">
        <canvas id="chart-area1" width="220"></canvas>
        </div>
        <div id="canvas-holder" style="width: 33%;  float: left;">
        <canvas id="chart-area2" width="220"></canvas>
        </div>
        </div>'; */

        $performance_chart_result .= '<div class="pricegraph_item" style="padding-top: 50px;"> ';
        $performance_chart_result .= ' <span class ="bartittle" >low </span> <span class ="bartittle"> Mid </span> <span class ="bartittle"> High  </span>
                                <canvas id="chart3" width="800" height="300" style="padding:20px;  box-shadow:2px 2px 2px rgba(0,0,0,0.2);"></canvas>
                                <div class="graph_text">';
                                if($total>0){
                                    $performance_chart_result .= '       <div class="textgraph" style="text-align: left;">
                                    After analyzing ' . $total . 'similar solutions the data above show that <a href="' . get_the_permalink($min_id) . '"> ' . $namemin . '</a> offers the lowesest starting price while <a href="' . get_the_permalink($max_id) . '"> ' . $namemax . '</a> offers the highest entry price.That being said is also worthtaking a closer look at whats on offer because sometimes you may get way more value for a solution with a higher entry price and vise versa

                                    </div>
                                    ';
                                }
       

        $performance_chart_result .= '</div></div>';

        //for price vs perforamnce
        $post_id = get_the_id();
        $compObj = new Mv_List_Comparision(null,true);
        $comared_data = $compObj->most_compared($post_id, 40);
        $item_price = get_field('price_starting_from', $post_id);
        $itemfs = get_or_calc_fs_individual($post_id);
        $all_items_fs= $ids_title = $all_price_trim=array();
        
        foreach ($comared_data as $post_idss) {
            $all_items_fs["_" . $post_idss] = get_or_calc_fs_individual($post_idss);
            $all_price = get_field('price_starting_from', $post_idss);
            $all_price_trim["_" . $post_idss] = intval($all_price);
            $ids_title["_" . $post_idss] = get_the_title($post_idss);
        }
        // print_R( $all_price_trim);
        // echo "count".count($comared_data);
        $count_comared = count($comared_data)==0?1:count($comared_data);
        $avg_price = array_sum($all_price_trim) / $count_comared;
        // echo "avg price";
        // print_r($avg_price);
        $result = array_merge_recursive($ids_title, $all_items_fs, $all_price_trim);
        $result = array_merge(array(array('Id', 'fs', 'price')), $result);

// echo "fs";
        // echo $itemfs;

        ?>
            <script type="text/javascript">

            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawSeriesChart);

        function drawSeriesChart() {
            var data = google.visualization.arrayToDataTable(   <?php echo json_encode(array_values($result)) ?>);
            var rangeX = data.getColumnRange(1);
            var rangeY = data.getColumnRange(2);
            var options = {
                
                backgroundColor:'#fbf6e6',
                width: '100%',
                height: 500,
                title: 'Price vs. Performance Comparision',
                    hAxis: {title: 'Overall Performance Score (0-100 , better scores are to the right)',
                    viewWindow: {
                    min: rangeX.min-10,
                    max: rangeX.max+10
                    }},
                    vAxis: {title: 'Price $(Lower is better)',
                    viewWindow: {
                    min: rangeY.min-1,
                    max: rangeY.max+1
                    }},
                    legend: 'null',
                    sizeAxis: {
                    maxSize: 10,
                    minSize: 10
                    },
                    bubble: {textStyle: {fontSize: 10}}

                        };
            var chart = new google.visualization.BubbleChart(document.getElementById('scatterchart_material'));
            chart.draw(data, options);
        }
        </script>
        <?php
$performance_chart_result .= ' <div class ="graph_text" ><div class="textgraph" style="text-align: left;">';

        $performance_chart_result .= '</div></div>';

        $performance_chart_result .= ' <div id="price-comp" class = "div_img"><div id="scatterchart_material"></div></div>';
        $performance_chart_result .= '</div></div>'; //item-pricing-performace-comp

        return $performance_chart_result;
    }

    public function list_market_radar_chart($post_id){
        // $compObj = new Mv_List_Comparision(null,true);
        // $returns = $compObj->most_compared($post_id, 10, false);
        // $returns = array_slice(get_field('list_items', $post_id),0,10);
        $this_list = $_SESSION['gen_list'][$post_id];
    /*     echo "gen list";
        print_r($this_list); */
        $sorts = '';
        if(isset($_GET['sort'])){
            $sorts=$_GET['sort'];
        }
        $postarr = $this_list[$sorts];
       /*  echo "postarr";
        print_r($postarr); */
        foreach($postarr as $key=>$value){
            $compareItems[]=$key;
            if(count($compareItems) >= 10){
                break;
            }
        }
        $returns = $compareItems;
        /*  echo "post id $post_id";
        print_r($returns); */
        // foreach ($returns as $id){
        //     $compared_item_fs[$id] =  get_or_calc_fs_individual($id);
        //     }
        // $item_result .= '<p style="width: 800px; top: 118px; text-align:  center; position:  relative; word-spacing: 110px;">Contenders Strong-Performers Leaders</p>';

        $radar_data[] = array('Name', 'Research Frequency', 'Findscore');
        foreach ($returns as $single_compared) {
            $radar_data[] = array(get_the_title($single_compared), calc_frequency($single_compared), get_or_calc_fs_individual($single_compared));
            $find_avg_findscore[$single_compared] = get_or_calc_fs_individual($single_compared);
        }
        $fs_average = array_sum($find_avg_findscore) / count($returns);
             
            
             $item_result ='  <div class="row chart-padding " id="softwareFinderRadarList">            
            </div> ';
            ?>
        <script type="text/javascript">
        // console.log("here2");
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(list_radar);

            function list_radar()
            {
                var data = google.visualization.arrayToDataTable( <?php echo json_encode($radar_data) ?>  );
                var rangeX = data.getColumnRange(1);
                console.log({rangeX});
                var options = {
                    chartArea: {
                        left: 80,
                        width: "100%"
                        },
                        width: "100%",
                        height:600,
                    title: '',
                    hAxis: {title: 'Research Frequency', gridlines: {color: 'transparent'}, textPosition: 'out', ticks: [{v:0, f:'Weak'}, {v: 0.9*rangeX.max, f:'Strong'}]},
                    vAxis: {title: 'Findscore', gridlines: {color: 'transparent'}, textPosition: 'out', ticks: [{v:0, f:'Weak'}, {v:100, f:'Strong'}]},
                    bubble: {textStyle: {fontSize: 10}},
                    sizeAxis: {
                        maxSize: 10
                    },
                    legend: {position: 'none'},
                    backgroundColor: 'none'
                };

                var chart = new google.visualization.BubbleChart(document.getElementById('softwareFinderRadarList'));
                google.visualization.events.addListener(chart, "ready", addBG);

                chart.draw(data, options);
            }

            function addBG(){
                console.log("all done");
                var svg = document.querySelector("#softwareFinderRadarList svg");
                console.log({svg});
                var attsRect = svg.children[1].children[0];
                console.log({attsRect});
                var parentElement = svg.children[1];
                console.log("here");
                console.log({parentElement});
                x = attsRect.getAttribute("x");
                y = attsRect.getAttribute("y");
                height = attsRect.getAttribute("height");
                width = attsRect.getAttribute("width");

                var svgimg = document.createElementNS("http://www.w3.org/2000/svg","image");
                svgimg.setAttributeNS(null,"preserveAspectRatio","none");
                svgimg.setAttributeNS(null,"height",height);
                svgimg.setAttributeNS(null,"width",width);
                svgimg.setAttributeNS("http://www.w3.org/1999/xlink","href", "<?php echo esc_url(plugins_url('image/wavechartbg.png', dirname(__FILE__))) ?>");
                svgimg.setAttributeNS(null,"x",x);
                svgimg.setAttributeNS(null,"y",y);
                svgimg.setAttributeNS(null, "visibility", "visible");


                var svgNS = "http://www.w3.org/2000/svg";
                var newText = document.createElementNS(svgNS,"text");
                newText.setAttributeNS(null,"x",x);     
                newText.setAttributeNS(null,"y",86); 
                newText.setAttributeNS(null,"font-size","12");
          
                var textNode = document.createTextNode("Contenders");
                newText.appendChild(textNode);
                text1Pos=parseInt(x)+parseInt(width)/3;
                var newText1 = document.createElementNS(svgNS,"text");
                newText1.setAttributeNS(null,"x",text1Pos);     
                newText1.setAttributeNS(null,"y",72); 
                newText1.setAttributeNS(null,"font-size","12");
          
                var textNode = document.createTextNode("Strong");
                newText1.appendChild(textNode);

                text3Pos=parseInt(x)+parseInt(width)/3;
                var newText3 = document.createElementNS(svgNS,"text");
                newText3.setAttributeNS(null,"x",text1Pos);     
                newText3.setAttributeNS(null,"y",86); 
                newText3.setAttributeNS(null,"font-size","12");
          
                var textNode = document.createTextNode("Performers");
                newText3.appendChild(textNode);

                text2Pos=parseInt(x)+parseInt(width)*2/3;
                var newText2 = document.createElementNS(svgNS,"text");
                newText2.setAttributeNS(null,"x",text2Pos);     
                newText2.setAttributeNS(null,"y",86); 
                newText2.setAttributeNS(null,"font-size","12");
          
                var textNode = document.createTextNode("Leaders");
                newText2.appendChild(textNode);

                parentElement.insertBefore(svgimg, parentElement.children[1]);
                parentElement.insertBefore(newText, parentElement.children[1]);
                parentElement.insertBefore(newText1, parentElement.children[1]);
                parentElement.insertBefore(newText2, parentElement.children[1]);
                parentElement.insertBefore(newText3, parentElement.children[1]);
            }
        </script>

<?php
        $item_result .= '<div>
        The SoftwareFindr Market Radar compares all solutions on our platform in your chosen category and tries to segment them to give you a visual representation of the market. All the solutions are compared two-dimensionally which takes into account their FindrScore which is given based on numerous data points and research frequency. The average FindrScore for users looking at a '.get_field('list_content_title_singular',$post_id).' is ' . round($fs_average) . ' which we’ve used as a threshold to only show the top 10 solutions.


</div>
        ';

        $item_result .= '</div>'; //item-softwarefindr-radar
        return $item_result;

    }
    public function list_price_performance_chart($post_id)
    {

        $post_id = get_the_id();
        $lists = get_field('list_items', $post_id, false);
        // echo "compared data";
        // print_r($lists);
        foreach ($lists as $post_idss) {

            $all_items_fs["_" . $post_idss] = get_or_calc_fs_individual($post_idss);
            $all_price = get_field('price_starting_from', $post_idss);
            $all_price_trim["_" . $post_idss] = intval($all_price);
            $ids_title["_" . $post_idss] = get_the_title($post_idss);
        }
        // echo "aray sum fs";
        // echo array_sum($all_items_fs);
        // arsort($all_items_fs);
        // print_r($all_items_fs);
        // echo count($lists);
        $this->list_max_fs = max($all_items_fs);
        $this->list_min_fs = min($all_items_fs);
        $this->list_avg_fs = array_sum($all_items_fs) / count($lists);
        $result = array_merge_recursive($ids_title, $all_items_fs, $all_price_trim);
        $result = array_merge(array(array('Id', 'fs', 'price')), $result);
        ?>
            <script type="text/javascript">

            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawSeriesChart);

        function drawSeriesChart() {
            var data = google.visualization.arrayToDataTable(   <?php echo json_encode(array_values($result)) ?>);
            var rangeX = data.getColumnRange(1);
            var rangeY = data.getColumnRange(2);
            var options = {
                backgroundColor:'#fbf6e6',
               
                height: 500,
                title: 'Price vs. Performance Comparision',
                    hAxis: {title: 'Overall Performance Score (0-100 , better scores are to the right)',
                    viewWindow: {
                    min: rangeX.min-10,
                    max: rangeX.max+10
                    }},
                    vAxis: {title: 'Price $(Lower is better)',
                    viewWindow: {
                    min: rangeY.min-1,
                    max: rangeY.max+1
                    }},
                    legend: 'null',
                    sizeAxis: {
                    maxSize: 10,
                    minSize: 10
                    },
                    bubble: {textStyle: {fontSize: 10}}

                        };
            var chart = new google.visualization.BubbleChart(document.getElementById('list_price_chart'));
            chart.draw(data, options);
        }
        </script>
        <?php
        $performance_chart_result = ' <div class = "div_img"><div id="list_price_chart"></div></div>';
        $performance_chart_result .= '</div></div>'; //item-pricing-performace-comp

        return $performance_chart_result;
    }

    public function list_comparion_data_show($post_id)
    {
        $post_id = get_the_id();
        $lists = get_field('list_items', $post_id, false);
        $i = 0;
        $plural_title = get_field('list_content_title_plural', $post_id);
        if ($plural_title == ''){
            $plural_title = get_the_title($post_id);
        }
        $list_comparion_data = '<h4 style=" font-size: 1.75rem; font-family: ProximaNova !important;">
              Compare Popular' . $plural_title . ' 
              </h4>';

        $list_comparion_data .= ' <div class="row-1" style=" overflow: hidden;">';
        $list_comparion_data .= '<p> When evaluating ' . $plural_title . ' our users also give serious thoughts to these other solutions:- </p>';
        foreach ($lists as $post_idss) {
            // echo " four post id";
            $listfour_id[] = $post_idss;
            // $all_items_fs["_".$post_idss]= get_or_calc_fs_individual($post_idss);
            // $all_price = get_field( 'price_starting_from', $post_idss);
            // $all_price_trim["_".$post_idss] =   intval($all_price);
            // $ids_title["_".$post_idss]= get_the_title($post_idss);
            $i++;

            if ($i == 4) {
                break;
            }

        }
        $abc = 0;
        // $def = 1;
        $list_comparion_data .= '<div class="comp_list" align="center" style=" overflow: hidden;" >';
        for (; $abc + 1 < count($listfour_id); $abc++) {
            $post_id = $listfour_id[$abc];
            $def = $abc + 1;
            for (; $def < count($listfour_id); $def++) {

                $id = $listfour_id[$def];
                $item_image = get_the_post_thumbnail_url($id, array(100, 100));
                $imagecurr = get_the_post_thumbnail_url($post_id, array(100, 100));
                // $url = '<a href="'.generate_compare_link(array($item1,$item2)).'/" class="new-comparison-btn ls_referal_link" data-parameter="itemid" data-id="'.$post_id.'" data-secondary="'.$id.'">'..' </a>';
                $list_comparion_data .= '<div  align="center" class="comparison-box-list col-sm-6 " style="height: 125px !important; text-align:center;">
                        <a href="' . generate_compare_link(array($post_id, $id)) . '/" class="new-comparison-btn ls_referal_link" data-parameter="itemid" data-id="' . $post_id . '" data-secondary="' . $id . '">
                        <div class="cp-item1"  style="width: 34% !important;"><img style="width: 50px !important; margin: auto;" src="' . $imagecurr . '" class="img-responsive sss" alt="' . get_the_title($post_id) . '">
                        <span class="cp-title" style="font-size: 12px;">' . get_the_title($post_id) . '</span> </div>

                    <p style="float: left;position: relative;top: 29px; text-align: center; width: 30%; ">
                    <span class="cp-vs-item"><span class="cp-vs-inner-item">vs</span></span></p>

                        <div class="cp-item1" style="width: 36% !important;"> <img style="width: 50px !important; margin: auto;" src="' . $item_image . '" class="img-responsive sss" alt="' . get_the_title($id) . '" >
                        <span class="cp-title" style="font-size: 12px;">' . get_the_title($id) . '</span></div> </div></a>';

            }
        }
        $list_comparion_data .= ' </div >';
        ?>
      <?php

        $list_comparion_data .= ' </div >';

        return $list_comparion_data;

    }

    public function list_trust_guid_sec($post_id)
    {
        $total_datapoints_voters_in_site = get_total_dp_site();
        $total_voters_in_site = $total_datapoints_voters_in_site['total_voters'];
        $total_datapoints_in_site = $total_datapoints_voters_in_site['total_datapoints'];

        $count_posts = wp_count_posts('list_items')->publish;

        $data = '<div class = "container-fluid decision-sec"><div class = "row ">';

        $data .= '<h4 style=" font-size: 1.75rem; font-family: ProximaNova !important;">
          Why You Should Trust Us ?
            </h4>';
        $data .= '<span class="trust_text">Here at SoftwareFindr we are all about learning from the people who came before us. The recommendations presented are completely impartial, we can not control the data we are analyzing and the numbers don’t lie. If a product is lacking in a certain area chance are our algorithm will pick that up. And if a product shines in a particular category that will be presented also. Gone are the days where it’s one size fits all, our recommendations are advice and personalize down to geographical preferences.
            <p>In the last 12 months, we have a crunch ' . $total_datapoints_in_site . ' data points in our system which paints a unique picture of each product from where they stand in the market, users sentiment and so on. All that is not without the of over ' . $total_voters_in_site . ' user contribution rating and reviewing over ' . $count_posts . ' products on our platform. That is the SoftwareFindr difference big data meets real user experiences</p>
            ';
        $data .= '</div></div>';

        $data .= '<div class = "container-fluid "><div class = "row ">';
         $buyer_data = get_field('buyer_guide', $post_id, false);
        if(!empty($buyer_data)){
            
        
        $data .= '<h4 style=" font-size: 1.75rem; font-family: ProximaNova !important;">
         Buyers Guide <br>
            </h4>';
       
        $data .= "<p style='width: 100%;'>" . $buyer_data . "</p>";
        $data .= '</div></div>';
    }
        return $data;
    }

    public function list_avg_cat_fndrscore($post_id)
    {
        $post_id = get_the_id();
        $lists = get_field('list_items', $post_id, false);
        $list_findrScore = get_or_calc_fs_individual($post_id);

        $findrScore = $this->findrScore;
        // $item_result .= ' <div class="item-overview-content">';
        $item_result = '<div class = "item-findrscore item-sec-div" >';
        $item_result .= '<h4>Average FindrScore in this Category</h4>';
        //-----------------Average findrscore in this category chart start--------------------------------
        $minfs_list = $this->list_min_fs;
        $highest_fs = $this->list_max_fs;

        $all_fs_svg = $this->list_avg_fs;
        $item_result .= '<div class="_30_3B" style="max-width: 75% !important;  margin: 0 auto;">
                <div class="_27_Cy">
                    <div class="_2mh2-"> </div>
                    <ol class="_2iCXM">
                        <li class="_3gKl5 _1xzv1 _1c9N4">
                            <div class="Ywjxq">
                                <div class="_1mOpY">
                                    <div data-which-id="badge">
                                        <span data-which-id="dont buy">
                                            <svg class="_3eMDH" version="1.1" xmlns="http://www.w3.org/2000/svg" xlink:href="http://www.w3.org/1999/xlink" xml:space="preserve" focusable="false" tabindex="-1" width="50" height="50" name="dontBuy" viewBox="0 0 31 31"><g fill="none" fill-rule="evenodd"><path fill="#55636C" d="M15.5 30.826c8.465 0 15.327-6.861 15.327-15.326S23.965.174 15.5.174.173 7.035.173 15.5 7.035 30.826 15.5 30.826"></path><path fill="#FFF" d="M2.584 9.929H5.01c2.177 0 3.226 1.015 3.226 2.968 0 2.012-1.041 3.054-3.285 3.054H2.584V9.93zm2.306 4.696c.86 0 1.282-.412 1.282-1.651 0-1.238-.396-1.678-1.282-1.678h-.26v3.33h.26zM8.7 12.957c0-1.996 1.369-3.122 3.071-3.122 1.712 0 3.079 1.126 3.079 3.122 0 1.994-1.325 3.088-3.079 3.088-1.755 0-3.07-1.094-3.07-3.088zm4.077.008c0-1.204-.404-1.694-1.006-1.694s-1.006.49-1.006 1.694.404 1.695 1.006 1.695c.603 0 1.006-.49 1.006-1.695zM15.462 9.929h2.072l1.66 3.01h.026v-3.01h1.652v6.022h-1.859l-1.875-3.38h-.025v3.38h-1.651V9.929zM21.456 11.804c.327-.052.585-.164.585-.344 0-.156-.197-.182-.344-.31a.648.648 0 0 1-.266-.559c0-.448.36-.748.852-.748.515 0 .963.327.963 1.067 0 .997-.585 1.53-1.79 1.617v-.723zM25.13 15.95v-4.628h-1.428V9.93h4.903v1.393h-1.428v4.629zM7.1 17.55h2.495c1.591 0 2.392.508 2.392 1.574 0 .74-.414 1.092-1.11 1.272v.036c.825.137 1.324.576 1.324 1.41 0 1.144-.963 1.728-2.581 1.728H7.1v-6.02zm2.374 2.374c.414 0 .611-.189.611-.568 0-.37-.189-.558-.602-.558h-.37v1.126h.361zm.027 2.434c.447 0 .679-.215.679-.654 0-.447-.241-.645-.68-.645h-.387v1.3h.388zM16.399 21.31v-3.76h1.695v3.82c0 1.488-.912 2.27-2.607 2.27-1.797 0-2.718-.791-2.718-2.27v-3.82h2.047v3.76c0 .618.242.876.8.876.55 0 .783-.258.783-.877zM20.433 23.57v-2.124l-1.815-3.466v-.43h1.918l1.015 2.081h.017l.972-2.082h1.67v.43l-1.73 3.416v2.175z"></path>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="_2qYt4 njNac">
                                <div class="zHZ2m _2zkHr" style="line-height: 37px;">' . round($minfs_list) . '<span class="_2-B6C">%</span>
                                </div>
                                <span class="w9dS5">Lowest score</span>
                            </div>
                        </div>
                    </li>
                    <li style="left:calc(' . $all_fs_svg . '% - 0.72px); " class="_3gKl5">
                        <div class="Ywjxq">
                            <div class="_1mOpY">
                                <div class="_2I_0Z _2E4dc" style="width: 15px !important;height: 15px !important;">

                                </div>
                            </div>
                            <div class="_2qYt4 kRixw">
                                <div class="zHZ2m _38leh" style="line-height: 37px;">' . round($all_fs_svg) . '<span class="_2-B6C">%</span>
                                </div>
                                <span class="w9dS5">Average score</span>
                            </div>
                        </div>
                    </li>

                    <li class="_3gKl5 kjH_m _1c9N4">
                        <div class="Ywjxq">
                            <div class="_1mOpY">
                                <div data-which-id="badge">
                                    <span data-which-id="best buy">
                                        <svg class="_3eMDH" version="1.1" xmlns="http://www.w3.org/2000/svg" xlink:href="http://www.w3.org/1999/xlink" xml:space="preserve" focusable="false" tabindex="-1" width="50" height="50" name="bestBuy" viewBox="0 0 31 31">';
        $item_result .= '<g fill="none" fill-rule="evenodd">
                                                    <path fill="#C10E1A" d="M15.498 0C6.999 0 0 7 0 15.498c0 8.599 7 15.498 15.498 15.498 8.599 0 15.498-6.9 15.498-15.498C30.996 6.999 24.096 0 15.498 0"></path><path fill="#FFF" d="M15.598 14.198v1.7h-5.5v-7.4h5.3v1.7h-2.8v1.2h2v1.6h-2v1.2h3zm-5.6 6.9c.9.2 1.6.7 1.6 1.699 0 1.5-1.1 2.2-3.1 2.2H5.4v-7.3h3c1.9 0 2.9.6 2.9 1.9 0 .9-.5 1.3-1.3 1.5zm-.9 1.699c.1-.6-.2-.8-.7-.8H7.8v1.6h.5c.5 0 .8-.3.8-.8zm-1.299-3.5v1.3h.5c.5 0 .8-.2.8-.7 0-.4-.2-.6-.7-.6h-.6zm1.7-5.699c0 1.4-1.2 2.1-3.1 2.1H3.3v-7.2h3c1.9 0 2.9.6 2.9 1.9 0 .9-.5 1.3-1.3 1.5.9.2 1.6.7 1.6 1.7zm-3.8.8h.5c.6 0 .9-.3.9-.8s-.3-.8-.8-.8h-.6v1.6zm0-4.4v1.4h.5c.5 0 .8-.2.8-.7 0-.4-.3-.7-.8-.7h-.5zm12.299 3.1c-1.3-.3-2.2-.8-2.2-2.4.3-1.6 1.5-2.3 3.2-2.3 1 0 1.7.2 2.3.5v1.6h-.9c-.3-.2-.8-.4-1.3-.4-.6 0-.9.1-.9.5s.3.4 1 .6c1.9.4 2.4 1 2.4 2.4 0 1.5-1.1 2.4-3.2 2.4-1.1 0-2-.2-2.5-.6v-1.5h1c.3.2.8.4 1.4.4.6 0 .9-.2.9-.5 0-.4-.4-.5-1.2-.7zm-1.4 9.1v-4.5h2.1v4.6c0 1.799-1.1 2.799-3.2 2.799-2.2 0-3.3-1-3.3-2.8v-4.6h2.5v4.5c0 .8.3 1.1 1 1.1s.9-.3.9-1.1zm5.4-13.7h5.998v1.7h-1.8v5.6h-2.5v-5.6h-1.699v-1.7zm.899 11.7l1.2-2.5h2v.5h-.1l-2 4.1v2.699h-2.5v-2.6l-2.2-4.2v-.5h2.4l1.2 2.5z"></path>
                                                </g>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="_2qYt4 _1wHMh">
                                    <div class="zHZ2m _2fML7" style="line-height: 37px;">' . round($highest_fs) . '<span class="_2-B6C">%</span>
                                    </div>
                                    <span class="w9dS5">Highest score</span>
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
                </div>';
        //-------------------------------------Average findrscore in this category chart end------------------------------------------------------

        $item_result .= '</div>';
        return $item_result;

    }

    public function data_item_page($post_id)
    {
        $post_id = get_the_id();
        $findrScore = $this->findrScore;
        $item_result = ' <div class="item-overview-content">';
        $item_result .= '<div id="market-overview" class = "item-findrscore item-sec-div" id="avgfindrscore">';
        $item_result .= '<h3>Average FindrScore in this Category</h3>';
        //-----------------Average findrscore in this category chart start--------------------------------
        $minfs_list = $this->min_fs_list;
        $highest_fs = $this->highest_fs;
        $all_fs_svg = $this->all_fs_avg;
        
        $item_result .= '<div class="_30_3B" style="max-width: 75% !important; margin: 0 auto;">
                <div class="_27_Cy">
                    <div class="_2mh2-"> </div>
                    <ol class="_2iCXM">
                        <li class="_3gKl5 _1xzv1 _1c9N4">
                            <div class="Ywjxq">
                                <div class="_1mOpY">
                                    <div data-which-id="badge">
                                        <span data-which-id="dont buy">
                                            <svg class="_3eMDH" version="1.1" xmlns="http://www.w3.org/2000/svg" xlink:href="http://www.w3.org/1999/xlink" xml:space="preserve" focusable="false" tabindex="-1" width="30" height="30" name="dontBuy" viewBox="0 0 31 31"><g fill="none" fill-rule="evenodd"><path fill="#55636C" d="M15.5 30.826c8.465 0 15.327-6.861 15.327-15.326S23.965.174 15.5.174.173 7.035.173 15.5 7.035 30.826 15.5 30.826"></path><path fill="#FFF" d="M2.584 9.929H5.01c2.177 0 3.226 1.015 3.226 2.968 0 2.012-1.041 3.054-3.285 3.054H2.584V9.93zm2.306 4.696c.86 0 1.282-.412 1.282-1.651 0-1.238-.396-1.678-1.282-1.678h-.26v3.33h.26zM8.7 12.957c0-1.996 1.369-3.122 3.071-3.122 1.712 0 3.079 1.126 3.079 3.122 0 1.994-1.325 3.088-3.079 3.088-1.755 0-3.07-1.094-3.07-3.088zm4.077.008c0-1.204-.404-1.694-1.006-1.694s-1.006.49-1.006 1.694.404 1.695 1.006 1.695c.603 0 1.006-.49 1.006-1.695zM15.462 9.929h2.072l1.66 3.01h.026v-3.01h1.652v6.022h-1.859l-1.875-3.38h-.025v3.38h-1.651V9.929zM21.456 11.804c.327-.052.585-.164.585-.344 0-.156-.197-.182-.344-.31a.648.648 0 0 1-.266-.559c0-.448.36-.748.852-.748.515 0 .963.327.963 1.067 0 .997-.585 1.53-1.79 1.617v-.723zM25.13 15.95v-4.628h-1.428V9.93h4.903v1.393h-1.428v4.629zM7.1 17.55h2.495c1.591 0 2.392.508 2.392 1.574 0 .74-.414 1.092-1.11 1.272v.036c.825.137 1.324.576 1.324 1.41 0 1.144-.963 1.728-2.581 1.728H7.1v-6.02zm2.374 2.374c.414 0 .611-.189.611-.568 0-.37-.189-.558-.602-.558h-.37v1.126h.361zm.027 2.434c.447 0 .679-.215.679-.654 0-.447-.241-.645-.68-.645h-.387v1.3h.388zM16.399 21.31v-3.76h1.695v3.82c0 1.488-.912 2.27-2.607 2.27-1.797 0-2.718-.791-2.718-2.27v-3.82h2.047v3.76c0 .618.242.876.8.876.55 0 .783-.258.783-.877zM20.433 23.57v-2.124l-1.815-3.466v-.43h1.918l1.015 2.081h.017l.972-2.082h1.67v.43l-1.73 3.416v2.175z"></path>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="_2qYt4 njNac">
                                <div class="zHZ2m _2zkHr">' . round($minfs_list) . '<span class="_2-B6C">(FS)</span>
                                </div>
                                <span class="w9dS5">Lowest score</span>
                            </div>
                        </div>
                    </li>
                    <li style="left:calc(' . $all_fs_svg . '% - 0.72px); " class="_3gKl5">
                        <div class="Ywjxq">
                            <div class="_1mOpY">
                                <div class="_2I_0Z _2E4dc">

                                </div>
                            </div>
                            <div class="_2qYt4 kRixw">
                                <div class="zHZ2m _38leh">' . round($all_fs_svg) . '<span class="_2-B6C">(FS)</span>
                                </div>
                                <span class="w9dS5">Average score</span>
                            </div>
                        </div>
                    </li>
                    <li style="left:calc(' . round($findrScore) . '% - 0.72px); " class="_3gKl5">
                        <div class="_2qYt4-- kRixw item_average">
                            <div class="zHZ2m _38leh">' . round($findrScore) . '<span class="_2-B6C">(FS)</span>
                            </div>
                            <span class="w9dS5">' . get_the_title($post_id) . '</span>
                        </div>
                        <div class="Ywjxq">
                            <div class="_1mOpY">
                                <div class="_2I_0Z _2E4dc">

                                </div>
                                <div class ="vl"></div>
                            </div>

                        </div>
                    </li>
                    <li class="_3gKl5 kjH_m _1c9N4">
                        <div class="Ywjxq">
                            <div class="_1mOpY">
                                <div data-which-id="badge">
                                    <span data-which-id="best buy">
                                        <svg class="_3eMDH" version="1.1" xmlns="http://www.w3.org/2000/svg" xlink:href="http://www.w3.org/1999/xlink" xml:space="preserve" focusable="false" tabindex="-1" width="30" height="30" name="bestBuy" viewBox="0 0 31 31">';
        $item_result .= '<g fill="none" fill-rule="evenodd">
                                                    <path fill="#C10E1A" d="M15.498 0C6.999 0 0 7 0 15.498c0 8.599 7 15.498 15.498 15.498 8.599 0 15.498-6.9 15.498-15.498C30.996 6.999 24.096 0 15.498 0"></path><path fill="#FFF" d="M15.598 14.198v1.7h-5.5v-7.4h5.3v1.7h-2.8v1.2h2v1.6h-2v1.2h3zm-5.6 6.9c.9.2 1.6.7 1.6 1.699 0 1.5-1.1 2.2-3.1 2.2H5.4v-7.3h3c1.9 0 2.9.6 2.9 1.9 0 .9-.5 1.3-1.3 1.5zm-.9 1.699c.1-.6-.2-.8-.7-.8H7.8v1.6h.5c.5 0 .8-.3.8-.8zm-1.299-3.5v1.3h.5c.5 0 .8-.2.8-.7 0-.4-.2-.6-.7-.6h-.6zm1.7-5.699c0 1.4-1.2 2.1-3.1 2.1H3.3v-7.2h3c1.9 0 2.9.6 2.9 1.9 0 .9-.5 1.3-1.3 1.5.9.2 1.6.7 1.6 1.7zm-3.8.8h.5c.6 0 .9-.3.9-.8s-.3-.8-.8-.8h-.6v1.6zm0-4.4v1.4h.5c.5 0 .8-.2.8-.7 0-.4-.3-.7-.8-.7h-.5zm12.299 3.1c-1.3-.3-2.2-.8-2.2-2.4.3-1.6 1.5-2.3 3.2-2.3 1 0 1.7.2 2.3.5v1.6h-.9c-.3-.2-.8-.4-1.3-.4-.6 0-.9.1-.9.5s.3.4 1 .6c1.9.4 2.4 1 2.4 2.4 0 1.5-1.1 2.4-3.2 2.4-1.1 0-2-.2-2.5-.6v-1.5h1c.3.2.8.4 1.4.4.6 0 .9-.2.9-.5 0-.4-.4-.5-1.2-.7zm-1.4 9.1v-4.5h2.1v4.6c0 1.799-1.1 2.799-3.2 2.799-2.2 0-3.3-1-3.3-2.8v-4.6h2.5v4.5c0 .8.3 1.1 1 1.1s.9-.3.9-1.1zm5.4-13.7h5.998v1.7h-1.8v5.6h-2.5v-5.6h-1.699v-1.7zm.899 11.7l1.2-2.5h2v.5h-.1l-2 4.1v2.699h-2.5v-2.6l-2.2-4.2v-.5h2.4l1.2 2.5z"></path>
                                                </g>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="_2qYt4 _1wHMh">
                                    <div class="zHZ2m _2fML7">' . round($highest_fs) . '<span class="_2-B6C">(FS)</span>
                                    </div>
                                    <span class="w9dS5">Highest score</span>
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
                </div>';
        //-------------------------------------Average findrscore in this category chart end------------------------------------------------------

        $item_result .= '</div>'; //item-findrscore

        $item_result .= '<div class = "item-softwarefindr-radar item-sec-div">';
        $item_result .= '<h3>SoftwareFindr Market Radar</h3>';

        ?>
<!-- $software_finder_arr -->

<?php
$compObj = new Mv_List_Comparision(null,true);
        $returns = $compObj->most_compared($post_id, 9, false);
        $returns[]=$post_id;
        $countReturns = count($returns)==0?1:count($returns);
        // foreach ($returns as $id){
        //     $compared_item_fs[$id] =  get_or_calc_fs_individual($id);
        //     }
        // $item_result .= '<p style="width: 800px; top: 118px; text-align:  center; position:  relative; word-spacing: 110px;">Contenders Strong-Performers Leaders</p>';

        $radar_data[] = array('Name', 'Research Frequency', 'Findscore');
        $find_avg_findscore=array();
        foreach ($returns as $single_compared) {
            $radar_data[] = array(get_the_title($single_compared), calc_frequency($single_compared), get_or_calc_fs_individual($single_compared));
            $find_avg_findscore[$single_compared] = get_or_calc_fs_individual($single_compared);
        }
        $fs_average = array_sum($find_avg_findscore) / $countReturns;

        $item_result .= '
    <div class="row chart-padding background_softwareFinderRadar" id="softwareFinderRadar">

            
                
            
        </div>
               
        <script type="text/javascript">
        console.log("here2");
            google.charts.load(\'current\', {\'packages\':[\'corechart\']});
            google.charts.setOnLoadCallback(drawSeriesChart_7);

            function drawSeriesChart_7()
            {
                var data = google.visualization.arrayToDataTable(' . json_encode($radar_data) . ');
                var rangeX = data.getColumnRange(1);
                console.log({rangeX});
                var options = {
                    chartArea: {
                        left: 80,
                        width: "100%"
                        },
                        width: "100%",
                        height:500,
                    title: \'\',
                    hAxis: {title: \'Research Frequency\', gridlines: {color: \'transparent\'}, textPosition: \'out\', ticks: [{v:0, f:\'Weak\'}, {v: 0.9*rangeX.max, f:\'Strong\'}]},
                    vAxis: {title: \'Findscore\', gridlines: {color: \'transparent\'}, textPosition: \'out\', ticks: [{v:0, f:\'Weak\'}, {v:100, f:\'Strong\'}]},
                    bubble: {textStyle: {fontSize: 10}},
                    sizeAxis: {
                        maxSize: 10
                    },
                    legend: {position: \'none\'},
                    backgroundColor: \'none\'
                };

                var chart = new google.visualization.BubbleChart(document.getElementById(\'softwareFinderRadar\'));
                google.visualization.events.addListener(chart, "ready", addBG);

                chart.draw(data, options);
            }

            function addBG(){
                console.log("all done");
                var svg = document.querySelector(".background_softwareFinderRadar svg");
                console.log({svg});
                var attsRect = svg.children[1].children[0];
                console.log({attsRect});
                var parentElement = svg.children[1];
                console.log("here");
                console.log({parentElement});
                x = attsRect.getAttribute("x");
                y = attsRect.getAttribute("y");
                height = attsRect.getAttribute("height");
                width = attsRect.getAttribute("width");

                var svgimg = document.createElementNS("http://www.w3.org/2000/svg","image");
                svgimg.setAttributeNS(null,"preserveAspectRatio","none");
                svgimg.setAttributeNS(null,"height",height);
                svgimg.setAttributeNS(null,"width",width);
                svgimg.setAttributeNS("http://www.w3.org/1999/xlink","href",  "'.esc_url(plugins_url('image/wavechartbg.png', dirname(__FILE__))).'");
                svgimg.setAttributeNS(null,"x",x);
                svgimg.setAttributeNS(null,"y",y);
                svgimg.setAttributeNS(null, "visibility", "visible");


                var svgNS = "http://www.w3.org/2000/svg";
                var newText = document.createElementNS(svgNS,"text");
                newText.setAttributeNS(null,"x",x);     
                newText.setAttributeNS(null,"y",86); 
                newText.setAttributeNS(null,"font-size","12");
          
                var textNode = document.createTextNode("Contenders");
                newText.appendChild(textNode);
                text1Pos=parseInt(x)+parseInt(width)/3;
                var newText1 = document.createElementNS(svgNS,"text");
                newText1.setAttributeNS(null,"x",text1Pos);     
                newText1.setAttributeNS(null,"y",72); 
                newText1.setAttributeNS(null,"font-size","12");
          
                var textNode = document.createTextNode("Strong");
                newText1.appendChild(textNode);

                text3Pos=parseInt(x)+parseInt(width)/3;
                var newText3 = document.createElementNS(svgNS,"text");
                newText3.setAttributeNS(null,"x",text1Pos);     
                newText3.setAttributeNS(null,"y",86); 
                newText3.setAttributeNS(null,"font-size","12");
          
                var textNode = document.createTextNode("Performers");
                newText3.appendChild(textNode);

                text2Pos=parseInt(x)+parseInt(width)*2/3;
                var newText2 = document.createElementNS(svgNS,"text");
                newText2.setAttributeNS(null,"x",text2Pos);     
                newText2.setAttributeNS(null,"y",86); 
                newText2.setAttributeNS(null,"font-size","12");
          
                var textNode = document.createTextNode("Leaders");
                newText2.appendChild(textNode);

                parentElement.insertBefore(svgimg, parentElement.children[1]);
                parentElement.insertBefore(newText, parentElement.children[1]);
                parentElement.insertBefore(newText1, parentElement.children[1]);
                parentElement.insertBefore(newText2, parentElement.children[1]);
                parentElement.insertBefore(newText3, parentElement.children[1]);
            }
        </script>
';

        $item_result .= '
<div>The SoftwareFindr Market Radar compares all solutions on our platform in your chosen category and tries to segment them to give you a visual representation of the market. All the solutions are compared two-dimensionally which takes into
account their FindrScore which is given based on numerous data points and research frequency. The average FindrScore for products like <a href=' . get_permalink($post_id) . '>' . get_the_title($post_id) . ' </a>is
' . round($fs_average) . ' which we\'ve used as a threshold to only show the top 10 solutions.
</div>
        ';

        $item_result .= '</div>'; //item-softwarefindr-radar

        $item_result .= '<div class = "item-alternative-compare item-sec-div" id="compare">';
        $item_result .= '<h3>Compare ' . get_the_title($post_id) . ' to Similar Solutions</h3>';
        $item_result .= 'When evaluating ' . get_the_title($post_id) . ' our users also give serious thoughts to these other solutions';
       
            
        $compObj = new Mv_List_Comparision(null,true);
        $returns = $compObj->most_compared($post_id,1000);

        if (!empty($returns) && is_array($returns)) {
            $titleCurr = get_the_title($post_id);
            //$imagecurr = get_thumbnail_small($post_id,array(100,100));
            $imagecurr = get_the_post_thumbnail_url($post_id, array(100, 100));

            $item_result .= '<div class="comparison-list">';
            $compareBoxes = '';
            $compareBoxPromoted = '';
            $sixth = '';
            $noOfSixth = 0;
            $noOfCompareBoxes =0;
            $noOfPromoted = 0;
            foreach ($returns as $id) {
                $com_id = get_the_title($id);
                $item_image = get_the_post_thumbnail_url($id, array(100, 100));
                $promoted = get_field('promoted_on_item_page',$id);
              /*   echo "promoted $id";
                var_dump($promoted); */
                if($promoted === true && $noOfPromoted<1){
                    $noOfPromoted++;
                    $compareBoxPromoted .= '<div class="comparison-box-list">
                                                <a href="' . generate_compare_link(array($post_id, $id)) . '/" class="new-comparison-btn ls_referal_link" data-parameter="itemid" data-id="' . $post_id . '" data-secondary="' . $id . '">
                                                        <div class="cp-item1">
                                                            <img src="' . $imagecurr . '" class="img-responsive sss" alt="' . get_the_title($post_id) . '">
                                                            <span class="cp-title">' . $titleCurr . '</span> 
                                                        </div>

                                                        <p style="float: left;position: relative;top: 29px; text-align: center; width: 35%; ">
                                                            <span class="cp-vs-item"><span class="cp-vs-inner-item">vs</span></span>
                                                        </p>

                                                        <div class="cp-item1"> <img src="' . $item_image . '" class="img-responsive sss" alt="' . get_the_title($id) . '" >
                                                            <span class="cp-title">' . get_the_title($id) . '</span>
                                                        </div> 
                                                            <span style="
                                                        margin-top: 5px;
                                                        float: right;
                                                    ">Promoted</span>
                                                </a> 
                                            </div>';
                }
                
                elseif($noOfCompareBoxes < 5){
                    $noOfCompareBoxes++;
                    $compareBoxes .= '<div class="comparison-box-list">
                    <a href="' . generate_compare_link(array($post_id, $id)) . '/" class="new-comparison-btn ls_referal_link" data-parameter="itemid" data-id="' . $post_id . '" data-secondary="' . $id . '">
                    <div class="cp-item1"><img src="' . $imagecurr . '" class="img-responsive sss" alt="' . get_the_title($post_id) . '">
                    <span class="cp-title">' . $titleCurr . '</span> </div>

                <p style="float: left;position: relative;top: 29px; text-align: center; width: 35%; ">
                <span class="cp-vs-item"><span class="cp-vs-inner-item">vs</span></span></p>

                    <div class="cp-item1"> <img src="' . $item_image . '" class="img-responsive sss" alt="' . get_the_title($id) . '" >
                    <span class="cp-title">' . get_the_title($id) . '</span></div> </a></div>';
                }elseif($noOfSixth<1){
                    $noOfSixth++;
                    $sixth .= '<div class="comparison-box-list">
                    <a href="' . generate_compare_link(array($post_id, $id)) . '/" class="new-comparison-btn ls_referal_link" data-parameter="itemid" data-id="' . $post_id . '" data-secondary="' . $id . '">
                    <div class="cp-item1"><img src="' . $imagecurr . '" class="img-responsive sss" alt="' . get_the_title($post_id) . '">
                    <span class="cp-title">' . $titleCurr . '</span> </div>

                <p style="float: left;position: relative;top: 29px; text-align: center; width: 35%; ">
                <span class="cp-vs-item"><span class="cp-vs-inner-item">vs</span></span></p>

                    <div class="cp-item1"> <img src="' . $item_image . '" class="img-responsive sss" alt="' . get_the_title($id) . '" >
                    <span class="cp-title">' . get_the_title($id) . '</span></div> </a></div>';
                }
              
                if($noOfPromoted ==1 && $noOfCompareBoxes==5){
                    break;
                }
            }
            if($noOfPromoted==1){
                $item_result .=  $compareBoxPromoted;
            }
            
            
            $item_result .=  $compareBoxes;
            if($noOfPromoted==0){
                $item_result .=  $sixth;
            }
            $item_result .= "</div>";
        }

        $item_result .= '</div>'; //item-alternative-compare

        $item_result .= '<div id="alternative" class = "item-alternative item-sec-div">';
        $item_result .= '<h3>Alternatives to ' . get_the_title($post_id) . '</h3>';
        $findrScore = 0; // use calculate_findrscore_individual fxn

        $lists = get_alternate_items_info($post_id);

        if (!empty($lists)) {

            // $item_result.='<div class="see-more-btn" style=""><a href="'.get_the_permalink( $review_id ).'alternative/" class="ls_referal_link" data-parameter="itemid" data-id="'.$review_id.'">See More</a></div> <div class="clr"></div>';
            $item_result .= '<div class="full-width-itm"><div class="flexslider-1 carousel-itmcr-alternate">
                        <div class="altr-box"><div class="row"> <div class="col-sm-12">';
            $ac = 0;
            $noOfPromoted = 0;
            $boxes = '';
            $boxesHidden = '';
            $sixthpid= $sixthimages= $sixthRatingItem= $sixthFS=''; 
            foreach ($lists as $pid) {

                $pid = $pid['id'];
                $promoted = get_field('promoted_on_item_page',$pid);
                $fs_alternative[$pid] = get_or_calc_fs_individual($pid);
                $price_altrnative[$pid] = intval(get_field('price_starting_from', $pid));
                $reviews = get_overall_combined_rating($pid);
                $findrScore =0;
                if(isset($reviews['list']['overallrating']['score'])){
                    $findrScore += $reviews['list']['overallrating']['score'] * 10;
                }
                
                $listrankord = $this->ranklist;
                // print_r($listrankord);
                $percentileSum = 0;
                // print_r($listrankord);
                foreach ($listrankord as $listid => $rank) {
                    $noOflistitems = sizeof(get_field('list_items', $listid));
                    // echo "sizeof lists $noOflistitems";
                    $percentileSum += ($noOflistitems - $rank) / $noOflistitems;
                }
                $percentileAvg = $percentileSum / (sizeof($listrankord));
                // print_r($listitems);
                $findrScore += $percentileAvg * 50;

                $rating_item = do_shortcode('[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=20 post=' . $pid . ']');

                $images = get_the_post_thumbnail_url($pid, array(150, 150));
                if($promoted === true && $noOfPromoted<1){
                    $class="";
                    $boxPromoted = '<div class="item_list_alternative '.$class.'" >
                    <div class="col-sm-4 item_list_img"><a href="' . get_the_permalink($pid) . '"><img src="' . $images . '" class="img-responsive sss" alt="' . get_the_title($pid) . '" ></a></div>
                    <div class="col-sm-5"><a href="' . get_the_permalink($pid) . '"><strong><span>' . get_the_title($pid) . '</span></strong></a><br>' . $rating_item . '</div>
                    <div class="col-sm-3">' . round($findrScore) . '/100</div>
                    <span style="
    float: right;
    width: 100%;
    text-align: right;
    margin-right: 5px;
">Promoted</span>
                    </div>';
                    $noOfPromoted++;
                    $ac--;
                }
                elseif($ac>5){
                    $class="hide_alt";
                    $boxesHidden .= '<div class="item_list_alternative '.$class.'" >
                    <div class="col-sm-4 item_list_img"><a href="' . get_the_permalink($pid) . '"><img src="' . $images . '" class="img-responsive sss" alt="' . get_the_title($pid) . '" ></a></div>
                    <div class="col-sm-5"><a href="' . get_the_permalink($pid) . '"><strong><span>' . get_the_title($pid) . '</span></strong></a><br>' . $rating_item . '</div>
                    <div class="col-sm-3">' . round($findrScore) . '/100</div>
                    </div>';
                }
                elseif($ac<5){
                    $class="";
                    $boxes .= '<div class="item_list_alternative '.$class.'" >
                    <div class="col-sm-4 item_list_img"><a href="' . get_the_permalink($pid) . '"><img src="' . $images . '" class="img-responsive sss" alt="' . get_the_title($pid) . '" ></a></div>
                    <div class="col-sm-5"><a href="' . get_the_permalink($pid) . '"><strong><span>' . get_the_title($pid) . '</span></strong></a><br>' . $rating_item . '</div>
                    <div class="col-sm-3">' . round($findrScore) . '/100</div>
                    </div>';
                }
                else{
                    $sixthpid=$pid;
                    $sixthimages=$images;
                    $sixthRatingItem=$rating_item;
                    $sixthFS=round($findrScore); 
                }
                

                $ac++;
            }
            if($noOfPromoted == 1){
                $class="hide_alt";
            }
            else{
                $class="";
            }
            if(isset($boxPromoted)){
                $item_result .= $boxPromoted;
            }
            
            $item_result .= $boxes;
            $item_result .= '<div class="item_list_alternative '.$class.'" >
            <div class="col-sm-4 item_list_img"><a href="' . get_the_permalink($sixthpid) . '"><img src="' . $sixthimages . '" class="img-responsive sss" alt="' . get_the_title($sixthpid) . '" ></a></div>
            <div class="col-sm-5"><a href="' . get_the_permalink($sixthpid) . '"><strong><span>' . get_the_title($sixthpid) . '</span></strong></a><br>' . $sixthRatingItem . '</div>
            <div class="col-sm-3">' . $sixthFS . '/100</div>
            </div>';
            $item_result.=$boxesHidden;
            // print_r($price_altrnative);
            asort($price_altrnative);
            foreach ($price_altrnative as $key => $low_price) {

                $checp_item = $key;
                $checp_item_score = get_overall_combined_rating($key);
                $checp_item_score_list = $checp_item_score['list']['overallrating']['score'] * 10;

                break;
            }
            arsort($fs_alternative);
            // print_r($fs_alternative);
            foreach ($fs_alternative as $key => $top_altr) {
                $top_altr1 = $key;
                break;

            }
            // echo $top_altr1;
            $item_result .= '</div>
            <h5 style="font-weight:600; cursor: pointer;" id="seeMore_alt">See More</h5>
            </div></div></div></div>
            <script>
            jQuery("#seeMore_alt").click(function()
            {
                jQuery(".hide_alt").toggle();
            });</script>
            ';

        }
        $cheap_list_data = isset($checp_item_score_list)?$checp_item_score_list:0;;

        //    $indust_avg =   ;
        //    echo $indust_avg;
        //    echo "<br>";
        //    echo $cheap_list_data;
        $sumindoverall=0;
        if(is_array($this->overallindustryratings)){
            foreach($this->overallindustryratings as $key=>$overallscore){
                $sumindoverall+=$overallscore;
            }
            $indust_avg = $sumindoverall/count($this->overallindustryratings);
        }
        if(isset($indust_avg)){
            if ($indust_avg < $cheap_list_data) {
                $usrexp = " which users are having a positive experience with";
            } elseif ($indust_avg == $cheap_list_data) {
                $usrexp = " having mixed experience with";
    
            } else {
                $usrexp = " having a negative experience with";
    
            }
        }
        
        if(isset($top_altr1) && isset($checp_item) && isset($usrexp)){

        
        $item_result .= '<p class="alternative-text">Not quite satisfied with  ' . get_the_title($post_id) . '? No worries, users who research this solution also look at
                    <a href="' . get_the_permalink($top_altr1) . ' "> ' . get_the_title($top_altr1) . '</a>. The cheapest alternatives we found in our system are  <a href="' . get_the_permalink($checp_item) . '">' . get_the_title($checp_item) . ' </a>  ' . $usrexp . '. For a detailed breakdown,
                    <a href="' . get_the_permalink($post_id) . 'alternative/"> click here to see the best ' . get_the_title($post_id) . ' alternatives.</a>
                   </p>';
                }
        $item_result .= '</div>'; //item-alternative
        $item_result .= '</div>'; //maindiv
        return $item_result;

    }

    public function videos_sec($postid)
    {
        $post_id = get_the_id();
        $details = '<div class = "item-videos item-sec-div" id="videos">';
        $item_result = '<h3>Videos </h3>';
        /******************************************Video******************************************** */

        //        update_post_meta($post_id, 'video_list' ,array($url));
        $video_list_new = array_unique(get_post_meta($post_id, 'video_list', true));
        /*  echo "get post meta vln";
        print_r($video_list_new); */
        foreach ($video_list_new as $key => $video) {
            if (trim($video) == '') {
                unset($video_list_new[$key]);
            }
        }
        $video_list_new = array_values($video_list_new); //re-index after unset
        //        echo "video_list new after get post meta";
        //        print_r($video_list_new);
        //        echo $video_list_new;
        /*     if(!in_array($url,$video_list_new)){
        $video_list_new[] = $url;

        update_post_meta($post_id, 'video_list' ,$video_list_new);
        } */

      /*   if (!empty($video_list_new)) {
            $this->array_head['videos'] = 'Videos';
        } */
        // echo "vln";
        // print_r($video_list_new);
        if (is_array($video_list_new)) { //remove non-youtube links
            foreach($video_list_new as $key=>$vln){
                if(strpos($vln,"youtube") === false){
                    unset($video_list_new[$key]);
                }
            }
        }
        // print_r($video_list_new);
        if (!empty($video_list_new)) {
                // echo "v".$video_list_new[0];
                $splittedstring = explode("?v=", $video_list_new[0]);
                $firstvid = $splittedstring[1];

                if (count($splittedstring) == 1) {
                    $splittedstring = explode("embed/", $video_list_new[0]);
                    $firstvid = $splittedstring[1];
                }

                $details .= ' <div id="video-slideshow " class="VideoSlideShowcomponent__Styled-tbnhnn-0 hBRwUj" data-e2e="video-slideshow" >
            <div class="black-background">
                <div class="extra-black">

            </div>
            <div class="slideshow-container">
                <div class="copy-container">
                    <h3>Videos</h3>
                    <span>View view video reviews and product walk through</span>
                    <h6>Up Next</h6>
                    <a class="up-next" role="button">';

                $i = 0;
                $num = 1;
                /*  echo "video list new";
                print_r($video_list_new); */
                foreach ($video_list_new as $new) {
                    if (trim($new) != '') {
                        $splittedstring = explode("?v=", $new);
                        $video_list_new[$i] = $splittedstring[1];

                        //                        print_r($splittedstring);
                        if (count($splittedstring) == 1) {
                            $splittedstring = explode("embed/", $new);
                            $video_list_new[$i] = $splittedstring[1];
                        }
                        $abc = count($video_list_new);

                        // $content = file_get_contents("http://youtube.com/get_video_info?video_id=".$video_list_new[$i]);
                        $api = "AIzaSyCXRiwRiOYQBHbOFeinDzo6YsPFH1S_uhY"; //geniusunil1 //"AIzaSyBxZQza9iYMySd0Tcd93k3Esv3AGfIVJp0"; // YouTube Developer API Key testdemo256@gmail.com

                        $content = @file_get_contents("https://www.googleapis.com/youtube/v3/videos?key=$api&part=snippet&id=" . $video_list_new[$i]);
                        // var_dump($content);
                        // file_put_contents("youtubeapidata".$i.".txt",$content);

                        // $content = file_get_contents("youtubeapidata".$i.".txt");

                        //   $video = json_decode(file_get_contents("video.txt"), true);

                        // var_dump($http_response_header);
                        // var_dump($content);
                        // file_put_contents("mvlistsvn.txt","video_list_new is : ".print_r($video_list_new,true)."end of video_list_new\n",FILE_APPEND);

                        // file_put_contents("mvlistsvn.txt","content youtube is : ".$content."end of content youtube\n",FILE_APPEND);

                        // parse_str($content, $ytarr);
                        // $jsondec = json_decode($ytarr['player_response'],true);
                        if($content !== false){
                        
                        
                        $jsondec = json_decode($content);

                        file_put_contents("mvlistsvn.txt", "jsondec youtube is : " . print_r($jsondec,true) . "end of jsondec youtube\n", FILE_APPEND);
                        //['items']['snippet']['thumbnails']['default']['url']
                        $active = "";
                        if ($i == 0) {
                            $active = "active1";
                        } elseif ($i == 1 || $i == 2) {
                            $active = "active";
                        }
                        // var_dump($jsondec);
                        $details .= '<div class="vid-item ' . $active . '" data-number="' . $num . '" data-url="https://youtube.com/embed/' . $video_list_new[$i] . '"  onClick="document.getElementById(\'vid_frame1\').src=\'https://youtube.com/embed/' . $video_list_new[$i] . '?autoplay=1&rel=0&showinfo=0&autohide=1\'">

                        <div class="thumb">
                            <img src="' . $jsondec->items[0]->snippet->thumbnails->default->url . '">
                        </div>
                         <div class="desc">
                                    ' . $jsondec->items[0]->snippet->title . '
                            </div>
                        </div>


                        ';
                    }
                        $num++;
                        $i++;

                    }

                }

                $details .= ' </a>';
                $video_list_new_id = implode(" , ", $video_list_new);

                $details .= '</div><a class="close-button" role="button">
                                <svg width="17px" height="17px" viewBox="0 0 17 17" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="square">
                                <g transform="translate(-287.000000, -19.000000)" stroke="#FFFFFF" stroke-width="2">
                                    <g transform="translate(295.500000, 27.500000) rotate(-315.000000) translate(-295.500000, -27.500000) translate(286.000000, 26.000000)"><path d="M0.5,1.5 L18.5,1.5" id="Line-Copy-2"></path>
                                    </g>
                                    <g transform="translate(295.500000, 27.500000) rotate(-45.000000) translate(-295.500000, -27.500000) translate(286.000000, 26.000000)"><path d="M0.5,1.5 L18.5,1.5" id="Line-Copy-2"></path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </a>
                    <div class="slideshow">
                        <div class="ratio-keeper">

                        </div>
                        <div class="transition">
                            <div style="width:100%;height:100%" class="react-player">
                                <div style="width:100%;height:100%">';
                $details .= '<iframe src="https://www.youtube.com/embed/' . $firstvid . '" id="vid_frame1"
                                    allowfullscreen
                                    webkitallowfullscreen
                                    mozallowfullscreen
                                    style="position: absolute; top: 0px; right: 0px; bottom: 0px; left: 0px; width: 100%; height: 100%;"></iframe>

                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="pagination">
                        <a class="pagination__prev disabled " role="button">
                            <img alt="left arrow" id="arrow-left" class="arrow" src="data:image/svg+xml;base64,PHN2ZyBoZWlnaHQ9IjIwIiB3aWR0aD0iMTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CiAgPHBhdGggZD0iTTEyLjAwNSAyLjM1N0w5LjcxOCAwIC4wMTEgMTBsOS43MDcgMTAgMi4yODctMi4zNTdMNC41ODcgMTB6IiBmaWxsPSIjZmZmIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiLz4KPC9zdmc+Cg=="><img alt="up arrow" id="arrow-up" class="arrow" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMiIgaGVpZ2h0PSI4Ij4KICA8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGZpbGw9IiNGRkYiIGQ9Ik0xMC41ODYgNy4xMjhMMTIgNS43NyA2IC4wMDYgMCA1Ljc3bDEuNDE0IDEuMzU4TDYgMi43MjNsNC41ODYgNC40MDV6Ii8+Cjwvc3ZnPgo="></a>
                            <h6><span >1</span> <span>/</span> <span class="pagination__current">' . $abc . '</span>
                            </h6><a class="pagination__next arrow-right" role="button">
                                <img alt="right arrow" id="arrow-right" class="arrow" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMiIgaGVpZ2h0PSIyMCI+CiAgPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBmaWxsPSIjRkZGIiBkPSJNLS4wMDUgMi4zNTdMMi4yODMgMGw5LjcwNiAxMC05LjcwNiAxMC0yLjI4OC0yLjM1N0w3LjQxNCAxMC0uMDA1IDIuMzU3eiIvPgo8L3N2Zz4K">
                                <img alt="down arrow" id="arrow-down" class="arrow" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMiIgaGVpZ2h0PSI4Ij4KICA8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGZpbGw9IiNGRkYiIGQ9Ik0xMC41ODYuOTY2TDEyIDIuMzA2IDYgNy45OTMgMCAyLjMwNiAxLjQxNC45NjYgNiA1LjMxMiAxMC41ODYuOTY2eiIvPgo8L3N2Zz4K">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            </div>';

            } else {
                $details .= '';
            }
        
        //------------------------------------------video_list end-------------------------------------------------------------------

        return $details;
    }

    public function support_sec($post_id)
    {
        $post_id = get_the_id();
        $support_result = ' <div class="item-overview-content">';
        $support_result .= '<div class = "item-support item-sec-div" id="support">';
        $support_result .= '<h3>What Support Does This Vendor Offer? </h3>';
        $support = get_field('support', $post_id);
        $item_title_name = get_the_title($post_id);
        $item_support_score = get_overall_combined_rating($post_id);

        // $support       = $item_support_score['list'][customersupport][score]*20
        // echo "item scoure";
        // print_r($item_support_score);
        $item_score=0;
        if(isset($item_support_score['list']['customersupport']['score'])){
            $item_score = $item_support_score['list']['customersupport']['score'] * 20;
        }
        

        $map = array("comments" => "Live Chat", "envelope" => "Mail", "phone" => "Phone", "wpforms" => "Forum");
        $mapped_support = array();
        $support_result .= '<div class="row"><div class = "col-sm-12"><div class = "col-sm-4">';
        if ($support) {
            $supportIcons = '<span class="support-icons"><ul>';
            foreach ($support as $ckey) {
                $mapped_support = $map[$ckey];
                $supportIcons .= "<li><i class='fa fa-$ckey'></i> $mapped_support </li>";
            }
            // $item_supports = implode(", " , $mapped_support);

            $supportIcons .= "</ul></span>";
            $support_result .= ' ' . $supportIcons . '';
        }
        $support_result .= ' </div>';
        // echo "item scoure";
        // print_r($item_support_score);
        $all_support_score_avg = $this->all_support_score_avg;

        $support_result .= '<div class = "col-sm-4" style="overflow: hidden;">';
        //    print_R($item_support_score);
        // echo "support score avg";
        // echo $all_support_score_avg;
        // echo "item score";
        // echo $item_score;
        if ($all_support_score_avg < $item_score) {
            $support_result .= '<img style="width: 100%;" src="' . esc_url(plugins_url('image/good-support.png', dirname(__FILE__))) . '">';

        } else {
            $support_result .= '<img style="width: 100%;" src="' . esc_url(plugins_url('image/poor-support.png', dirname(__FILE__))) . '">';
        }
        $support_result .= '</div>';
        $support_result .= '<div class = "col-sm-4">';

        $sliceStyle = 'style="
            clip: rect(auto, auto, auto, auto);
        "';
        $degree = $item_score * 3.6;
        if ($item_score > 50) {
            $style = 'style="
            -webkit-transform: rotate(180deg);
            -moz-transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            -o-transform: rotate(180deg);
            transform: rotate(180deg);
            position: absolute;
            border: 0.08em solid grey;
            width: 0.84em;
            height: 0.84em;
            clip: rect(0em, 0.5em, 1em, 0em);
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            -ms-border-radius: 50%;
            -o-border-radius: 50%;
            border-radius: 50%;

        "';
            $sliceStyle = 'style="
        clip: rect(auto, auto, auto, auto);
    "';
        } else {
            $style = "";
            $sliceStyle = "";
        }

        $support_result .= '          <div class="full"  style="text-align:  center;">
                                                <div class="c100 center" style="font-size: 100px;">
                                            <span style="color: #307bbb;">' . $item_score . '%</span>
                                            <div class="slice" ' . $sliceStyle . '><div class="bar" style="
                                            border: 0.08em solid grey;
                                            -webkit-transform: rotate(' . $degree . 'deg);
                                            -moz-transform: rotate(' . $degree . 'deg);
                                            -ms-transform: rotate(' . $degree . 'deg);
                                            -o-transform: rotate(' . $degree . 'deg);
                                            transform: rotate(' . $degree . 'deg);
                                        "></div><div class="fill" ' . $style .
            '></div></div>
                                        </div><br>
                                    </div>';

        if ($all_support_score_avg < $item_score) {
            $support_result .= 'satisfaction rating which indicates that you will be in good company with ' . $item_title_name . '';
            $avg_text = ' ' . $item_title_name . ' is above this industry average';

        } else {
            $support_result .= ' satisfaction rating which isnt the best and worth consideration if you feel you might need support.';
            $avg_text = ' ' . $item_title_name . ' is below this industry average';
        }

        $support_result .= '</div>';

        $all_support_score_per = $this->all_support_score_per;
        $support_result .= '</div>'; //col-sm-12
        $support_result .= '<div class = "col-sm-12">';
        $support_result .= '<p>Looking at data gathered on our platform the industry average for this category is
             ' . round($all_support_score_avg, 2) . ' % satisfaction rate which means  ' . $avg_text . '
             </p>';
        $support_result .= ' </div>';
        $support_result .= ' </div>';
        $support_result .= '</div>'; //item-support

        //screenshot

        $support_result .= '<div class = "item-screenshots item-sec-div" id="screenshot">';
        // $item_result .= '<h3>ScreenShots </h3>';
        /******************************************SCreenshort******************************************** */
        $gallery = get_field('gallery', $post_id);
        if (!empty($gallery) && is_array($gallery)) {

            $support_result .= '<div class="gallery-item left-content-box"><div class="gallery-title left-title"><h3 id="screenshots">Screenshots</h3></div> ';
            $thumbs = '';
            $slides = '';
            foreach ($gallery as $img) {

                $thumbs .= '<li> <img src="' . (isset($img['sizes']['thumbnail']) ? $img['sizes']['thumbnail'] : $img['url']) . '" /></li>';
                $slides .= '<li><a href="' . $img['url'] . '" data-fancybox="flex_images"><img src="' . $img['url'] . '" /></a> </li>';
            }
            $support_result .= '<div id="slider" class="flexslider">
            <ul class="slides">' . $slides . '</ul></div>
            <div id="carousel" class="flexslider">
            <ul class="slides">' . $thumbs . '</ul></div>';
            $support_result .= '</div>';
        }

        $support_result .= '</div>'; //item-screenshots

        $support_result .= '<div class="list-main-content left-content-box" id="faqs">';
        $faqs = '<div id="overview">
            <h3>Frequently Asked Questions</h3>

            <div>
            <span itemprop="description"> ' . $this->get_faqs($post_id) . '</span>
            </div>
            </div>
            ';
        $support_result .= $faqs;
        $support_result .= '</div>';
        // $support_result .= '<div style="display: inline-block;" class="col-sm-12">'.do_shortcode('"[rwp_box id="0"]"').'</div>';
        return $support_result;

    }

    /********************************************************item page content function start******************************************************** */
    public function item_content_value($post_id)
    {
        //echo "cp8";
        $findrScore = $this->findrScore; //calculate_findrscore_individual($post_id);

        $item_title = get_the_title($post_id);
        $postDat = get_post($post_id);
        $contentP = $postDat->post_content;

        $categories = $this->categories; //for managed by
        $author_id = get_field('real_author', $post_id);
        $author_name = get_the_author_meta('display_name',$author_id);//get_author_name($author_id);
        $category_names = ''; //for category
        if ($categories) {
            foreach ($categories as $cat) {
                $category_names .= '<span class="cat-name"><a href="' . esc_url(get_term_link($cat->term_id)) . '" title="' . $cat->name . '">' . $cat->name . '</a></span> ,&nbsp;';
            }
        }
        /****************************************************PROS AND CONS**************************************************************** */

        // print_r($r2b);
        $rnr2b=get_pros_cons($post_id);
	$r2b = $rnr2b['r2b'];
	$rn2b = $rnr2b['rn2b'];
        $htmlr2b = '<ul>';
        foreach ($r2b as $r) {
            $htmlr2b .= '<li>' . $r . '</li>';
        }
        $htmlr2b .= '</ul>';
        // print_r($rn2b);
        $htmlrn2b = '<ul>';
        foreach ($rn2b as $r) {
            $htmlrn2b .= '<li>' . $r . '</li>';
        }
        $htmlrn2b .= '</ul>';
        $proscons = '<div id="pros-cons" class="row">
        <div class="col-md-6 pros">
        <div class="mr-5 pr-5 col-md-6-inner">
        <h4 class="mygreen"> <i class="fa fa-check-circle mh10" aria-hidden="true"></i>' . count($r2b) . ' Reasons to buy</h4>' . $htmlr2b . '

        </div></div>
        <div class="col-md-6 cons">
        <div class="ml-5 pl-5 col-md-6-inner">
        <h4 class="myred"><i class="fa fa-times-circle mh10" aria-hidden="true"></i>' . count($rn2b) . ' Reasons not to buy</h4>
        ' . $htmlrn2b . '
        </div>
        </div>
    </div>';
        /**********************************************PROS AND CONS************************************************************************** */

        
        //echo "cp11";
        $review_id = get_the_ID(); //alternative grid
        $html = '';
        $compObj = new Mv_List_Comparision(null,true);
        $lists = $compObj->most_compared($review_id, 20, true);

        /**********************************************alternative informatoion ************************************************************************** */
        //manage for calculation

        $fsArr30_previous_fs = get_post_meta($post_id, 'findrScore30', true);

        $current_fs = round($findrScore);
        $previous_fs = $fsArr30_previous_fs['fs'];
        //    echo "current fs ".$current_fs." previous fs ".$previous_fs;

        if ($current_fs > $previous_fs) {
            $fs_result = "The score for this software has increased over the past month.";
        } elseif ($current_fs < $previous_fs) {

            $fs_result = "The score for this software has declined over the past month.";
        } else {
            $fs_result = "The score for this software has not changed over the past month.";

        }
        //echo "cp12";
        $data_point = get_data_points_list_item($post_id);
        add_datapoints_databse($post_id, 'list_item', $data_point);
        //  echo $data_point;
        $item_result = '<div class = "item-overview-content">';
        $item_result .= '<div class = "item-content-sec1">';
        $item_result .= '<p><b>Manage by :&nbsp;</b>' . $author_name . ' <b>| Category : </b> ' . $category_names . ' | ' . $fs_result . ' </p>';

        /* echo "the date";
        echo get_the_date( 'U', 123082 );
        echo "days"; */
        $item_is_new='';
        if ((time() - get_the_date('U', $post_id)) / 60 / 60 / 24 < 7) {
            $item_is_new = $item_title . ' is new to our platform, so please allow some more time for our algorithm to gather more data on  ' . $item_title . '. That been said here is what we’ve analyzed so far';
        }
/*         echo "max";
        echo $this->highest_fs;
        echo "min";
        echo $this->min_fs_list;
 */        $benchmark = $this->min_fs_list + ($this->highest_fs - $this->min_fs_list)*3/4;
/*         echo "benchmark";
        echo $benchmark;
        
 */     
        $topQuad='';
        if($benchmark < $findrScore){
            $topQuad = ' which is in the top quadrant in this segment';
        }
        $reasonablePrice = '';
        if(strcasecmp($this->price_item, 'Low cost software') == 0){
            $reasonablePrice = 'It’s also priced reasonably with the occasional promotions.';
        }
        $custSupport='';
        if(strcasecmp($this->bestForCustSupport, 'Customer support')==0){
            $custSupport=' Many users on our platform to express great satisfaction in the service provided overall.';
        }
        $easeOfuse = '';
        if(strcasecmp($this->ease_dis, 'Ease of use')==0){
            $easeOfuse = ' If you are not tech-savvy then you will be pleased to know ' . $item_title . ' is fair easy to grasp.';
        }
        $item_result .= '<div class = "item-content-editor item-sec-div"> <b>Editors note </b> - This review has been created by looking at ' . $data_point . ' datapoints and the opinions of actual users of the software and the company representative.

        ' . $item_is_new . $item_title . ' as a FindrScore of ' . round($findrScore) . $topQuad.'.'.$reasonablePrice. $custSupport.$easeOfuse.'
        </div>';
        // $item_result .= $conlast;
        // echo "contentP";
        // echo $contentP;
        $item_result .= '<div id="overview">
         <div class="overview-content item">' . $contentP . '</div></div><a href="javascript:;" class="readbutton">Read More >>></a>
      ';

        $item_result .= $this->adaptation_of_geo_map($post_id);
     
        $item_result .= '<div class = "item-feature item-sec-div">';
        $item_result .= '<div class="list-main-content left-content-box" id="pros-cons">';
        $item_result .= '<h3> Pros & Cons </h3>';
        $item_result .= $proscons;
        $item_result .= '</div>';
        $item_result .= '</div>'; //pros and cons

      

        $item_result .= '<div class = "item-sec-div" >';
        $item_result .= '<h3>Ranking & Positions </h3>';

        $item_result .= '</div>'; //item-videos item-sec-div
        $item_result .= '</div>'; //item-content-sec1 div
        $item_result .= '</div>'; //item-overview-content div
        return $item_result;
    }

    public function list_geo_map($post_id)
    {
        $item_result = '<div class = "item-map item-sec-div" >';
        $item_result .= '<span style="display:block"><strong>Adaptation by Geography </strong></span>';
        /************************************************************chart******************************************************************* */

        $list_setting = get_option('mv_list_items_settings');
        $target_countries = $list_setting['list_page_target_countries'];
        $countryPair = explode(',', $target_countries);
        foreach ($countryPair as $cp) {
            $pair = explode('=>', $cp);
            foreach ($pair as $key => $value) {
                $pair[$key] = trim(trim($value), "'");
            }

            $targetCountries[$pair[0]] = $pair[1];
        }
        $item_one = get_the_title($post_id);

        $post_id = get_the_id();
        $lists = get_field('list_items', $post_id, false);
        // echo "compared data";
        // print_r($lists);

        $passToMaps = array();
        $downloads1 = array();
        foreach ($lists as $post_idss) {
            foreach ($targetCountries as $key => $tc) {
                if(!isset($downloads1[$tc])){
                    $downloads1[$tc] = 0;
                }
                $thisDownload = ceil((float) get_field('downloads_in_' . $key, $post_idss));
                if(is_numeric($thisDownload)){
                    if($thisDownload>0){
                        
                        $downloads1[$tc] += $thisDownload;
                    }
                    
                }
               

                // echo "download 111list ";
                // print_r( $downloads1);

               /*  if ($downloads1 == '') {
                    $downloads1 = 0;
                } */

            }

        }

        $passToMaps[] = array('Country', $post_idss);

        arsort($downloads1);
        // var_dump($downloads1);
        $sum_value = 0;
        foreach ($downloads1 as $download) {

            if ($download >= 0) {
                $sum_value += $download;
            }

            //     echo "downaload status";
            //   print_r($downloads1);
            //   print_r($sum_value);

        }
        $country_name='';
        foreach ($downloads1 as $key => $values) {
            if ($values >= 0) {
                $passToMaps[] = array($key, $values);
                //   echo "maps-data";
                //   print_r($passToMaps);
                $value_per = ($values * 100) / $sum_value;
            } else {
                $value_per = 0;
            }

            $country_name .= '<tr><td>' . $key . '</td> <td>' . round($value_per, 2) . '%</td></tr>'; // decending order country usage

            if ($values != 0) {
                $popular_countries[] = $key;

                //  echo "copuntry--123";
                //  print_r($popular_countries);

            }
        }
        $popular_countries2 = array_slice($popular_countries, 0, 2);
        //   echo "popular_countriesiiiii";
        //         print_r($popular_countries2);

        // $last_element = array_pop($popular_countries);

        $countries = trim(implode(",", $popular_countries2), ',');
        if (count($popular_countries) > 2) {
            $countries .= (' and ' . (count($popular_countries) - 2) . ' Other Countries.');
        }

        $item_title = get_the_title($post_id);
        if ($sum_value == 0) {
            $map_data = "There’s not enough data available for $item_title to display its geography adaption trend. We are still collecting data on  $item_title adoption check again soon. ";
        } else {
            $map_data = "After analyzing our data gathered on our platform $item_title is popular in these countries $countries ";

            $item_result .= '<div id="list_map_data" ></div>';
            $item_result .= '<div class="country-box">';
            $item_result .= '<div class="box1">
          <table id="countries_list">';
            $item_result .= '<tr><th>Country</th><th>Usage</th>';
            $item_result .= ' ' . $country_name . '';
            // $item_result .= 'Usage <li> '. $uasge .'</li>';

            $item_result .= '</table></div> </div>';

        }

        $item_result .= '<span>'.$map_data.'</span>';

        ?>
        <script>
                google.charts.load('current', {
                    'packages':['geochart'],
                    'mapsApiKey': 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'
                });
                google.charts.setOnLoadCallback(drawRegionsMap);

                function drawRegionsMap() {
                    var data = google.visualization.arrayToDataTable(
                        <?php echo json_encode($passToMaps) ?>
                    );

                    var options = {
                    };

                    var chart = new google.visualization.GeoChart(document.getElementById('list_map_data'));

                    chart.draw(data, options);


                }

        </script>

      <!-- /************************************************************chart******************************************************************* */ -->
      <?php
$item_result .= '</div>'; //item-map div

        return $item_result;

        // return $mapdata;

    }
    public function adaptation_of_geo_map($post_id)
    {

        // $post_id = get_the_id();
        // echo $post_id;
        $item_result = '<div class = "item-map1 item-sec-div mapp-sec" id="geographicapaption">';
        $item_result .= '<h3>Adaptation by Geography </h3>';

        /************************************************************chart******************************************************************* */

        $list_setting = get_option('mv_list_items_settings');
        $target_countries = $list_setting['list_page_target_countries'];
        $countryPair = explode(',', $target_countries);
        foreach ($countryPair as $cp) {
            $pair = explode('=>', $cp);
            foreach ($pair as $key => $value) {
                $pair[$key] = trim(trim($value), "'");
            }

            $targetCountries[$pair[0]] = $pair[1];
        }
        $item_one = get_the_title($post_id);
        $passToMaps = array();
        $passToMaps[] = array('Country', $item_one);

        foreach ($targetCountries as $key => $tc) {

            $downloads1[$tc] = ceil((float) get_field('downloads_in_' . $key, $post_id));

            if ($downloads1 == '') {
                $downloads1 = 0;
            }

        }
        arsort($downloads1);
        // var_dump($downloads1);
        $sum_value = 1;
        foreach ($downloads1 as $download) {
            if ($download >= 0) {
                $sum_value += $download;
            }

        }
        $country_name='';
        $popular_countries=array();
        foreach ($downloads1 as $key => $values) {
            if ($values >= 0) {
                $passToMaps[] = array($key, $values);

                $value_per = ($values * 100) / $sum_value;
            } else {
                $value_per = 0;
            }

            $country_name .= '<tr><td>' . $key . '</td> <td>' . round($value_per, 2) . '%</td></tr>'; // decending order country usage

            if ($values != 0) {
                $popular_countries[] = $key;
            }
        }

        $popular_countries2 = array_slice($popular_countries, 0, 2);
        // $last_element = array_pop($popular_countries);

        $countries = trim(implode(",", $popular_countries2), ',');
        if (count($popular_countries) > 2) {
            $countries .= (' and ' . (count($popular_countries) - 2) . ' Other Countries.');
        }

        $item_title = get_the_title($post_id);
        if ($sum_value == 0) {
            $map_data = "There’s not enough data available for $item_title to display its geography adaption trend. We are still collecting data on  $item_title adoption check again soon. ";
        } else {
            $map_data = "After analyzing our data gathered on our platform $item_title is popular in these countries $countries ";

            $item_result .= '<div id="regions_div" ></div>';
            $item_result .= '<div class="country-box">';
            $item_result .= '<div class="box1">
          <table id="countries_list">';
            $item_result .= '<tr><th>Country</th><th>Usage</th>';
            $item_result .= ' ' . $country_name . '';
            // $item_result .= 'Usage <li> '. $uasge .'</li>';

            $item_result .= '</table></div> </div>';

        }

        $item_result .= '<span>'.$map_data.'</span>';

        ?>
        <script>
                google.charts.load('current', {
                    'packages':['geochart'],
                    'mapsApiKey': 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'
                });
                google.charts.setOnLoadCallback(drawRegionsMap);

                function drawRegionsMap() {
                    var data = google.visualization.arrayToDataTable(
                        <?php echo json_encode($passToMaps) ?>
                    );

                    var options = {
                    };

                    var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

                    chart.draw(data, options);


                }

        </script>

      <!-- /************************************************************chart******************************************************************* */ -->
      <?php
$item_result .= '</div>'; //item-map div

        return $item_result;
    }

    /********************************************************item page content function End******************************************************** */

    public function get_single_sidebar($post_id)
    {
        $affiliate_url = $this->affiliate_url;
        $affiliate_button_text = $this->affiliate_button_text;
        $source_url = $this->source_url;
        $credit = $this->credit_text;
        $pricing_model = $this->pricing_model;
        $product_type = $this->product_type;
        $editor_choice = $this->editor_choice;
        $software = $this->software;
        $categories = $this->categories;
        $tags = $this->tags;
        $support = get_field('support', $post_id);
        $category_names = '';
        $tag_names = '';
        if ($categories) {
            foreach ($categories as $cat) {
                $category_names .= '<span class="cat-name"><a href="' . esc_url(get_term_link($cat->term_id)) . '" title="' . $cat->name . '">' . $cat->name . '</a></span>&nbsp;';
            }
        }
        if ($tags) {
            foreach ($tags as $tag) {
                $tag_names .= '<span class="round-tags"><a href="' . esc_url(get_term_link($tag->term_id)) . '" title="' . $tag->name . '">' . $tag->name . '</a></span>&nbsp;';
            }
        }
        $img = get_the_post_thumbnail($post_id, 'medium', array('itemprop' => 'image'));
        ob_start();
        ?><div class="item-other-details sidebar-column">
        <div class="item-thumb-new"><span ><?php echo $img; ?></span>
       </div>

        <div class="it-ot-title-new"><span itemprop="name"><?php echo get_the_title($post_id); ?> Details</span></div>
        <?php
echo '<ul class="product-details-new">';

        if ($categories) {
            echo '<li><span class="pro-dtl-heading" itemprop="category">Category: ' . $category_names . '</span></li>';
        }

        if ($tags) {
            echo '<div><li><span class="pro-dtl-heading">Industry</span>: ' . $tag_names . '</li></div>';
        }
        if ($support) {
            $supportIcons = '<span class="support-icons">';
            foreach ($support as $ckey) {
                $supportIcons .= "<i class='fa fa-$ckey'></i>";
            }
            $supportIcons .= "</span>";
            echo '<li><span class="pro-dtl-heading">Support</span>: ' . $supportIcons . '</li>';
        }
        echo '</ul>';

        /*----------------------------claim button-----------------------------------*/

        $post_id = get_the_ID();
        $current_user = wp_get_current_user();
        $user_id_mv = $current_user->ID;
        echo "<i class='fa fa-question-circle' aria-hidden='true'></i> <span class='claim-listingheading'>Claim this Listing</span>
		 <a class='claim-listing-button' onclick='claim_listing_func(1," . $post_id . "," . $user_id_mv . ")'> Is this your product? </a>";

        /*----------------------------claim button end -----------------------------------*/
        ?>

		  </div>

        <?php
$reviews = get_overall_combined_rating($post_id);
        // print_r($reviews);
        //    $rev = new RWP_Rating_Stars_Shortcode();
        //    $reviews = $rev->combined_review($post_id,'rwp_template_590f86153bc54');
        if (!empty($reviews) && is_array($reviews)) {
            ?>
            <div class="review-detail-col sidebar-column">


                <div class="it-ot-title-new"><?php echo get_the_title($post_id); ?> Reviews</div>
                <?php
foreach ($reviews['list'] as $kk => $rev) {
                $percen = ($rev['score'] / 5) * 100;
                $percen = round($percen);
                ?>
                    <div class="prog-container">
                        <div class="prog-label"><span class="prg-label"><?php echo $rev['label'] ?></span><span class="prg-valval"> <?php echo $percen; ?>%</span> </div>
                        <div class="progress">
                            <div data-name="<?php echo $kk; ?>" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php $rev['score'];?>" aria-valuemin="0" aria-valuemax="5" style="width:<?php echo $percen; ?>%">
                            </div>
                        </div>
                    </div>
                    <?php
}
            ?>
            </div>
            <?php
}
        ?>
        <div class="compare-detail-col sidebar-column">
            <div class="it-ot-title-new">Compare <?php echo get_the_title($post_id); ?> to Similar Solutions</div>
            <?php
$compObj = new Mv_List_Comparision(null,true);
        $returns = $compObj->most_compared($post_id);

        if (!empty($returns) && is_array($returns)) {

            $titleCurr = get_the_title($post_id);

            //$imagecurr = get_thumbnail_small($post_id,array(100,100));
            $imagecurr = get_the_post_thumbnail_url($post_id, array(100, 100));

            echo '<ul class="comparison-list">';

            foreach ($returns as $id) {
                $com_id = get_the_title($id);

                $item_image = get_the_post_thumbnail_url($id, array(100, 100));

                ?>

                    <?php

                echo '<li><a href="' . generate_compare_link(array($post_id, $id)) . '/" class="new-comparison-btn ls_referal_link" data-parameter="itemid" data-id="' . $post_id . '" data-secondary="' . $id . '"><span class="cp-item1"><img src="' . $imagecurr . '" class="img-responsive sss" alt="' . get_the_title($post_id) . '" ><span class="cp-title">' . $titleCurr . '</span> </span><span class="cp-vs"><span class="cp-vs-inner">vs</span></span><span class="cp-item2-footer"> <img src="' . $item_image . '" class="img-responsive sss" alt="' . get_the_title($id) . '" ><span class="cp-title">' . get_the_title($id) . '</span></span> </a></li>';

            }
            echo "</ul>";
        }
        ?>
            <!--<div class="new-comparison-btn-container"> <a href="javascript:;" class="new-comparison-btn" data-id="--><?php //echo $post_id?><!--" data-secondary="0">New Comparison</a></div>-->
        </div>
        <?php

        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
/* -----------------------------------------------get_single_sidebar fxn close--------------------------------------------------------------*/
  
    public function get_single_list_item_content($post_id, $content)
    {
        $findrScore = $this->findrScore;
        // echo "fs of this item ".$findrScore;
        //calculating fs for whole list
        
        $all_item_id = $this->industry_items;
       /*  print_r($this->industry_items);
        echo "all item id printed";
        echo "count of all item id is : ".count($all_item_id);
        echo "count of all item id distinct : ".count(array_unique($all_item_id));
        print_r($all_item_id);  */

        foreach ($all_item_id as $all_items) {
            $rat = get_overall_combined_rating($all_items); //Overall combined rating
            if(isset($rat['list']['easeofuse']['score'])){
                $easeofuse[] = $rat['list']['easeofuse']['score'];
            }
            if(isset($rat['list']['easeofuse']['score'])){
                $functionality_feature[] = $rat['list']['easeofuse']['score'];
            }
            if(isset($rat['list']['valueformoney']['score'])){
                $value_money[] = $rat['list']['valueformoney']['score'];
            }
            if(isset($rat['list']['customersupport']['score'])){
                $customersupport[] = $rat['list']['customersupport']['score'];
            }
            $price = get_field('price_starting_from', $all_items);
            if(isset($rat['list']['overallrating']['score'])){

                $this->overallindustryratings[] = $rat['list']['overallrating']['score'];
            }
            // do_shortcode( '[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=20 post='.$post_id.']' );
            $price_trim[] = (float) filter_var($price, FILTER_SANITIZE_NUMBER_INT);//str_replace("$", "", $price);
            $all_features_ratings[$all_items] = get_field('features_list_ratings', $all_items);
            $all_item_findrscore[$all_items] = get_or_calc_fs_individual($all_items);
            $all_pricing_model[$all_items] = get_field('pricing_model', $all_items);
        }

        /* findrscore highest and lowest* */
        arsort($all_item_findrscore);

        // print_r($all_item_findrscore);
        $this->highest_fs = max($all_item_findrscore);
        $min_fs_list = $this->min_fs_list = min($all_item_findrscore);
        $all_fs_sum = array_sum($all_item_findrscore);
        // echo "all fs sum".$all_fs_sum;
        $this->all_fs_avg = $all_fs_sum / count($all_item_id);
       
        foreach ($all_item_findrscore as $key => $findrscore) {

            $this->fs_list = $all_item_findrscore;

            if ($findrscore <= 50) {

                $all_weak_fs_title[$key] = $findrscore;
            } else {

                $all_highest_fs[$key] = $findrscore;
            }

        }

        // end of calculating fs

        $title = get_the_title($post_id) . " Reviews: Pricing and Features";
        // $rating_item = do_shortcode('[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=20 post=' . $post_id . ']');
        $rat = get_overall_combined_rating($post_id);
        $nor = $rat['count']; //$this->get_number_of_reviews($rating_item);
        //     echo "nor is ";
        // echo $nor;
        /* if($nor > 1){
        echo "nor is above 10";
        }
        else{
        echo "nor is below 10";
        } */
        $reviews = $feature = get_overall_combined_rating($post_id); // For Accounting featture
        $this_ease_of_use = 0;
            $this_value_for_money = 0;
            $this_customer_support = 0;
            $this_features_functionality =0;
        if(isset($reviews['list']['easeofuse']['score'])){
            $this_ease_of_use = $reviews['list']['easeofuse']['score'];
            $this_value_for_money = $reviews['list']['valueformoney']['score'];
            $this_customer_support = $reviews['list']['customersupport']['score'];
            $this_features_functionality = $reviews['list']['featuresfunctionality']['score'];
        }
      
        $this_features_functionality *= 2;
        $this_features_functionality_percentage = $this_features_functionality * 10;
        $this_ease_of_use *= 2;
        $this_ease_of_use_percentage = $this_ease_of_use * 10;
        $this_value_for_money *= 2;
        $this_value_for_money_percentage = $this_value_for_money * 10;
        $this_customer_support *= 2;
        $this_customer_support_percentage = $this_customer_support * 10;

        /******************************************FOr NEW LAYOUT end******************************* */
        //    $video_List  = get_post_meta($post_id, 'video_list', true);
        $compObj = new Mv_List_Comparision(null,true);
        ////////////////////////////Graphs_start/////////////////////////////////////////////////////////
        $lists = $compObj->most_compared($post_id, 1000, true);
        $onetime = 0;
        $subscription = 0;
        $opensource = 0;
        $freemium = 0;
        $free = 0;
        $others = 0;
        $size = sizeof($lists);
        foreach ($lists as $pid) {
            $pricing_model = get_field('pricing_model', $pid);
            if (empty($pricing_model)) {
                $pricing_model = array();
            }
            $counts = array_count_values($pricing_model);

            if (in_array("freemium", $pricing_model)) {
                $freemium += $counts['freemium'];
                continue;
            } elseif (in_array("open_source", $pricing_model)) {
                $opensource += $counts['open_source'];
                continue;
            } elseif (in_array("subscription", $pricing_model)) {
                $subscription += $counts['subscription'];
                continue;
            } elseif (in_array("one_time_license", $pricing_model)) {
                $onetime += $counts['one_time_license'];
                continue;
            } else {
                $others = $others + 1;
            }

        }
        foreach ($lists as $pid) {
            $free_trial = get_field('free_trial', $pid);
            if ($free_trial == 1) {
                $free += $free_trial;
            }
        }
        $items_ratio = array("freemium" => $freemium, "subscription" => $subscription, "open_source" => $opensource, "one_time_license" => $onetime, 'size' => $size, 'free' => $free, "others" => $others);

        $freetr = $items_ratio['free'];
        $total = $items_ratio['size'];
        //$subs   = ($item['subscription'] / $total) * 100;
        $subs = $items_ratio['subscription'];
        $freem = $items_ratio['freemium'];
        $openso = $items_ratio['open_source'];
        $onet = $items_ratio['one_time_license'];
        $total1 = $subs + $openso + $onet + $freem;
        if($total1==0){
            $total1=1;
        }
        $subscription = ($subs / $total1) * 100;
        $onetime = ($onet / $total1) * 100;
        $free = ($openso / $total1) * 100;
        $freetrial = ($freetr / $total1) * 100;
        $freemium = ($freem / $total1) * 100;

        $l = 0;
        $lists_atlter = $compObj->most_compared($post_id, 1000, true);
        foreach ($lists_atlter as $idss) {
            $pricing_model[$l] = get_field('pricing_model', $idss);
            if (empty($pricing_model[$l])) {
                $pricing_model[$l] = array();
            }
            if (in_array('open_source', $pricing_model[$l])) {
                $price_starting_from = 0;
            } else {
                $price_starting_from = get_field('price_starting_from', $idss);
            }
            $item_pmodel[]['pmodel'] = str_replace("$", "", $price_starting_from);
            $l++;
        }
      
        ?>

						        <input type="hidden" id="subscription" value="<?php echo $subscription; ?>">
                                <input type="hidden" id="free" value="<?php echo $free ?>" />
                                <input type="hidden" id="onetime" value="<?php echo $onetime ?>" />
                                <input type="hidden" id="freetrial" value="<?php echo $freetrial ?>" />
                                <input type="hidden" id="freemium" value="<?php echo $freemium ?>" />
                                <input type="hidden" id="total" value="<?php echo $total ?>" />
                                <input type="hidden" id="maxo" value="<?php echo htmlspecialchars(json_encode($item_pmodel)) ?>" />

		<?php
////////////////////////////Graphs_end/////////////////////////////////////////////////////////

        $affiliate_url = $this->affiliate_url;

        $compareCat = $this->comp_categories;
        $compSlug = array();

        if (is_array($compareCat)) {
            foreach ($compareCat as $cc) {
                $compSlug[] = $cc->taxonomy . ':' . $cc->slug;
            }
        }
        $this->array_head = array('overview' => 'Overview');

        

        $firldsGroups = get_acf_field_groups_by_cpt($compSlug);
        $video = get_field('video', $post_id);
        // $video = "https://www.youtube.com/embed/Sizwu58wkeI";
        if ($video != '') {
            // echo "not empty";
            preg_match_all('/src="([^\?]+)/', $video, $match);

            $url = $match[1][0];
            $url = str_replace('embed/', 'watch?v=', $url);
        } else {
            // echo "empty";
        }
        // echo "got this from video";
        // echo $video;
        // echo "end of got this from video";

        //echo count($video);
        // find all iframes generated by php or that are in html

        //        array_push($video_list_new, $url );
        $gallery = get_field('gallery', $post_id);
        $altername = $this->get_alternate_items();
        if (!empty($firldsGroups)) {
            $this->array_head['features'] = 'Features';
        }
        //        update_post_meta($post_id, 'video_list' ,array($url));
        $video_list_new = array_unique(get_post_meta($post_id, 'video_list', true));
        foreach ($video_list_new as $key => $video) {
            if (trim($video) == '') {
                unset($video_list_new[$key]);
            }
        }
        $video_list_new = array_values($video_list_new); //re-index after unset
        //        echo "video_list new after get post meta";
        //        print_r($video_list_new);
        //        echo $video_list_new;
        /* if(isset($url)){
        echo "url is set";
        echo $url;
        }
        else{
        echo "url is not set";
        }
         */if (isset($url) && !in_array($url, $video_list_new)) {
            $video_list_new[] = $url;

            update_post_meta($post_id, 'video_list', $video_list_new);
        }
            if (!empty($this->ranklist)) {
                $this->array_head['geographicapaption'] = 'Geographic Adaption';   
            } 
            $this->array_head['pros-cons'] = 'Pros & Cons';
            if (!empty($this->ranklist)) {
                $this->array_head['awards'] = 'Awards';
            } 
            $features_list = get_field('features_list', $post_id);
            if(!empty($features_list)){
            $this->array_head['key-feature'] = 'Key Features ';
            }
           
            $inetgartion = get_field('integrate_with_item', $post_id, false);
            if(!empty($inetgartion )){
            $this->array_head['integration'] = 'Integration';
            }
            $this->array_head['pricing'] = 'Pricing';
            $this->array_head['price-comp'] = 'Price Comparison';
            if(!empty($this->price_performance_chart)){
            $this->array_head['scatterchart_material'] = 'Price Comparision';
            }
          
            $this->array_head['market-overview'] = 'Market Overview';
            
            
            $this->array_head['compare'] = 'Versus';
            
            if (!empty($altername)) {
                $this->array_head['alternative'] = 'Alternative';
            }        

      
            if (!empty($video_list_new)) {
                $this->array_head['videos'] = 'Videos';
            }     
         $this->array_head['support'] = 'Support';
            
        if (!empty($gallery)) {
            $this->array_head['screenshots'] = 'Screenshots';
        }
        $this->array_head['faqs'] = 'FAQs';
        
        $this->array_head['reviews'] = 'Reviews';
        
       
        $lis = '';
        foreach ($this->array_head as $hkey => $head) {
            $lis .= '<li><a href="#' . $hkey . '">' . $head . '</a> </li>';
        }
        $affiliate_url = $this->affiliate_url;
        $affiliate_button_text = $this->affiliate_button_text;
        $source_url = $this->source_url;
        $credit_text = $this->credit_text;
     

        $item_images = get_the_post_thumbnail_url($post_id);?>

        <?php
// $item_title = get_the_title($post_id);
        $postDat = get_post($post_id);
        $contentP = $postDat->post_content;

        
        //        $btnhtml = '<a class="mes-lc-li-down aff-link head-links" href="'.$afflink.'" rel="nofollow" target="_blank">'.$btntext.'</a>';
    //     $revHeader = "<div id='review-fix-header'>
    // </div>";
        $conlast = '<div id="overview">
                        <div class="overview-content item-content">
                    
                        <div >
                          <div itemprop="description" style="
                          height: 124px;
                          overflow: hidden;
                          display: inline-block;
                          width: -moz-available;
                          width: -moz-available;
                          width: -webkit-fill-available;
                      ">'.$contentP.'...</div>

                        </div>
                        </div>
                    </div>
    ';

        $affiliate_button_text = $this->affiliate_button_text;
        $source_url = $this->source_url;
        $credit = $this->credit_text;
        // $details = $revHeader;
        $home_url = get_home_url();
        
        $current_user = wp_get_current_user();
        $user_id_mv = $current_user->ID;
        $details = '
        <div class="breadcrum">
            
                <div style="
                padding-left: 6%;
            "><span><a href="'.$home_url.'">Home</a> > <span class="breadcrumb_last" aria-current="page">' . get_the_title($post_id) . '</span></span></div>
                <div style="
                padding-right: 6%;
                text-align: right;
            ">Do you work for this company? <a class="claim-listing-button" onclick="claim_listing_func(1,' . $post_id . ',' . $user_id_mv . ')" href="#">Manage this listing</a></div>
                </div>';

        $details .= '<div class="full-width-full item-container">';

        $details .= '<div class="item_list_page_container">
        <div class="title-container" >
                    <h1 class="entry-title1"><span itemprop="name">' . $title . '</span></h1>
        </div>
        ';
        // echo "check2 ".time();
        /*------------------------------------------------------section1 start-----------------------------------------------------------*/

        /* findrscore highest and lowest* */

        /* item details */
        $item_id = get_the_id();
        $item_overall = get_overall_combined_rating($item_id);
        $item_pricing_model = get_field('pricing_model', $item_id);
        // echo "item pricing model";
        // print_r($item_pricing_model);
        $this_pricing_model="";
        $this_pricing_plan = get_field('plan', $item_id);
        //   echo "<br>this pricing plan ".$this_pricing_plan;
        $this_item_price_start = get_field('price_starting_from', $item_id);

        $this_item_price = (float) filter_var($this_item_price_start, FILTER_SANITIZE_NUMBER_INT); //item price
        if ($this_pricing_plan == 'Year') {
            $this_item_price /= 12;
        }

        // echo "<br> this item price ".$this_item_price;
        // if( in_array('one_time_license',$item_pricing_model))
        $all_price_avg = 0;
        if (in_array('freemium', $item_pricing_model) || in_array('open_source', $item_pricing_model)) {
            $this->price_item = "Low cost software";
        }else{

        
        if ($this_item_price > 0) {

            if ($this_pricing_plan == "One Time") {
                $this_pricing_model = 'one_time_license';

            } elseif ($this_pricing_plan == "Year" || $this_pricing_plan == "Month") {
                $this_pricing_model = 'subscription';
            }
        } 
        // echo "this pricing model ".$this_pricing_model;
        // echo "<br>all pricng model";
        //  print_r($all_pricing_model);
       
        if ($this_pricing_model == "subscription" || $this_pricing_model == "one_time_license") {

            foreach ($all_pricing_model as $key => $pricng_model) {

                if (!in_array($this_pricing_model, $pricng_model)) {
                    unset($all_pricing_model[$key]);
                } else {
                    $item_pricing_plan[$key] = get_field('plan', $key);

                    // print_r($item_pricing_plan);

                    if ($this_pricing_model == 'subscription') {
                        $price = get_field('price_starting_from', $key);
                        // $price = str_replace("$","",$price_start);
                        // echo "price<br>";
                        // print_r( $price);
                        $int = (int) filter_var($price, FILTER_SANITIZE_NUMBER_INT);
                        if ($item_pricing_plan[$key] == 'Year') {

                            $price_convt = $int / 12;
                            // echo "int<br>";
                            // print_r($int);
                            // echo "end";
                            //  print_r($price_convt);
                            //  echo "pricecnt";

                            // echo "price start";
                            // print_r($price_start);
                            if ($price_convt > 0) {
                                $price_start[$key] = $price_convt;
                            }

                        } elseif ($item_pricing_plan[$key] == 'Month') {
                            if ($int > 0) {
                                $price_start[$key] = $int;
                            }

                        }

                    } elseif ($this_pricing_model == 'one_time_license') {
                        if ($item_pricing_plan[$key] == 'One Time') {
                            $price = get_field('price_starting_from', $key);
                            // print_r($price);

                            $int = (int) filter_var($price, FILTER_SANITIZE_NUMBER_INT);
                            // echo "int is ".$int;
                            if ($int > 0) {
                                $price_start[$key] = $int;
                            }

                        }

                    }

                }

            }
            // echo "price_start";
            // print_r($price_start);
            $price_sum = array_sum($price_start);
            // echo "price sum".$price_sum;
            $all_price_avg = $price_sum / count($price_start); // all items avg price in a sorted way

        }}
        // echo "item price".$this_item_price;
        // $this->price_item = $int;

        $over_high_score = max($this->overallindustryratings); //overall highest score
        $item_feature_rating = get_or_create_feature_ratings($item_id); //get_field( 'features_list_ratings', $item_id );
        // echo "item rat score";
        // print_r($item_rat_score);
        // echo "<br>item feature rating<pre>";
        // print_r($item_feature_rating);
        $item_feature_individual_avg = array();
        if(is_array(  $item_feature_rating)){
            foreach ($item_feature_rating as $key => $item_rat) {
                $item_feature_individual_avg[$key] = $item_rat['average'];
    
            }
        }
        
        if(!empty($item_feature_individual_avg)){
            arsort($item_feature_individual_avg);
        }
        
      /*   echo "all features ratings";
        print_r($all_features_ratings); */
        if(is_array($all_features_ratings)){

        
            foreach ($all_features_ratings as $feature_rat) {
                if(is_array($feature_rat)){
                    foreach ($feature_rat as $key => $feature) {
                    // $total_score_feature_ind_avg[$key] = $feature[average];

                    if (!isset($total_score_feature[$key]) || !is_array($total_score_feature[$key])) {
                        $total_score_feature[$key] = array('total_score'=>0,'votes'=>0,'average'=>0);
                    }
                    if ($feature['average'] != 0) {
                        $total_score_feature[$key]['total_score'] += $feature['average'];
                        $total_score_feature[$key]['votes'] += 1;
                        $total_score_feature[$key]['average'] = $total_score_feature[$key]['total_score'] / $total_score_feature[$key]['votes'];

                        }

                    }
                }
            }
        }
        foreach ($total_score_feature as $key => $feature) {
            $total_score_feature_ind_avg[$key] = $feature['average'];

        }

        $this->total_score_feature_ind_avg = $total_score_feature_ind_avg;
        arsort($total_score_feature_ind_avg);
// echo "all total average score";
        // print_r($total_score_feature_ind_avg);

        // $combine_feature = array_intersect_assoc($item_feature_individual_avg,$total_score_feature_ind_avg);

        // echo "all total score";
        // print_r($total_score_feature);
        if(!empty($item_feature_individual_avg)){
            foreach ($item_feature_individual_avg as $key => $feature_one) {
                if ($total_score_feature_ind_avg[$key] < $feature_one) {

                    $mutual_feature_name = $key;
                }

                break;
            }
         }   
        $ease_count = array_sum($easeofuse);
        // echo "ease count".$ease_count;
        $ease_average = round($ease_count / count($all_item_id) * 2, 2); //ease average

        $functionality_count = array_sum($functionality_feature);
        $functionality_feature_average = round($functionality_count / count($all_item_id) * 2, 2); // feature functionality average
        $valueformoney_count = array_sum($value_money);
        $valueformoney_feature_average = round($valueformoney_count / count($all_item_id) * 2, 2); // valueformoney average

        $customersupport_count = array_sum($customersupport);

        // echo "customersupport_count";
        // print_r($customersupport_count);

        $customersupport_feature_average = round($customersupport_count / count($all_item_id) * 2, 2); // customersupport  average
        $item_rat = 0;
        if(isset($item_overall['list']['overallrating']['score'])){
            $item_rat = $item_overall['list']['overallrating']['score']; //item rating
        }
        
        

        // echo "item_rat ".$item_rat;

        // $item_feature = get_field('features_list', $pId);
        // $featureList  = get_post_meta($post_id, 'features_list', true);

        $details .= '<div class=" item-description container-">'; //main header- sec item-description container div
        $details .= '<div class="row">
            <div class="col-sm-12">

                        <div class="col-md-6 col-sm-12 fl item_list_image_sec">
                        <div class="boxxx" style="overflow: hidden;">
                            <div class="col-sm-5 fl img_item img_height">';
        $img = get_the_post_thumbnail($post_id, 'medium', array('itemprop' => 'image'));
        $details .= '' . $img . '</div>
                            <div class="col-sm-6 fl item_rank_score img_height">
                            <div >
                            <div class="item-subtitle">  <div class="tooltip_1">FindrScore <i class="fa fa-info-circle"></i>
                            <span class="tooltiptext_1">The Scoring is based on our unique algorithm that looks at reviews, votes, behavior, social signals and more. <a rel="nofollow" href="/scoring-methodology/">Learn more</a></span>
                        </div></div>
                            <p class="item-score item-sec-div"><strong>' . round($findrScore) . '</strong>/100</p>

                            </div>
                            </div>
                            </div>';
                            $rating = get_overall_combined_rating($post_id);
                            $overall = 0;
                            $votes = 0;
                            if(isset($rating['list']['overallrating']['score']))
                            {
                                $overall = $rating['list']['overallrating']['score'];
                                $votes = $rating['count'];
                            }
                            $rating_item = $this->reviewClass->get_stars( $overall, 20, 5 );//do_shortcode('[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=20 post=' . $post_id . ']');

                            $details .= '<div class="col-sm-12"> <div class="rating-count">' . $rating_item . '</div>
                                            <div class="user-rating-cont">
                                            ('.$votes.' User Rating)</div>
                                        </div>
                                            ';
        $details .= $conlast;
        $details .= '<p class="visit_btn_sec">';
        if($this->availability == 'no') { 
          $details .=  '<a style="
          color: white;
      " href="'. get_the_permalink( $post_id ).'alternative/" class="alter-btn head-links" data-parameter="itemid" data-id="'. $post_id .'" >Alernative</a>';
        
         } else { 
             $details.='<a style="
             color: white;
         " class="mes-lc-li-down head-links" href="'. $this->afflink.'" rel="nofollow" target="_blank">'.$this->btntext.'</a>'; 
        } 
        $details.='</p>';
        $details .= '</div> '; //div class="col-sm-6 item_list_image_sec

        $details .= '<div id="rating" class="col-md-6 col-sm-12 fl item_list_content_sec">'; //item features and their progress bar first list
        $ease_average_sr = ($ease_average * 10);
        $functionality_feature_average_sr = ($functionality_feature_average * 10);
        $details .= '<div class="item-description price_feature ">
                                    <div class=" review-sections"> Features & Functionality <br>
                                        <div class="progress">

                                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:' . $this_features_functionality_percentage . '%">

                                            </div>
                                            <span class="sr-only" style="left: calc(' . $functionality_feature_average_sr . '% + 3px);"></span>

                                            <span style="position: absolute; top: 24px; width: 100%; left: calc(' . $functionality_feature_average_sr . '% - 46px); font-weight: 100;
                                            font-size: 12px; color:#000;">Industry avg ' . $functionality_feature_average . '</span>
                                        </div>&nbsp;<span>' . $this_features_functionality . '/10<span>
                                    </div>
                                    <div class=" review-sections"> Ease of use <br>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:' . $this_ease_of_use_percentage . '%">


                                            <span class="sr-only" style="left: calc(' . $ease_average_sr . '% + 3px);"></span>

                                            <span style="position: absolute; top: 24px; width: 100%; left: calc(' . $ease_average_sr . '% - 85px);  font-weight: 100;
                                            font-size: 12px; color:#000;">Industry avg ' . $ease_average . '</span>


                                            </div>
                                        </div>&nbsp;<span>' . $this_ease_of_use . '/10<span>
                                    </div>

                               '; //col-sm-6 item_list_content_sec ,tem-description , row div
        $valueformoney_feature_average_sr = ($valueformoney_feature_average * 10);
        $customersupport_feature_average_sr = ($customersupport_feature_average * 10);
        $details .= '<div class=" review-sections">Value for money<br>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:' . $this_value_for_money_percentage . '%">
                                            <span class="sr-only" style="left: calc(' . $valueformoney_feature_average_sr . '% + 3px);"></span>

                                            <span style="position: absolute; top: 24px; width: 100%; left: calc(' . $valueformoney_feature_average_sr . '% - 85px);  font-weight: 100;
                                            font-size: 12px;color:#000;">Industry avg ' . $valueformoney_feature_average . '</span>


                                             </div>
                                        </div>&nbsp;<span>' . $this_value_for_money . '/10<span>
                                    </div>
                                    <div class="  review-sections">Customer Support <br>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:' . $this_customer_support_percentage . '%">


                                            <span class="sr-only" style="left: calc(' . $customersupport_feature_average_sr . '% + 3px);"></span>

                                            <span style="position: absolute; top: 24px; width: 100%; left: calc(' . $customersupport_feature_average_sr . '% - 85px);  font-weight: 100;
                                            font-size: 12px; color:#000;">Industry avg ' . $customersupport_feature_average . '</span>

                                            </div>
                                        </div>&nbsp;<span>' . $this_customer_support . '/10<span>
                                    </div>
                        </div>
                    ';

        // echo "customersupport_count ".$this_customer_support;
        // echo "customersupport_feature_average ".$customersupport_feature_average;

        if ($this_customer_support > $customersupport_feature_average) {
            $this->bestForCustSupport = "Customer support";
        }
        // echo "ease of use this item score ".$this_ease_of_use;
        // echo "ease_average ".$ease_average;
        if ($this_ease_of_use > $ease_average) {
            $this->ease_dis = "Ease of use";
        }
        // echo "ease_dis ".$this->ease_dis;
        // echo "<br>this item price ";
        // print_r($this_item_price);
        // echo "<br> all price avg ";
        // print_r($all_price_avg);
        if (($this_item_price < $all_price_avg) || $this_pricing_model == 'freemium' || $this_pricing_model == 'open_source') {

            $this->price_item = "Low cost software";
        }
        // echo "over hight score $over_high_score";

        if ($item_rat >= $over_high_score) {
            $overlist = "Complete solution ";
        }

        // echo "item at sum ". $item_at_sum;
        // echo " total score avg".$total_score_avg;
        // if($item_at_sum > $total_score_avg){
        //     $features_name = "Feature";
        // }

        // echo "feature name : ". $feature_name;
        if (empty($this->bestForCustSupport) && empty($this->ease_dis) && empty($this->price_item) && empty($overlist) && empty($mutual_feature_name)) {
            $no_area = "No particluar area";

        }
        // echo "pid is $post_id";
        $features_ratings = get_or_create_feature_ratings($post_id);//get_field('features_list_ratings', $post_id);
        // echo "check1 ".time();
        // echo $features_ratings;
    /*     if ($features_ratings === null || empty($features_ratings)) {

            $features = get_field('features_list', $post_id);
            // var_dump($features);
            if ($features !== null && !empty($features)) {

                $features_ratings = create_feature_ratings($post_id, $features);
                // echo "fr1 caught!";

            }

        } */
        // echo "features ratings";
        // print_r($features_ratings);

        $details .= '<strong>Best For: </strong> <ul class="feature_list"><li>';
        if (!empty($this->bestForCustSupport)) {
            $details .= ' <i class="fa fa-square"></i> ' . $this->bestForCustSupport . '  </li>';
        }
        if (!empty($this->price_item)) {
            $details .= ' <li><i class="fa fa-square"></i> ' . $this->price_item . '</li>';
        }
        if (!empty($this->ease_dis)) {
            $details .= '  <li><i class="fa fa-square"></i> ' . $this->ease_dis . ' </li>';
        }
        if (!empty($overlist)) {
            $details .= '   <li><i class="fa fa-square"></i> ' . $overlist . ' </li>';
        }
        if (!empty($mutual_feature_name)) {
            $details .= '   <li><i class="fa fa-square"></i> ' . $mutual_feature_name . ' </li>';
        }
        if (!empty($no_area)) {
            $details .= '   <li><i class="fa fa-square"></i> ' . $no_area . ' </li>';
        }
        $details .= '</div>
        </div>
    </div>
    ';
        /*------------------------------------------------------section1 complte-----------------------------------------------------------*/
        $details .= '<div class="row" style="
        margin-bottom: 5%;
        "><hr class="hr-line" style="
        border-top: 7px solid #e1e3e3;
        width: 100%;
        ">
        <div style="
        display: flex;
        position: absolute;
        margin-left: 28%;
        ">';

        if ($nor > 10) {
            $details .= '<img class="leaf1" src="' . esc_url(plugins_url('image/leaf1.png', dirname(__FILE__))) . '"><div class="row11" style="
        height: fit-content;
    ">
        <div class="user-rat-sec">
        Rating </br>
            <div class="rat-section">' . $rating_item . '</div>
        </div>
    </div><img src="' . esc_url(plugins_url('image/leaf1.png', dirname(__FILE__))) . '" class="leaf2">';
        }

        // echo "award_result";
        // echo $this->total_award;
        if (!empty($this->total_award)) {
            $details .= '<div class="awarsds"><a style="
    display: flex;
" href="#awards"><img style="
    margin-left: 20px;
" class="leaf1" src="'. content_url('uploads/2020/06/leaf3a.png').'"><div class="awars_sec">
    <div class="user-rat-sec"><span>' . $this->total_award . '</span>
        <div class="rat-section"> Awards</div>
    </div>
    </div><img class="leaf1" src="'. content_url('uploads/2020/06/leaf3b.png').'">

    </div></a></div>';

        }

        // else{
        //     $details .= "<div class='awarsds' style='display:none'>";

        // }

        $details .= '</div></div>';
        // <!-- close the row -->
        $content = $details;

        /**--------------------------------------------------------test-------------------------------------------------------------------------------- */
        return $content;
    }

    public function increment_visit_count($post_id)
    {
        $visit_count = get_post_meta($post_id, 'visit_count', true);
        if(!is_numeric($visit_count)){
            $visit_count=0;
        }
        $visit_count += 1;
        update_post_meta($post_id, 'visit_count', $visit_count);
        return $visit_count;

    }

    public function full_content($post_id)
    {
        //echo "cp1";

        $post_id = get_the_ID();
        $awardssresult = $this->get_award_list($post_id);
        // echo "<br>get award list".time();
        //echo "cp4";
        $data_all_page = $this->get_single_list_item_content(get_the_ID(), '');
        // echo "<br>get_single_list_item_content".time();
        //echo "cp5";
        $data_all_page .= '<div class= "item-body-content">'; //inside body content
        // $data_all_page .= "visit_count ".get_visit_count($post_id);
        $data_all_page .= '' . $this->get_item_sidebar($post_id) . '';
        // echo "<br>get_item_sidebar".time();
        //echo "cp7";
        $data_all_page .= '' . $this->item_content_value($post_id) . '';
        // echo "<br>item_content_value".time();
        //echo "cp6";
        
        $data_all_page .= '</div>'; //item-body-content div

        $data_all_page .= '</div></div>'; //item-content container
        $data_all_page .= '' . $awardssresult . '';
        $data_all_page .= '<div class="item_list_page_container">' . $this->keyfeature_pricing($post_id) . '</div>';
        // echo "<br>keyfeature_pricing".time();
        //echo "cp3";
        // $details  .= $this->keyfeature_pricing($post_id);
        $data_all_page .= '<div class= "item-body-content">'; //inside body content
        $data_all_page .= '' . $this->pricing_chart($post_id) . '';
        // echo "<br>pricing_chart".time();
        $data_all_page .= '</div">'; //inside body content close
        $data_all_page .= '<div class="item_list_page_container">' . $this->price_performance_chart($post_id) . '';
        // echo "<br>price_performance_chart".time();
        $data_all_page .= '</div>'; //full-widthdiv
        $data_all_page .= '<div class="item_list_page_container">' . $this->data_item_page($post_id) . '</div>';
        // echo "<br>data_item_page".time();
        $data_all_page .= '' . $this->videos_sec($post_id) . '';
        // echo "<br>videos_sec".time();
        $data_all_page .= '<div class="item_list_page_container">' . $this->support_sec($post_id) . '</div><div id="reviews"></div>';
        // echo "<br>support_sec".time();

        //echo "cp2";
        return $data_all_page;
    }

}

//add_action('init','define_single_item');
function define_single_item()
{
    $user = wp_get_current_user();

    if ($user && isset($user->user_login) && 'devvv' == $user->user_login) {
        new Mv_List_Single_View_New();
    } else {
        new Mv_List_Single_View();
    }
}
new Mv_List_Single_View_New();
