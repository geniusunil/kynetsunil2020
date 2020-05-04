<?php
/*
 * Template Name: software_details 
 * Template Post Type: post, page
 */
 ?>
<?php 
ob_get_clean(); 
acf_form_head(); ?>
<?php get_header(); ?>

<?php
echo do_shortcode("[mv_search]");
$html_before_form .= '<input type="hidden" name="acf[register]" value="true"/>';
$site_url = get_site_url();

$settings = array(
      'id' => 'acf-form',    
     'html_after_fields' => $html_before_form,  
      'post_id' => 'new_post',
      'post_title' => true,
      'post_content' => true,
	  '_thumbnail_id' => 'true',
	  'form_attributes' => 'hidden',
      'new_post'		=> array(
              'post_type'		=> 'list_items',
              'post_status'		=> 'pending'
              
            ),
	  'field_groups' => array(146140),
	  'return'		=> $site_url.'/update-software/?update-software= %post_id%',
	  'submit_value'		=> 'Next', 




	
      );     
      
      $value = acf_form( $settings ); 

?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>