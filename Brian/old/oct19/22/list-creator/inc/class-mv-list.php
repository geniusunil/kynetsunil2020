<?php

class Mv_List {
    private $url_file;
    function __construct()
    {

        add_action('wp_enqueue_scripts',array($this,'add_single_list_js_css'),PHP_INT_MAX);
    }
    function  add_single_list_js_css(){
        $temp = get_page_template();
      
        if ( is_single() &&  'lists' === get_post_type() ) {
//            wp_deregister_style('c27-style');
            wp_enqueue_style('roboto-fonts','https://fonts.googleapis.com/css?family=Roboto');
            wp_enqueue_style('bootstrap','https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css');
            wp_enqueue_style('bootstrap-fontawesome','https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
            wp_enqueue_style('owl.carousel.min.css',plugins_url( 'assets/list/style/owl.carousel.min.css',dirname(__FILE__)));
            wp_enqueue_style('owl.theme.default.min.css',plugins_url( 'assets/list/style/owl.theme.default.min.css',dirname(__FILE__)));
            wp_enqueue_style('prettyPhoto.css',plugins_url( 'assets/list/style/prettyPhoto.css',dirname(__FILE__)));
            wp_enqueue_style('stackGallery_horizontal.css',plugins_url( 'assets/list/style/stackGallery_horizontal.css',dirname(__FILE__)),array(),time());
            wp_enqueue_style('morris.css',plugins_url( 'css/morris.css',dirname(__FILE__)));
            wp_enqueue_style('single-list-style',plugins_url( 'assets/list/style/style.css',dirname(__FILE__)),array(),time(),'all');
            wp_enqueue_script('bootstrap_js','https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js',array('jquery'),false,true);
            wp_enqueue_script('owl.carousel.js',plugins_url( 'assets/list/js/owl.carousel.js',dirname(__FILE__)),array('jquery'),false,true);
            wp_enqueue_script('jquery.easing.1.3.js',plugins_url( 'assets/list/js/jquery.easing.1.3.js',dirname(__FILE__)),array('jquery'),false,true);
            wp_enqueue_script('jquery.prettyPhoto.js',plugins_url( 'assets/list/js/jquery.prettyPhoto.js',dirname(__FILE__)),array('jquery'),false,true);
            /******** Transform Plugin********/
            wp_enqueue_script('jquery.transform.js',plugins_url( 'assets/list/js/tr/jquery.transform.js',dirname(__FILE__)),array('jquery'),false,true);
            wp_enqueue_script('jquery.angle.js',plugins_url( 'assets/list/js/tr/jquery.angle.js',dirname(__FILE__)),array('jquery'),false,true);
            wp_enqueue_script('jquery.matrix.js',plugins_url( 'assets/list/js/tr/jquery.matrix.js',dirname(__FILE__)),array('jquery'),false,true);
            wp_enqueue_script('jquery.matrix.calculations.js',plugins_url( 'assets/list/js/tr/jquery.matrix.calculations.js',dirname(__FILE__)),array('jquery'),false,true);
            wp_enqueue_script('jquery.matrix.functions.js',plugins_url( 'assets/list/js/tr/jquery.matrix.functions.js',dirname(__FILE__)),array('jquery'),false,true);
            wp_enqueue_script('jquery.transform.animate.js',plugins_url( 'assets/list/js/tr/jquery.transform.animate.js',dirname(__FILE__)),array('jquery'),false,true);
            wp_enqueue_script('jquery.transform.attributes.js',plugins_url( 'assets/list/js/tr/jquery.transform.attributes.js',dirname(__FILE__)),array('jquery'),false,true);
            /******** End Transform Plugin********/
            wp_enqueue_script('jquery.touchSwipe.min.js',plugins_url( 'assets/list/js/jquery.touchSwipe.min.js',dirname(__FILE__)),array('jquery'),false,true);
            wp_enqueue_script('jquery.func.js',plugins_url( 'assets/list/js/jquery.func.js',dirname(__FILE__)),array('jquery'),false,true);
            wp_enqueue_script('jquery.stackGallery.min.js',plugins_url( 'assets/list/js/jquery.stackGallery.min.js',dirname(__FILE__)),array('jquery'),false,true);
            wp_localize_script('jquery.stackGallery.min.js','path',array('img'=>image_path_single()));
            wp_enqueue_script('morris.min.js',plugins_url( 'js/morris.min.js',dirname(__FILE__)),array('jquery'),false,true);
            wp_enqueue_script('raphael-min.js',plugins_url( 'js/raphael-min.js',dirname(__FILE__)),array('jquery'),false,true);
            wp_enqueue_script('single_list_script',plugins_url( 'assets/list/js/script.js',dirname(__FILE__)),array('jquery'),time(),true);
            wp_localize_script('single_list_script', 'objData', array(
                'ajaxUrl' => admin_url('admin-ajax.php1')
            ));

        }
    }
};
new Mv_List();
