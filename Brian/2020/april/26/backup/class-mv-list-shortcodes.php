<?php
class Mv_List_Shortcodes {

    public function __construct(){
         // shortcode
         add_shortcode( 'search_and_compare', array( $this, 'search_compare' ) );
         add_shortcode( 'mv_search', array( $this, 'mv_search_shortcode') );	
         //list data
         add_action( 'wp_ajax_list_data', array( $this, 'list_data' ) );
         add_action( 'wp_ajax_nopriv_list_data', array( $this, 'list_data') );

         add_action( 'wp_ajax_list_compare_items', array( $this, 'list_compare_items' ) );
         add_action( 'wp_ajax_nopriv_list_compare_items', array( $this, 'list_compare_items' ));

         add_action( 'wp_ajax_list_compare_button', array( $this, 'list_compare_button' ) );
         add_action( 'wp_ajax_nopriv_list_compare_button', array( $this, 'list_compare_button' ));
        
         
         add_action( 'wp_ajax_get_bubbles', array( $this, 'get_bubbles') );
         add_action( 'wp_ajax_nopriv_get_bubbles', array( $this, 'get_bubbles' ));

         add_action( 'wp_ajax_claim_action', array( $this,'claim_action' ));
         add_action( 'wp_ajax_nopriv_claim_action', array( $this, 'claim_action') );
         
    }
    function claim_action() {
        $allTheClaimsID = 146580;
        //$posttitle = $_POST['postiddata'];
         $posttitle = $_POST['posttitle'];
    //			file_put_contents("debug.txt","posttile is   ".$posttitle  ,FILE_APPEND);
            $existing_options = get_field("claim_list2",$_POST['post_id']);
            $existing_options_all = get_field("claim_list2",$allTheClaimsID);
            // array_push($existing_options,$_POST['current_user']);
            $value = array("red", "blue", "yellow");
            $value = $existing_options."checking\n";
            echo "post id ". $_POST['post_id'];
    //			file_put_contents("debug.txt","postid is   ".$_POST['post_id']  ,FILE_APPEND);
            update_field("claim_list2",$existing_options_all."user Id of the claimant is : ".$_POST['current_user']." post id claimed is : ". $_POST['post_id']."\n",$allTheClaimsID );
            update_field("claim_list2",$existing_options."user Id of the claimant is : ".$_POST['current_user']."\n",$_POST['post_id']);
    //			file_put_contents("claimaction.txt","1",FILE_APPEND);
            $resp = array("postid" => $posttitle);
    //			file_put_contents("claimaction.txt","2",FILE_APPEND);
            echo "hi";
            echo json_encode($resp); 
    //			file_put_contents("claimaction.txt","3",FILE_APPEND);
            wp_die();
    //			file_put_contents("claimaction.txt","4",FILE_APPEND);
    }

    function get_bubbles() {		
		$list_id = $_POST['list_id'];
//		file_put_contents("debugbubbles.txt","list_id is   ".print_r($list_id,true ) );
		
    $popularityArray = array();
    if(is_array($list_id)){

    
		foreach($list_id as $li){
//			file_put_contents("debugbubbles.txt","list_id11 is   ".print_r($list_id,true ));
		 	$items_in_list = get_field("list_items",$li);
			$i=0;
				foreach($items_in_list as $iil){
					$thisFeatures = get_field( 'features_list', $iil->ID );
					// print_r($thisFeatures);
					foreach($thisFeatures as $tf){
//						file_put_contents("debugbubbles.txt","i is  $i ",FILE_APPEND);

						if(!array_key_exists($tf, $popularityArray))
							$popularityArray[$tf] = 1;
						else
							$popularityArray[$tf]++;

							}
						$i++;
					}
      }
    }
		// echo "bubbles";
		arsort($popularityArray);
		$popularityArray = array_slice($popularityArray,0,20);
		 wp_send_json($popularityArray);
	}
/*----------------------------------------------Software search functionality---------------------------------------------------------*/
function mv_search_shortcode() {
    //file_put_contents("debug.txt","mvshortcode",FILE_APPEND);
    ?>
        <form autocomplete="off" action="/action_page.php" class="search_form">
        <div class="autocomplete" style="width:300px;">
        <input id="myInput" type="text" name="software" placeholder="Enter your product name here..">
          <input type ="hidden" id = 'mv_search'> 
          <span id="myOutput"></span>
         </div>
        </form>	
    <?php
        global $wpdb;
        $custom_post_type = 'list_items'; // define your custom post type slug here
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", $custom_post_type ), ARRAY_A );
            foreach( $results as $index => $post ) {	
              $current_title = $post['post_title'];
              $current_id = $post['ID'];
              $current_link = get_permalink($current_id);
              $current_user = wp_get_current_user();
              $user_id_mv = $current_user->ID;
              $complex[] = $post['post_title']."|".$post['ID']."|".$current_link."|".$user_id_mv;
              //print_r($current_link);
            }
        ?>
      
    <script>  
        
         
          var search_list = <?php echo json_encode($complex); ?>;  
         
          function autocomplete(inp,inpmv,arr) {
                var currentFocus;
                inp.addEventListener("input", function(e) {
                var a, b, i, val = this.value;
                closeAllLists();
                if (!val) { return false;}
                   currentFocus = -1;     
                   a = document.createElement("DIV");
                    
                   a.setAttribute("id", this.id + "autocomplete-list");
                   a.setAttribute("class", "autocomplete-items");   
                   this.parentNode.appendChild(a);     
                     for (i = 0; i < arr.length; i++) {  
                          var ret = arr[i].split("|");		 
                          var post_title = ret[0];
                          var post_id = ret[1];
                          var post_link = ret[2];  
                          var user_id = ret[3];
    console.log(search_list);
              if (post_title.substr(0, val.length).toUpperCase() == val.toUpperCase()) {        
                    b = document.createElement("DIV"); 
                  b.setAttribute("class", "autocomplete_name");
                  
                    b.innerHTML = "<strong>" + post_title.substr(0, val.length) + "</strong>";
                    //b.innerHTML = "<a href=''" + post_link.substr(0, val.length) + "</a>";
                    b.innerHTML += post_title.substr(val.length);	
                    b.innerHTML += "<input type='hidden' value='" + post_title + "'>";
                    b.innerHTML += "<input type='hidden' value='" + post_id + "'>"; 	
                    b.innerHTML += "<input type='hidden' value='" + post_link + "'>";        
                        b.addEventListener("click", function(e) {
                            inp.value = this.getElementsByTagName("input")[0].value;
                            inpmv.value = this.getElementsByTagName("input")[1].value;
                            var link_mv = this.getElementsByTagName("input")[2].value;	
                            window.open(link_mv);            
                            var mv_postid = inp_id.value;         			   
                              closeAllLists();
                        });
                        a.appendChild(b);
                        d = document.createElement("DIV"); 
                        d.innerHTML += '<a class="claim-listing-button" onclick="claim_listing_func('+'1'+','+post_id+','+user_id+')" href="#">Claim Listing </a>';
                        a.appendChild(d);
                      }
                        }
                         c = document.createElement("DIV");         
                            c.innerHTML = '	<div class="items" ><span class="formheading-1">	DON\'T SEE WHAT YOU\'RE LOOKING FOR?	</span><p id="addnew"> Add a new product</p></div> ';
                         a.appendChild(c);
                          document.getElementById("addnew").addEventListener("click", function(e) {         
                          document.getElementById("acf-form").removeAttribute("hidden");
                          console.log("add a new product");
                          });
                        
                });
                  inp.addEventListener("keydown", function(e) {
                    var x = document.getElementById(this.id + "autocomplete-list");
                    if (x) x = x.getElementsByTagName("div");
                    if (e.keyCode == 40) {        
                      currentFocus++;        
                    addActive(x);
                    } else if (e.keyCode == 38) {
                      currentFocus--;       
                      addActive(x);
                        } else if (e.keyCode == 13) {
                          e.preventDefault();
                      if (currentFocus > -1) {         
                      if (x) x[currentFocus].click();
                      }
                        }
                    });
                          function addActive(x) {		
                          if (!x) return false;			
                          removeActive(x);
                          if (currentFocus >= x.length) currentFocus = 0;
                          if (currentFocus < 0) currentFocus = (x.length - 1);			
                          x[currentFocus].classList.add("autocomplete-active");
                          }
                          function removeActive(x) {			
                          for (var i = 0; i < x.length; i++) {
                            x[i].classList.remove("autocomplete-active");
                          }
                          }
                          function closeAllLists(elmnt) {
                          var x = document.getElementsByClassName("autocomplete-items");
    
                          for (var i = 0; i < x.length; i++) {
                            if (elmnt != x[i] && elmnt != inp) {
                            x[i].parentNode.removeChild(x[i]);
                          }
                          }
                        }
                        document.addEventListener("click", function (e) {
                          closeAllLists(e.target);
                        });
                    }
        autocomplete(document.getElementById("myInput"),document.getElementById("mv_search"), search_list);  
        function submitAll(){
          console.log("hello there");
          $('.acf-button')[3].click();
          console.log($('.acf-button')[3]);
        }
      </script>
    <?php 
    }
    public function search_compare(){
        ob_start(); ?>
          <style>
  .ui-autocomplete-loading {
    background: white url("https://assets.livrariacultura.net.br/assets/images/custom/ajax-loader-small.gif") right center no-repeat;
  }
  </style>
        <div class="container">
                <div id="loader-animate"><span>Loading...</span></div>
                <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <input type="text"  class="list-items-input"/> VS
                        <input type="text"  class="compare-items-input"/>
                        <a href="#" id="dynamic-compare" class="new-comparison-btn action_link action_compare ls_referal_link compare_search_btn" data-parameter="alternative" data-id="" data-secondary="">></a>
                    </div>
                <div class="col-md-2"></div>
        </div>
            
       <?php 
       $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
    public function list_data() {
        $q = strtolower($_POST['q']);
        $args = array(
            'post_type' => 'list_items',
            'orderby' => 'DESC', 
            'posts_per_page'=>-1, 
            'numberposts'=>-1,
            's'  => $q
        );
        $data = array();
        $query = get_posts( $args );
        foreach ( $query as $post ) : setup_postdata( $post );
             // $title = get_the_title($post->ID);
             // $find = strpos($title, $q);
             // if($find != false){
               $data[] = array(
                'item_id'=> $post->ID,
                'item'=> get_the_title($post->ID)
               );   
             // }    
       endforeach;
        wp_reset_postdata();
        echo json_encode($data);
	    wp_die();
    }
    public function list_compare_items(){
        $id = $_POST['item'];
        $cat = array();
        $category_detail=get_the_terms($id, 'list_categories');
        foreach($category_detail as $cd){
            array_push($cat, $cd->term_id); ;
        }
        $args = array(
            'post_type' => 'list_items',
            'orderby' => 'DESC', 
            'posts_per_page'=>-1, 
            'numberposts'=>-1,
            'tax_query' => array(
                array(
                  'taxonomy' => 'list_categories',
                  'field' => 'id',
                  'terms' => $cat, 
                  'include_children' => false
                )
              )
        );
        $data = array();
        $query = get_posts( $args );
        foreach ( $query as $post ) : setup_postdata( $post );
               $data[] = array(
                'item_id'=> $post->ID,
                'item'=> get_the_title($post->ID)
               );       
       endforeach;
       $key = array_search($id, array_column($data, 'item_id'));
        if($key !== false) {
             unset($data[$key]);
        }
        wp_reset_postdata();
        echo json_encode($data);
	    wp_die();
    }
    public function list_compare_button(){
        $id = $_POST['id'];
        $item = $_POST['item'];
        $url = generate_compare_link(array($id,$item));
        echo json_encode($url);
	    wp_die();

    }
   
}
new Mv_List_Shortcodes();