<?php
class Mv_List_Single_View_New {
    private $affiliate_url ;
    private $affiliate_button_text;
    private $source_url ;
    private $credit_text  ;
    private $tags;
    private $categories  ;
    private $comp_categories  ;
    private $editor_choice ;
    private $product_model;
    private $product_type ;
    private $alternate_tag ;
    private $software ;
    private $findrScore;

    function __construct() {
        add_filter( 'the_content', array( $this, 'show_list' ), 1  );
        add_action( 'wp_ajax_list_creator_post_vote', array( $this, 'update_vote' ) );
        add_action( 'wp_ajax_nopriv_list_creator_post_vote', array( $this, 'update_vote' ) );        
        add_shortcode( 'show_list', array( $this, 'show_list_shortcode' ) );// shortcode     
        add_filter( 'manage_lists_posts_columns' , array( $this, 'add_shortcode_column' ) );   // shortcode coloumn
        add_action( 'manage_lists_posts_custom_column' , array( $this, 'shortcode_column' ), 10, 2 );   // shortcode coloumn        
        add_action( 'comment_form_after', array( $this, 'also_mentioned_in' ) );//mentioned in
    }

    function show_list( $content ) {  
         if ( is_singular( 'lists' ) && ! has_shortcode( $content, 'show_list' )  ) { 
            global $post;
            if ( ! has_shortcode( $post->post_content, 'show_list' ) ) {
                $content = $this->list_contnet(get_the_ID());
                $content .= $this->get_list_html( get_the_ID() );
                 }  
                        }
        elseif ( is_singular( 'list_items' ) ) {
             $currentId = get_the_ID();
            $this->set_single_list_item_vars($currentId );  
            $this->ranklist = $this->get_item_ranks($currentId);            
            $content=  $this->full_content($post_id);
            // $content = $this->get_single_list_item_content( get_the_ID(), $content );   

        }
        return $content;
    }

    function sort_features($total_fearturess, $features_ids){
        global $wpdb;
        $sorted_features_array = array();
        $a = array();
        foreach($total_fearturess as $total_fearturesi){
            $name_feature = str_replace(' ', '_', strtolower($total_fearturesi));
            $votes = "";
            // print_r($features_ids);
            $count_theme = count($features_ids);
            /* if($count_theme==0)
                $count_theme++; */
            // echo "count theme : $count_theme";
            // print_r($features_ids);
        foreach($features_ids as $features_idi){
            // echo "SELECT votes FROM wpxx_feature_rating WHERE post_id = '$features_idi' && feature_name = '".$name_feature."' ";
            $db_data1 = $wpdb->get_results( "SELECT votes FROM wpxx_feature_rating WHERE post_id = '$features_idi' && feature_name = '".$name_feature."' "  );
            // echo "db_data1\n";
            // print_r($db_data1);
            if($wpdb->num_rows > 0){
                // print_r($db_data1);
        foreach($db_data1 as $datas1){
            $votes += $datas1->votes;
            }
        }
        }
        // echo "isset votes ".isset($votes);

        if(isset($votes)){
            $a['vote']= $votes/$count_theme;
        }else{
            $a['vote']= 0;
        }
        $a['feature']= $total_fearturesi;
        $sorted_features_array[] = $a;
            }
        arsort($sorted_features_array);
        // print_r($sorted_features_array);

        return $sorted_features_array;
    }
//get_faqs fxn only for faq
    function get_faqs($post_id=0){
        $item_rating_feature = get_field( 'features_list_ratings', $post_id );
        arsort( $item_rating_feature);
   
        foreach($item_rating_feature as $key=> $item_key_feature_rat){
            
            $topfeature_avg = $item_key_feature_rat[average];
            $topfeature_score =$item_key_feature_rat[total_score];
            $topfeature_name = $key;
            break;
        }


        if($post_id == 0)
        $post_id = get_the_ID();        
        $iem_name = get_the_title( $post_id );
        $beginners ='';
        $rating = get_overall_combined_rating($post_id);
        /******************************************8* faq-3 start ***********************************************/
        $pricing_model = get_field( 'pricing_model', $post_id );
        $plan = get_field('plan',$post_id);
        $starting_price = get_field( 'price_starting_from', $post_id);        
         foreach($pricing_model as $pricing)
            {
                if($pricing == 'freemium'){
                    $pric = "Free to use.";
                    $free= "Yes there is a free trial available which should give you enough time to test out $iem_name to see if it’s the right fit for you.";
                }
                else{
                    $pric = "You can expect to pay around </label><span itemprop='priceCurrency' content='USD'>$</span><span itemprop='price'>".preg_replace("/[^0-9.]/", "", $starting_price)." USD per <span itemprop='price_plan'>" .$plan ."</span></p>" ;   
                    $free=   "The isn’t a mention of a free trial on our system but you can always check to see it a money back guarantee is on offer with $iem_name .";      
                }
            }
        /********************************************** * end of faq-3 *******************************************/
        /******************************************** * faq-1 start ***************************************/
        $easeofuse = $rating['list'][easeofuse][score];
        if( $easeofuse > 2.5){
             $beginners = "Users who have used $iem_name as reported that it’s fairly easy to grasp.";

             } 
        else
                {
                    $beginners = "Users who have used $iem_name as reported that there is a learning curve which you should keep in mind..";
                }
/****************************************************end of faq-1*****************************************/
/******************************************** faq-2 start ***********************************************/
        $list_setting = get_option( 'mv_list_items_settings' );
        $target_countries = $list_setting['list_page_target_countries'];
        $countryPair = explode(',',$target_countries);
        $download_count = array();
        $max_downloads_in='';
        $max_downloads_value= 0;
        foreach($countryPair as $cp){
            $pair = explode('=>',$cp);
                foreach($pair as $key=>$value){
                    $pair[$key]=trim(trim($value),"'");
                }
                    $targetCountries[$pair[0]] = $pair[1];
                    $download_count[$pair[0]] = get_field( 'downloads_in_'.$pair[0], $post_id );
                        if($max_downloads_value < $download_count[$pair[0]])
                        {
                            $max_downloads_value = $download_count[$pair[0]];
                            $max_downloads_in = $pair[0];
                        }        

        }
        $county = $targetCountries[$max_downloads_in];
        $faqans = "Out of all the users on our platform $iem_name as the highest adaptation rate in $county"; 
        /******************************************** faq-2 end **************************************************/
        /****************************************************start of faq-4*****************************************/
        $itm_support = get_field( 'support', $post_id );
        $overalrating = $rating['list'][overallrating][score];
        // $ratings = $compareClass->most_compared_rating($post_id);
        $rating = get_overall_combined_rating($post_id);
        $map =  array ("comments" => "Live Chat", "envelope" => "Mail" ,"phone" => "Phone", "wpforms" => "Forum" );
        $mapped_support = array();
        $rat=$rating['list'];            
            foreach($itm_support as $item_supports){
                    $mapped_support[] = $map[$item_supports];                            
            }  
            $item_supports = implode(", " , $mapped_support);
            $cust_rat = $rat[customersupport][score];
            if($cust_rat > 2.5){
                $itm_support_ans ="The majority of users who are sharing their experience with us on $iem_name are experiencing a positive experience with the support offered through $item_supports.";
            }
            else{
                $itm_support_ans = "The majority of users who are sharing their experience with us on $iem_name are experiencing a poor experience with the support offered through $item_supports.";
            }               
        
        /****************************************************end of faq-4*****************************************/
        /******************************************** * faq-5 & faq-7 start ***************************************/
        $link = get_permalink($post_id);
        $link_coupon = '<a href="'.$link.'coupon">coupon</a>';
        $couponlist  = get_post_meta($post_id, 'coupons_list', true);
        if(!empty($couponlist)){
            $link_coupon_not =  'Yes there are a few coupons you can try here: '.$link_coupon.'';            
        }
        else{
            $link_coupon_not = "There are no record on our system of $iem_name offering coupons historically";
        }      
        /******************************************** * faq-5 & faq-7 end ******************************************/
        /******************************************** * faq-8start *************************************************/   
        $compObj = new Mv_List_Comparision();
        $lists = $compObj->most_compared( $post_id,20,true);
   
        $pricing_model = get_field( 'pricing_model', $post_id );
        $plan = get_field('plan',$post_id); 

            if ( !empty ( $lists ) ) {
                $ac = 0;
                foreach( $lists as $pid ) {	 
                    $rating_comp = get_overall_combined_rating($pid);
                    $pricing_model = get_field( 'pricing_model', $pid );
                    $plan = get_field('plan',$pid );
                        foreach($pricing_model as $pricing){                               
                                if($pricing == 'freemium')
                                {
                                    $overalrating_comp[] = $rating_comp['list'][overallrating][score];                                   
                                        $rat_alternte = max($overalrating_comp);  
                                        $alternate_title =  get_the_title($pid);
                                        $altr_link =  generate_compare_link(array($post_id,$pid));  
                                        // $altr_link =  get_the_permalink($pid);                                                 
                                       $alternate_ans = "Yes, $alternate_title is proving quite popular with our users, <a href='$altr_link'>see how it stacks up against $iem_name</a>";      
                                    
                                }
                            $ac++;
                        } 
                }
            }

        /******************************************** * faq-8end *****************************************************/
        /******************************************** * faq-9 start **************************************************/
        $this->ranklist = $this->get_item_ranks($post_id);
        $listrankord = $this->ranklist;              
        if(!empty($listrankord)){
                        foreach ( $listrankord as $id => $rank ) {
                            // $post_id = $id;                           
                            $main_list_id = $id;                   
                            $catobj = get_the_terms($id, 'list_categories');
                            $finalVotes = do_shortcode("[total_votes id=$main_list_id]");                                 
                            $faq9 =  get_field('list_content_title_singular', $main_list_id);
                            $ans_faq9 ="$iem_name is ranked $rank on that list according to $finalVotes users";                            
                        }
                    
                    }
        /******************************************** * faq-9 start **************************************************/
        /******************************************** * faq-10 start ************************************************/
        $list_altr = $compObj->most_compared($post_id,20,true);
        foreach($list_altr as $lists){           
            $rating = get_overall_combined_rating($lists); 
            $overalrating_1[$lists] = $rating['list'][overallrating][score];     

        }
        arsort($overalrating_1);
        foreach($overalrating_1 as $key=> $top_alter){
            $top_alternative = $key;           
        break;
        }
        $top_alternative_title = get_the_title($top_alternative);     
        $altr_link_top =  generate_compare_link(array($post_id,$top_alternative));       
        $better_item = "This is a tricky question as it all depends on your needs, but $iem_name as a higher FindScore than $top_alternative_title which factors in many data points. <a href='$altr_link_top'>Have a closer look to see why?</a>";
        /******************************************** * faq-10 end *************************************************/
        $faq = ' <div itemscope itemtype="https://schema.org/FAQPage">';
        $qalist  = get_post_meta($post_id, 'qas_list', true);
        // print_r($qalist);
        foreach($qalist as $faq_ls){
            $custfaq = "<div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                            <h3 itemprop='name'> Q. ".$faq_ls['question']."</h3>
                            <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                                <div itemprop='text'>
                                    Ans.".$faq_ls[answer]."
                                </div>
                            </div>
                        </div>";
            $faq.=$custfaq;            
        }      
          $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  Is $iem_name good for beginners?</h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. $beginners
                             </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  How good is $iem_name in $county? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans.$faqans       </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  How much does $iem_name cost? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. $pric
                             </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  What type of support can I expect with $iem_name? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. $itm_support_ans
                             </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  Are there any coupons for $iem_name? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. $link_coupon_not                                
                            </div>
                        </div>
                    </div>
          ";
      
          if(!empty($topfeature_score)){
          $faq.= " <div  itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  Is $iem_name any good for $topfeature_name? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. $iem_name as a high rating when it comes to  $topfeature, scoring  $topfeature_score with a category average of $topfeature_avg
                                
                            </div>
                        </div>
                    </div>
          "; }
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.   Can I try $iem_name for free? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. $free
                            </div>
                        </div>
                    </div>
          ";
          if(!empty($alternate_ans)){
          $faq.= "           
          <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  Are there any Free alternative to $iem_name?</h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. $alternate_ans
                             </div>
                        </div>
                    </div>
          
          ";
          }
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  Is $iem_name the best $faq9 ?</h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans.  $ans_faq9.
                            </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q. Which is better $iem_name or $top_alternative_title?</h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. $better_item
                            </div>
                        </div>
                    </div>
          ";
          $faq .= "</div>";
             return $faq;

    } //get_faqs fxn only for faq end

    //------------------------------getting select relationship  id ---------------------------------------
    function get_item_ranks($pId){
        $lists =  get_field( 'add_to_list', $pId, false );
        $itemiid = $pId;
        $listrankord = array();
        if ( !empty ( $lists ) && is_array( $lists ) ) {
            foreach ( $lists as $id ) {
                if($this->acme_post_exists($id)){
                    $rank = get_item_rank($id,$itemiid);
                    $listrankord[$id] = $rank;
                }
            }
            asort($listrankord);
        }   
        return   $listrankord;
       
    }
    function list_contnet($listId){
        $adddata = '';
        $postDat = get_post($listId);
        $contentP = $postDat->post_content;
        $addInform = get_field('additional_information',$listId);
        if(!empty($addInform)):
            $adddata = '<div class="container-fluid section3">
                        <div class="container">
                            <div class="row justify-content-md-center">
                                <div class="col-md-8 center">
                                    <p class="quote" >'. $addInform.'</p>
                                </div>
                            </div>
                        </div>
                    </div> ';
        endif;
        $options = get_option( 'mv_list_items_settings' );
        $googleContnet = !empty( $options['list_page_google_add'] )?$options['list_page_google_add']:'';
        $htm = '<div class="container-fluid section2">
            <div class="container">
                <div class="row">
                    <div class="col-md-5 contenttext"><div id="list_main_contnet">'.$contentP .'</div><div class="list-read-more"><a href="javascript:;" class="readbutton">Read More >>></a></div><p class="toc_container">[toc]</p>
                </div>
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-5 contentimage">'.$googleContnet.'               
                    </div>
                </div>
            </div>
        </div>'.$adddata;   
        return $htm;
    }

    function set_single_list_item_vars( $post_id ) {
        $this->affiliate_url = get_field( 'affiliate_url', $post_id  );
        $this->affiliate_button_text = get_field( 'affiliate_button_text', $post_id  ) == '' ?'Download/Demo': get_field( 'affiliate_button_text', $post_id  );
        $this->source_url    = get_field( 'source_url', $post_id  );
        $this->credit_text   = get_field( 'credit', $post_id );
        $this->features   = get_field( 'features_list', $post_id );
        $this->pricing_model = get_field( 'pricing_model', $post_id );
        $this->free_trial = get_field( 'free_trial', $post_id );
        $this->free_trial_card = get_field( 'card_required', $post_id );
        $this->price_starting_from = get_field( 'price_starting_from', $post_id );
        $this->additional_price_info = get_field( 'additional_price_info', $post_id );
        $this->product_type  = get_field( 'product_type', $post_id );
        $this->editor_choice = get_field( 'editor_choice', $post_id );
        $this->alternate_tag = get_field( 'alternate_tag', $post_id );
        $this->software      = get_field( 'software', $post_id );
        $this->plan = get_field('plan',$post_id);
        $catobj = get_the_terms( $post_id, 'list_categories' );
        if ( $catobj && ! is_wp_error( $catobj ) ) {
            $this->categories = $catobj;
        }
        $campobj = get_the_terms( $post_id, 'list_comp_categories' );
        if ( $campobj && ! is_wp_error( $campobj ) ) {
            $this->comp_categories = $campobj;
        }
        $tagsobj = get_the_terms( $post_id, 'item_tags' );
        if ( $tagsobj && ! is_wp_error( $tagsobj ) ) {
            $this->tags = $tagsobj;
        }
    }

    function show_list_shortcode( $atts ) {
        return $this->get_list_html( $atts['id']  );
    }
	//getlist filter================/
    function get_list_html( $list_id ) {
        global $wp;
        $index =1;
        $pageID = get_the_ID();
        $main_list_id        = $list_id ;
        $site_url 			 = get_site_url();
        $main_list_permalink = get_the_permalink( $list_id );
        $main_list_uri 	 	 = get_page_uri( $list_id );
        $attached_items      = get_field( 'list_items', $list_id, true );
        $promoted_list_items =  get_field( 'promoted_list_items', $list_id, true );
        $items_per_page      = get_field( 'items_per_page', $list_id );
        $voting_closed       = get_field( 'voting_closed', $list_id );
        $current_page        = get_query_var( "page" ) ? get_query_var( "page" ) : 1;
        $total_pages         = ceil( count( $attached_items )/$items_per_page );		 
        foreach ( $attached_items as $key =>$child_post ) {
            $total_votes = get_post_meta( $child_post->ID, 'votes_given', true );
            if ( ! isset( $total_votes[$main_list_id] ) ) {
                $total_votes[$main_list_id] = 0;
                $attached_items[$key]->votes = 0;
            }else {
                $attached_items[$key]->votes =$total_votes[$main_list_id];
            }

        }
        $index = ( ( $current_page - 1 ) * $items_per_page )+1;

        //usort( $attached_items, array( $this, "cmp" ) ); ?>
        <script>
                           // console.log("isocode"+isoCode);

        </script> 
        <?php
       /*  $location = $_GET['lang'];
        if($location == '') //because sometimes the lang has not reached into the url until this line.
        {
            $location = do_shortcode('[geoip_detect2 property="country.isoCode"]');

        } */
        // echo "lang from session is : ".$_SESSION['lang'];
        $location = $_SESSION['lang'];
        // file_put_contents("mvlistsvn.txt",$location,FILE_APPEND);
        //  Mes_Lc_Ext::sort($list_id, $attached_items,$location); //unnecessary, the items are sorted later via calculate score anyway
         //Mes_Lc_Ext::sortfree($list_id, $postarr);
        //  print_r($attached_items);
        // echo "attached_items after sort()";
        /* foreach($attached_items as $k => $v){
             echo $v->post_title."\n";
         } */

        $itmes_with_promoted = $attached_items;

        $temp_array=array();

        // make promoted items at top
        if ( ! empty( $promoted_list_items ) ) {
            foreach ( $attached_items as $key => $item ) {
                if ( in_array( $item->ID, $promoted_list_items ) ) {
                    unset( $itmes_with_promoted[$key] );
                    $item->promoted =1;
                    $temp_array[]=$item;
                }

            }
        }
        $attached_items = array_merge( $temp_array, $itmes_with_promoted );
        // echo "attached items ";
        // print_r($attached_items);
		// Mes_Lc_Ext::sortfree($list_id, $attached_items); // I think this is unnecessary commented on 5 oct 2019
        //krsort($items_by_votes);
        // $posts =  array_slice( $attached_items, ( $current_page - 1 ) * $items_per_page , $items_per_page );
        $posts = $attached_items;
        /*echo '<pre>';
            print_r($posts);
            echo '</pre>';  */

        $totalVotes = 0;
        $totalActVOtes = 0;
        $postarr = array();
		$postarr1 = array();
        $postarrOBJ = array();
        if(!empty($posts))
        {
            // echo print_r($posts);
            $postsScores = array();
            $postsDownloadsThisLocation = array();
            $minScore = null;
            $totalDownloadsInList = 0;
            foreach($posts as $key=>$list_post)
            {
                // update_post_meta($list_post->ID,'downloads_in_'.$location,0);

                $postsDownloadsThisLocation[$key] = get_post_meta($list_post->ID,'downloads_in_'.$location,true); 
                $totalDownloadsInList += $postsDownloadsThisLocation[$key];
                //$totalVotes += $list_post->votes;
                $score = Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);
                $postsScores[$key]= $score;
                if($minScore == null){
                    $minScore = $score;
                }
                if($minScore > $score){
                    $minScore = $score;
                }
                if ($list_post->votes > 0) {
                    // $list_post->votes = $list_post->votes*2.27;
                    $totalActVOtes  += $list_post->votes;
                    $totalVotes += $score;


                }
            }
            // echo "total downloads are $totalDownloadsInList <br>";
            
            
            // echo "minimum score in the list is : $minScore<br>";
            $toAddInAll = 0;
            if($minScore < 0){
                $toAddInAll = abs($minScore) + 0.01;
            }
        }
		if(isset($_GET['sort'])){
	  $sorts = $_GET['sort'];
		}
	  $a = array();
        if(!empty($posts))
        {
           
            
           
            foreach($posts as $key=>$list_post){
                    //$totalVotes += $list_post->votes;
    //            if ($list_post->votes > 0) {
                    // echo "<br>$list_post->post_title score $postsScores[$key]";
                    // echo "<br>$list_post->post_title score + toadd ".($postsScores[$key]+$toAddInAll);
                    $score = $postsScores[$key]+ $toAddInAll;
                    // $score = Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);

                    // echo "<br>$list_post->post_title score/totalvotes = ".($score / $totalVotes)."<br>";
                    $downloadsThisItem = $postsDownloadsThisLocation[$key];
                    if($downloadsThisItem == ''){
                        $downloadsThisItem = 0;
                    }
                    if($totalDownloadsInList != 0){
                        $downloadPercent = $downloadsThisItem*100/$totalDownloadsInList; 
                        $downloadPercent += 1; //to keep the scale even if downloads are zero
                    }
                    else{
                        $downloadPercent = 100;
                    }
                    // echo "$list_post->post_title download percent in this location = ".$downloadPercent."<br>";

                    $score = ($score / $totalVotes) * $downloadPercent;
                    $score = number_format($score, 2);
                    // echo "$list_post->post_title score after using location download percentage = ".$score."<br>";

                    $pricemodel12 = get_field( 'pricing_model', $list_post->ID );
                    $freetrial = get_field( 'free_trial', $list_post->ID);
                    if(empty($pricemodel12)){
                    $pricemodel12 = array();	
                    }
                    if(in_array('open_source', $pricemodel12) || in_array('freemium', $pricemodel12) || $freetrial == 1){
                    $free_items_array[]="free";	
                    }
    ////       
                        
                    if(isset($sorts) && $sorts =="free"){
                            $postarr12[$list_post->ID] = $pricemodel12;  
                                if(in_array('freemium', $postarr12[$list_post->ID]) ||in_array('open_source', $postarr12[$list_post->ID]) || $freetrial == 1) {
                                $postarr[$list_post->ID] = $score;
                            }else{
                                $postarr1[$list_post->ID] = $score; 
                            }
                            }elseif(isset($sorts) && $sorts =="affordable_price"){ 
                            $aa = $score; 
                            $price_starting_from = get_field( 'price_starting_from', $list_post->ID);
                            $price_starting_from = str_replace("$","",$price_starting_from);
                            $postarr[$list_post->ID] = $price_starting_from;	
                            
                            $counts = array_count_values($postarr);
                            $filtered = array_filter($postarr, function ($value) use ($counts) {
                            return $counts[$value]>1 ;
                                });

                    if(count($filtered)>=1){
                    foreach($postarr as $key => $value) {
                        foreach($filtered as $key1 => $value1) {
                            if($key == $key1 && $value == $value1){
                                unset($postarr[$key]);
                                $postarr1[$key] = $aa;
                                $postarr11[$key] = $value;
                                break;
                            }
                        }
                        }	

                    }
                    }elseif(isset($sorts) && $sorts =="User_friendly"){
                                $reviews1223 = get_overall_combined_rating($list_post->ID);
                                foreach($reviews1223 as $key=>$value){
                                $postarr[$list_post->ID] = $value['overallrating']['score'];
                                }
                            }else{
                            $postarr[$list_post->ID] = $score;	
                            }
                            
                    $postarrOBJ[$list_post->ID] = $list_post;
                    
        //            }
            }
            /* echo "postarrOBJ after sorts";
            foreach($postarrOBJ as $k => $v){
                 echo $v->post_title."\n";
             } */
        }

		
		if(isset($sorts) && $sorts =="free"){
			 arsort($postarr);
			 arsort($postarr1);
        	 $postarr = $postarr+$postarr1;
		}
		elseif(isset($sorts) && $sorts =="affordable_price"){
			asort($postarr);
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

		}else{
			 arsort($postarr);
		}
		// print_r($postarr);
        $maxsScore = array_keys($postarr, max($postarr));
        $maxsScorePost = $maxsScore[0];

			#--------------------------*************************----------------------------------
            $static_val = array_slice( $postarr, ( $current_page - 1 ) * $items_per_page , $items_per_page , true);
		
               if ( ! empty( $posts ) ) {
            ob_start()
            ?>
            
            <div class="zombify-main-section-front zombify-screen" id="zombify-main-section-front-new">
                <div class="container-fluid section4" id="bargraph">
                    <div class="container">
                        <div class="row justify-content-md-center">
                            <div class="col-md-8 center">

                                <p>
                                    <strong>
                                        LETS HAVE A LOOK AT <?php the_title();?>
                                        
                                    </strong>
                                </p>
                                <?php if ( ! empty( $posts ) ) {?>
								
								


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
         <section id="filter">
         </section>
     <div  class="fileter_list">
    
     <?php $a = get_permalink();
	  ?>
 <a href="<?php echo get_permalink()?>" class="filterlist <?php if((isset($sorts) && $sorts =="free") || (isset($sorts) && $sorts =="affordable_price") || (isset($sorts) && $sorts =="User_friendly")){}else{ echo 'ractive';} ?>">Recommended For Me </a>
 <?php if(count($free_items_array) !== 0){ ?>
 <a href="<?php echo get_permalink().'?sort=free#filter'?>" class="filterlist <?php if(isset($sorts) && $sorts =="free"){ echo 'ractive'; } ?>" >Free</a>
<?php } ?>
 <a rel="nofollow" href="<?php echo  get_permalink().'?sort=affordable_price#filter'?>" class="filterlist <?php if(isset($sorts) && $sorts =="affordable_price"){ echo 'ractive'; } ?>">Most affordable</a>
 <a rel="nofollow" href="<?php echo get_permalink().'?sort=user_friendly#filter'?>" class="filterlist <?php if(isset($sorts) && $sorts =="User_friendly"){ echo 'ractive'; } ?>">Most User friendly</a>

      </div>
      <div class="page-cont" data-page="<?php echo $current_page ?>" data-page-url="<?php echo $site_url."/best/".$main_list_uri?>/" > 

         
             <?php $kk = 1;
                $promoteditems  = '';
                $alllistitems = '';
                $videoItems = '';
                $compareItems = array();
                $tooltipAdded = false;
                // print_r($postarr);
                foreach($static_val as $key=>$score): // variable must be called $post (IMPORTANT)
                    //setup_postdata( $post );
					/*if((isset($sorts) && $sorts =="free") || (isset($sorts) && $sorts =="affordable_price") || (isset($sorts) && $sorts =="User_friendly")){
					$main_list_id = $key;
					
                    }*/
                    // print_r($postarrOBJ);
                   /*  echo "postarrOBJ inside static val loop<br>";
                    foreach($postarrOBJ as $k => $v){
                        echo $v->post_title."<br>";
                    } */
                    $list_post = $postarrOBJ[$key];
                    $list_id = $list_post->ID;
                    if($kk < 3){
                        $compareItems[] = $list_id;
                        
                    }
					$kk++;
                    $affiliate_url = get_field( 'affiliate_url', $list_id );
                    /* if($kk==2){
                        $affiliate_button_text = "tolltip ";
                    } */
                    $affiliate_button_text = get_field( 'affiliate_button_text', $list_id ) == '' ?'Download': get_field( 'affiliate_button_text', $list_id   );
                    $source_url = get_field( 'source_url', $list_id );
                    $credit = get_field( 'credit', $list_id );
					$pricemodel = get_field( 'pricing_model', $list_id );
					$freetrial = get_field( 'free_trial', $list_id );
                    $support       = get_field( 'support', $list_id );
                    $gallery = get_field('gallery',$list_id);
                    $thumbiD = get_post_thumbnail_id($list_id);
                    $price_starting_from = get_field( 'price_starting_from', $list_id);
				   $pricingplan = get_field( 'plan', $list_id);
				   
				   

				   
                    if(!is_array($gallery)){
                        $gallery = array();
                    }
                    if(!empty($thumbiD)){
                        $img = wp_get_attachment_image_src( $thumbiD, 'list-thumb' );
                        $thumb = array('url'=>$img[0],'width'=>$img[1],'height'=>$img[2]);
                        $thumb['sizes']= get_all_image_sizes($thumbiD);
                        $gallery[] = $thumb;
                    }
                    
				   $video = get_field('video',$list_id);
				   
				   
				  
				   
				   
                    if(!empty($video)){
                        $videoId = 'video'.$list_id;
                        $ifarmeId = 'youtube'.$list_id;

                        ob_start();
						
                        ?>
                        <div id="<?php echo $videoId;?>" class="lightbox" onclick="hideVideo('<?php echo $videoId;?>','<?php echo $ifarmeId;?>')">
                            <div class="lightbox-container">
                                <div class="lightbox-content">

                                    <button onclick="hideVideo('<?php echo $videoId;?>','<?php echo $ifarmeId;?>')" class="lightbox-close">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <div class="video-container" id="<?php echo $ifarmeId;?>">
                                        <?php echo $video; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php
                        $videoItems.= ob_get_contents();
                        ob_end_clean();

                    }
                    ob_start();
                    ?>
                    <?php  $availability = get_post_meta( $list_id, '_item_availbility', true ); ?>
                    
                    
                    <div class="container-fluid section5 pos-relative" id="<?php echo  $kk ==3?'show_comprison_footer':'';?>">
                   
                        <div class="container">
                            <div class="row" id="test"> 
                                <div class="col-md-6 center">
                                <div class="<?php echo ($availability == 'no') ? 'item-not-available' : ''; ?>"></div>
                                    <div id="componentWrapper<?php echo$index?>"  class="componentWrapper">
                                        <!--
                                        Note: slides are stacked in order from bottom, so first slide in '.componentPlaylist' is a the bottom!
                                        (Their z-position is manipulated with z-indexes in jquery)
                                         -->
                                        <div class="componentPlaylist">
                                            <?php
                                            $numItems = count($gallery);
                                            $i = 0;
                                            if(!empty($gallery) && is_array($gallery)){
//                                                echo '<pre>';
//                                                print_r($gallery);
//                                                echo '</pre>';
                                                foreach ($gallery as $img){
                                                    $sizeUrl  = isset($img['sizes']['list-thumb'])?$img['sizes']['list-thumb']:$img['url'];
                                                    $sizewidth  =$img['width'];
                                                    $sizeheight  =$img['height'];
                                                    $style ='';
                                                    if(++$i === $numItems) {
                                                        $style = " style='filter: blur(0px);'";
                                                    }
                                                    ?>
                                                    <div class="slide" <?php echo $style;?>>
                                                        <div class="scaler" style="width: <?php echo $sizewidth;?>px;height: <?php echo $sizeheight;?>px">
                                                        <?php /*$alt = basename($sizeUrl);
														$pos = strrpos($alt, ".");
														 $alt = substr($alt,0,$pos); */
														?>
                                                               <i class="helper"></i> <img class='stack_img' src='<?php  echo $sizeUrl;?>' width="<?php echo $sizewidth;?>" height="<?php echo $sizeheight;?>" alt='<?php echo get_the_title( $list_id ); ?>'/>
                                                            <div class="slide_detail">
                                                                <?php   if($list_id == $maxsScorePost){ echo '<div class="greenbadge">Voted The BEST</div>';}
                                                                elseif($list_post->promoted){ echo '<div class="purplebadge">Promoted</div>'; }
                                                                else{
                                                                    if(!empty($price_starting_from)){ echo '<div class="orangebadge">$'.$price_starting_from.'&nbsp;/&nbsp;'.$pricingplan.'</div>';

                                                                    }

                                                                }
                                                                if(!empty($video)){
                                                                    ?>
                                                                    <div class="playbtn playme" onclick="revealVideo('<?php echo $videoId;?>','<?php echo $ifarmeId;?>')"></div>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>

                                        </div>

                                        <!-- controls -->
                                        <?php if($numItems >1){?>
                                            <div class="componentControls test">
                                                <!-- next -->
                                                <div class="controls_next">
                                                    <img src="<?php echo image_path_single('data/icons/next.png');?>" alt="" width="30" height="30"/>
                                                </div>
                                                <!-- toggle -->
                                                <div class="controls_toggle">
                                                    <img src="<?php echo image_path_single('data/icons/pause.png');?>" alt="" width="30" height="30"/>
                                                </div>
                                                <!-- previous -->
                                                <div class="controls_previous">
                                                    <img src="<?php echo image_path_single('data/icons/prev.png');?>" alt="" width="30" height="30"/>
                                                </div>


                                            </div>
                                            <!-- nav controls -->
                                            <div class="navControls componentControls">
                                                <ul>
                                                    <li class="controls_previous">
                                                        <a href="#"></a>
                                                    </li>
                                                    <li class="controls_previous">
                                                        <a href="#"></a>
                                                    </li>
                                                    <li class="controls_next">
                                                        <a href="#"></a>
                                                    </li>
                                                    <li class="controls_next">
                                                        <a href="#"></a>
                                                    </li>
                                                </ul>

                                            </div>
                                        <?php }?>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-5 nopadding">
                              
                                    <span class="ranknumber"><?php echo $index;?></span>
                                   
                                    <div class="productbox zf-list_item"><?php if($availability == 'no') { echo "<div class='pos-relative'><div class='text-overlay'></div>"; } ?><p class="badge list-other-badge"> 
                                            <?php  // if($list_id == $maxsScorePost){
                                            // print_r($ranks);
                                            $ranks = $this->get_item_ranks($list_id);
                                            foreach ($ranks as $rid => $raval){
                                                if($rid != $main_list_id){
                                                    echo '
                                            <a class="ls_referal_link" data-parameter="listid" data-id="'.$main_list_id.'" href="'.get_permalink($rid).'"> <span class="rank-ts"><span class="rr-val">'.$raval.'</span> </span> <span class="badge-text"> in '.get_the_title($rid).'</span></a>
                                        ';
                                                    break;
                                                }
                                            }
                                            ?>
                                        </p>
                                        <?php //} ?>
                                        <div class="titlebox">

                                            <h3 class="zf-list_title producttitle" data-zf-post-parent-id="<?php echo $main_list_id; ?>">
                                                <a class="mes-lc-li-link update_list_modified_link" data-zf-post-id="<?php echo $list_id; ?>" data-zf-post-parent-id="<?php echo $main_list_id; ?>" href="<?php echo get_the_permalink( $list_id ) ?>"><?php echo get_the_title( $list_id ); ?></a>
                                            </h3>
                                            <?php $total_votes = get_post_meta( $list_id, 'votes_given', true );

                                            if ( ! isset( $total_votes[$main_list_id] ) ) {
                                                $total_votes[$main_list_id] = 0;
                                            }

                                            if ( $voting_closed ) {
                                                $class_prefix= 'vts_';
                                            }else {
                                                $class_prefix= 'zf_';
                                            }
                                            if ( ! $voting_closed ) {
                                                ?>


                                                <div class="commentbtn">
                                                    <ul  class="zf-item-vote thumbs" data-zf-post-id="<?php echo $list_id; ?>" data-zf-post-parent-id="<?php echo $main_list_id; ?>">
                                                        <li>
                                                            <a href="javascript:;" class="zf-vote_btn <?php echo $class_prefix.'vote_up'; ?>">
                                                                <i class="fa fa-thumbs-o-up"></i>
                                                            </a>
                                                        </li>
                                                        <li class="spinner-list"><span class="zf-vote_count" data-zf-post-id="<?php echo $list_id; ?>" data-zf-votes="<?php echo number_format((float)$total_votes[$main_list_id],1); ?>">
															<i class="zf-icon zf-spinner-pulse"></i>
															<span class="zf-vote_number">
																<?php echo number_format((float)$total_votes[$main_list_id],1); ?>														</span>
														</span></li>
                                                        <li >
                                                            <a href="javascript:;" class="zf-vote_btn <?php echo $class_prefix.'vote_down'; ?>">
                                                                <i class="fa fa-thumbs-o-down"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            <?php  } ?>
                                        </div>
                                        <div class="reviewbox">
                                            <div class="review">
                                                <p class="nomargin">Editor</p>
                                                <?php echo do_shortcode( '[rwp_reviewer_rating_stars id=0 size=14 post='.$list_id.']' ); ?>
                                            </div>
                                            <div class="review">
                                                <p class="nomargin">User</p>
                                                <?php
                                                echo do_shortcode( '[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=14 post='.$list_id.']' );
                                                ?>
                                            </div>
                                        </div>
                                        <div class="product-excerpt">
                                            <div class="hr">
                                                <p>
                                                    <?php echo get_excerpt_custom( $list_id,120); ?>
                                                    <a class="mes-lc-li-link update_list_modified_link" data-zf-post-id="<?php echo $list_id; ?>" data-zf-post-parent-id="<?php echo $main_list_id; ?>" href="<?php echo get_the_permalink( $list_id ); ?>" rel="nofollow"><span class="moretext">... Read full review</span></a>
                                                </p>
                                            </div>
                                            <div class="hr">
                                                <hr class="xshr">
                                            </div>
                                        </div>
                                       
                                        <div class="features">

                                            <?php $features   = get_field( 'features_list', $list_id );
                                            $count = 0;

                                            if(!empty($features) && is_array($features)){
                                                $count = count($features);
                                                $addClass = $count <= 6?' fullul':'';
                                                echo '  <p><strong>Key Features :</strong></p><ul class="featurespoints'.$addClass.'">';

                                                foreach ($features as $ind => $feat){echo "<li>• $feat</li>";
                                                    if($ind == 5){
                                                        break;
                                                    }
                                                }
                                                echo '</ul>';

                                                if($count > 6){
                                                    $rest = $count-6;
                                                    echo '<ul class="otherlist"> <li class="others"><a class="mes-lc-li-link update_list_modified_link" data-zf-post-id="'.$list_id.'" data-zf-post-parent-id="'.$main_list_id.'" href="'.get_the_permalink( $list_id ).'#features">+'.$rest.' others </a></li></ul>';
                                                }

                                            }

                                            ?>

                                        </div>
                                        <?php if($availability == 'no') { echo "</div>"; } ?>
                                        <hr>

                                        <div class="supportcontact">
                                            <?php 
                                            $findr_scoreList = 0;  // to be show on the list
                                                $reviews = get_overall_combined_rating($list_post->ID);
                                                // print_r($reviews);
                                                $i=0;
                                                $findr_scoreList += $reviews['list']['featuresfunctionality']['score']*3;
                                                $findr_scoreList += $reviews['list']['easeofuse']['score']*2;
                                                $findr_scoreList += $reviews['list']['customersupport']['score']*2;
                                                $findr_scoreList += $reviews['list']['valueformoney']['score']*3;
                                                $findr_scoreList += (50/($kk-1));
                                                $findr_scoreList = round($findr_scoreList);
                                                $degree = $findr_scoreList*3.6;
                                                if($findr_scoreList>50){
                                                $style='style="
                                                -webkit-transform: rotate(180deg);
                                                -moz-transform: rotate(180deg);
                                                -ms-transform: rotate(180deg);
                                                -o-transform: rotate(180deg);
                                                transform: rotate(180deg);
                                                position: absolute;
                                                border: 0.08em solid #9e9da5;
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
                                        }
                                        else{
                                            $style="";
                                            $sliceStyle="";
                                        }
                                               
                                            if($availability == 'no') { ?>
                                              <div class="notavailable-notice pull-left">
                                              
                                                <center class="no-border red"><b>This item is no longer available ! </b></center>
                                               </div>
                                            <?php } ?>
                                            <?php /* 	if ( $support ) {
                                                $supportIcons = ' <ul class="pull-left">';
                                                $countSup = end($support);
                                                foreach ($support as  $ckey){
                                                    $supportIcons .=" <li><i class='fa fa-$ckey'></i></li>";
                                                    if($countSup != $ckey){
                                                        $supportIcons .=" <li class='grey'>|</li>";
                                                    }
                                                }
                                                $supportIcons .=" <li class='grey'>Support</li>";
                                                $supportIcons .= "</ul>";
                                                echo $supportIcons;

                                            } */?>
                                            <div class="pull-left2 flex">
                                            <?php echo '<div class="c100 center">
                                                <span>'.$findr_scoreList.'</span>
                                                <div class="slice" '.$sliceStyle.'><div class="bar" style="
                                                -webkit-transform: rotate('.$degree.'deg);
                                                -moz-transform: rotate('.$degree.'deg);
                                                -ms-transform: rotate('.$degree.'deg);
                                                -o-transform: rotate('.$degree.'deg);
                                                transform: rotate('.$degree.'deg);
                                            "></div><div class="fill" '.$style.
                                         '></div></div>
                                            </div>'; ?> <div class="flex findrscore">findrScore</div>
                                        </div>
                                            
                                            <ul class="pull-right2">
                                                <li class="report greya">
                                                    <a data-fancybox data-src="#reportModal" data-animation-duration="500" data-modal="true" href="javascript:;" data-item="<?php echo $list_id?>" data-title="<?php echo get_the_title( $list_id ); ?>">

                                                        <i class="fa fa-flag"></i> Report
                                                    </a>
                                                </li>
                                                													<li>	<?php if ( ! empty( $source_url ) ) { ?>
												<a class="credit-btn" href="<?php echo $source_url ?>" rel="nofollow" target="_blank"><?php echo $credit ?></a>
													<?php } ?>    </li>  
                                                <?php 
                                                if($availability == 'no') { ?>
                                                        <li class="greya"><a href="<?php echo get_the_permalink( $list_id )?>alternative/" class="alter-btn getbtn" data-parameter="itemid" data-id="<?php echo $list_id ?>" >Alernative</a></li>   
                                               <?php } else {

													if ( ! empty( $affiliate_url ) ) { 
														if(substr_count($affiliate_url, "?")>=1){
															$affiliate_url.="&utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=list".$pageID;
														}
														else{
															$affiliate_url.="?utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=list".$pageID;
														}
														
												?>
                                                    <li class="greya">
                                                        
                                                        <div class="<?php if(!$list_post->promoted && !$tooltipAdded && (round($list_post->votes)>=90)) {echo "tooltip"; }?>">
                                                            <a class="mes-lc-li-down btn-affiliate zf-buy-button getbtn" target="_blank" href="<?php echo $affiliate_url; ?>" rel="nofollow" ><?php echo $affiliate_button_text ?></a>
                                                            <?php if(!$list_post->promoted && !$tooltipAdded && (round($list_post->votes)>=90)) {echo "<span class=\"visibletooltiptext\">Over ".round($list_post->votes)." users downloaded this.</span>"; $tooltipAdded = true;} ?>
                                                        </div> 
                                                        <span >
                                                            <?php
                                                                // echo $list_id;
                                                                // echo 'downloads_in_'.$location;
                                                                // echo get_post_meta($list_id,'downloads_in_'.$location,true);
                                                            ?>
                                                        </span>
                                                </li>
                                                <?php }
                                                } ?>



                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid hrcon">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $conval = ob_get_contents();
                    ob_end_clean();

                    if(isset( $list_post->promoted )){
                        $promoteditems .=  $conval;
                    } else{
                        $alllistitems .=  $conval;
                    }
                    $index++;
					
                endforeach;
                echo $promoteditems;
                echo $alllistitems;
                echo "</div>";
				$big = 999999999; // need an unlikely integer
				/* echo paginate_links( array(
						'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format' => '?page=%#%',
						'current' => $current_page,
						'total' =>  $total_pages
					) ); */
                    $pagelink 	 = get_permalink();
                    $options	 = get_option( 'mv_list_items_settings' );
                    $scroll 	 = !empty( $options['scroll_setting'] )?$options['scroll_setting']:'';
                    if($scroll == "numeric"){
                        if($total_pages > 1){	
                             for ($i=1; $i<=$total_pages; $i++) { 
                              if ($i==$current_page) { 
                                  $pagLink .= "<a class='page-numbers current' href='"
                                                                    .$pagelink.$i."/'>".$i."</a>"; 
                              }             
                              else  { 
                                  $pagLink .= "<a class='page-numbers' href='".$pagelink.$i."/'> 
                                                                    ".$i."</a>";   
                              } 
                             }; 
                             echo '<div class="container"><div class="row"><div class="col-md-12" >';
                             echo "<nav class='navigation pagination' role='navigation'><div class='nav-links' style='margin: 0px auto;padding-bottom:40px'>"; 
                             echo $pagLink;  
                             echo "</div></nav>";  
                             echo "</div></div></div>";  
                        }
                    } elseif($scroll == "loadbutton"){ ?>
                        <div id="ajax-response"></div>
                        <div id="nopostmessage"></div>
                        <div class="ajax-ele" style="text-align: center;"> 
                          <button id="loadmore" data-startpage="<?php echo $current_page+1 ?>"  data-uri="<?php echo $_SERVER['REQUEST_URI']; ?>"  class="getbtn" style="border:none">Load More</button>  
                          <div class="loader-container" style="text-align: center;padding: 40px;">  
                            <img id="ajax-loader"  data-uri="<?php echo $_SERVER['REQUEST_URI']; ?>" src="<?php echo $site_url; ?>/wp-content/uploads/2019/04/ajax-loader-1.gif" style="display:none;"/>
                          </div>
                      </div>	
            <?php	}else{ ?> 
                        <div id="ajax-response"></div>
                         <div id="nopostmessage"></div>
                         <div class="ajax-ele" style="text-align: center;"> 
                            <img id="infinite-loader" data-startpage="<?php echo $current_page+1 ?>"  data-uri="<?php echo $_SERVER['REQUEST_URI']; ?>" src="<?php echo $site_url; ?>/wp-content/uploads/2019/04/ajax-loader-1.gif" />
                         </div>
            <?php	}
                  ?>
                  <input type="hidden" id="total-pages" value="<?php echo $total_pages ?>" />
                  
                 
                    
            </div>
            <div class="container-fluid section6">
                <div class="container">
                    <div class="row justify-content-md-center">
                        <div class="col-md-7 center">
                            <h4 style="color: #fff; font-size: 1.75rem; font-family: ProximaNova !important;">
                                Add Your Recommendation:
                            </h4>
                            <ul class="addrec">
                                <li class="photocam">
                                    <img width="25px" src="<?php echo image_path_single('images/photocam.jpg')?>">
                                </li>
                                <li class="">
                                    <form>
                                        <input type="text" placeholder="Something missing? add it here...">
                                        <img src="<?php echo image_path_single('images/searchicon.png');?>">
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid section5">
                <div class="container">
                    <div class="row justify-content-md-center">
                        <div class="col-md-10 center">
                            <?php   if(!empty($postarr) && count($postarr) > 0){?>
                                <p class="center greycol">
                                    <?php echo get_the_title()." Comparison Summary";?>
                                </p>
                                <div class="top-list_item_table">
                                    <?php


                                    $index = 1;
                                    echo "<table class='list-tab'><thead><th>Rank</th><th>Name</th><th>Rating</th><th>Price</th><th>Pricing Model</th></thead>"; $cont=0;
                                    foreach ($postarr as $key1=>$score): // variable must be called $post (IMPORTANT)
                                        if($index > 5){
											
                                            break;
                                        }
										$cont++;
                                        $main_list_idn = get_the_ID();
                                        //setup_postdata( $post );
                                        $list_postii = $postarrOBJ[$key1];
                                        $list_idn = $list_postii->ID;
                                        $pricing_model = get_field( 'pricing_model', $list_idn );
										////field get Price Starting From//////////
										if(!empty(get_field( 'price_starting_from', $list_idn ))){
										$pricing_start = get_field( 'price_starting_from', $list_idn );
										}else{
											$pricing_start ='-';
										}
                                        $score = RWP_API::get_review( $list_idn);
                                        echo "<tr><td>".$cont."</td><td><a class='update_list_modified_link' data-zf-post-id='$list_idn' data-zf-post-parent-id='$main_list_idn' href='".get_permalink($list_idn)."'>".get_the_title($list_idn)."</a> </td><td>".round($score['review_overall_score'],1)."/5</td><td>".$pricing_start."</td><td>".str_replace( '_', ' ', implode( ', ', array_map( 'ucfirst', $pricing_model ) ) )."</td></tr>";
                                        $index++;
                                    endforeach;
                                    echo "</table>";


                                    ?>
                                </div>
                            <?php } ?>
                            <div class="helpful">
                                <p>
                                    Was this helpful?
                                </p>
                                <ul>
                                    <li><?php if(function_exists('the_ratings')) { the_ratings(); } ?></li>
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
            <div>
                <?php echo $videoItems;?>
            </div>


            <?php
            if(!empty($compareItems) && count($compareItems) > 1) {
                echo ' <div class="comparison_fix_footer">
 <a href="javascript:;" class="compare_close"><i class="fa fa-times-circle"></i> </a>
 <div class="compare_items"> <div style="display:table" ><div style="display:table-row" ><div style="display:table-cell; vertical-align:middle" >Compare the top two solutions: </div>';
                foreach ($compareItems as $ind => $cmp){
                    echo '<div style="display:table-cell; vertical-align:middle" ><span class="cp-item cp-item-foot-'.($ind+1).'" title="'.get_the_title($cmp) .'">'  .get_thumbnail_small($cmp,array(50,50)). ' </span></div>';
					
					if($ind<1){
						echo '<div style="display:table-cell; vertical-align:middle" ><span class="cp-vs-inner2">vs</span> </div>';
					}
                }

                echo '</div></div></div><div class="compare_btn"> <a href="' . generate_compare_link($compareItems) . '/" class="new-comparison-btn getbtn ls_referal_link" data-parameter="listid" data-rid="'.$main_list_id.'" data-id="' . $compareItems[0] . '" data-secondary="' . $compareItems[1] . '">Compare Now</a></div></div>';
           }?>
            <div id="reportModal" class="p-5 animated-modal" style="display: none;max-width:600px;">
                <button data-fancybox-close="" class="fancybox-close-small" title="Close"><svg viewBox="0 0 32 32"><path d="M10,10 L22,22 M22,10 L10,22"></path></svg></button>
                <form method="post" id="report-submit">
                    <input type="hidden" name="list_id" value="<?php echo $main_list_id; ?>">
                    <input type="hidden" name="list_item" id="report_list_item" value="">
                    <h3 class="report-head">
                        Report <span id="item_name"></span>

                    </h3>
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
            $html = ob_get_contents();
            ob_end_clean();
            wp_reset_postdata();

            //var_dump($current_page+1 ); die;

        }
        // return "hello";
        // echo "get_list_html_complete";
        return $html;

    }

    function update_vote() {
        $post_id = $_POST['post_id'];
        $post_parent_id = $_POST['post_parent_id'];
        $vote_type = $_POST['vote_type'];

        if ( isset( $_COOKIE["list_creator_post_vote_".(int)$_POST["post_id"]] ) ) {

            $voted = 1;
            $voted_type = $_COOKIE["list_creator_post_vote_".(int)$_POST["post_id"]];

        }

        $total_votes = get_post_meta( $post_id, 'votes_given', true );
        if ( ! isset( $total_votes[$post_parent_id] ) ) {
            $total_votes[$post_parent_id] = 0;
        }
        $votes_count = $total_votes[$post_parent_id];


        if ( $voted == 1 ) {

            if ( $_POST["vote_type"] == 'up' ) {

                if ( $voted_type == 'up' ) {
                    $votes_count--;

                    setcookie( "list_creator_post_vote_".(int)$_POST["post_id"], "up", time()-60*60*24, '/' );
                } else {
                    $votes_count+=2;

                    setcookie( "list_creator_post_vote_".(int)$_POST["post_id"], "up", time()+60*60*24, '/' );
                }

            } else {

                if ( $voted_type == 'down' ) {
                    $votes_count++;

                    setcookie( "list_creator_post_vote_".(int)$_POST["post_id"], "down", time()-60*60*24, '/' );
                } else {
                    $votes_count-=2;

                    setcookie( "list_creator_post_vote_".(int)$_POST["post_id"], "down", time()+60*60*24, '/' );
                }

            }

        } else {

            if ( $_POST["vote_type"] == 'up' ) {
                $votes_count++;
                setcookie( "list_creator_post_vote_".(int)$_POST["post_id"], "up", time()+60*60*24, '/' );
            } else {
                $votes_count--;
                setcookie( "list_creator_post_vote_".(int)$_POST["post_id"], "down", time()+60*60*24, '/' );
            }

        }
        $total_votes[$post_parent_id] = $votes_count;
        update_post_meta( $post_id, 'votes_given', $total_votes );

        echo wp_json_encode( array( 'post_id'=>
            $post_id, 'votes'=>$votes_count ) );
        die;
    }

    function cmp( $a, $b ) {
        return $a->votes <= $b->votes;
        //  return strcmp($b->votes,$a->votes);
    }
    function acme_post_exists( $id ) {
        return is_string( get_post_status( $id ) );
    }

    function also_mentioned_in() {
        if ( is_singular( 'list_items' ) ) {

            $itemiid = get_the_ID();
            if ( !empty($this->ranklist) && is_array($this->ranklist) ) {
                $listrankord = $this->ranklist;
                echo '<div><h3 id="awards">'.get_the_title().' is Featured In:</h3>';
                foreach ( $listrankord as $id => $rank ) {
                    $post_id = $id;
                    $rankhtm = '';
                    if($rank > 0){
                        $rankhtm = '<span class="rank-ts"><span class="rr-val">'.$rank.'</span> </span>';
                    }
					$image1 = get_the_post_thumbnail_url($post_id, array( '230', '200' ));
	
                    echo '<div class="zombify_mentioned_in" data-mh="equal-height-mentioned">
					<h4 data-mh="equal-height-mentioned-title"><a href="'.get_the_permalink( $post_id ).'">'.do_shortcode(get_the_title( $post_id )
                        ).'</a></h4>
					<a href="'.get_the_permalink( $post_id ).'">
					'.$rankhtm.'<img src="'.$image1.'" class="img-responsive sss" alt="'.get_the_title($post_id).'" >	
					</a>
					</div>';
                }
                echo '</div>';
            }
        }
    }

    function get_alternate_items() {
        $review_id= get_the_ID();
        $html ='';
        $compObj = new Mv_List_Comparision();
        $lists = $compObj->most_compared($review_id,20,true);

        // echo '<pre>';
        // var_dump($lists);
        if ( !empty ( $lists ) ) {
            $html ='<div class="alternate-carousel"><h3 id="alternative">Alternatives to '.get_the_title( $review_id ).'</h3>';
            $html .='<div class="see-more-btn" style=""><a href="'.get_the_permalink( $review_id ).'alternative/" class="ls_referal_link" data-parameter="itemid" data-id="'.$review_id.'">See More</a></div> <div class="clr"></div>';
            $html .='<div class="full-width"><div class="flexslider carousel cr-alternate">
            <ul class="slides">';
            $ac = 0;
            foreach ( $lists as $pid ) {
            $images = get_the_post_thumbnail_url($pid,array(150,150));?>
				
              <?php   $html .='<li><div class="cs-title single-line"><a href="'.get_the_permalink($pid).'"> '.get_the_title($pid).'</a></div> <div class="cs-image"><a href="'.get_the_permalink($pid).'"> <img src="'.$images.'" class="img-responsive sss" alt="'.get_the_title($pid).'" ></a></div> </li>';
               
                $ac++;
            }
            $html .='</ul></div></div></div>';
        }



        return $html;
    }

    function add_shortcode_column( $columns ) {
        // unset($columns['author']);
        return array_merge( $columns,
            array( 'shortcode' => __( 'Shortcode' ) ) );
    }
    function shortcode_column( $column, $post_id ) {
        switch ( $column ) {
            case 'shortcode':
                echo "[show_list id=$post_id]";
                break;
        }

    }

    function get_single_list_item_header( $post_id ) {
        $affiliate_url = $this->affiliate_url;
        $affiliate_button_text = $this->affiliate_button_text;
        $source_url = $this->source_url;
        $credit_text = $this->credit_text;
        $pricing_model = $this->pricing_model;
        $img = get_the_post_thumbnail( $post_id, 'medium' );
        $availability = get_post_meta( $post_id, '_item_availbility', true );
		$coupon_availability = get_post_meta($post_id,'coupons_list',true);		
		$unique_slice = array_slice($this->ranklist, 0, 1);
        $fisrt =  array_shift($unique_slice);
        if(!empty($fisrt)){
            $friends_count = array_count_values($this->ranklist);
            $batchCount = $friends_count[$fisrt];
        }
        ob_start();
        ?>        
        <div class="item-header-cnt"
            <?php
            if(isset($_GET['listid']) && !empty($_GET['listid'])) {
                $list_id = $_GET['listid'];
                echo "data-zf-post-id=\"$post_id\" data-zf-post-parent-id=\"$list_id\" ";
            }
            ?>
       >
        <?php if($availability == 'no') { ?>
        <div class="notavailable-notice"><p><center><b>This product is no longer available.  <a href="<?php echo get_the_permalink( $post_id )?>alternative" class="alter-btn plain-link" data-parameter="itemid" data-id="<?php echo $post_id ?>" >Check out top</a> that works similar or better!</b></center></p></div>
        <?php } ?>
            <div class="title-rating-cont" id="review-title">
                <?php if(!empty($batchCount)):?>
                    <div class="batch-id">
                        <a href="#awards"><div class="batch-data"><span class="rank-val">#<?php echo $fisrt?></span><span class="rank-in">in</span><span class="rank-items"><?php echo $batchCount?></span></div>
                            <div class="batch-image"></div></a>

                    </div>
                <?php endif;?>
                <div class="title-container" >
                    <h1 class="entry-title1"><span itemprop="name"><?php echo get_the_title( $post_id ).":Pricing and Features"; ?></span></h1>
                    <div class="pricing-mdl">Pricing Model: <?php echo str_replace( '_', ' ', implode( ', ', array_map( 'ucfirst', $pricing_model ) ) ); ?></div>

                </div>
                <div class="clr"></div>
                <div class="title-other-container">

                    <div class="avg-rating-cont">
                        <div class="editor-rating-cont">
                            Editor Rating
                            <br/>
                            <?php   //$users_rating = RWP_API::get_reviews_box_users_rating($post_id,-1);
                            //echo do_shortcode( '[rwp-users-rating-stars  id=0 size=20 post='.$post_id.']' );
                            ?><?php echo do_shortcode( '[rwp_reviewer_rating_stars id=0 size=20 post='.$post_id.']' ); ?>

                        </div>
                        <div class="user-rating-cont">
                            User's Rating
                            <br/>
                            <?php
                            echo do_shortcode( '[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=20 post='.$post_id.']' );
                            // $user_score = get_post_meta( $post_id, 'user_rating_custom', true );
                            // if ( $user_score ) {
                            // 	$user_score = round( $user_score );
                            // }else {
                            // 	$user_score = 0;
                            // }
                            // $obj = new RWP_Rating_Stars_Shortcode();
                            // echo $obj->get_stars( $user_score, '20', '5' ) ;
                            ?>


                        </div>
                        <div class="clr"></div>
                    </div>
                    <div class="item-links">
                        <?php
						  
													if ( ! empty( $affiliate_url ) ) { 
														if(substr_count($affiliate_url, "?")>=1){
															$affiliate_url.="&utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=".$post_id;
														}
														else{
															$affiliate_url.="?utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=".$post_id;
														}
														
													}
						
						
						
                        $afflink = empty( $affiliate_url )? $source_url:$affiliate_url;
                        $btntext = empty( $affiliate_button_text )? $credit_text:$affiliate_button_text;
                        ?>
                         <?php if($availability == 'no') { ?>
                            <a href="<?php echo get_the_permalink( $post_id )?>alternative/" class="alter-btn head-links" data-parameter="itemid" data-id="<?php echo $post_id ?>" >Alernative</a>
						
						<?php } else { ?><a class="mes-lc-li-down aff-link head-links" href="<?php echo $afflink; ?>" rel="nofollow" target="_blank"><?php echo $btntext; ?></a><?php } ?><a id="go-to-user-comments" class="custom-link head-links" href="javascript:void(0)" rel="nofollow" target="_blank">Write a Review</a><?php if(!empty($coupon_availability)) {?><a href="<?php echo get_the_permalink( $post_id )?>coupon/" class="alter-btn head-links" data-parameter="itemid" data-id="<?php echo $post_id ?>" >Coupon</a><?php }?>
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
    function get_item_sidebar($post_id){
        $rating_item = do_shortcode( '[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=20 post='.$post_id.']' );
        $sidebar .= '<div class ="main_div_sidebar"><div class="right_sidebar_style">';  
        $sidebar .= '<div class ="main_container_sidebar">';
        $sidebar .=  '<div class="it-ot-title-new sidebar-content sidebar-item-title "><span itemprop="name"> In this review</span></div>';
        $sidebar .= '<ul class = "sidebar-content-list sidebar-content">';
        $sidebar .= '<li><i class="fa fa-square"></i><a href="#overview"> Overview</a></li>
                    <li><i class="fa fa-square"></i> <a href="#video">Videos</a></li> 
                    <li><i class="fa fa-square"></i><a href="#price"> Pricing</a></li>
                    <li><i class="fa fa-square"></i><a href="#screenshot"> Screenshots</a></li> 
                    <li><i class="fa fa-square"></i><a href="#conclusion"> Pros & Cons</a></li> 
                    <li><i class="fa fa-square"></i><a href="#reviews"> User Reviews</a></li> 
                    <li><i class="fa fa-square"></i><a href="#ranking"> Ranking</a></li>';
        $sidebar .= '</ul></div>';
        $sidebar .= '<div class="sidebar-section-2">';
        $sidebar .='<p class="visit_btn_sec"><a href="" class="visit_btn">Visit Site <i class="fa fa-lock"></i> </a></p>';
        $sidebar .='<div class= "reviw-sec">Write a Review ';
        $sidebar .='| See Coupon';
        $sidebar .='<div style="width: 33%;margin: 0 auto;"> '.$rating_item.'(User Rating)</div>';
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
    function get_award_list($post_id){
        $post_id = get_the_id();
        // print_r($post_id);
        $this->ranklist = $this->get_item_ranks($post_id);
        // print_r("listrankord ");
        // print_r($this->ranklist);
        $listrankord = $this->ranklist; 
       
       $all_item_count = count( $listrankord );
       $i=0;
        if(!empty($listrankord)){
                        foreach ( $listrankord as $id => $rank ) {          
                            $ranked_list= ($rank/$all_item_count)*100;                        
                            if($ranked_list <20){
                                $award_list[] = $id;                               
                            }


                        }

                        

                        $total_award = $this->total_award= count($award_list);                 
                      
                        foreach($award_list as $award_item_name){
                            $award_items = get_the_title($award_item_name);
                            
                             $i++;
                             if($i=20) 
                               break;
                             }

                    }
                   

                   
                //    echo $total_award;
                //    echo $award_items;
        $ranked_result .= '<div class = "ranking_sec">';
        // $ranked_result .= '<div class="item_list_page_container">';

        // $ranked_result .= '<h3>Ranking & Positions </h3>';
        $ranked_result .= '<div class = "row ranking_sec">';
        $ranked_result .= '<div class = "col-sm-6 award_text"><img src="https://area52.softwarefindr.com/wp-content/uploads/2020/02/leaf4a.png" class="award_leaf">'.$total_award.'  Awards<img src="https://area52.softwarefindr.com/wp-content/uploads/2020/02/leaf4b.png" class="award_leaf">';
        $ranked_result .= '</div>';//col-sm - 4 close
        $ranked_result .= '<div class = "col-sm-6"> ';
        $ranked_result .= $award_items;
        $ranked_result .= '</div>';//colsn-8 close
        $ranked_result .= '</div>';//row ranking
        $ranked_result .= '</div>'; //item-ranking-position
        // $ranked_result .= '  </div>';
            

        return $ranked_result;
    }


    function keyfeature_pricing($post_id){

        $key_feature_result .= '<div class = "item-overview-content">';        
        $key_feature_result .= '<div class = "item-key-feature item-sec-div">';
        $key_feature_result .= '<h3>Key Features </h3>';
        $item_rating_feature = get_field( 'features_list_ratings', $post_id );
        foreach($item_rating_feature as $key=> $item_key_feature_rat){ 
        $all_feature_score[] = $item_key_feature_rat[total_score];
        $all_feature_name[] = $key;
     
      }


        $arrToPass = array(
            array(
                'label'				=> get_the_title($post_id),
                'backgroundColor'	=> 'rgba(54, 162, 235, 0.2)',
                'borderColor'       => 'rgb(54, 162, 235)',
                'data'				=>  $all_feature_score,
            ));

        $key_feature_result .= '  <div>					
                    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
                    <!-- <canvas id="keyfeature_chart" width="600" height="400"></canvas> -->
                   <canvas id="marksChart" width="600" height="400"></canvas>
                    <script>						
                        var marksCanvas = document.getElementById("marksChart");
                         var marksData = {
                            labels: '.json_encode($all_feature_name).',
                            datasets:  '.json_encode($arrToPass).' 
                        };
                        var radarChart = new Chart(marksCanvas, {
                          type: "radar",
                          data: marksData
                        });                    
                    </script>
                </div>';
        
        $key_feature_result .= '  <div class="single-list-data zf-item-vote" data-zf-post-id="'.$post_id.'"> ';      
        $key_feature_result .= ' <div id="app">
            <v-app>';?>
        	<?php          
					if(isset($_SESSION['act'])){
						$acts =   $_SESSION['act'];
					}
					if(isset($_SESSION['feature_btn_actions'])){
						$feature_btn_name =  $_SESSION['feature_btn_actions'];
					}else{
						$feature_btn_name = array(); 
					}  
					if(isset($_SESSION['post_ids'])){
						$session_post_ids =  $_SESSION['post_ids'];
					}else{
						$session_post_ids = array();
					}          
					$k = 1;
					$l = 0;  ?>
             <?php      
        $key_feature_result .= '<div class="col-sm-12">';
        foreach($item_rating_feature as $f => $r){
            $key_feature_result .= '<div class="col-sm-5 featurre2" style=" margin: 0 5% 3% 0; background-color:#fff;  border: 1px solid #e6e6e6;">';
            $f_ = str_replace(" ","_",$f);
            $key_feature_result .= '<h4>  '.$k.'.'.$f.' <span> <i class="fa fa-question-circle qmark" style=" color:grey; margin-top: 2px;"></i></span> </h4>';
            $key_feature_result .= '<div class="info hidden">This information is based on what our users have shared with us, in some cases, the solution in question could update its feature list which may not reflect here immediately.</div>';
            $key_feature_result .= '<v-slider
            v-on:click="greet"
            v-model=" '.$f_ .'.val"
            :color="'.$f_ .'.color"
            :thumb-size="15"
            data-obj="'. $f_ .'"
            step="1"
            thumb-label="always"
            ticks
            tick-size="10"
            min=1
            max=10            
            ></v-slider>';       
           $key_feature_result .= '<span class="status">{{ '.$f_ .' .status}}</span> <br>';          
           $key_feature_result .= '<button class="relevent" id="relavant_'.$k.'" value="'.$f.'" style="color: gray;" ';
           if(in_array("relavant_$k", $feature_btn_name) && in_array($post_id, $session_post_ids)){
           $key_feature_result .= ' disabled style="color: rgb(190, 190, 190);"';
            }
            $key_feature_result .= '><i class="fa fa-caret-up" style="padding-top:2px;"></i> ';           
           $key_feature_result .= ' Relevent</button>  <button class="irrelevent" id="irrelavant_'. $k.'" value="'. $f.' "';
           $key_feature_result .= ' style="color: gray;" ';           
            if(in_array("irrelavant_$k.'", $feature_btn_name) && in_array($post_id, $session_post_ids))
           { 
            $key_feature_result .= 'disabled style="color: rgb(190, 190, 190);" ';
            
            } 
            $key_feature_result .= '> <i class="fa fa-caret-down" style="padding-top:3px;"></i> Irrelevent</button>';
           $key_feature_result .= '  <input type="hidden" class="f_id" value="'. htmlspecialchars(json_encode($post_id)) .'"/> ';
           $key_feature_result .= '<input type="hidden" class="f_name" value="'.$f_.'"/> ';
           $key_feature_result .= '</div>';
           }       
           $key_feature_result .= '</div>'; 
           $key_feature_result .= '   </v-app>
                </div>';
              ?>
            <?php
        $key_feature_result .= '<script>
               
                 var vm = new Vue({
                 el: \'#app\',
                 vuetify: new Vuetify(),
                 data: {';              
                   foreach($item_rating_feature as $f => $r){ 
                       $label = $f;
                       $f_ = str_replace(" ","_",$f);                         
                       $score = $r[average];
                       $item_result .= ('\''.$f_.'\' : {label : \''.$label.'\', val : '.$score.', color: \'green\' ,status:\'\'},');
                 }                     
        $key_feature_result .= '},  
                   methods: {
                   greet: function (event) {   
                   var input = event.target.querySelectorAll(\'input\');       
                   if(input.length === 0){           
                        alert(\'Failed! Please try again\');
                        return false;
                    }
                    var feature_name = input[0].getAttribute("data-obj");//(jQuery(event.target).find(\'input\')[0]).getAttribute("data-obj");
                    if(feature_name === null){
                        (vm._data[feature_name]).status = \'Failed! Please try again\';
                    }
                    (vm._data[feature_name]).color = \'yellow\';
                    (vm._data[feature_name]).status = \'Please wait....\';
                    var vote= (vm._data[feature_name]).val;';

        $key_feature_result .= '  var zf_post_id = document.querySelectorAll(\'.zf-item-vote\')[0].getAttribute("data-zf-post-id");
            console.log(zf_post_id);
            // var zf_post_parent_id = jQuery(this).closest(\'.zf-item-vote\').attr("data-zf-post-parent-id");
            console.log("before ajax vote up");
            jQuery.ajax({
           url: \''.admin_url('admin-ajax.php').'\',
           type: \'POST\',
           data: {post_id: zf_post_id, feature_name : feature_name, vote_size : vote, action: \'mes-lc-feature-rate\'},
           dataType: \'json\',
           success: function (data) {
               console.log(data);
               (vm._data[feature_name]).val = data[\'rating\'];
               (vm._data[feature_name]).color = \'green\';
               (vm._data[feature_name]).status = \'Thanks for voting!\';
               console.log("success vote up");
               // if (data.voted) setVoteCount(data, true);
               jQuery(".zf-vote_count[data-zf-post-id=\'"+data.post_id+"\']").closest(\'.zf-item-vote\').removeClass(\'zf-loading\');
                }
                    });
                }
                }
                });  </script>';

        $key_feature_result .= '<script>
                console.log("i tried so hard");
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
       
        $key_feature_result .= ' var ajaxurl = \''.admin_url('admin-ajax.php').' \'';
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
        
        $key_feature_result .= ' var ajaxurl = \''.admin_url('admin-ajax.php').'\';';
	 
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
        $key_feature_result .= '</div>'; //item-key-feature

        //integrate_with          
        
          $key_feature_result .= '<div class = "item-integrateswell-with item-sec-div">';
          $key_feature_result .= '<h3>Integrates well with  </h3>';
  
          $all_integrated_list =  get_field( 'integrate_with_item', $post_id, false );
          // print_r( $all_integrated_list);
           // count($all_integrated_list);   
          $key_feature_result .='<div class="row"><div class="col-sm-12 more_data">';
          foreach($all_integrated_list as $integrated_item){
          $integarte_item_name = get_the_title($integrated_item);
          $integarte_item_img = get_the_post_thumbnail( $integrated_item,  array(100,100));         
          $key_feature_result .='<div class="col-sm-6" > <div class="col-sm-5 integrate_img">';
          $key_feature_result .=  $integarte_item_img;
          $key_feature_result .=' </div >';
          $key_feature_result .=' <div class="col-sm-6 integrate_name">';
          $key_feature_result .=  "<strong><span>".$integarte_item_name."</strong></span>";
          $key_feature_result .=' </div></div>';  
          $total_integrate_list[]=$integrated_item;
       }
      
       $key_feature_result .='</div >
    
       </div>';
       $key_feature_result .= '</div>'; //item-integrateswell with"
  
       //pricing result 

       
    // <!-- /************************************************************chart******************************************************************* */ -->


    // print_r($result);
   // <!-- /************************************************************chart******************************************************************* */ -->
        $key_feature_result .='<div class="pricing-item-new left-content-box style="
        background: white;"><h3 id="pricing"><div class="pricing-title left-title"><span>Pricing</span></div></h3> ';
        $key_feature_result .= "<ul class='pricing-details'>";	
        if(!empty($this->price_starting_from)){
			$new_plan = $this->plan; 
            $key_feature_result .="<li itemprop='offers' itemscope itemtype='http://schema.org/Offer'><label>Starting From:</label><span itemprop='priceCurrency' content='USD'>$</span><span itemprop='price'>".preg_replace("/[^0-9.]/", "", $this->price_starting_from)."</span><span itemprop='price_plan'>&nbsp;/&nbsp;" .$new_plan."</span></li>";
			
        }
        $key_feature_result .="<li><label>Pricing Model:</label>".str_replace( '_', ' ', implode( ', ', array_map( 'ucfirst', $this->pricing_model ) ) )."</li>";
        if(!empty($this->free_trial)){
            $card = 'No Credit Card required';
            if($this->free_trial_card){
                $card = 'Credit Card required';
            }
            $key_feature_result .="<li><label>Free Trial:</label>Available ($card)</li>";
        }
        if(!empty($this->additional_price_info)){
            $key_feature_result .="<li>$this->additional_price_info</li>";
        }
        $key_feature_result.='</ul></div>';

        $key_feature_result .= '</div>'; //item-pricing"



        return $key_feature_result;
    }
    
    function pricing_chart($post_id){                  
    $list_item  = $this->get_item_ranks($pId);
    foreach($list_item as $key => $lists){
        $post_ids = get_field('list_items' ,$key);       
       foreach($post_ids as $post_id_item){
      $all_item_id[] = $post_id_item->ID;     
       }      
    }        
    foreach($all_item_id as $all_items){       
        $pricing_model_check[$all_items] = get_field( 'pricing_model',$all_items );
        $item_coupon_all[$all_items] = get_post_meta($all_items, 'coupons_list', true);
        $free_trial_all[$all_items] = get_field( 'free_trial', $all_items );
        // $rating[] = get_overall_combined_rating($all_items); //Overall combined rating      
        $all_support     = get_overall_combined_rating($all_items);
         $all_support_score[$all_items]  = $all_support['list'][overallrating][score]*10;
     }

     $this->all_support_score_avg = array_sum($all_support_score)/count($all_item_id);
 
     $this->all_support_score_per = array_sum($all_support_score)*100/ count($all_item_id);
     foreach($free_trial_all as  $key =>$free_trial){
        if($free_trial == 1)
        {
            $free +=$free_trial;

        } 
        // print_r($key);
     }
     $free_trail_percentage = $free*100/count($all_item_id);     
     $coupon_filter = array_filter($item_coupon_all);    
     foreach($coupon_filter as $key => $coupon_one ){ 
        
     
                foreach($coupon_one as $single_coupon){
                //    print_r($single_coupon);
                $all_list[] = $single_coupon;
                }
                
      }

   $coupn_count = count($all_list);
    $coupon_all_list = $coupn_count*100/ count($all_item_id);//coupon percentage
     foreach($pricing_model_check as  $key =>$price){
     foreach($price as $pric_list){
        $list_price[] = $pric_list;
     } 

    }
    
    $i=0;
    foreach($total_integrate_list as $intergate_single_item){
    $items_names[] = get_the_title($intergate_single_item);
    $i++;
    if($i==3) break;
    }
    $items_names_3 = implode(",",$items_names);    
    $integrate_Avg = round(count($total_integrate_list)/count($all_item_id),2);  
    if(!empty($all_integrated_list) ){
        $integrate_text = "Looking at the data collected on our platform, the average amount of integrations confirmed by similar solutions is $integrate_Avg.
        The top 3 interrogations are $items_names_3 .  ";
    }
    else{
        $integrate_text = "This item is not integrate with anyone";
        
    }
    $item_result .= $integrate_text;
    $list_item_count  =array_count_values($list_price);
    $one_time_lince = $list_item_count['one_time_license'];
    $freemium = $list_item_count['freemium'];
    $subscription = $list_item_count['subscription'];
    $one_time_license = $list_item_count['subscription'];
    $license = $one_time_lince*100;  
    $license_percentage = round($license/count($all_item_id));  
    $freemium_list = $freemium*100;  
    $freemium_percentage = round($freemium_list/count($all_item_id));
    $subscription_list = $subscription*100;  
    $subscription_percentage = round($subscription_list/count($all_item_id));
    $one_time_license_list = $one_time_license*100;  
    $one_time_license_percentage = round($one_time_license_list/count($all_item_id)); 

   

        $pricing_result .= '<div class = "item-pricing item-sec-div " id="price">';    
        // $one_time_license_percentage = 25;
        $colorOfOneTimePercentage = "LIGHTSALMON";
        $one_time_license_percentage_title = "One-time License";        
        $p2show_freemium = $freemium_percentage;
        $freemium_percentage = $one_time_license_percentage+$p2show_freemium;
        $colorOfFreemiumPercentage = "indianred";
        $freemium_title = "Freemium";        
        $p2show_subscription = $subscription_percentage;
        $subscription_percentage = $freemium_percentage+$p2show_subscription;
        $colorOfSubscriptionPercentage = "pink";
        $subscription_percentage_title = "Subscription";   

            $pricing_result .= '<div id="circles" style="background:#2898f7;">
            <div class="viewport inview">
            <svg width="100%" height="666px" viewBox="217 116 554 666" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">';
            $pricing_result .= '<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(218.000000, 116.000000)"><g><g transform="translate(336.000000, 98.000000)"></g></g><g transform="translate(250.000000, 169.000000)" stroke="#FFFFFF" stroke-width="2"><path d="M0,0 L101,67"></path><path d="M6,224 L107,291" transform="translate(56.500000, 257.500000) scale(-1, 1) translate(-56.500000, -257.500000) "></path>';
            $pricing_result .= '</g><text font-size="20" line-spacing="25" fill="#FFFFFF"><tspan x="100" y="19">Pricing Model</tspan></text><g transform="translate(89.000000, 32.000000)"><g><circle r="88" cx="88" cy="88" fill="#e8d743" />';
            $pricing_result .= '<circle r="44" cx="88" cy="88" fill="transparent" stroke="'.$colorOfSubscriptionPercentage.'" stroke-width="88" transform="rotate(-90) translate(-176)"stroke-dasharray="calc('.$subscription_percentage.' * 276.32 / 100) 276.32"/>';
            $pricing_result .= '<rect x="200" y="45" width="20" height="20" rx="5" fill="'.$colorOfSubscriptionPercentage.'" /><text class="detail-text" font-size="20" line-spacing="20" fill="#5BD49C"><tspan x="230" y="65">'.$subscription_percentage_title.' '.$p2show_subscription.'%</tspan></text>';
            $pricing_result .= '<circle r="44" cx="88" cy="88" fill="transparent"stroke="'.$colorOfFreemiumPercentage.'"stroke-width="88"stroke-dasharray="calc('.$freemium_percentage.' * 276.32 / 100) 276.32"transform="rotate(-90) translate(-176)" />';
            $pricing_result .= '<rect x="200" y="70" width="20" height="20" rx="5" fill="'.$colorOfFreemiumPercentage.'" /><text class="detail-text" font-size="20" line-spacing="20" fill="#5BD49C"><tspan x="230" y="90">'.$freemium_title.' '.$p2show_freemium.'%</tspan></text>';
            $pricing_result .= '<circle r="44" cx="88" cy="88" fill="transparent"stroke="'.$colorOfOneTimePercentage.'" stroke-width="88" stroke-dasharray="calc('.$one_time_license_percentage.'  * 276.32 / 100) 276.32" transform="rotate(-90) translate(-176)" />';
            $pricing_result .= '<rect x="200" y="95" width="20" height="20" rx="5" fill="'.$colorOfOneTimePercentage.'" /><text class="detail-text" font-size="20" line-spacing="20" fill="#5BD49C"><tspan x="230" y="115">'.$one_time_license_percentage_title.' '.$one_time_license_percentage.'%</tspan></text></g></g>';
            $pricing_result .= '<text font-size="20" line-spacing="25" fill="#FFFFFF"><tspan x="366" y="185">Promotional Offers</tspan></text><g transform="translate(324.000000, 197.000000)"><circle fill="#b2e8d6" stroke="#fff" stroke-width="2" cx="114" cy="114" r="114" class="circ-fill" id="r15"></circle>';
            $pricing_result .= '<circle r="57" cx="62" cy="114" fill="transparent" stroke="#bbb0b0" stroke-width="114" stroke-dasharray="calc('.round($coupon_all_list).' * 357.9 / 100) 357.9" transform="rotate(-90) translate(-176)" />';
            $pricing_result .= '<text class="detail-text" font-size="20" line-spacing="25" fill="forestgreen"><tspan x="135" y="86">'.round($coupon_all_list).'%</tspan></text> </g><text font-size="20" line-spacing="25" fill="#FFFFFF"><tspan x="80" y="364">Free Trials</tspan></text>';
            $pricing_result .= '<g transform="translate(0.000000, 377.000000)"><circle fill="#c2d2e0" stroke="#fff" stroke-width="2" cx="132" cy="132" r="132" class="circ-fill-circle" id="r16"></circle><circle r="66" cx="44" cy="132" fill="transparent" stroke="#bbb0b0" stroke-width="131" stroke-dasharray="calc('.round($free_trail_percentage).' * 414.48 / 100) 414.48" transform="rotate(-90) translate(-176)" /> ';
            $pricing_result .= '<text class="detail-text" font-size="20" line-spacing="25" fill="forestgreen"><tspan x="141" y="76">'.round($free_trail_percentage).'%</tspan></text>';
            $pricing_result .= '</g></svg></div></div>';             
        
            return $pricing_result;
        }

        function price_performance_chart($post_id){
            $performance_chart_result .= '  <div class="item-overview-content">';
	        	$performance_chart_result .= '<div class="charts_graph doughnut">                           
							<div id="canvas-holder" style="width: 33%; float: left;" >
                                 <canvas id="chart-area" width="220"></canvas>
                            </div>                          
                           <div id="canvas-holder" style="width: 33%; float: left;">
                             <canvas id="chart-area1" width="220"></canvas>
                           </div>                             
                           <div id="canvas-holder" style="width: 33%;  float: left;">
                             <canvas id="chart-area2" width="220"></canvas>
                           </div>
                           </div>';

                           $performance_chart_result .= '<div class="pricegraph_item" style="padding-top: 50px;"> ';    
                           $performance_chart_result .= ' <span class ="bartittle" >low </span> <span class ="bartittle"> Mid </span> <span class ="bartittle"> High  </span>
                                <canvas id="chart3" width="800" height="300" style="padding:20px;  box-shadow:2px 2px 2px rgba(0,0,0,0.2);"></canvas>
                                <div class="graph_text">
                                    <div class="textgraph" style="text-align: left;">
                                          After analyzing '.$total.'similar solutions the data above show that <a href="'.get_the_permalink($min_id).'"> '.$namemin.'</a> offers the lowesest starting price while <a href="'.get_the_permalink( $max_id).'"> '.$namemax.'</a> offers the highest entry price.That being said is also worthtaking a closer look at whats on offer because sometimes you may get way more value for a solution with a higher entry price and vise versa
                        
                                    </div>
                                    </div>';
                                 
                           $performance_chart_result .= '</div>';
                // $performance_chart_result='  <div class="pricegraph_item" style="padding-top: 50px;">
                //                    <span class ="bartittle" >low </span> <span class ="bartittle"> Mid </span> <span class ="bartittle"> High  </span>
                //                    <canvas id="chart3" width="800" height="300" style="padding:20px;  box-shadow:2px 2px 2px rgba(0,0,0,0.2);"></canvas>
                //                           </div>
        		// 				   <div class="graph_text">
                //                     <div class="textgraph" style="text-align: left;">
                                    
                //                     </div>
                //                     </div>';

                                //     $performance_chart_result='  After analyzing '.$total.'similar solutions the data above show that <a href="'.get_the_permalink($min_id).'"> '.$namemin.'</a> offers the lowesest starting price while <a href="'.get_the_permalink( $max_id).'"> '.$namemax.'</a> offers the highest entry price.That being said is also worthtaking a closer look at whats on offer because sometimes you may get way more value for a solution with a higher entry price and vise versa
                                //           </div>
                                //             </div>
        						//    ';









                //for price vs perforamnce
            $post_id = get_the_id();        
            $compObj = new Mv_List_Comparision();
            $comared_data = $compObj->most_compared($post_id,40);    
            foreach($comared_data as $post_idss)
                {
                    $all_items_fs["_".$post_idss]= get_or_calc_fs_individual($post_idss);  
                    $all_price = get_field( 'price_starting_from', $post_idss); 
                    $all_price_trim["_".$post_idss] =   intval($all_price);       
                $ids_title["_".$post_idss]= get_the_title($post_idss);              
                }
                $result = array_merge_recursive($ids_title,$all_items_fs, $all_price_trim);     
                $result = array_merge(array(array('Id' , 'fs', 'price')),$result); 
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
                width: 800,
                height: 500,
                title: 'Price vs. Performance Comparision',       
                    hAxis: {title: 'Overall Performance Score (0-100 , better scores are to the right)',
                    viewWindow: { 
                    min: rangeX.min-10,
                    max: rangeX.max+10
                    }},
                    vAxis: {title: 'Price $(Lower is better)',
                    viewWindow: { 
                    min: rangeY.min-10,
                    max: rangeY.max+10
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
         $performance_chart_result .=' <div class = "div_img"><div id="scatterchart_material"></div></div>';
         $performance_chart_result .= '</div></div>'; //item-pricing-performace-comp

         return $performance_chart_result;
        }

        function data_item_page($post_id){
            $post_id = get_the_id();
            $findrScore = $this->findrScore ; 
                $item_result .= ' <div class="item-overview-content">';            
                $item_result .= '<div class = "item-findrscore item-sec-div">';
                $item_result .= '<h3>Average FindrScore in this Category</h3>';
            //-----------------Average findrscore in this category chart start--------------------------------
                $minfs_list = $this->min_fs_list;
                $highest_fs = $this->highest_fs;
                $all_fs_svg = $this->all_fs_avg;
                $item_result .='<div class="_30_3B"> 
                <div class="_27_Cy">
                    <div class="_2mh2-">Average FindrScore in this Category </div>
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
                                <div class="zHZ2m _2zkHr">'.round($minfs_list).'<span class="_2-B6C">%</span>
                                </div>
                                <span class="w9dS5">Lowest score</span>
                            </div>
                        </div>
                    </li>               
                    <li style="left:calc('.$all_fs_svg.'% - 0.72px); " class="_3gKl5">
                        <div class="Ywjxq">
                            <div class="_1mOpY">
                                <div class="_2I_0Z _2E4dc">

                                </div>
                            </div>
                            <div class="_2qYt4 kRixw">
                                <div class="zHZ2m _38leh">'.round($all_fs_svg).'<span class="_2-B6C">%</span>
                                </div>
                                <span class="w9dS5">Average score</span>
                            </div>
                        </div>
                    </li>
                    <li style="left:calc('.round($findrScore) .'% - 0.72px); " class="_3gKl5">
                        <div class="_2qYt4-- kRixw item_average">
                            <div class="zHZ2m _38leh">'.round($findrScore).'<span class="_2-B6C">%</span>
                            </div>
                            <span class="w9dS5">This Model</span>
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
                                    <div class="zHZ2m _2fML7">'.round($highest_fs).'<span class="_2-B6C">%</span>
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
        $item_result .= '<h3>SoftwareFindr Radar</h3>';

        $item_result .= '<div class="radar_wave_chart">dsklfjkl<br>gdsgdsgads<br>gdgdsgdsgds<br>
        <div class="chartWithOverlay">
        <div id="series_chart_div" style="width: 700px; height: 500px;"></div>
        

        <div class="overlay">
         <div style="    position: relative;
         left: -64px;
         top: -6px">
         <img src="https://area52.softwarefindr.com/wp-content/uploads/2020/03/mouseover_chart_transparent.png" style="
         opacity: 0.5;
         width: 452px;
         height: 365px;
     "></div>
         
       </div>
        </div>
        </div>';

        $item_result .= '</div>'; //item-softwarefindr-radar

        $item_result .= '<div class = "item-alternative-compare item-sec-div">';
        $item_result .= '<h3>Compare '.get_the_title($post_id).' to Similar Solutions</h3>';
        $item_result .= 'When evaluating '.get_the_title($post_id).' our users also give serious thoughts to these other solutions';?>
            <script>
            
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawSeriesChart);

            function drawSeriesChart() {

            var data = google.visualization.arrayToDataTable([
                ['ID', 'Life Expectancy', 'Fertility Rate', 'Region',     'Population'],
                ['CAN',    80.66,              1.67,      'North America',  33739900],
                ['DEU',    79.84,              1.36,      'Europe',         81902307],
                ['DNK',    78.6,               1.84,      'Europe',         5523095],
                ['EGY',    72.73,              2.78,      'Middle East',    79716203],
                ['GBR',    80.05,              2,         'Europe',         61801570],
                ['IRN',    72.49,              1.7,       'Middle East',    73137148],
                ['IRQ',    68.09,              4.77,      'Middle East',    31090763],
                ['ISR',    81.55,              2.96,      'Middle East',    7485600],
                ['RUS',    68.6,               1.54,      'Europe',         141850000],
                ['USA',    78.09,              2.05,      'North America',  307007000]
            ]);

            var options = {
                title: 'Correlation between life expectancy, fertility rate ' +
                        'and population of some world countries (2010)',
                hAxis: {title: 'Research Frequency', ticks: [{v:40, f:'weak'},  {v:100, f:'strong'}]},
                vAxis: {title: 'Findrscore',
                    viewWindow : {
                      min : "weak",
                      max : "strong",
                    },
            ticks: [ {v:10, f:'strong'}]
                },

                         
                bubble: {textStyle: {fontSize: 11}}      };

            var chart = new google.visualization.BubbleChart(document.getElementById('series_chart_div'));
            chart.draw(data, options);
            }

            console.log("askdjgk");
                    var svg = document.getElementById('series_chart_div');
            console.log(svg);
            

            window.addEventListener("load", function() {
    // var svgObject = document.getElementById('svg-object').contentDocument;
    var svg1 = document.getElementById('series_chart_div');
    console.log("svg1");
    console.log(svg1);
  });

            </script>

            <?php
            $compObj = new Mv_List_Comparision();
                $returns = $compObj->most_compared($post_id);

                if(!empty($returns) && is_array($returns)){    
                $titleCurr = get_the_title($post_id);     
                //$imagecurr = get_thumbnail_small($post_id,array(100,100));
                $imagecurr = get_the_post_thumbnail_url($post_id,array(100,100));

                $item_result .= '<div class="comparison-list">';
                foreach ($returns as $id){
                $com_id = get_the_title($id);    
                    $item_image = get_the_post_thumbnail_url($id,array(100,100));
                    $item_result .= '<div class="comparison-box-list">
                    <a href="'.generate_compare_link(array($post_id,$id)).'/" class="new-comparison-btn ls_referal_link" data-parameter="itemid" data-id="'.$post_id.'" data-secondary="'.$id.'">
                    <div class="cp-item1"><img src="'.$imagecurr.'" class="img-responsive sss" alt="'.get_the_title($post_id).'">
                    <span class="cp-title">'.$titleCurr.'</span> </div>

                <p style="float: left;position: relative;top: 29px; text-align: center; width: 35%; "> 
                <span class="cp-vs-item"><span class="cp-vs-inner-item">vs</span></span></p>

                    <div class="cp-item1"> <img src="'.$item_image.'" class="img-responsive sss" alt="'.get_the_title($id).'" >
                    <span class="cp-title">'.get_the_title($id).'</span></div> </div></a>';
            
                }
                $item_result .= "</div>";
                }

                    
                    $item_result .= '</div>'; //item-alternative-compare

                    $item_result .= '<div class = "item-alternative item-sec-div">';
                    $item_result .= '<h3>Alternatives to '.get_the_title( $review_id ).'</h3>';
                    $findrScore = 0; // use calculate_findrscore_individual fxn
                    

                    $lists = $this->get_alternate_items_info($post_id);
            
                
                    if ( !empty ( $lists ) ) {          
                        
                        // $item_result.='<div class="see-more-btn" style=""><a href="'.get_the_permalink( $review_id ).'alternative/" class="ls_referal_link" data-parameter="itemid" data-id="'.$review_id.'">See More</a></div> <div class="clr"></div>';
                        $item_result .='<div class="full-width-itm"><div class="flexslider-1 carousel-itmcr-alternate">
                        <div class="altr-box"><div class="row"> <div class="col-sm-12">';
                        $ac = 0;
                        foreach ( $lists as $pid ) {
                        $pid = $pid[id];
                        $fs_alternative[$pid] = get_or_calc_fs_individual($pid );  
                        $price_altrnative[$pid] =  intval (get_field( 'price_starting_from', $pid ));
                            $reviews = get_overall_combined_rating($pid);
                            $findrScore = $reviews['list'][overallrating][score]*10;
                            $listrankord = $this->ranklist;
                            // print_r($listrankord);
                            $percentileSum = 0;
                            // print_r($listrankord);
                            foreach($listrankord as $listid => $rank){
                                $noOflistitems = sizeof(get_field('list_items',$listid));
                                // echo "sizeof lists $noOflistitems";
                                $percentileSum += ($noOflistitems-$rank)/$noOflistitems;
                            }
                            $percentileAvg = $percentileSum/(sizeof($listrankord));
                            // print_r($listitems);
                            $findrScore += $percentileAvg*50;           
                            
                            $rating_item = do_shortcode( '[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=20 post='.$pid.']' );

                        $images = get_the_post_thumbnail_url($pid,array(150,150));
                            
                    $item_result .='<div class="item_list_alternative"> 
                    <div class="col-sm-4 item_list_img"><a href="'.get_the_permalink($pid).'"><img src="'.$images.'" class="img-responsive sss" alt="'.get_the_title($pid).'" ></a></div>
                    <div class="col-sm-5"><a href="'.get_the_permalink($pid).'"><strong><span>'.get_the_title($pid).'</span></strong></a><br>'.$rating_item.'</div>
                    <div class="col-sm-3">'.round($findrScore).'/100</div>
                    </div>';
                        
                            $ac++;
                        }
                        // print_r($price_altrnative);
                        asort($price_altrnative);
                        foreach( $price_altrnative as $key => $low_price){

                            $checp_item = get_the_title($key);
                        break;
                        }
                        arsort($fs_alternative);
                        // print_r($fs_alternative);
                        foreach($fs_alternative as $key=> $top_altr){
                            $top_altr1 = get_the_title($key);
                        break;

                        }
                        // echo $top_altr1;
                        $item_result.='</div></div></div></div></div>'; 

                    }

                    $item_result .=  '<p class="alternative-text">Not quite satisfied with '.get_the_title($post_id).'? No worries, users who research this solution also look at 
                    '.$top_altr1.'. The cheapest alternatives we found in our system are '. $checp_item.'/which users are having a positive experience with/having a negative experience with/having mixed experience with. For a detailed breakdown, click on the alternative report.</p>';
                    
                    $item_result .= '</div>'; //item-alternative
                    $item_result .= '</div>';//maindiv
                return $item_result;

        }

    function videos_sec($postid){ 
        $post_id = get_the_id();
        $item_result .= '<div class = "item-videos item-sec-div" id="video">';
        $item_result .= '<h3>Videos </h3>';
        /******************************************Video******************************************** */
      
    //		update_post_meta($post_id, 'video_list' ,array($url));
        $video_list_new = array_unique(get_post_meta($post_id, 'video_list', true));
        foreach($video_list_new as $key=> $video){
			if(trim($video) == ''){
				unset($video_list_new[$key]);
			}
        }
        $video_list_new = array_values($video_list_new); //re-index after unset
    //		echo "video_list new after get post meta";
    //		print_r($video_list_new);
    //		echo $video_list_new;
		if(!in_array($url,$video_list_new)){
				$video_list_new[] = $url;

		update_post_meta($post_id, 'video_list' ,$video_list_new);
		}
	
		
		
		
        if(!empty($video_list_new)){
            $array_head['videos'] = 'Videos';
        }


    if(!empty($video_list_new)){ 
            if(  count($video_list_new) != 1 || !empty($video) ){
            $splittedstring = explode("?v=",$video_list_new[0]);
            $firstvid = $splittedstring[1];	
            
            if(count($splittedstring) == 1){
                $splittedstring = explode("embed/",$video_list_new[0]);
            $firstvid = $splittedstring[1];	
            }
            
            $details .= ' <div id="video-slideshow" class="VideoSlideShowcomponent__Styled-tbnhnn-0 hBRwUj" data-e2e="video-slideshow">
            <div class="black-background">
                <div class="extra-black">
    
            </div>
            <div class="slideshow-container">
                <div class="copy-container">
                    <h2>Videos</h2> 
                    <span>Top of the week playlist</span>          
                    
                    <a class="up-next" role="button"><h6>Up Next</h6>
                       ';
        
            $i=0;
            foreach($video_list_new as $new)
                {
                if(trim($new) != ''){
                    $splittedstring = explode("?v=",$new);
                    $video_list_new[$i] = $splittedstring[1];

                //						print_r($splittedstring);
                    if(count($splittedstring) == 1){
                            $splittedstring = explode("embed/",$new);
                    $video_list_new[$i] = $splittedstring[1];
                        }
                       $abc =  count($video_list_new);
                      
                    // $content = file_get_contents("http://youtube.com/get_video_info?video_id=".$video_list_new[$i]);	
                    $api = "AIzaSyBxZQza9iYMySd0Tcd93k3Esv3AGfIVJp0"; // YouTube Developer API Key testdemo256@gmail.com

                    $content = file_get_contents("https://www.googleapis.com/youtube/v3/videos?key=$api&part=snippet&id=".$video_list_new[$i]);	
                    
                    // var_dump($http_response_header);
                    file_put_contents("mvlistsvn.txt","video_list_new is : ".print_r($video_list_new,true)."end of video_list_new\n",FILE_APPEND);

                    file_put_contents("mvlistsvn.txt","content youtube is : ".$content."end of content youtube\n",FILE_APPEND);

                    // parse_str($content, $ytarr);
                    // $jsondec = json_decode($ytarr['player_response'],true);
                    $jsondec = json_decode($content);

                    file_put_contents("mvlistsvn.txt","jsondec youtube is : ".$jsonedec."end of jsondec youtube\n",FILE_APPEND);
                    //['items']['snippet']['thumbnails']['default']['url']
                    $details .= '<div class="vid-item" onClick="document.getElementById(\'vid_frame1\').src=\'http://youtube.com/embed/'.$video_list_new[$i].'?autoplay=1&rel=0&showinfo=0&autohide=1\'">
                    
                        <div class="thumb">
                            <img src="'.$jsondec->items[0]->snippet->thumbnails->default->url.'">
                        </div>
                         <div class="desc">
                                    '.$jsondec->items[0]->snippet->title.'
                            </div>
                        </div>
                       
                       
                        ';

                    $i++;

                }
            
            }
        
            $details .=' </a>';
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
                                $details .= '<iframe src="https://www.youtube.com/embed/'.$firstvid.'" id="vid_frame1"  
                                    allowfullscreen
                                    webkitallowfullscreen
                                    mozallowfullscreen
                                    style="position: absolute; top: 0px; right: 0px; bottom: 0px; left: 0px; width: 100%; height: 100%;"></iframe>                   
                                
                                </div>
                            </div>
                        
                            
                        </div>
                    </div>
                    <div class="pagination">
                        <a class="pagination__prev disabled arrow-left" role="button">
                            <img alt="left arrow" id="arrow-left" class="arrow" src="data:image/svg+xml;base64,PHN2ZyBoZWlnaHQ9IjIwIiB3aWR0aD0iMTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CiAgPHBhdGggZD0iTTEyLjAwNSAyLjM1N0w5LjcxOCAwIC4wMTEgMTBsOS43MDcgMTAgMi4yODctMi4zNTdMNC41ODcgMTB6IiBmaWxsPSIjZmZmIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiLz4KPC9zdmc+Cg=="><img alt="up arrow" id="arrow-up" class="arrow" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMiIgaGVpZ2h0PSI4Ij4KICA8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGZpbGw9IiNGRkYiIGQ9Ik0xMC41ODYgNy4xMjhMMTIgNS43NyA2IC4wMDYgMCA1Ljc3bDEuNDE0IDEuMzU4TDYgMi43MjNsNC41ODYgNC40MDV6Ii8+Cjwvc3ZnPgo="></a>
                            <h6><span class="pagination__current">1</span><span>/</span><span class="pagination__total">' .$abc. '</span>
                            </h6><a class="pagination__next arrow-right" role="button">
                                <img alt="right arrow" id="arrow-right" class="arrow" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMiIgaGVpZ2h0PSIyMCI+CiAgPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBmaWxsPSIjRkZGIiBkPSJNLS4wMDUgMi4zNTdMMi4yODMgMGw5LjcwNiAxMC05LjcwNiAxMC0yLjI4OC0yLjM1N0w3LjQxNCAxMC0uMDA1IDIuMzU3eiIvPgo8L3N2Zz4K">
                                <img alt="down arrow" id="arrow-down" class="arrow" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMiIgaGVpZ2h0PSI4Ij4KICA8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGZpbGw9IiNGRkYiIGQ9Ik0xMC41ODYuOTY2TDEyIDIuMzA2IDYgNy45OTMgMCAyLjMwNiAxLjQxNC45NjYgNiA1LjMxMiAxMC41ODYuOTY2eiIvPgo8L3N2Zz4K">
                            </a>
                        </div>
                    </div>
                </div>
            </div>';
            
        }
        else{
            $details .= '<span id="novid">Author has not provided any video for this product. </span>';
        }
        } 
    //------------------------------------------video_list end-------------------------------------------------------------------



            return $details;
        }

        function support_sec($post_id){
            $post_id = get_the_id();
            $support_result .= ' <div class="item-overview-content">';  
            $support_result .= '<div class = "item-support item-sec-div">';
            $support_result .= '<h3>What Support Does This Vendor Offer? </h3>';
            $support       = get_field( 'support', $post_id );
            $item_title_name = get_the_title($post_id);
            $item_support_score     = get_overall_combined_rating($post_id);
            $item_score = $item_support_score['list'][overallrating][score]*10;
            $map =  array ("comments" => "Live Chat", "envelope" => "Mail" ,"phone" => "Phone", "wpforms" => "Forum" );
            $mapped_support = array();
            $support_result .= '<div class="row"><div class = "col-sm-12"><div class = "col-sm-4">';  
            if ( $support ) {
                $supportIcons = '<span class="support-icons"><ul>';
                foreach ($support as $ckey){
                    $mapped_support = $map[$ckey];
                    $supportIcons .="<li><i class='fa fa-$ckey'></i> $mapped_support </li>";
                }
                // $item_supports = implode(", " , $mapped_support);

                $supportIcons .= "</ul></span>";
                $support_result .=' '.$supportIcons.'';
            }
            $support_result .= ' </div>';  

            $all_support_score_avg = $this->all_support_score_avg;

            $support_result .= '<div class = "col-sm-4">';
        //    print_R($item_support_score);
            if($all_support_score_avg > $item_score){           
                $support_result .='<img src="https://area52.softwarefindr.com/wp-content/uploads/2020/02/Good-Experience.png">';
               
            }
            else{           
                $support_result .='<img src="https://area52.softwarefindr.com/wp-content/uploads/2020/02/bad-exprience.png">';
            }
            $support_result .= '</div>';
            $support_result .= '<div class = "col-sm-4">';
            
            if($all_support_score_avg > $item_support_score){   
                $support_result .='satisfaction rating which indicates that you will be in good company with $item_title_name';
                $avg_text = ' $item_title_name is below this industry average';
               
    
            }else{
                $support_result .=' satisfaction rating which isnt the best and worth consideration if you feel you might need support.';
                $avg_text = ' '.$item_title_name.' is above this industry average';
            }
    
            
            $support_result .= '</div>';
    
            $all_support_score_per =  $this->all_support_score_per;
            $support_result .= '</div>';//col-sm-12
            $support_result .= '<div class = "col-sm-12">';
            $support_result .= '<p>Looking at data gathered on our platform the industry average for this category is
             '.round($all_support_score_per,2).' % satisfaction rate which means  '.$avg_text.'
             </p>';
            $support_result .=' </div>';        
            $support_result .=' </div>';
            $support_result .= '</div>'; //item-support

            //screenshot

            
                $support_result .= '<div class = "item-screenshots item-sec-div" id="screenshot">';
                // $item_result .= '<h3>ScreenShots </h3>';
            /******************************************SCreenshort******************************************** */
            $gallery = get_field('gallery' ,$post_id);
            if(!empty($gallery) && is_array($gallery)){

                $support_result .='<div class="gallery-item left-content-box"><div class="gallery-title left-title"><h3 id="screenshots">Screenshots</h3></div> ';
                $thumbs = '';$slides = '';
                foreach ($gallery as $img){
            //echo '<pre>';
            ////print_r($img);
            //echo '</pre>';
                $thumbs .= '<li> <img src="'.(isset($img['sizes']['thumbnail'])?$img['sizes']['thumbnail']:$img['url']).'" /></li>';
                $slides .= '<li><a href="'.$img['url'].'" data-fancybox="flex_images"><img src="'.$img['url'].'" /></a> </li>';
            }
            $support_result .= '<div id="slider" class="flexslider">
            <ul class="slides">'.$slides.'</ul></div>
            <div id="carousel" class="flexslider">
            <ul class="slides">'.$thumbs.'</ul></div>';
            $support_result .='</div>';
            }

                $support_result .= '</div>'; //item-screenshots

                $support_result.='<div class="list-main-content left-content-box">';
                $faqs = '<div id="overview">
            <h3>Frequently Asked Questions</h3>

            <div>
            <span itemprop="description"> '.$this->get_faqs().'</span>
            </div>
            </div>
            ';
                $support_result.=$faqs;
                $support_result.='</div>';

            return $support_result;

        }
        





    /********************************************************item page content function start******************************************************** */
    function item_content_value($post_id){
   
    $findrScore = $this->findrScore ;//calculate_findrscore_individual($post_id);

    $item_title = get_the_title($post_id);
    $postDat = get_post($post_id);
    $contentP = $postDat->post_content;
    $categories    = $this->categories; //for managed by 
        $author_id = get_field('real_author', $post_id);
        $author_name =  get_author_name( $author_id);    
    $category_names =''; //for category
    if ( $categories ) {
        foreach ( $categories as $cat ) {
            $category_names.='<span class="cat-name"><a href="'.esc_url( get_term_link( $cat->term_id ) ).'" title="'.$cat->name.'">'.$cat->name.'</a></span> ,&nbsp;';
        }
    }
  /****************************************************PROS AND CONS**************************************************************** */
    $r2b = Array();
        $rn2b = Array();
       
        // print_r($support);
        foreach($support as $cit){
            if($cit == '24/7')
                $r2b []= "24/7 support options available";
        }
        if(sizeof($support)==1 && $support[0]=='envelope'){
            $rn2b []= "Support only includes emails";
        }
        $freeTrial = get_field('free_trial',$post_id);
        if($freeTrial)
            $r2b []= "Free Trial is offered to perform testing";

        $mbg = get_field('money_back_guarantee',$post_id);
        if($mbg){
            $r2b []= "Money back guarantee";
        }
        
        $reviews = get_overall_combined_rating($post_id);
        // echo "This is post id $post_id"; 
        // print_r ($reviews);
        if($reviews['list']['customersupport']['score'] > 4)
            $r2b []= "Friendly customer service";

        if($reviews['list']['easeofuse']['score'] > 4)
            $r2b []= "Easy to use even for a beginner";
        elseif($reviews['list']['easeofuse']['score'] < 1)
            $rn2b []="According to our users ".get_the_title( $post_id )." can be a bit confusing";
        
            $compObj = new Mv_List_Comparision();
        $lists = $compObj->most_compared($post_id,1000,true);
        
        $max = 0;
       
        foreach($lists as $alternate){
            
            $price = ltrim(get_field( 'price_starting_from', $alternate),"$");
			
			
           
                if($price > $max){
                    $max = $price;

                }
            }
           
       
            if($this->price_starting_from )
            $trimmedprice =trim(trim($this->price_starting_from," "),"$");
            if($trimmedprice=='')
                $trimmedprice = 0;
            $maxhalf = round($max/2);
            $ifvalue = ($trimmedprice <= $maxhalf);
     
        if($trimmedprice <= $maxhalf){
   

            $r2b []= "Compared to others the price is reasonable"; 
          

        }
        else{
            
            
            $rn2b []= "A bit on the Expensive side compared to other solution in this category.";
            

        }
            

            $tpi = get_field('third_party_integrations',$post_id);
        if($tpi){
            $r2b []= "Third-party integrations";
        }
            $pricing_model = get_field( 'pricing_model', $post_id);
        // print_r($pricing_model);
        if (in_array("freemium", $pricing_model))
            $r2b []= "Offers free plan with multiple advanced features";
        // if($pricing_model[])
        if($reviews['list']['overallrating']['score'] > 4)
            $r2b []= "The majority of our users are experiencing positive experience with ".get_the_title($post_id);
        elseif($reviews['list']['overallrating']['score'] < 2.5){
            $rn2b []= "A few of our users are experiencing dissatisfaction ";
        }



        $alternateinfo = $this->get_alternate_items_info($post_id);
        if(empty($alternateinfo)){
            $alternateinfo = array();
        } 
        /* $maximum =  max(array_column($alternateinfo, 'price'));
        $minimum = min(array_column($alternateinfo, 'price')); */
        // print_r($alternateinfo);
        // echo "max and min ".$maximum." and ".$minimum;
        $sum=0;
        // echo "nof : ";
        for($i=0;$i<sizeof($alternateinfo);$i++){
            $nof = sizeof(get_field( 'features_list', $alternateinfo[$i]['id'] ));
            // echo $nof." ";
            $sum += $nof;
        }
        $avgnof = $sum/sizeof($alternateinfo);
        // echo "avgnof ".$avgnof." ";
        get_field( 'features_list', $post_id );
         if(sizeof(get_field( 'features_list', $post_id )) > $avgnof)
            $r2b []= "Great features list";
        else
            $rn2b []= "Lack of features compared to other solution in this category";
         
    

        // print_r($this->ranklist);
        foreach($this->ranklist as $listid=>$rank){
            if($rank < 4){
                $r2b []= "Category leader in ".get_the_title($listid );
            }
            elseif($rank < 11){
                $r2b []= "A contender in ".get_the_title($listid );

            }
        }
        if (in_array("open_source", $pricing_model))
            $r2b []= "The core product is 100% free";

        // print_r($r2b);
        $htmlr2b = '<ul>';
        foreach($r2b as $r){
            $htmlr2b .= '<li>'.$r.'</li>';
        }
        $htmlr2b .= '</ul>';
        // print_r($rn2b);
        $htmlrn2b = '<ul>';
        foreach($rn2b as $r){
            $htmlrn2b .= '<li>'.$r.'</li>';
        }
        $htmlrn2b .= '</ul>';
        $proscons = '<div id="pros-cons" class="row">
        <div class="col-md-6 pros">
        <div class="mr-5 pr-5 col-md-6-inner">
        <h3 class="mygreen"> <i class="fa fa-check-circle mh10" aria-hidden="true"></i>'.count($r2b).' Reasons to buy</h3>'.$htmlr2b.'
        
        </div></div>
        <div class="col-md-6 cons">
        <div class="ml-5 pl-5 col-md-6-inner">
        <h3 class="myred"><i class="fa fa-times-circle mh10" aria-hidden="true"></i>'.count($rn2b).' Reasons not to buy</h3>
        '.$htmlrn2b.'
        </div>
        </div>
    </div>';
  /**********************************************PROS AND CONS************************************************************************** */

  $alternateinfo = $this->get_alternate_items_info($post_id);
  if(empty($alternateinfo)){
      $alternateinfo = array();
  }
            $maximum =  max(array_column($alternateinfo, 'price'));
            $minimum = min(array_column($alternateinfo, 'price'));
            $max =   array_keys($alternateinfo, $maximum);
           
             foreach ($alternateinfo as $alternateinfos){
               foreach($alternateinfos as $key=>$value){
             
              if($value == $maximum){
              $namemax = $alternateinfos['name'];
              $max_id = $alternateinfos['id'];
              }
              if($value == $minimum){
                  $namemin = $alternateinfos['name'];
                  $min_id = $alternateinfos['id'];
              }
               }
             }

             $review_id= get_the_ID(); //alternative grid
             $html ='';
             $compObj = new Mv_List_Comparision();
             $lists = $compObj->most_compared($review_id,20,true);
   

  
    /**********************************************alternative informatoion ************************************************************************** */
             //manage for calculation 

        $fsArr30_previous_fs = get_post_meta($post_id,'findrScore30',true);
        
        $current_fs = round($findrScore);
       $previous_fs = $fsArr30_previous_fs[fs];
    //    echo "current fs ".$current_fs." previous fs ".$previous_fs;

       if($current_fs > $previous_fs )
       {
           $fs_result = "The score for this software has increased over the past month.";
       }
       elseif($current_fs < $previous_fs){

        $fs_result = "The score for this software has declined over the past month.";
       }
       else{
        $fs_result = "The score for this software has not changed over the past month.";

       }

        $item_result .= '<div class = "item-overview-content">';
        $item_result .= '<div class = "item-content-sec1">';
        $item_result .= '<p><b>Manage by name :&nbsp;</b>'.$author_name.' <b>| Category : </b> '.$category_names.' | '.$fs_result.' </p>';
        
        $item_result .= '<div class = "item-content-editor item-sec-div"> <b>Editors note </b> - This review has been created by looking at [data points] and the opinions of actual users of the software and the company representative. 

        If new to platform less than 2 weeks: '.$item_title.' is new to our platform, so please allow some more time for our algorithm to gather more data on  '.$item_title.'. That been said here is what we’ve analyzed so far. '.$item_title.' as a FindrScore of '.round($findrScore).'  which is in the top quadrant in this segment. It’s also priced reasonably with the occasional promotions. Many users on our platform to express great satisfaction in the service provided overall. If you are not tech-savvy then you will be pleased to know '.$item_title.' is fair easy to grasp.
        </div>';
        // $item_result .= $conlast;
        $item_result .= '<div id="overview">
         <div class="overview-content">'.$contentP .'</div></div><a href="javascript:;" class="readbutton">Read More >>></a>   
       
      ';


      $item_result .= '<div class = "item-map item-sec-div">';
      $item_result .= '<h3>Adaptation by Geography </h3>';
    /************************************************************chart******************************************************************* */

   $list_setting = get_option( 'mv_list_items_settings' );
   $target_countries = $list_setting['list_page_target_countries'];
   $countryPair = explode(',',$target_countries);
   foreach($countryPair as $cp){
       $pair = explode('=>',$cp);
       foreach($pair as $key=>$value){
           $pair[$key]=trim(trim($value),"'");
       }
       
       $targetCountries[$pair[0]] = $pair[1];
   } 
   $item_one = get_the_title($post_id);
   $passToMaps = array();
   $passToMaps[]= array('Country',$item_one);

   $item_result .= ' <div id="regions_div" style="width: 70%; height: 500px; float: left;"></div>';
   $item_result .= '<div class="country-box">';
   $item_result .= '<div class="box1">
   <table id="countries_list">';
   $item_result .= '<tr><th>Country</th><th>Usage</th>';
 
      foreach($targetCountries as $key=>$tc){
        
       $downloads1[$tc] = ceil((float)get_field( 'downloads_in_'.$key, $post_id));
       
       if ($downloads1 == '' ) {
           $downloads1 = 0;
       }
    

    }
    arsort($downloads1);
    // var_dump($downloads1);
    
    foreach($downloads1  as  $download){
        if($download >=0)
            $sum_value += $download; 
        
        
    }
    

    foreach($downloads1 as $key=>$values){               
        if($values >=0){
            $passToMaps[]= array($key,$values);   

            $value_per = ($values *100)/$sum_value;           
        }
            
        else
            $value_per = 0;

        $country_name .= '<tr><td>'.$key.'</td> <td>'.round($value_per,2).'%</td></tr>'; // decending order country usage
    
       if($values != 0 ){
           $popular_countries[] = $key;
       }
    }
    $popular_countries2 = array_slice($popular_countries,0,2);
    // $last_element = array_pop($popular_countries);
   
    $countries = trim(implode(",",$popular_countries2 ),',');
    if(count($popular_countries) >2){
        $countries.=(' and '.(count($popular_countries)-2).' Other Countries.');
    }


    $item_title = get_the_title($post_id);   
    if($sum_value == 0){
        $map_data = "There’s not enough data available for  $item_title to display its geography adaption trend. We are still collecting data on  $item_title adoption check again soon. ";
    }
    else{
        $map_data = "After analyzing our data gathered on our platform $item_title is popular in these countries $countries ";
    }

    $item_result .= ' '.$country_name .'';
    // $item_result .= 'Usage <li> '. $uasge .'</li>';

    $item_result .= '</table></div> </div>';
    $item_result .= $map_data;

        
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

        // $item_result .= '<div class = "item-feature item-sec-div">';
        // $item_result .= '<h3> Features/Benefits </h3>';
        // $item_result .= '</div>'; //item-feature div
        $item_result .= '<div class = "item-feature item-sec-div">';
        $item_result.='<div class="list-main-content left-content-box">';
        $item_result .= '<h3> Pros & Cons </h3>';
        $item_result.= $proscons;
        $item_result.='</div>';
        $item_result .= '</div>'; //pros and cons       

        


?>
       <?php 


        
        $item_result .= '<div class = "item-videos item-sec-div" id="video">';
        $item_result .= '<h3>Ranking & Positions </h3>';
        /******************************************Video******************************************** */
        $video_list_new = array_unique(get_post_meta($post_id, 'video_list', true));
        foreach($video_list_new as $key=> $video){
			if(trim($video) == ''){
				unset($video_list_new[$key]);
			}
        }
        $video_list_new = array_values($video_list_new); //re-index after unset
    //		echo "video_list new after get post meta";
    //		print_r($video_list_new);
    //		echo $video_list_new;
		if(!in_array($url,$video_list_new)){
				$video_list_new[] = $url;

		update_post_meta($post_id, 'video_list' ,$video_list_new);
		}
        if(  count($video_list_new) != 1 || !empty($video) ){
            $splittedstring = explode("?v=",$video_list_new[0]);
            $firstvid = $splittedstring[1];	
			
			if(count($splittedstring) == 1){
				$splittedstring = explode("embed/",$video_list_new[0]);
            $firstvid = $splittedstring[1];	
			}
          /*   echo "video_list_new";
            print_r($video_list_new);
            echo "splittedstring";
            print_r($splittedstring);
            echo "firstvid";
            print_r($firstvid); */
           

            $item_result .= '<div class="video-item left-content-box">
            <div class="embed-container"> <div class="vid-main-wrapper clearfix">';
            $item_result .='<div class="vid-container">';	
            $item_result .= '<iframe src="https://www.youtube.com/embed/'.$firstvid.'" id="vid_frame"  
                allowfullscreen
                webkitallowfullscreen
                mozallowfullscreen
                style="position: absolute; top: 0px; right: 0px; bottom: 0px; left: 0px; width: 100%; height: 100%;"></iframe>';
            $item_result .= '</div> ';
            $item_result .= '<div class= "vid-list-container"> <div class="vid-list">';	
            
            $i=0;
            foreach($video_list_new as $new)
                {
                    if(trim($new) != ''){
                        $splittedstring = explode("?v=",$new);
                        $video_list_new[$i] = $splittedstring[1];
    
    //						print_r($splittedstring);
						if(count($splittedstring) == 1){
								$splittedstring = explode("embed/",$new);
                        $video_list_new[$i] = $splittedstring[1];
							}
						
                        // $content = file_get_contents("http://youtube.com/get_video_info?video_id=".$video_list_new[$i]);	
                        $api = "AIzaSyBxZQza9iYMySd0Tcd93k3Esv3AGfIVJp0"; // YouTube Developer API Key testdemo256@gmail.com

                        $content = file_get_contents("https://www.googleapis.com/youtube/v3/videos?key=$api&part=snippet&id=".$video_list_new[$i]);	
                        
                        // var_dump($http_response_header);
                        file_put_contents("mvlistsvn.txt","video_list_new is : ".print_r($video_list_new,true)."end of video_list_new\n",FILE_APPEND);

                        file_put_contents("mvlistsvn.txt","content youtube is : ".$content."end of content youtube\n",FILE_APPEND);

                        // parse_str($content, $ytarr);
                        // $jsondec = json_decode($ytarr['player_response'],true);
                        $jsondec = json_decode($content);

                        file_put_contents("mvlistsvn.txt","jsondec youtube is : ".$jsonedec."end of jsondec youtube\n",FILE_APPEND);
                        //['items']['snippet']['thumbnails']['default']['url']
                        $item_result .= '<div class="vid-item" onClick="document.getElementById(\'vid_frame\').src=\'http://youtube.com/embed/'.$video_list_new[$i].'?autoplay=1&rel=0&showinfo=0&autohide=1\'">
                        
                            <div class="thumb">
                                <img src="'.$jsondec->items[0]->snippet->thumbnails->default->url.'">
                            </div>
                             <div class="desc">
                                        '.$jsondec->items[0]->snippet->title.'
                                </div>
                            </div>
                            ';
    
                        $i++;
    
                    }
                
                }
            $video_list_new_id = implode(" , ", $video_list_new); 
            
    
            $item_result .= ' </div></div>';
            $item_result .= '<div class="arrows">
                <div class="arrow-left">
                    <i class="fa fa-chevron-left fa-lg"></i>
                </div>
                <div class="arrow-right">
                    <i class="fa fa-chevron-right fa-lg"></i>
                </div>
            </div>';
            $item_result .= '</div></div></div>';
        }
        /******************************************Video******************************************** */
        $item_result .= '</div>'; //item-videos

       
    /**----------------------------------------------------In complete------------------------------------------------------------------------------- */

     

    /**----------------------------------------------------In complete-------------------------------------------------------------------------- */





    $item_result .= '</div>'; //item-content-sec1 div
    $item_result .= '</div>'; //item-overview-content div
    return $item_result;
    }

/********************************************************item page content function End******************************************************** */

    function get_single_sidebar($post_id){
        $affiliate_url = $this->affiliate_url;
        $affiliate_button_text = $this->affiliate_button_text;
        $source_url    = $this->source_url;
        $credit        = $this->credit_text;
        $pricing_model = $this->pricing_model;
        $product_type  = $this->product_type;
        $editor_choice = $this->editor_choice;
        $software      = $this->software;
        $categories    = $this->categories;
        $tags          = $this->tags;
        $support       = get_field( 'support', $post_id );
        $category_names ='';
        $tag_names = '';
        if ( $categories ) {
            foreach ( $categories as $cat ) {
                $category_names.='<span class="cat-name"><a href="'.esc_url( get_term_link( $cat->term_id ) ).'" title="'.$cat->name.'">'.$cat->name.'</a></span>&nbsp;';
            }
        }
        if ( $tags ) {
            foreach ( $tags as $tag ) {
                $tag_names.='<span class="round-tags"><a href="'.esc_url( get_term_link( $tag->term_id ) ).'" title="'.$tag->name.'">'.$tag->name.'</a></span>&nbsp;';
            }
        }
        $img = get_the_post_thumbnail( $post_id, 'medium', array('itemprop' => 'image'));
        ob_start();
        ?><div class="item-other-details sidebar-column">
        <div class="item-thumb-new"><span ><?php echo $img; ?></span>
        <?php 
            // $findrScore = 0;
            // $reviews = get_overall_combined_rating($post_id);
           
            // $findrScore = $reviews['list'][overallrating][score]*10;
            // $listrankord = $this->ranklist;
            // // print_r($listrankord);
            // $percentileSum = 0;
            // // print_r($listrankord);
            // foreach($listrankord as $listid => $rank){
            //     $noOflistitems = sizeof(get_field('list_items',$listid));
            //     // echo "sizeof lists $noOflistitems";
            //     $percentileSum += ($noOflistitems-$rank)/$noOflistitems;
            // }
            // $percentileAvg = $percentileSum/(sizeof($listrankord));
            // // print_r($listitems);
            // $findrScore += $percentileAvg*50;
            // echo "<div>".round($findrScore)." findrscore</div>";
            
         ?></div>
        
        <div class="it-ot-title-new"><span itemprop="name"><?php echo get_the_title($post_id);?> Details</span></div>
        <?php
        echo '<ul class="product-details-new">';

        if ( $categories ) {
            echo '<li><span class="pro-dtl-heading" itemprop="category">Category: '.$category_names.'</span></li>';
        }

        if ( $tags ) {
            echo '<div><li><span class="pro-dtl-heading">Industry</span>: '. $tag_names.'</li></div>';
        }
        if ( $support ) {
            $supportIcons = '<span class="support-icons">';
            foreach ($support as $ckey){
                $supportIcons .="<i class='fa fa-$ckey'></i>";
            }
            $supportIcons .= "</span>";
            echo '<li><span class="pro-dtl-heading">Support</span>: '. $supportIcons.'</li>';
        }
        echo '</ul>';
        
			/*----------------------------claim button-----------------------------------*/	
			 
         $post_id = get_the_ID();
         $current_user = wp_get_current_user();
        $user_id_mv = $current_user->ID;
         echo "<i class='fa fa-question-circle' aria-hidden='true'></i> <span class='claim-listingheading'>Claim this Listing</span>
		 <a class='claim-listing-button' onclick='claim_listing_func(1,".$post_id.",".$user_id_mv.")'> Is this your product? </a>";   
      	
				
		/*----------------------------claim button end -----------------------------------*/		
          ?> 
		  
		  </div>

        <?php
        $reviews = get_overall_combined_rating($post_id);
        // print_r($reviews);
    //    $rev = new RWP_Rating_Stars_Shortcode();
    //    $reviews = $rev->combined_review($post_id,'rwp_template_590f86153bc54');
        if(!empty($reviews) && is_array($reviews)){
            ?>
            <div class="review-detail-col sidebar-column">


                <div class="it-ot-title-new"><?php echo get_the_title($post_id);?> Reviews</div>
                <?php
                foreach ($reviews['list'] as $kk => $rev){
                    $percen = ($rev['score']/5)*100;
                    $percen = round($percen);
                    ?>
                    <div class="prog-container">
                        <div class="prog-label"><span class="prg-label"><?php echo $rev['label']?></span><span class="prg-valval"> <?php echo $percen;?>%</span> </div>
                        <div class="progress">
                            <div data-name="<?php echo $kk;?>" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php $rev['score'];?>" aria-valuemin="0" aria-valuemax="5" style="width:<?php echo $percen;?>%">
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
            <div class="it-ot-title-new">Compare <?php echo get_the_title($post_id);?> to Similar Solutions</div>
            <?php
            $compObj = new Mv_List_Comparision();
            $returns = $compObj->most_compared($post_id);
		
            if(!empty($returns) && is_array($returns)){
				
                $titleCurr = get_the_title($post_id);
				
				
                //$imagecurr = get_thumbnail_small($post_id,array(100,100));
				$imagecurr = get_the_post_thumbnail_url($post_id,array(100,100));
			
                echo '<ul class="comparison-list">';

                foreach ($returns as $id){
			$com_id = get_the_title($id);
				
					$item_image = get_the_post_thumbnail_url($id,array(100,100));
					
					
			
			?>
			
                    <?php
					



                    echo '<li><a href="'.generate_compare_link(array($post_id,$id)).'/" class="new-comparison-btn ls_referal_link" data-parameter="itemid" data-id="'.$post_id.'" data-secondary="'.$id.'"><span class="cp-item1"><img src="'.$imagecurr.'" class="img-responsive sss" alt="'.get_the_title($post_id).'" ><span class="cp-title">'.$titleCurr.'</span> </span><span class="cp-vs"><span class="cp-vs-inner">vs</span></span><span class="cp-item2-footer"> <img src="'.$item_image.'" class="img-responsive sss" alt="'.get_the_title($id).'" ><span class="cp-title">'.get_the_title($id).'</span></span> </a></li>';
             

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
	function get_alternate_items_info($post_id) {
		$compObj = new Mv_List_Comparision();
        $lists = $compObj->most_compared($post_id,20,true);
        $alternate_info = array();
            foreach ( $lists as $pid ) {
                $alternate_info[] = array(
                  'id'   =>  $pid,
                 'name'  =>  get_the_title($pid),
                 'price' =>  intval (get_field( 'price_starting_from', $pid ))
                );  
            }
            return $alternate_info;       
    }
   
    function get_single_list_item_content( $post_id, $content ) {

            $findrScore=$this->findrScore = get_or_calc_fs_individual($post_id);  
            $title =  get_the_title($post_id)." Reviews: Pricing and Features";
            $rating_item = do_shortcode( '[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=20 post='.$post_id.']' );            
            $feature  = get_overall_combined_rating($post_id); // For Accounting featture
            $reviews = get_overall_combined_rating($post_id);
            $this_ease_of_use = $reviews['list'][easeofuse][score];  
            $this_value_for_money = $reviews['list'][valueformoney][score];      
            $this_customer_support = $reviews['list'][customersupport][score];
            $this_features_functionality = $reviews['list'][featuresfunctionality][score];         
            $this_features_functionality *=2; 
            $this_features_functionality_percentage = $this_features_functionality*10;
            $this_ease_of_use *=2; 
            $this_ease_of_use_percentage = $this_ease_of_use*10;
            $this_value_for_money *=2; 
            $this_value_for_money_percentage = $this_value_for_money*10;
            $this_customer_support *=2; 
            $this_customer_support_percentage = $this_customer_support*10; 

        /******************************************FOr NEW LAYOUT end******************************* */     
         //    $video_List  = get_post_meta($post_id, 'video_list', true);	
		$compObj = new Mv_List_Comparision();
         ////////////////////////////Graphs_start/////////////////////////////////////////////////////////
		$lists = $compObj->most_compared($post_id,1000,true);
        $onetime = 0;
        $subscription = 0;
        $opensource = 0;
        $freemium = 0;
        $free = 0;
		$others = 0;
        $size = sizeof($lists);
            foreach ( $lists as $pid ) {
 				   $pricing_model = get_field('pricing_model', $pid);
				   if(empty($pricing_model)){
					   $pricing_model = array();
				   }
                   $counts = array_count_values($pricing_model);
				   
				if(in_array("freemium", $pricing_model)){
                    $freemium += $counts['freemium'];
                    continue;
				}
				elseif(in_array("open_source", $pricing_model)){
                    $opensource += $counts['open_source'];
                    continue;
				}
				elseif(in_array("subscription", $pricing_model)){
                    $subscription += $counts['subscription'];
                    continue;
				}
				elseif(in_array("one_time_license", $pricing_model)){
                	$onetime += $counts['one_time_license'];
               	   continue;	
                }
				else {
					$others = $others+1;
				}
                
            }
            foreach ( $lists as $pid ) {
                $free_trial = get_field( 'free_trial', $pid );
                if($free_trial == 1)
                {
                    $free +=$free_trial;
                }
            }
            $items_ratio =  array("freemium" => $freemium, "subscription" => $subscription, "open_source" => $opensource, "one_time_license" => $onetime, 'size'=> $size, 'free'=> $free, "others" => $others);

		                    $freetr =  $items_ratio['free'];
                            $total  =  $items_ratio['size'];
                            //$subs   = ($item['subscription'] / $total) * 100;
                            $subs   = $items_ratio['subscription'];
                            $freem  = $items_ratio['freemium'] ;
                            $openso = $items_ratio['open_source'] ;
                            $onet   = $items_ratio['one_time_license'];
							$total1 = $subs + $openso + $onet;  	
                            $subscription = ($subs/$total1) * 100;  
                            $onetime      = ($onet/$total1) * 100;
                            $free         = ($openso/$total1) * 100; 
                            $freetrial    = ($freetr/$total1) * 100; 
                            $freemium     = ($freem/$total1) * 100; 
							
							$l=0;
							$lists_atlter = $compObj->most_compared($post_id,1000,true);	
				foreach($lists_atlter as $idss){
                    $pricing_model[$l] = get_field( 'pricing_model', $idss);
					if(empty($pricing_model[$l])){
						$pricing_model[$l] = array();
					}
                    if(in_array('open_source', $pricing_model[$l])) {
                        $price_starting_from = 0;
                    }else{
                        $price_starting_from = get_field( 'price_starting_from', $idss);
                    }
					 $item_pmodel[]['pmodel']= str_replace("$","",$price_starting_from);
                $l++;	
                } 
				$alternateinfo = $this->get_alternate_items_info($post_id);
				if(empty($alternateinfo)){
					$alternateinfo = array();
				}
                          $maximum =  max(array_column($alternateinfo, 'price'));
                          $minimum = min(array_column($alternateinfo, 'price'));
                          $max =   array_keys($alternateinfo, $maximum);
                         
                           foreach ($alternateinfo as $alternateinfos){
                             foreach($alternateinfos as $key=>$value){
                           
                            if($value == $maximum){
                            $namemax = $alternateinfos['name'];
                            $max_id = $alternateinfos['id'];
                            }
                            if($value == $minimum){
                                $namemin = $alternateinfos['name'];
                                $min_id = $alternateinfos['id'];
                            }
                             }
                           }
							?>
                            
						        <input type="hidden" id="subscription" value="<?php echo $subscription;?>">
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

        if(is_array($compareCat)){
            foreach ($compareCat as $cc){
                $compSlug[] = $cc->taxonomy.':'.$cc->slug;
            }
        }
        $array_head = array('overview'=>'Overview');
        $firldsGroups = get_acf_field_groups_by_cpt($compSlug);
        $video = get_field('video',$post_id); 
    //		echo $video;
        
        
    //echo count($video);
    // find all iframes generated by php or that are in html    
    preg_match_all('/src="([^\?]+)/', $video, $match);

		$url = $match[1][0];
		$url = str_replace('embed/','watch?v=',$url);
		
    //		array_push($video_list_new, $url );
	  $gallery = get_field('gallery',$post_id);
        $altername = $this->get_alternate_items();
        if(!empty($firldsGroups)){
            $array_head['features'] = 'Features';
        }
    //		update_post_meta($post_id, 'video_list' ,array($url));
        $video_list_new = array_unique(get_post_meta($post_id, 'video_list', true));
        foreach($video_list_new as $key=> $video){
			if(trim($video) == ''){
				unset($video_list_new[$key]);
			}
        }
        $video_list_new = array_values($video_list_new); //re-index after unset
    //		echo "video_list new after get post meta";
    //		print_r($video_list_new);
    //		echo $video_list_new;
		if(!in_array($url,$video_list_new)){
				$video_list_new[] = $url;

		update_post_meta($post_id, 'video_list' ,$video_list_new);
		}
	
		
		
		
        if(!empty($video_list_new)){
            $array_head['videos'] = 'Videos';
        }
        $array_head['pricing'] = 'Pricing';
        if(!empty($gallery)){
            $array_head['screenshots'] = 'Screenshots';
        } if(!empty($altername)){
            $array_head['alternative'] = 'Alternative';
        }
        $array_head['rating'] = 'Rating';
        if(!empty($this->ranklist)){
            $array_head['awards'] = 'Awards';
        }

        $lis = '';
        foreach ($array_head as $hkey => $head){
            $lis .='<li><a href="#'.$hkey.'">'.$head.'</a> </li>';
        }
        $affiliate_url = $this->affiliate_url;
        $affiliate_button_text = $this->affiliate_button_text;
        $source_url = $this->source_url;
        $credit_text = $this->credit_text;
        $afflink = empty( $affiliate_url )? $source_url:$affiliate_url;
		
		if ( ! empty( $afflink ) ) { 
														if(substr_count($afflink, "?")>=1){
															$afflink.="&utm_source=SoftwareFindr&utm_medium=free%20traffic&utm_campaign=".$post_id;
														}
														else{
															$afflink.="?utm_source=SoftwareFindr&utm_medium=free%20traffic&utm_campaign=".$post_id;
														}
		}
		
		$item_images = get_the_post_thumbnail_url($post_id);?>
        
        <?php
        $btntext = empty( $affiliate_button_text )? $credit_text:$affiliate_button_text;
    //        $btnhtml = '<a class="mes-lc-li-down aff-link head-links" href="'.$afflink.'" rel="nofollow" target="_blank">'.$btntext.'</a>';
        $revHeader = "<div id='review-fix-header'>
    </div>";
         $conlast = '<div id="overview">
                        <div class="overview-content">
                        <h3>Overview</h3>

                        <div>
                          <span itemprop="description"> '.$content.'</span>
                           
                        </div>
                        </div>
                    </div>
    ';

        $affiliate_button_text = $this->affiliate_button_text;
        $source_url =$this->source_url;
        $credit = $this->credit_text;
        $details = $revHeader;
        $home_url = get_home_url();
        $details .= '
        <div class="breadcrum">
            <p id="breadcrumbs">
                <span><span><a href="$home_url">Home</a> > <span class="breadcrumb_last" aria-current="page">'.get_the_title($post_id).'</span></span></span></p><p class="breadcrumbs_sec">Do you work for this company? <a href="#">Manage this listing</a></p></div>';
        
        
                $details .= '<div class="full-width-full item-container">';
               
                $details .= '<div class="item_list_page_container">
        <div class="title-container" >
                    <h1 class="entry-title1"><span itemprop="name">'.$title.'</span></h1>       
        </div>             
        ';
      
    /*------------------------------------------------------section1 start-----------------------------------------------------------*/  


    $list_item  = $this->get_item_ranks($pId);
    // print_r("ranked");

    // print_r($list_item);
    // print_r($list_item);
    foreach($list_item as $key => $lists){
        $post_ids = get_field('list_items' ,$key);   
    foreach($post_ids as $post_id_item){
    $all_item_id[] = $post_id_item->ID;
    }    

    }   
    // echo "all item id";
    // print_r($all_item_id);

    foreach($all_item_id as $all_items){
        
        $rat = get_overall_combined_rating($all_items); //Overall combined rating  
        $easeofuse[] = $rat['list'][easeofuse][score];
        $functionality_feature[] = $rat['list'][featuresfunctionality][score];
        $value_money[] = $rat['list'][valueformoney][score];
        $customersupport[] = $rat['list'][customersupport][score];    
        $price= get_field('price_starting_from',$all_items);
        $over_all[] = $rat['list'][overallrating][score];
        // do_shortcode( '[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=20 post='.$post_id.']' );    
        $price_trim[] = str_replace("$","",$price);
        $all_features_ratings[$all_items]  = get_field( 'features_list_ratings', $all_items ); 
        $all_item_findrscore[$all_items] = get_or_calc_fs_individual($all_items);
        $all_pricing_model[$all_items] = get_field('pricing_model',$all_items);
         


    
    }
   
    /* findrscore highest and lowest* */  
    arsort($all_item_findrscore);
    // print_r($all_item_findrscore);
     $this->highest_fs = max($all_item_findrscore);   
     $min_fs_list = $this->min_fs_list = min($all_item_findrscore);  
     $all_fs_sum = array_sum($all_item_findrscore);    
     $this->all_fs_avg = $all_fs_sum/count($all_item_id);
     
    //  echo "max";
    //  echo $max_high;
    // $max = 0;

    // $min_fs_list =  array_search(min($all_item_findrscore), $all_item_findrscore);
    // $highest_fs = array_search(max($all_item_findrscore), $all_item_findrscore);

     foreach($all_item_findrscore as $key =>$findrscore){
      

    //     $highest_fs_item = $key;
    //     $highest_fs_value = $findrscore;
    // break;

     }
     
  
 
    //  array_search
    //  echo "max";
    // echo $max;



    /* findrscore highest and lowest* */ 

    /* item details */
    $item_id = get_the_id();
    $item_overall = get_overall_combined_rating($item_id);
    $item_pricing_model = get_field('pricing_model',$item_id);
    // echo "item pricing model";
    // print_r($item_pricing_model);
    $this_pricing_model;
      $this_pricing_plan = get_field('plan',$item_id);
    //   echo "<br>this pricing plan ".$this_pricing_plan;
      $this_item_price_start = get_field('price_starting_from',$item_id);
                
      $this_item_price = (int) filter_var( $this_item_price_start, FILTER_SANITIZE_NUMBER_INT); //item price
      if($this_pricing_plan == 'Year')
          $this_item_price/=12;
        // echo "<br> this item price ".$this_item_price;
        // if( in_array('one_time_license',$item_pricing_model))
        
        if( $this_item_price > 0 ){    
        
        if( $this_pricing_plan == "One Time")
            {
            $this_pricing_model = 'one_time_license';
           

            }
            elseif($this_pricing_plan == "Year" || $this_pricing_plan == "Month")
            {
                $this_pricing_model = 'subscription';
            }
        }

            elseif( in_array('freemium',$item_pricing_model) || in_array('open_source',$item_pricing_model)){
               $price_item =  "Low Cost software";
            }
            // echo "this pricing model ".$this_pricing_model;
            // echo "<br>all pricng model";
            //  print_r($all_pricing_model);
             if($this_pricing_model == "subscription" || $this_pricing_model == "one_time_license"){
                 
            foreach($all_pricing_model as $key => $pricng_model){

                if( !in_array($this_pricing_model,$pricng_model)){
                    unset($all_pricing_model[$key]);
                }else{
                    $item_pricing_plan[$key] = get_field('plan',$key);

                    // print_r($item_pricing_plan);               

                    if($this_pricing_model == 'subscription'){
                        $price = get_field('price_starting_from',$key);
                                        // $price = str_replace("$","",$price_start);
                                        // echo "price<br>";
                                        // print_r( $price);
                        $int = (int) filter_var( $price, FILTER_SANITIZE_NUMBER_INT);
                     if ($item_pricing_plan[$key] == 'Year' )
                     {
                                        
                                        $price_convt = $int /12;
                                        // echo "int<br>";
                                        // print_r($int);
                                        // echo "end";
                                        //  print_r($price_convt);
                                        //  echo "pricecnt";

                                   
                                        // echo "price start";
                                        // print_r($price_start);
                                        if($price_convt>0)
                                            $price_start[$key] = $price_convt;

                     }
                    elseif($item_pricing_plan[$key] == 'Month') {
                        if($int>0)
                            $price_start[$key] = $int;
                        
                    }

                }
                elseif($this_pricing_model == 'one_time_license'){
                    if ($item_pricing_plan[$key] == 'One Time' ){
                    $price = get_field('price_starting_from',$key);
                    // print_r($price);

                    $int = (int) filter_var( $price, FILTER_SANITIZE_NUMBER_INT);
                    // echo "int is ".$int;
                    if($int>0)
                        $price_start[$key] = $int;

                    }

                }

                    }

            }
// echo "price_start";
// print_r($price_start);
                $price_sum = array_sum($price_start);
                // echo "price sum".$price_sum;
                $all_price_avg = $price_sum/count($price_start);        // all items avg price in a sorted way     

            
            }       
                // echo "item price".$this_item_price;
                  // $price_item = $int;




    $over_high_score = max($over_all);//overall highest score
    $item_feature_rating = get_feature_ratings($item_id);//get_field( 'features_list_ratings', $item_id ); 
    // echo "item rat score";
    // print_r($item_rat_score);
    // echo "<br>item feature rating<pre>";
    // print_r($item_feature_rating);
    foreach( $item_feature_rating as  $key =>$item_rat){
        $item_feature_individual_avg[$key] = $item_rat[average];
        
    
    }
    arsort($item_feature_individual_avg);

    foreach($all_features_ratings as $feature_rat)
    {
        foreach($feature_rat as $key =>$feature){
            // $total_score_feature_ind_avg[$key] = $feature[average];

           if(!isset($total_score_feature[$key]) || !is_array($total_score_feature[$key])){
            $total_score_feature[$key] = array();
           }
           if($feature['average'] != 0){
            $total_score_feature[$key]['total_score'] += $feature['average'];
            $total_score_feature[$key]['votes'] += 1;
            $total_score_feature[$key]['average'] = $total_score_feature[$key]['total_score']/$total_score_feature[$key]['votes'];
 
           }
        
            
        }
    
    }


    foreach($total_score_feature as $key =>$feature)
    {
        $total_score_feature_ind_avg[$key] = $feature[average];

    }    
    arsort($total_score_feature_ind_avg);
// echo "all total average score";
// print_r($total_score_feature_ind_avg);
    
    // $combine_feature = array_intersect_assoc($item_feature_individual_avg,$total_score_feature_ind_avg);



    // echo "all total score";
    // print_r($total_score_feature);


    foreach($item_feature_individual_avg as $key => $feature_one){
    if($total_score_feature_ind_avg[$key] <  $feature_one ){

    $mutual_feature_name = $key;
    }

      break;
    }

    $ease_count = array_sum($easeofuse);
    // echo "ease count".$ease_count;
    $ease_average = round($ease_count/count($all_item_id)*2,2);//ease average
  
    $functionality_count = array_sum($functionality_feature);
    $functionality_feature_average = round($functionality_count/count($all_item_id)*2,2);// feature functionality average
    $valueformoney_count = array_sum($value_money);
    $valueformoney_feature_average = round($valueformoney_count/count($all_item_id)*2,2);// valueformoney average   
    
    $customersupport_count = array_sum($customersupport);

    // echo "customersupport_count";
    // print_r($customersupport_count); 


    $customersupport_feature_average = round($customersupport_count/count($all_item_id)*2,2);// customersupport  average    
    $item_rat = $item_overall['list'][overallrating][score]; //item rating

    // echo "item_rat ".$item_rat;

    // $item_feature = get_field('features_list', $pId);
    // $featureList  = get_post_meta($post_id, 'features_list', true);

    

    $details .= '<div class=" item-description container-">'; //main header- sec item-description container div 
    $details .= '<div class="row">
            <div class="col-sm-12">

                        <div class="col-sm-6 item_list_image_sec">
                            <div class="col-sm-5 img_item img_height">';
                                $img = get_the_post_thumbnail( $post_id, 'medium', array('itemprop' => 'image'));      
                                  $details .= ''.$img.'</div> 
                            <div class="col-sm-6 item_rank_score img_height">
                            <div >
                            <div class="item-subtitle">  <div class="tooltip_1">FindrScore <i class="fa fa-info-circle"></i>
                            <span class="tooltiptext_1">The Scoring is based on our unique algorithm that looks at reviews, votes, behavior, social signals and more. <a rel="nofollow" href="/scoring-methodology/">Learn more</a></span>
                        </div></div>
                            <p class="item-score item-sec-div"><strong>'.round($findrScore).'</strong>/100</p>
                            
                            </div>
                            </div>';

                            $details .=  '<div class="col-sm-12"> <div class="rating-count">'.$rating_item.'</div>
                                            <div class="user-rating-cont">
                                            (User Rating)</div>
                                        </div>
                                            ';
                            $details.=$conlast;
                            $details.='<p class="visit_btn_sec"><a href="" class="visit_btn">Visit Site <i class="fa fa-lock"></i></a></p>';
                        $details .='</div> '; //div class="col-sm-6 item_list_image_sec 



                     $details .= '<div class="col-sm-6 item_list_content_sec">'; //item features and their progress bar first list  
                $ease_average_sr = ($ease_average*10);                
                $functionality_feature_average_sr = ($functionality_feature_average*10);
                     $details .='<div class="item-description price_feature ">
                                    <div class="col-sm-6 easeofuse"> Features & Functionality <br>
                                        <div class="progress"> 
                                      
                                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:'.$this_features_functionality_percentage.'%">
                                           
                                            </div>
                                            <span class="sr-only" style="left: calc('.$functionality_feature_average_sr .'% + 3px);"></span>
                                            
                                            <span style="position: absolute; top: 24px; width: 100%; left: calc('.$functionality_feature_average_sr .'% - 46px); font-weight: 100;
                                            font-size: 12px;">Industry avg '.$functionality_feature_average.'</span>
                                        </div>&nbsp;<span>'.$this_features_functionality.'/10<span>
                                    </div> 
                                    <div class="col-sm-6 easeofuse"> Ease of use <br>
                                        <div class="progress"> 
                                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:'.$this_ease_of_use_percentage.'%">
                                          

                                            <span class="sr-only" style="left: calc('.$ease_average_sr .'% + 3px);"></span>
                                            
                                            <span style="position: absolute; top: 24px; width: 100%; left: calc('.$ease_average_sr .'% - 46px);  font-weight: 100;
                                            font-size: 12px;">Industry avg '.$ease_average.'</span>
                                        
                                        
                                            </div>
                                        </div>&nbsp;<span>'.$this_ease_of_use.'/10<span>        
                                    </div> 
        
                               '; //col-sm-6 item_list_content_sec ,tem-description , row div
                               $valueformoney_feature_average_sr = ($valueformoney_feature_average*10);
                               $customersupport_feature_average_sr = ($customersupport_feature_average*10);
                        $details .='<div class="col-sm-6 easeofuse">Value for money<br>
                                        <div class="progress"> 
                                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:'.$this_value_for_money_percentage.'%">
                                            <span class="sr-only" style="left: calc('.$valueformoney_feature_average_sr .'% + 3px);"></span>
                                            
                                            <span style="position: absolute; top: 24px; width: 100%; left: calc('.$valueformoney_feature_average_sr .'% - 46px);  font-weight: 100;
                                            font-size: 12px;">Industry avg '.$valueformoney_feature_average.'</span>


                                             </div>
                                        </div>&nbsp;<span>'.$this_value_for_money.'/10<span>        
                                    </div>
                                    <div class="col-sm-6  easeofuse">Customer Support <br>
                                        <div class="progress"> 
                                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:'.$this_customer_support_percentage.'%">
                                           

                                            <span class="sr-only" style="left: calc('.$customersupport_feature_average_sr .'% + 3px);"></span>
                                            
                                            <span style="position: absolute; top: 24px; width: 100%; left: calc('.$customersupport_feature_average_sr .'% - 46px);  font-weight: 100;
                                            font-size: 12px;">Industry avg '.$customersupport_feature_average.'</span>

                                            </div>
                                        </div>&nbsp;<span>'.$this_customer_support.'/10<span>  
                                    </div>
                        </div>
                    ';


                    // echo "customersupport_count ".$this_customer_support;
                    // echo "customersupport_feature_average ".$customersupport_feature_average;
                    

                    if($this_customer_support > $customersupport_feature_average  ){
                        $dis_customer = "Customer Support";
                    }
                    // echo "ease of use this item score ".$this_ease_of_use;
                    // echo "ease_average ".$ease_average;
                    if($this_ease_of_use > $ease_average){
                        $ease_dis = "Ease of Use";
                    }
                    // echo "ease_dis ".$ease_dis;
                    // echo "<br>this item price ";
                    // print_r($this_item_price);
                    // echo "<br> all price avg ";
                    // print_r($all_price_avg);
                    if(($this_item_price < $all_price_avg) || $this_pricing_model == 'freemium' || $this_pricing_model == 'open_source')
                        {

                            $price_item = "Low Cost Software";
                        }
                   

                    if($item_rat >= $over_high_score){
                        $overlist = "Complete solution ";
                    }

                
                    // echo "item at sum ". $item_at_sum;
                    // echo " total score avg".$total_score_avg;
                    // if($item_at_sum > $total_score_avg){
                    //     $features_name = "Feature";
                    // }

                    // echo "feature name : ". $feature_name;
                    if( empty($dis_customer) && empty($ease_dis) && empty($price_item) && empty($overlist) && empty($mutual_feature_name) ){
                        $no_area = "No particluar area";

                    }
                    // echo "pid is $post_id";
                    $features_ratings  = get_field( 'features_list_ratings', $post_id );
                    // echo $features_ratings;
                    if($features_ratings === NULL || empty($features_ratings)){

                        

                        $features   = get_field( 'features_list', $post_id );
                        // var_dump($features);
                        if($features !== NULL && !empty($features)){

                            $features_ratings = create_feature_ratings($post_id,$features);
                            // echo "fr1 caught!";
                       
                     
                        }
                        
                    }
                    // echo "features ratings";
                    // print_r($features_ratings);

                    
                    
                             $details .='<strong>Best For: </strong> <ul class="feature_list"><li>';
                             if(!empty($dis_customer))
                             {
                                $details .=' <i class="fa fa-square"></i> '.$dis_customer.'  </li>';
                             }
                               if(!empty($price_item)){
                                $details .=' <li><i class="fa fa-square"></i> '.$price_item.'</li>';
                               }
                           if(!empty($ease_dis)){
                            $details .='  <li><i class="fa fa-square"></i> '.$ease_dis.' </li>';
                           }
                           if(!empty($ease_overlistdis)){
                             $details .='   <li><i class="fa fa-square"></i> '.$overlist.' </li>';
                           }
                             if(!empty($mutual_feature_name)){
                             $details .='   <li><i class="fa fa-square"></i> '.$mutual_feature_name.' </li>';
                             }
                             if(!empty($no_area)){
                                $details .='   <li><i class="fa fa-square"></i> '.$no_area.' </li>';
                                }
        $details .='</div>
        </div>
    </div>
    </div>';
    /*------------------------------------------------------section1 complte-----------------------------------------------------------*/
    $details .= '<div class="row"><hr class="hr-line"><img class="leaf1" src="https://area52.softwarefindr.com/wp-content/uploads/2020/02/leaf1.png"> <div class="row11"> 
    <div class="user-rat-sec">                
    Rating </br>
        <div class="rat-section">'.$rating_item.'</div>                      
     </div>
</div><p class="leaf_sec"><img src="https://area52.softwarefindr.com/wp-content/uploads/2020/02/leaf2.png" class="leaf1b"><img class="leaf2" src="https://area52.softwarefindr.com/wp-content/uploads/2020/02/leaf3a.png"></p><div class="awars_sec"> 
<div class="user-rat-sec"><span>'.$this->total_award.'</span>
    <div class="rat-section"> Awards</div>                      
 </div>
</div><img class="leaf2" src="https://area52.softwarefindr.com/wp-content/uploads/2020/02/leaf3b.png"><hr class="hr-leaf">     

</div>';

         

    
        $content=$details; 

    /**--------------------------------------------------------test-------------------------------------------------------------------------------- */
     return $content;		
    }

  


    function full_content($post_id){
        $data_all_page = $this->get_single_list_item_content( get_the_ID(), $content );   
        $data_all_page .='<div class= "item-body-content">'; //inside body content
        $data_all_page .=''.$this->get_item_sidebar($post_id).'';
        $data_all_page .= ''.$this->item_content_value($post_id).''; 
        $data_all_page .='</div>';  //item-body-content div
        $data_all_page .= '</div>'; //item-content container
        $data_all_page .= ''.$this->get_award_list($post_id).'';
        $data_all_page .= ''.$this->keyfeature_pricing($post_id).''; 
        // $details  .= $this->keyfeature_pricing($post_id); 
        $data_all_page .='<div class= "item-body-content">'; //inside body content
        $data_all_page .= ''.$this->pricing_chart($post_id).'';   
        $data_all_page .='</div">'; //inside body content close   
        $data_all_page .= ''.$this->price_performance_chart($post_id).'';   
        $data_all_page .=   '</div>';    //full-widthdiv
        $data_all_page .= ''.$this->data_item_page($post_id).''; 
        $data_all_page .= ''.$this->videos_sec($post_id).'';  
        $data_all_page .= ''.$this->support_sec($post_id).''; 
        
        
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
