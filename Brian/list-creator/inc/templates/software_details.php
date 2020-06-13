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

<div class="sub-page">
			<h4 class="sub-header">Create a Free Listing OR Claim your Product</h4>		
				

					<div class="sub-body"><h2 class="delta subheading">Every month, more than&nbsp;<span class="weight-semibold">100,000 business software buyers</span>&nbsp;use SoftwareFindr. With a free listing, you’ll reach relevant customers precisely when they are researching the kind of software you offer.</h2></div>

<?php
echo do_shortcode("[mv_search]");
$html_before_form = '<input type="hidden" name="acf[register]" value="true"/>';
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
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>