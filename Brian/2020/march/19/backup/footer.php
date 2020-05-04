<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}
	if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
		get_template_part( 'template-parts/footer' );
	}
?>

<?php wp_footer(); 
// $isoCode = do_shortcode('[geoip_detect2 property="country.isoCode"]');
?>

<script>
	

	
	
	
	
	
	
	
	
	
	
	
	
	var mostlyVisible = function(element) {
	  // if ca 25% of element is visible
	  var scroll_pos = jQuery(window).scrollTop();
	  var window_height = jQuery(window).height();
	  var el_top = jQuery(element).offset().top;
	  var el_height = jQuery(element).height();
	  var el_bottom = el_top + el_height;
	  return ((el_bottom - el_height*0.25 > scroll_pos) && 
			  (el_top < (scroll_pos+0.5*window_height)));
	}
	<?php if(!isset($_GET['sort'])){ ?>
	var last_scroll = 0;
	jQuery(window).scroll(function() {
		// console.log("scrolled");
		//get location from url
			// console.log("current url from footer : "+window.location.href);
			var url = window.location.href;
			var queryStart = url.indexOf("?") + 1,
        queryEnd   = url.indexOf("#") + 1 || url.length + 1,
        query = url.slice(queryStart, queryEnd - 1),
        pairs = query.replace(/\+/g, " ").split("&"),
        parms = {}, i, n, v, nv;

    if (query === url || query === "") return;

    for (i = 0; i < pairs.length; i++) {
        nv = pairs[i].split("=", 2);
        n = decodeURIComponent(nv[0]);
        v = decodeURIComponent(nv[1]);

        if (!parms.hasOwnProperty(n)) parms[n] = [];
        parms[n].push(nv.length === 2 ? v : null);
    }
	// console.log(parms);
		// end of get location from url 
		
		var scroll_pos = jQuery(window).scrollTop();
		if (Math.abs(scroll_pos - last_scroll) > jQuery(window).height()*0.1) {
		  last_scroll = scroll_pos;
				 jQuery(".page-cont").each(function(index) {	
				  var url = jQuery(this).attr("data-page-url"); 
				  var page_no = jQuery(this).attr("data-page");
						if(mostlyVisible(this)) {
							 history.replaceState(null, null, url+page_no+"/?lang="+parms.lang);
							 return false;
						} else {
								console.log("Not visible");
						}
				});
		}
	});
	<?php } ?>
var infinite_scroll = function(list_id, reuri, count) {
    			jQuery.ajax({
							url: "<?php bloginfo('wpurl') ?>/wp-admin/admin-ajax.php",
							type:'POST',
							data: {"action": "infinite_scroll", "page_no": count, "list_id": list_id},
							beforeSend: function() {
							  jQuery("#ajax-loader").show();
						    }, 
							success: function(html){
								jQuery("#ajax-response").append(html);
								//page_c = count-1;
                               // history.pushState(page_c, null, reuri+page_c+"/");
									 
							},
							complete: function(){
								var stack_setting = {
										/* slideshowLayout: horizontalLeft, horizontalRight, verticalAbove, verticalRound */
										slideshowLayout: 'horizontalLeft',
										/* slideshowDirection: forward, backward */
										slideshowDirection:'forward',
										/* controlsAlignment: rightCenter, topCenter */
										controlsAlignment:'rightCenter',
										/* fullSize: slides 100% size of the componentWrapper, true/false. */
										fullSize:true,
										/* slideshowDelay: slideshow delay, in miliseconds */
										slideshowDelay: 3000,
										/* slideshowOn: true/false */
										slideshowOn:false,
										/* useRotation: true, false */
										useRotation:true,
										/* swipeOn: enter slide number(s) for which you want swipe applied separated by comma (counting starts from 0) */
										swipeOn:'0,1,2,3,4'
									};
									var galleries2 ={}
									//setTimeout(function(){
									jQuery('.componentWrapper').each(function (ind) {
										var id = jQuery(this).attr('id');
										galleries2[ind] = jQuery('#'+id).stackGallery(stack_setting);
										// $("#componentWrapper a[data-rel^='prettyPhoto']").prettyPhoto({theme:'pp_default',
										//     callback: function(){galleries[ind].checkSlideshow2();}});
									});
									
									//}, 5000);
								    stack_settings = null; 
									jQuery("#ajax-loader").hide();
									//if(count == total_pages){
										
										//jQuery("#loadmore").hide();
										//jQuery("#ajax-response").append("No more posts!");
									//}
							},
							error: function(ajaxreq){
							  console.log(ajaxreq.responseText);
						    }
				});
}


var countl = jQuery("#loadmore").attr("data-startpage");
jQuery("#loadmore").click(function() {	
	var list_id = <?php echo get_the_ID(); ?>;
	var reuri  =   jQuery(this).attr("data-uri");
	var total_pages = jQuery("#total-pages").val(); 
	console.log("Total Pages:"+total_pages);
		if(countl > total_pages) {
			 jQuery("#nopostmessage").html("<p style='text-align:center'>No more posts!</p>");
			 jQuery(this).attr("disabled", "disabled");
			 return false;
		} else {
						   infinite_scroll(list_id, reuri, countl);				 
						   countl++;
		  }

});
var count = jQuery("#infinite-loader").attr("data-startpage");
jQuery('#infinite-loader').on('inview', function(event, isInView) {
	var list_id	    = <?php echo get_the_ID(); ?>;
	var reuri   	=   jQuery(this).attr("data-uri");
	var total_pages =   jQuery("#total-pages").val(); 
	if (isInView) {
		if(count <= total_pages){
			 infinite_scroll(list_id, reuri, count);			 
             count++;
		} else {
			jQuery("#nopostmessage").html("<p style='text-align:center'>No more posts!</p>");
			jQuery(this).hide();
			return false;
		}
		
	} 
});

/* new code for comparision forter--------*/
var offsetTop = jQuery(".column-pricing").offset().top;
jQuery(window).scroll(function() {
  var scrollTop = jQuery(window).scrollTop();
  if (scrollTop > offsetTop) {
	  jQuery(".compair").addClass("stickyshow");
    jQuery(".compair").fadeIn(200);
  }else{
	   jQuery(".compair").fadeOut(200);
  }
});
    

function claim_listing_func(group_id,post_id,current_user){
	
		console.log(event);
		var source = event.target || event.srcElement;
		console.log(source);
		console.log(group_id +  " "+ post_id);
        console.log(current_user);



		var data = {
		'action': 'claim_action',
		'posttitle': 'posttitle',
		'dataType': 'json',
		// 'group_id':group_id',
		'post_id':post_id,
		'current_user':current_user,

		};
	
	
			var ajaxurl = "<?php get_site_url(); ?>/wp-admin/admin-ajax.php";
			jQuery.ajax({
			type: "POST",
			url:ajaxurl,
			data: data,

			success: function(data){
			 console.log("claimed");
			 source.outerHTML = "<span>Claim sent! It will show up on your my software page when approved by admin.</span>";
			 document.getElementById("myOutput").innerHTML = "<span>Claim sent! It will show up on your my software page when approved by admin.</span>";


			}
			});

    }




	



 
	
</script>
</body>
</html>
