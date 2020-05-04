
jQuery(document).ready(function () {
		jQuery(".wp-editor-area").attr("placeholder", "What is the product about?   How do you position yourself against your competitors?");

    var currentRequest = null;

    jQuery(document).on('change','#features_category',function () {

        var val = jQuery(this).val();

        var post = '140701';

        currentRequest = jQuery.ajax({

            type:'post',

            url: WPURLS.siteurl+'/wp-admin/admin-ajax.php',

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

		
	jQuery(document).on('click','.bubbleFeature',function () {	
//			console.log(event.srcElement.innerText);
		//	jQuery('.bubble_insert').removeClass('bubble_insert');
		var slides = document.getElementsByClassName("bubble_insert");
		for(var i = 0; i < slides.length; i++)
		{
		   if(slides.item(i).value != '' )
			   {
//				   console.log("woringke");
				  jQuery(slides.item(i)).removeClass('bubble_insert');
			   }
		}

		 jQuery('.bubble_insert').val(event.srcElement.innerText);

//				console.log("input crewqated");
				var li= document.createElement("li");
				li.classList.add("item_list");
				var input= document.createElement("input");
				input.classList.add("software_search1","bubble_insert");
				input.setAttribute("type","text");
				input.setAttribute("name","new_features_list[]");
		 input.setAttribute("id","software_search1");
				input.setAttribute("value","");
				input.setAttribute("autocomplete","off");
				autocomplete_software(input, countries);
				li.append(input);
				var span = document.createElement("span");
				span.classList.add("remove_cat_feature");
				span.innerText = "X";
				li.append(span);
				jQuery('.features_list_container').find('ul').append(li)
//				console.log("new input created");

	});
	
	 jQuery(document).on('click','.remove_video_list',function () {

         jQuery(this).closest('p').remove();
	
    })
	
	
	
	 jQuery(document).on('click','.image_box',function () {		 
		 jQuery('.box_url').removeClass('box_url');
		 
		 
//		console.log(event.target);
//		console.log(event.target.getAttribute("data-url"));	 
		var li= document.createElement("p");
		li.classList.add("item_list");
		var input= document.createElement("input");
		input.classList.add("box_url","video_url");
		input.setAttribute("type","text");
		input.setAttribute("name","new_video_list[]");
		input.setAttribute("value","");
		li.append(input);
		var span = document.createElement("span");
		span.classList.add("remove_video_list");
		span.innerText = "X";
		li.append(span);
     	jQuery('.videos_list').append(li);
 
		var url_list = jQuery('.box_url').val("https://www.youtube.com/watch?v="+event.target.getAttribute("data-url"));
//		 console.log(url_list); 
	    var abs = jQuery(".box_url").append('<a href = https://www.youtube.com/watch?v='+event.target.getAttribute("data-url")+'>');
	 
//	  console.log(abs);
	
 });

	 jQuery(document).on('click','.add_new_item_feature',function () {
		jQuery('.bubble_insert').removeClass('bubble_insert');
		var li= document.createElement("li");
		li.classList.add("item_list");
		var input= document.createElement("input");
		input.classList.add("software_search1","bubble_insert");
		input.setAttribute("type","text");
		input.setAttribute("name","new_features_list[]");
		  input.setAttribute("id","software_search1");
		input.setAttribute("value","");
		input.setAttribute("autocomplete","off");
		
		autocomplete_software(input, search_list);
		li.append(input);
		var span = document.createElement("span");
		span.classList.add("remove_cat_feature");
		span.innerText = "X";
		li.append(span);
        jQuery('.features_list_container').find('ul').append(li);	 
	 })
	
	
   jQuery(document).ready(function () {

 
    populateBubbles();
    })

    jQuery(".selection").click(function (){
      setTimeout(function() {
        populateBubbles();
    }, (1 * 1000));
     

    })

    jQuery(".values").click(function (){
      setTimeout(function() {
        populateBubbles();
    }, (1 * 1000));
     

    })
	
    function populateBubbles(){
      jQuery('#features').show();
			var allInputs = jQuery(".selection .values input").map(function() {
			return $(this).val();
			}).get();
		
		var data = {
		'action': 'get_bubbles',
		'list_id':allInputs,

		};
		var ajaxurl = WPURLS.siteurl+"/wp-admin/admin-ajax.php";
		jQuery.ajax({
		async: false,
		type: "POST",
		url:ajaxurl,
		data: data,
		success: function(data){
	
			var obj = data;
			jQuery('#bubble_feature_group').html('');	
			jQuery("#features").append("<div id='bubble_feature_group'></div>");	
			for (var key in obj) {
			  if (obj.hasOwnProperty(key)) {
				var val = obj[key];

				  jQuery("#features").find('#bubble_feature_group').append("<span class='bubbleFeature'>"+key+"</span>");
			  	}

			}	
//			console.log(obj);
				
			},
				
			});

    }
	

    if(jQuery('#features').length) {
        var options = {
            valueNames: ['name']
        };
        var userList = new List('features', options);

    }
});
function autocomplete_software(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
//  console.log('running');
//  console.log(inp[0]);
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}

/*An array containing all the country names in the world:*/
var countries =["abc","def"];



/* jQuery(document).ready(function () {
  if($('#pricing_id').length){ //check if pricing_id exists on the page or not, this is working only for update_software page
    jQuery('html, body').animate({
      scrollTop: $('#pricing_id').offset().top //this line crashes on pages other than update_software. Add an if condition so that it does not throw an error.
  }, 'slow');
  }
    
});	 */
	
	


