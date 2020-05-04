<?php
/**
 * Flatsome functions and definitions
 *
 * @package flatsome
 */

require get_template_directory() . '/inc/init.php';

/**
 * Note: It's not recommended to add any custom code here. Please use a child theme so that your customizations aren't lost during updates.
 * Learn more here: http://codex.wordpress.org/Child_Themes
 */
function my_scripts() {
    //bootstrap
    wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
    wp_enqueue_script( 'boot10','https://code.jquery.com/jquery-3.3.1.slim.min.js', array( 'jquery' ),'',false );
    
    //table for /buy-diamonds
    wp_enqueue_script( 'boot5',get_template_directory_uri().'/assets/js/jquery.min.js', array( 'jquery' ),'',false );
    wp_enqueue_script( 'boot6',get_template_directory_uri().'/assets/js/bootstrap.min.js', array( 'jquery' ),'',false );
    wp_enqueue_script( 'boot3',get_template_directory_uri().'/assets/js/jquery.csv.min.js', array( 'jquery' ),'',false );
    wp_enqueue_script( 'boot4',get_template_directory_uri().'/assets/js/jquery.dataTables.min.js', array( 'jquery' ),'',false );
    wp_enqueue_script( 'boot2',get_template_directory_uri().'/assets/js/dataTables.bootstrap.js', array( 'jquery' ),'',false );

    wp_enqueue_script( 'boot1',get_template_directory_uri().'/assets/js/csv_to_html_table.js', array( 'jquery' ),'',false );
    wp_enqueue_script( 'boot9',get_template_directory_uri().'/assets/js/my.js', array( 'jquery' ),'',false );

    //bootstrap
    wp_enqueue_script( 'boot7','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array( 'jquery' ),'',false );
    wp_enqueue_script( 'boot8','https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array( 'jquery' ),'',false );

}
add_action( 'wp_enqueue_scripts', 'my_scripts' );