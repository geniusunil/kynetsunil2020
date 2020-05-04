<?php 
/*
 * Template Name: update_software
 * 
 */
 ?>
<?php ob_get_clean(); 
acf_form_head(); ?>

<?php get_header();?>
<!-- <button id="submititem" type="button">popup</button> -->
<script>

	jssor_1_slider_init = function() {

            var jssor_1_options = {
              $AutoPlay: 0,
              $AutoPlaySteps: 4,
              $SlideDuration: 160,
              $SlideWidth: 200,
              $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$,
                $Steps: 4
              },
              $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
              }
            };

            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

            /*#region responsive code begin*/

            var MAX_WIDTH = 980;

            function ScaleSlider() {
                var containerElement = jssor_1_slider.$Elmt.parentNode;
                var containerWidth = containerElement.clientWidth;

                if (containerWidth) {

                    var expectedWidth = Math.min(MAX_WIDTH || containerWidth, containerWidth);

                    jssor_1_slider.$ScaleWidth(expectedWidth);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }

            ScaleSlider();

            $Jssor$.$AddEvent(window, "load", ScaleSlider);
            $Jssor$.$AddEvent(window, "resize", ScaleSlider);
            $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
            /*#endregion responsive code end*/
        };
	
</script>
    <style>
		
			.list_all {
    border: #DFDFDF solid 1px;
   
}
	.expdate {
    border-bottom: 1px solid 
    #eee;
}
		.list_another_cpn {
			border: #DFDFDF solid 1px;
}
	
		
        /* jssor slider loading skin double-tail-spin css */
        .jssorl-004-double-tail-spin img {
            animation-name: jssorl-004-double-tail-spin;
            animation-duration: 1.2s;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
        }

        @keyframes jssorl-004-double-tail-spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }


        .jssorb051 .i {position:absolute;cursor:pointer;}
        .jssorb051 .i .b {fill:#fff;fill-opacity:0.5;stroke:#000;stroke-width:400;stroke-miterlimit:10;stroke-opacity:0.5;}
        .jssorb051 .i:hover .b {fill-opacity:.7;}
        .jssorb051 .iav .b {fill-opacity: 1;}
        .jssorb051 .i.idn {opacity:.3;}

        .jssora051 {display:block;position:absolute;cursor:pointer;}
        .jssora051 .a {fill:none;stroke:#2d2d2d;stroke-width:360;stroke-miterlimit:10;}
        .jssora051:hover {opacity:.8;}
        .jssora051.jssora051dn {opacity:.5;}
        .jssora051.jssora051ds {opacity:.3;pointer-events:none;}
    </style>

<!--<script type="text/javascript">jssor_1_slider_init();</script>-->
<?php

$groups = acf_get_field_groups(145826);


	$currentuser= $_GET['update-software']; 
	$post_name =  get_the_title($currentuser);
	$post_id = $currentuser;	
    $current_user = wp_get_current_user();	
	$userid = $current_user->ID;
	$claim  = get_field('real_author',$currentuser);


if( $claim == $userid || $userid==2 || $userid==13) {
  		wp_nonce_field(basename(__FILE__), "nd-list-item-mbnonce");
		$html_before_form = '';
		$html_before_form .= '<div class="meta-box-list">';
		$html_before_form .= '<div class="metabox-field">';
		$terms = get_terms( array(
        'taxonomy' => 'list_comp_categories',
        'hide_empty' => false,

    ) );


    $tax  = get_post_meta($post_id, 'features_category', true);

    $featureList  = get_post_meta($post_id, 'features_list', true);
 
    if(!is_array($featureList)){

        $featureList = array();
	

    }

    $termsArr = array();


    foreach ($terms as $td){

        $selected = $tax==$td->term_id?'selected="selected"':'';

        $termsArr[] = $td->term_id;     
	
    }

    $html_before_form .= '</select> </div> ';
    $html_before_form .= '</div>';
    $html_before_form .= '<div class="metabox-field">';
    $html_before_form .= '<label for="">Features</label><div class="meta-field-inp">
	<div id="list_items_features_meta">';

 foreach ($termsArr as $allid){
	 $software_id[] = get_option( "taxonomy_$allid" );
	 $software_id_flat = array();
 }

foreach($software_id as $k ) {
 	$new = (array)$k["features_list"];	 
	$software_id_flat = array_merge($software_id_flat,$new);
		
    }

?>


<?php
		
    $term_meta = get_option( "taxonomy_$tax" ); 
	$html_before_form .= '<div class="features_list_container post_edit_page">
		<input autocomplete="off" class="add_new_item_featur bubble_insert" type="text" name="new_features_list[]" value="" id ="software_search1" >		
		<ul class="items_list">             

            </ul> </div>';
	$items = $featureList;

     $html_before_form .= '<div id="features">';
	 $html_before_form .= "<ul class='post_features_list list'>";
	 foreach ($items as $key => $it){
			 if(!empty($it)){
				 $checked = in_array($it,$featureList)?'checked="checked"':'';
				 $html_before_form .= '<li class = "item_list"><input autocomplete="off" class="add_new_item_featur" type="text" name="new_features_list[]" value="'.$it.'" id ="software_searc"><span class="remove_cat_feature">X</span></li>';

                }

            }

            $html_before_form .= "</ul></div>";
			$html_before_form .=     '<div class="features_list_container1 post_edit_page"><span class="add_new_item_feature">Add Extra Features</span> <ul>

				</ul> </div>';
    $html_before_form .= '</div>';

    $html_before_form .= '</div>';

    $html_before_form .= '</div>';

    $html_before_form .= '</div>';
    $html_before_form .= '<input type="hidden" name="acf[register]" value="true"/>';

//---------------------------------videobox---------------------------------------------	
	$video_List  = get_post_meta($post_id, 'video_list', true);
//	  file_put_contents("updatesoftware.txt","videoList1 ".print_r($video_List,true),FILE_APPEND);
	$termsArr = array();
		foreach ($terms as $td){

		$selected = $tax==$td->term_id?'selected="selected"':'';

		$termsArr[] = $td->term_id;

		}

		$html_before_form .= '</select> </div> ';
		$html_before_form .= '</div>';
		$html_before_form .= '<div class="metabox-field">';
		$html_before_form .= '<div class="meta-field-inp">
		<div id="list_items_features_meta">';
	
?>

<?php
			$term_meta = get_option( "taxonomy_$tax" );
			$items_vdu = $video_List;
            $html_before_form .= '<div class = "video_box">';           
            $html_before_form .= '<b>Video</b>';
			$html_before_form .= "<ul class='post_video_list list'>";           

            foreach ($items_vdu as $key => $it_vdu){            

                if(!empty($it_vdu)){
				 $checked = in_array($it_vdu,$video_List)?'checked="checked"':'';
				
				  $html_before_form .= '<li class = "item_list"><input autocomplete="off" class="add_new_item_featur" type="text" name="new_video_list[]" value="'.$it_vdu.'" id ="software_searc"><span class="remove_cat_feature">X</span></li>';

                }

            }

            $html_before_form .= "</ul></div>";

			$html_before_form .= '</div>';

			$html_before_form .= '</div>';

			$html_before_form .= '</div>';

			$html_before_form .= '</div>';

			$html_before_form .= '<div class = "videos_list"></div>';
			 error_reporting(0);
			$html_before_form .= '<input autocomplete="off" class="video_list " type="text" name="new_video_list[]" value="" id ="software_searc">

				<ul class="items_list">              

					</ul>';

		$name_post = get_the_title( $currentuser );
//				file_put_contents("search_title.txt",$name_post,FILE_APPEND);
		$search = $name_post ; // Search Query
		$api = "AIzaSyBxZQza9iYMySd0Tcd93k3Esv3AGfIVJp0"; // YouTube Developer API Key

		$link = "https://www.googleapis.com/youtube/v3/search?safeSearch=moderate&order=relevance&part=snippet&q=".urlencode($search). "&maxResults=10&key=". $api;

		$video = file_get_contents($link);
	
		$video = json_decode($video, true);

	
		$html_before_form .= '<div id="jssor_1" style="position:relative;margin:0 auto;top:0px;left:0px;width:980px;height:360px;overflow:hidden;visibility:hidden;">
        <!-- Loading Screen -->
        <div data-u="loading" class="jssorl-004-double-tail-spin" style="position:absolute;top:0px;left:0px;text-align:center;background-color:rgba(0,0,0,0.7);">
            <img style="margin-top:-19px;position:relative;top:50%;width:38px;height:38px;" src="img/double-tail-spin.svg" />
        </div>
        <div data-u="slides" style="cursor:default;position:relative;top:0px;left:0px;width:980px;height:360px;overflow:hidden;" class="video_box_list">';
            
		foreach ($video['items'] as $data){

			$title = $data['snippet']['title'];
			$description = $data['snippet']['description'];
			$vid = $data['id']['videoId'];
			$image = '<img src = "https://i.ytimg.com/vi/'.$vid.'/default.jpg" class="image_box" data-url='.$vid.'>';  
			 if($vid){
			$html_before_form .= '<div class=video_box1>';       
			$html_before_form .= '<div>'.$image.'</div>';
			$html_before_form .= '<div class="box_title"><a target= "_blank" href="https://www.youtube.com/watch?v='.$vid.'">'.$title.'</a></div>';
			$html_before_form .= '<div>'.$description.'</div>';		
  
			$html_before_form .= '</div>';
		}
}
	

	$html_before_form .= '</div>
        <!-- Bullet Navigator -->        
        <!-- Arrow Navigator -->
        <div data-u="arrowleft" class="jssora051" style="width:55px;height:55px;top:0px;left:0px;" data-autocenter="2" data-scale="0.75" data-scale-left="0.75">
            <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                <polyline class="a" points="11040,1920 4960,8000 11040,14080 "></polyline>
            </svg>
        </div>
        <div data-u="arrowright" class="jssora051" style="width:55px;height:55px;top:0px;right:0px;" data-autocenter="2" data-scale="0.75" data-scale-right="0.75">
            <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                <polyline class="a" points="4960,1920 11040,8000 4960,14080 "></polyline>
            </svg>
        </div>
    </div></div>';
	
	
	$html_before_form .= '</div>';
	
	//---------------------------------end of videobox---------------------------------------------	 
	$html_before_form .= '<div class= "form_group">';
	$group_ID = 146126;

	$fields = array();
	$fields = acf_get_fields($group_ID);

//	echo "<pre>";
//	print_r($fields);
//	echo "</pre>";
	
	
	if( $fields )
	{
		$html_before_form .= '<dt class= "review_title">Want to get reviews right away ?</dt>';
		$html_before_form .= '<span class = "review_desc">Provide up to 3 email addresses for users of the product/service you\'re adding, and we\'ll reach out to them to ask for their review.</span>';
		foreach( $fields as $field )
			{
				$value = get_field( $field['name'],$currentuser );
				echo '<dl>';
				
//		$html_before_form .= '<dt class= "review_title">' . $field['label'] . '</dt>';
				$html_before_form .= '<input type="'.$field['type'].'" id="'.$field['ID'].'"  placeholder="'.$field['placeholder'].'" value = "'."$value".'" class="acf-field acf-field-email review_input" name="acf['.$field['key'].']"> ';
				 echo '</dl>';



			}
	}
	
        $html_before_form .= '</div>';
        $couponlist  = get_post_meta($post_id, 'coupons_list', true);

        $html_before_form .= '
        <script>
        function showCouponForm{
            document.getElementById("couponForm").style.display = "block";
        }
        function hideButtons{
            document.getElementById("couponButtons").style.display = "none";
        }
        
        
        </script>
        
        <div>
        <h2>Want to boost conversions?</h2>
        <h5>Adding an offer for this product can significantly increase your conversion rate.</h5>
        <div id="couponButtons"><button type="button" id="yesbtn" >yes</button>
        <button type="button" id="nobtn" >no</button></div>';
        if(empty($couponlist)){
            $class="hidden";
        }        
        else{
            $class="";
        }
        $html_before_form .= '<div id="couponForm" class="'.$class.'">
            ';
        
            $html_before_form .= '<div class="coupons_container post_edit_page"><span class="add_new_coupon">Add Another Coupon</span> <ul class="list_all">

              

            </ul> </div>';
        // $couponlist  = get_post_meta($post_id, 'video_list', true);
        $couponlist  = get_post_meta($post_id, 'coupons_list', true);
            if(!empty($couponlist)){
        
        
        
              
                if(!empty($couponlist) && is_array($couponlist)){
                
        
                
        
                    $html_before_form .= '<div id="features">
        
                         ';
        
                    $html_before_form .= "<ul class='post_coupons_list list'>";
        
                    // $couponlist = $couponlist[0];
                    foreach ($couponlist as $coupon){
        
                        if(!empty($coupon)){
                            // for($j=0;$j<count($coupon);$j++){
                                    // print_r($coupon);
                                    $coupontypecoupon = ($coupon[type]=="coupon")? "selected":"";
                                    $coupontypedeal = ($coupon[type]=="deal")? "selected":"";
                                    $ccclass = ($coupontypedeal=="selected")?"hidden":"";
                                    $coupondateshow = ($coupon[showDate]=="show")? "selected":"";
                                    $coupondatehide = ($coupon[showDate]=="hide")? "selected":"";
                                    $cdclass = ($coupondatehide=="selected")?"hidden":"";
                                    $html_before_form .= '<li><label> Coupon : </label><span class="remove_coupon">X Remove Coupon</span></li>';
                                    $html_before_form .= ' <li><label> Coupon Type : </label></label><select class="coupdeal" name="old_coupon_type[]" ><option value="coupon" '.$coupontypecoupon.'>Coupon</option><option value="deal"  '.$coupontypedeal.'>Deal</option></select></li>';
                                    
                                    $html_before_form .= '<li class="'.$ccclass.'" ><label> Coupon code : </label> <input type="text" name="old_coupon_code[]"  value="' . $coupon[code]. '" id="feat_'.$key.'" '.$checked.'></li>';
                                    $html_before_form .= '<li><label> Link : </label><input type="text" name="old_coupon_link[]" value="'.$coupon[link].'" required> </li>';
                                    $html_before_form .= '<li ><label>Discount Amount/Text :</label> <input type="text" name="old_coupon_title[]"  value="' . $coupon[title]. '" id="feat_'.$key.'" '.$checked.' required></li>';
                                    $html_before_form .= '<li ><label> Description : </label> <input type="text" name="old_coupon_description[]"  value="' . $coupon[description]. '" id="feat_'.$key.'" '.$checked.' required></li>';
                                    $html_before_form .= '<li><label> Coupon/Deal Expiration : </label></label><select class="exptoggle" name="old_coupon_expdate_show[]"><option value="show" '.$coupondateshow.'>show</option><option '.$coupondatehide.' value="hide">Hide</option>
                                    
                                  </select></li>';
                                    $html_before_form .= '<li > <label> Expiry date : </label><input type="date" name="old_coupon_expiry_date[]"  value="' . $coupon[expdate]. '" id="feat_'.$key.'" '.$checked.' required></li>';
                                    $html_before_form .= '<li > <input type="hidden" name="old_coupon_id[]"  value="' . $coupon[id]. '" id="feat_'.$key.'" '.$checked.'></li>';
                                    $html_before_form .= '<li > <input type="hidden" name="old_coupon_votes[]"  value="' . $coupon[votes]. '" id="feat_'.$key.'" '.$checked.'></li>';
        
                            // }
                         /*    $checked = "checked";
        
                            $html_before_form .= '<li > <input type="checkbox" name="new_video_list[]"  value="' . $it. '" id="feat_'.$key.'" '.$checked.'><label for="feat_'.$key.'" class="name">'.$it.'</label></li>';
         */
                        }
        
                    }
        
                    $html_before_form .= "</ul></div>";
        
                } else{
        
                    $html_before_form .= "<div class='no_features'>No features added to category yet.</div>";
        
                }
        
              
        
        
        
            } else{
        
                $html_before_form .= "<div class='no_features'>No coupons found!</div>";
        
            }
            $html_before_form .= '<div class="coupons_container post_edit_page"><span class="add_new_coupon">Add Another Coupon</span> <ul class="list_another_cpn"></ul></div>';
            

        $html_before_form .= '</div>
        </div>

        <!-- faqs start -->

            
        <!-- faqs end -->
        <style>
            #couponButtons button{
                width:45%;
                margin:2%;
                background-color: white;
                color: black;
                border: 1px solid #565d62;
            }
        </style>
        
        ';
	//---------------------------form group-------------------------------------

?>


	<?php
		$settings = array(
			  'id' => 'acf-form-1',
			  'post_id' => $currentuser,
			  'post_title' => true,
			  'post_content' =>true,
			  'html_after_fields' => $html_before_form,
			  'field_groups' => array(146140,146079),
			  'submit_value'=> 'Submit',
			
			 );

      $value = acf_form( $settings );	
      echo '<button id="submititem" style="display: none;" type="button">popup</button>';
}
else{
		?>
	<div class = "owner_id"><h4 style="text-align: center;"><strong>You currently don't have managerial privileges to edit this product.</strong></h4>
<h4 style="text-align: center;">If you believe you should have this access you can submit your request here:
<a href="https://www.softwarefindr.com/submit/">https://www.softwarefindr.com/submit/</a> OR click the claim button on the product page.</h4></div>

	<?php
}?>



<script type="text/javascript">jssor_1_slider_init();</script>
<script>
	
var search_list =<?php echo json_encode($software_id_flat); ?>; 
autocomplete_software(document.getElementById("software_search1"), search_list);	 
	
jQuery(document).ready(function () {
	
    jQuery(document).on('click','#yesbtn',function () {
		  
    jQuery("#couponForm").removeClass("hidden");
		
	jQuery(".list_all").append(' <li><label> Coupon Type : </label></label><select class="coupdeal" name="new_coupon_type[]">'+
'<option value="coupon">Coupon</option>'+
'<option value="deal">Deal</option>'+

'</select><span class="remove_coupon">X cancel coupon</span></li>'+

'<li class="coup_code"><label> Coupon code : </label><input type="text" name="new_coupon_code[]" value=""> </li>'+
'<li><label> Link : </label><input type="text" name="new_coupon_link[]" value="" required> </li>'+

' <li><label> Discount Amount/Text : </label></label><input type="text" name="new_coupon_title[]"  value="" required> </li>'+
'<li><label> Description : </label><input type="text" name="new_coupon_description[]" value="" required> </li>'+
' <li><label> Coupon/Deal Expiration : </label></label><select class="exptoggle" name="new_coupon_expdate_show[]">'+
'<option value="show">show</option>'+
'<option value="hide">Hide</option>'+

'</select></li>'+
'<li class="expdate"><label> Expires : </label><input type="date" name="new_coupon_expiry_date[]"  value="" required> </li>');
			 

			 
			 
		  
	});
  jQuery(document).on('click','#nobtn',function () {
    jQuery("#couponForm").addClass("hidden");
    console.log("hide");
  });
    jQuery('html, body').animate({
      scrollTop: jQuery('#pricing_id').offset().top //this line crashes on pages other than update_software. Add an if condition so that it does not throw an error.
  }, 'slow');

  jQuery(document).on('change','.coupdeal',function () {
        console.log("changed deal");
        var val= this.value;
        console.log(val);
        // console.log(jQuery(this).closest('li').next());
        switch(val)
           {
                case "coupon":
                {
                    jQuery(this).closest('li').find('input').prop('required',true);
                    jQuery(this).closest('li').next().show();

                     break;
                }
                case "deal":
                {
                    jQuery(this).closest('li').find('input').prop('required',false);

                    jQuery(this).closest('li').next().hide();
                     
                     break;
                }
           }
    });
    
   /*  jQuery(document).on('change','.exptoggle',function () {
        // console.log("changed deal");
        var val= this.value;
        // console.log(val);
        // console.log(jQuery(this).closest('ul').find('.coup_code'));
        switch(val)
           {
                case "show":
                {
                    jQuery(this).closest('li').next().show();

                     break;
                }
                case "hide":
                {
                    jQuery(this).closest('li').next().hide();
                     
                     break;
                }
           }
    }); */

    jQuery(document).on('click','.add_new_coupon',function () {
		jQuery(this).closest('.coupons_container').find('ul').append(' <li><label> Coupon Type : </label></label><select class="coupdeal" name="new_coupon_type[]">'+
'<option value="coupon">Coupon</option>'+
'<option value="deal">Deal</option>'+

'</select><span class="remove_coupon">X cancel coupon</span></li>'+

'<li class="coup_code"><label> Coupon code : </label><input type="text" name="new_coupon_code[]" value=""> </li>'+
'<li><label> Link : </label><input type="text" name="new_coupon_link[]" value="" required> </li>'+

' <li><label> Discount Amount/Text : </label></label><input type="text" name="new_coupon_title[]"  value="" required> </li>'+
'<li><label> Description : </label><input type="text" name="new_coupon_description[]" value="" required> </li>'+
' <li><label> Coupon/Deal Expiration : </label></label><select class="exptoggle" name="new_coupon_expdate_show[]">'+
'<option value="show">show</option>'+
'<option value="hide">Hide</option>'+

'</select></li>'+
'<li class="expdate"><label> Expires : </label><input type="date" name="new_coupon_expiry_date[]"  value="" required> </li>');
    console.log("show");


/* jQuery(this).closest('.coupons_container').find('ul').append(' ');
jQuery(this).closest('.coupons_container').find('ul').append(' ');
jQuery(this).closest('.coupons_container').find('ul').append(' ');
*/
});



jQuery(document).on('click','.remove_coupon',function () {
        // jQuery(this).closest('li').remove();
        for (var i=0;i<9;i++){
            jQuery(this).closest('li').next().remove();

        }
        jQuery(this).closest('li').remove();



    });
   /*  $( "#submititem" ).click(function() {
        alert( "Handler for .click() called." );
    }); */

    

    // jQuery("#elementor-popup-modal-146022").show();
    // clickLink(document.getElementById('submititem'));
    var url=window.location.href;
    console.log(url);
    if(url.indexOf('updated=true') >= 0){

        setTimeout(function () {

        // fakeClick(event,document.getElementById('submititem'));
        simulateClick();  //https://developer.mozilla.org/en-US/docs/Web/Guide/Events/Creating_and_triggering_events
        //https://developer.mozilla.org/samples/domref/dispatchEvent.html
        // jQuery('#submititem').trigger('click');
        }, 2000);
    }
   

});	
function fakeClick(event, anchorObj) {
  if (anchorObj.click) {
    anchorObj.click()
  } else if(document.createEvent) {
    if(event.target !== anchorObj) {
    //   var evt = document.createEvent("MouseEvents"); 
    //   evt.initMouseEvent("click", false, false, window, 
        //   0, 0, 0, 0, 0, false, false, false, false, 0, null); 

        var evt = new CustomEvent('build', { detail: elem.dataset.time });
      var allowDefault = anchorObj.dispatchEvent(evt);
      // you can check allowDefault for false to see if
      // any handler called evt.preventDefault().
      // Firefox will *not* redirect to anchorObj.href
      // for you. However every other browser will.
    }
  }
}

function simulateClick() {
  var event = new MouseEvent('click', {
    view: window,
    bubbles: true,
    cancelable: true
  });
  var cb = document.getElementById('submititem'); 
  var cancelled = !cb.dispatchEvent(event);
  /* if (cancelled) {
    // A handler called preventDefault.
    alert("cancelled");
  } else {
    // None of the handlers called preventDefault.
    alert("not cancelled");
  } */
}


</script>


<?php


get_sidebar(); 
get_footer();


?>

