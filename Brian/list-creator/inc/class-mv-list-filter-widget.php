<?php
class Mv_List_Filter_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'list_filter_widget',
			'description' => 'List Filter Widget',
		);
		parent::__construct( 'list_filter_widget', 'List Filter Widget', $widget_ops );
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
		<h3>Rating</h3>
		<ul>
			<li><input  <?php if(isset($_GET['rating'])){ checked( $_GET['rating'], '4', 1 ); } ?> type="radio" name="rating" value="4">
			<label class="filter_stars_filled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_filled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_filled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_filled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_unfilled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>4 & up</li>
			<li><input type="radio" <?php if(isset($_GET['rating'])){ checked( $_GET['rating'], '3', 1 ); } ?> name="rating" value="3">
			<label class="filter_stars_filled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_filled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_filled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_unfilled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_unfilled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>3 & up </li>
			<li><input type="radio" <?php if(isset($_GET['rating'])){  checked( $_GET['rating'], '2', 1 ); } ?> name="rating" value="2">
			<label class="filter_stars_filled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_filled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_unfilled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_unfilled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_unfilled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>2 & up </li>
			<li><input type="radio" <?php if(isset($_GET['rating'])){  checked( $_GET['rating'], '1', 1 ); } ?> name="rating" value="1">
			<label class="filter_stars_filled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_unfilled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_unfilled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_unfilled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>
			<label class="filter_stars_unfilled" style="background-image: url(<?php echo home_url(); ?>/wp-content/plugins/reviewer/public/assets/images/rating-star.png);"  ></label>1 & up </li>

		</ul>
		<h3>Pricing model</h3>
		<?php
		$pricing_model= array();
		if ( isset( $_GET['pricing_model'] ) && ! empty( $_GET['pricing_model'] ) ) {
			$pricing_model = explode( ',', sanitize_text_field( $_GET['pricing_model'] ) );
		}
?>
		<ul>
			<li><input <?php echo  in_array( 'free-trail', $pricing_model )?'checked="checked"':''; ?> type="checkbox" name="pricing_model" value="free-trail"> Free Trial</li>
			<li><input <?php echo  in_array( 'freemium', $pricing_model )?'checked="checked"':''; ?> type="checkbox" name="pricing_model" value="freemium"> Freemium</li>
			<li><input <?php echo  in_array( 'one-time-license', $pricing_model )?'checked="checked"':''; ?> type="checkbox" name="pricing_model" value="one-time-license"> One-time License</li>
			<li><input <?php echo  in_array( 'open-source', $pricing_model )?'checked="checked"':''; ?> type="checkbox" name="pricing_model" value="open-source"> Open-source</li>
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
			<li><input <?php if(isset($_GET['editor_choice'])){ checked( $_GET['editor_choice'], 'editor_choice', 1 ); } ?> type="checkbox" name="editor_choice" value="editor_choice"></li>
		</ul>

		<h3> Tags</h3>
		<ul>
		<?php
		$selected_tags_list = '';
		$selected_tags_list_array =array();
		if ( isset( $_GET['tags'] ) && ! empty( $_GET['tags'] ) ) {
			$selected_tags_list = $_GET['tags'] ;
			$selected_tags_list_array= explode( ',', sanitize_text_field( $_GET['tags']  ) );
		}
?>
			<li><input  type="text" name="tags" id="tags" value="<?php echo $selected_tags_list; ?>">
			<div class="filter-tag-list">
					<?php
		$terms = get_terms( array(
				'taxonomy' => 'item_tags',
				'hide_empty' => true,
			) );
		$tags =array();

		if ( $terms ) {
			foreach ( $terms as  $term ) {
				if ( !in_array( $term->slug, $selected_tags_list_array ) ) {
					echo "<span class='tag_item'>$term->slug</span>";
				}
			}

		}
?>

			</div>

			</li>
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


add_filter( 'pre_get_posts', 'multiverse_filter_lists' );
function multiverse_filter_lists( $query ) {
// 	if( $query->is_main_query()){
// 	echo '<pre>';
// 	print_r($query);
// 	var_dump(is_tax( 'list_categories' )); die;
// }
	if ( ! is_admin()  && $query->is_main_query() && ( is_post_type_archive( 'list_items' )|| is_tax( 'list_categories' )|| is_tax( 'list_items' ) ) ) {

		$args = array();

		if ( isset( $_GET['pricing_model'] ) && ! empty( $_GET['pricing_model'] ) ) {
			$pricing_model = explode( ',', sanitize_text_field( $_GET['pricing_model'] ) );
			if ( count( $pricing_model ) >1 ) {
				$temp_array =array();
				$temp_array['relation'] = 'AND';
				foreach ( $pricing_model as $model ) {
					$temp_array[]=array(
						'key' => 'pricing_model',
						'value' => '"'.$model.'"',
						'compare' => 'LIKE'
					);
				}
				$args[] =$temp_array;
			}else {
				$args[] =array(
					'key' => 'pricing_model',
					'value' => '"'.$pricing_model[0].'"',
					'compare' => 'LIKE'
				);
			}


		}
		if ( isset( $_GET['product_type'] ) && ! empty( $_GET['product_type'] ) ) {
			$product_type = explode( ',', sanitize_text_field( $_GET['product_type'] ) );
			if ( count( $product_type ) >1 ) {
				$temp_array =array();
				$temp_array['relation'] = 'AND';
				foreach ( $product_type as $model ) {
					$temp_array[]=array(
						'key' => 'product_type',
						'value' => '"'.$model.'"',
						'compare' => 'LIKE'
					);
				}
				$args[] =$temp_array;
			}else {
				$args[] =array(
					'key' => 'product_type',
					'value' => '"'.$product_type[0].'"',
					'compare' => 'LIKE'
				);
			}
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


		if ( !empty( $args ) ) {

			 // echo '<pre>';
			// //$val = get_post_meta( 479, 'pricing_model', true );
			// $val = get_field( 'pricing_model', 479 );
			// print_r( $val );
			// print_r( $args ); die;

			$query->set( 'meta_query', array(
					'relation' => 'AND', $args
				)
			);
		}
	}
	//}
	return $query;
}


function set_promoted_items_cat_tag( $query ) {

	// do not modify queries in the admin
	if ( is_admin() || ! $query->is_main_query()  ) {

		return $query;

	}

	if ( is_tax( 'list_categories' ) ) {
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', 'promoted_on_category' );
		$query->set( 'order', 'DESC' );

	} else if ( is_tax( 'item_tags' ) ) {
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_key', 'promoted_on_tags' );
			$query->set( 'order', 'DESC' );
		}


	// return
	return $query;

}

add_action( 'pre_get_posts', 'set_promoted_items_cat_tag' );
