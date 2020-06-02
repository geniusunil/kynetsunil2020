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
#canvas5,#canvas6{
  height:31%;
}

</style>

<script>
/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
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
        // console.log(data);
        var json= data['list_rankings'];
        // console.log(data['list_rankings']);
        list_rankings = '';
        best_for = '';
        market_radar = '';
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            var val = json[key];
            // console.log(val);
            list_rankings += '<span type="ranking" id="'+val.id+'" pointOne="'+val.ranking_quota+'" one="'+val.singular_title+'">'+val.ranking_quota+' '+val.singular_title+'</span>';
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
            document.getElementById("design1").innerHTML = '<canvas id="canvas1" width=500 height=500 style="background-color:#808080;"></canvas><p></p>';
            document.getElementById("design2").innerHTML = '<canvas id="canvas2" width=500 height=500 style="background-color:#808080;"></canvas><p></p>';
            document.getElementById("design3").innerHTML = '<canvas id="canvas3" width=500 height=500 style="background-color:#808080;"></canvas><p></p>';
            document.getElementById("design4").innerHTML = '<canvas id="canvas4" width=500 height=500 style="background-color:#808080;"></canvas><p></p>';
            document.getElementById("design5").innerHTML = '<canvas id="canvas5" width=500 height=500 style="background-color:#808080;"></canvas><p></p>';
            document.getElementById("design6").innerHTML = '<canvas id="canvas6" width=500 height=500 style="background-color:#808080;"></canvas><p></p>';

               //   '<a id="download" download="myImage.jpg" href="" onclick="download_img(this);">Download to myImage.jpg</a>'
            
            document.getElementById("myDropdown2").classList.toggle("show");
            var singular_title=event.target.getAttribute("one");
            var ranking_quota=event.target.getAttribute("pointOne").toUpperCase();
            var badgetype=event.target.getAttribute("type");
            var d = new Date();
            var year = d.getFullYear();
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
                singular_title=singular_title.toUpperCase();
                ctx.font = txtheight+"px "+fontNow;
                ctx.fillStyle = "#2e4658";
                // ctx.fillText(singular_title, canvas.width*12/50, canvas.height*225/475);
                
                varObj['x']=canvas.width*12/50;
                varObj['y']=canvas.height*105/265;
                wrapText(ctx,singular_title,varObj,canvas.width/2,txtheight,fontNow);
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

              
              fontNow= "MyriadProRegular";
                lineHeight = canvas.height*15/265;
                ctx.font = lineHeight+"px "+fontNow;
                metricsYear = ctx.measureText(ranking_quota);
                quotaWidth = metricsYear.width;
                varObj.x = canvas.width*120/290;
                varObj.y = canvas.height*210/265;
                ctx.fillStyle = "#2e4658";
                console.log("canvas1");
                adjustLine(year,ctx, varObj, canvas.width*45/290, lineHeight,fontNow);
              
              // ctx.fillText(ranking_quota, xQuota, canvas.height*165/265);
           
           
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
              txtheight = canvas2.height*40/265;
              fontNow = "MyriadProBold";
              ctx2.font = txtheight+"px "+fontNow;
              
              // loaded_face holds the loaded FontFace
              ctx2.fillStyle = "#596977";
              varObj['x']=canvas2.width*25/300;
              
              if(singular_title !== null){
                // console.log("not null");
                varObj['y']=canvas2.height*130/265;
                singular_title=singular_title.toUpperCase();
                completeText = ranking_quota + ' '+singular_title
                
                
                // ctx2.fillText(singular_title, canvas2.width*12/50, canvas2.height*225/475);
                
                
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
                adjustLine(ranking_quota,ctx2, varObj, canvas2.width*245/300, txtheight,fontNow);

              }
            

             
              
                
                lineHeight = canvas2.height*25/265;
                ctx2.font = lineHeight+"px "+fontNow;
                metricsYear = ctx2.measureText(ranking_quota);
                quotaWidth = metricsYear.width;
                varObj.x = canvas2.width*115/300;
                varObj.y = canvas2.height*198/265;
                ctx2.fillStyle = "#114668";
                console.log("canvas2");
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
              txtheight = canvas3.height*40/265;
              
              fontNow = "MyriadProRegular";
              ctx3.font = txtheight+"px "+fontNow;
              // loaded_face holds the loaded FontFace
              ctx3.fillStyle = "#fff";
              varObj['x']=canvas3.width*55/300;
              maxWidth = canvas.width*185/300;
              if(singular_title !== null){
                // console.log("not null");
                varObj['y']=canvas3.height*125/265;
                singular_title=singular_title.toUpperCase();
                completeText = ranking_quota + ' '+singular_title
                
                
                // ctx3.fillText(singular_title, canvas3.width*12/50, canvas3.height*225/475);
                
                
                
                wrapText(ctx3,completeText,varObj,maxWidth,txtheight,fontNow);
              }
              else{
                varObj['y']=canvas3.height*140/265;
                metricsQuota = ctx3.measureText(ranking_quota);
                quotaWidth = metricsQuota.width;
                
                // ctx3.fillStyle = "#fff";
                // console.log("before ranking quota");
                // console.log({varObj});
                adjustLine(ranking_quota,ctx3, varObj, maxWidth, txtheight,fontNow);

              }
            

             
              
                
                lineHeight = canvas3.height*25/265;
                ctx3.font = lineHeight+"px "+fontNow;
                metricsYear = ctx3.measureText(ranking_quota);
                quotaWidth = metricsYear.width;
                varObj.x = canvas3.width*125/300;
                varObj.y = canvas3.height*198/265;
                ctx3.fillStyle = "#2e4658";
                console.log("canvas3");
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
              txtheight = canvas4.height*40/265;
              
              // fontNow = "MyriadProRegular";
              ctx4.font = txtheight+"px "+fontNow;
              // loaded_face holds the loaded FontFace
              ctx4.fillStyle = "#2e4658";
              varObj['x']=canvas4.width*61/300;
              maxWidth = canvas.width*176/300;
              if(singular_title !== null){
                // console.log("not null");
                varObj['y']=canvas4.height*170/265;
                singular_title=singular_title.toUpperCase();
                completeText = ranking_quota + ' '+singular_title
                
                
                // ctx4.fillText(singular_title, canvas4.width*12/50, canvas4.height*225/475);
                
                
                
                wrapText(ctx4,completeText,varObj,maxWidth,txtheight,fontNow);
              }
              else{
                varObj['y']=canvas4.height*190/265;
                metricsQuota = ctx4.measureText(ranking_quota);
                quotaWidth = metricsQuota.width;
                
                // ctx4.fillStyle = "#fff";
                // console.log("before ranking quota");
                // console.log({varObj});
                adjustLine(ranking_quota,ctx4, varObj, maxWidth, txtheight,fontNow);

              }
            

             
              
                
                lineHeight = canvas4.height*25/265;
                ctx4.font = lineHeight+"px "+fontNow;
                metricsYear = ctx4.measureText(ranking_quota);
                quotaWidth = metricsYear.width;
                varObj.x = canvas4.width*128/300;
                varObj.y = canvas4.height*232/265;
                ctx4.fillStyle = "#2e4658";
                console.log("canvas4");
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
              txtheight = canvas5.height*6/81;
              
              // fontNow = "MyriadProRegular";
              ctx5.font = txtheight+"px "+fontNow;
              // loaded_face holds the loaded FontFace
              ctx5.fillStyle = "#fff";
              varObj['x']=canvas5.width*80/290;
              varObj['y']=canvas5.height*30/81;
              maxWidth = canvas.width*200/290;
              if(badgetype=='ranking'){
                // console.log("not null");
                
                // singular_title=singular_title.toUpperCase();
                completeText =  'Top '+singular_title;
                
                
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
              txtheight = canvas6.height*6/81;
              
              // fontNow = "MyriadProRegular";
              ctx6.font = txtheight+"px "+fontNow;
              // loaded_face holds the loaded FontFace
              ctx6.fillStyle = "#114668";
              varObj['x']=canvas6.width*80/290;
              varObj['y']=canvas6.height*60/81;
              maxWidth = canvas.width*200/290;
              if(badgetype=='ranking'){
                // console.log("not null");
                
                singular_title=singular_title.toUpperCase();
                completeText =  'TOP '+singular_title;
                
                
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
              adjustLine(completeText,ctx6, varObj, maxWidth, txtheight,fontNow);
            

            }

          });
           
            
            


          
            
            
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
function wrapText(context, text, varObj, maxWidth, lineHeight, fontName) { //https://www.html5canvastutorials.com/tutorials/html5-canvas-wrap-text-tutorial/
              var half=text.length/2;
              spacefound=false;
              for(i=half;spacefound==false && i<text.length;i++){
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
                adjustLine(lines[j], context, varObj, maxWidth, lineHeight,fontName);
                j++;
              }

            }
            
            function adjustLine(string, context, varObj, maxWidth, lineHeight,fontName){
              // console.log({varObj});
              console.log({string});
              console.log(context.font);
              printed=false;
              while(printed == false){
                // console.log("in the loop");
                var metrics = context.measureText(string);
                var line1Width = metrics.width;
                if (line1Width > maxWidth) {
                  lineHeight--;
                  context.font=lineHeight +"px "+fontName; 
                  console.log("adjusted");
                  console.log(context.font);
                
                }
                else {
                  xpluscenterpadding = (maxWidth-line1Width)/2;
                  console.log({xpluscenterpadding});
                  context.fillText(string, varObj.x+xpluscenterpadding, varObj.y);
                  varObj.y += lineHeight;
                  printed = true;
                  // break;
                  // notPrinted = false
                }
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