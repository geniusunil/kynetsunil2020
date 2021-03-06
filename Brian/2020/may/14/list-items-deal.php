<?php
get_header();
global $post;

    $post_slug = $post->post_name;
	
	$pageAlt = get_page_by_path( $post_slug );
    $post_id_deal = get_the_id( $pageAlt );
?>

<style>
	.deal_desc_box p {
    background: rgba(0,0,0,0.8);
}
	.deal_desc {
    
    overflow: hidden;
    height: 75px;
}
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
	background: url(<?php echo home_url(); ?>/wp-content/uploads/2019/11/imag.jpg);
		box-shadow: inset 0 0 0 1000px rgba(0,0,0,.5);
		background-size:100%;
}
	.desc_box1 {
    width: 75%;
    float: right;
    text-align: left;
	height: 163px;
    overflow: hidden;
}
	.btn {
    overflow: hidden;
    display: block;
    width: 96%;
    background-color: #f9f9f9;
    line-height: 30px;
    padding: 10px 2%;
}
	.deal_title-btn {
    float: left;
	display: flex;
	max-width:70%;
}

div.deal_title-btn p a {
    padding-right: 8px;
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
    height: 225px;
    float: left;
    border: 1px solid rgba(0,0,0,0.12);
    margin-right: 1%;
    margin-bottom: 1%;
    box-shadow: 0 2px 3px 0px #ccc;

}



	.deal_image {
    width: 20%;
    float: left;
	padding-left: 10px;
    padding-top: 10px;
    padding-bottom: 20px;
}

	body.page-template-default.page.page-id-<?php  echo $post_id_deal;?> .rwp-review-wrap {
    display: none;
}
.main_deal {
	width: 75%;
    max-width: 1110px;
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
		background: #ddd;
    float: left;
    width: 60%;
    padding: 6px 7px;
	border-radius:4px 0 0 4px;
}
	input#deal_search_submit {
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
    width: 60%;
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
div.main_deal.row {
    padding-top: 50px;
}

div.deal_title h2 {
    color: #e6e6e6;
}
@media only screen and (max-width: 1023px) {
	.deal_coupon {
    	width: 99%;
    height: 225px;
    float: left;
    border: 1px solid rgba(0,0,0,0.12);
    margin-right: 1%;
    margin-bottom: 1%;
    box-shadow: 0 2px 3px 0px #ccc;
  }

  /* .btn{
	  padding:10px 0;
	  
  } */

  .deal_btn a{
	font-size:11px;
  }

  .rwp-rating-stars{
	  display:none;
  }

  .deal_desc_box{
	  display:none;
  }
  .sorted_list{
	  display:none;
  }
  .deal_search{
	  width:66%;
  }
}
</style>
<div class="content-area mv-alternative" id="primary">
	<?php 
	
global $post;

    $post_slug = $post->post_name;
	
	$pageAlt = get_page_by_path( $post_slug );
    $post_id_deal = get_the_id( $pageAlt );

	$post_12 = get_post( $post_id_deal); 

	$src = wp_get_attachment_image_src( get_post_thumbnail_id($post_12),'full');
	$url = $src[0];		
						
						
						?>
	
	<?php 
	$settings = get_option( 'mv_list_items_settings' );
	$deal_subsciption_form = $settings['deal_subscription_form'];
	
	$deal_form = do_shortcode($deal_subsciption_form);
	
	?>
	
	
	<div class=" main_Container deal_page" >	
	
			 <div class="deal_box header-wrap" style="background: url('<?php echo $url; ?>') no-repeat;   
  			background-size: 100%;">				
		<div class = "main_deal row">
			 	<div class = deal_title>
				 
				 <h2><?php the_title();?></h2>
				 </div>
		<div class = deal_desc_box>
			
				<p ><?php the_content();?></p> 
			 </div>
			
<div class = deal_search>
		
		  <div class="search_coupon" >
			  <input id="deal_search" type="text" name="search" placeholder="Search Coupon">
			  <input id="deal_search_submit" type="button"  value ="Enter">
		  </div>
					
      
				
<?php		
$deal_search='';
if(isset($_GET['search'])){
	$deal_search= $_GET['search']; 
}

			
?>
				
				
<!--			<input type ="text" value="search" >-->
				
				</p> 
				 
			
			
			
			
			
			
		
		</div>
	
	
	

	</div>
	
	</div>
		<div class="call_to_action main_deal">
			
			
			<div class="deal_couponall">
			
				
			
				<?php 
		
				
				
		$allDealPosts = get_post_meta($post_id_deal,'all_posts_with_coupon'); ?>
				<div class="list_deal">
				
					
	<?php 
					//	var_dump($allDealPosts);				
					$allDealPost =$allDealPosts[0];
					$categories_count = array();
					$categories_name = array();
//					
					?>
				<div class="deal_category">
					<div class="deal_link" style=" display: none;">
					
		<?php	
						$count=0;
					$pageAlt = 1;
						$total_coupon = 0;
						foreach($allDealPost as $alllist){
							$taxonomy = wp_get_post_terms($alllist, 'list_categories',  array("fields" => "all"));
					foreach($taxonomy as $category)
				{
//					
	$categories_count[$category->term_id] =$categories_count[$category->term_id]+1;
	$categories_name[$category->term_id]	= $category->name;

			
		?>

	<?php   				
						
				}
					
							global $post;
    $post_slug = $post->post_name;
							foreach($categories_count as $key => $value){
							$total_coupon += $value;
									$pages = ceil($value/10);
								for($i=1; $i<=$pages; $i++){
								echo "<a href='http://" . $_SERVER['SERVER_NAME']."/$post_slug/?cat=".$categories_name[$key]."&pageno=".$i."'>$categories_name[$key]</a>";
								
								}
								
							}
							
						
							
						}
						
							$pages = ceil($total_coupon/10);
							for($i=1; $i<=$pages; $i++){
						echo "<a href='http://" . $_SERVER['SERVER_NAME']."/$post_slug/?cat=all&pageno=".$i."'>all</a>";
							}
					?>	
					</div>
					
		<div class="cat_deal"><select id="cat_select">		
		<?php	echo "<option value=''>All</option>";
		
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
						$deal_link = get_site_url().'/deal/?cat='.$term->name;
if($i<4){
		
		echo "<div class='cpn_list'><a  data-id='$cat_id' data-url= '$cat_link' data-name= '$term->name' class='all_cat_name' data-page='1'  data-search = '$deal_search'>".$term->name."</a></div>";
			
		
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
		
	<?php $link_url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']."?cat=all&pageno=1"; ?>

	<div class="view_coupon"><div class="all_coupn_btn" id ="view_coupn_btn" data-startpage="1"  > 
		View More Coupon</div> 
	<div id ="no_more_coupon" style="display:none;" >  No More Coupon</div>
	</div>

	</div>
	
	
	
	
	
	
	
		<div class="deal_subscritipn">		<div class="deal_form_box"><?php echo $deal_subsciption_form;?></div> </div>
</div>


<!-------------------- load more coupon-------------------------------------------------->


                      
<!--
  <div id="ajax-result"></div>
                        
                        <div class="ajax-ele" style="text-align: center;"> 
                          <button id="morecoupon" data-startpage="<?php //echo $current_page+1 ?>"  data-uri="<?php //echo $_SERVER['REQUEST_URI']; ?>"  class="getbtn" style="border:none"><?php //echo $current_page+1; ?> Load More</button>  
                         
                      </div>
-->




<!-------------------- load more coupon -------------------------------------------------->

<script>
	var current_page = <?php 
	if( isset($_GET['pageno']))
		echo  $_GET['pageno'];
	else
		echo 1; ?>;
	<?php 
	$termid = '\'\'';
	$categoryName = 'all';
	if(isset($_GET['cat'])){
		$categoryName = urldecode($_GET['cat']);
		
		$taxonomy = get_terms('list_categories');
		file_put_contents("deal.txt","taxonomy url \n".print_r($taxonomy ,true),FILE_APPEND);
		foreach($taxonomy as $tax){
			file_put_contents("deal.txt","category name $categoryName tax $tax->name",FILE_APPEND);

			if($categoryName == $tax->name){
				$termid = $tax->term_id;
				break;
			}
		}

	}
	
	
	
	?>
		var termid= <?php echo $termid; ?>;
		var categoryNameUrl = '<?php echo $categoryName ?>';
	
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
	console.log(data.no_more_posts); 
	var nmp = data.no_more_posts;
	if( nmp == 1){
		jQuery("#view_coupn_btn").hide();
		jQuery("#no_more_coupon").show();
	}
	else{
		jQuery("#view_coupn_btn").show();
		jQuery("#no_more_coupon").hide();
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
				'<p><a href="'+val.post_link+'">'+val.post_title+'</a></p>'+
		' <p class="rating_list">'+val.rating+				
             '</p>'+
		'</div>'+
					
					'<div class="deal_btntn">'+
							'<p class="deal_btn"><a href="'+val.post_link+'coupon/" >View Deal</a></p>'+
					
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
	
	console.log("starting of page current page "+current_page+ "termid "+termid);
	ajax_call_coupons(current_page,'',termid);

	jQuery(".all_cat_name").click(function(){
		
		jQuery('.all_cat_name').removeClass('active');		
	
		var current_page = jQuery(this).attr("data-page");
		var search_term = jQuery(this).attr("data-search");
		var termid = jQuery(this).attr("data-id");		
		var cat_name = jQuery(this).attr("data-name");
		categoryNameUrl = cat_name;
		console.log("remebber" +categoryNameUrl);
		pushurl = '?cat='+categoryNameUrl;
		history.replaceState({name: 'Category'}, "pushState example", pushurl);

		
		
		current_page =1;
	ajax_call_coupons(current_page,search_term,termid);
	jQuery("#deal_search_submit").attr("data-id",termid);
	jQuery("#view_coupn_btn").attr("data-id",termid);
	jQuery("#view_coupn_btn").attr("data-startpage",'1');

 jQuery(this).addClass("active");
	});
	
	
//   jQuery('.search_deal ').submit(function(e){
////    alert("Form submitted!");
//     // Prevent the original submit
//});
//	   jQuery(document).ready(function(){  
//	jQuery("#deal_search").on("keypress", function(e){
////		  jQuery("#deal_search").keypress(function(){  
//
//        if(e.which == 13){
//
//            alert('abc');
//
//        }
//
//    });
//	
//	   });
	
	

	
//	jQuery( "#deal_search" ).on("keypress", function(e){ 
//  if(e.which == 13){
//console.log(jQuery("#deal_search_submit"));
//		jQuery("#deal_search_submit").attr("data-search",jQuery(this).val());
//
//		
//
//        }
//  
//});
	jQuery("#deal_search").keypress(function(e) {	
		var key = e.which;
		if(key == 13){
			
		console.log(jQuery("#deal_search_submit"));
			current_page =1;
//		jQuery("#deal_search_submit").attr("data-search",jQuery(this).val());
		var current_page = jQuery("#deal_search_submit").attr("data-page");
		var search_term = jQuery("#deal_search_submit").attr("data-search");
		var termid = jQuery("#deal_search_submit").attr("data-id");
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
		
			
		}
		
		 
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
	
//		jQuery( "#deal_search" ).on("keypress", function(e){ 
//  if(e.which == 13){
//console.log(jQuery("#deal_search_submit"));
//		jQuery("#deal_search_submit").attr("data-search",jQuery(this).val());
//
//		
//
//        }
//  
//});
	
	
	jQuery("#deal_search").keyup(function() {
		
		console.log(jQuery("#deal_search_submit"));
		jQuery("#deal_search_submit").attr("data-search",jQuery(this).val());
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
	
	
		//  var parms = get_param();
		 var pushurl = "?cat="+categoryNameUrl+"&pageno="+current_page;
              /*   if(parms != null){
                    pushurl += "sort=" + parms.sort[0] +"&lang=<?php echo $isoCode ?>#filter";
                }
                else{
                    pushurl="?lang=<?php echo $isoCode ?>";
                } */
 history.replaceState({name: 'Location'}, "pushState example", pushurl);
		
 
	});
	
	
	
	

	
	jQuery('#cat_select').on('change', function() {
		jQuery('.all_cat_name').removeClass('active');
		

		
		// var current_page = jQuery(this).attr("data-page");
		var search_term = jQuery(this).attr("data-search");
		var termid = this.value;

		var cat_name = jQuery(this).attr("data-name");
		categoryNameUrl = jQuery("#cat_select option:selected").text();
		console.log("remebber" +categoryNameUrl);
		pushurl = '?cat='+categoryNameUrl;
		history.replaceState({name: 'Category'}, "pushState example", pushurl);

		console.log("term id cat_select"+termid);
		current_page =1;
	ajax_call_coupons(current_page,search_term,termid);
	jQuery("#deal_search_submit").attr("data-id",termid);
	jQuery("#view_coupn_btn").attr("data-id",termid);
	jQuery("#view_coupn_btn").attr("data-startpage",'1');
		

		
		
 jQuery(this).addClass("active");
	});
});
	
	 function get_param(){
		 
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
		 return parms;
	 }
</script>




<?php
get_footer();
?>