<?php
get_header();

?>

<style>
	
/*	category */
	
	.category {
    width: 20%;
    float: left;
    text-align: center;
}
/*	category */	
	
.deal_form_box {
    width: 50%;
    margin: 0 auto;
}	
	.deal_subscritipn {
    overflow: hidden;
    display: block;
    width: 100%;
}
	.desc_box1 {
    width: 75%;
    float: right;
    text-align: left;
}
	.btn {
    overflow: hidden;
    display: block;
    width: 100%;
}
	.deal_title-btn {
    float: left;
}
	.deal_btn {
    background: #00395d;
    padding: 2px 0px;
    color: #fff;
    border-radius: 4px;
    width: 20%;
    float: right;
}
	.deal_btn a {
    color: white;
    position: relative;
        font-size: 13px;
}
	.deal_coupon {
        width: 49%;
    float: left;
    border: 1px solid rgba(0,0,0,0.12);
    margin-right: 1%;
    padding: 1em;
    margin-bottom: 1%;

}
	.deal_image {
    width: 20%;
    float: left;
}

	body.page-template-default.page.page-id-148162 .rwp-review-wrap {
    display: none;
}
.main_deal {
  width: 75%;
   margin: 0 auto;
  text-align: center;
	    overflow: hidden;
    display: block;
}
	.deal_search {
    width: 30%;
    margin: 0 auto;
    margin-bottom: 4em;
    margin-top: 2em;
}
	input#deal_search {
    float: left;
    width: 60%;
    padding: 6px 0px;
}
	.search_deal input[type="submit"] {
    width: 39%;
    background: #00395d;
    padding: 6px 0px;
    color: #fff;
    border-radius: 4px;
    width: 24%;
    /* float: right; */
}
	.all_coupn_btn {
    background: #00395d;
    padding: 4px 0px;
    color: #fff;
    border-radius: 4px;
    width: 20%;
    margin: 0 auto;
    margin-bottom: 2%;
    margin-top: 2%;
}
	.deal_couponall {
    overflow: hidden;
    display: block;
}
	.cat_deal {
    text-align: right;
}
	
	
	.cpn_list {
    float: left;
    width: 13%;
    margin-right: 35px;
    color: #ccc;
    text-decoration: none !important;
}
	.list_deal {
    display: block;
    overflow: hidden;
    margin: 0 auto;
    text-align: center;
    height: 35px;
    border-bottom: solid #ededed;
}
</style>
<div class="content-area mv-alternative" id="primary">
	<?php 
	$post_12 = get_post(148162); 

	$src = wp_get_attachment_image_src( get_post_thumbnail_id($post_12),'full');
$url = $src[0];		
						
						
						?>
	
	<?php 
	$settings = get_option( 'mv_list_items_settings' );
	$deal_subsciption_form = $settings['deal_subscription_form'];
	
	$deal_form = do_shortcode($deal_subsciption_form);
	
	?>
	
	
	<div class=" main_Container deal_page" >	
	
			 <div class="deal_box header-wrap" style="background: url('<?php echo $url; ?>') no-repeat;   background-attachment: fixed;
  			background-size: cover;">				
		<div class = "main_deal row">
			 	<div class = deal_title>
				 
				 <h2><?php the_title();?></h2>
				 </div>
		<div class = deal_desc>
			
				<p><?php the_content();?></p> 
			
			
		
			
			
				 </div>
			
			
			
			
			
			
			
			
			
			
			<div class = deal_search>
		<form autocomplete="off" class="search_deal" >
		  <div class="search_coupon" >
			  <input id="deal_search" type="text" name="search" placeholder="Search Coupon">
			  <input type="submit"  value ="Enter">
		  </div>
					
        </form>
				
<?php
				
				
$deal_search= $_GET['search']; 
echo $deal_search;
				
				
?>
				
				
<!--			<input type ="text" value="search" >-->
				
				</p> 
				 
			
			
			
			
			
			
			 </div>
			 </div>
		
		</div>
	
	<div class="deal_category">
		<div class="cat_deal">
	
		
<?php		// post category

 $custom_terms = get_terms('list_categories'); // I don't know what it does 20 sep 2019
	?> <select value = "cpn_deal">
			
		 <?php
foreach($custom_terms as $custom_term) {
    wp_reset_query();
    $args = array('post_type' => 'lists',
        'tax_query' => array(
            array(
                'taxonomy' => 'list_categories',
                'field' => 'slug',
                'terms' => $custom_term->slug,
            ),
        ),
     );

     $loop = new WP_Query($args);?>

	<?php
     if($loop->have_posts()) {
		 
		 ?>
		 
		
			 
 <option class="box">
		
				<?php echo  $custom_term->name;
			
			?> 
		 </option>

		
		<?php
//        echo '<h2>'.$custom_term->name.'</h2>';

        while($loop->have_posts()) : $loop->the_post();
//            echo '<a href="'.get_permalink().'">'.get_the_title().'</a><br>';
        endwhile;
     }?>
				 
<?php }
 ?>
				 
			 
			</select>

	</div>
	
	</div>
		<div class="call_to_action main_deal">
			
			
			<div class="deal_couponall">
			
			
				<?php 
		
				


				
				
				
				
		$allDealPosts = get_post_meta(148162,'all_posts_with_coupon'); ?>
				<div class="list_deal">
				
	<?php	foreach($allDealPosts as $allDealPost){
			foreach($allDealPost as $alllist){
$taxonomies=get_post_taxonomies($alllist); 
				
$taxonomy = wp_get_post_terms($alllist, 'list_categories',  array("fields" => "all"));?>	
			<?php
//				print_r($taxonomy);
				
	foreach($taxonomy as $category){?>					
				<?php
		$cat_id = $category->term_id;
	$cpnlink = get_category_link($cat_id );
				
		
		echo "<div class='cpn_list'><a href = '$cpnlink' >".$category->name."</a></div>";
		?>		
				
<?php	}	
		}
		}		
				
	?>
		</div>		
				<?php
foreach($allDealPosts as $allDealid)
{
//	  var_dump("second_loop".$allDealid); 
	
	
	foreach($allDealid as $post_id){
				 

	
		$couponlist  = get_post_meta($post_id, 'coupons_list', true);?>
						
	<?php
		
	
		
		foreach($couponlist as $couponlist_new)
				{
				
			$deal_form = array($couponlist_new['votes']);
//	rsort($deal_form);
//			print_r($deal_form);
			
			
			
				if($deal_search == ''  || strpos(strtolower(get_the_title($post_id)),strtolower($deal_search))!== false )
				{?>
				
			<div class="deal_coupon">
					<div class="deal_image">
						    <?php
						
						//echo $img = get_thumbnail_small($post->ID,array(150,150));
					 $featured_img_url = get_the_post_thumbnail_url($post_id,array(150,150)); 
					 $alt = basename($featured_img_url);
					 $pos = strrpos($alt, ".");
					 $alt = substr($alt,0,$pos);
					 ?>
					 <img src="<?php echo $featured_img_url?>" class="img-responsive coupon_img" alt="<?php echo  $alt?>" >
                     
						
					</div>
				<div class="desc_box1">
					<div class="deal_title">
					<h3><?php echo $coupon_vote_list = $couponlist_new[title]; ?></h3>
					
					</div>
				<div class="deal_desc">
					<p><?php echo $coupon_vote_list = $couponlist_new[description]; ?></p>
					
					</div>
					
					</div>
				
				 
			
			
				
				<div class="btn">
					<div class="deal_title-btn">
				<p><?php	echo get_the_title($post_id); ?></p>
						<?php   $ratingItem = get_overall_combined_rating($post_id);
			
			
						
							foreach($ratingItem as $rating){
								$score = $rating['overallrating']['score'];
//							print_r($score);
								  
								
//								echo '<div class="rwp-rating-stars-count">('. $votes .' '. $count_label .')</div>';
//								echo "<pre>";
//								var_dump($rating);
//								
//								echo "</pre>";
							}
			
			
			
			?>
                       
                  
				</div>
					
					<div class="deal_btntn">
							<p class="deal_btn"><a href="#" >Get Code</a></p>
					
					</div>
				
				</div>
				
				
				
			</div>	
			
					
<?php
		}
//			else{
//					
//					echo "No Coupon to show";
//				}
//			
			
			
				}
		?>
				
			
	<?php 
		
	}
	
	
}
		
			
			?>
			
			
			
			
			
		
		</div>
		
	

	<div class="view_coupon">		<div class="all_coupn_btn"> View More Coupon</div> </div>

	</div>
	
	
	
	
	
	
	
		<div class="deal_subscritipn">		<div class="deal_form_box"><?php echo $deal_subsciption_form;?></div> </div>
</div>


<!-------------------- load more coupon-------------------------------------------------->
<?php  $current_page        = get_query_var( "page" ) ? get_query_var( "page" ) : 1;


?>
 <div id="ajax-response"></div>
                        <div id="nopostmessage"></div>
                        <div class="ajax-ele" style="text-align: center;"> 
                          <button id="loadmore" data-startpage="<?php echo $current_page+1 ?>"  data-uri="<?php echo $_SERVER['REQUEST_URI']; ?>"  class="getbtn" style="border:none"><?php echo $current_page+1; ?> Load More</button>  
                          <div class="loader-container" style="text-align: center;padding: 40px;">  
                            <img id="ajax-loader"  data-uri="<?php echo $_SERVER['REQUEST_URI']; ?>" src="<?php echo $site_url; ?>/wp-content/uploads/2019/04/ajax-loader-1.gif" style="display:none;"/>
                          </div>
                      </div>




<!-------------------- load more coupon -------------------------------------------------->








<?php
get_footer();
?>







