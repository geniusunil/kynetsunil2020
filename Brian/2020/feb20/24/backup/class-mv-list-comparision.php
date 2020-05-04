
<?php

class Mv_List_Comparision {

    private $item;
    private $comprisionid;
    private $comparison_slug;
    private $compareditems;
    private $itemlist;
    private $otheritems;
    private $table;
    private $db;
    private $category;
    private $templateId = "rwp_template_590f86153bc54";
    private $alternate = "";
	private $findrScoreArr= Array();
    private $ctaArr = Array();
    private $winner;
    private $loser;
    private $sorted_features_array;
    public function __construct($compareId=NULL,$ajax=false)
    {

        //session_start();
        $user_ip =  do_shortcode('[show_ip]');
        $_SESSION['user_ip'] = $user_ip;
      
        $this->setup_variables($compareId);
        if(!$ajax) {
            add_action('custom_comparison_content', array($this, 'show_comparison_contnet'));
            add_action('wp_head', array($this, 'add_javascript'));
            add_action('wp_enqueue_scripts', array($this, 'add_javascript_files'));
            if ( is_page( 'compare' ) ) {
                add_filter("wpseo_canonical", array($this, 'compare_canonical'));
                add_filter('wpseo_title', array($this, 'add_to_page_titles'));
				$settings    = get_option( 'mv_list_items_settings' );
       			$description = $settings['comparison_page_description'];
				if($description == "" ){
                	add_filter('wpseo_metadesc', array($this, 'get_compare_title_desc'));
				} else {
					add_filter('wpseo_metadesc', array($this, 'compare_title_desc'));
					
                }
                
            }
        }
    }
    
    function add_javascript(){
        ?><script  type="text/javascript">
            window.comparedItems = '<?php echo json_encode($this->itemlist);?>'
            window.comparisonId = '<?php echo $this->comprisionid;?>'
        </script>
        <?php
    } function add_javascript_files(){

}
    function check_comparison($compareID){

        $getitms = explode('-vs-',$compareID);
        $itemsArr = array();
        if(!empty($getitms)){
            foreach ($getitms as $item){
                if ( $post = get_page_by_path( $item, OBJECT, 'list_items' ) )
                    $id = $post->ID;
                else
                    $id = 0;
                if(!empty($id)){
                    $itemsArr[] = $id;
                }
            }
        }

        return $itemsArr;

    }
    public function setup_variables($compareId){
        global $wpdb;
        $this->comprisionid  = $compareId;
        $this->itemlist = $this->check_comparison($compareId);
        // file_put_contents("mvlc.txt","postarroriginal is: ".print_r($postsArrOriginal,true)."\n and postsArr is :".print_r($postsArr,true),FILE_APPEND);
        $postsArrOriginal = $this->itemlist;
        
        if(count($postsArrOriginal) ==2){
            $postsArr = $postsArrOriginal;
            sort($postsArr);
            // file_put_contents("mvlc.txt","postarroriginal is: ".print_r($postsArrOriginal,true)."\n and postsArr is :".print_r($postsArr,true),FILE_APPEND);

            if($postsArrOriginal !== $postsArr){
                $url = generate_compare_link($postsArr);
                print_r($postArr);
                wp_safe_redirect( $url, 301 );
                // exit;
            }
        }
        
        $this->comparison_slug  = genearate_post_slug($this->itemlist);
        foreach ($this->itemlist as $key => $it){
            $key1= $key+1;
            if($key == 0){
                $this->item = $it;
            } else{
                $this->otheritems['item'.$key1] = $it;
            }
            $this->compareditems['item'.$key1] = $it;
        }

        $this->db = $wpdb;
        $this->table = $this->db->prefix.'comparison';
        $this->reviewClass = new RWP_Rating_Stars_Shortcode();
        $campobj = get_the_terms( $this->item, 'list_comp_categories' );
        if ( $campobj && ! is_wp_error( $campobj ) ) {
            $this->comp_categories = $campobj;
            $this->category = $campobj[0]->term_id;
        }

        $post_id = $this->compareditems[item1];
        $post_id2 = $this->compareditems[item2];
        if(sizeof($this->findrScoreArr) == 0){
            $this->calculate_fs($post_id,$post_id2);
            $this->calculate_fs($post_id2,$post_id);
        }
        if($this->findrScoreArr[$post_id] > $this->findrScoreArr[$post_id2]){
            $this->winner = $post_id;
            $this->loser = $post_id2;
        }else{
            $this->winner = $post_id2;
            $this->loser = $post_id;
        }

    }
    
            
    public function  show_comparison_contnet(){

        $post_id = $this->get_item();
        $compareditem = $this->compareditems;
	

        $basedthings = $this->get_sections();

        // print_r($basedthings);
        $compCount = count($compareditem);
        $class = 'column-width-'.$compCount;

        ob_start();



        ?>
       
        <div id='loader-animate' style='display: none;'><span>Loading...</span></div>
        <div class="comparison-box">
            <div class="comparison-sidebar">
                <div class="comparison-criteria">
                    <p class="ctitle">Based on:</p>
                    <ul id="sidebar-main-menu">
                        <?php
                        foreach ($basedthings as $bb => $bbtitle){
                            if($bb !='hidden')
                            echo '<li><a href="#'.$bb.'" class="pricing_index">'.ucfirst($bb).'</a> </li>';
                        }
                        ?>
                    </ul>
                </div>
                <div class="comparison-criteria">
                    <p class="ctitle">Share with your network:</p>
                    <!-- Go to www.addthis.com/dashboard to customize your tools -->
                    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5b223494aa1691b9"></script>
                    <div class="addthis_inline_share_toolbox"></div>
                </div> <?php $compared = $this->most_compared($post_id,5,true);
                if(!empty($compared) && is_array($compared)):
                    $this->alternate = $compared[0];?>
                    <div class="frequest-comparison">
                        <p class="ctitle">Frequently compared with:</p>

                        <ul>
                            <?php foreach ($compared as $id){
 							$item_image = get_the_post_thumbnail_url($id, 'thumbnail' );?>
            
                               <?php  echo '<li><div class="image"><a href="javascript:;" class="get_compare_obj" data-val="'.$id.'"> <span class="cp-item1"><img src="'.$item_image.'" class="img-responsive" alt="'.get_the_title($id).'" ></span></a></div>';

                                echo '<div class="title_rat"> <a title="'.get_the_title($id).'" href="javascript:;" class="get_compare_obj" data-val="'.$id.'><span class="cp-title">'.get_the_title($id).'</span><i class="fa fa-plus-circle"></i> </a>';
                                echo do_shortcode( '[rwp_users_rating_stars id="-1"  template="'.$this->templateId.'" size=15 post='.$id.']' );

                                echo '</div></li>';
                            }?>
                        </ul>
                    </div>
                <?php endif;?>
            </div>
            <div class="comparison-container content-have-<?php echo $compCount?>">
                <div class="comparison-title"><h1>
                        <?php echo $this->get_compare_title();?></h1>
                </div>
                <div class="comparison-desc">

                    <?php echo $this->get_compare_title_desc();?>
                </div>
                <?php
                if($compCount <4){
                    echo "<div class='add-pro-con'><a href='javascript:;' id='add-products'  data-toggle='modal'  data-backdrop='static' data-show='false' data-target='#addItemsBox'><i class='fa fa-plus-circle'></i> Add Solutions</a> </div>";
                }
                ?>
                <div class="comparison_columns">
                    <div class="column-head">
						
                        <?php
						//$alcount = 1;
						
      					//$lists = $this->most_compared($post_id,20,true);
							
						// print_r($pricing_model);
							
                        foreach ($basedthings as  $it => $ittitle){
                            // echo "it is '$it'";
                            //$leftDetails = $this->get_left_column_details($it);
							if($it == "ratings") {
                            $item = $this->get_alternate_items_ratio();
                            $freetr =  $item['free'];
                            $total  =  $item['size'];
                            //$subs   = ($item['subscription'] / $total) * 100;
                            $subs   = $item['subscription'];
                            $freem  = $item['freemium'] ;
                            $openso = $item['open_source'] ;
                            $onet   = $item['one_time_license'];
							$total1 = $subs + $openso + $onet;  	
                            $subscription = ($subs/$total1) * 100;  
                            $onetime      = ($onet/$total1) * 100;
                            $free         = ($openso/$total1) * 100; 
                            $freetrial    = ($freetr/$total) * 100; 
                            $freemium     = ($freem/$total) * 100; 
							$post_id = $this->get_item();
                            
                       ?>
                                    
                                <input type="hidden" id="subscription" value="<?php echo $subscription;?>">
                                <input type="hidden" id="free" value="<?php echo $free ?>" />
                                <input type="hidden" id="onetime" value="<?php echo $onetime ?>" />
                                <input type="hidden" id="freetrial" value="<?php echo $freetrial ?>" />
                                <input type="hidden" id="freemium" value="<?php echo $freemium ?>" />
                                <input type="hidden" id="total" value="<?php echo $total ?>" />
                              
                        
                            <div class="charts_graph doughnut">
							<div id="canvas-holder" style="width:32%; float: left;">
                                <canvas id="chart-area"></canvas>
                            </div>
                            
                            <div id="canvas-holder" style="width:32%; float: left;">
                                <canvas id="chart-area1"></canvas>
                            </div>
                           
                           <div id="canvas-holder" style="width:32%; float: left;">
                                <canvas id="chart-area2"></canvas>
                           </div>
                           </div>
                           <div class="pricegraph">
                           <span class ="bartittle" >low </span> <span class ="bartittle"> Mid </span> <span class ="bartittle"> High  </span>
                           <canvas id="chart3" width="800" height="300" style="padding:20px;  box-shadow:2px 2px 2px rgba(0,0,0,0.2);"></canvas>
                           
                                  </div>
                                  
                        <?php 
                          $alternateinfo = $this->get_alternate_items_info($post_id);
                          $maximum =  max(array_column($alternateinfo, 'price'));
                          $minimum = min(array_column($alternateinfo, 'price'));
                          $max =   array_keys($alternateinfo, $maximum);
                         
                            foreach ($alternateinfo as $alternateinfos){
                                foreach($alternateinfos as $key=>$value){
                                    if($value == $maximum){
                                    $namemax = $alternateinfos['name'];
                                    $max_id = $alternateinfos['id'];
                                    }
                                    if($value == $minimum){
                                        $namemin = $alternateinfos['name'];
                                        $min_id = $alternateinfos['id'];
                                    }
                            }
                        }

                $lists = $this->most_compared($post_id,1000,true);
                $cont = 1;
                $l=0;
                $item_images = array();
				foreach($lists as $idss){
                    $pricing_model[$l] = get_field( 'pricing_model', $idss);
                    if(empty($pricing_model[$l])){
                        $pricing_model[$l] = array();
                    }
                    if(in_array('open_source', $pricing_model[$l])) {
                     $price_starting_from = 0;
                    }else{
                     $price_starting_from = get_field( 'price_starting_from', $idss);
                    }
                $item_pmodel[]['pmodel']= str_replace("$","",$price_starting_from);
                $cont++; 
                $l++;	
                } 
            
                $post_id = $this->compareditems[item1];
                $post_id2 = $this->compareditems[item2];
                $item_one = get_the_title($post_id);
                $item_two = get_the_title($post_id2);
                $ovr_all_rat1 = get_overall_combined_rating($post_id);
                $ovr_all_rat2 = get_overall_combined_rating($post_id2);

                # Post Id one ratings
                $valueformoney_1 = $ovr_all_rat1['list']['valueformoney']['score'];
                $easeofuse_1 = $ovr_all_rat1['list']['easeofuse']['score'];
                $customersupport_1 = $ovr_all_rat1['list']['customersupport']['score'];
                $featuresfunctionality_1 = $ovr_all_rat1['list']['featuresfunctionality']['score'];
                # Post Id two ratings
                $valueformoney_2 = $ovr_all_rat2['list']['valueformoney']['score'];
                $easeofuse_2 = $ovr_all_rat2['list']['easeofuse']['score'];
                $customersupport_2 = $ovr_all_rat2['list']['customersupport']['score'];
                $featuresfunctionality_2 = $ovr_all_rat2['list']['featuresfunctionality']['score'];
                

                   ?>
            <input type="hidden" id="maxo" value="<?php echo htmlspecialchars(json_encode($item_pmodel)) ?>" />
            
            <div class="graph_text">
             <div class="textgraph" style="text-align: left;">
                     After analyzing <?php echo $total; ?> similar solutions the data above show that <a href="<?php echo get_the_permalink( $min_id ) ?>"> <?php echo  $namemin;?></a> offers the lowesest starting 
                     price while <a href="<?php echo get_the_permalink( $max_id ) ?>"> <?php echo  $namemax;?></a> offers the highest entry price.That being said is also worthtaking a closer look at what's on offer
                     because sometimes you may get way more value for a solution with a higher entry price and vise versa.
                </div>
            </div>
            <div class = "" style="margin-top: 2em;">
                <p>
                    When evaluating weather to go with <?php echo $item_one; ?> or <?php echo $item_two; ?> which two criteria is most important to you.
                </p>
            </div>
            <div class ="dropdownsection" style="margin-top: 0.5em;">
                <p>
                    Please select from the following
                    <select class='dropdown-one'> 
                        <option value="valueformoney">Value For Money</option>
                        <option value="easeofuse">Ease Of Use</option>
                        <option value="customersupport">Customer Support</option>
                        <option value="featuresfunctionality">Features And Functionality</option>
                    </select>
                    and
                    <select class='dropdown-two'>
                        <option value="valueformoney">Value For Money</option>
                        <option value="easeofuse">Ease Of Use</option>
                        <option value="customersupport">Customer Support</option>
                        <option value="featuresfunctionality">Features And Functionality</option>
                    </select>
                </p>
                <div class='sec'></div>
            </div>
            <script>
            jQuery('.dropdown-one').on('change', function() {
                jQuery('#series_chart_div_1').removeClass('active');
            });
            </script>
            

           
            
       <div id="series_chart_div_1" style="width: 900px; height: 500px;" class="active"></div>
       <div id="series_chart_div_2" style="width: 900px; height: 500px;" class="hide_chart active"></div>
       <div id="series_chart_div_3" style="width: 900px; height: 500px;" class="hide_chart active"></div>
       <div id="series_chart_div_4" style="width: 900px; height: 500px;" class="hide_chart active"></div>
       <div id="series_chart_div_5" style="width: 900px; height: 500px;" class="hide_chart active"></div>
       <div id="series_chart_div_6" style="width: 900px; height: 500px;" class="hide_chart active"></div>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawSeriesChart);

                function drawSeriesChart(){

                var data = google.visualization.arrayToDataTable([
                    ['ID', 'Value For Money','Ease Of Use'],
                    [<?php echo json_encode($item_one); ?>,<?php echo json_encode($valueformoney_1); ?>,<?php echo json_encode($easeofuse_1); ?>],
                    [<?php echo json_encode($item_two); ?>,<?php echo json_encode($valueformoney_2); ?>,<?php echo json_encode($easeofuse_2); ?>],
                ]);

                var options = {
                    title: 'Correlation between life expectancy, fertility rate ' +
                        'and population of some world countries (2010)',
                    hAxis: {title: 'Value For Money',baseline:0 },
                    vAxis: {title: 'Ease Of Use',baseline:0},
                    bubble: {textStyle: {fontSize: 11}}};

                var chart = new google.visualization.BubbleChart(document.getElementById('series_chart_div_1'));
                chart.draw(data, options);
                }
            
            </script>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawSeriesChart);

                function drawSeriesChart(){

                var data = google.visualization.arrayToDataTable([
                    ['ID', 'Value For Money','Customer Support'],
                    [<?php echo json_encode($item_one); ?>,<?php echo json_encode($valueformoney_1); ?>,<?php echo json_encode($customersupport_1); ?>],
                    [<?php echo json_encode($item_two); ?>,<?php echo json_encode($valueformoney_2); ?>,<?php echo json_encode($customersupport_2); ?>],
                ]);

                var options = {
                    title: 'Correlation between life expectancy, fertility rate ' +
                        'and population of some world countries (2010)',
                    hAxis: {title: 'Value For Money',baseline:0 },
                    vAxis: {title: 'Customer Support',baseline:0},
                    bubble: {textStyle: {fontSize: 11}}};

                var chart = new google.visualization.BubbleChart(document.getElementById('series_chart_div_2'));
                chart.draw(data, options);
                }
            
            </script>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawSeriesChart);

                function drawSeriesChart(){

                var data = google.visualization.arrayToDataTable([
                    ['ID', 'Value For Money','Features and Functionality'],
                    [<?php echo json_encode($item_one); ?>,<?php echo json_encode($valueformoney_1); ?>,<?php echo json_encode($featuresfunctionality_1); ?>],
                    [<?php echo json_encode($item_two); ?>,<?php echo json_encode($valueformoney_2); ?>,<?php echo json_encode($featuresfunctionality_2); ?>],
                ]);

                var options = {
                    title: 'Correlation between life expectancy, fertility rate ' +
                        'and population of some world countries (2010)',
                    hAxis: {title: 'Value For Money',baseline:0 },
                    vAxis: {title: 'Features and Functionality',baseline:0},
                    bubble: {textStyle: {fontSize: 11}}};

                var chart = new google.visualization.BubbleChart(document.getElementById('series_chart_div_3'));
                chart.draw(data, options);
                }
            
            </script>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawSeriesChart);

                function drawSeriesChart(){

                var data = google.visualization.arrayToDataTable([
                    ['ID', 'Features and Functionality','Ease of use'],
                    [<?php echo json_encode($item_one); ?>,<?php echo json_encode($featuresfunctionality_1); ?>,<?php echo json_encode($easeofuse_1); ?>],
                    [<?php echo json_encode($item_two); ?>,<?php echo json_encode($featuresfunctionality_2); ?>,<?php echo json_encode($easeofuse_2); ?>],
                ]);

                var options = {
                    title: 'Correlation between life expectancy, fertility rate ' +
                        'and population of some world countries (2010)',
                    hAxis: {title: 'Features and Functionality',baseline:0 },
                    vAxis: {title: 'Ease of use',baseline:0},
                    bubble: {textStyle: {fontSize: 11}}};

                var chart = new google.visualization.BubbleChart(document.getElementById('series_chart_div_4'));
                chart.draw(data, options);
                }
            
            </script>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawSeriesChart);

                function drawSeriesChart(){

                var data = google.visualization.arrayToDataTable([
                    ['ID', 'Features and Functionality','Customer Support'],
                    [<?php echo json_encode($item_one); ?>,<?php echo json_encode($featuresfunctionality_1); ?>,<?php echo json_encode($customersupport_1); ?>],
                    [<?php echo json_encode($item_two); ?>,<?php echo json_encode($featuresfunctionality_2); ?>,<?php echo json_encode($customersupport_2); ?>],
                ]);

                var options = {
                    title: 'Correlation between life expectancy, fertility rate ' +
                        'and population of some world countries (2010)',
                    hAxis: {title: 'Features and Functionality',baseline:0 },
                    vAxis: {title: 'Customer Support',baseline:0},
                    bubble: {textStyle: {fontSize: 11}}};

                var chart = new google.visualization.BubbleChart(document.getElementById('series_chart_div_5'));
                chart.draw(data, options);
                }
            
            </script>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawSeriesChart);

                function drawSeriesChart(){

                var data = google.visualization.arrayToDataTable([
                    ['ID', 'Customer Support','Ease of use'],
                    [<?php echo json_encode($item_one); ?>,<?php echo json_encode($customersupport_1); ?>,<?php echo json_encode($easeofuse_1); ?>],
                    [<?php echo json_encode($item_two); ?>,<?php echo json_encode($customersupport_1); ?>,<?php echo json_encode($easeofuse_2); ?>],
                ]);

                var options = {
                    title: 'Correlation between life expectancy, fertility rate ' +
                        'and population of some world countries (2010)',
                    hAxis: {title: 'Customer Support',baseline:0 },
                    vAxis: {title: 'Ease of use',baseline:0},
                    bubble: {textStyle: {fontSize: 11}}};

                var chart = new google.visualization.BubbleChart(document.getElementById('series_chart_div_6'));
                chart.draw(data, options);
                }
            
            </script>
                            
                       <?php  }

         // echo "it is '$it'";
            if($it == "features") { 


                 /*//////feature rating slider/////////*/

               $item1= $this->compareditems['item1'];
               $item2= $this->compareditems['item2'];
               print_r($item1) ;
        // $features   = get_field( 'features_list', '136923' );
        // $features_ratings  = get_field( 'features_list_ratings', '136923' );
        $features   = get_field( 'features_list', $item1 );
        var_dump($features);
        $features_ratings1  = get_field( 'features_list_ratings', $item1 );
        $features_ratings2  = get_field( 'features_list_ratings', $item2 );
        if($features_ratings1 === NULL || empty($features_ratings1)){

            $features   = get_field( 'features_list', $item1 );
            var_dump($features);
            if($features !== NULL && !empty($features)){
                echo "fr1 caught!";
           
            $features_list_ratings = array();
            foreach($features as $mbv){
                // update_post_meta($post_id, 'features_list_ratings', array());
        
        
                $total_score = 0;
                $votes = 0;
                $average = 0;
                // $unusedFeatureListRatings = $featureListratings;
               
                $f_ = str_replace(" ","_",$mbv);    
                $features_list_ratings [$f_]= array('total_score' => $total_score,
                'votes' => $votes, 'average'=> $average);
            }
            $features_ratings1 = $features_list_ratings;
            // update_post_meta($post_id, 'features_list', $meta_box_value);
            update_post_meta($item1, 'features_list_ratings', $features_list_ratings);
            }
            
        }
        if($features_ratings2 === NULL || empty($features_ratings2)){
            $features   = get_field( 'features_list', $item2 );
            var_dump($features);
            if($features !== NULL && !empty($features)){
                echo "fr2 caught!";
            
                $features_list_ratings = array();
                foreach($features as $mbv){
                    // update_post_meta($post_id, 'features_list_ratings', array());
            
            
                    $total_score = 0;
                    $votes = 0;
                    $average = 0;
                    // $unusedFeatureListRatings = $featureListratings;
                   
                    $f_ = str_replace(" ","_",$mbv);    
                    $features_list_ratings [$f_]= array('total_score' => $total_score,
                    'votes' => $votes, 'average'=> $average);
                }
                $features_ratings2 = $features_list_ratings;
                // update_post_meta($post_id, 'features_list', $meta_box_value);
                update_post_meta($item2, 'features_list_ratings', $features_list_ratings);
            }


           
        }

        echo "<pre>";
        echo "$item1";
         var_dump($features_ratings1);
         echo "$item2";
        var_dump($features_ratings2);
        echo "</pre>";
       
    ?>

	    <div class="single-list-data zf-item-vote" data-zf-post-id="<?php echo '136923'?>">
        
		<style>
			.status{
				text-align:center;
			}
			.v-application--wrap
			{
				display: block !important;
				min-height: 0 !important;
			}
			.v-application{
				width:100%;
			}
		</style>

<!--feature rating slider end -->
<?php
                // echo "hi features";
              $total_feartures = array();
              $Each_features = array();
              $features_details = array();
              $features_ids = array();
              $j=0;
                foreach ($compareditem as $key => $cit){
                     $ax =  get_the_title($cit);
                     $total_feartures [] = get_field( 'features_list', $cit );
                     //print_r($total_feartures);
                     $Each_features[$j] =    get_field( 'features_list', $cit );
                     $features_details['tittle'.$j] = get_the_title($cit);
                     $features_ids['id'.$j] = $cit;
                     $j++;
                }
                $total_fearturess = array();
                foreach($total_feartures as $tt ){
                    if(empty($tt)){
                        $tt = array();
                    }
                $total_fearturess = array_merge($total_fearturess,$tt);
                }
                        $total_fearturess = array_values(array_unique($total_fearturess));
                        // echo "total fearturess";
                        // print_r($total_fearturess);
                        if(!empty($total_feartures)){  ?>
                       <div name="features" class="columns-heading"><h3 id="<?php echo $it?>"><?php echo $ittitle; ?></h3></div>
					   <h2>jasbir</h2>
          <?php          
                        if(isset($_SESSION['act'])){
                            $acts =   $_SESSION['act'];
                        }
                        if(isset($_SESSION['feature_btn_actions'])){
                            $feature_btn_name =  $_SESSION['feature_btn_actions'];
                        }else{
                            $feature_btn_name = array(); 
                        }  
                        if(isset($_SESSION['post_ids'])){
                            $session_post_ids =  $_SESSION['post_ids'];
                        }else{
                            $session_post_ids = array();
                        }          
                       
                       
                        $this->sorted_features_array = $this->sort_features($total_fearturess, $features_ids); // for both compared items
///////////////////////////////////sort features////////////////////////////////////// 
            // print_r($sorted_features_array);
/////////////////////////////////////////// --end-- /////////////////////////////////////////////////

//////////////////////////////////////// --Features Section-- //////////////////////////////////////
     $k = 1;
     $l = 0;  ?>
      
   <div class="row" id="app">
   <v-app>
   <?php
   foreach($this->sorted_features_array as $tt => $key){ 
    $t =   $key['feature'];
    ?>
    <div class="featurre2 column-width-3 column-sections" style="background-color:#fff; width:31%; margin-left: 17px; margin-top: 10px; padding: 18px; border: 1px solid #e6e6e6;">
    <h4> <?php echo $k.". ".$t; ?> <span><i class="fa fa-question-circle qmark" style="color:grey; margin-top: 2px;"></i></span> </h4>
    
    <div class="info hidden">This information is based on what our users have shared with us, in some cases, the solution in question could update its feature list which may not reflect here immediately.</div>
    <?php 
    $i=1;
    foreach($Each_features as $key => $Each_feature){ 
        if(empty($Each_feature)){
  $Each_feature = array();
        }
        
    ?>
    <li class="comp_theme"><?php if (in_array($t, $Each_feature)) { ?> <i class="fa fa-check-square" style="font-size:20px;color:green"></i> <?php }else{ ?> <i class="fa fa-remove" style="font-size:20px;color:red"></i><?php } ?><span>
    <?php echo $features_details['tittle'.$key]; $f_ = str_replace(" ","_",$t)."_$i";  $postid12= ($i==1)?$item1:$item2; ?> </span></li>
          <?php 
              if (in_array($t, $Each_feature)) {
            
              ?><v-slider
              v-on:click="greet"
                v-model="<?php echo $f_ ?>.val"
                :color="<?php echo $f_ ?>.color"
                :thumb-size="15"
                data-obj="<?php echo $f_ ?>"
                data-postid="<?php echo $postid12 ?>"
                data-postid= 
                step="1"
                thumb-label="always"
                ticks
                tick-size="10"
                min=1
                max=10
              
              ></v-slider>
                  <span class="status">{{<?php echo $f_ ?>.status}}</span> <?php }
       
          ?>
    
                  
                  

    <?php $i++;} ?>
    <button class="relevent" id="relavant_<?php echo $k; ?>" value="<?php echo $t; ?>" style="color: gray;" <?php if(in_array("relavant_$k", $feature_btn_name) && in_array($post_id, $session_post_ids)){ ?> disabled style="color: rgb(190, 190, 190);" <?php } ?>><i class="fa fa-caret-up" style="padding-top:2px;"></i>
Relevent</button>  <button class="irrelevent" id="irrelavant_<?php echo $k; ?>" value="<?php echo $t; ?>" style="color: gray;" <?php if(in_array("irrelavant_$k", $feature_btn_name) && in_array($post_id, $session_post_ids)){ ?> disabled style="color: rgb(190, 190, 190);" <?php } ?>><i class="fa fa-caret-down" style="padding-top:3px;"></i> Irrelevent</button>
    <input type="hidden" class="f_id" value="<?php  echo htmlspecialchars(json_encode($features_ids)) ?>"/> 
    <input type="hidden" class="f_name" value="<?php echo $t; ?>"/> 
                    </div>
    <?php  
        $l++;
        $k++; } 
     ?>
        </v-app>
    </div>
   <?php
        /*for item1*/
                $list_item = get_field('add_to_list',$item1);
        // $list_item  = $this->get_item_ranks($post_id);

        foreach($list_item as $key => $post_id_item){
            $all_item[] = $post_id_item->ID;
             }
             $list_item = $all_item; 

       // print_r($list_item);
        foreach($list_item as $key => $lists){
           $post_ids = get_field('list_items' ,$lists);   
           foreach($post_ids as $post_id_item){
          $all_item_id[] = $post_id_item->ID;
           }    
        
         } 

        
        ?> <br/><?php
        
        /*for item2*/
        $list_items = get_field('add_to_list',$item2);
        // $list_item  = $this->get_item_ranks($post_id);

        foreach($list_items as $key => $post_id_item){
            $all_item2[] = $post_id_item->ID;
             }
             $list_items = $all_item2; 


       // print_r($list_item);
        foreach($list_items as $key => $lists){
           $post_ids = get_field('list_items' ,$lists);   
           foreach($post_ids as $post_id_item){
          $all_item_id[] = $post_id_item->ID;
           }    
        
         } 
        //print_r($all_item_id);

        /** Array uniqe */
        // $list1 = array_merge($list_item, $list_items);
        $list = array_unique($all_item_id);
       // echo "mege list";?><br/>
        
   <?php 
 

    $i=0;
    $feature_all_ratings= array();
      foreach($this->sorted_features_array as $feature_list_id)
  {
    $fn_ = str_replace(" ","_",$feature_list_id[feature]);
      $feature_names  []= $fn_;

        //1. Get feature list rating of each item in all_list_id


        // print_r($list);
     /** feature rating avrage */
    
    
    
     foreach($list as $key=>$value){
        //  echo"value id";
        //  print_r($value);
        $features_ratings  = get_field( 'features_list_ratings', $value );
        if($features_ratings === NULL || empty($features_ratings)){
             $features   = get_field( 'features_list', $value );
            // var_dump($features);
            if($features !== NULL && !empty($features)){

                $features_ratings = create_feature_ratings($value,$features);
                echo "fr1 caught!";

                

           
           
         
            }
             
        }
        $avrage= $features_ratings[$fn_]['average'];
        if($avrage!=0 && !empty($avrage))
        {
            $feature_all_ratings[$fn_][$value]= $avrage;
            // echo "null value";
        }
        // $feature_all_ratings[$fn_][$value]= $avrage;
        // print_r($feature_all_ratings);



        //echo $value;
        //print_r($features_ratings);

    }

      $i=$i+1;
      if($i>=3){
    break;
      }
      
   }
//    echo "avrage score";
// print_r( $feature_all_ratings);
 /**total score */  
$avg_feature = array(); 
foreach($feature_all_ratings as $key => $fv)
{
	$count=count($fv);
   
	foreach($fv as $feature_array)
	{
		$a += $feature_array;
		
	}
	$avg = $a/$count;
	$avg_feature[$key]=$avg;
	$a=0;
}

	echo'avg feature';
	echo'<pre>';
	print_r($avg_feature);
	echo'product 1';
	print_r($features_ratings1);
	echo'product 2';
	print_r($features_ratings2);

	$feature1_winner;
	$feature1_winner_item;
	$feature1_loser_item;
	$featureboth_loser;
	$stillaboveindavg;
	$feature_both_notexist;
	$item1scorearr = array();
	$item2scorearr = array();


	$avg_feature_orig = $avg_feature;
	//winner winner
	foreach($avg_feature as $key => $values)
	{
		if(isset($features_ratings1[$key]) && isset($features_ratings2[$key]))
		{
			if($features_ratings1[$key]['average'] > $features_ratings2[$key]['average'])
			{
				$feature1_winner = $key;
				$feature1_winner_item = $item_one;
				$feature1_loser_item = $item_two;
				if($values >  $features_ratings2[$key]['average'])
				{
					$stillaboveindavg = false;
				}
				else
				{
					$stillaboveindavg = true;
				}
				unset($avg_feature[$key]);
				break;
			}
			elseif($features_ratings1[$key]['average'] < $features_ratings2[$key]['average'])
			{
				$feature1_winner = $key;
				$feature1_winner_item = $item_two;
				$feature1_loser_item = $item_one;
				
				if($values >  $features_ratings1[$key]['average'])
				{
					$stillaboveindavg = false;
				}
				else
				{
					$stillaboveindavg = true;
				}
				unset($avg_feature[$key]);
				break;
			}
		}	
	}
	//featureboth_loser
	foreach($avg_feature as $key => $total_avg)
	{
		$item1_score = $features_ratings1[$key]['average'];
		$item2_score = $features_ratings2[$key]['average'];
		if($total_avg > $item1_score && $total_avg > $item2_score)
		{
			$featureboth_loser = $key;
			unset($avg_feature[$key]);
			break;
		}
	}

	//feature_both_notexist
	foreach($avg_feature as $key => $values)
	{
		if (!array_key_exists($key,$features_ratings1) && !array_key_exists($key,$features_ratings2))
		{
			$feature_both_notexist = $key;
			break;
		}
	}

	
	echo "item one" . $item_one;
	echo "item two" . $item_two;
	echo "stillevotes" . $stillaboveindavg;
	var_dump($stillaboveindavg);
	echo "testing111" . $feature1_winner;
	$feature1_winner = str_replace("_", " ", $feature1_winner);

	echo "both loser" . $featureboth_loser;
	echo '<br>';
	echo "both no exist" . $feature_both_notexist;
	?>
    <p style="margin-top:10px;">“We have ordered the list of features above by what users are telling us is more important to them. Technology is fast-moving so we always suggest that you check <a href="<?php echo get_permalink( $item1 );?>"><?php echo get_the_title( $item1 ); ?> </a>and <a href="<?php echo get_permalink( $item2 );?>"><?php echo get_the_title( $item2 ); ?> </a> details page for any changes not mentioned here.
	Looking at the top 3 features <?php if(!empty($feature1_winner)){?>when it comes to <?php echo $feature1_winner; ?> <?php echo $feature1_winner_item; 
	?> wins the vote<?php if ($stillaboveindavg){?>that being said <?php echo $feature1_loser_item; ?> is still voted well above the industry average.<?php }} if(!empty($featureboth_loser)){?> Look at <?php echo $featureboth_loser; ?> both <?php echo $item_one; ?> and <?php echo $item_two; ?> scores below the industry.<?php } if(! empty($feature_both_notexist)){?> With regards to <?php echo $feature_both_notexist; ?> neither solution has been recorded on our system has provided this feature<?php }?></p>

    <script>
               var vm = new Vue({
                  el: '#app',
                  vuetify: new Vuetify(),
                  data: {
                    <?php 
                    foreach($features_ratings1 as $f => $r){ 
                        $label = $f;
                        $f_ = str_replace(" ","_",$f);    
                        
                        $score = $r[average];
                        echo $f_."_1 : {label : '$label', val : $score, color: 'green' ,status:''}" ?>,
                  <?php } 
                   foreach($features_ratings2 as $f => $r){ 
                    $label = $f;
                    $f_ = str_replace(" ","_",$f);    
                    
                    $score = $r[average];
                    echo $f_."_2 : {label : '$label', val : $score, color: 'green' ,status:''}" ?>,
              <?php } 
                        
                  
                  
                  ?>
                      
                    },
                    methods: {
    greet: function (event) {
        
        console.log(event);
      // `this` inside methods points to the Vue instance
    //   alert('Hello ' + this.name + '!')
      // `event` is the native DOM event
        //   jQuery(this).closest('.zf-item-vote').addClass('zf-loading');
        console.log(event.target.closest('.v-input__control'));
        var input = event.target.closest('.v-input__control').querySelectorAll('input')[0];
        var feature_name = input.getAttribute("data-obj");//(jQuery(event.target).find('input')[0]).getAttribute("data-obj");
        var feature_label = (vm._data[feature_name]).label;
        (vm._data[feature_name]).color = 'yellow';
        (vm._data[feature_name]).status = 'Please wait....';

        console.log(feature_name);
     
        // console.log();

        var vote= (vm._data[feature_name]).val;

        var zf_post_id = input.getAttribute("data-postid");//document.querySelectorAll('.zf-item-vote')[0].getAttribute("data-zf-post-id");
        console.log(zf_post_id);
        // var zf_post_parent_id = jQuery(this).closest('.zf-item-vote').attr("data-zf-post-parent-id");


   


        console.log("before ajax vote up");
        jQuery.ajax({

            url: '<?php echo admin_url('admin-ajax.php') ?>',

            type: 'POST',

            data: {post_id: zf_post_id, feature_name : feature_label, vote_size : vote, action: 'mes-lc-feature-rate'},

            dataType: 'json',

            success: function (data) {
                console.log(data);
                (vm._data[feature_name]).val = data['rating'];
                (vm._data[feature_name]).color = 'green';
                (vm._data[feature_name]).status = 'Thanks for voting!';
                console.log("success vote up");
                // if (data.voted) setVoteCount(data, true);

                // jQuery(".zf-vote_count[data-zf-post-id='"+data.post_id+"']").closest('.zf-item-vote').removeClass('zf-loading');

            }

        });
    }
  }
    

                });
              
                // vm.greet();

              </script>
    <script>
        console.log("i tried so hard");
    jQuery('.qmark').mouseenter(function(){

    jQuery(this).closest(".featurre2").find('.info').removeClass('hidden');
    })
    jQuery('.qmark').mouseleave(function(){
        jQuery(this).closest(".featurre2").find('.info').addClass('hidden');
    })

     jQuery('.relevent').click(function(){
        jQuery(this).css('color','rgb(190, 190, 190)');
         var xx = jQuery(this).attr("value");  
         var idss = jQuery('.f_id').val();
         var obj = JSON.parse(idss);
         var arr = [];
			i = 0;
			jQuery.each(obj, function (key, data1) {
			  arr[i++]  =data1;	
			});
         var f_name = jQuery('.f_name').val();
         var btn_id = this.id;
         var data1 = {
			'action': 'my_action',
			'post_ids': arr,
            'feature_name': xx,
            'votes': 1,
            'act' : 'relevent',
            'btn_id': btn_id
            
		};

        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		jQuery.post(ajaxurl, data1, function(response) {
		});
        var btn_id = '#ir'+btn_id;
        jQuery(this).prop('disabled',true);
        jQuery(btn_id).prop('disabled',false);
    }); 

        jQuery('.irrelevent').click(function(){
        jQuery(this).css('color','rgb(190, 190, 190)');
         var xx = jQuery(this).attr("value");  
         var idss = jQuery('.f_id').val();
         var obj = JSON.parse(idss);
         var arr = [];
			i = 0;
			jQuery.each(obj, function (key, data1) {
			arr[i++]  =data1;
			});
         var f_name = jQuery('.f_name').val();
         var btn_id = this.id;
         var data1 = {
			'action': 'my_action',
			'post_ids': arr,
            'feature_name': xx,
            'votes': 1,
            'act' : 'irrelevent',
            'btn_id': btn_id
            
		};
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		jQuery.post(ajaxurl, data1, function(response) {
		
		});
        
        var itemId = btn_id.substring(2, btn_id.length);
        var btn_id = '#'+ itemId;
        jQuery(this).prop('disabled',true);
        jQuery(btn_id).prop('disabled',false);
    });

</script>

                        <?php  }
///////////////////////////////////////////// --end-- ///////////////////////////////////////////////////////                     
                }
                elseif($it=="map"){
                $list_setting = get_option( 'mv_list_items_settings' );
                $target_countries = $list_setting['list_page_target_countries'];
                // print_r($target_countries);
                $countryPair = explode(',',$target_countries);
                // print_r($countryPair);
                foreach($countryPair as $cp){
                    $pair = explode('=>',$cp);
                    foreach($pair as $key=>$value){
                        $pair[$key]=trim(trim($value),"'");
                    }
                    $targetCountries[$pair[0]] = $pair[1];
                } 
                $post_id = $this->compareditems[item1];
                $post_id2 = $this->compareditems[item2];
                $item_one = get_the_title($post_id);
                $item_two = get_the_title($post_id2);
                $passToMaps = array();
                $passToMaps[]= array('Country',$item_one,$item_two);

                    $count_one = 0;
                    $count_two = 0;
                    $win_con1 = array();
                    $win_con2 = array();
                    $final1 = "hasn't got a lead over ". $map_winner ." in any country.";
                    $final2 = "hasn't got a lead over ". $map_winner ." in any country.";
                foreach($targetCountries as $key=>$tc){
                    $downloads1 = ceil((float)get_field( 'downloads_in_'.$key, $post_id));
                    $downloads2 = ceil((float)get_field( 'downloads_in_'.$key, $post_id2));
                    if ($downloads2 == '') {
                        $downloads2 = 0;
                    }
                    if ($downloads1 == '' ) {
                        $downloads1 = 0;
                    }
                    $passToMaps[]= array($key,$downloads1,$downloads2);
                    
                    if ($downloads1 > $downloads2) {
                        $count_one++;
                        $win_con1[] .= $tc;

                    
                       

                    }elseif($downloads1 < $downloads2){
                        $count_two++;
                        $win_con2[] .= $tc;
                        
                    }

                

                }
                if(count($win_con1) == 0){
                    $final1 = "hasn't got a lead over ". $item_two ." in any country.";
                }
                elseif (count($win_con1) <= 3) {
                    $output1 = implode(', ', $win_con1);
                    $final1 =   "is more popular in following countries- ". $output1;
                }elseif(count($win_con1) > 3){
                    $slicedarray = array_slice($win_con1, 1, 3);
                    $output1 = implode(', ', $slicedarray);
                    $plus_con = $count_one-3;
                    $final1 = "is more popular in countries including ". $output1." and ".$plus_con ." other Countries";
                }

                if(count($win_con2) == 0){
                    $final2 = "hasn't got a lead over ". $item_one ." in any country.";
                }
                elseif(count($win_con2) <= 3) {
                    $output2 = implode(', ', $win_con2);
                    $final2 = "is more popular in following countries - ". $output2;
                }elseif(count($win_con2) > 3){
                    $win_con2 [] = $tc;
                    $slicedarray = array_slice($win_con2, 1, 3);
                    $output2 = implode(', ', $slicedarray);
                    $plus_con = $count_two-3;
                    $final2 = "is more popular in countries including ". $output2." and ". $plus_con ." other Countries";

                }
                $win_col = '#164f72';
                $los_col = '#000';
                if ($count_one >= $count_two) {
                    $map_winner = $item_one;
                    $map_loser = $item_two;
                    $count_win = $count_one;
                    $count_loss = $count_two;
                    $win_final = $final1;
                    $los_final = $final2;
                }elseif($count_one < $count_two){
                    $map_winner = $item_two;
                    $map_loser = $item_one;
                    $count_win = $count_two;
                    $count_loss = $count_one;
                    $win_final = $final2;
                    $los_final = $final1;
                } 
               /*  echo "<pre>";
                print_r($passToMaps);
                echo "</pre>"; */

                    // echo "los final $los_final";
                    // $passToMaps [0]= array("country",$item_1,$item_2);
                    // $passToMaps [1]= array("IN",$downloads1,$downloads2);
                    ?>
                <div class="columns-heading" aling='center'><h3 id="<?php echo $it?>">Adoption By Geography</h3></div>
                
                    <div class='row'>
                        <div class='col-md-6 py-5'>
                        <p class='h4'>According to our data gathered on our platform 
                        <b style="color:#000;"><?php echo $map_winner; ?></b> <?php echo $win_final; ?>
                        </p>    
                        </div>
                        <div class='col-md-6 py-5'>
                        <p class='h4'>According to our data gathered on our platform 
                            <b style="color:#000;"><?php echo $map_loser; ?></b> <?php echo $los_final; ?></b>
                        </p>
                        </div>
                    </div>
                    

                    <div id="regions_div" style="width: 900px; height: 500px;"></div>
                    <script>
                            google.charts.load('current', {
                                'packages':['geochart'],
                                'mapsApiKey': 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'
                            });
                            google.charts.setOnLoadCallback(drawRegionsMap);

                            function drawRegionsMap() {
                                var data = google.visualization.arrayToDataTable(
                                    <?php echo json_encode($passToMaps) ?>
                                );

                                var options = {
                                    colors: [<?php echo json_encode($los_col) ?>, <?php echo json_encode($win_col) ?>],
                                    legend: 'none',
                                    datalessRegionColor: '#fff',
                                };

                                var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

                                chart.draw(data, options);
                            }

                    </script>
                    <div class='row'>
                        <div clas="col-md-12 py-5 text-center">
                            <p>In order to give you more personalized recommendations, we tailor the 
                            ranking of a product to reflect what users are currently using in your geographical area.</p>
                        </div>
                    </div>
                    <?php
                }
                    else{
        ?>
        
            
            <div class="column-container-list column-<?php echo $it?>">
            
                <div class="columns-heading"><h3 id="<?php echo $it?>"><?php echo $ittitle; ?></h3></div>
                            
                <div class="column-section-container">
                
                    <?php

    
                    
                    $compareditemnew=Array();


                    foreach ( $compareditem as $key => $cit){
                        $compareditemnew []= [$key, $cit];
                        
                        
                        }           
                        // print_r($compareditemnew[0]);
                    // foreach ($compareditem as $key => $cit){
                    for ($i=0;$i<2;$i++){
                        $key =  $compareditemnew[$i][0];
                        
    
                        $cit = $compareditemnew[$i][1];
                        $key2 =  $compareditemnew[1-$i][0];
                        $cit2 = $compareditemnew[1-$i][1];
                        $methodName = 'get_column_'.$it;
                        $data='';
                        if(!empty($cit)){
                            $data = $this->{$methodName}($cit,$key,$cit2);
                            
                        }else{
                            if($it =='title'){
                                $data = $this->get_compare_dropdown($key);
                            }
                        }
                        
                                    
                        
                        echo "<div class='$class column-sections column-$it-$key column-sec-$it' data-mh='equal-height-col-$it' data-key='$key'>$data</div>";

        
                    }?>
                </div>
            </div>
            <?php
                    }
                    }
                        ?>
                    </div>
                    <div class="faqs">
                    <?php echo $this->get_column_faq(); ?>
                    </div>
                
                <div class="alernatives_items" style="margin-top: 3em;">
                  
                    <?php echo $this->get_alternate_items(); ?>
                </div>
                <?php $cmpList = $this->most_compared($post_id,20);
                $title = get_the_title($post_id);
                if(!empty($cmpList) && is_array($cmpList)){
                    echo "<div class='comparison-pool'><h3>Related Comparison</h3><ul class='pool-list'> ";
					
                    foreach ($cmpList as $cmp){
                        $singTitle = $title.' VS '.get_the_title($cmp);
echo '<li><a  href="'.generate_compare_link(array($post_id,$cmp)).'" class="new-comparison-btn ls_referal_link" data-parameter="comparison" data-rid="'.$this->comparison_slug.'" data-id="'.$post_id.'" data-secondary="'.$cmp.'" title="'.$singTitle.'">'.$singTitle.'</a></li>';
                    }
                    echo "</ul></div>";
                }
                ?>
<div class="compare-rating">	<p>
	<strong>Was this comparison helpful?</strong>
	</p>
	<?php if(function_exists('the_ratings')) { the_ratings(); } ?></div>
            </div>

        </div>
        <div id="addItemsBox" class="modal add_items_model" role="dialog" style="display: none;">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add more items</h4>
                    </div>
                    <div class="modal-body">

                        <div id="add-review-list">

                            <!-- class="search" automagically makes an input a search field. -->
                            <input class="search" placeholder="Search" />
                            <!-- class="sort" automagically makes an element a sort buttons. The date-sort value decides what to sort by. -->
<!--                            <button class="sort" data-sort="name">-->
<!--                                Sort-->
<!--                            </button>-->

                            <!-- Child elements of container with class="list" becomes list items -->
                            <ul class="list">
                                <?php $comCon = $this->can_be_compared();
                                foreach ($comCon as $idd){
									$item_image = get_the_post_thumbnail_url($idd);?>
                                  <?php   echo '<li><div class="itemSec"><div class="img-sec"><img src="'.$item_image.'" class="img-responsive" alt="'.get_the_title($idd).'" ></div> <p class="name">'.get_the_title($idd).'</p></div> 
                        <div class="buttonSec"><button class="get_compare_obj" data-val="'.$idd.'">Add</button></div>
                    </li>';
                                }
                                ?>

                            </ul>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <?php
        $content = ob_get_contents();
        ob_get_clean();
        echo $content;
		?>
		  <?php /*--------------------------*/
		 
 echo ' <div class="comparison_fix_footer compair">
 <a href="javascript:;" class="compare_close"><i class="fa fa-times-circle"></i> </a>
 <div class="compare_items"> <div style="display:table" ><div style="display:table-row" ><div style="display:table-cell; vertical-align:middle; font-family: Roboto;"></div>'." ";
 $n =0;
 //print_r($compareditem);
               foreach ($compareditem as $ind => $cmp){
				   $image1 = get_the_post_thumbnail_url($cmp,array(30,30));
				  
                    echo '<div style="display:table-cell; vertical-align:middle" ><span class="cp-item cp-item'.($ind+1).'" title="'.get_the_title($cmp) .'"> <img style="max-width:50px;" src="'.$image1.'" class="img-responsive sss" alt="'.get_the_title($cmp).'" ></span></div>';
					echo '<div class="pro_name"><h5>'.get_the_title($cmp) .'</h5></div>';
                    if(sizeof($this->ctaArr) < 2){
                        $this->calc_cta($cmp);
                    }
        
					echo '<div class="compare_btn single-btn"> <a class="mes-lc-li-down aff-link" href="'. $this->ctaArr[$cmp]['afflink'].'" rel="nofollow" target="_blank">'.$this->ctaArr[$cmp]['btntext'].'</a></div>';
					$n++;
					if($n==1){
						echo '<div style="display:table-cell; vertical-align:middle" ><span class="cp-vs-inner2">vs</span> </div>';
					}
               }

                echo '</div></div></div></div>';
    }

    public function get_item(){
        return $this->item;
    }

    public function set_comparison_item($id){
        foreach ($this->compareditems as $it){
            $this->db->insert($this->table,array('item1'=>$it,'item2'=>$id,'time'=>time()));

        }
    }
    public function get_compared_item(){

        return $this->compareditems;
    }
    public function get_other_item(){

        return $this->otheritems;
    }
    public function itemlist(){

        return $this->itemlist;
    }
    // function get_item_ranks($pId){
    //     $lists =  get_field( 'add_to_list', $pId, false );
    //     $itemiid = $pId;
    //     $listrankord = array();
    //     if ( !empty ( $lists ) && is_array( $lists ) ) {
    //         foreach ( $lists as $id ) {
    //             if($this->acme_post_exists($id)){
    //                 $rank = get_item_rank($id,$itemiid);
    //                 $listrankord[$id] = $rank;
    //             }
    //         }
    //         asort($listrankord);
    //     }   
    //     return   $listrankord;
       
    // }
    // function acme_post_exists( $id ) {
    //     return is_string( get_post_status( $id ) );
    // }
    public function price_filter($alterItems){
        $i=0;
        $alterItems1 = array();
        foreach ( $alterItems as $pid ) {
         $a = get_field( 'price_starting_from', $pid);
         $a = str_replace("$","",$a);
         $alterItems1[$pid]= $a;
        }
        asort($alterItems1);
        //print_r($alterItems1);
        foreach ( $alterItems1 as $key=>$pid ) {
            $alterItems[$i]= $key;
           $i++;
           }
           return $alterItems;
    }
    public function most_compared($post_id ='',$number = 5,$outcurrent = false){
        if(empty($post_id)){
            $post_id = $this->item;
        }
        if($outcurrent){
            $unique = $this->otheritems;
            if(!empty($unique)){
            $in = implode(',',$unique);
            $sql = "SELECT CASE WHEN item1 = '$post_id' THEN item2 ELSE item1 END AS items
FROM $this->table WHERE (item1='$post_id' OR item2='$post_id') AND((SELECT CASE WHEN item1 = '$post_id' THEN item2 ELSE item1 END) NOT IN ($in)) AND (item1 !='$post_id' OR item2!='$post_id') ";
     }else{
        $sql = "SELECT CASE WHEN item1 = '$post_id' THEN item2 ELSE item1 END AS items
        FROM $this->table WHERE (item1='$post_id' OR item2='$post_id') AND (item1 !='$post_id' OR item2!='$post_id')";
     }
       } else{
            $sql = "SELECT CASE WHEN item1 = '$post_id' THEN item2 ELSE item1 END AS items
FROM $this->table WHERE (item1='$post_id' OR item2='$post_id') AND (item1 !='$post_id' OR item2!='$post_id')";
        }

        $complist =  $this->db->get_results($sql,ARRAY_A);
        $comparr = array();

        if(!empty($complist)){
            $ocuuranceArr  = array_flatten($complist);
            $reduce = array_count_values($ocuuranceArr);
            arsort($reduce);
            $finalArr = array_slice($reduce, 0, $number,true);
            $comparr = array_keys($finalArr);

        }
        if(count($comparr) == $number){
            return $comparr;
        }else{
            $done = count($comparr);
            $num = $number - $done;
            $already = $comparr;
            $already[] = $post_id;
            $compared =   $this->can_be_compared($post_id,false,$num,$already);
            return array_merge($comparr,$compared);
        }
    }
    public function most_compared_rating($post_id =''){

        $overallrating = array();
        $valueformoney = array();
        $easeofuse = array();
        $featuresfunctionality = array();
        $customersupport = array();
        if(empty($post_id)){
            $post_id = $this->item;
        }

            $sql = "SELECT CASE WHEN item1 = '$post_id' THEN item2 ELSE item1 END AS items
FROM $this->table WHERE (item1='$post_id' OR item2='$post_id') AND (item1 !='$post_id' OR item2!='$post_id')";


        $complist =  $this->db->get_results($sql,ARRAY_A);
        $comparr = array();

        if(!empty($complist)){
            $ocuuranceArr  = array_flatten($complist);
            $reduce = array_count_values($ocuuranceArr);
            arsort($reduce);
//            $finalArr = array_slice($reduce, 0, $number,true);
            $comparr = array_keys($reduce);

        }
        if(count($comparr) < 5){
            $already = $comparr;
            $already[] = $post_id;
            $compared =   $this->can_be_compared($post_id,false,-1,$already);
            $comparr =  array_merge($comparr,$compared);
        }
         foreach ($comparr as $it){
           $rating =  get_overall_combined_rating($it);

               if(isset($rating['list']['overallrating']['score'])){
                   $overallrating[$it] =$rating['list']['overallrating']['score'];
               } else{
                   $overallrating[$it] = 0;
               }
              if(isset($rating['list']['valueformoney']['score'])){
                   $valueformoney[$it] =$rating['list']['valueformoney']['score'];
               } else{
                  $valueformoney[$it] = 0;
              }
              if(isset($rating['list']['easeofuse']['score'])){
                   $easeofuse[$it] =$rating['list']['easeofuse']['score'];
               } else{
                  $easeofuse[$it] = 0;
              }
              if(isset($rating['list']['featuresfunctionality']['score'])){
                  $featuresfunctionality[$it] = $rating['list']['featuresfunctionality']['score'];
               } else{
                  $featuresfunctionality[$it] = 0;
              }
              if(isset($rating['list']['customersupport']['score'])){
                   $customersupport[$it] =$rating['list']['customersupport']['score'];
               } else{
                  $customersupport[$it] = 0;
              }


        }
        arsort($overallrating);
        arsort($valueformoney);
        arsort($easeofuse);
        arsort($featuresfunctionality);
        arsort($customersupport);
        $ratArr = array(
            'overallrating'=>array_slice($overallrating, 0, 5,true),
            'valueformoney'=>array_slice($valueformoney, 0, 5,true),
            'easeofuse'=>array_slice($easeofuse, 0, 5,true),
            'featuresfunctionality'=>array_slice($featuresfunctionality, 0, 5,true),
            'customersupport'=>array_slice($customersupport, 0, 5,true),
        );
        return $ratArr;
    }
    public function compare_canonical($url){ 
        if(is_page('compare')){
            $uri = $_SERVER['REQUEST_URI'];
            $uri = ltrim($uri,'/');
            $site_url = site_url('/');
            $url = $site_url.$uri;
            $url = str_replace("http://","",$url);
            return  $url;
        }
        return  $url;
    }

    public function get_compare_title(){
        $count = 0 ;
        ob_start();
        foreach ($this->compareditems as $key => $ictem) {
            if (!empty($ictem)) {
                echo $count!=0?  ' vs ':'';
                echo  get_the_title($ictem);
                $count++;
            }
        }
        $con = ob_get_contents();
       
		
        $settings = get_option( 'mv_list_items_settings' );
        $title_get = $settings['comparison_page_title'];
		if($title_get == "") {
				 $con .= " detailed comparison as of ".date('Y');
				ob_get_clean();
				return $con;
		} else {
				$title = str_replace( '[Item name]', $con, $title_get );
        		$title = str_replace( '[Year]', date('Y'), $title );
        	    ob_get_clean();
        	    return do_shortcode($title);
		}
        
    }
    public function add_to_page_titles( $title ){
        $count = 0 ;
        ob_start();
		
        foreach ($this->compareditems as $key => $ictem) {
            if (!empty($ictem)) {
                echo $count!=0?  ' vs ':'';
                echo get_the_title($ictem);
                $count++;
            }
        }
        $item_nm = ob_get_contents();
        //$con .= " which is better? (".date('Y')." compared)";
		
		$settings = get_option( 'mv_list_items_settings' );
        $title_get = $settings['comparison_page_title'];
		if($title_get == "") {
				$item_nm .= " which is better? (".date('Y')." compared)";
				ob_get_clean();
				return $item_nm;
		} else {
       		$title = str_replace( '[Item name]', $item_nm, $title_get );
        	$title = str_replace( '[Year]', date('Y'), $title );
        	ob_get_clean();
        	return do_shortcode($title);
		}
    }
	public function compare_title_desc(){
		$count = 0 ;
        ob_start();
		
        foreach ($this->compareditems as $key => $ictem) {
            if (!empty($ictem)) {
                echo $count!=0?  ' vs ':'';
                echo get_the_title($ictem);
                $count++;
            }
        }
        $item_nm = ob_get_contents();
		
		$settings    = get_option( 'mv_list_items_settings' );
        $description = $settings['comparison_page_description'];
		$description = str_replace( '[Item name]', $item_nm, $description );
        $description = str_replace( '[Year]', date('Y'), $description );
        ob_get_clean();
        return do_shortcode($description);
			
		
	}
    public function get_compare_title_desc(){

        $cmdetails = array();
//        $userReview = $this->reviewClass->user_detail_review($this->item,$this->templateId);
        $itemTitle = get_the_title($this->item);
        $items = $this->compareditems;
        $fullArr = array();
        $ratOverallSort = array();
        $ratMoneySort = array();
        $ratEasySort = array();
        $ratOverallvotes = array();
        foreach ($items as $it){
            if(!empty($it)) {

                $fullArr[] = $this->get_item_list_rank($it);
                $rating = get_overall_combined_rating($it);
                $overall = 0;
                $votes = 0;
                $money = 0;
                $easy = 0;

                if(!empty($rating)){
                    $overall = $rating['list']['overallrating']['score'];
                    $easy = $rating['list']['easeofuse']['score'];
                    $money = $rating['list']['valueformoney']['score'];
                    $votes = $rating['count'];

                }
                $ratOverallSort[$it]= $overall;
                $ratOverallvotes[$it]= $votes;
                $ratEasySort[$it]= $easy;
                $ratMoneySort[$it]= $money;
            }

        }
        arsort($ratOverallSort);
        arsort($ratEasySort);
        arsort($ratMoneySort);
        $result = call_user_func_array('array_intersect', $fullArr );
        $score = -1;
        $current = 0;

        if(!empty($result)) {
            foreach ($result as $rat) {
                $ratval = get_post_meta($rat, 'ratings_users', true);
                if ($ratval > $score) {
                    $score = $ratval;
                    $current = $rat;
                }
            }
        }
        $listCon = '';

        if(!empty($current)){
            $arrSort = array();

            foreach ($items as $key => $ictem) {
                if(!empty($ictem)) {
                    $rk = get_item_rank($current, $ictem);
                    $arrSort[$ictem] = $rk;

                }
            }

            asort($arrSort);

            if(!empty($arrSort)) {
                $listCon ='In the collection "<u><a href="'.get_permalink($current).'" class="ls_referal_link" data-parameter="comparison" data-id="'.$this->comparison_slug.'">'.get_the_title($current).'</a></u>"';
                $cn = 1;
                foreach ($arrSort as $k => $v) {
                    $suffix = ', ';
                    if ($cn == 1) {
                        $suffix = ' ';
                    }
                    if ($cn == count($arrSort)) {
                        $suffix = ' while ';
                    }
                    $cn++;
                    $listCon .= $suffix . get_the_title($k) . " is ranked $v" . ordinal_suffix($v);
                }
                $listCon .= '.';
            }
        }
        foreach ($this->otheritems as $key => $ictem) {
            if (!empty($ictem)) {

                $cmdetails[$key] = array('key' => $ictem, 'title' => get_the_title($ictem));
            }
        }
        $count = 1;
        $overallRatContnet = '';
        foreach ($ratOverallSort as $post => $rat){
            if($count==1){
                $overallRatContnet .=get_the_title($post)." dominates with an overall user/editors rating of $rat/5 stars with $ratOverallvotes[$post] reviews";
            } else {
                if ($count == count($overallRatContnet)) {
                    $add = ' and ';
                } else {
                    $add = ', ';
                }
                $overallRatContnet .= $add . get_the_title($post) . " user/editors rating is $rat/5 stars with $ratOverallvotes[$post] reviews";
            }
            $count++;
        }

        $overallRatContnet .=". This data is calculated in real-time from verified user reviews or editors rating if there isn't enough data for user rating.";
        $unique_arr_val = array_values($ratMoneySort);
        $moneyFirst = array_shift($unique_arr_val);
        foreach ($ratMoneySort as $moneyItem => $moneyval){
            break;
        }foreach ($ratEasySort as $easyItem => $easyval){
            break;
        }
        ob_start();

        ?>
        <p>Today we'll be comparing <b><?php
            $reviews = '';
            $vstitle = $itemTitle;
            $ortitle = $itemTitle;
            foreach ($cmdetails as $cc){
                $vstitle .=' vs '.$cc['title'];
                $ortitle .=' or '.$cc['title'];

            }  echo $vstitle;
            ?></b>, to ultimately help you decide which is the best solution for you. The information below is based on real data from our community of users like you, giving you a truly unbiased comparison. <?php echo $listCon;?></p>

        <p><?php echo $overallRatContnet;?></p>

        <?php if(!empty($this->alternate)){?><p>If for whatever reason by the end of this comparison you are unable to choose between <?php echo $ortitle;?>, we have included a few useful alternatives like <a href="<?php echo get_permalink($this->alternate);?>" class="ls_referal_link" data-parameter="comparison" data-id="<?php echo $this->comparison_slug;?>"><?php echo get_the_title($this->alternate);?></a> based on our community recommendations.</p><?php }?>

        <p>
            <?php
            if(!empty($moneyItem) && $moneyval > 0){
                if($moneyItem == $easyItem){
                    $start = ' and ';
                } else{
                    $start = ' but ';
                }
                echo "As far as Value for money goes, ".get_the_title($moneyItem)." wins by $moneyval marks";
            }
            if(!empty($easyItem) && $easyval > 0){
                echo $start .get_the_title($easyItem)." is also voted as the easiest solution to use";
            }
            ?>
        </p>

        <em>Without further ado, let's look at a detailed breakdown of <?php echo $vstitle;?></em>
        <?php
        $con = ob_get_contents();
        ob_get_clean();
        return $con;

    }
    public function can_be_compared($post_id='',$chAl = true,$num=-1,$checkArr = array()){

if(empty($post_id)){
    $post_id = $this->item;
}
        if($chAl) {
            $already = $this->compareditems;
            foreach ($already as $vv) {
                if (!empty($vv)) {
                    $checkArr[] = $vv;
                }
            }
        }
//        $post_args = array(
//            'fields' => 'ids',
//            'post_type' => array('list_items'),
//            'post__not_in' => $checkArr,
//            'posts_per_page' => -1
//        );
//        $post_args['tax_query'][] = array(
//            'taxonomy' => 'list_comp_categories',
//            'field' => 'id',
//            'terms' => $this->category
//        );
//        $posts = get_posts( $post_args );
//        $output = array();
//        foreach( $posts as $id ) {
//            $id = intval( $id );
//
//            $output[] = $id;
//
//        }
        $output= array();
        $status  = is_string( get_post_status( $post_id ) );
        $lists =  get_field( 'add_to_list', $post_id, false );

        $list_setting = get_option( 'mv_list_items_settings' );
        $list_order = $list_setting['comparison_page_order'];
        

        if(!empty($lists) && is_array($lists)){
            foreach ($lists as $lit){
                $exclude = get_field('exclude_from_comparison',$lit);
                if(!$exclude) {
                    $attached_items = get_field('list_items', $lit, false);
//                var_dump($attached_items);
                    if (!empty($attached_items) && is_array($attached_items)) {
                        foreach ($attached_items as $ait) {
                            if (get_post_status($ait) == 'publish' && !in_array($ait, $checkArr) && !in_array($ait, $output)) {
                                $output[] = $ait;
                                if (count($output) == $num && $num != -1) {
                                    break 2;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $output;
    }
    // $custorder= get_option( 'mv_list_items_settings' );
    public function get_sections(){
        $setting= get_option( 'mv_list_items_settings' );
        $custorderstring=$setting['comparison_page_order'];

        $custorder = explode(",",$custorderstring);

        //make custorder into an array
        // settype($custorder, 'array');
        
        // print_r($custorder);
        $maps = array(
            'hidden'=>'',
            'betterthan'=>'',
            'ratings'=>'Ratings',
            'pricing'=>'Pricing',
            'features'=>'Features',
            'support'=>'Support',
            'reviews'=>'Reviews',
            'screenshots'=>'Behind the scenes',
            'video'=>'Video Review',
            'download'=>'Get it Here',
            'ranking'=>'Ranked in these collections',
            'map' => 'map'
        );

        $order=array();
        //loop on custorder
        foreach($custorder as $val){
            $val=trim($val);
            $order[$val] = $maps[$val];
        }
       

        return $order;
    }
    public function get_column_overview($post_id,$item,$post_id2){
        ob_start();

        echo '
        <style>
        .hide_chart{
        display:none;
        }
        .loser {
            background: #868686 !important;
        }
        .loser:hover {
            background: #000 !important;
            color: #fff !important;
        }
        .cat-bg {
            padding: 5px;
            background: #c2d5f3;
            margin: 2px;
        }
        .cat-bg:hover {
            color: #fff !important;
        }
        .title-head{
            display:flex;
        }
        </style>
        ';

        $rating = get_overall_combined_rating($post_id);
        $overall = 0;
        $votes = 0;

        if(!empty($rating)){
            $overall = $rating['list']['overallrating']['score'];
            $votes = $rating['count'];
        }
        $count_label = $votes==1?'vote':'votes';
        $compCount = count($this->compareditems);
        ?>
        <div class="title-head">
            <div class="item-remove" data-mh="equal-h-remove">
                <?php if($compCount > 2){
                    echo "<a href='javascript:;' data-key='$item' data-id='$post_id' class='remove_compare_project'><i class='fa fa-times-circle'></i> Remove Item</a>";
                }?>
            </div>
            <span class="cp-<?php echo $item?> title-image">
			<?php //echo get_thumbnail_small($post_id,array(100,100));
            $item_image = get_the_post_thumbnail_url($post_id,array(100,100));?>
             <img src="<?php echo $item_image?>" class="img-responsive" alt="<?php echo get_the_title($post_id);?>" >
            <span class="title-link"><a href="<?php echo get_permalink($post_id);?>" class="ls_referal_link" data-parameter="comparison" data-id="<?php echo $this->comparison_slug;?>"><?php echo get_the_title($post_id);?></a> </span>
            <div class="title-review">
            <?php
            echo $this->reviewClass->get_stars( $overall, 20, 5 );
            echo '<div class="rwp-rating-stars-count">('. $votes .' '. $count_label .')</div>';
            //            echo do_shortcode( '[rwp_users_rating_stars id="-1"  template="'.$this->templateId.'" size=20 post='.$post_id.']' );
            ?>
        </div>
       <span><p><?php echo get_excerpt_custom($post_id,200); ?>... <em><a href="<?php echo get_permalink($post_id);?>" class="ls_referal_link" data-parameter="comparison" data-id="<?php echo $this->comparison_slug;?>" rel="nofollow">Read full review</a></em></p> <span>
        </div>
        <?php 
        
         if(sizeof($this->ctaArr) < 2){
            $this->calc_cta($post_id);
            }   ?>
        <?php
       
        $cl='loser';
        if($post_id == $this->winner){
            $cl='';
        }
        /* echo "scores";
        echo $this->findrScoreArr[$post_id];
        echo $this->findrScoreArr[$post_id2]; */

        
        if($availability == 'no') { ?>
             <a href="<?php echo get_the_permalink( $post_id )?>alternative/" class="alter-btn aff-link" data-parameter="itemid" data-id="<?php echo $post_id ?>" >Alernative</a>
         <?php } else {
             echo '<a class="mes-lc-li-down aff-link '.$cl.'" href="'. $this->ctaArr[$post_id]['afflink'].'" rel="nofollow" target="_blank">'.$this->ctaArr[$post_id]['btntext'].'</a>';
         }

        
        // print_r($this->findrScoreArr); 
        if($post_id == $this->winner)
            echo '<div class="winner">Comparison winner</div>';
         ?>
        <?php
        $con = ob_get_contents();
        ob_get_clean();
        return $con;
    }
    public function get_column_hidden($post_id,$item,$post_id2){
        ob_start();
        ?>
        <div class="title-head">

            <span class="cp-<?php echo $item?> title-image">
			<?php $item_image = get_the_post_thumbnail_url($post_id,array(100,100));?>
             <img src="<?php echo $item_image?>" class="img-responsive" alt="<?php echo get_the_title($post_id);?>" >
            <span class="tile-link"><a href="<?php echo get_permalink($post_id);?>" class="ls_referal_link" data-parameter="comparison" data-id="<?php echo $this->comparison_slug;?>"><?php echo get_the_title($post_id);?> </a></span>
        </div>
        <?php
        $con = ob_get_contents();
        ob_get_clean();
        return $con;
    }
    public function get_column_betterthan($post_id,$item,$post_id2){
		
		
        ob_start();
        ?>
        <div class="title-head">
            <h4>Why is <?php echo get_the_title($post_id); ?> better than <?php echo get_the_title($post_id2); ?>?</h4><hr>
            
            </div>
            <div class="cat_link_secnew">
            <?php
            $features_ids= Array(
                'id0'=>$post_id
            );
            
echo "</div> ";
            
                echo '<h4><span class="fscomp"><b>FindrScore (fs)</b></span>
                <span class="tooltip"><i class="fa fa-info-circle"></i>
                <span class="tooltiptext">The Scoring is based on our unique algorithm that looks at reviews, votes, behavior, social signals and more. <a rel="nofollow" href="/scoring-methodology/">Learn more</a></span>
            </span>
            </b><div class="comparison-fs"><div class="w3-border">
                <div class="w3-grey" style="height:24px;width:'.$this->findrScoreArr[$post_id].'%;float: right;"></div>
                <div class="findrcomp">+'.$this->findrScoreArr[$post_id].'</div>
              </div></div></h4>';
            //   $this->findrScoreArr []= round($findrScore);
            //   print_r($this->findrScoreArr);
            ?>
            
            <?php
                $r2b=Array();
                $price1 = ltrim(get_field( 'price_starting_from', $post_id),"$"); 
                $price2 = ltrim(get_field( 'price_starting_from', $post_id2),"$");
                // echo "price1 and price2 ".$price1." and ".$price2; 
                if($price1 < $price2){
                    $r2b []= get_the_title($post_id)." is cheaper than ".get_the_title($post_id2) ."<br>";//echo get_the_title($post_id)." is cheaper than ".get_the_title($post_id2) ."<br>";
                }
                $support = get_field( 'support', $post_id );
                foreach($support as $cit){
                    if($cit == '24/7')
                        $r2b []= "24/7 support options available";
                }

                $freeTrial = get_field('free_trial',$post_id);
                if($freeTrial)
                    $r2b []= "Free Trial is offered to perform testing";
                
                $mbg = get_field('money_back_guarantee',$post_id);
                if($mbg){
                    $r2b []= "Money back guarantee";
                }

                $reviews = get_overall_combined_rating($post_id);
                if($reviews['list']['customersupport']['score'] > 4)
                    $r2b []= "Friendly customer service";

                if($reviews['list']['easeofuse']['score'] > 4)
                    $r2b []= "Easy to use even for a beginner";


                
            $compObj = new Mv_List_Comparision();
            $lists = $compObj->most_compared($post_id,1000,true);
            // print_r($lists);
        
            $max = 0;
            // echo "priceses are : ";
            foreach($lists as $alternate){
                
                $price = ltrim(get_field( 'price_starting_from', $alternate),"$");
                // echo $price."\n";
                    if($price > $max){
                        $max = $price;
    
                    }
                }
            // echo "max is : ".$max;
            // echo "price is : ".ltrim(get_field( 'price_starting_from', $post_id),"$");
            if(ltrim(get_field( 'price_starting_from', $post_id),"$") <= $max/2)
                $r2b []= "Compared to others the price is reasonable"; 
            
            
        $tpi = get_field('third_party_integrations',$post_id);
        if($tpi){
            $r2b []= "Third-party integrations";
        }
            $pricing_model = get_field( 'pricing_model', $post_id);
        // print_r($pricing_model);
        if (in_array("freemium", $pricing_model))
            $r2b []= "Offers free plan with multiple advanced features";
        // if($pricing_model[])
        if($reviews['list']['overallrating']['score'] > 4)
            $r2b []= "The majority of our users are experiencing positive experience with ".get_the_title($post_id);
        


        $alternateinfo = $this->get_alternate_items_info($post_id);
        if(empty($alternateinfo)){
            $alternateinfo = array();
        } 
        /* $maximum =  max(array_column($alternateinfo, 'price'));
        $minimum = min(array_column($alternateinfo, 'price')); */
        // print_r($alternateinfo);
        // echo "max and min ".$maximum." and ".$minimum;
        $sum=0;
        // echo "nof : ";
        for($i=0;$i<sizeof($alternateinfo);$i++){
            $nof = sizeof(get_field( 'features_list', $alternateinfo[$i]['id'] ));
            // echo $nof." ";
            $sum += $nof;
        }
        $avgnof = $sum/sizeof($alternateinfo);
        // echo "avgnof ".$avgnof." ";
        // get_field( 'features_list', $post_id );
         if(sizeof(get_field( 'features_list', $post_id )) > $avgnof)
            $r2b []= "Great features list";
        
        
        // print_r($this->ranklist);
        $lists =  get_field( 'add_to_list', $post_id, false );
        $itemiid = $post_id;
        if ( !empty ( $lists ) && is_array( $lists ) ) {
            // echo '<ul class="compare-rank-list">';
            $listrankord = array();
            foreach ($lists as $id) {
                $status  = is_string( get_post_status( $id ) );
                if ($status && !empty($id)) {

                    $rank = get_item_rank($id, $itemiid);
                    $listrankord[$id] = $rank;
                }
            }
            asort($listrankord);

        foreach($listrankord as $listid=>$rank){
            if($rank < 4){
                $r2b []= "Category leader in <a href='".get_permalink($listid)."'>".get_the_title($listid)."</a>";
            }
            elseif($rank < 11){
                $r2b []= "A contender in <a href='".get_permalink($listid)."'>".get_the_title($listid)."</a>";

            }
        }
    }
        if (in_array("open_source", $pricing_model))
            $r2b []= "The core product is 100% free";

        $features =    get_field( 'features_list', $post_id );
       
        // print_r($features);
        //if(sizeof($features) > 2){ 
            $features_ids= Array(
                'id0'=>$post_id
            );
            $sorted_features_array = $this->sort_features($features,$features_ids); // for individual items for reasons to buy section.
//            print_r($sorted_features_array);
            $three ="";
            $i =0 ;
            foreach($sorted_features_array as $vote=>$feature){
                $three.=("<li> Has ".($feature['feature']));
                $i++;
                if($i>2){
                    break;
                }
            }
		
            $r2b []= "Has ".sizeof($features)." highlight features".$three;
        //}

        
        // print_r($r2b);
        $htmlr2b = '<ul>';
        
        foreach($r2b as $r){
            $htmlr2b .= '<li>'.$r.'</li>';
        }
        $htmlr2b .= '</ul>';
        echo '<p class="mygreen"> <i class="fa fa-check-circle mh10" aria-hidden="true"></i>'.count($r2b).' Reasons to consider this</p>
              '.$htmlr2b;
              
              
            
            
            ?>

        

            <a class="scrollfeatures" href="#features"><i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i> Scroll Down for more details</a><br>
            
        <?php
        $con = ob_get_contents();
        ob_get_clean();
        return $con;
    }

    
    public function get_column_pricing($post_id,$item,$post_id2){
        ob_start();

        $pricing_model = get_field( 'pricing_model', $post_id );
        $free_trial = get_field( 'free_trial', $post_id );
        $price_starting_from = get_field( 'price_starting_from', $post_id );
        $price_starting_from =round(str_replace("$","",$price_starting_from));
		 $pricing_plan = get_field('plan',$post_id);
		$coupon_availability = get_post_meta($post_id,'coupons_list',true);
        ?>
        <!----------for chart-------->
      
        <ul class="compare-pricing-list">
            <li data-mh="equal-h-pricing"><label>Pricing Model</label><?php echo str_replace( '_', ' ', implode( ', ', array_map( 'ucfirst', $pricing_model ) ) )?></li>
            <li data-mh="equal-h-start"><label >Starting From</label><?php echo "$".$price_starting_from."&nbsp;/&nbsp;". $pricing_plan;?></li>
            <li><label data-mh="equal-h-free">Free Trial</label><i class="fa fa-check <?php echo !empty($free_trial)?'active':'';?>"></i></li>
			<li>
			<?php if(!empty($coupon_availability)) {?><a href="<?php echo get_the_permalink( $post_id )?>coupon/" class="couponbtn" data-parameter="itemid" data-id="<?php echo $post_id ?>"><?php echo get_the_title($post_id); ?> Coupons</a><?php }?>
			</li>
			
        </ul>

        <?php
        $con = ob_get_contents();
        ob_get_clean();
        return $con;
    }
    public function get_column_ratings($post_id,$item,$post_id2){
        ob_start();
        $reviews = get_overall_combined_rating($post_id);
        if(!empty($reviews) && is_array($reviews)){
            echo "<ul>";
            foreach ($reviews['list'] as $rev) {
                $percen = ($rev['score'] / 5) * 100;
                $percen = round($percen);
                ?><li>
                <div class="rat-label"><span class="prg-label"><?php echo $rev['label']?></span><span class="prg-valval"> <?php echo $percen;?>%</span></div>
                <div class="progress">
                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php $rev['score'];?>" aria-valuemin="0" aria-valuemax="5" style="width:<?php echo $percen;?>%">
                    </div>

                </div>

                </li>
                <?php
            }
            echo "</ul>";
        } else{
            echo  "<div class='no-data'>No Ratings provided.</div>";;
        }
        ?>
        <?php
        $con = ob_get_contents();
        ob_get_clean();
        return $con;
    }
    public function get_column_features($post_id,$item,$post_id2=''){
        ob_start();

        $features =    get_field( 'features_list', $post_id );

        if(!empty($features) && is_array($features)) {
                    $count = count($features);
                    $i =  1;
                    echo '<ul>';
                   
                    foreach ($features as $value) {
                         
                        if(!empty($value)) {
                            if($i <= 8 ){
                                echo '<li class="key-features ' . ($value ? 'active' : '') . '"><span>' . $value . '</span></li>';
                            } else {
                                 echo '<li class="key-features features-hidden ' . ($value ? 'active' : '') . '"><span>' . $value . '</span></li>';
                            }  
                        }
                        $i++;  
                    }
                    echo '</ul>';
                    if($count > 8){
                        $left = $count - 8;
                        echo '<a class="full-list-toggle">+'.$left.' others </a>';
                    }


        }
        $con = ob_get_contents();
        ob_get_clean();
        return $con;
    }
    public function get_column_support($post_id,$item,$post_id2){
        ob_start();
        $support = get_field('support',$post_id);
        $supportkey = acf_get_field_key('support',$post_id);
        $supportl = get_field_object($supportkey,$post_id);

        if(!empty($supportl)) {
            echo "<ul>";
            foreach ($supportl['choices'] as $ckey=> $cval){
                if(empty($support)){
                    $support = array();
                }
                echo "<li class='support-features ".(in_array($ckey,$support)?'active':'')."'><i class='fa fa-$ckey'></i> $cval</li>";
            }
            echo "</ul>";
        }
        $con = ob_get_contents();
        ob_get_clean();
        return $con;
    }
    public function get_column_reviews($post_id,$item,$post_id2){
        ob_start();
        $ratings = $this->get_ratings($post_id);
        $count = count($ratings);
        if(!$count){
            return  "<div class='no-data'>No Reviews Submitted</div>";;
        }

        $reviewfirst = $ratings[0];

        $reviewfirst['title'] = "Most Helpful Review";
        $revend = array();
        if($count > 1){
            $reviewfirst['title'] = "Most Helpful Favorable Review";
            $revend = end($ratings);
            $revend['title'] = "Most Helpful Critical Review";
        }
        $revArr = array($reviewfirst,$revend); ;
        ?>
        <ul class="rev-list-ul">
            <?php foreach ($revArr as $key => $rev):
                if(!empty($rev)):?>
                    <li class="review-list" data-mh="equal-h-review-<?php echo $key;?>">
                        <div class="rev-detail">
                            <p class="hhead"><?php echo $rev['title']?></p>
                            <div class="rev-user">
                                <div  class="image"><?php echo get_avatar($rev['rating_user_id'])?></div>
                                <div class="img-beside">
                                    <span class="name"><?php echo $rev['rating_user_name']; ?></span>
                                    <span class="rat-title"> <?php echo $rev['rating_title']?></span></div>
                            </div>
                            <div class="rev-detail">

                                <div class="desc"><?php if(strlen($rev['rating_comment'])>200){
                                        echo substr($rev['rating_comment'],0,200).'...';
                                    } else{
                                        echo $rev['rating_comment'];
                                    }?></div>
                            </div>
                        </div>
                    </li>
                <?php endif; endforeach;

            ?>
            <li  class="review-list" data-mh="equal-h-review-btn">
                <a class="read-all-review" href="<?php echo get_permalink($post_id);?>#rating">Read all <?php echo get_the_title($post_id)?> review</a>
            </li>
        </ul>
        <?php

        $con = ob_get_contents();
        ob_get_clean();
        return $con;
    }
    public function get_column_screenshots($post_id,$item,$post_id2){
        ob_start();
        $gallery = get_field('gallery',$post_id);
        if(!empty($gallery) && is_array($gallery)){
            $slides = '';
            foreach ($gallery as $img){

                $slides .= '<li><a href="'.$img['url'].'" data-fancybox="flex_images_'.$post_id.'"><img src="'.$img['url'].'" /></a> </li>';
            }
            echo '<div class="flexslider slider-compare">
  <ul class="slides">'.$slides.'</ul></div>';
        }else{
            echo "<div class='no-data'>No Screenshots Provided</div>";
        }

        $con = ob_get_contents();
        ob_get_clean();
        return $con;
    }
    public function get_column_video($post_id,$item,$post_id2){
		
	
        ob_start();
		$video = get_field('video',$post_id);
		
		preg_match_all('/src="([^\?]+)/', $video, $match);
		$url = $match[1][0];
		
		$url = str_replace('embed/','watch?v=',$url);
		
		//https://www.youtube.com/embed/MaxqO4iMIHo
		//https://www.youtube.com/watch?v=MaxqO4iMIHo 
		
		
		
//			
		
		$videos_list = get_post_meta($post_id, 'video_list', true);
	
		
		
//	 $video_list_new = get_post_meta($post_id, 'video_list', true);
		
		
		if(!in_array($url,$videos_list)){
		$videos_list[] = $url;
		update_post_meta($post_id, 'video_list' ,$videos_list);
			
		}
	
		
		
//				$videos_list[] = $url;
//
//		update_post_meta($post_id, 'video_list' ,$videos_list);
//print_r($videos_list);
//	echo count($videos_list);
////        if(!empty($videos_list)){
//	echo count($video);
//	var_dump($videos_list);	
		
			if(count($videos_list) != 1 || !empty($video)){	
			echo '<div class="embed-container">';
			
			
			
            $splittedstring = explode("?v=",$videos_list[0]);
            $firstvid = $splittedstring[1];	
			
			
			if(count($splittedstring) == 1){
				$splittedstring = explode("embed/",$videos_list[0]);
            $firstvid = $splittedstring[1];	
			}
				
           ?>

            <div class="video-item left-content-box"><h3 id="videos"><div class="videos-title left-title"></div></h3>
            <div class="embed-container"> <div class="vid-main-wrapper clearfix">
           <div class="vid-container">	
			   <?php $random_id = rand();?>
          <iframe src="https://www.youtube.com/embed/<?php echo $firstvid ?>" id="vid_frame-<?php echo $random_id;?>"  
                allowfullscreen
                webkitallowfullscreen
                mozallowfullscreen
                style="position: absolute; top: 0px; right: 0px; bottom: 0px; left: 0px; width: 100%; height: 100%;"></iframe>
            </div>
           <div class= "vid-list-container"> <div class="vid-list">	
           <?php 
            $i=0;
//			var_dump($videos_list);
            foreach($videos_list as $new)
                {
				
                    if(trim($new) != ''){
                        $splittedstring = explode("?v=",$new);
                        $videos_list[$i] = $splittedstring[1];
    		
//						print_r($splittedstring);
						if(count($splittedstring) == 1){
								$splittedstring = explode("embed/",$new);
                        $videos_list[$i] = $splittedstring[1];
					
							}
                        $api = "AIzaSyBxZQza9iYMySd0Tcd93k3Esv3AGfIVJp0"; // YouTube Developer API Key

                        $content = file_get_contents("https://www.googleapis.com/youtube/v3/videos?key=$api&part=snippet&id=".$videos_list[$i]);	
                        // echo $content;
                        // parse_str($content, $ytarr);
                        $jsondec = json_decode($content);

                        // $jsondec = json_decode($ytarr['player_response'],true);?>
<!--                        <div class="vid-item" data-videolink="http://youtube.com/embed/<?php echo $videos_list[$i];?>?autoplay=1&rel=0&showinfo=0&autohide=1" onclick='reload()'>-->
                        <div class="vid-item" onClick="document.getElementById('vid_frame-<?php echo $random_id;?>').src='http://youtube.com/embed/<?php echo $videos_list[$i];?>?autoplay=1&rel=0&showinfo=0&autohide=1'">
							
                            <div class="thumb">
                                <img src="<?php echo $jsondec->items[0]->snippet->thumbnails->default->url; ?>">
                            </div>
                             <div class="desc">
                                        <?php echo $jsondec->items[0]->snippet->title;?>
                                </div>
                            </div>
                           
    <?php 
                        $i++;
    
                    }
                
                }
            $videos_list = implode(" , ", $videos_list); 
            
    ?>
            </div></div>
            <div class="arrows">
                <div class="arrow-left">
                    <i class="fa fa-chevron-left fa-lg"></i>
                </div>
                <div class="arrow-right">
                    <i class="fa fa-chevron-right fa-lg"></i>
                </div>
            </div>
				</div></div></div></div> <?php
        
		} else{
            echo "<div class='no-data'>No Video Provided</div>";
        }
		
        $con = ob_get_contents();
        ob_get_clean();
        return $con;
    }
	
	
    public function get_column_download($post_id,$item,$post_id2){
        ob_start();
        if(sizeof($this->ctaArr) < 2){
            $this->calc_cta($post_id);
            }   ?>
        <?php if($availability == 'no') { ?>
             <a href="<?php echo get_the_permalink( $post_id )?>alternative/" class="alter-btn aff-link" data-parameter="itemid" data-id="<?php echo $post_id ?>" >Alernative</a>
         <?php } else {
             $cl='loser';
             if($post_id == $this->winner){
                 $cl='';
             }
             echo '<a class="mes-lc-li-down aff-link '.$cl.'" href="'. $this->ctaArr[$post_id]['afflink'].'" rel="nofollow" target="_blank">'.$this->ctaArr[$post_id]['btntext'].'</a>';
         }
        $con = ob_get_contents();
        ob_get_clean();
        return $con;
    }
    public function get_item_list_rank($post_id,$sort=false){
        $status  = is_string( get_post_status( $post_id ) );
        $lists =  get_field( 'add_to_list', $post_id, false );
        if(!$sort) {
            return $lists;
        }else{
            $listrankord = array();
            if ( !empty ( $lists ) && is_array( $lists ) ) {


                foreach ($lists as $id) {
                    if ($status) {

                        $rank = get_item_rank($id, $post_id);
                        $listrankord[$id] = $rank;
                    }
                }
                asort($listrankord);
            }
            return $listrankord;
        }
    }
    public function get_column_ranking($post_id,$item,$post_id2=NULL){
        ob_start();

        $lists =  get_field( 'add_to_list', $post_id, false );
		
        $itemiid = $post_id;
		
        if ( !empty ( $lists ) && is_array( $lists ) ) {
            echo '<ul class="compare-rank-list">';
            $listrankord = array();
			
            foreach ($lists as $id) {
                $status  = is_string( get_post_status( $id ) );
                if ($status && !empty($id)) {

                    $rank = get_item_rank($id, $itemiid);
                    $listrankord[$id] = $rank;
					
//					var_dump($listrankord);
                }

            }
            asort($listrankord);
            $count = 0;
            foreach ($listrankord as $id => $rank) {
//                if(!empty($id)) {
                    $rankhtm = '';
                    if ($rank > 0) {
                        $rankhtm = '<span class="compare-rank"><span class="rank-val">#' . $rank . '</span> </span>';
                    }
                    echo '<li data-mh="equal-h-rank-' . $count . '" data-id="' . $id . '"><a class="compare_link ls_referal_link" data-parameter="comparison" data-id="'.$this->comparison_slug.'"  href="' . get_the_permalink($id) . '">' . $rankhtm . '<span class="rank-text">' . do_shortcode(get_the_title($id)) . '</span></a></li>';
                    $count++;
//                }
            }
            echo '<ul>';
        }
        $con = ob_get_contents();
        ob_get_clean();
        return $con;
    }
    public function get_left_column_details($type=''){
        $labels = $this->get_sections();
        $data =array('title'=>'','labels'=>array());
        switch ($type){
            case "pricing":
                $data = array('title'=>$labels['pricing'],'labels'=>array("Pricing Model","Starting Price","Free Trial"));
                break;
            case "ratings":
                $criteria = $this->reviewClass->template_field('template_criterias', true);

                $order 		= $this->reviewClass->template_field('template_criteria_order', true);
                $order		= ( $order == null ) ? array_keys( $criteria) : $order;
                $lab = array();
                foreach ($order as $i) {
                    $lab[] = $criteria[$i];
                }
                $lab[] ='Overall Rating';
                $data = array('title'=>$labels['ratings'],'labels'=>$lab);
                break;
            case "support":
                $data['title'] = $labels['support'];
                break;
            case "screenshots":
                $data['title'] = $labels['screenshots'];
                break;
            case "reviews":
                $data['title'] = $labels['reviews'];
                break;
            case "video":
                $data['title'] = $labels['video'];
                break;
            case "download":
                $data['title'] = $labels['download'];
                break;
            case "ranking":
                $data['title'] = $labels['ranking'];
                break;

        }
        return $data;
    }
    public function get_compare_dropdown($key='',$onlyoptions=false){
        $item = $this->item;
        $list = $this->can_be_compared();
        $options = '<option>Select</option>';
        foreach ($list as $val){
            $options .= "<option value='$val'>".get_the_title($val)."</option>";
        }
        ob_start();
        if($onlyoptions){
            return $options;
        }
        ?>
        <div class="compare-dropdown">
            <select data-key="<?php echo $key?>" class="get_compare_obj">

                <?php
                echo $options;
                ?>

            </select>
        </div>
        <?php
        $con = ob_get_contents();
        ob_get_clean();
        return $con;
    }

    public function get_ratings($post_id)

    {

        global $wpdb;

        $result = array();
        $template_id = $this->templateId;
        $post_type 	= get_post_type( $post_id );
        $auto_id 	= -1;
        $review_id 	= md5( 'rwp-'. $template_id .'-'. $post_type . '-' . $post_id . '-' . $auto_id );
        $result =  RWP_User_Review::users_reviews( $post_id, $review_id, $template_id );;
        $reviews = array();
        if(isset($result['reviews'])){
            $reviews = $result['reviews'];
            usort($reviews, array($this, 'compare'));
            $reviews = array_reverse($reviews);
        }
        return $reviews;

    }
    public function compare($a,$b){
        return $a['rating_overall'] - $b['rating_overall'];

    }
    function get_alternate_items() {
        $post_id = $this->get_item();
        $html ='';
        $lists = $this->most_compared($post_id,20,true);

        // echo '<pre>';
        // var_dump($lists);
        if ( !empty ( $lists ) ) {
            $html ='<div class="alternate-carousel"><h3 id="alternative">Alternatives to '.get_the_title( $post_id ).'</h3>';
            $html .='<div class="see-more-btn" style=""><a href="'.get_the_permalink( $post_id ).'alternative" class="ls_referal_link" data-parameter="itemid" data-id="'.$post_id.'">See More</a></div> <div class="clr"></div>';
            $html .='<div class=""><div class="flexslider carousel cr-alternate">
  <ul class="slides">';
            $ac = 0;
            foreach ( $lists as $pid ) {
 				$item_image = get_the_post_thumbnail_url($pid,array(150,150));?>
             	
              <?php   $html .='<li><div class="cs-title single-line"><a href="'.get_the_permalink($pid).'"> '.get_the_title($pid).'</a></div> <div class="cs-image"><a href="'.get_the_permalink($pid).'"><img src="'.$item_image.'" class="img-responsive" alt="'.get_the_title($pid).'" > </a></div> </li>';
               // if($ac > 3) { break; }
                $ac++;
            }
            $html .='</ul></div></div></div>';
        }



        return $html;
    }
	function get_alternate_items_ratio() {
        $post_id = $this->get_item();
        $lists = $this->most_compared($post_id,1000,true);
        $onetime = 0;
        $subscription = 0;
        $opensource = 0;
        $freemium = 0;
        $free = 0;
		$others = 0;
        $size = sizeof($lists);
            foreach ( $lists as $pid ) {
                    $pricing_model = get_field('pricing_model', $pid); 
                    if(empty($pricing_model)){
                    $pricing_model = array();
                    }
                   $counts = array_count_values($pricing_model);
				   
				if(in_array("freemium", $pricing_model)){
                    $freemium += $counts['freemium'];
                    continue;
				}
				elseif(in_array("open_source", $pricing_model)){
                    $opensource += $counts['open_source'];
                    continue;
				}
				elseif(in_array("subscription", $pricing_model)){
                    $subscription += $counts['subscription'];
                    continue;
				}
				elseif(in_array("one_time_license", $pricing_model)){
                	$onetime += $counts['one_time_license'];
               	   continue;	
                }
				else {
					$others = $others+1;
				}
                
            }
            foreach ( $lists as $pid ) {
                $free_trial = get_field( 'free_trial', $pid );
                if($free_trial == 1)
                {
                    $free +=$free_trial;
                }
            }
            $items_ratio =  array("freemium" => $freemium, "subscription" => $subscription, "open_source" => $opensource, "one_time_license" => $onetime, 'size'=> $size, 'free'=> $free, "others" => $others);
            return $items_ratio;
       
    }
    public function get_alternate_items_info($post_id) {
        $post_id = $this->get_item();
        $lists = $this->most_compared($post_id,20,true);
        $alternate_info = array();
            foreach ( $lists as $pid ) {
                $alternate_info[] = array(
                  'id'   =>  $pid,
                 'name'  =>  get_the_title($pid),
                 'price' =>  intval (get_field( 'price_starting_from', $pid ))
                );  
            }
            return $alternate_info;
       
    }
    # *************  Map section  *************
    // public function get_column_map($post_id,$item,$post_id2){
    //     $list_setting = get_option( 'mv_list_items_settings' );
    //     $target_countries = $list_setting['list_page_target_countries'];
    //     // print_r($target_countries);
    //     $countryPair = explode(',',$target_countries);
    //     // print_r($countryPair);
    //     foreach($countryPair as $cp){
    //         $pair = explode('=>',$cp);
    //         foreach($pair as $key=>$value){
    //             $pair[$key]=trim(trim($value),"'");
    //         }
    //         $targetCountries[$pair[0]] = $pair[1];
    //     }
    //     return "hello maps";
    // }


    # *************  FAQ section  *************

    public function get_column_faq($post_id,$item,$post_id2){

        $x = 6;
        $post_id = $this->compareditems[item1];
        $post_id2 = $this->compareditems[item2];
        # Finder Scores
        $score_1 = $this->findrScoreArr[$post_id];
        $score_2 = $this->findrScoreArr[$post_id2];

        $winner = '';
        $loser = '';

        # ************ Calculation of FinderScore ************
        if ($score_1 > $score_2){
            $winner = get_the_title($post_id);
            $winner_score = $this->findrScoreArr[$post_id];

            $loser  = get_the_title($post_id2);
            $loser_score = $this->findrScoreArr[$post_id2];
        }else{
            $winner = get_the_title($post_id2);
            $winner_score = $this->findrScoreArr[$post_id2];

            $loser  = get_the_title($post_id);
            $loser_score = $this->findrScoreArr[$post_id];
        }
        $total = $winner_score - $loser_score;

        # ************ Calculating the pricing ************

        $pricing_model_one = get_field( 'pricing_model', $post_id );
        $pricing_model_two = get_field( 'pricing_model', $post_id2 );

        $price_starting_one = get_field( 'price_starting_from', $post_id );
        $price_starting_one =round(str_replace("$","",$price_starting_one));
        $price_starting_two = get_field( 'price_starting_from', $post_id2 );
        $price_starting_two =round(str_replace("$","",$price_starting_two));

        $pricing_plan_one = get_field('plan',$post_id);
        $pricing_plan_two = get_field('plan',$post_id2);

        $total_com_prise = '';
        if ($pricing_model_one == $pricing_model_two){
            if ($pricing_model_one < $pricing_model_two){
                $winner = get_the_title($post_id);
                $loser = get_the_title($post_id2);
                $total_com_prise = $price_starting_two - $price_starting_one;

            }elseif($pricing_model_one > $pricing_model_two){
                $winner = get_the_title($post_id2);
                $loser = get_the_title($post_id);
                $total_com_prise = $price_starting_one - $price_starting_two;
            }
            
            $mes = "Looking at the starting price of these two solutions ".$winner." is $".$price_starting_one."/".$pricing_plan_one." and ".str_replace(' ', '_', $pricing_model_one[0])."
                    which ".$loser." is $".$price_starting_two."/".$pricing_plan_two." and ".str_replace(' ', '_', $pricing_model_two[0]).". So on the entry level ".$loser." is ".$total_com_prise." more per month.";
        }else{
            $mes = "The solutions are priced slightly different so calculating the price would need to be over a set period. ".get_the_title($post_id)." is ".str_replace('_', ' ', $pricing_model_one[0])." whilst ".get_the_title($post_id2)." is ".str_replace('_', ' ', $pricing_model_two[0]).",";
        }

        # ************ Beginner friendly rating score ************

        $total_bg_df = '';
        $rating_one = get_overall_combined_rating($post_id);
        $rating_two = get_overall_combined_rating($post_id2);

        $easeofuse_one = $rating_one['list'][easeofuse][score];
        $easeofuse_two = $rating_two['list'][easeofuse][score];
        if ($easeofuse_one > $easeofuse_two){
            $beginner_friendly = get_the_title($post_id);
            $non_beginner_friendly = get_the_title($post_id2);
            $total = $easeofuse_one - $easeofuse_two;
            $total_bg_df = 20*$total;
        }else{
            $beginner_friendly = get_the_title($post_id2);
            $non_beginner_friendly = get_the_title($post_id);
            $total = $easeofuse_two - $easeofuse_one;
            $total_bg_df = 20*$total;

        }
        

        # ************ Customer Support rating score ************

        $cust_rat_one = $rating_one['list'][customersupport][score];
        $cust_rat_two = $rating_two['list'][customersupport][score];
        if($cust_rat_one > $cust_rat_two){
            $winner = get_the_title($post_id);
            $loser = get_the_title($post_id2);
            
        }else{
            $winner = get_the_title($post_id2);
            $loser = get_the_title($post_id);
            
        }
        $sup_faq_message = "The data gathered shows that ".$winner." as a slightly better user satisfaction that ".$loser.".";

        # ************ Alternate of product ************
        
        $alter_list = $this->most_compared($post_id,20,true);
        // echo "alter list";
        // print_r($alter_list);
        $alter = get_the_title($alter_list[0]);

        $alternate_list = get_option('mv_list_items_settings');
        // print_r($alternate_list['comparison_page_order']);
        $alt = do_shortcode('[total_items id ="'.$post_id.'"]');

        $id_alt = $alter_list[0];
        // echo "id alt ".$id_alt;
        $this->calculate_fs($id_alt,$post_id);
        // print_r($this->findrScoreArr);
        
        
        # ******************** FAQ's ********************
        $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'>Q. Is ".$winner." better than ".$loser."?</h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. We have analyzed ".$x." data points to answer this question, in ".date("Y")." ".$winner." is better than ".$loser.".
                            the scoring ".$winner_score." whilst ".$loser." scored ".$loser_score."
                             </div>
                        </div>
                    </div>
                    ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  For beginners which is better ".$winner." or ".$loser."? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. We ask this very question to our readers and the results are in, ".$beginner_friendly." scored better that
                            ".$non_beginner_friendly." by ".$total_bg_df." points crowing it the most user friendly.
                             </div>
                        </div>
                    </div>
                    ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  What's the price difference between ".$winner." and ".$loser."?</h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. $mes                            
                             </div>
                        </div>
                    </div>
                    ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  What is the difference between ".$winner." and ".$loser."?</h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. The main difference beyond price and support, is ".$winner." has an overall higher FindrScore which looks at over ".$x." data points powered by real user testing and overall satisfaction.
                             </div>
                        </div>
                    </div>
                    ";
          $faq .= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q. Out of ".$winner." and ".$loser." which as the better support?</h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. ".$sup_faq_message."
                             </div>
                        </div>
                    </div>
                    ";
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.   What are a few good alternatives to ".$winner." and ".$loser."? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                            Ans. Users who research ".$winner." and ".$loser." also look at ".$alter." which as a FindrCsore of ".$this->findrScoreArr[$id_alt].", 
                            <a href='".get_the_permalink( $post_id )."alternative'>See ".$alt." more similar solutions</a>
                             </div>
                        </div>
                    </div>
                    ";
                    
          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                    <h3 itemprop='name'> Q.  ".$winner." or ".$loser." which is good for beginners?</h3>
                    <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                        <div itemprop='text'>
                            Ans. Looking at the scorecard for both these products, the results show that ".$beginner_friendly." is better for beginners
                         </div>
                    </div>
                </div>
                "; 

          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                        <h3 itemprop='name'> Q.  Which is better, ".$winner." or ".$loser." in ".date("Y")."? </h3>
                        <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                            <div itemprop='text'>
                                Ans. We’ve asked this very question to users like you and the results are in. 
                                The majority of users are recommending ".$winner.".
                             </div>
                        </div>
                    </div>
                    "; 

          $faq.= " <div itemscope itemprop='mainEntity' itemtype='https://schema.org/Question'>
                    <h3 itemprop='name'> Q.  How does ".$winner." compare to ".$loser."? </h3>
                    <div itemscope itemprop='acceptedAnswer' itemtype='https://schema.org/Answer'>
                        <div itemprop='text'>
                        Ans. The main difference beyond price and support, is ".$winner." has an overall higher FindrScore 
                             which looks at over ".$x." data points powered by real user testing and overall satisfaction
                         </div>
                    </div>
                </div>
                ";   
                
           return $faq;     
       
    }
    


    public function calculate_fs($post_id,$post_id2){ //findrScore
         $reviews = get_overall_combined_rating($post_id);
         // print_r($reviews);
         $findrScore = 0;
         // $list = $reviews['list'];
         // print_r($reviews[list]);
         $findrScore += $reviews['list']['featuresfunctionality']['score']*4;
         $findrScore += $reviews['list']['easeofuse']['score']*2;
         $findrScore += $reviews['list']['customersupport']['score'];
         $findrScore += $reviews['list']['valueformoney']['score']*2;
         $featurelist1 = get_field( 'features_list', $post_id );
         $featurelist2 = get_field( 'features_list', $post_id2 );
         $i=0;
        /*  foreach($featurelist1 as $value){
             if(!empty($value)){
                 $i++;
             }
         } */
         // echo "i is ".$i;
         $n1 = sizeof($featurelist1);
         $n2 = sizeof($featurelist2);
         // echo $n1 . " and " .$n2;
         if($n1 > $n2)
             $findrScore += 25;

         
 $lists =  get_field( 'add_to_list', $post_id, false );
//  echo "lists ";
//  print_r($lists);
 $itemiid = $post_id;
 if ( !empty ( $lists ) && is_array( $lists ) ) {
     // echo '<ul class="compare-rank-list">';
     $listrankord = array();
     foreach ($lists as $id) {
         $status  = is_string( get_post_status( $id ) );
         if ($status && !empty($id)) {

             $rank = get_item_rank($id, $itemiid);
             $listrankord[$id] = $rank;
         }
     }}
     asort($listrankord);
     // echo " listrankord ";
     // print_r($listrankord);

     $lists =  get_field( 'add_to_list', $post_id2, false );
     $itemiid = $post_id2;
     if ( !empty ( $lists ) && is_array( $lists ) ) {
         // echo '<ul class="compare-rank-list">';
         $listrankord2 = array();
         foreach ($lists as $id) {
             $status  = is_string( get_post_status( $id ) );
             if ($status && !empty($id)) {

                 $rank = get_item_rank($id, $itemiid);
                 $listrankord2[$id] = $rank;
             }
         }}
         asort($listrankord2);
         // echo " listrankord2 ";
         // print_r($listrankord2);

         $commonLists = Array();
         foreach($listrankord as $key => $value){
             foreach($listrankord2 as $key2=>$value2){
                 // echo $key . " key " . " key2 ". $key2;
                 if($key == $key2)
                     $commonLists []= $key;
             }
         }
         $inc = 30/(sizeof($commonLists));
        //  echo $findrScore;
         foreach($commonLists as $cl){
             if($listrankord[''.$cl] < $listrankord2[''.$cl]){
                 $findrScore+=$inc;
             }
         }
        //  echo "commonlists ";
        //  print_r($commonLists);
         $this->findrScoreArr[$post_id]= round($findrScore);
            //   print_r($this->findrScoreArr);
        }

        function calc_cta($post_id){

                        		$affiliate_url = get_field( 'affiliate_url', $post_id  );

                                $availability = get_post_meta( $post_id, '_item_availbility', true );

                                if ( ! empty( $affiliate_url ) ) { 
                                $comaprd =  $_SERVER['REQUEST_URI'];
                                $comaprd =  str_replace('compare/', '', $comaprd);
                                $comaprd =  str_replace('/', '', $comaprd);
                                
                                if(substr_count($affiliate_url, "?")>=1){
                                    $affiliate_url.="&utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=".$comaprd;
                                }
                                else{
                                    $affiliate_url.="?utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=".$comaprd;
                                }
                                                                            
                                }
                            $affiliate_button_text = get_field( 'affiliate_button_text', $post_id  ) == '' ?'Download/Demo': get_field( 'affiliate_button_text', $post_id  );
                            $source_url    = get_field( 'source_url', $post_id  );
                            $credit_text   = get_field( 'credit', $post_id );
                            $afflink = empty( $affiliate_url )? $source_url:$affiliate_url;
                            $btntext = empty( $affiliate_button_text )? $credit_text:$affiliate_button_text;
                            $this->ctaArr[$post_id] =  Array(
                                                            'afflink' => $afflink,
                                                            'btntext' => $btntext
                                                        );
                        
        }

        function sort_features($total_fearturess, $features_ids){

            global $wpdb;
            $sorted_features_array = array();
            $a = array();
            foreach($total_fearturess as $total_fearturesi){
                $name_feature = str_replace(' ', '_', strtolower($total_fearturesi));
                $votes = "";
                // print_r($features_ids);
                $count_theme = count($features_ids);
                /* if($count_theme==0)
                    $count_theme++; */
                // echo "count theme : $count_theme";
                // print_r($features_ids);
            foreach($features_ids as $features_idi){
                // echo "SELECT votes FROM wpxx_feature_rating WHERE post_id = '$features_idi' && feature_name = '".$name_feature."' ";
                $db_data1 = $wpdb->get_results( "SELECT votes FROM wpxx_feature_rating WHERE post_id = '$features_idi' && feature_name = '".$name_feature."' "  );
                // echo "db_data1\n";
                // print_r($db_data1);
                if($wpdb->num_rows > 0){
                    // print_r($db_data1);
            foreach($db_data1 as $datas1){
                $votes += $datas1->votes;
                }
            }
            }
            // echo "isset votes ".isset($votes);

            if(isset($votes)){
                $a['vote']= $votes/$count_theme;
            }else{
                $a['vote']= 0;
            }
            $a['feature']= $total_fearturesi;
            $sorted_features_array[] = $a;
                }
            arsort($sorted_features_array);
            // print_r($sorted_features_array);

            return $sorted_features_array;
        }
    }
add_action('wp','initiate_mv_list_comparision');
function initiate_mv_list_comparision()
{

    global $wp_query;
    $compareId =get_query_var('cid');
    if(empty($compareId)){
		if(!empty($_GET['cid'])){
        $compareId = $_GET['cid'];
		}
    }

    new Mv_List_Comparision($compareId);
}
?>
