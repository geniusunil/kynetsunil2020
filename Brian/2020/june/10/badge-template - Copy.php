<?php
get_header();
// $catobj = get_terms('list_categories');
global $wpdb;
$custom_post_type = 'list_items'; // define your custom post type slug here
$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", $custom_post_type ), ARRAY_A );
// print_r($results);        
// echo "count ".count($results);
// print_r($catobj);
//  store_image_get_link_test();
if (!file_exists('wp-content/badges')) {
  mkdir('wp-content/badges', 0755, true);
}
$tickImage = '<img style="position: absolute;right: 10px;top: 10px;" src="'. esc_url(plugins_url("../image/tick.png", dirname(__FILE__))).'">';
?>

<div id="backdrop" style="background: #2c4168;height: 500px;"></div>

<div class="container" style="
    top: -400px;
    position: relative;
    background: #f5f5f5;
    padding: 3%;
">
    <div id="topsfbadges" class="top-container">
        <div class="design">
            
           
                <div class="title">
                    <span>TOP SOFTWARE BADGES</span>
                </div>
                <div class="container-inner">
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
            
            <div id="designs" >
              <div class="row">
                <div class= "col-md-4 design-box-outer">
                  <div class="design-box design-boxA selected" id="design1">
                    <canvas class="canvasA" id="canvas1" width=500 height=500 style="background-color:#fff;"></canvas>
                    <?php echo $tickImage; ?>
                  </div>
                </div>
                <div class= "col-md-4 design-box-outer">
                  <div class="design-box design-boxA" id="design2">
                    <canvas class="canvasA" id="canvas2" width=500 height=500 style="background-color:#fff;"></canvas>
                    <?php echo $tickImage; ?>
                  </div>
                </div>
                <div class= "col-md-4 design-box-outer">
                  <div class="design-box design-boxA" id="design3">
                    <canvas class="canvasA" id="canvas3" width=500 height=500 style="background-color:#fff;"></canvas>
                    <?php echo $tickImage; ?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class= "col-md-4 design-box-outer">
                  <div class="design-box design-boxA" id="design4">
                    <canvas class="canvasA" id="canvas4" width=500 height=500 style="background-color:#fff;"></canvas>
                    <?php echo $tickImage; ?>
                  </div>
                </div>
                <div class= "col-md-4 design-box-outer">
                  <div class="design-box design-boxA" id="design5">
                  <canvas class="canvasA" id="canvas5" width=500 height=134 style="background-color:#fff;"></canvas>
                  <?php echo $tickImage; ?>
                  </div>
                </div>
                <div class= "col-md-4 design-box-outer">
                  <div class="design-box design-boxA" id="design6">
                  <canvas class="canvasA" id="canvas6" width=500 height=134 style="background-color:#fff;"></canvas>
                  <?php echo $tickImage; ?>
                  </div>
                </div>
              </div>             
        </div>
           
        </div>
        
        
        <div class="preview" style="
   
            width: 500px;
            float: left;
            background: white;

        ">
                              <div class="title">
                                  <span>PREVIEW</span>
                              </div>
                              <div style=" width: 100%; height: 500px; display:flex;">
                                <div id="window" style="
                                    width: fit-content;
                                    margin: auto;
                                "></div>
                              </div>
                              <div id="resize" style="background: #fbfbfb;border-top: 1px solid #e2e2e2;border-bottom: 1px solid #e2e2e2; padding:15px;">
                                  <div class="slidecontainer">
                                  <p style="font-weight: 600;">Adjust Size</p>
                                  <input type="range" min="80" max="500" value="500" class="slider" id="myRange">
                                  <p>Width: <span id="demo"></span></p>
                                  </div>
                              </div>
                              <button class="sf-button" onclick="store_image_get_link('badge')">Generate code</button>
                              <div style="padding:15px;">
                              <label>You can use this code on your site</label>
                              <textarea id="code" readonly="" rows="5" cols="70" style="resize: none;background-color: #fff;border: 1px solid #ccc;border-radius: 4px;">
                                
                              </textarea>
                                <button style="width:100%" class="sf-button" onclick="copy_to_clipboard('code')">copy</button>
                              </div>
        
        </div>
    </div>
    <div id="topsfreviews" class="top-container">
    <div class="design">
            
           
            <div class="title">
                <span>READ OUR REVIEWS</span>
            </div>
            <div class="container-inner">
              <div class="col-md-4">
                  <div class="dropdown">
                  <button id="selectSoftReviews" onclick="myFunction1()" class="dropbtn">Select Software</button>
                  <div id="myDropdownReviews" class="dropdown-content">
                      <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                      <?php foreach ($results as $co) {
                              echo "<span style='display: block;' post-id=" . $co['ID'] . " >" . $co['post_title'] . "</span>";
                            }?>
                  </div>
                  </div>
              </div>
              <div id="awardTypeReviews" class="col-md-4">

              </div>
            </div>
        
        <div id="designs" >
          <div class="row">
            <div class= "col-md-4 design-box-outer">
              <div class="design-box design-boxR selectedReviews" id="design7">
                <canvas class="canvasR" id="canvas7" width=500 height=500 style="background-color:#fff;"></canvas>
                <?php echo $tickImage; ?>
              </div>
            </div>
            <div class= "col-md-4 design-box-outer">
              <div class="design-box design-boxR" id="design8">
                <canvas class="canvasR" id="canvas8" width=500 height=77 style="background-color:#fff;"></canvas>
                <?php echo $tickImage; ?>
              </div>
            </div>
            <div class= "col-md-4 design-box-outer">
              <div class="design-box design-boxR" id="design9">
                <canvas class="canvasR" id="canvas9" width=500 height=154 style="background-color:#fff;"></canvas>
                <?php echo $tickImage; ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class= "col-md-4 design-box-outer">
              <div class="design-box design-boxR" id="design10">
                <canvas class="canvasR" id="canvas10" width=500 height=438 style="background-color:#fff;"></canvas>
                <?php echo $tickImage; ?>
              </div>
            </div>
            <div class= "col-md-4 design-box-outer">
              <div class="design-box design-boxR" id="design11">
              <canvas class="canvasR" id="canvas11" width=500 height=438 style="background-color:#fff;"></canvas>
              <?php echo $tickImage; ?>
              </div>
            </div>
            <div class= "col-md-4 design-box-outer">
              <div class="design-box design-boxR" id="design12">
              <canvas class="canvasR" id="canvas12" width=500 height=152 style="background-color:#fff;"></canvas>
              <?php echo $tickImage; ?>
              </div>
            </div>
          </div>             
    </div>
       
    </div>
    
    
    <div class="preview" style="

        width: 500px;
        float: left;
        background: white;

    ">
                          <div class="title">
                              <span>PREVIEW</span>
                          </div>
                          <div style=" width: 100%; height: 500px; display:flex;">
                            <div id="windowReviews" style="
                                width: fit-content;
                                margin: auto;
                            "></div>
                          </div>
                          <div id="resize" style="background: #fbfbfb;border-top: 1px solid #e2e2e2;border-bottom: 1px solid #e2e2e2; padding:15px;">
                              <div class="slidecontainer">
                              <p style="font-weight: 600;">Adjust Size</p>
                              <input type="range" min="80" max="500" value="500" class="slider" id="myRangeR">
                              <p>Width: <span id="demoR"></span></p>
                              </div>
                          </div>
                          <button class="sf-button" onclick="store_image_get_link('review')">Generate code</button>
                          <div style="padding:15px;">
                          <label>You can use this code on your site</label>
                          <textarea id="codeR" readonly="" rows="5" cols="70" style="resize: none;background-color: #fff;border: 1px solid #ccc;border-radius: 4px;">
                            
                          </textarea>
                            <button style="width:100%" class="sf-button" onclick="copy_to_clipboard('codeR')">copy</button>
                          </div>
    
    </div>
    </div> <!-- tops of reviews end -->
</div>

<style>
  .top-container{
    display:inline-block;
    margin-bottom:60px;
  }
  .design{
    width: calc(100% - 515px);
    background: white;
    float: left;
    margin-right: 15px;
  }
  .canvasA,.canvasR{
    margin:auto;
  }
  .design-box{
    display:flex;
    border: 1px solid #d3d8e0;
   
  }
  .design-box img{
    display:none;
  }
  .design-box.selected,.design-box.selectedReviews{
    border-color:rgb(67, 133, 244);
  }
  .design-box.selected img,.design-box.selectedReviews img{
    display:block;
  }
  .row{
    margin:0;
  }
  .design-box-outer{
    padding:5px;
    display:flex;
  }
.sf-button{
  display: block;
  margin: 10px auto;
  background: #3a7af3;
  border-radius: 5px;
  padding: 10px;
  color: white;
}
  .container-inner {
    width: 100%;
    display: flex;
  }
  .title{
    font-weight: 700;
    padding: 15px;
    font-size: 18px;
    border-bottom: 1px solid #e2e2e2;
  }
/* slider */
.slidecontainer {
  width: 100%;
}

.slider {
  -webkit-appearance: none;
    width: 90%;
    height: 10px;
    border-radius: 5px;
    background: #e4ebf4;
    outline: none;
    opacity: .7;
    -webkit-transition: .2s;
    margin: 3% 5%;
    transition: opacity .2s;
    padding:0;
    border-bottom:none;
}

.slider:hover {
  opacity: 1;
}

.slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 25px;
  height: 25px;
  border-radius: 50%;
  background: #fff;
  cursor: pointer;
  border: 2px solid #e4ebf4;
}

.slider::-moz-range-thumb {
  width: 25px;
  height: 25px;
  border-radius: 50%;
  background: #fff;
  cursor: pointer;
  border: 2px solid #e4ebf4;
}
/* Dropdown Button */
.dropbtn {
  
  background-image: url(<?php echo esc_url(plugins_url('../image/dropdown.png', dirname(__FILE__))); ?>);
  
    background-color: #fff;
    color: #99a6c4;
    padding: 16px 25px 16px 16px;
    background-size: 20px;
    font-size: 16px;
    border: 1px solid #99a6c4;
    border-radius: 4px;
    cursor: pointer;
    background-position: 95%;
    background-repeat: no-repeat;
    width: 100%;
}

/* Dropdown button on hover & focus */
/* .dropbtn:hover, .dropbtn:focus {
  background-color: #3e8e41;
} */
.dropdown-content.show{
  max-height: 50vh;
  overflow: scroll;
  width:100%;
}
.dropdown-content.show span{
  padding:5px;
  cursor:pointer;
}
.dropdown-content.show span:hover{
  background:#3a7af3;
  color:white;
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
  width:100%;
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
  
  min-width: 300%;
  left: -100%;
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
#canvas5,#canvas6{
  height:31%;
}

</style>

<script>
/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
var itemUrl='';
var itemUrlToUse='';
var itemUrlList='';
var itemUrlR='';
var alt='';
var altR='';
var image='';
var post_id=0;
var imageLink = '';
var imageLinkR = '';
var overallscore=0;
var ratingscount=0;
var d = new Date();
var year = d.getFullYear();
var myriadProBold = new FontFace('MyriadProBold', 'url("<?php echo esc_url(plugins_url('../assets/fonts/MyriadProBold.ttf', dirname(__FILE__))); ?>")');
var myriadProRegular = new FontFace('MyriadProRegular', 'url("<?php echo esc_url(plugins_url('../assets/fonts/Myriad Pro Regular.ttf', dirname(__FILE__))); ?>")');
myriadProBold.load().then(function(loaded_face) {
  document.fonts.add(loaded_face);
  
}); 
myriadProRegular.load().then(function(loaded_face) {
    document.fonts.add(loaded_face);
    console.log("load");
    console.log(document.fonts);
});//end load font face

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
        itemUrl = data['permalink'];
        var json= data['list_rankings'];
        // console.log(data['list_rankings']);
        list_rankings = '';
        best_for = '';
        market_radar = '';
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            var val = json[key];
            // console.log(val);
            list_rankings += '<span type="ranking" permalink="'+val.permalink+'" id="'+val.id+'" pointOne="'+val.ranking_quota+'" one="'+val.singular_title+'">'+val.ranking_quota+' '+val.singular_title+'</span>';
          }
        }
        bfjson = data['best_for'];
        for (var key in bfjson) {
          if (bfjson.hasOwnProperty(key)) {
            var val = bfjson[key];
            console.log(val);
            best_for += '<span type="bestfor" pointOne="'+val+'">'+val+'</span>';
          }
        }
        mrjson = data['market_radar'];
        for (var key in mrjson) {
          if (mrjson.hasOwnProperty(key)) {
            var val = mrjson[key];
            // console.log(val);
            market_radar += '<span type="market" pointOne="'+val+'">'+val+'</span>';
          }
        }
        document.getElementById("awardType").innerHTML = '<div class="dropdown">'+
        '<button id="selectAward" onclick="myFunction1()" class="dropbtn">Select Award</button>'+
        '<div id="myDropdown2" class="dropdown-content">'+
          '<div class="col-md-6">'+
            list_rankings+
          '</div>'+
          '<div class="col-md-3">'+
          best_for+'</div>'+
          '<div class="col-md-3">'+
          market_radar+'</div>'+
        '</div>'+
        '</div>';
        const awards = document.querySelectorAll("#myDropdown2 span")
        for (const award of awards) {
          award.addEventListener('click', function(event) {
            activeCanvasBox = document.querySelector('.design-boxA.selected');
            if(activeCanvasBox !== null){
                    activeCanvasBox.classList.remove("selected");
            }
            box = document.querySelector('#design1');
                 
            box.classList.add("selected");
            itemUrlList = null;
            itemUrlList = event.target.getAttribute("permalink");
            if(itemUrlList != null){
              itemUrlToUse = itemUrlList;
            }
            else{
              itemUrlToUse = itemUrl;
            }
            document.getElementById("design1").innerHTML = '<canvas class="canvasA" id="canvas1" width=500 height=500 style="background-color:#fff;"></canvas><p></p><?php echo $tickImage; ?>';
            document.getElementById("design2").innerHTML = '<canvas class="canvasA" id="canvas2" width=500 height=500 style="background-color:#fff;"></canvas><p></p><?php echo $tickImage; ?>';
            document.getElementById("design3").innerHTML = '<canvas class="canvasA" id="canvas3" width=500 height=500 style="background-color:#fff;"></canvas><p></p><?php echo $tickImage; ?>';
            document.getElementById("design4").innerHTML = '<canvas class="canvasA" id="canvas4" width=500 height=500 style="background-color:#fff;"></canvas><p></p><?php echo $tickImage; ?>';
            document.getElementById("design5").innerHTML = '<canvas class="canvasA" id="canvas5" width=500 height=134 style="background-color:#fff;"></canvas><p></p><?php echo $tickImage; ?>';
            document.getElementById("design6").innerHTML = '<canvas class="canvasA" id="canvas6" width=500 height=134 style="background-color:#fff;"></canvas><p></p><?php echo $tickImage; ?>';

            const designBoxesA = document.querySelectorAll(".design-boxA");
            for (const designBox of designBoxesA) {
                
                designBox.addEventListener('click', function(event) {
                  
                  canvas= designBox.querySelector('canvas');
                  image = canvas.toDataURL("image/png");
                  var slider = document.getElementById("myRange");
                  document.getElementById("window").innerHTML = '<a target="_blank" href="'+itemUrlToUse +'" title="ImageName">'+
                                  '<img style="width:'+slider.value+'px;" id="previewImg" alt="'+alt+'" src="'+image+'">'+
                              '</a>';
                  activeCanvasBox = document.querySelector('.design-boxA.selected');
                  if(activeCanvasBox !== null){
                    activeCanvasBox.classList.remove("selected");
                    // activeCanvasBox.style.borderColor = "#d3d8e0";
                    // var element = designBox.querySelector('img');
                    // designBox.removeChild(element);
                  }
                  designBox.classList.add("selected");
                  // designBox.innerHTML += '';
                  // designBox.style.borderColor = "#4385f4";
              });
            }
               //   '<a id="download" download="myImage.jpg" href="" onclick="download_img(this);">Download to myImage.jpg</a>'
            
            document.getElementById("myDropdown2").classList.toggle("show");
            draw_canvass(false);
          });
        }
      }

    });    

    document.getElementById("myDropdown").classList.toggle("show");


  })
}
const buttonsReviews = document.querySelectorAll("#myDropdownReviews span")
for (const button of buttonsReviews) {
  button.addEventListener('click', function(event) {
    activeCanvasBox = document.querySelector('.design-boxR.selectedReviews');
    if(activeCanvasBox !== null){
      activeCanvasBox.classList.remove("selectedReviews");
    }
    box = document.querySelector('#design7');
                 
    box.classList.add("selectedReviews");

    document.getElementById("selectSoftReviews").innerText = event.target.innerText;
    post_id = event.target.getAttribute("post-id");
    document.getElementById("awardTypeReviews").innerHTML = 'Please wait...';
    jQuery.ajax(
    {
      url: '<?php echo admin_url('admin-ajax.php') ?>',
      type: 'POST',
      data: {post_id: post_id, action: 'reviews_by_post_id'},
      dataType: 'json',

      success: function (data)
      {
        console.log(data);
        document.getElementById("awardTypeReviews").innerHTML = '';

        overallscore = data['overallscore'];
        ratingscount= data['ratingscount'];
        itemUrlR = data['permalink'];
        draw_canvass_reviews(false);
        
      }

    });    

    document.getElementById("myDropdownReviews").classList.toggle("show");


  })
}
function copy_to_clipboard(id) {
  var copyText = document.getElementById(id);
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
  // alert("Copied the text: " + copyText.value);
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
function wrapText(context, text, varObj, maxWidth, lineHeight, fontName) { //https://www.html5canvastutorials.com/tutorials/html5-canvas-wrap-text-tutorial/
              var half=text.length/2;
              spacefound=false;
              for(i=half;spacefound==false && i<text.length;i--){
                char=text.charAt(i);
                // console.log({char});
                if(char == ' '){
                  half=i;
                  spacefound=true;
                }
              }
              // console.log({half});
              lines=[];
              lines.push(text.substring(0, half)) ;
              lines.push(text.substring(half+1));
              // console.log({lines});
              // console.log({line2});
              
              // notPrinted = true;

              for(j=0;j<lines.length;){
                console.log("lines j"+lines[j]);
                console.log({varObj});
                adjustLine(lines[j], context, varObj, maxWidth, lineHeight,fontName);
                j++;
              }

            }
            
            function adjustLine(string, context, varObj, maxWidth, lineHeight,fontName){
              // console.log({varObj});
              /* console.log({string});
              console.log(context.font); */
              printed=false;
              while(printed == false){
                // console.log("in the loop");
                var metrics = context.measureText(string);
                var line1Width = metrics.width;
                if (line1Width > maxWidth) {
                  lineHeight--;
                  context.font=lineHeight +"px "+fontName; 
                  /* console.log("adjusted");
                  console.log(context.font); */
                
                }
                else {
                  xpluscenterpadding = (maxWidth-line1Width)/2;
                  // console.log({xpluscenterpadding});
                  context.fillText(string, varObj.x+xpluscenterpadding, varObj.y);
                  console.log(varObj.y);
                  varObj.y += lineHeight;
                  console.log(varObj.y);
                  printed = true;
                  // break;
                  // notPrinted = false
                }
              }
              
            }
            download_img = function(el) {
              image = canvas.toDataURL("image/jpg");
              el.href = image;
            };
           
           

            

            function myFunction1() {

              event.target.nextElementSibling.classList.toggle("show");

            }
            var singular_title,singular_title_original_case,ranking_quota,badgetype;
            function draw_canvass(repeat){
              if(repeat === false){
                singular_title=event.target.getAttribute("one");
                ranking_quota=event.target.getAttribute("pointOne").toUpperCase();
                badgetype=event.target.getAttribute("type");
                singular_title_original_case = singular_title;
                if(singular_title !== null){
                  singular_title=singular_title.toUpperCase();
                }
                
              }
              
              
              
              
             
              var fontNow = "MyriadProBold";
              // console.log({ranking_quota});
              var img = new Image();
              img.src="<?php echo esc_url(plugins_url('../image/badge/badge1.png', dirname(__FILE__))); ?>";
              // console.log(img);
              var canvas = document.getElementById("canvas1");
              var ctx = canvas.getContext("2d");
              // var img = document.getElementById("scream");
              
              document.fonts.ready.then(function(font_face_set) {
                console.log({font_face_set});
              img.onload = function(){
                let varObj = {};
                ctx.drawImage(img, 0, 0,canvas.width,canvas.height);
                txtheight = canvas.height*15/265;
                
                // loaded_face holds the loaded FontFace
                
                if(singular_title !== null){
                  console.log("not null");
                  
                  ctx.font = txtheight+"px "+fontNow;
                  ctx.fillStyle = "#2e4658";
                  // ctx.fillText(singular_title, canvas.width*12/50, canvas.height*225/475);
                  
                  varObj['x']=canvas.width*12/50;
                  varObj['y']=canvas.height*105/265;
                  wrapText(ctx,singular_title,varObj,canvas.width/2,txtheight,fontNow);

                  alt = ranking_quota+" "+singular_title;
                  
                }
              

                lineHeight = canvas.height*30/265;
                ctx.font = lineHeight+"px "+fontNow;
                metricsQuota = ctx.measureText(ranking_quota);
                quotaWidth = metricsQuota.width;
                varObj.x = canvas.width*55/290;
                varObj.y = canvas.height*165/265;
                ctx.fillStyle = "#fff";
                console.log("before ranking quota");
                console.log({varObj});
                adjustLine(ranking_quota,ctx, varObj, canvas.width*175/290, lineHeight,fontNow);
                if(alt==''){
                  alt=ranking_quota;
                }
                canvas.setAttribute("alt",alt);
                fontNow= "MyriadProRegular";
                  lineHeight = canvas.height*15/265;
                  ctx.font = lineHeight+"px "+fontNow;
                  metricsYear = ctx.measureText(ranking_quota);
                  quotaWidth = metricsYear.width;
                  varObj.x = canvas.width*120/290;
                  varObj.y = canvas.height*210/265;
                  ctx.fillStyle = "#2e4658";
                  // console.log("canvas1");
                  adjustLine(year,ctx, varObj, canvas.width*45/290, lineHeight,fontNow);
                
                // ctx.fillText(ranking_quota, xQuota, canvas.height*165/265);
            
                activeCanvas = document.querySelector('.design-boxA.selected canvas');
              image = activeCanvas.toDataURL("image/png");
              
              document.getElementById("window").innerHTML = '<a target="_blank" href="'+itemUrlToUse+'" title="ImageName">'+
                              '<img style="width:'+slider.value+'px;" id="previewImg" alt="'+alt+'" src="'+image+'">'+
                               '</a>';
              }

              
              // design 2
              var img2 = new Image();
              img2.src="<?php echo esc_url(plugins_url('../image/badge/badge2.png', dirname(__FILE__))); ?>";
              // console.log(img);
              canvas2 = document.getElementById("canvas2");
              var ctx2 = canvas2.getContext("2d");
              // var img = document.getElementById("scream");
              
              
              img2.onload = function(){
                let varObj = {};
                ctx2.drawImage(img2, 0, 0,canvas2.width,canvas2.height);
                txtheight = canvas2.height*20/265;
                fontNow = "MyriadProBold";
                ctx2.font = txtheight+"px "+fontNow;
                
                // loaded_face holds the loaded FontFace
                ctx2.fillStyle = "#596977";
                varObj['x']=canvas2.width*25/300;
                
                if(singular_title !== null){
                  // console.log("not null");
                  varObj['y']=canvas2.height*130/265;
                  // singular_title=singular_title.toUpperCase();
                  completeText = ranking_quota + ' '+singular_title
                  
                  
                  // ctx2.fillText(singular_title, canvas2.width*12/50, canvas2.height*225/475);
                  
                  
                  alt=completeText;
                
                  maxWidth = canvas.width*245/300;
                  wrapText(ctx2,completeText,varObj,maxWidth,txtheight,fontNow);
                }
                else{
                  varObj['y']=canvas2.height*145/265;
                  metricsQuota = ctx2.measureText(ranking_quota);
                  quotaWidth = metricsQuota.width;
                  
                  // ctx2.fillStyle = "#fff";
                  // console.log("before ranking quota");
                  // console.log({varObj});
                  alt=ranking_quota;
                  adjustLine(ranking_quota,ctx2, varObj, canvas2.width*245/300, txtheight,fontNow);

                }
              
                canvas2.setAttribute("alt",alt);
              
                
                  
                  lineHeight = canvas2.height*25/265;
                  ctx2.font = lineHeight+"px "+fontNow;
                  metricsYear = ctx2.measureText(ranking_quota);
                  quotaWidth = metricsYear.width;
                  varObj.x = canvas2.width*115/300;
                  varObj.y = canvas2.height*198/265;
                  ctx2.fillStyle = "#114668";
                  // console.log("canvas2");
                  adjustLine(year,ctx2, varObj, canvas2.width*73/300, lineHeight,fontNow);
                
                  // ctx2.fillText(ranking_quota, xQuota, canvas2.height*165/265);
              
            
              }

              // design 3
              var img3 = new Image();
              img3.src="<?php echo esc_url(plugins_url('../image/badge/badge3.png', dirname(__FILE__))); ?>";
              // console.log(img);
              canvas3 = document.getElementById("canvas3");
              var ctx3 = canvas3.getContext("2d");
              // var img = document.getElementById("scream");
              
              
              img3.onload = function(){
                let varObj = {};
                ctx3.drawImage(img3, 0, 0,canvas3.width,canvas3.height);
                txtheight = canvas3.height*20/265;
                
                fontNow = "MyriadProRegular";
                ctx3.font = txtheight+"px "+fontNow;
                // loaded_face holds the loaded FontFace
                ctx3.fillStyle = "#fff";
                varObj['x']=canvas3.width*55/300;
                maxWidth = canvas.width*185/300;
                if(singular_title !== null){
                  // console.log("not null");
                  varObj['y']=canvas3.height*125/265;
                  // singular_title=singular_title.toUpperCase();
                  completeText = ranking_quota + ' '+singular_title
                  
                  
                  // ctx3.fillText(singular_title, canvas3.width*12/50, canvas3.height*225/475);
                  
                  
                  alt=completeText;
                  wrapText(ctx3,completeText,varObj,maxWidth,txtheight,fontNow);
                }
                else{
                  varObj['y']=canvas3.height*140/265;
                  metricsQuota = ctx3.measureText(ranking_quota);
                  quotaWidth = metricsQuota.width;
                  alt=ranking_quota;
                  // ctx3.fillStyle = "#fff";
                  // console.log("before ranking quota");
                  // console.log({varObj});
                  adjustLine(ranking_quota,ctx3, varObj, maxWidth, txtheight,fontNow);

                }
              
                canvas3.setAttribute("alt",alt);
              
                
                  
                  lineHeight = canvas3.height*25/265;
                  ctx3.font = lineHeight+"px "+fontNow;
                  metricsYear = ctx3.measureText(ranking_quota);
                  quotaWidth = metricsYear.width;
                  varObj.x = canvas3.width*125/300;
                  varObj.y = canvas3.height*198/265;
                  ctx3.fillStyle = "#2e4658";
                  // console.log("canvas3");
                  adjustLine(year,ctx3, varObj, canvas3.width*47/300, lineHeight,fontNow);
        
                
                  // ctx3.fillText(ranking_quota, xQuota, canvas3.height*165/265);
                
            
              }

              // design 4
              var img4 = new Image();
              img4.src="<?php echo esc_url(plugins_url('../image/badge/badge4.png', dirname(__FILE__))); ?>";
              // console.log(img);
              canvas4 = document.getElementById("canvas4");
              var ctx4 = canvas4.getContext("2d");
              // var img = document.getElementById("scream");
              
              
              img4.onload = function(){
                let varObj = {};
                ctx4.drawImage(img4, 0, 0,canvas4.width,canvas4.height);
                txtheight = canvas4.height*20/265;
                
                // fontNow = "MyriadProRegular";
                ctx4.font = txtheight+"px "+fontNow;
                // loaded_face holds the loaded FontFace
                ctx4.fillStyle = "#2e4658";
                varObj['x']=canvas4.width*61/300;
                maxWidth = canvas.width*176/300;
                if(singular_title !== null){
                  // console.log("not null");
                  varObj['y']=canvas4.height*170/265;
                  // singular_title=singular_title.toUpperCase();
                  completeText = ranking_quota + ' '+singular_title;
                  
                  
                  // ctx4.fillText(singular_title, canvas4.width*12/50, canvas4.height*225/475);
                  
                  
                  alt=completeText;
                  wrapText(ctx4,completeText,varObj,maxWidth,txtheight,fontNow);
                }
                else{
                  varObj['y']=canvas4.height*190/265;
                  metricsQuota = ctx4.measureText(ranking_quota);
                  quotaWidth = metricsQuota.width;
                  alt=ranking_quota;
                  // ctx4.fillStyle = "#fff";
                  // console.log("before ranking quota");
                  // console.log({varObj});
                  adjustLine(ranking_quota,ctx4, varObj, maxWidth, txtheight,fontNow);

                }
              

                canvas4.setAttribute("alt",alt);
                
                  
                  lineHeight = canvas4.height*25/265;
                  ctx4.font = lineHeight+"px "+fontNow;
                  metricsYear = ctx4.measureText(ranking_quota);
                  quotaWidth = metricsYear.width;
                  varObj.x = canvas4.width*128/300;
                  varObj.y = canvas4.height*232/265;
                  ctx4.fillStyle = "#2e4658";
                  // console.log("canvas4");
                  adjustLine(year,ctx4, varObj, canvas4.width*43/300, lineHeight,fontNow);
        
                
                  // ctx4.fillText(ranking_quota, xQuota, canvas3.height*165/265);
                
            
              }

              // design 5
              var img5 = new Image();
              img5.src="<?php echo esc_url(plugins_url('../image/badge/badge5.png', dirname(__FILE__))); ?>";
              // console.log(img);
              canvas5 = document.getElementById("canvas5");
              var ctx5 = canvas5.getContext("2d");
              // var img = document.getElementById("scream");
              
              
              img5.onload = function(){
                let varObj = {};
                ctx5.drawImage(img5, 0, 0,canvas5.width,canvas5.height);
                txtheight = canvas5.height*18/81;
                
                // fontNow = "MyriadProRegular";
                ctx5.font = txtheight+"px "+fontNow;
                // loaded_face holds the loaded FontFace
                ctx5.fillStyle = "#fff";
                varObj['x']=canvas5.width*75/290;
                varObj['y']=canvas5.height*30/81;
                maxWidth = canvas.width*205/290;
                if(badgetype=='ranking'){
                  // console.log("not null");
                  
                  // singular_title=singular_title.toUpperCase();
                  completeText =  'Top '+singular_title_original_case;
                  
                  
                  // ctx5.fillText(singular_title, canvas5.width*12/50, canvas5.height*225/475);
                  
                  
                  
                  // wrapText(ctx5,completeText,varObj,maxWidth,txtheight,fontNow);
                }
                else if(badgetype=='bestfor'){
                  
                  
                  // ctx5.fillStyle = "#fff";
                  // console.log("before ranking quota");
                  // console.log({varObj});
                  completeText = 'VOTED BEST FOR '+ranking_quota;

                }
                else{
                  completeText = 'VOTED '+ranking_quota;
                }
                alt=completeText;
                canvas5.setAttribute("alt",alt);
                adjustLine(completeText,ctx5, varObj, maxWidth, txtheight,fontNow);
              

              
                
      
                
            
              }

              // design 6
              var img6 = new Image();
              img6.src="<?php echo esc_url(plugins_url('../image/badge/badge6.png', dirname(__FILE__))); ?>";
              // console.log(img);
              canvas6 = document.getElementById("canvas6");
              var ctx6 = canvas6.getContext("2d");
              // var img = document.getElementById("scream");
              
              
              img6.onload = function(){
                let varObj = {};
                ctx6.drawImage(img6, 0, 0,canvas6.width,canvas6.height);
                txtheight = canvas6.height*18/81;
                
                // fontNow = "MyriadProRegular";
                ctx6.font = txtheight+"px "+fontNow;
                // loaded_face holds the loaded FontFace
                ctx6.fillStyle = "#114668";
                varObj['x']=canvas6.width*75/290;
                varObj['y']=canvas6.height*60/81;
                maxWidth = canvas.width*205/290;
                if(badgetype=='ranking'){
                  // console.log("not null");
                  
                  // singular_title=singular_title.toUpperCase();
                  completeText =  'Top '+singular_title_original_case;
                  
                  
                  // ctx6.fillText(singular_title, canvas6.width*12/50, canvas6.height*225/475);
                  
                  
                  
                  // wrapText(ctx6,completeText,varObj,maxWidth,txtheight,fontNow);
                }
                else if(badgetype=='bestfor'){
                  
                  
                  // ctx6.fillStyle = "#fff";
                  // console.log("before ranking quota");
                  // console.log({varObj});
                  completeText = 'VOTED BEST FOR '+ranking_quota;

                }
                else{
                  completeText = 'VOTED '+ranking_quota;
                }
                alt=completeText;
                canvas6.setAttribute("alt",alt);
                adjustLine(completeText,ctx6, varObj, maxWidth, txtheight,fontNow);
            
              }
              
              
            });
            
              
              


            
              
            
          }

          function draw_canvass_reviews(repeat){
             /*  if(repeat === false){
                
                
              } */
            document.getElementById("design7").innerHTML = '<canvas class="canvasR" id="canvas7" width=500 height=500 style="background-color:#fff;"></canvas><p></p><?php echo $tickImage; ?>';
            document.getElementById("design8").innerHTML = '<canvas class="canvasR" id="canvas8" width=500 height=77 style="background-color:#fff;"></canvas><p></p><?php echo $tickImage; ?>';
            document.getElementById("design9").innerHTML = '<canvas class="canvasR" id="canvas9" width=500 height=154 style="background-color:#fff;"></canvas><p></p><?php echo $tickImage; ?>';
            document.getElementById("design10").innerHTML = '<canvas class="canvasR" id="canvas10" width=500 height=438 style="background-color:#fff;"></canvas><p></p><?php echo $tickImage; ?>';
            document.getElementById("design11").innerHTML = '<canvas class="canvasR" id="canvas11" width=500 height=438 style="background-color:#fff;"></canvas><p></p><?php echo $tickImage; ?>';
            document.getElementById("design12").innerHTML = '<canvas class="canvasR" id="canvas12" width=500 height=152 style="background-color:#fff;"></canvas><p></p><?php echo $tickImage; ?>';

            const designBoxesR = document.querySelectorAll(".design-boxR")
            for (const designBox of designBoxesR) {
              designBox.addEventListener('click', function(event) {
                  
                  canvas= designBox.querySelector('canvas');
                  image = canvas.toDataURL("image/png");
                  var sliderR = document.getElementById("myRangeR");
                  document.getElementById("windowReviews").innerHTML = '<a target="_blank" href="'+itemUrlR +'" title="ImageName">'+
                                  '<img style="width:'+sliderR.value+'px;" id="previewImgR" alt="'+altR+'" src="'+image+'">'+
                              '</a>';
                  activeCanvasBox = document.querySelector('.design-boxR.selectedReviews');
                  if(activeCanvasBox !== null){
                    activeCanvasBox.classList.remove("selectedReviews");
                    // activeCanvas.parentNode.style.borderColor = "#d3d8e0";
                  }
                  designBox.classList.add("selectedReviews");
                  // canvas.parentNode.style.borderColor = "#4385f4";
              });
            }
              var fontNow = "MyriadProBold";
              // console.log({ranking_quota});
              var img = new Image();
              img.src="<?php echo esc_url(plugins_url('../image/badge/badge7.png', dirname(__FILE__))); ?>";
              // console.log(img);
              var canvas = document.getElementById("canvas7");
              var ctx = canvas.getContext("2d");
              // var img = document.getElementById("scream");
              
              document.fonts.ready.then(function(font_face_set) {
                // console.log({font_face_set});
              img.onload = function(){
                let varObj = {};
                ctx.drawImage(img, 0, 0,canvas.width,canvas.height);
                txtheight = canvas.height*60/265;
                
                // loaded_face holds the loaded FontFace
              
                
                 
                  
                  ctx.font = txtheight+"px "+fontNow;
                  ctx.fillStyle = "#114668";
                  // ctx.fillText(singular_title, canvas.width*12/50, canvas.height*225/475);
                  
                  varObj['x']=canvas.width*78/292;
                  varObj['y']=canvas.height*70/265;
                  txtToPrint="Users Love us!";
                  altR=txtToPrint;
                  canvas.setAttribute("alt",altR);
                  wrapText(ctx,txtToPrint,varObj,canvas.width*125/290,txtheight,fontNow);
                
              

                
                fontNow= "MyriadProRegular";
                  lineHeight = canvas.height*25/265;
                  ctx.font = lineHeight+"px "+fontNow;
                  metricsYear = ctx.measureText(ranking_quota);
                  quotaWidth = metricsYear.width;
                  varObj.x = canvas.width*120/290;
                  varObj.y = canvas.height*218/265;
                  ctx.fillStyle = "#2e4658";
                  // console.log("canvas1");
                  adjustLine(year,ctx, varObj, canvas.width*50/290, lineHeight,fontNow);
                
                // ctx.fillText(ranking_quota, xQuota, canvas.height*165/265);
            
                activeCanvas = document.querySelector('.design-boxR.selectedReviews canvas');
              image = activeCanvas.toDataURL("image/png");
              
              document.getElementById("windowReviews").innerHTML = '<a target="_blank" href="'+itemUrlR+'" title="ImageName">'+
                              '<img style="width:'+sliderR.value+'px;" id="previewImgR" alt="'+altR+'" src="'+image+'">'+
                               '</a>';
              }

              
              // design 2
              var img2 = new Image();
              img2.src="<?php echo esc_url(plugins_url('../image/badge/badge8.png', dirname(__FILE__))); ?>";
              // console.log(img);
              canvas2 = document.getElementById("canvas8");
              var ctx2 = canvas2.getContext("2d");
              // var img = document.getElementById("scream");
              
              
              img2.onload = function(){
                let varObj = {};
                ctx2.drawImage(img2, 0, 0,canvas2.width,canvas2.height);
                txtheight = canvas2.height*30/80;
                fontNow = "MyriadProBold";
                ctx2.font = txtheight+"px "+fontNow;
                
                // loaded_face holds the loaded FontFace
                ctx2.fillStyle = "#222222";
                varObj['x']=canvas2.width*4/300;
                
              
                  varObj['y']=canvas2.height*55/80;
                 
                  maxWidth = canvas.width*110/300;
                  score= 'Excellent '+overallscore;
                  altR=score+' out of 5';
                  canvas2.setAttribute("alt",altR);
                  adjustLine(score,ctx2, varObj, maxWidth, txtheight,fontNow);

                }
              

              
                
                  
              

              // design 3
              var img3 = new Image();
              img3.src="<?php echo esc_url(plugins_url('../image/badge/badge9.png', dirname(__FILE__))); ?>";
              // console.log(img);
              canvas3 = document.getElementById("canvas9");
              var ctx3 = canvas3.getContext("2d");
              // var img = document.getElementById("scream");
              
              
              img3.onload = function(){
                ctx3.drawImage(img3, 0, 0,canvas3.width,canvas3.height);
               
                
                
                altR="Read our reviews!";
                canvas3.setAttribute("alt",altR);
              }

              // design 4
              var img4 = new Image();
              img4.src="<?php echo esc_url(plugins_url('../image/badge/badge10.png', dirname(__FILE__))); ?>";
              // console.log(img);
              var stars = new Image();
              stars.src="<?php echo esc_url(plugins_url('../image/badge/stars.png', dirname(__FILE__))); ?>";
              canvas4 = document.getElementById("canvas10");
              var ctx4 = canvas4.getContext("2d");
              // var img = document.getElementById("scream");
              
              
              img4.onload = function(){
                
                let varObj = {};
                ctx4.drawImage(img4, 0, 0,canvas4.width,canvas4.height);
                txtheight = canvas4.height*35/265;
                
                fontNow = "MyriadProRegular";
                ctx4.font = txtheight+"px "+fontNow;
                // loaded_face holds the loaded FontFace
                ctx4.fillStyle = "#114668";
                varObj['x']=canvas4.width*45/290;
                maxWidth = canvas.width*165/300;
                varObj['y']=canvas4.height*180/265;
               
                  
                  
        
                
                txtPrint = "ScoutScore "+overallscore+" / "+ratingscount+" reviews";
                altR=txtPrint;
                canvas4.setAttribute("alt",altR);
                adjustLine(txtPrint,ctx4, varObj, maxWidth, txtheight,fontNow);

                
              

              
                
            
              }

              // design 5
              var img5 = new Image();
              img5.src="<?php echo esc_url(plugins_url('../image/badge/badge11.png', dirname(__FILE__))); ?>";
              // console.log(img);
              canvas5 = document.getElementById("canvas11");
              var ctx5 = canvas5.getContext("2d");
              // var img = document.getElementById("scream");
              
              stars.onload = function(){
               
                ctx4.drawImage(stars,0,0,canvas4.width*(overallscore/5)*(200/290),canvas4.height*45/290, canvas4.width*47/290, canvas4.height*117/265,canvas4.width*(overallscore/5)*(200/290),canvas4.height*45/290);
                ctx5.drawImage(stars,0,0,canvas4.width*(overallscore/5)*(200/290),canvas4.height*45/290, canvas4.width*47/290, canvas4.height*117/265,canvas4.width*(overallscore/5)*(200/290),canvas4.height*45/290);

              }
              img5.onload = function(){
                let varObj = {};
                ctx5.drawImage(img5, 0, 0,canvas5.width,canvas5.height);
                
                txtheight = canvas4.height*35/265;
                
                fontNow = "MyriadProRegular";
                ctx5.font = txtheight+"px "+fontNow;
                // loaded_face holds the loaded FontFace
                ctx5.fillStyle = "#114668";
                varObj['x']=canvas4.width*45/290;
                maxWidth = canvas.width*165/300;
                varObj['y']=canvas4.height*180/265;

                txtPrint = "ScoutScore "+overallscore+" / "+ratingscount+" reviews";
                altR=txtPrint;
                canvas5.setAttribute("alt",altR);
                adjustLine(txtPrint,ctx5, varObj, maxWidth, txtheight,fontNow);
              

              
                
      
                
            
              }

              // design 6
              var img6 = new Image();
              img6.src="<?php echo esc_url(plugins_url('../image/badge/badge12.png', dirname(__FILE__))); ?>";
              // console.log(img);
              canvas6 = document.getElementById("canvas12");
              var ctx6 = canvas6.getContext("2d");
              // var img = document.getElementById("scream");
              
              
              img6.onload = function(){
                // let varObj = {};
                altR="Featured on Saas Scout";
                canvas6.setAttribute("alt",altR);
                ctx6.drawImage(img6, 0, 0,canvas6.width,canvas6.height);
              
              }
              
              
            });
            
              
              


            
              
            
          }

          function store_image_get_link(imageType){
            // itemid="123997";
            if(imageType=='badge'){
              document.getElementById('code').value = "Please wait ...";
            }
            else{
              document.getElementById('codeR').value = "Please wait ...";
            }
            filename=imageType+"_"+post_id+"_";
            jQuery.ajax(
            {
              url: '<?php echo admin_url('admin-ajax.php') ?>',
              type: 'POST',
              data: {filename: filename, action: 'store_get_link', image:image},
              dataType: 'json',

              success: function (data)
              {
                console.log(data);
                if(imageType=='badge'){
                  imageLink=data['link'];
                  alt=document.querySelector('.design-boxA.selected canvas').getAttribute('alt');
                  document.getElementById('code').value = '<a target="_blank" href="'+itemUrlToUse+'" title="ImageName">'+
                              '<img style="width:'+slider.value+'px;" id="previewImg" alt="'+alt+'" src="'+imageLink+'">'+
                               '</a>';
                }
                else{
                  imageLinkR=data['link'];
                  altR=document.querySelector('.design-boxR.selectedReviews canvas').getAttribute('alt');
                  document.getElementById('codeR').value = '<a target="_blank" href="'+itemUrlR+'" title="ImageName">'+
                              '<img style="width:'+sliderR.value+'px;" id="previewImg" alt="'+altR+'" src="'+imageLinkR+'">'+
                               '</a>';
                }
                
                
              }
            });

          }
            var slider = document.getElementById("myRange");
            var output = document.getElementById("demo");
            output.innerHTML = slider.value+'px';

            slider.onchange = function() {
              output.innerHTML = this.value+'px';
              document.getElementById("previewImg").style.width = slider.value+"px";
              if(imageLink != ''){
                document.getElementById('code').value = '<a target="_blank" href="'+itemUrlToUse+'" title="ImageName">'+
                              '<img style="width:'+slider.value+'px;" id="previewImg" alt="'+alt+'" src="'+imageLink+'">'+
                               '</a>';
              }
            }
              var sliderR = document.getElementById("myRangeR");
            var outputR = document.getElementById("demoR");
            outputR.innerHTML = sliderR.value+'px';

            sliderR.onchange = function() {
              outputR.innerHTML = this.value+'px';
              document.getElementById("previewImgR").style.width = sliderR.value+"px";
              if(imageLinkR != ''){
                document.getElementById('codeR').value = '<a target="_blank" href="'+itemUrlR+'" title="ImageName">'+
                              '<img style="width:'+sliderR.value+'px;" id="previewImgR" alt="'+altR+'" src="'+imageLinkR+'">'+
                               '</a>';
              }

              
            }
</script>

<?php get_footer();?>