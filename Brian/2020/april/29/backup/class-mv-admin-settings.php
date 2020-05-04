<?php

class Mv_List_Item_Settings {
	function __construct() {



		add_action( 'admin_menu', array( $this, 'register_setting_page' ) );

		add_action( 'admin_init', array( $this, 'initialize_options' ) );

	}



	function register_setting_page() {

		add_submenu_page(  'edit.php?post_type=list_items', 'Item Settings', 'Item Settings', 'manage_options', 'mv_list_items_settings', array( $this, 'mv_list_items_settings_callback' ) );

		add_submenu_page(  'edit.php?post_type=list_items', 'Item Reports', 'Item Reports', 'manage_options', 'mv_list_items_reports', array( $this, 'mv_list_items_reports_callback' ) );

	}

function mv_list_items_reports_callback(){

	    global $wpdb;

	   $data=  $wpdb->get_results("SELECT * from wpxx_reports");

	    ?>

    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

    <script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

	 <div class="wrap">

         <h2>Item Reports</h2>

         <div class="reports_list">

             <table id="reportatble">

                 <thead><tr>

                 <th>Item</th>

                 <th>List</th>

                 <th>Reason</th>

                 <th>Comment</th>

                 <th>Time</th>

                 <th>User</th>

                 </tr>

                 </thead>

                 <tbody>

                 <?php if(!empty($data) && is_array($data)){

                     foreach ($data as $item){

                         $user_info = get_userdata($item->user);

                         echo '<tr>

<td><a href="'.get_edit_post_link($item->item_id).'">'.get_the_title($item->item_id).'</a></td>

<td><a href="'.get_edit_post_link($item->list_id).'">'.get_the_title($item->list_id).'</a></td>

<td>'.$item->reason.'</td>

<td>'.$item->comment.'</td>

<td>'.date('d/m/Y h:i a',$item->time).'</td>

<td>'.(!empty($user_info)?$user_info->first_name.' '.$user_info->last_name:'') .'</td>

</tr>';

                     }

                 } ?>

                 </tbody>

                 <tfoot><tr>

                 <th>Item</th>

                 <th>List</th>

                 <th>Reason</th>

                 <th>Comment</th>

                 <th>Time</th>

                 <th>User</th>

                 </tr>

                 </tfoot>

             </table>

         </div>

	   </div>

    <script type="text/javascript">

        jQuery(document).ready( function () {

            jQuery('#reportatble').DataTable({

                "order": [[ 4, "desc" ]]

            });

        } );

    </script>

    <?php

}

	function mv_list_items_settings_callback() {

?>

		<!-- Create a header in the default WordPress 'wrap' container -->

    <div class="wrap">



        <!-- Make a call to the WordPress function for rendering errors when settings are saved. -->

        <?php settings_errors(); ?>



        <!-- Create the form that will be used to render our options -->

        <form method="post" action="options.php">

            <?php settings_fields( 'mv_list_items_settings' ); ?>

            <?php do_settings_sections( 'mv_list_items_settings' ); ?>

            <?php submit_button(); ?>

        </form>



    </div><!-- /.wrap -->

<?php

	}





	/**

	 * Register settings and fields

	 *

	 * @author Aman Saini

	 * @since  1.0

	 * @return [type] [description]

	 */

	function initialize_options() {



		// If settings don't exist, create them.

		if ( false == get_option( 'mv_list_items_settings' ) ) {

			add_option( 'mv_list_items_settings' );

		} // end if



		add_settings_section(

			'mv_list_settings_section',         // ID used to identify this section and with which to register options

			'List Items Settings',                  // Title to be displayed on the administration page

			array( $this, 'list_archive_settings_callback' ), // Callback used to render the description of the section

			'mv_list_items_settings'                           // Page on which to add this section of options

		);







		// API Username

		add_settings_field(

			'list_archive_page_title',

			'Page Title',

			array( $this, 'list_archive_page_title' ),

			'mv_list_items_settings',

			'mv_list_settings_section'



		);



		// API Password

		add_settings_field(

			'list_archive_page_description',

			'Description',

			array( $this, 'list_archive_page_description' ),

			'mv_list_items_settings',

			'mv_list_settings_section'



		);



		add_settings_section(

			'mv_list_items_settings_section',

			'',

			array( $this, 'list_settings_callback' ),

			'mv_list_items_settings'

		);





			// API Username

		add_settings_field(

			'archive_page_title',

			'Page Title',

			array( $this, 'archive_page_title_callback' ),

			'mv_list_items_settings',

			'mv_list_items_settings_section'



		);



		// API Password

		add_settings_field(

			'archive_page_description',

			'Description',

			array( $this, 'archive_page_description_callback' ),

			'mv_list_items_settings',

			'mv_list_items_settings_section'



		);





		add_settings_section(

			'mv_alternative_page_settings_section',         // ID used to identify this section and with which to register options

			'',                  // Title to be displayed on the administration page

			array( $this, 'alternative_settings_callback' ), // Callback used to render the description of the section

			'mv_list_items_settings'                           // Page on which to add this section of options

		);



		add_settings_field(

			'alternative_page_title',

			'Page Title',

			array( $this, 'alternative_page_title_callback' ),

			'mv_list_items_settings',

			'mv_alternative_page_settings_section'



		);

		add_settings_field(

			'alternative_page_description',

			'Page Meta Description',

			array( $this, 'alternative_page_desc_callback' ),

			'mv_list_items_settings',

			'mv_alternative_page_settings_section'



		);

		add_settings_field(

			'alternative_page_btn_text',

			'Button Text',

			array( $this, 'alternative_page_btn_text' ),

			'mv_list_items_settings',

			'mv_alternative_page_settings_section'



		);
/* coupon ------------------------------------------------*/
		add_settings_section(

			'mv_coupon_page_settings_section',         // ID used to identify this section and with which to register options

			'',                  // Title to be displayed on the administration page

			array( $this, 'coupon_settings_callback' ), // Callback used to render the description of the section

			'mv_list_items_settings'                           // Page on which to add this section of options

		);

add_settings_field(

			'coupon_page_title',

			'Page Title',

			array( $this, 'coupon_page_title_callback' ),

			'mv_list_items_settings',

			'mv_coupon_page_settings_section'



		);

		add_settings_field(

			'coupon_page_description',

			'Page Meta Description',

			array( $this, 'coupon_page_desc_callback' ),

			'mv_list_items_settings',

			'mv_coupon_page_settings_section'



		);
		
	add_settings_field(

			'coupon_page_subscription_form',

			'Subscription form for coupon page',

			array( $this, 'coupon_page_subscription_form_callback' ),

			'mv_list_items_settings',

			'mv_coupon_page_settings_section'



		);
			add_settings_field(

			'coupon_subscription_form',

			'Subscription form for Coupon Page popup',

			array( $this, 'coupon_subscription_form_callback' ),

			'mv_list_items_settings',

			'mv_coupon_page_settings_section'



		);
		add_settings_field(

			'deal_subscription_form',

			'Deal Page Subscription form',

			array( $this, 'deal_subscription_form_callback' ),

			'mv_list_items_settings',

			'mv_coupon_page_settings_section'



		);
		
		add_settings_field(

			'coupon_page_btn_text',

			'Button Text',

			array( $this, 'coupon_page_btn_text' ),

			'mv_list_items_settings',

			'mv_coupon_page_settings_section'



		);

/* coupon ------------------------------------------------*/
		
		add_settings_section(

			'mv_comparison_page_settings_section',         // ID used to identify this section and with which to register options

			'',                  // Title to be displayed on the administration page

			array( $this, 'comparison_settings_callback' ), // Callback used to render the description of the section

			'mv_list_items_settings'                           // Page on which to add this section of options

		);





		add_settings_field(

			'comparison_page_title',

			'Page Title',

			array( $this, 'comparison_page_title_callback' ),

			'mv_list_items_settings',

			'mv_comparison_page_settings_section'



		);

		add_settings_field(

			'comparison_page_description',

			'Page Meta Description',

			array( $this, 'comparison_page_desc_callback' ),

			'mv_list_items_settings',

			'mv_comparison_page_settings_section'



		);

		# List order

		add_settings_field(

			'comparison_page_order',

			'sections Order',

			array( $this, 'comparison_page_list_order_callback' ),

			'mv_list_items_settings',

			'mv_comparison_page_settings_section'


		);


        add_settings_section(

            'mv_list_page_page_settings_section',         // ID used to identify this section and with which to register options

            '',                  // Title to be displayed on the administration page

            array( $this, 'listpage_settings_callback' ), // Callback used to render the description of the section

            'mv_list_items_settings'                           // Page on which to add this section of options

        );

        add_settings_field(

            'list_page_google_add',

            'Google Advertisement',

            array( $this, 'list_page_google_add_callback' ),

            'mv_list_items_settings',

            'mv_list_page_page_settings_section'



		);
		
		add_settings_field(

            'list_page_target_countries',

            'Targetted Countries',

            array( $this, 'list_page_targetted_countries_callback' ),

            'mv_list_items_settings',

            'mv_list_page_page_settings_section'



        );
		
		add_settings_section(
            'mv_list_scroll_settings_section',  
            '',              
            array( $this, 'scroll_settings_callback' ), 
            'mv_list_items_settings'               

        );

        add_settings_field(
            'scroll_setting',
            'Scroll Behaviour',
            array( $this, 'list_scroll_setting_callback' ),
            'mv_list_items_settings',
            'mv_list_scroll_settings_section'

        );



		//register settings

		register_setting( 'mv_list_items_settings', 'mv_list_items_settings' );



	}



	function list_settings_callback() {



		echo '<h2>List Item Archive Page</h2>';

	}

	function list_archive_settings_callback() {



		echo '<h2>List Archive Page</h2>';

	}

/* coupon ------------------------------------------------*/
	function coupon_settings_callback() {



		echo '<h2>Coupon Page</h2>';

	}
	
	
	/* coupon ------------------------------------------------*/

	function alternative_settings_callback() {



		echo '<h2>Alternative Page</h2>';

	}
	function comparison_settings_callback() {



		echo '<h2>Comparison Page</h2>';

	}

    function listpage_settings_callback() {
		echo '<h2>List Single Page</h2>';
 	}
	 function scroll_settings_callback() {
		echo '<h2>List page scroll setting</h2>';
 	}



	function list_archive_page_title() {

		$options = get_option( 'mv_list_items_settings' );



		$app_id = !empty( $options['list_archive_page_title'] )?$options['list_archive_page_title']:'';



		// Render the output

		echo '<input type="text" class="regular-text" id="app_id" name="mv_list_items_settings[list_archive_page_title]" value="' . $app_id. '" />';

		echo '<p class="description">Page Title to show on list Archive Page</a></p>';

	}



	function list_archive_page_description() {

		$options = get_option( 'mv_list_items_settings' );



		$content = !empty( $options['list_archive_page_description'] )?$options['list_archive_page_description']:'';



		// Render the output

		wp_editor( $content, 'list_archive_page_description', array( 'textarea_name'=>'mv_list_items_settings[list_archive_page_description]' ) );

		//echo '<textarea rows="8" cols="70" name="mv_list_items_settings[archive_page_description]">' . $app_id. '</textarea>';

		echo '<p class="description">Description to show on Archive Page</a></p>';

	}









	function archive_page_title_callback() {

		$options = get_option( 'mv_list_items_settings' );



		$app_id = !empty( $options['archive_page_title'] )?$options['archive_page_title']:'';



		// Render the output

		echo '<input type="text" class="regular-text" id="app_id" name="mv_list_items_settings[archive_page_title]" value="' . $app_id. '" />';

		echo '<p class="description">Page Title to show on List Item Archive Page</a></p>';

	}



	function archive_page_description_callback() {

		$options = get_option( 'mv_list_items_settings' );



		$content = !empty( $options['archive_page_description'] )?$options['archive_page_description']:'';



		// Render the output

		wp_editor( $content, 'archive_page_description', array( 'textarea_name'=>'mv_list_items_settings[archive_page_description]' ) );

		//echo '<textarea rows="8" cols="70" name="mv_list_items_settings[archive_page_description]">' . $app_id. '</textarea>';

		echo '<p class="description">Description to show on List Item  Archive Page</a></p>';

	}

/* coupon callback ------------------------------------------------*/
	
	function coupon_page_title_callback() {

		$options = get_option( 'mv_list_items_settings' );



		$app_id = !empty( $options['coupon_page_title'] )?$options['coupon_page_title']:'';



		// Render the output

		echo '<input type="text" class="large-text" id="app_id" name="mv_list_items_settings[coupon_page_title]" value="' . $app_id. '" />';

		echo '<p class="description">Page Title to show on Coupon Page. [Item name] will be replaced with review title </a></p>';

	}
	/* coupon callback ------------------------------------------------*/

	function alternative_page_title_callback() {

		$options = get_option( 'mv_list_items_settings' );



		$app_id = !empty( $options['alternative_page_title'] )?$options['alternative_page_title']:'';



		// Render the output

		echo '<input type="text" class="large-text" id="app_id" name="mv_list_items_settings[alternative_page_title]" value="' . $app_id. '" />';

		echo '<p class="description">Page Title to show on Alternative Page. [Item name] will be replaced with review title </a></p>';

	}

	function comparison_page_title_callback() {

		$options = get_option( 'mv_list_items_settings' );



		$app_id = !empty( $options['comparison_page_title'] )?$options['comparison_page_title']:'';



		// Render the output

		echo '<input type="text" class="large-text" id="app_id" name="mv_list_items_settings[comparison_page_title]" value="' . $app_id. '" />';

		echo '<p class="description">Page Title to show on Comparison Page. </a></p>';

	}
/* coupon coupon_page_desc_callback ------------------------------------------------*/
	function coupon_page_desc_callback() {

		$options = get_option( 'mv_list_items_settings' );



		$app_id = !empty( $options['coupon_page_description'] )?$options['coupon_page_description']:'';



		// Render the output

		echo '<textarea class="large-text" id="app_id" name="mv_list_items_settings[coupon_page_description]"  >'.$app_id.'</textarea>';

		echo '<p class="description">Page meta description to show on Coupon Page. [Item name] will be replaced with review title </a></p>';

	}

	
	
	
	
	/* coupon coupon_page_desc_callback ------------------------------------------------*/
function alternative_page_desc_callback() {

		$options = get_option( 'mv_list_items_settings' );



		$app_id = !empty( $options['alternative_page_description'] )?$options['alternative_page_description']:'';



		// Render the output

		echo '<textarea class="large-text" id="app_id" name="mv_list_items_settings[alternative_page_description]"  >'.$app_id.'</textarea>';

		echo '<p class="description">Page meta description to show on Alternative Page. [Item name] will be replaced with review title </a></p>';

	}

	function comparison_page_desc_callback() {

		$options = get_option( 'mv_list_items_settings' );



		$app_id = !empty( $options['comparison_page_description'] )?$options['comparison_page_description']:'';



		// Render the output

		echo '<textarea class="large-text" id="app_id" name="mv_list_items_settings[comparison_page_description]"  >'.$app_id.'</textarea>';

		echo '<p class="description">Page meta description to show on Comparison Page.  </a></p>';

	}

	function comparison_page_list_order_callback() {

		$options = get_option( 'mv_list_items_settings' );

		$app_id = !empty( $options['comparison_page_order'] )?$options['comparison_page_order']:'';


		// Render the output

		echo '<input type="text" class="large-text" id="app_id" name="mv_list_items_settings[comparison_page_order]" value="' . $app_id. '" />';

		echo '<p class="order">Order of sections to show on Comparison Page. Use lowercase only eg. video, pricing, overview, ratings, features, hidden, betterthan, support, reviews, screenshots, download, ranking </a></p>';

	}


/* coupon coupon_page_btn_text  and subscription------------------------------------------------*/
	function coupon_page_subscription_form_callback() {

		$options = get_option( 'mv_list_items_settings' );



		$app_id = !empty( $options['coupon_page_subscription_form'] )?$options['coupon_page_subscription_form']:'';



		// Render the output

		echo '<textarea class="large-text" id="app_id" name="mv_list_items_settings[coupon_page_subscription_form]"  >'.$app_id.'</textarea>';

		echo '<p class="description">Subscription form  </a></p>';

	}
	function coupon_subscription_form_callback() {

		$options = get_option( 'mv_list_items_settings' );



		$app_id = !empty( $options['coupon_subscription_form'] )?$options['coupon_subscription_form']:'';



		// Render the output

		echo '<textarea class="large-text" id="app_id" name="mv_list_items_settings[coupon_subscription_form]"  >'.$app_id.'</textarea>';

		echo '<p class="description"> Coupon page Subscription form  </a></p>';

	}
	function deal_subscription_form_callback() {

		$options = get_option( 'mv_list_items_settings' );



		$app_id = !empty( $options['deal_subscription_form'] )?$options['deal_subscription_form']:'';



		// Render the output

		echo '<textarea class="large-text" id="app_id" name="mv_list_items_settings[deal_subscription_form]"  >'.$app_id.'</textarea>';

		echo '<p class="description"> Deal Page Subscription form  </a></p>';

	}
	

	function coupon_page_btn_text() {

		$options = get_option( 'mv_list_items_settings' );



		$app_id = !empty( $options['coupon_page_btn_text'] )?$options['coupon_page_btn_text']:'';



		// Render the output

		echo '<input type="text" class="normal-text" id="app_id" name="mv_list_items_settings[coupon_page_btn_text]" value="' . $app_id. '" />';

		//echo '<p class="description">Button T </a></p>';

	}

	/* coupon coupon_page_btn_text ------------------------------------------------*/




	function alternative_page_btn_text() {

		$options = get_option( 'mv_list_items_settings' );



		$app_id = !empty( $options['alternative_page_btn_text'] )?$options['alternative_page_btn_text']:'';



		// Render the output

		echo '<input type="text" class="normal-text" id="app_id" name="mv_list_items_settings[alternative_page_btn_text]" value="' . $app_id. '" />';

		//echo '<p class="description">Button T </a></p>';

	}

	function list_page_google_add_callback() {

		$options = get_option( 'mv_list_items_settings' );



		$app_id = !empty( $options['list_page_google_add'] )?$options['list_page_google_add']:'';



		// Render the output

		echo '<textarea id="app_id" name="mv_list_items_settings[list_page_google_add]" style="width: 100%;

    height: 200px;">' . $app_id. '</textarea>';

		//echo '<p class="description">Button T </a></p>';

	}

	function list_page_targetted_countries_callback(){
		$options = get_option( 'mv_list_items_settings' );

		$app_id = !empty( $options['list_page_target_countries'] )?$options['list_page_target_countries']:'';


		// Render the output
		echo '<textarea id="app_id" name="mv_list_items_settings[list_page_target_countries]" style="width: 100%;

    height: 200px;">' . $app_id. '</textarea>';
		// echo '<input type="text" class="large-text" id="app_id" name="mv_list_items_settings[list_page_target_countries]" value="' . $app_id. '" />';

		echo '<p class="order">Alpha 2 codes of targetted countries. Use UPPERCASE, separated by => (no spaces between = and >) , single quotes at appropriate places eg. "\'IN\' => \'India\',     \'US\' => \'United States\',     \'GB\' => \'United Kingdom\', " refer to : <a href="https://www.nationsonline.org/oneworld/country_code_list.htm">nationsonline.org</a> <a href="https://raw.githubusercontent.com/blazeworx/flagstrap/master/src/jquery.flagstrap.js">Flagstrap</a></a></p>';

	}


	function list_scroll_setting_callback(){ 
		$options = get_option( 'mv_list_items_settings' );
		$scroll = !empty( $options['scroll_setting'] )?$options['scroll_setting']:'';
	?>
		
        <select name="mv_list_items_settings[scroll_setting]">
        	<option <?php echo ($scroll == 'numeric' ? 'selected' : ''); ?> value="numeric">Numeric Pagination</Option>
            <option <?php echo ($scroll == 'infinite' ? 'selected' : ''); ?> value="infinite">Infinite Scroll</Option>
            <option <?php echo ($scroll == 'loadbutton' ? 'selected' : ''); ?> value="loadbutton">Load More Button</Option>
        </select>
	<?php
    }




}

new Mv_List_Item_Settings();

