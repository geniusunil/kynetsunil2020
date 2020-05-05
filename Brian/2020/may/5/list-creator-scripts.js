
/* =========================================================

 * bootstrap-modal.js v1.4.0

 * http://twitter.github.com/bootstrap/javascript.html#modal

 * =========================================================

 * Copyright 2011 Twitter, Inc.

 *

 * Licensed under the Apache License, Version 2.0 (the "License");

 * you may not use this file except in compliance with the License.

 * You may obtain a copy of the License at

 *

 * http://www.apache.org/licenses/LICENSE-2.0

 *

 * Unless required by applicable law or agreed to in writing, software

 * distributed under the License is distributed on an "AS IS" BASIS,

 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.

 * See the License for the specific language governing permissions and

 * limitations under the License.

 * ========================================================= */



jQuery(document).ready(function () 
{
	jQuery('.gallery-item-compare #carousel').flexslider(
	{
		animation: "slide",

		controlNav: false,

		animationLoop: false,

		slideshow: false,

		itemWidth: 100,

		itemMargin: 5,

		asNavFor: '#slider'
	});



	jQuery('.gallery-item-compare #slider').flexslider({

		animation: "slide",

		controlNav: false,

		animationLoop: false,

		slideshow: false,

		smoothHeight: true,

		sync: "#carousel"
	});
	
	jQuery('.gallery-item-compare #carousel2').flexslider(
	{
		animation: "slide",

		controlNav: false,

		animationLoop: false,

		slideshow: false,

		itemWidth: 100,

		itemMargin: 5,

		asNavFor: '#slider2'
	});



	jQuery('.gallery-item-compare #slider2').flexslider({

		animation: "slide",

		controlNav: false,

		animationLoop: false,

		slideshow: false,

		smoothHeight: true,

		sync: "#carousel2"
    });
    
  
});

!function( $ ){



    "use strict"



    /* CSS TRANSITION SUPPORT (https://gist.github.com/373874)

     * ======================================================= */



    var transitionEnd
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
	// console.log(parms.lang[0]);
		// end of get location from url 



    $(document).ready(function () {



        $.support.transition = (function () {

            var thisBody = document.body || document.documentElement

                , thisStyle = thisBody.style

                , support = thisStyle.transition !== undefined || thisStyle.WebkitTransition !== undefined || thisStyle.MozTransition !== undefined || thisStyle.MsTransition !== undefined || thisStyle.OTransition !== undefined

            return support

        })()



        // set CSS transition event type

        if ( $.support.transition ) {

            transitionEnd = "TransitionEnd"

            if ( $.browser.webkit ) {

                transitionEnd = "webkitTransitionEnd"

            } else if ( $.browser.mozilla ) {

                transitionEnd = "transitionend"

            } else if ( $.browser.opera ) {

                transitionEnd = "oTransitionEnd"

            }

        }

        
            	

    })

    /* MODAL PUBLIC CLASS DEFINITION

     * ============================= */
	var Modal = function ( content, options ) {

        this.settings = $.extend({}, $.fn.modal.defaults, options)

        this.$element = $(content)

            .delegate('.close', 'click.modal', $.proxy(this.hide, this))



        if ( this.settings.show ) {

            this.show()

        }



        return this

    }



    Modal.prototype = {



        toggle: function () {

            return this[!this.isShown ? 'show' : 'hide']()

        }



        , show: function () {

            var that = this

            this.isShown = true

            this.$element.trigger('show')



            escape.call(this)

            backdrop.call(this, function () {

                var transition = $.support.transition && that.$element.hasClass('fade')



                that.$element

                    .appendTo(document.body)

                    .show()



                if (transition) {

                    that.$element[0].offsetWidth // force reflow

                }



                that.$element.addClass('in')



                transition ?

                    that.$element.one(transitionEnd, function () { that.$element.trigger('shown') }) :

                    that.$element.trigger('shown')



            })



            return this

        }



        , hide: function (e) {

            e && e.preventDefault()



            if ( !this.isShown ) {

                return this

            }



            var that = this

            this.isShown = false



            escape.call(this)



            this.$element

                .trigger('hide')

                .removeClass('in')



            $.support.transition && this.$element.hasClass('fade') ?

                hideWithTransition.call(this) :

                hideModal.call(this)



            return this

        }



    }





    /* MODAL PRIVATE METHODS

     * ===================== */



    function hideWithTransition() {

        // firefox drops transitionEnd events :{o

        var that = this

            , timeout = setTimeout(function () {

            that.$element.unbind(transitionEnd)

            hideModal.call(that)

        }, 500)



        this.$element.one(transitionEnd, function () {

            clearTimeout(timeout)

            hideModal.call(that)

        })

    }



    function hideModal (that) {

        this.$element

            .hide()

            .trigger('hidden')



        backdrop.call(this)

    }



    function backdrop ( callback ) {

        var that = this

            , animate = this.$element.hasClass('fade') ? 'fade' : ''

        if ( this.isShown && this.settings.backdrop ) {

            var doAnimate = $.support.transition && animate



            this.$backdrop = $('<div class="modal-backdrop ' + animate + '" />')

                .appendTo(document.body)



            if ( this.settings.backdrop != 'static' ) {

                this.$backdrop.click($.proxy(this.hide, this))

            }



            if ( doAnimate ) {

                this.$backdrop[0].offsetWidth // force reflow

            }



            this.$backdrop.addClass('in')



            doAnimate ?

                this.$backdrop.one(transitionEnd, callback) :

                callback()



        } else if ( !this.isShown && this.$backdrop ) {

            this.$backdrop.removeClass('in')



            $.support.transition && this.$element.hasClass('fade')?

                this.$backdrop.one(transitionEnd, $.proxy(removeBackdrop, this)) :

                removeBackdrop.call(this)



        } else if ( callback ) {

            callback()

        }

    }



    function removeBackdrop() {

        this.$backdrop.remove()

        this.$backdrop = null

    }



    function escape() {

        var that = this

        if ( this.isShown && this.settings.keyboard ) {

            $(document).bind('keyup.modal', function ( e ) {

                if ( e.which == 27 ) {

                    that.hide()

                }

            })

        } else if ( !this.isShown ) {

            $(document).unbind('keyup.modal')

        }

    }





    /* MODAL PLUGIN DEFINITION

     * ======================= */



    $.fn.modal = function ( options ) {

        var modal = this.data('modal')



        if (!modal) {



            if (typeof options == 'string') {

                options = {

                    show: /show|toggle/.test(options)

                }

            }



            return this.each(function () {

                $(this).data('modal', new Modal(this, options))

            })

        }



        if ( options === true ) {

            return modal

        }



        if ( typeof options == 'string' ) {

            modal[options]()

        } else if ( modal ) {

            modal.toggle()

        }



        return this

    }



    $.fn.modal.Modal = Modal



    $.fn.modal.defaults = {

        backdrop: false

        , keyboard: false

        , show: false

    }





    /* MODAL DATA- IMPLEMENTATION

     * ========================== */


    console.log("checkpoint 2");
    $(document).ready(function () {

        $('body').delegate('[data-controls-modal]', 'click', function (e) {

            e.preventDefault()

            var $this = $(this).data('show', true)

            $('#' + $this.attr('data-controls-modal')).modal( $this.data() )

        })

    })



}( window.jQuery || window.ender );
console.log("checkpoint 3");

jQuery(function($){

    /** Open list/Ranked List voting  (up) */

    var setVoteCount = function(data, isAdd){

        var current = $(".zf-vote_count[data-zf-post-id='"+data.post_id+"']").attr('data-zf-votes');

        if (data.vote_type_action == 'undo')

            var next = isAdd? Number(current) - 1: Number(current) +1;

        else if (data.vote_type_action == 'redo')

            var next = isAdd? Number(current) + 2: Number(current) -2;

        else

            var next = isAdd? Number(current) + 1: Number(current) -1;

        next = next.toFixed(1);

        $(".zf-vote_count[data-zf-post-id='"+data.post_id+"']").attr('data-zf-votes', next);

        $(".zf-vote_count[data-zf-post-id='"+data.post_id+"'] .zf-vote_number").html( next );

    }

    var setCpVoteCount = function(data, isAdd){

        var current = $(".cp-vote_count[data-cp-coup-id='"+data.coup_id+"']").attr('data-cp-votes');

        if (data.vote_type_action == 'undo')

            var next = isAdd? Number(current) - 1: Number(current) +1;

        else if (data.vote_type_action == 'redo')

            var next = isAdd? Number(current) + 2: Number(current) -2;

        else

            var next = isAdd? Number(current) + 1: Number(current) -1;

        next = next.toFixed(1);

        $(".cp-vote_count[data-cp-coup-id='"+data.coup_id+"']").attr('data-cp-votes', next);

        $(".cp-vote_count[data-cp-coup-id='"+data.coup_id+"'] .cp-vote_number").html( next );

    }

    $(document).on("click", ".zf_vote_up", function(){



        $(this).closest('.zf-item-vote').addClass('zf-loading');



        var zf_post_id = $(this).closest('.zf-item-vote').attr("data-zf-post-id");

        var zf_post_parent_id = $(this).closest('.zf-item-vote').attr("data-zf-post-parent-id");


        console.log("before ajax vote up");
        $.ajax({

            url: gMesLCData.ajaxUrl,

            type: 'POST',

            data: {post_id: zf_post_id, post_parent_id: zf_post_parent_id, vote_type: 'up', action: 'mes-lc-li-vote',location:parms.lang[0]},

            dataType: 'json',

            success: function (data) {
                console.log("success vote up");
                if (data.voted) setVoteCount(data, true);

                $(".zf-vote_count[data-zf-post-id='"+data.post_id+"']").closest('.zf-item-vote').removeClass('zf-loading');

            }

        });



    });





    /** Open list/Ranked List voting  (down) */

    $(document).on("click", ".zf_vote_down", function(e){
        // console.log(parms.lang[0]);


        $(this).closest('.zf-item-vote').addClass('zf-loading');



        var zf_post_id = $(this).closest('.zf-item-vote').attr("data-zf-post-id");

        var zf_post_parent_id = $(this).closest('.zf-item-vote').attr("data-zf-post-parent-id");



        $.ajax({

            url: gMesLCData.ajaxUrl,

            type: 'POST',

            data: {post_id: zf_post_id, post_parent_id: zf_post_parent_id, vote_type: 'down', action: 'mes-lc-li-vote',location:parms.lang[0]},

            dataType: 'json',

            success: function (data) {

                if (data.voted) setVoteCount(data);

                $(".zf-vote_count[data-zf-post-id='"+data.post_id+"']").closest('.zf-item-vote').removeClass('zf-loading');

            }

        });



    });

    $(document).on("click", ".cp_vote_down", function(e){
        // console.log(parms.lang[0]);


        $(this).closest('.cp-item-vote').addClass('cp-loading');



        var cp_coup_id = $(this).attr("data-cp-coup-id");

        var cp_post_id = $(this).attr("data-cp-post-id");


        console.log("before coupon vote down");

        $.ajax({

            url: gMesLCData.ajaxUrl,

            type: 'POST',

            data: {post_id: cp_post_id, coup_id: cp_coup_id, vote_type: 'down', action: 'mes-lc-cp-vote'},

            dataType: 'json',

            success: function (data) {
                console.log("success vote down");
                if (data.voted) setCpVoteCount(data, false);
                $(".cp-vote_count[data-cp-coup-id='"+data.coup_id+"']").closest('.cp-item-vote').removeClass('cp-loading');

            }

        });



    });


    $(document).on("click", ".cp_vote_up", function(){



        $(this).closest('.cp-item-vote').addClass('cp-loading');



        var cp_coup_id = $(this).attr("data-cp-coup-id");

        var cp_post_id = $(this).attr("data-cp-post-id");


        console.log("before coupon vote up");
        $.ajax({

            url: gMesLCData.ajaxUrl,

            type: 'POST',

            data: {post_id: cp_post_id, coup_id: cp_coup_id, vote_type: 'up', action: 'mes-lc-cp-vote'},

            dataType: 'json',

            success: function (data) {
                console.log("success vote up");
                if (data.voted) setCpVoteCount(data, true);

                $(".cp-vote_count[data-cp-coup-id='"+data.coup_id+"']").closest('.cp-item-vote').removeClass('cp-loading');

            }

        });



    });




    $('body').on('click','#go-to-user-comments',function(){



        $('html, body').animate({

            scrollTop: $(".rwp-users-reviews").offset().top

        }, 2000);



    })



    $('body').on('click','#tags_tag',function(){

        $('.filter-tag-list').show();



    })

    $('body').on('click','.tag_item',function(){

        $('#tags').addTag($(this).text());

        $(this).remove();

    })





})



function add_tag_back_to_list( tag ){



    jQuery('.filter-tag-list').append('<span class="tag_item">'+tag+'</span>');

}





jQuery(function($){

    var selectorListItemLink = '.mes-lc-li-link';

    var selectorListItemDownload = '.mes-lc-li-down';

    var selectorListContainer = '#zombify-main-section-front-new';

    var selectorListItemHeader = '.item-header-cnt';

    var selectorListItemVote = '.zf-item-vote';

    var selectorListItem = '.zf-list_item';



    var dataListItemId = 'data-zf-post-id';

    var dataListId = 'data-zf-post-parent-id';

    var paramListId = 'listid';

    var refferal_type = '.ls_referal_link';

    var refferal_param = 'parameter';

    var refferal_id_orig = 'rid';

    var refferal_id = 'id';





    var appentQueryParam = function(qs, listId){

        if (listId) return (qs? qs + '&':'') + paramListId + '=' + encodeURIComponent(listId);

        else return qs;

    };

    var appentQueryParamRefer = function(qs, listId,listParam){

        if (listId) return (qs? qs + '&':'') + listParam + '=' + encodeURIComponent(listId);

        else return qs;

    };



    $(selectorListItemLink, selectorListContainer).click(function(e){

        e.preventDefault();

        var $a = $(this).clone();

        var qs = $a.prop('search');

        console.log(qs);

        var listId = $a.attr(dataListId);

        $a.prop('search', appentQueryParam(qs, listId));
        // window.open(url,'_blank');
        window.location.href = $a.prop('href');

    });

    $(refferal_type).click(function(e){

        e.preventDefault();

        var $a = $(this).clone();

        var qs = $a.prop('search');

        var listIdorig = $a.data(refferal_id_orig);

        if(typeof listIdorig != "undefined" && listIdorig != ''){

            var  listId = listIdorig;

        } else{

            var listId = $a.data(refferal_id);

        }



        var listParam = $a.data(refferal_param);



        $a.prop('search', appentQueryParamRefer(qs, listId,listParam));

        window.location.href = $a.prop('href');

    });

/*     $(selectorListItemLink, selectorListContainer).click(function(e){

        e.preventDefault();

        var $a = $(this).clone();

        var qs = $a.prop('search');

        console.log(qs);

        var listId = $a.attr(dataListId);

        $a.prop('search', appentQueryParam(qs, listId));

        // window.location.href = $a.prop('href');

    }); */ // its a duplicate code from same file



    $(selectorListItemDownload).click(function(e){

        var $this = $(this);

        var $wrapper = $(selectorListItemHeader);

        if (!$wrapper.length){

            $wrapper = $this.parents(selectorListItem).find(selectorListItemVote);

        }

        var url = $this.attr('href');

        if ($wrapper.length){

            var itemId = $wrapper.attr(dataListItemId);

            var listId = $wrapper.attr(dataListId);

            if (itemId && listId){

                //e.preventDefault();

                $.ajax(

                    gMesLCData.ajaxUrl,

                    {

                        method: 'POST',

                        data: {post_id: itemId, post_parent_id: listId, vote_type: 'download', action: 'mes-lc-li-vote',location:parms.lang[0]},

                        complete: function () {

                            //window.open(url);

                        }

                    }

                );

            }

        }

    });

    $(document).on('click','.compare_close',function(e){

        jQuery(this).closest('.comparison_fix_footer').remove();

    });

    $(document).on('click','.update_list_modified',function(e){

        var $this = $(this);



        var itemId = $this.attr(dataListItemId);

        var listId = $this.attr(dataListId);



        if (itemId && listId){

            e.preventDefault();

            $.ajax(

                gMesLCData.ajaxUrl,

                {

                    method: 'POST',

                    data: {post_id: itemId, post_parent_id: listId, vote_type: 'download', action: 'mes-lc-li-vote',location:parms.lang[0]},

                    complete: function () {

                        window.location.href = $this.attr('href');

                    }

                }

            );

        }



    });

    $(document).on('click','.update_list_modified_link',function(e){
        //  e.preventDefault();
       /* window.open("https://www.w3schools.com"); */
        console.log(parms.lang[0]);
        var $this = $(this);



        var itemId = $this.attr(dataListItemId);

        var listId = $this.attr(dataListId);



        if (itemId && listId){

            e.preventDefault();

            $.ajax(

                gMesLCData.ajaxUrl,

                {

                    method: 'POST',

                    data: {post_id: itemId, post_parent_id: listId, vote_type: 'read', action: 'mes-lc-li-vote',location:parms.lang[0]},

                    complete: function () {

                        window.location.href = $this.attr('href');

                    }

                }

            );

        }


    
    });

});

console.log("working3");
jQuery(document).ready(function() {

    jQuery(document).on('click','.new-comparison-btn',function (e) {

        e.preventDefault();

        var $this = jQuery(this);

        var id = $this.data('id');

        var secondary = $this.data('secondary');

        jQuery.ajax({

            url: gMesLCData.ajaxUrl,

            type: 'POST',

            data:{

                action:'create_new_comparison',

                id:id,

                secondary:secondary

            },

            beforeSend:function(){

                jQuery('#loader-animate').show();

            },

            success:function (res) {

                var res = jQuery.parseJSON(res)



                // if(res['status']==1){

                //     window.location.href = res['url'];

                // }

            }

        })

    })




})
console.log("checkpoint 1");
jQuery(function($) {
	console.log("inside slider block");
    // The slider being synced must be initialized first

    jQuery('.gallery-item #carousel').flexslider({

        animation: "slide",

        controlNav: false,

        animationLoop: false,

        slideshow: false,

        itemWidth: 100,

        itemMargin: 5,

        asNavFor: '#slider'

    });



    jQuery('.gallery-item #slider').flexslider({

        animation: "slide",

        controlNav: false,

        animationLoop: false,

        slideshow: false,

        smoothHeight: true,

        sync: "#carousel"

    });

    jQuery('.slider-compare').flexslider({

        animation: "slide",

        controlNav: false,

        animationLoop: false,

        slideshow: false,

    });

    jQuery('.cr-alternate').flexslider({

        animation: "slide",

        animationLoop: false,

        itemWidth: 150,

        itemMargin: 20,

        controlNav: false

    });

});

jQuery(function($) {
  var resizeEnd;

    jQuery(document).on('click','.get_compare_obj',function (e) {



        var id = jQuery(this).data('val');

        if(id != '') {

            jQuery.ajax({

                url: gMesLCData.ajaxUrl,

                type: 'POST',

                data: {

                    action:'add_comparison_item',

                    id: id,

                    comitms: jQuery.parseJSON(window.comparedItems),

                    comid:window.comparisonId



                },

                beforeSend:function(){

                    jQuery('#loader-animate').show();

                },

                success: function (res) {

                    var res = jQuery.parseJSON(res)

                    if(res['status']==1){

                        window.location.href = res['url'];

                    }



                }

            });

        }

    });

    jQuery(document).on('click','.remove_compare_project',function (e) {

        var id = jQuery(this).data('id');

        if(id != '') {

            jQuery.ajax({

                url: gMesLCData.ajaxUrl,

                type: 'POST',

                data: {

                    action:'remove_comparison_item',

                    id: id,

                    comitms: jQuery.parseJSON(window.comparedItems),

                    comid:window.comparisonId



                },

                beforeSend:function(){

                    jQuery('#loader-animate').show();

                },

                success: function (res) {

                    var res = jQuery.parseJSON(res)

                    if(res['status']==1){

                        window.location.href = res['url'];

                    }





                }

            });

        }

    });

    if(jQuery('#add-review-list').length) {

        jQuery('.comparison-sidebar').stick_in_parent();

        var options = {

            valueNames: [ 'name' ]

        };

        var userList = new List('add-review-list', options);

        jQuery('#addItemsBox').modal({"backdrop":"static","show":false});

        var lastId,

            topMenu = $("#sidebar-main-menu"),

            topMenuHeight = $(".column-hidden").outerHeight()+15,

            // All list items

            menuItems = topMenu.find("a"),

            // Anchors corresponding to menu items

            scrollItems = menuItems.map(function(){

                var item = $($(this).attr("href"));

                if (item.length) { return item; }

            });



// Bind click handler to menu items

// so we can get a fancy scroll animation

        menuItems.click(function(e){



            var href = $(this).attr("href");

            console.log( $(href).offset())

            if(href !='#overview'){

                var topMenuHeight1 = $(".column-hidden").outerHeight();

                console.log(topMenuHeight1);



                var off = $(href).offset().top-topMenuHeight1;

            } else{

                var off = $(href).offset().top;

            }

            // console.log(off)

            var  offsetTop = href === "#" ? 0 : off;

            setTimeout(function () {

                $('html, body').stop().animate({

                    scrollTop: off

                }, 300);

            },100);

            return true;

        });



// Bind to scroll

        $(window).scroll(function(){

            // Get container scroll position

            var  topMenuHeight1 = $(".column-hidden").outerHeight()+15;

            var fromTop = $(this).scrollTop()+topMenuHeight1;

            // Get id of current scroll item

            var cur = scrollItems.map(function(){

                if ($(this).offset().top < fromTop)

                    return this;

            });

            // Get the id of the current element

            cur = cur[cur.length-1];

            var id = cur && cur.length ? cur[0].id : "";



            if (lastId !== id) {

                lastId = id;

                // Set/remove active class

                menuItems

                    .parent().removeClass("active")

                    .end().filter("[href='#"+id+"']").parent().addClass("active");

            }

        });



        var navbar = jQuery('.column-hidden');

        var width = jQuery('.column-head').width();

        navbar.css('width',width+'px');

        var navbarref = jQuery('#overview');

        var  height = navbarref.outerHeight(true);

        var sticky = navbarref.offset().top + height + 20;

        jQuery(window).scroll(function () {

            var top = jQuery(window).scrollTop();

            if (top >= sticky) {

                navbar.addClass("sticky")

            } else {

                navbar.removeClass("sticky");

            }

        })

    }

    if(jQuery('#review-fix-header').length) {

        var navbar = jQuery('#review-fix-header');

        jQuery( "#overview" ).readmore({

            speed: 150,

            collapsedHeight:180,

            lessLink: '<a href="#" class="acc-read read-less">Read Less</a>',

            moreLink: '<a href="#" class="acc-read read-more">Read More</a>',

            afterToggle: function(trigger, element, expanded) {

                if(! expanded) { // The "Close" link was clicked

                    $('html, body').animate( { scrollTop: element.offset().top }, {duration: 100 } );

                }

            }

        });

        jQuery(window).scroll(function () {

            var top = jQuery(window).scrollTop();

            if (top >= 100) {

                navbar.addClass("sticky")

            } else {

                navbar.removeClass("sticky");

            }

        });

        // Cache selectors

        var lastId,

            topMenu = $("#review-fix-header"),

            topMenuHeight = topMenu.outerHeight()+15,

            // All list items

            menuItems = topMenu.find('.text-sec').find("a"),

            // Anchors corresponding to menu items

            scrollItems = menuItems.map(function(){

                var item = $($(this).attr("href"));

                if (item.length) { return item; }

            });



// Bind click handler to menu items

// so we can get a fancy scroll animation

        menuItems.click(function(e){

            // e.preventDefault();

            var topMenuHeight = topMenu.outerHeight()+15;

            var href = $(this).attr("href"),

                offsetTop = href === "#" ? 0 : $(href).offset().top-topMenuHeight+1;

            setTimeout(function () {

                $('html, body').stop().animate({

                    scrollTop: offsetTop

                }, 300);

            },100);

            return true;

        });



// Bind to scroll

        $(window).scroll(function(){

            // Get container scroll position

            var topMenuHeight = topMenu.outerHeight()+15;

            var fromTop = $(this).scrollTop()+topMenuHeight;



            // Get id of current scroll item

            var cur = scrollItems.map(function(){

                if ($(this).offset().top < fromTop)

                    return this;

            });

            // Get the id of the current element

            cur = cur[cur.length-1];

            var id = cur && cur.length ? cur[0].id : "";



            if (lastId !== id) {

                lastId = id;

                // Set/remove active class

                menuItems

                    .parent().removeClass("active")

                    .end().filter("[href='#"+id+"']").parent().addClass("active");

            }

        });

    }





    jQuery(document).on('click','a.barlink',function(e){

        e.preventDefault();



        var href = $(this).attr("href"),

            offsetTop = $(href).offset().top-20;

        setTimeout(function () {

            $('html, body').stop().animate({

                scrollTop: offsetTop

            }, 300);

        },100);

        // return true;

    });



});

function flexsliderResize(){

    if (jQuery('.flexslider').length > 0) {

        jQuery('.flexslider').each(function () {

            jQuery(this).data('flexslider').resize();

            console.log(jQuery(this))

        })



    }

}
jQuery( function() {
    jQuery('#loader-animate').hide();
    var availableListItems = {};
    var compareItems = {};
  
    var item = "";
    var id = "";
    var url = "";
    
		
    function split( val ) {
      return val.split( /,\s*/ );
    }
    var cache = {};
   
	var f = false;
jQuery( ".list-items-input" ).autocomplete({
    source:  function(request, response) {
        var r = [];
        var q = request.term;
        var qs = request.term.toLowerCase();
        var data = {
            'action': 'list_data',
            'q': q

        };
      
        jQuery.ajax({
            type: "POST",
            url: gMesLCData.ajaxUrl,
            dataType: "json",
            data: data,
            beforeSend:function(){
               // jQuery('#loader-animate').show();
            },
            success: function(datarec) {
                jQuery.each( datarec, function( key, val ) {
                    if (val.item.toLowerCase().indexOf(qs) != -1) {
                    r.push({
                        label: val.item,
                        value: val.item_id
                    });
                }
                  
                });
                console.log(r);
               // cache[term] = data;
                response(r);
               // jQuery('#loader-animate').hide();
               
            },
            error: function(datarec) {
                console.log(datarec);
            }
        });
        
       // console.log(r);
    },
    minLength: 2,
    select: function( event, ui ) {
        console.log(ui.item.value);
        event.preventDefault();
        jQuery(".list-items-input").val(ui.item.label);
        jQuery(".list-items-input").val(ui.item.label);
        id = ui.item.value;
        var data1 = {
            'action': 'list_compare_items',
            'item': ui.item.value
        }
        jQuery.ajax({
            type: "POST",
            url: gMesLCData.ajaxUrl,
            dataType: "json",
            data: data1,
            beforeSend:function(){
                jQuery('#loader-animate').show();
            },
            success: function(response) {
                jQuery.each( response, function( key, val ) {
                    compareItems[key] = val;
                });
                jQuery('#loader-animate').hide();
                jQuery( ".compare-items-input" ).autocomplete({
                    source:  function(request, response) {
                    var res = [];
                    var term_val = request.term.toLowerCase();
                    jQuery.each(compareItems, function(k, v) {
                        if (v.item.toLowerCase().indexOf(term_val) != -1) {
                        res.push({
                            label: v.item,
                            value: v.item_id
                        });
                        }
                    });
                    response(res);
                    },
                   select: function( event, ui ) {
                        event.preventDefault();
						
                        jQuery(".compare-items-input").val(ui.item.label);
                        jQuery(".compare-items-input").val(ui.item.label);
                        item = ui.item.value;
                        var data2 = {
                            'action': 'list_compare_button',
                            'item': ui.item.value,
                            'id': id
                        } 
                        jQuery.ajax({
                            type: "POST",
                            url: gMesLCData.ajaxUrl,
                            dataType: "json",
                            data: data2,
                            beforeSend:function(){
                                jQuery('#loader-animate').show();
                            },
                            success: function(response) {
                                jQuery('#loader-animate').hide();
                                jQuery("#dynamic-compare").attr('data-id', id);
                                jQuery("#dynamic-compare").attr('data-secondary', item);
                                jQuery("#dynamic-compare").attr('href', response);
                                console.log(response);
                            }
                        });
                   }
                });
                console.log(response);
            },
            error: function(response) {
                console.log(response);
            }
        });
    },
    focus: function(event, ui) {
        //event.preventDefault();
       // jQuery(".list-items-input").val(ui.item.label);
    }
  });
});
jQuery(document).on('click','a.full-list-toggle',function(e){
    e.preventDefault();
    jQuery(this).parent().find(".features-hidden").toggle();
    jQuery.fn.matchHeight._update();
    jQuery(this).hide();

});


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
	

jQuery(document).ready(function () {
    
var numItems = jQuery('.up-next .vid-item').length;
// console.log(numItems);

// var pagination_num = jQuery(".vid-item.active1").data("number");
// console.log(pagination_num);

jQuery('.pagination h6').html('<span class="pagination__current">1</span>/<span class="total">'+numItems+'</span>');

    
   jQuery(".arrow-right").bind("click", function (event) {    	 
        event.preventDefault();
       jQuery(this).parents(".vid-main-wrapper").find(".vid-list-container").stop().animate({
            scrollLeft: "+=140"
        }, 750);   
	
	   
    });
    jQuery(".arrow-left").bind("click", function (event) {
        event.preventDefault();
        jQuery(this).parents(".vid-main-wrapper").find(".vid-list-container").stop().animate({
            scrollLeft: "-=336"
        }, 750);
    });
});



jQuery(document).on('click','.pagination__next',function () {
   
    if(jQuery(this).hasClass('disabled')){
        console.log('disabled pgnxt');
    }
    else{    
    var pgnxt=this;
    jQuery('.up-next').css({ 'right': '0px','position' : 'relative', 'left': '' }).animate({
        'right' : '1260px'           
    }, 500, function() {
        // Animation complete.
        var activeelement = jQuery('.vid-item.active1');
        if(activeelement.next().length)
          activeelement.removeClass('active1').next().addClass('active1');
        else
          activeelement.removeClass('active1').closest('.up-next').find('> div:first').addClass('active1');      
          activeelement = jQuery('.vid-item.active');
          console.log(activeelement);

          var numItems = jQuery('.up-next .vid-item').length;
            console.log(numItems);
            var pagination_num = jQuery(".vid-item.active1").data("number");
            var pagination_count = pagination_num;
            // console.log(pagination_num);
            jQuery('.pagination h6').html('<span class="pagination__current">'+pagination_count+'</span>/<span class="total">'+numItems+'</span>');


          if(jQuery(activeelement[0]).next().length){
            if(jQuery(activeelement[1]).next().length){
                jQuery(activeelement[1]).next().addClass('active');
            }
            jQuery(activeelement[0]).removeClass('active');
          }
            
        else{ //don't show any next video
            //   console.log(activeelement.removeClass('active').closest('.up-next'));
              jQuery(activeelement[0]).removeClass('active');
              console.log(jQuery(pgnxt));
              jQuery(pgnxt).addClass('disabled');
            // activeelement.removeClass('active').closest('.up-next').find('> div:first').addClass('active').next().addClass('active');
          } 
          activeelement = jQuery('.vid-item.active1');
          if(activeelement.prev().length){
              jQuery('.pagination__prev').removeClass('disabled');
          }
    
          jQuery('.up-next').css({ 'right': '1260px','position' : 'relative' }).animate({
            'right' : '0px'
               
        },500);   
      });  
   jQuery('.transition').animate({

    top : "-=36%",
    opacity: 0.25
    
    
   }, 500, function() {


    // activeelement.removeClass('active').next().click();

    // Animation complete.
    // var hide_frame = ;
    console.log("data url");
    console.log( jQuery(".vid-item.active").data("url"));
    jQuery("#vid_frame1").hide(1000);
    // jQuery("#vid_frame1").attr('src',jQuery(".vid-item.active").data("url"));
    document.getElementById('vid_frame1').src=jQuery(".vid-item.active1").data("url");
    // document.getElementById("vid_frame1").contentDocument.location.reload(true);
    jQuery("#vid_frame1").show(1000);
    jQuery('.transition').animate({
       
        top : "+=36%",
        opacity: 1
        
        
       }, 500)



   }

  );

}


});




    // console.log("pagination__prev");
    
jQuery(document).on('click','.pagination__prev',function () {
    jQuery('.pagination__next').removeClass('disabled');
  


//     jQuery('.pagination h6').html('<span class="pagination__current">'+pagination_num+'</span>/<span class="total">'+numItems+'</span>');
// <span class="total">'+numItems+'</span>/

    

    console.log("pagination__prev working");
    if(jQuery(this).hasClass('disabled')){
        console.log('disabled pgprev');
    }
    else{
        var pgprev=this;
    jQuery('.up-next').css({ 'right': '0px','position' : 'relative', 'left': '' }).animate({
        'right' : '1260px'
           
    }, 500, function() {
        // Animation complete.
        var activeelement = jQuery('.vid-item.active1');
        if(activeelement.prev().length){
            activeelement.removeClass('active1').addClass('active').prev().addClass('active1');
            activeelement = jQuery('.vid-item.active1');

            var pagination_number = jQuery(".vid-item.active1").attr("data-number");
            var num= pagination_number;
            var numItems = jQuery('.up-next .vid-item').length;
            //   console.log(num);
            //   console.log(numItems);
            
                jQuery('.pagination h6').html('<span class="pagination__previ">'+num+'</span>/<span class="total">'+numItems+'</span>');
        
                
            jQuery('.pagination__next').removeClass('disabled');
            if(activeelement.prev().length){

            }
            else{
                jQuery(pgprev).addClass('disabled');
            }
            
            activeelement = jQuery('.vid-item.active');
            if(activeelement.length > 2){
                jQuery(activeelement[2]).removeClass('active');
            }
        }
          
       /*  else{
            activeelement.removeClass('active1').closest('.up-next').find('> div:first').addClass('active1');

        } */
      
         /*  activeelement = jQuery('.vid-item.active');
          console.log(activeelement);
          if(jQuery(activeelement[1]).next().length){
            jQuery(activeelement[1]).next().addClass('active');
            jQuery(activeelement[0]).removeClass('active');
          }
            
          else{
              console.log(activeelement.removeClass('active').closest('.up-next'));
              jQuery(pgprev).addClass('disabled');
            activeelement.removeClass('active').closest('.up-next').find('> div:first').addClass('active').next().addClass('active');
          }
          
          activeelement = jQuery('.vid-item.active')[0];
          if(activeelement.next().length){
              jQuery('.pagination__next').removeClass('disabled');
          } */
          jQuery('.up-next').css({ 'right': '1260px','position' : 'relative' }).animate({
            'right' : '0px'
               
        },500);   
      });  
   jQuery('.transition').animate({
       
    top : "-=36%",
    opacity: 0.25
    
    
   }, 500, function() {

    console.log("data url");
    console.log( jQuery(".vid-item.active").data("url"));
    jQuery("#vid_frame1").hide(1000);
    // jQuery("#vid_frame1").attr('src',jQuery(".vid-item.active").data("url"));
    document.getElementById('vid_frame1').src=jQuery(".vid-item.active1").data("url");
    // document.getElementById("vid_frame1").contentDocument.location.reload(true);
    jQuery("#vid_frame1").show(1000);
    jQuery('.transition').animate({
       
        top : "+=36%",
        opacity: 1
        
        
       }, 500)



   }

  );



    }
  });


  jQuery(document).on('click','.compre_data',function () {
      var checked = jQuery('.compre_data:checked');
      var thisid = jQuery(this).attr("data-link");
      console.log({checked});
      if(checked.length > 2){
            checked.each(function(index, value) {
            console.log((jQuery(value).attr("data-link")));
            console.log(thisid);
          if((jQuery(value).attr("data-link")) != thisid){
              checked.splice(index,1);
              jQuery(value).prop("checked", false);
              return false;
          }
        });
      }
      
      console.log("after");
      console.log({checked});
    console.log("fxn work");
    
    // var post_id1 = 148621;
    // var post_id2 = 148173;
    // console.log( jQuery(this).attr("data-link"));
      
    // var total=jQuery('input[name="compare"]:checked').length;

    var sel_arr = jQuery('input[name="compare"]:checked').map(function(){
        return jQuery(this).attr("data-link");
      }).get(); // <----
    //   console.log(sel_arr);
    
     /*  if(total == 2){
    
        var ids = new Array(jQuery(this).attr("data-link"));
//          console.log("ïds");
   console.log(ids);
        
         
       } */

    //   console.log(sel_arr.length);
   if(sel_arr.length == 2)
   {

    var data = {
        'action': 'generate_url',
        'dataType': 'json',
        'sel_arr' : sel_arr,
        };
        
    jQuery.ajax({
    type: "POST",
    url: gMesLCData.ajaxUrl,
    data: data,
    dataType: "json",
    success: function(data) {
        console.log(data);
        // console.log(data.title1);
        // console.log(data.title1);
        jQuery( ".cp-item-foot-2" ).attr("title",data.title2);
        jQuery( ".cp-item-foot-2" ).html( data.item_image2);
        jQuery( ".cp-item-foot-1" ).attr("title",data.title1);
        jQuery( ".cp-item-foot-1" ).html( data.item_image1);
        jQuery(".compare_btn .new-comparison-btn").attr("href", data.link);
        // console.log(data.post_id);
       
        

    }
    }
    );

}
else if(sel_arr.length > 2){
    sel_arr.shift(0);
    console.log("result");
    console.log(sel_arr);
}else{
  console.log("0element");
}
  




  });


