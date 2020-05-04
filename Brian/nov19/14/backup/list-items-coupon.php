<?php  acf_form_head(); ?><?php
get_header();

?>

<style>
	button#show {
    background-color: #00395d;
    margin: 0.5em 0px;
    color: rgb(255, 255, 255);
    display: block;
    padding: 1.125em 1.25em;
    border-radius: 5px;
    font-size: 1em;
    font-family: "Gibson SemiBold", Helvetica, Arial, "Sans Serif";
    text-align: center;
    text-transform: uppercase;
    vertical-align: middle;
    cursor: pointer;
}
	.coupon_subheading h1 {
    /* display: block; */
    /* text-align: center; */
    font-size: 2.25em;
    font-weight: 600;
}
	.popupbtn {

    margin: auto;
    width: 70%;
    padding: 20px;
        padding-bottom: 20px;
    padding-bottom: 20px;
    box-shadow: 0 4px 8px 0 

rgba(0, 0, 0, 0.2), 0 6px 20px 0

    rgba(0, 0, 0, 0.19);
    padding-bottom: 1em;
    text-align: center;

}
	p#breadcrumbs {
    width: 80%;
    margin: 0 auto;
}
	.copon {
    margin: auto;
    width: 70%;
    padding: 20px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    padding-bottom: 1em;
	text-align: center;
    
}
	p#breadcrumbs span a {
    display: none;
}
	.deal_desc p {
    overflow: hidden;
    display: block;
}
	input.subcode {
    width: 60%;
    float: left;
    border: 1px solid #dddd;
    padding: 1em;
}
/*	form start*/
	.center {
    margin: auto;
    width: 100%;
    padding: 20px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
	padding-bottom: 1em;
}

.hideform {
    display: none;
}
	
/*	formend*/
	
	th {
    
		padding: 0.5em;
}
	.data th {
    font-size: 20px;
    font-weight: 500 !important;
		padding: 0.5em;
}
	.mv-single-lists.coupon_banner {
    position: relative;
    /* bottom: 203px; */
   
}

.review-bck-btn {
    margin: 0px;
}
	.item {
    overflow: visible;
}
	img.img-responsive.coupon_img {
    position: relative;
    bottom: -23px;
}
	p.subsc_coupon_page {
    padding: 0.5em;
    border: 1px solid #eee;
    overflow: hidden;
}
	.comparision_coupon_list {
    overflow: hidden;
    display: block;
}
	.comparision_coupon_list li {
    float: left;
    width: 20%;
    margin-right: 25px;
}
	table.item_table {
    width: 80%;
    margin: 0 auto;
    border: 1px solid #ddd;
}
.thubm_up {
    font-size: 16px;
}

.coupon_subheading {
    text-align: center;
	padding: 2em;
}
.coupon_subheading h1 {
    line-height: 2em;
}
	.alternative-page-desc p {
    line-height: 1.5em;
}
.coupon_reviewbtn {
    float: none;
}
	.get_code_btn {
    overflow: hidden;
    display: block;
    padding: 1.5em;
		
    margin: 0 auto;
}
	p.subsc {
    float: left;
    width: 55%;
}
	
</style>
<?php

global $wp_query;
$item_name = get_query_var( 'item_name' );
$settings = get_option( 'mv_list_items_settings' );

//$title = $settings['coupon_page_title'];


$coupon_subsciption = $settings['coupon_page_subscription_form'];
$coupon_subsciption_form = $settings['coupon_subscription_form'];


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

// $title = get_field( "coupon_title", $post->ID  );
// $coupon_desc  = get_field( "copon_description", $post->ID  );

$subscription_form = do_shortcode( $coupon_subsciption);
$cpn_form = do_shortcode($coupon_subsciption_form);

// $coupon_full_desc = get_field( "copon_short_description", $post->ID  );



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

$description = do_shortcode( $coupon_desc );

$item_title = $post->post_title;
$tableArr = array();

?>
<!--
<?php
//if ( function_exists('yoast_breadcrumb') ) {
//  yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
//  echo "exists";
//}
//echo "dne";
?>
-->

<div id='loader-animate' style='display: none;'><span>Loading...</span></div>
<div class="content-area mv-alternative" id="primary">
	
	<?php $post_12 = get_post(148112); 

						
				$src = wp_get_attachment_image_src( get_post_thumbnail_id($post_12),'full');
$url = $src[0];		
						
						
						?>
<!--
	<div class="">
	<img src="<?php //echo $url;?>" width="100%">
	</div>
-->
	<div class="deal_box header-wrap" >	
						
						
    <div class="alternate-list-items_coupon alternate_upper_header coupon_header" style="
    height: 320px;
    width: 100%;
    overflow: hidden;">
		<img src="<?php echo $url; ?>" style="filter:blur(15px);height: inherit;width: inherit;">
		
    </div>
    
    <div class="mv-single-lists coupon_banner" style="    bottom: 320px;
    height: 320px;">
            <div class="alternate_header row item" style="
  			background: url('<?php echo $url; ?>') no-repeat;
  			background-attachment: fixed;
  			background-size: cover;
  			height: 320px;
  			">
                <div class="item_data col-sm-12 col-xs-12">
                    <?php 
                        $gapUnix = time() - get_post_meta($post->ID,'last_seen',true);
                        $gap = $gapUnix/60;
                        if($gap > 60){
                            $gap/=60;
                            $gap = floor($gap);
                            $gap .= " hours ";
                        }
                        else{
                            $gap = floor($gap);
                            $gap .= " minutes ";
                        }
                        update_post_meta($post->ID,'last_seen',time());

                    
                    ?>
                    <span style="
    background: white;
    padding: 5px;
    bottom: 16px;
    position: absolute;
    left: 1px;
">Last used <?php echo $gap; ?> ago</span>
                    <div class="image_data_inner coupon_featured_img">
						
                        <?php
						
						//echo $img = get_thumbnail_small($post->ID,array(150,150));
					 $featured_img_url = get_the_post_thumbnail_url($post->ID,array(150,150)); 
					 $alt = basename($featured_img_url);
					 $pos = strrpos($alt, ".");
					 $alt = substr($alt,0,$pos);
					 ?>
					 <img src="<?php echo $featured_img_url?>" class="img-responsive coupon_img" alt="<?php echo  $alt?>" >
                      <?php  echo get_item_batch($post->ID);

                        ?>

                    </div>
<!--
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
-->
                </div>
               
            </div>
			<?php 
				 $couponlist  = get_post_meta($post->ID, 'coupons_list', true);
//
			echo "<pre>";
			var_dump($couponlist);
			echo "</pre>";
			?>
			 <div class="item_heading col-sm-12">
                    <div class="item_heading_inner ttt coupon_subheading" ><?php 
						
						
				foreach($couponlist as $couponlist_new)
				{
						$coupon_vote_list[] = $couponlist_new[votes];
					$coupon_id_list[] = $couponlist_new[id];
					

				}
						$copnid = $coupon_id_list;
						
					$value_max = max($coupon_vote_list); 
						foreach($couponlist as $key => $coupon_item)
						{
							if($coupon_item['votes'] == $value_max){								
								$coupon_key = $key;							
								
							}
					}	
				
						
						echo "<h1>".$couponlist[$coupon_key]['title']."</h1>";	
						$coupon_exp_date = $couponlist[$coupon_key]['showDate'];
						
					
						
						?>
<!--	pop up form start-->
						
<div class="center hideform">
<button id="close" style="float: right;">X</button>
<div class="deal_desc">
	<span id ="coupon_date" ></span>
			<h2><?php echo $couponlist[$coupon_key]['description']?> </h2>

           <?php if($couponlist[$coupon_key]['type'] == 'coupon') {
                ?>

<p>Copy and paste this code at  <a href="<?php echo $couponlist[$coupon_key]['link']?>"> <?php echo  $post->post_title; ?></a> </p>		
	
    <p style="
        display: flex;
        justify-content: center;
    "><input type="text" readonly id ="myInput_copy" value= "<?php 	if(!empty( $couponlist[$coupon_key]['code'])){
                        echo $couponlist[$coupon_key]['code'];
                    }
                    else{
                        echo $couponlist[$coupon_key]['title']; 
                    }?>" placeholder="Couponcode" style="
                    width: 40%;
                    display: inline-block;
                    border: 1px solid gray;
                    padding: 7px;
                    font-size: 25px;
                    font-weight: 700;
                    text-align: center;
                    display: inline-flex;
                "><button onclick="myFunctioncopy()" class="copytxt" style="
        background: #0898d5;
        border: none;
        color: white;
        border-radius: 0 4px 4px 0;
    ">Copy</button></p>	
                <?php
           }
           else{
               ?>

               <p><h2>coupon applied!</h2></p>
               <?php
           } 
           
           
           $class_prefix="cp_"; ?>
			
	<p>Did this help you save money?   <ul  class="cp-item-vote thumbs" data-cp-coup-id="<?php echo $couponlist[$coupon_key][id]; ?>" data-cp-post-id="<?php echo $post->ID ?>">
                                                        <li style="
    display: inline-block;
">
                                                            <a href="javascript:;" class="cp-vote_btn <?php echo $class_prefix.'vote_up'; ?>" data-cp-coup-id="<?php echo $couponlist[$coupon_key][id]; ?>" data-cp-post-id="<?php echo $post->ID ?>">
                                                                <i class="fa fa-thumbs-o-up"></i> yes
                                                            </a>
                                                        </li>
                                                        <li style="
    display: none;
" class="spinner-list"><span class="cp-vote_count" data-cp-post-id="<?php echo $post->ID ?>" data-cp-votes="<?php echo number_format((float)$couponlist[$coupon_key][votes],1); ?>">
															<i class="cp-icon cp-spinner-pulse"></i>
															<span class="cp-vote_number">
																<?php echo number_format((float)$couponlist[$coupon_key][votes],1); ?>														</span>
														</span></li>
                                                        <li style="
    display: inline-block;
">
                                                            <a href="javascript:;" class="cp-vote_btn <?php echo $class_prefix.'vote_down'; ?>"  data-cp-coup-id="<?php echo $couponlist[$coupon_key][id]; ?>" data-cp-post-id="<?php echo $post->ID ?>">
                                                                <i class="fa fa-thumbs-o-down"></i> no
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    
                                                    </p>
	<p>Subscribe to the Best of RetailMeNot to never miss a deal from 100s of retailers,
including WP Engine.
	
	
	</p>
	<p><?php echo $cpn_form; ?><span class="list_box_coupon">Subscribe</span></p>
	<p><center>Privacy Policy</center></p>
		</div>
        
</div>
<!--	Popup form end-->
						
				 
				 
						<div class="alternative-page-desc">
							
							 <?php 
										if($coupon_exp_date == 'show')
										{ 
                                            $date = date_create($couponlist[$coupon_key]['expdate']);
											echo '<p><b> Hurry! This special offer ends on ' .date_format($date,"F jS"); 
										} 
								?> 
							</b></p>
							<p><?php echo $couponlist[$coupon_key]['description']?> </p>
                            <p>Shop online at <a href="<?php echo $couponlist[$coupon_key]['link'] ?>"><?php echo $couponlist[$coupon_key]['link'] ?></a></p>


                        </div>

                        <div class=" coupon_reviewbtn review-bck-btn">
							<div class="get_code_btn">
								   
								<button id= "show" data-url="<?php echo $couponlist[$coupon_key]['link'] ?>" >Get code & open site</button>
								
									  <?php
	 
	
//						var_dump($couponlist);
						
								
									
								?>
									
<!--
							<a  href="<?php do_shortcode();?>" class="btn ls_referal_link" data-parameter="alternative" data-id="#">Get code & open site</a>
							


-->

							</div>
							
							<a  href="" class="btn ls_referal_link thubm_up" data-parameter="alternative" data-id=""><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a>

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
                          <a class="mes-lc-li-down btn-affiliate zf-buy-button btn btn-primary" href="" rel="nofollow"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a>

                            <?php } 
                            }?>

                        </div> <div class="clr"></div>
                    </div>
                </div>
        </div>


    <div class="alternate-list-items alternate_upper_table">
        <div class="mv-single-lists">
            <?php
            ob_start();
          
                ?>
			
                <div class="alternate-list-items alternate_upper_<?php echo $key;?>">
                    <div class="wider-contnet">
                        <div class="section_head">
						
                            <h3> Top <?php	the_title(); ?> Coupons or Discount codes October 2019</h3>
							<!------------------------	table  pop up form start-------------------------------------->
<?php 
// print_r(get_post_types());
	foreach($couponlist as $key=> $coupon_item )
						{ $cpn_id = $coupon_item[id]; ?>
		
<div class="copon<?php echo $cpn_id; ?>  popupbtn" style="display: none;">
	<button class="closecpon" style="float: right;" data-id="<?php echo $cpn_id; ?>">X</button>
<div class="deal_desc">

	
			<h2><?php echo $coupon_item[title]; ?> </h2>
			<p>Copy and paste this code at  <a href="<?php echo $coupon_item['link']; ?>"> <?php echo  $post->post_title; ?></a> </p>		
	
<p><input type="text" class="subcode<?php echo $cpn_id; ?>" value= "<?php if(!empty( $coupon_item['code'])){
					echo $coupon_item['code'];
				}
				else{
					echo $coupon_item['title']; 
				}?>" placeholder="Couponcode">
	
	<button onclick="myFunction_text(<?php echo $cpn_id; ?>)" class="list_box_coupon" data-id="<?php echo $cpn_id; ?>">Copy text</button></p>
	
	<p>Did this help you save money? <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>  <i class="fa fa-thumbs-o-down" aria-hidden="true"></i></p>
	<p>Subscribe to the Best of RetailMeNot to never miss a deal from 100s of retailers,
including WP Engine.
	
	
	</p>
	<p><?php echo $cpn_form; ?><span class="list_box_coupon">Subscribe</span></p>
	<p><center>Privacy Policy</center></p>
	<?php   ?> </div> 
		
		</div>
		
		<?php } ?>
                           <table  class ="item_table">
							 <tr><th> Offer Description</th>  <th>Expire</th>   <th>Code</th>	</th>   <th> Redeem</th></tr>  
						<tr>
							   <?php
//							   var_dump($couponlist);
						
						foreach($couponlist as  $coupon_item)
						{
						$coupon_deal = $coupon_item[type];
//							echo "<tr class=data><th>".$coupon_item[title]."</th>";
							echo "<td>".$coupon_item[description]."</th>";
							echo "<td>".$coupon_item[expdate]."</th>";
							if ($coupon_deal === 'coupon'){
							echo "<td>".$coupon_item[code]."</th>";
							}else{
								echo "<td>Not Required</th>";
							}
						 $coupon_id = $coupon_item[id];
//							echo $coupon_id;
						?>
						
						<td>
							
								<button class= "all_pop" data-id="<?php echo $coupon_id; ?>" data-url="<?php echo $coupon_item['link'] ?>"  >Click Here</button>
<!--
						<form method="post"> <input type="submit"  value="Click Here" name ="submittext" id="abc">
							<input type="hidden" name="cnpid" value="<?php //echo $coupon_id;?>" >
							
							
							</form>
							
-->
						</td>
						
							</tr>
							
							
							
					<?php	}
						
						
						
							   
							   ?>
							    
							  
							
							</table>
                        </div>


		
<!--------------------------------------------	Popup form end------------------------------------------>
	

			
		
		
		
		
		
		

                        <div class="section_items">
                      
                        </div>
                    </div>
                </div>
                <?php
           
            $rating_contnet = ob_get_contents();
            ob_get_clean();
            ?>
			
				
            <div class="alternate-table">
				
                <p class="subsc "><strong><i class="fa fa-map-marker" aria-hidden="true" style="    font-size: 22px;
    color: #5478dc;"></i> Signup for more & similar deals </strong><?php echo $subscription_form; ?></p>	
			
			
				
				
            </div>
				 <div class="coupon_transistor ">
				<h3>About <?php	the_title(); ?>
			 </h3>
				<div class="rat">
                        <?php
                        $ratingItem = get_overall_combined_rating($id);
                        $votes = $ratingItem['count'];
                        $score = isset($ratingItem['list']['overallrating'])?$ratingItem['list']['overallrating']['score']:0;
                        $count_label = $votes==1?'vote':'votes';
				
                        echo $revClass->get_stars( $score, 20, 5 );
					
//                        echo '<div class="rwp-rating-stars-count">('. $votes .' '. $count_label .')</div>';

                     ?>
                    </div>
				 
                <p class="subsc_item"><?php		
					$my_postid = get_the_id();//This is page id or post id
					$content_post = get_post($my_postid);
					$content = $content_post->post_content;
					$content = apply_filters('the_content', $content);
					$content = str_replace(']]>', ']]&gt;', $content);
//					echo $content;
				 echo substr($content, 0, 300)."....";
					 
					$link = get_the_permalink();
					
				 ?></p> 
				 <p><a href="<?php echo $link; ?>">Read full review</a></p>
				 
			
    </div>
			
        </div>
        <div class="wider-contnet">
            <div class="rating_headings">
<!--
                <?php
                foreach ($ratingLabel as $key => $name){
                    echo "<div class='rat_head'><a href='#$key'>$name</a> </div>";
                }
                ?>

-->
				
				
            </div>
        </div>
    </div>
    <?php echo $rating_contnet;?>
    <div class="alternate-list-items alternate_upper_rank">
        <div class="mv-single-lists">
            <div class="section_head">
                <span class="rating_label">What user are saying:</span>
                </div>
			</div>
</div>
		<div class="alternate-list-items alternate_upper_">
		<div class="mv-single-lists">	
            <div class="rank_list">
               <span class="rating_label"><?php the_title(); ?> Customer also love these offers</span>  
            </div>
		</div>
</div>
		<div class="alternate-list-items alternate_upper_rank">
			<div class="wider-contnet">	
            <div class="all_alernative">
            
				<?php
				  
 
        $review_id= get_the_ID();
	
       
        $compObj = new Mv_List_Comparision();
        $lists = $compObj->most_compared($review_id,20,true);
				

//         echo '<pre>';
//         var_dump($lists);
        if ( !empty ( $lists ) ) {
            echo '<div class="alternate-carousel"> <span class="rating_label">Alternatives to '.get_the_title( $review_id ).'</span>';
            echo'<div class="see-more-btn" style=""><a href="'.get_the_permalink( $review_id ).'alternative/" class="ls_referal_link" data-parameter="itemid" data-id="'.$review_id.'">See More</a></div> <div class="clr"></div>';
           echo'<div class="full-width"><div class="flexslider carousel cr-alternate">
  <ul class="slides">';
            $ac = 0;
            foreach( $lists as $pid ) {
				
$images = get_the_post_thumbnail_url($pid,array(150,150));?>
				
              <?php   echo '<li><div class="cs-title single-line"><a href="'.get_the_permalink($pid).'"> '.get_the_title($pid).'</a></div> <div class="cs-image"><a href="'.get_the_permalink($pid).'"> <img src="'.$images.'" class="img-responsive sss" alt="'.get_the_title($pid).'" ></a></div> </li>';
               
                $ac++;
            }
            echo '</ul></div></div></div>';
        }



       
   
				
				
				?>
				
				
				
            </div> 
		
<div class="alternate-list-items alternate_upper_">
	<div class="wider-contnet">	
			<div class="all_alernative">
           
			      
            <h3 class="rating_label">Compare <?php echo get_the_title($post_id);?> to Similar Solutions</h3>
            <?php
			$post_id = get_the_id();
            $compObj = new Mv_List_Comparision();
            $returns = $compObj->most_compared($post_id);
		
            if(!empty($returns) && is_array($returns)){
				
                $titleCurr = get_the_title($post_id);
				
				
                //$imagecurr = get_thumbnail_small($post_id,array(100,100));
				$imagecurr = get_the_post_thumbnail_url($post_id,array(100,100));
			
                echo '<ul class="comparison-list comparision_coupon_list">';

                foreach ($returns as $id){
			$com_id = get_the_title($id);
				
					$item_image = get_the_post_thumbnail_url($id,array(100,100));
					
					
			
			?>
			
                    <?php
					



                    echo '<li><a href="'.generate_compare_link(array($post_id,$id)).'/" class="new-comparison-btn ls_referal_link" data-parameter="itemid" data-id="'.$post_id.'" data-secondary="'.$id.'"><span class="cp-item1"><span class="cp-title">'.$titleCurr.'</span> </span><span class="cp-vs"><span class="cp-vs-inner">vs</span></span><span class="cp-item2"> <span class="cp-title">'.get_the_title($id).'</span></span> </a></li>';
             

				}
                echo "</ul>";
            }
            ?>
            <!--<div class="new-comparison-btn-container"> <a href="javascript:;" class="new-comparison-btn" data-id="--><?php //echo $post_id?><!--" data-secondary="0">New Comparison</a></div>-->
        </div>
            
</div>
				</div>
			</div>
</div>
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

				 
					 
 jQuery("#show").click(function(){
jQuery('.center').show();
var date=new Date();
var id=date.getDate()+"/"+(date.getMonth()+1)+"/"+date.getFullYear();
//	alert(id);
jQuery(this).hide();
var url = jQuery(this).data("url");
window.open(url,'_blank');


});
		
jQuery('#close').on('click', function () {
    jQuery('.center').hide();
   jQuery('#show').show();
	
});	

					 
					 	 
jQuery(".all_pop").click(function(){
	var id = jQuery(this).data("id");
	 jQuery('.copon' +id).show();	
	  jQuery('.item_table').hide();
		
      var url = jQuery(this).data("url");
        window.open(url,'_blank');
	
});
					 
					 
jQuery('.closecpon').click(function() {	
	var id = jQuery(this).data("id");
   jQuery('.copon'+id).hide();
	jQuery('.item_table').show();
	
	
});				 
	 


					 
					 
					 
function myFunctioncopy() {
  /* Get the text field */
  var copyText = document.getElementById("myInput_copy");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /*For mobile devices*/

  /* Copy the text inside the text field */
  document.execCommand("copy");

  /* Alert the copied text */
//   alert("Copied the text: " + copyText.value);
}
					 

		 
					 
	function myFunction_text(id) {
				
  var copyText = document.getElementsByClassName("subcode"+id)[0];
  
       
		
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
  alert("Copied the text: " + copyText.value);
}				 
			 
				 
					 
//coupon ajax
		

							 
					 
	//coupon ajax
								 

					 

 
				 
					 
					 
					 
                 </script>
          <?php 
             ?>
            </div>
           	<p>
	Was this comparison helpful?
	</p>
	<?php if(function_exists('the_ratings')) { the_ratings(); } ?>
        </div><br>

    </div>
	
        </div>
    
</div>




<?php
get_footer();
?>