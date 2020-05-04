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
	 background: #eee;
    padding: 1em;
    width: 50%;
    margin: 0 auto;
    margin-top: 230px;
    box-shadow: 0 3px 8px 0px #888888;
}	
	.deal_subscritipn {
		    height: 500px;
    overflow: hidden;
    display: block;
    width: 100%;
	background: url(https://area51.softwarefindr.com/wp-content/uploads/2019/11/imag.jpg);
		box-shadow: inset 0 0 0 1000px rgba(0,0,0,.5);
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
	 box-shadow: 0 2px 3px 0px #888888;

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
	.search_deal input[type="button"] {
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
	 margin-top: 1em;
    margin-bottom: 1em;
}
	.cat_deal {
    text-align: right;
}
	
	
	.cpn_list {
    float: left;
    width: auto;
    margin-right: 35px;
    color: #ccc;
    text-decoration: none !important;
}
	.list_deal {
    display: block;
    overflow: hidden;
    margin: 0 auto;
    text-align: center;
    height: 48px;
    border-bottom: solid #ededed;
	 padding-top: 1em;    
    margin-bottom: 2em;
}
	.deal_category {
    float: right;
}
	.sorted_list {
    overflow: hidden;
    display: block;
    width: 55%;
    margin: 0 auto;
	 height: 50px;
}
	.cpn_list a {
    color: #000;
}
	.active {
    border-bottom: #4a8ee3 solid;
    padding-bottom: 9px;
    color: #000 !important;
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
			  <input id="deal_search_submit" type="button"  value ="Enter">
		  </div>
					
        </form>
				
<?php		
$deal_search= $_GET['search']; 
			
?>
				
				
<!--			<input type ="text" value="search" >-->
				
				</p> 
				 
			
			
			
			
			
			
		
		</div>
	
	
	

	</div>
	
	</div>
		<div class="call_to_action main_deal">
			
			
			<div class="deal_couponall">
			
			
				<?php 
		
		$allDealPosts = get_post_meta(148162,'all_posts_with_coupon'); ?>
				<div class="list_deal">
				
					
	<?php //	var_dump($allDealPosts);
					
					
						
						$allDealPost =$allDealPosts[0];
					$categories_count = array();
					$categories_name = array();?>
				<div class="deal_category">
		<div class="cat_deal"><select id="cat_select">		
		<?php	echo "<option value=''>All </option>";
		
		foreach($allDealPost as $alllist){

					$taxonomy = wp_get_post_terms($alllist, 'list_categories',  array("fields" => "all"));
	
		file_put_contents("deal.txt","taxonomy\n".print_r($taxonomy ,true),FILE_APPEND);
		
foreach($taxonomy as $category)
				{
					
	$categories_count[$category->term_id] =$categories_count[$category->term_id]+1;
	$categories_name[$category->term_id]	= $category->name;
				}
										
					?>	
					<?php  $current_page = get_query_var( "page" ) ? get_query_var( "page" ) : 1;?>
				
			<?php
		
				?>

		
				
		<?php	}  
		
		foreach($categories_name as $key=>$value){
			echo "<option value='$key'>$value </option>";
		}
		
		?>
			</select></div></div>
					
				<?php	
					arsort($categories_count);	
//					print_r($categories_count);
					
					
				
	
					$i=0;
	echo '<div class="sorted_list">';
					


 
					foreach($categories_count as $key=>$count){ 
				
					$cat_id = $key;				
						$term = get_term( $cat_id);						
					$cat_link =get_category_link( $cat_id );
if($i<4){
		
		echo "<div class='cpn_list'><a data-id='$cat_id' data-url= '$cat_link' data-name= '$term->name' class='all_cat_name' data-page='1'  data-search = '$deal_search' data-post ='$var'>".$term->name."</a></div>";
			
		
		?>		
				
<?php	}
						$i++;	
					
					
					}
					echo '</div>';

			
		
				
	?>
		</div>		
				
				
				
				<div class="coupondd"></div>
					
				<div class="coupon_list">
				<?php
//						$couponlist  =	populate_coupons_func();
//		echo "coupon_list";
//		print_r($couponlist);
	
			
			?>
				</div>
			
			
			
			
		
		</div>
		
	

	<div class="view_coupon"><div class="all_coupn_btn" id ="view_coupn_btn" data-startpage="1"  data-uri="<?php echo $_SERVER['REQUEST_URI']; ?>">  View More Coupon</div> 
	<div id ="no_more_coupon" style="display:none;" >  No More Coupon</div>
	</div>

	</div>
	
	
	
	
	
	
	
		<div class="deal_subscritipn">		<div class="deal_form_box"><?php echo $deal_subsciption_form;?></div> </div>
</div>


<!-------------------- load more coupon-------------------------------------------------->


                      
<!--
  <div id="ajax-result"></div>
                        
                        <div class="ajax-ele" style="text-align: center;"> 
                          <button id="morecoupon" data-startpage="<?php echo $current_page+1 ?>"  data-uri="<?php echo $_SERVER['REQUEST_URI']; ?>"  class="getbtn" style="border:none"><?php echo $current_page+1; ?> Load More</button>  
                         
                      </div>
-->




<!-------------------- load more coupon -------------------------------------------------->

<script>

	//----------------------------------------more coupon---------------------------------------------------

function ajax_call_coupons(current_page,search_term,termid){
	

var data = {
'action': 'populate_coupons',
'dataType': 'json',
//'category_name': cat_name, 
'current_page_no': current_page,
'search_item' : search_term,
'term_id' : termid,
//'link_url' : url,
	
};
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
jQuery.ajax({
type: "POST",
url:ajaxurl,
data: data,
dataType: "json",
success: function(data) {
//	  jQuery(".coupon_list")[0].html('hi');
//jQuery(".htmlbadgelink").html(data.link);
	console.log("check");
	console.log(data.filtered_coupon); 
	var nmp = data.no_more_posts;
	if( nmp == 1){
		jQuery("#view_coupn_btn").hide();
		jQuery("#no_more_coupon").show();
	}
	var list = data.filtered_coupon;
	
	var coupon_data = '';
	  jQuery.each(list,function(key,val){
       	coupon_data += '<div class="deal_coupon">'+
					'<div class="deal_image">'+val.image+
						
					'</div>'+
				'<div class="desc_box1">'+
					'<div class="deal_title">'+
					'<h3>'+val.title+'</h3>'+
					
					'</div>'+
				'<div class="deal_desc">'+
					'<p>'+val.description+'</p>'+
					
					'</div>'+
					
					'</div>'+
				
				 
			
			
				
			'	<div class="btn">'+
				'	<div class="deal_title-btn">'+
				'<p>'+val.post_title+'</p>'+
		' <p class="rating_list">'+val.rating+				
             '</p>'+
		'</div>'+
					
					'<div class="deal_btntn">'+
							'<p class="deal_btn"><a href="'+val.post_link+'" >View Deal</a></p>'+
					
					'</div>'+
				
				'</div>'+
				
				
				
			'</div>	';
		
		  
        });   
jQuery(".coupon_list").html(coupon_data );
}
});
	
}
	//---------------------------------------------more coupon-----------------------------------
	
	
	
	//----------------------category-----------------------------
	
jQuery(document).ready(function () {
	
	
	ajax_call_coupons();
	
	jQuery(".all_cat_name").click(function(){
		jQuery('.all_cat_name').removeClass('active');
		
	
		var current_page = jQuery(this).attr("data-page");
		var search_term = jQuery(this).attr("data-search");
		var termid = jQuery(this).attr("data-id");
		current_page =1;
	ajax_call_coupons(current_page,search_term,termid);
	jQuery("#deal_search_submit").attr("data-id",termid);
	jQuery("#view_coupn_btn").attr("data-id",termid);
	jQuery("#view_coupn_btn").attr("data-startpage",'1');

 jQuery(this).addClass("active");
	});
	jQuery("#deal_search_submit").click(function(){
		
		current_page =1;
	
		var current_page = jQuery(this).attr("data-page");
		var search_term = jQuery(this).attr("data-search");
		var termid = jQuery(this).attr("data-id");
		console.log("cp "+current_page+" st "+search_term+" ti "+termid);
	ajax_call_coupons(current_page,search_term,termid);
	jQuery(".all_cat_name").each(function() {
		// console.log("all cat name");
		// console.log(jQuery(this));
		var x=jQuery(this);
    x.attr("data-search",search_term);
});
	jQuery("#view_coupn_btn").attr("data-search",search_term);
	jQuery("#view_coupn_btn").attr("data-startpage",'1');
	
	jQuery("#cat_select").attr("data-search",search_term);
 
	});
	jQuery("#view_coupn_btn").click(function(){
		
		// current_page =1;
	
		var current_page = parseInt(jQuery(this).attr("data-startpage"))+1;
		var search_term = jQuery(this).attr("data-search");
		var termid = jQuery(this).attr("data-id");
		
		console.log("cp "+current_page+" st "+search_term+" ti "+termid);
	ajax_call_coupons(current_page,search_term,termid);
	jQuery(".all_cat_name").each(function() {
		// console.log("all cat name");
		// console.log(jQuery(this));
		var x=jQuery(this);
    x.attr("data-search",search_term);
});
	jQuery("#view_coupn_btn").attr("data-search",search_term);
	jQuery("#view_coupn_btn").attr("data-startpage",current_page);
	

 
	});
	jQuery("#deal_search").keyup(function() {
		console.log(jQuery("#deal_search_submit"));
		jQuery("#deal_search_submit").attr("data-search",jQuery(this).val());
	});

	jQuery('#cat_select').on('change', function() {
		jQuery('.all_cat_name').removeClass('active');
		
	
		// var current_page = jQuery(this).attr("data-page");
		var search_term = jQuery(this).attr("data-search");
		var termid = this.value;
		console.log("term id cat_select"+termid);
		current_page =1;
	ajax_call_coupons(current_page,search_term,termid);
	jQuery("#deal_search_submit").attr("data-id",termid);
	jQuery("#view_coupn_btn").attr("data-id",termid);
	jQuery("#view_coupn_btn").attr("data-startpage",'1');

 jQuery(this).addClass("active");
	});
});
	
	
</script>




<?php
get_footer();
?>







