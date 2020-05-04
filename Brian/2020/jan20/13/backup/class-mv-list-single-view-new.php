
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

    //geniusnuil
    private $targetCountries=array('IN','US','GM','BR','IT','CA','FR','TU','ID','ID','GB'); // array of countries for which we want to show modified lists and change url attribute ?lang=INalt


    function __construct() {
        add_filter( 'the_content', array( $this, 'show_list' ), 1  );
        add_action( 'wp_ajax_list_creator_post_vote', array( $this, 'update_vote' ) );
        add_action( 'wp_ajax_nopriv_list_creator_post_vote', array( $this, 'update_vote' ) );

        // shortcode
        add_shortcode( 'show_list', array( $this, 'show_list_shortcode' ) );

        // shortcode coloumn
        add_filter( 'manage_lists_posts_columns' , array( $this, 'add_shortcode_column' ) );
        add_action( 'manage_lists_posts_custom_column' , array( $this, 'shortcode_column' ), 10, 2 );

        //mentioned in
        add_action( 'comment_form_after', array( $this, 'also_mentioned_in' ) );
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
     

            $content = $this->get_single_list_item_content( get_the_ID(), $content );
      

        }
  

        return $content;
    }

    function get_faqs(){
        $post_id = get_the_ID();
         $iem_name = get_the_title( $post_id );
        $beginners ='';
        $rating = get_overall_combined_rating($post_id);
        // echo "<pre>";
        // var_dump($rating);
        // echo "</pre>";
/******************************************8* faq-3 start ***********************************************/
        $pricing_model = get_field( 'pricing_model', $post_id );
        $plan = get_field('plan',$post_id);
        $starting_price = get_field( 'price_starting_from', $post_id);        
          $details .="<label>Pricing Model: </label>".str_replace( '_', ' ', implode( ', ', array_map( 'ucfirst', $pricing_model ) ) );
        foreach($pricing_model as $pricing)
        {
        if($pricing == 'freemium'){
            $pric = "Free to use.";
            $free= "Yes there is a free trial available which should give you enough time to test out $iem_name to see if it’s the right fit for you.";
         }
          else{
            $pric = "You can expect to pay around </label><span itemprop='priceCurrency' content='USD'>$</span><span itemprop='price'>".preg_replace("/[^0-9.]/", "", $starting_price)."<span itemprop='price_plan'>&nbsp;/&nbsp;" .$plan ."</span></p>
            $details" ;   
            $free=   "The isn’t a mention of a free trial on our system but you can always check to see it a money back guarantee is on offer with $iem_name .";      
          }
        }
/********************************************** * end of faq-3 *******************************************/
/******************************************** * faq-1 start ***************************************/
        $overalrating = $rating['list'][overallrating][score];
      if( $overalrating > 2.5){
        $beginners = "Users who have used $iem_name as reported that it’s fairly easy to grasp.";

      } 
      else
      {
        $beginners = "Users who have used $iem_name as reported that there is a learning curve which you should keep in mind..";
      }
/****************************************************end of faq-1*****************************************/
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
// echo "<pre>";
// var_dump($couponlist);
// echo "</pre>";
/******************************************** * faq-5 & faq-7 end ******************************************/
/******************************************** * faq-8start ***************************************/

$compObj = new Mv_List_Comparision();
$lists = $compObj->most_compared( $post_id,20,true);


$pricing_model = get_field( 'pricing_model', $post_id );
$plan = get_field('plan',$post_id);
 
if ( !empty ( $lists ) ) {
// echo'<a href="'.get_the_permalink( $review_id ).'alternative/" class="ls_referal_link" data-parameter="itemid" data-id="'.$review_id.'">See More</a>';
$ac = 0;
foreach( $lists as $pid ) {	
    // print_r($pid );	
    
$pricing_model = get_field( 'pricing_model', $pid );
$plan = get_field('plan',$pid );
// echo $pid;
foreach($pricing_model as $pricing)
        {
            // echo $pricing;
        if($pricing == 'freemium'){
            echo "jhdfds";

        //     $pric = "Free to use.";
        //     $free= "Yes there is a free trial available which should give you enough time to test out $iem_name to see if it’s the right fit for you.";
         }
        }
// print_r($pricing_model);

// echo '<a href="'.get_the_permalink($pid).'"> '.get_the_title($pid).'</a><a href="'.get_the_permalink($pid).'"> </a> ';


$ac++;
}
 echo '';
}
	  

/******************************************** * faq-8end ***************************************/

//         echo "<pre>";
//         print_r($rating);
//         echo "</pre>";
//   echo "faq post id $post_id";
$faq = ' <div itemscope itemtype="https://schema.org/FAQPage">';
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
                        <h3 itemprop='name'> Q.  How good is $iem_name in [top location]? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. Out of all the users on our platform $iem_name as the highest aptotion rate in [top location].
                             </div>
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
                        <h3 itemprop='name'> Q.  What type for support can I expect with $iem_name? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. The majority of users who are sharing their experience with us on [item] are experiencing a positive experience with the support offered through [insert support provided]
                                    {Else} The majority of users who are sharing their experience with us on [item] are experiencing a poor experience with the support offered through [insert support provided]
                        
                             </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  Is there any coupons for $iem_name? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. $link_coupon_not                                
                            </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  Is $iem_name any good for [top 1 feature]? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. [item] as a high rating when it comes to [top 1 feature], scoring [] with a category average of [category average for that feature]
                                
                            </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.   Can I try $iem_name for free? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. $free
                            </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  Are there any Free alternative to $iem_name?</h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. Yes, [insert top free alternative] is proving quite popular with our users, <a href=”link to vs page”>see how it stacks up against [item]</a>
                            </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  Is $iem_name the [list]?]</h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. $iem_name is ranked x on that list according to [total users]
                            </div>
                        </div>
                    </div>
          ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q. Which is better $iem_name or [top alternative]?</h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. This is a tricky question as it all depends on your needs, but [item] as a higher FindScore than X which factors in many data points. <a href=”link to vs page”>Have a closer look to see why?</a>
                            </div>
                        </div>
                    </div>
          ";
          $faq .= "</div>";
             return $faq;
    }

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
        // print_r( $atts['id'] ); die;
        return $this->get_list_html( $atts['id']  );

    }
	//getlist filter================/
	
	
    function get_list_html( $list_id ) {
        // echo "get_list_html_entered";
        /* echo "hi";
        echo $_SERVER['REMOTE_ADDR']; */
        global $wp;
        $index =1;
        $pageID = get_the_ID();
        $main_list_id        = $list_id ;
        $site_url 			 = get_site_url();
        $main_list_permalink = get_the_permalink( $list_id );
        $main_list_uri 	 	 = get_page_uri( $list_id );
        $attached_items      = get_field( 'list_items', $list_id, true );
        // print_r($attached_items);
        $promoted_list_items =  get_field( 'promoted_list_items', $list_id, true );
       $items_per_page      = get_field( 'items_per_page', $list_id );
	    // $items_per_page      = 10;
        $voting_closed       = get_field( 'voting_closed', $list_id );
        $current_page        = get_query_var( "page" ) ? get_query_var( "page" ) : 1;
        $total_pages         = ceil( count( $attached_items )/$items_per_page );

        //$items_by_votes = '';
		
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
                                            $findrScore = 0;
                                                $reviews = get_overall_combined_rating($list_post->ID);
                                                // print_r($reviews);
                                                $i=0;
                                                $findrScore += $reviews['list']['featuresfunctionality']['score']*3;
                                                $findrScore += $reviews['list']['easeofuse']['score']*2;
                                                $findrScore += $reviews['list']['customersupport']['score']*2;
                                                $findrScore += $reviews['list']['valueformoney']['score']*3;
                                                $findrScore += (50/($kk-1));
                                                $findrScore = round($findrScore);
                                                $degree = $findrScore*3.6;
                                                if($findrScore>50){
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
                                                <span>'.$findrScore.'</span>
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
                    echo '<div style="display:table-cell; vertical-align:middle" ><span class="cp-item cp-item'.($ind+1).'" title="'.get_the_title($cmp) .'">'  .get_thumbnail_small($cmp,array(50,50)). ' </span></div>';
					
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
                    <h1 class="entry-title1"><span itemprop="name"><?php echo get_the_title( $post_id ); ?></span></h1>
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
            $findrScore = 0;
            $reviews = get_overall_combined_rating($post_id);
            //  print_r($reviews['list'][overallrating]);
            /*$i=0;
            foreach ($reviews['list'] as $kk2 => $rev){
                if($i==4)
                    $findrScore += $rev['score']*10;
                $i++;
                ?>
                
                <?php
            } */
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
            echo "<div>".round($findrScore)." findrscore</div>";
            
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
					



                    echo '<li><a href="'.generate_compare_link(array($post_id,$id)).'/" class="new-comparison-btn ls_referal_link" data-parameter="itemid" data-id="'.$post_id.'" data-secondary="'.$id.'"><span class="cp-item1"><img src="'.$imagecurr.'" class="img-responsive sss" alt="'.get_the_title($post_id).'" ><span class="cp-title">'.$titleCurr.'</span> </span><span class="cp-vs"><span class="cp-vs-inner">vs</span></span><span class="cp-item2"> <img src="'.$item_image.'" class="img-responsive sss" alt="'.get_the_title($id).'" ><span class="cp-title">'.get_the_title($id).'</span></span> </a></li>';
             

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
        $revHeader = "<div id='loader-animate' style='display: none;'><span>Loading...</span></div><div id='review-fix-header'><div class='review-fix-inner'>
<div class='image-sec'><img src=".$item_images." class='img-responsive' alt=".get_the_title()." ></div><div class='text-sec'><div class='head-left'><span class='head'>".get_the_title()."</span> </div>
<div class='head-right'><ul class='fix-head-link'>$lis</ul></div></div> <div class='button-sec '><a class='mes-lc-li-down aff-link head-links' href='$afflink' rel='nofollow' target='_blank'>$btntext</a></div>
</div></div>";
        $conlast = '<div id="overview">
                        <h3>Overview</h3>

                        <div>
                          <span itemprop="description"> '.$content.'</span>
                        </div>
                    </div>
 ';
 $faqs = '<div id="overview">
 <h3>Frequently Asked Questions</h3>

 <div>
   <span itemprop="description"> '.$this->get_faqs().'</span>
 </div>
</div>
';
        $affiliate_button_text = $this->affiliate_button_text;
        $source_url =$this->source_url;
        $credit = $this->credit_text;
        $details = $revHeader;
        $details .= '<div class="full-width-full"><div class="list-item-head-container">'.$this->get_single_list_item_header( $post_id ).'</div></div>';
        $details .= '<div class="list-item-col-container"><div class="list-item-left-col">';

        $support = get_field( 'support', $post_id );
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
        <h3 class="mygreen"> <i class="fa fa-check-circle mh10" aria-hidden="true"></i>'.count($r2b).' Reasons to buy</h3>
        '.$htmlr2b.'
        
        </div></div>
        <div class="col-md-6 cons">
        <div class="ml-5 pl-5 col-md-6-inner">
        <h3 class="myred"><i class="fa fa-times-circle mh10" aria-hidden="true"></i>'.count($rn2b).' Reasons not to buy</h3>
        '.$htmlrn2b.'
        </div>
        </div>
    </div>
';

$details.='<div class="list-main-content left-content-box">';
$details.=$faqs;
$details.='</div>';

        $details.='<div class="list-main-content left-content-box">';
        $details.=$conlast;
        $details.='</div>';

        $details.='<div class="list-main-content left-content-box">';
        $details.=$proscons;
        $details.='</div>';
       

        if(!empty($this->features) && is_array($this->features)){
            $details .='<div class="feature-list left-content-box"><div class="feature-list-title left-title"><h3 id="features">Key Features</h3></div> ';

            $details .='<ul class="field-key-list">';
            foreach( $this->features as $value )
            {
                if(!empty($value)) {
                    $details .='<li>'. $value.'</li>';
                }
            }
            $details .='</ul>';

            $details .='</div>';

        }
//-------------------------------------------------------------------------------

	
//        if(!empty($video_list_new)){ 
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
           

            $details .= '<div class="video-item left-content-box"><h3 id="videos"><div class="videos-title left-title"><span>Videos</span></div></h3>
            <div class="embed-container"> <div class="vid-main-wrapper clearfix">';
            $details .='<div class="vid-container">';	
            $details .= '<iframe src="https://www.youtube.com/embed/'.$firstvid.'" id="vid_frame"  
                allowfullscreen
                webkitallowfullscreen
                mozallowfullscreen
                style="position: absolute; top: 0px; right: 0px; bottom: 0px; left: 0px; width: 100%; height: 100%;"></iframe>';
            $details .= '</div> ';
            $details .= '<div class= "vid-list-container"> <div class="vid-list">';	
            
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
                        $details .= '<div class="vid-item" onClick="document.getElementById(\'vid_frame\').src=\'http://youtube.com/embed/'.$video_list_new[$i].'?autoplay=1&rel=0&showinfo=0&autohide=1\'">
                        
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
            
    
            $details .= ' </div></div>';
            $details .= '<div class="arrows">
                <div class="arrow-left">
                    <i class="fa fa-chevron-left fa-lg"></i>
                </div>
                <div class="arrow-right">
                    <i class="fa fa-chevron-right fa-lg"></i>
                </div>
            </div>';
            $details .= '</div></div></div>';
        }
        else{
            $details .= '<span id="novid">Author has not provided any video for this product. </span>';
        }

     ?>
				
		<?php

//--------------------------------------------------video_list end-----------------------------

        $details .='<div class="pricing-item-new left-content-box"><h3 id="pricing"><div class="pricing-title left-title"><span>Pricing</span></div></h3> ';
        $details .= "<ul class='pricing-details'>";
		
	
	
        if(!empty($this->price_starting_from)){

			$new_plan = $this->plan; 


            $details .="<li itemprop='offers' itemscope itemtype='http://schema.org/Offer'><label>Starting From:</label><span itemprop='priceCurrency' content='USD'>$</span><span itemprop='price'>".preg_replace("/[^0-9.]/", "", $this->price_starting_from)."</span><span itemprop='price_plan'>&nbsp;/&nbsp;" .$new_plan."</span></li>";
			
        }
        $details .="<li><label>Pricing Model:</label>".str_replace( '_', ' ', implode( ', ', array_map( 'ucfirst', $this->pricing_model ) ) )."</li>";
        if(!empty($this->free_trial)){
            $card = 'No Credit Card required';
            if($this->free_trial_card){
                $card = 'Credit Card required';
            }
            $details .="<li><label>Free Trial:</label>Available ($card)</li>";
        }
        if(!empty($this->additional_price_info)){
            $details .="<li>$this->additional_price_info</li>";
        }

        $details.='</ul></div>';
        
							
		$details .= '<div class="charts_graph doughnut">
                           
							<div id="canvas-holder" style="width: 33%; float: left;" >
                                 <canvas id="chart-area" width="220"></canvas>
                            </div>
                            
                           
                           <div id="canvas-holder" style="width: 33%; float: left;">
                             <canvas id="chart-area1" width="220"></canvas>
                           </div>
                           
                           
                           <div id="canvas-holder" style="width: 33%;  float: left;">
                             <canvas id="chart-area2" width="220"></canvas>
                           </div>
                           </div>
						   <div class="pricegraph_item" style="padding-top: 50px;">
                           <span class ="bartittle" >low </span> <span class ="bartittle"> Mid </span> <span class ="bartittle"> High  </span>
                           <canvas id="chart3" width="800" height="300" style="padding:20px;  box-shadow:2px 2px 2px rgba(0,0,0,0.2);"></canvas>
                           
                                  </div>
						   <div class="graph_text">
                            <div class="textgraph" style="text-align: left;">
After analyzing '.$total.'similar solutions the data above show that <a href="'.get_the_permalink($min_id).'"> '.$namemin.'</a> offers the lowesest starting price while <a href="'.get_the_permalink( $max_id).'"> '.$namemax.'</a> offers the highest entry price.That being said is also worthtaking a closer look at whats on offer because sometimes you may get way more value for a solution with a higher entry price and vise versa
                                  </div>
                                    </div>
						   ';
		
        if(!empty($gallery) && is_array($gallery)){

            $details .='<div class="gallery-item left-content-box"><div class="gallery-title left-title"><h3 id="screenshots">Screenshots</h3></div> ';
            $thumbs = '';$slides = '';
            foreach ($gallery as $img){
//echo '<pre>';
////print_r($img);
//echo '</pre>';
                $thumbs .= '<li> <img src="'.(isset($img['sizes']['thumbnail'])?$img['sizes']['thumbnail']:$img['url']).'" /></li>';
                $slides .= '<li><a href="'.$img['url'].'" data-fancybox="flex_images"><img src="'.$img['url'].'" /></a> </li>';
            }
            $details .= '<div id="slider" class="flexslider">
  <ul class="slides">'.$slides.'</ul></div>
  <div id="carousel" class="flexslider">
  <ul class="slides">'.$thumbs.'</ul></div>';
            $details.='</div>';
        }
        $details.='</div>';
        $details .='<div class="list-item-right-col">';
        $details .= $this->get_single_sidebar( $post_id );
        $details.='</div>';
        $details.='</div>';

        $content=$details;
        /*  if ( ! empty( $affiliate_url ) ) {
              $content.='<div style="display: block;text-align:center;"><a class="mes-lc-li-down zf-buy-button rev1" href="'.$affiliate_url.'" rel="nofollow" target="_blank"><button>'.$affiliate_button_text.'</button></a></div>';
          }
          if ( ! empty( $source_url ) ) {
              $content.='<div style="display: block;text-align:center;margin-top:20px;"><a class="zf-buy-button rev2" href="'.$source_url.'" rel="nofollow" target="_blank"><button>'.$credit.'</button></a></div>';
          }*/
        $content.='<div style="clear:both"></div>';
        $content.= $altername;

        $content.='<div id="rating"></div>';
  

        return $content;
		
	
		
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
