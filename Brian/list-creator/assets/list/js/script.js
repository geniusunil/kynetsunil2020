

// Function to reveal lightbox and adding YouTube autoplay
var opendiv ='';
var openvideo = '';
function revealVideo(div,video_id) {
    if(opendiv !='' && openvideo !=''){
        hideVideo(opendiv,openvideo);
    }
    var video = jQuery('#'+video_id).find('iframe').attr('src');
    opendiv = div;
    openvideo = video_id;
    var src = video+'&autoplay=1'; // adding autoplay to the URL
    jQuery('#'+video_id).find('iframe').attr('src',src);
    document.getElementById(div).style.display = 'block';
}

// Hiding the lightbox and removing YouTube autoplay
function hideVideo(div,video_id) {
    var video = jQuery('#'+video_id).find('iframe').attr('src');
    var cleaned = video.replace('&autoplay=1','');
    jQuery('#'+video_id).find('iframe').attr('src',cleaned);
    document.getElementById(div).style.display = 'none';
    opendiv ='';
    openvideo = '';
}

jQuery(document).ready(function($) {
    var owl = $('.owl-carousel.car1');
    owl.owlCarousel({
        stagePadding: 50,
        margin: 10,
        nav: false,
        pagination: false,
        loop: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 6
            }
        }
    })

    var owl1 = $('.owl-carousel.car2');
    owl1.owlCarousel({
        stagePadding: 50,
        margin: 20,
        nav: false,
        pagination: false,
        loop: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 5
            }
        }
    });
    var listId = jQuery('#yarrp_container').data('id');
    jQuery('.owlitemlink').attr('data-id',listId);
    jQuery("[data-fancybox]").fancybox({
        beforeShow: function( instance, slide ) {
            var a = slide.opts.$orig
            var id = jQuery(a).data('item');
            var title = jQuery(a).data('title');
            jQuery('#reportModal #item_name').text(title);
            jQuery('#reportModal #report_list_item').val(id);



        },
        afterShow:function (instance,slide) {

           jQuery('#report-submit').on('submit',function (e) {
               e.preventDefault();
               var name=$('select[name="reason"]').val();
               if(name.length == 0){
                   $('.rederror').text('Please select a reason');
               } else {
                   var data = jQuery('#report-submit').serialize();
                   jQuery.ajax({
                       url: objData.ajaxUrl,
                       type: 'post',
                       data: data + '&action=report_list_item',
                       beforeSend:function(){
                         jQuery('#report-submit .report-field .btn').addClass('loading');
                       },
                       success: function (data) {
                           data = jQuery.parseJSON(data);
                           jQuery('#report-submit .report-field .btn').removeClass('loading');
                           jQuery('#report-success').html(data.msg);
                           if(data.type=='yes') {
                               jQuery('#report-submit select').val('');
                               jQuery('#report-submit textarea').val('');
                           }
                           setTimeout(function () {
                               jQuery('#report-success').html('');
                               if(data.type=='yes'){
                                   $.fancybox.close();
                               }
                           },3000)


                       }
                   })
               }

           })
        }
    });
});
jQuery(function() {

    var $el, $ps, $up, totalHeight;

    jQuery(".readbutton").click(function() {

        // IE 7 doesn't even get this far. I didn't feel like dicking with it.

        totalHeight = 0

        $el = jQuery(this);
        $p  = $el.parent();
        $up = jQuery('#list_main_contnet');
        $ps = $up.find("p:not('.read-more')");

        // measure how tall inside should be by adding together heights of all inside paragraphs (except read-more paragraph)
        $ps.each(function() {
            totalHeight += jQuery(this).outerHeight(true);
            // console.log({totalHeight});
            // FAIL totalHeight += $(this).css("margin-bottom");
        });

        $up
            .css({
                // Set height to prevent instant jumpdown when max height is removed
                "height": $up.height(),
                "max-height": 9999
            })
            .animate({
                "height": totalHeight
            });

        // fade out read-more
        $p.fadeOut();

        // prevent jump-down
        return false;

    });
    var footer  = jQuery('.comparison_fix_footer');
    
    
    jQuery(window).scroll(function () {
        var reference  = jQuery('#show_comprison_footer');
        var sticky = reference.offset().top ;
        var top = jQuery(window).scrollTop();
       /*  console.log({footer,reference,sticky});
        console.log({top}); */

        if (top >= sticky) {
            footer.addClass("stickyshow")
        } else {
            footer.removeClass("stickyshow");
        }
    })

});



jQuery("#social_fb_comments .toggle").click(function(){
    jQuery('#social_fb_comments').toggleClass("show");
});

function stackGalleryReady(){
    //function called when component is ready to receive public method calls
    //console.log('stackGalleryReady');
}

function detailActivated(){
    //function called when prettyphoto (in this case) is being triggered (in which case slideshow if is on, automatically stops, then later when prettyphoto is closed, slideshow is resumed)
    //console.log('detailActivated');
}

function detailClosed(){
    //function called when prettyphoto (in this case) is closed
    //console.log('detailClosed');
}

function beforeSlideChange(slideNum){
    //function called before slide change (plus ORIGINAL! slide number returned)
    //(ORIGINAL slide number is slide number in unmodified stack from the bottom as slides are listed in html '.componentPlaylist' element, 1st slide from the bottom = 0 slide number, second slide from the bottom = 1 slide number, etc...)
    //console.log('beforeSlideChange, slideNum = ', slideNum);
}

function afterSlideChange(slideNum){
    //function called after slide change (plus ORIGINAL! slide number returned)
    //console.log('afterSlideChange, slideNum = ', slideNum);
}

// SETTINGS
var stack_settings = {
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

//gallery instances
var gallery1;

jQuery(document).ready(function($) {

    //init component
    var galleries ={}
    jQuery('.componentWrapper').each(function (ind) {
        var id = jQuery(this).attr('id');
        galleries[ind] = $('#'+id).stackGallery(stack_settings);
        // $("#componentWrapper a[data-rel^='prettyPhoto']").prettyPhoto({theme:'pp_default',
        //     callback: function(){galleries[ind].checkSlideshow2();}/* Called when prettyPhoto is closed */});
    })
    stack_settings = null;


});


