jQuery(document).ready(function () {

    var currentRequest = null;

    jQuery(document).on('change','#features_category',function () {
        console.log(wp_ajax_url);
        var val = jQuery(this).val();

        var post = jQuery(this).data('post');

        currentRequest = jQuery.ajax({

            type:'post',

            url:wp_ajax_url,

            beforeSend : function()    {

                if(currentRequest != null && currentRequest.readyState < 4) {

                    currentRequest.abort();

                }

                jQuery('#list_items_features_meta').html('');



            },

            data:{

                action:'get_feature_list',

                taxonomy:val,

                post:post,

            },

            success: function(data) {

                jQuery('#list_items_features_meta').html(data);

                var options = {

                    valueNames: ['name']

                };



                var userList = new List('features', options);

                jQuery.fn.matchHeight._update();

            },

            error: function(xhr, ajaxOptions, thrownError) {

                if(thrownError == 'abort' || thrownError == 'undefined') return;

                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

            }

        });



    });

    jQuery(document).on('click','.remove_cat_feature',function () {

        jQuery(this).closest('li').remove();

    })

    jQuery(document).on('click','.add_new_cat_feature',function () {

        jQuery('.features_list_container ul').append(' <li><input type="text" name="term_meta[features_list][]"  value=""> <span class="remove_cat_feature">X</span></li>');

    });

    jQuery(document).on('click','.add_new_coupon',function () {

        jQuery(this).closest('.coupons_container').find('ul').append(' <li><label> Coupon Type : </label></label><select class="coupdeal" name="new_counpon_type[]">'+
        '<option value="coupon">Coupon</option>'+
        '<option value="deal">Deal</option>'+
        
      '</select><span class="remove_coupon">X</span></li>'+
      
      '<li class="coup_code"><label> Coupon code : </label><input type="text" name="new_coupon_code[]" value=""> </li>'+
      '<li><label> Link : </label><input type="text" name="new_coupon_link[]" value=""> </li>'+

        ' <li><label> Discount Amount/Text : </label></label><input type="text" name="new_coupon_title[]"  value=""> </li>'+
        '<li><label> Description : </label><input type="text" name="new_coupon_description[]"  value=""> </li>'+
       ' <li><label> Coupon/Deal Expiration : </label></label><select class="exptoggle" name="new_coupon_expdate[]">'+
        '<option value="show">show</option>'+
        '<option value="hide">Hide</option>'+
        
      '</select>'+
        '<li class="expdate"><label> Expires : </label><input type="text" name="new_coupon_expiry_date[]"  value=""> </li>');
        /* jQuery(this).closest('.coupons_container').find('ul').append(' ');
        jQuery(this).closest('.coupons_container').find('ul').append(' ');
        jQuery(this).closest('.coupons_container').find('ul').append(' ');
 */
    });
    jQuery(document).on('change','.coupdeal',function () {
        console.log("changed deal");
        var val= this.value;
        console.log(val);
        console.log(jQuery(this).closest('li').next());
        switch(val)
           {
                case "coupon":
                {
                    jQuery(this).closest('li').next().show();

                     break;
                }
                case "deal":
                {
                    jQuery(this).closest('li').next().hide();
                     
                     break;
                }
           }
    });
    
    jQuery(document).on('change','.exptoggle',function () {
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
    });
    if(jQuery('#features').length) {

        var options = {

            valueNames: ['name']

        };



        var userList = new List('features', options);

    }



});

function SwitchCoupon(val)
      {
           switch(val)
           {
                case "coupon":
                {
                    jQuery(this).closest('.coup_code').style.display = "block";

                     break;
                }
                case "deal":
                {
                    jQuery(this).closest('.coup_code').style.display = "none";
                     
                     break;
                }
           }
      }