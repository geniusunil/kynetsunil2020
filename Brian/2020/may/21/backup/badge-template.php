<?php
get_header();
// $catobj = get_terms('list_categories');
global $wpdb;
$custom_post_type = 'list_items'; // define your custom post type slug here
$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", $custom_post_type ), ARRAY_A );
// print_r($results);        
// echo "count ".count($results);
// print_r($catobj);
?>



<div class="container">
    <div id="topsfbadges">
        <div class="design">
            <div class="title">
                <span>Top Software Badges</span>
            </div>
            <div>
                <div class="col-md-4">
                    <div class="dropdown">
                    <button id="selectSoft" onclick="myFunction1()" class="dropbtn">Select Software</button>
                    <div id="myDropdown" class="dropdown-content">
                        <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                        <?php foreach ($results as $co) {
                                echo "<span style='display: block;' post-id=" . $co['ID'] . " >" . $co['post_title'] . "</span>";
                              }?>
                    </div>
                    </div>
                </div>
                <div id="awardType" class="col-md-4">

                </div>
                
            </div>
        </div>
        <div class="preview">
        </div>
    </div>
</div>

<style>
/* Dropdown Button */
.dropbtn {
  background-color: #4CAF50;
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
  cursor: pointer;
}

/* Dropdown button on hover & focus */
.dropbtn:hover, .dropbtn:focus {
  background-color: #3e8e41;
}
.dropdown-content.show{
  max-height: 50vh;
  overflow: scroll;
}

/* The search field */
#myInput {
  margin-top:0;
  box-sizing: border-box;
  background-image: url(<?php echo esc_url(plugins_url('../image/search.ico', dirname(__FILE__))); ?>);
  background-position: 14px 12px;
  background-repeat: no-repeat;
  font-size: 16px;
  padding: 14px 20px 12px 45px;
  border: none;
  border-bottom: 1px solid #ddd;
}

/* The search field when it gets focus/clicked on */
#myInput:focus {outline: 3px solid #ddd;}

/* The container <div> - needed to position the dropdown content */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f6f6f6;
  min-width: 230px;
  border: 1px solid #ddd;
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {background-color: #f1f1f1}

/* Show the dropdown menu (use JS to add this class to the .dropdown-content container when the user clicks on the dropdown button) */
.show {display:block;}
</style>

<script>
/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction1() {

    event.target.nextElementSibling.classList.toggle("show");

}

const buttons = document.querySelectorAll("#myDropdown span")
for (const button of buttons) {
  button.addEventListener('click', function(event) {
   
    document.getElementById("selectSoft").innerText = event.target.innerText;
    post_id = event.target.getAttribute("post-id");
    document.getElementById("awardType").innerHTML = 'Please wait...';
    // document.getElementById("awardName").innerHTML = '';
    document.getElementById("awardType").innerHTML = '<div class="dropdown">'+
        '<button id="selectAward" onclick="myFunction1()" class="dropbtn">Select Award</button>'+
        '<div id="myDropdown2" class="dropdown-content">'+
        '<input type="text" placeholder="Search.." id="myInput2" onkeyup="filterFunction()">'+
        '<span style="display: block;" post-id='+post_id+' award-type="list-ranking" >List Rankings</span>'+
        '<span style="display: block;" post-id='+post_id+' award-type="best-for" >Best For</span>'+
        '<span style="display: block;" post-id='+post_id+' award-type="contender" >Contender</span>'+  
        '</div>'+
        '</div>';
        const awards = document.querySelectorAll("#myDropdown2 span")
        for (const button of awards) {
            button.addEventListener('click', function(event) {
            
              var awardType = event.target.getAttribute("award-type");
              post_id = event.target.getAttribute("post-id");

              jQuery.ajax(
              {
                url: '<?php echo admin_url('admin-ajax.php') ?>',
                type: 'POST',
                data: {post_id: post_id , award_type:awardType, action: 'awards_by_post_id'},
                dataType: 'json',

                success: function (data)
                {
                  console.log(data);
                
                      }

              });
              document.getElementById("myDropdown2").classList.toggle("show");

            })
        }
        document.getElementById("myDropdown").classList.toggle("show");
        // till here

  
  })
}
function filterFunction() {
  var input, filter, ul, li, a, i;
  // input = document.getElementById("myInput");
  filter = event.target.value.toUpperCase();
  div = event.target.parentNode;
  a = div.getElementsByTagName("span");
  for (i = 0; i < a.length; i++) {
    txtValue = a[i].textContent || a[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      a[i].style.display = "block";
    } else {
      a[i].style.display = "none";
    }
  }
}
</script>

<?php get_footer();?>