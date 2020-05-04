<?php
class Mv_List_Archive_Filter_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'list_archive_filter_widget',
			'description' => 'List Archive Filter Widget',
		);
		parent::__construct( 'list_archive_filter_widget', 'List Archive Filter Widget', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array   $args
	 * @param array   $instance
	 */
	public function widget( $args, $instance ) {

		// $rating  = get_post_meta( 361, 'rwp_reviews', true );
		// echo '<pre>';
		// print_r($rating); die;

		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
?>
		<form class='filter_list_items_form'>

		<h3>Pricing model</h3>
		<?php
		$pricing_model= array();
		if ( isset( $_GET['pricing_model'] ) && ! empty( $_GET['pricing_model'] ) ) {
			$pricing_model = explode( ',', sanitize_text_field( $_GET['pricing_model'] ) );
		}
?>
		<ul>
			<li><input <?php echo  in_array( 'free_trial', $pricing_model )?'checked="checked"':''; ?> type="checkbox" name="pricing_model" value="free_trial"> Free Trial</li>
			<li><input <?php echo  in_array( 'freemium', $pricing_model )?'checked="checked"':''; ?> type="checkbox" name="pricing_model" value="freemium"> Freemium</li>
			<li><input <?php echo  in_array( 'one_time_license', $pricing_model )?'checked="checked"':''; ?> type="checkbox" name="pricing_model" value="one_time_license"> One-time License</li>
			<li><input <?php echo  in_array( 'open_source', $pricing_model )?'checked="checked"':''; ?> type="checkbox" name="pricing_model" value="open_source"> Open-source</li>
			<li><input <?php echo  in_array( 'subscription', $pricing_model )?'checked="checked"':''; ?> type="checkbox" name="pricing_model" value="subscription"> Subscription</li>
		</ul>
		<h3>Product type</h3>
		<?php
		$product_type= array();
		if ( isset( $_GET['product_type'] ) && ! empty( $_GET['product_type'] ) ) {
			$product_type = explode( ',', sanitize_text_field( $_GET['product_type'] ) );
		}
?>
		<ul>
			<li><input <?php echo  in_array( 'template', $product_type )?'checked="checked"':''; ?> type="checkbox" name="product_type" value="template"> Template</li>
			<li><input <?php echo  in_array( 'service', $product_type )?'checked="checked"':''; ?> type="checkbox" name="product_type" value="service"> Service</li>
			<li><input <?php echo  in_array( 'plugin', $product_type )?'checked="checked"':''; ?> type="checkbox" name="product_type" value="plugin"> Plugin</li>
		</ul>
		<h3> Editor's choice</h3>
		<ul>
			<li><input <?php  checked( $_GET['editor_choice'], 'editor_choice', 1 ) ?> type="checkbox" name="editor_choice" value="editor_choice"></li>
		</ul>


		<input type="submit" name="submit" value="Filter">
		<!-- <div>Software::</div>
		<div>Pricing model:</div> -->
		</form>

		<script>
			(function($){
				$(function(){

					$('#tags').tagsInput({
						width: 'auto',
						'onRemoveTag':add_tag_back_to_list,
						//autocomplete_url:"<?php echo admin_url( 'admin-ajax.php?action=get_filter_tags' ) ?>",
					});

					$( 'body' ).on( 'submit', '.filter_list_items_form', function(e){
						e.preventDefault();
						var rating, editor_choice, product_type, pricing_model, software, tags;
						rating = editor_choice = product_type = pricing_model = software = tags = '';

						var fields = $(this).serializeArray();
						$.each( fields, function(i, field){
							switch(field.name){
								case 'rating':
								rating = field.value;
								break;
								case 'pricing_model':
								pricing_model += (pricing_model.length > 0 ? ',' : '') + field.value ;
								break;
								case 'product_type':
								product_type += (product_type.length > 0 ? ',' : '') + field.value ;
								break;
								case 'editor_choice':
								editor_choice = field.value;
								break;
								case 'software':
								break;
								case 'tags':
								tags= field.value;
								break;
							}

						})
						query  = '?rating='+rating+'&pricing_model='+pricing_model+'&product_type='+product_type+'&editor_choice='+editor_choice+'&tags='+tags;
						var redirect_url = [location.protocol, '//', location.host, location.pathname].join('')+query
						window.location.href = redirect_url;
						//console.log(fields)
					})

				})
			})(jQuery);



		</script>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array   $instance The widget options
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array   $new_instance The new options
	 * @param array   $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}
}


add_filter( 'pre_get_posts', 'multiverse_filter_list_archives' );
function multiverse_filter_list_archives( $query ) {
	echo 'here'; die;


	//if ( is_home() ) {
	if ( ! is_admin() && (is_post_type_archive( 'lists' ) || is_post_type_archive( 'list_items' ) )) {

		$args = array();

		if ( isset( $_GET['pricing_model'] ) && ! empty( $_GET['pricing_model'] ) ) {
			$pricing_model = explode( ',', sanitize_text_field( $_GET['pricing_model'] ) );
			$args[] =array(
				'key' => 'pricing_model',
				'value' => $pricing_model,
				'compare' => 'LIKE'
			);
		}
		if ( isset( $_GET['product_type'] ) && ! empty( $_GET['product_type'] ) ) {
			$product_type = explode( ',', sanitize_text_field( $_GET['product_type'] ) );
			$args[] =array(
				'key' => 'product_type',
				'value' => $product_type,
				'compare' => 'LIKE'
			);
		}

		if ( isset( $_GET['editor_choice'] ) && ! empty( $_GET['editor_choice'] ) ) {
			$args[] =array(
				'key' => 'editor_choice',
				'value' => true,
			);
		}
		if ( isset( $_GET['rating'] ) && ! empty( $_GET['rating'] ) ) {
			$args[] =array(
				'key' => 'user_rating_custom',
				'value' =>  sanitize_text_field( $_GET['rating'] ),
				'compare' => '>='
			);

		}

		if ( isset( $_GET['tags'] ) && ! empty( $_GET['tags'] ) ) {
			$tags = explode( ',', sanitize_text_field( $_GET['tags'] ) );
			$query->set( 'tax_query', array(
				 array(
						'taxonomy' => 'item_tags',
						'field'    => 'slug',
						'terms'    => $tags )
				)
			);
		}

		var_dump($args); die;



		$query->set( 'meta_query', array(
				'relation' => 'AND', $args
			)
		);
	}
	//}
	return $query;
}


