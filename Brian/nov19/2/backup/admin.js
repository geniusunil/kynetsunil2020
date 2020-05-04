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

        jQuery(this).closest('.coupons_container').find('ul').append(' <li><label> Title : </label></label><input type="text" name="new_coupon_title[]"  value=""> <span class="remove_coupon">X</span></li>');
        jQuery(this).closest('.coupons_container').find('ul').append(' <li><label> Description : </label><input type="text" name="new_coupon_description[]"  value=""> <span class="remove_coupon">X</span></li>');
        jQuery(this).closest('.coupons_container').find('ul').append(' <li><label> Coupon code (if any) : </label><input type="text" name="new_coupon_code[]" value=""> <span class="remove_coupon">X</span></li>');
        jQuery(this).closest('.coupons_container').find('ul').append(' <li><label> Expiry date : </label><input type="text" name="new_coupon_expiry_date[]"  value=""> <span class="remove_coupon">X</span></li>');

    })

    if(jQuery('#features').length) {

        var options = {

            valueNames: ['name']

        };



        var userList = new List('features', options);

    }



});