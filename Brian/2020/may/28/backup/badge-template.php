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
            <div class="row">
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
            <div id="designs">
              <div class="row">
                <div id="design1" class="col-md-4">
                               Design 1
                  
                </div>
                <div id="design2" class="col-md-4">Design 2</div>
                <div id="design3" class="col-md-4">Design 3</div>
              </div>
              <div class="row">
                <div id="design4" class="col-md-4">Design 4</div>
                <div id="design5" class="col-md-4">Design 5</div>
                <div id="design6" class="col-md-4">Design 6</div>
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
  position:fixed;
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
.dropdown-content#myDropdown2 {
  min-width:60%;
}
.dropdown-content#myDropdown2 span {
  display:block;
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
canvas{
  width:100%;
}

</style>

<script>
/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */


const buttons = document.querySelectorAll("#myDropdown span")
for (const button of buttons) {
  button.addEventListener('click', function(event) {
   
    document.getElementById("selectSoft").innerText = event.target.innerText;
    post_id = event.target.getAttribute("post-id");
    document.getElementById("awardType").innerHTML = 'Please wait...';
    jQuery.ajax(
    {
      url: '<?php echo admin_url('admin-ajax.php') ?>',
      type: 'POST',
      data: {post_id: post_id, action: 'awards_by_post_id'},
      dataType: 'json',

      success: function (data)
      {
        console.log(data);
        var json= data['list_rankings'];
        // console.log(data['list_rankings']);
        list_rankings = '';
        best_for = '';
        market_radar = '';
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            var val = json[key];
            console.log(val);
            list_rankings += '<span id="'+val.id+'" pointOne="'+val.ranking_quota+'" one="'+val.singular_title+'">'+val.ranking_quota+' '+val.singular_title+'</span>';
          }
        }
        bfjson = data['best_for'];
        for (var key in bfjson) {
          if (bfjson.hasOwnProperty(key)) {
            var val = bfjson[key];
            console.log(val);
            best_for += '<span pointOne="'+val+'">'+val+'</span>';
          }
        }
        mrjson = data['market_radar'];
        for (var key in mrjson) {
          if (mrjson.hasOwnProperty(key)) {
            var val = mrjson[key];
            console.log(val);
            market_radar += '<span>'+val+'</span>';
          }
        }
        document.getElementById("awardType").innerHTML = '<div class="dropdown">'+
        '<button id="selectAward" onclick="myFunction1()" class="dropbtn">Select Award</button>'+
        '<div id="myDropdown2" class="dropdown-content">'+
          '<div class="col-md-4">'+
            list_rankings+
          '</div>'+
          '<div class="col-md-4">'+
          best_for+'</div>'+
          '<div class="col-md-4">'+
          market_radar+'</div>'+
        '</div>'+
        '</div>';
        const awards = document.querySelectorAll("#myDropdown2 span")
        for (const award of awards) {
          award.addEventListener('click', function(event) {
            // alert("hi");
            document.getElementById("design1").innerHTML = '<canvas id="canvas" width=500 height=500 style="background-color:#808080;"></canvas><p></p>'+
                  
                  '<a id="download" download="myImage.jpg" href="" onclick="download_img(this);">Download to myImage.jpg</a>';
            
            document.getElementById("myDropdown2").classList.toggle("show");
            var singular_title=event.target.getAttribute("one");
            var ranking_quota=event.target.getAttribute("pointOne").toUpperCase();
            var img = new Image();
            img.src="<?php echo esc_url(plugins_url('../image/badge/badge1.png', dirname(__FILE__))); ?>";
            console.log(img);
            var canvas = document.getElementById("canvas");
            var ctx = canvas.getContext("2d");
            // var img = document.getElementById("scream");
            var myriadProBold = new FontFace('MyriadProBold', 'url("<?php echo esc_url(plugins_url('../assets/fonts/MyriadProBold.ttf', dirname(__FILE__))); ?>")');
           
            
            img.onload = function(){

              ctx.drawImage(img, 0, 0,canvas.width,canvas.height);
              txtheight = canvas.height*15/265;
              myriadProBold.load().then(function(loaded_face) {
              // loaded_face holds the loaded FontFace
              document.fonts.add(loaded_face);
              if(singular_title !== null){
                console.log("not null");
                singular_title.toUpperCase();
                ctx.font = txtheight+"px MyriadProBold";
                ctx.fillStyle = "#2e4658";
                // ctx.fillText(singular_title, canvas.width*12/50, canvas.height*225/475);
                var x=canvas.width*12/50;
                var y=canvas.height*105/265;
                wrapText(ctx,singular_title,x,y,canvas.width/2,txtheight);
              }
            

              txtheight = canvas.height*30/265;
              ctx.font = txtheight+"px MyriadProBold";
              metricsQuota = ctx.measureText(ranking_quota);
              quotaWidth = metricsQuota.width;
              xQuota = (canvas.width-quotaWidth)/2;
              ctx.fillStyle = "#fff";
              
              ctx.fillText(ranking_quota, xQuota, canvas.height*165/265);
            }).catch(function(error) {
              // error occurred
            });
           
            }
            
            /* var ox = canvas.width / 2;
            var oy = canvas.height / 2;
            ctx.font = "42px serif";
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";
            ctx.fillStyle = "#800";
            ctx.fillRect(ox / 2, oy / 2, ox, oy); */
  
            
          });
        }
      }

    });    

    document.getElementById("myDropdown").classList.toggle("show");


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
function wrapText(context, text, x, y, maxWidth, lineHeight) { //https://www.html5canvastutorials.com/tutorials/html5-canvas-wrap-text-tutorial/
              var half=text.length/2;
              spacefound=false;
              for(i=half;spacefound==false && i<text.length;i++){
                char=text.charAt(i);
                console.log({char});
                if(char == ' '){
                  half=i;
                  spacefound=true;
                }
              }
              console.log({half});
              lines=[];
              lines.push(text.substring(0, half)) ;
              lines.push(text.substring(half+1));
              console.log({lines});
              // console.log({line2});
              
              // notPrinted = true;
              for(j=0;j<lines.length;){
               adjustLine(context, x, y, maxWidth, lineHeight)
              }

            }
            
            function adjustLine(context, x, y, maxWidth, lineHeight){
              var metrics = context.measureText(lines[j]);
              var line1Width = metrics.width;
              if (line1Width > maxWidth) {
              lineHeight--;
              context.font=lineHeight +"px MyriadProBold";  
              }
              else {
                xpluscenterpadding = (maxWidth-line1Width)/2;
                console.log({xpluscenterpadding});
                context.fillText(lines[j], x+xpluscenterpadding, y);
                y += lineHeight;
                j++;
                // break;
                // notPrinted = false
              }
            }
            download_img = function(el) {
              var image = canvas.toDataURL("image/jpg");
              el.href = image;
            };
            function myFunction1() {

              event.target.nextElementSibling.classList.toggle("show");

            }
</script>

<?php get_footer();?>