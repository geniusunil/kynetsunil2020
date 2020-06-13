<?php 
/*
 * Template Name: user_dashboard
 * 
 */
 ?>
<?php get_header(); ?>
	<style>
		form#form-1 {
		width: 80%;
		margin: 0 auto;
	}
		.software_name {
		width: 40%;
		float: left;
	}

		.software_link1 {
		width: 50%;
		float: left;
	}
	</style>
		<h1 align="center">Claimed Software</h1>
			<form id="form-1" class="form" action="" method="post">			
				<?php
					$user_id1 = get_current_user_id();
					$site_url = get_site_url();
					global $wpdb;
					$table_name = $wpdb->prefix . "user_wise_claim_list";
					// echo $wpdb->dbname."table name is ". $table_name;
					$user_data = $wpdb->get_results( "SELECT software_ids FROM $table_name WHERE user_id = {$user_id1} " ); 
						foreach ($user_data as $new_user_data){	
							$software_id = $new_user_data->software_ids ;	
							//echo $software_id.'<br>';	
							echo "<div class='softwar_details'>";
							echo "<div class='software_name'>";
							$software_name = get_the_title($new_user_data->software_ids );
							echo $software_name.'<br>';
							echo "</div>";
							echo "<div class='software_link1'>";
							$software_link = get_permalink( $new_user_data->software_ids);
							echo $software_link.'<br>';
							echo "</div>";	
							echo '<p class="edit">									
							<a href="'.$site_url.'/update-software?update-software='.$software_id.'">Edit</a>
							</p>';	
							echo "</div>";
						}

				?>

			</form>

<?php get_sidebar(); ?>
<?php get_footer(); ?>