<?php
get_header();

?>
<style>

	body.page-template-default.page.page-id-148162 .rwp-review-wrap {
    display: none;
}
.main_deal {
    width: 60%;
    margin: 0 auto;
	    text-align: center;
}
	.deal_search {
    width: 30%;
    margin: 0 auto;
}

</style>
<div class="content-area mv-alternative" id="primary">
	<?php 
	$post_12 = get_post(148162); 

	$src = wp_get_attachment_image_src( get_post_thumbnail_id($post_12),'full');
$url = $src[0];		
						
						
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
			
			
		<?php 	
		$allDealPosts = get_post_meta(148162,'all_posts_with_coupon');
echo "all deal posts are ".print_r($allDealPosts);	
			
			
			?>
			
			
			
				 </div>
			
			
			
			
			
			
			
			
			
			
			<div class = deal_search>
				
				
				
			<input type ="text" value="search" ></p> 
				 
			
			
			
			
			
			
			 </div>
			 </div>
		
		</div>
		<div class="call_to_action main_deal">
			
			
			<div class="deal_category">Category </div>
			
			
			<div class="deal_category">Coupon list </div>
			
			
			
			
		<div class="deal_subscritipn">	<input type="text" value="Search" >Subscribe</div>
			
	
		
		
		
		</div>
		


	</div>
</div>
<?php
get_footer();
?>







