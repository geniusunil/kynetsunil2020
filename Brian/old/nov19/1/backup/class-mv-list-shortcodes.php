<?php
class Mv_List_Shortcodes {

    public function __construct(){
         // shortcode
         add_shortcode( 'search_and_compare', array( $this, 'search_compare' ) );
         //list data
         add_action( 'wp_ajax_list_data', array( $this, 'list_data' ) );
         add_action( 'wp_ajax_nopriv_list_data', array( $this, 'list_data') );

         add_action( 'wp_ajax_list_compare_items', array( $this, 'list_compare_items' ) );
         add_action( 'wp_ajax_nopriv_list_compare_items', array( $this, 'list_compare_items' ));

         add_action( 'wp_ajax_list_compare_button', array( $this, 'list_compare_button' ) );
         add_action( 'wp_ajax_nopriv_list_compare_button', array( $this, 'list_compare_button' ));
      
         
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