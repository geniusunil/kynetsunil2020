
<?php /*
 * Template Name: review_form 
 * Template Post Type: post, page
 */
 ?>
	<?php 
	ob_get_clean(); 
	acf_form_head(); ?>
	<?php get_header(); ?>
	<?php
		$html_before_form .= '<input type="hidden" name="acf[review]" value="true"/>';
		$settings = array(

		  /* (string) Unique identifier for the form. Defaults to 'acf-form' */
		  'id' => 'acf-review-form',


		'field_groups' => array(145826),
		'submit_value'		=> 'Submit',
		'html_after_fields' => $html_before_form

		  );	      
      	$value = acf_form( $settings ); 
	add_post_meta($post_id, '_thumbnail_id', $value);
    
?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>