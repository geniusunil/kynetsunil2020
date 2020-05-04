<?php

class Mv_List_Single_View {

	private $affiliate_url ;
	private $affiliate_button_text;
	private $source_url ;
	private $credit_text  ;
	private $tags        ;
	private $categories  ;
	private $editor_choice ;
	private $product_model;
	private $product_type ;
	private $alternate_tag ;
	private $software ;



	function __construct() {
		add_filter( 'the_content', array( $this, 'show_list' ), 1  );
		add_action( 'wp_ajax_list_creator_post_vote', array( $this, 'update_vote' ) );
		add_action( 'wp_ajax_nopriv_list_creator_post_vote', array( $this, 'update_vote' ) );

		// shortcode
		add_shortcode( 'show_list', array( $this, 'show_list_shortcode' ) );

		// shortcode coloumn
		add_filter( 'manage_lists_posts_columns' , array( $this, 'add_shortcode_column' ) );
		add_action( 'manage_lists_posts_custom_column' , array( $this, 'shortcode_column' ), 10, 2 );

		//mentioned in
		add_action( 'comment_form_after', array( $this, 'also_mentioned_in' ) );

	}

	function show_list( $content ) {


		if ( is_singular( 'lists' ) && ! has_shortcode( $content, 'show_list' ) ) {
			global $post;
			if ( ! has_shortcode( $post->post_content, 'show_list' ) ) {
				$content = wpautop( $content );
				$content .= $this->get_list_html( get_the_ID() );
			}

		}elseif ( is_singular( 'list_items' ) ) {

			$this->set_single_list_item_vars( get_the_ID() );

			$content = $this->get_single_list_item_content( get_the_ID(), $content );

		}
		return $content;
	}

	function set_single_list_item_vars( $post_id ) {
		$this->affiliate_url = get_field( 'affiliate_url', $post_id  );
		$this->affiliate_button_text = get_field( 'affiliate_button_text', $post_id  ) == '' ?'Download/Demo': get_field( 'affiliate_button_text', $post_id  );
		$this->source_url    = get_field( 'source_url', $post_id  );
		$this->credit_text   = get_field( 'credit', $post_id );
		$this->pricing_model = get_field( 'pricing_model', $post_id );
		$this->product_type  = get_field( 'product_type', $post_id );
		$this->editor_choice = get_field( 'editor_choice', $post_id );
		$this->alternate_tag = get_field( 'alternate_tag', $post_id );
		$this->software      = get_field( 'software', $post_id );

		$catobj = get_the_terms( $post_id, 'list_categories' );
		if ( $catobj && ! is_wp_error( $catobj ) ) {
			$this->categories = $catobj;
		}

		$tagsobj = get_the_terms( $post_id, 'item_tags' );
		if ( $tagsobj && ! is_wp_error( $tagsobj ) ) {
			$this->tags = $tagsobj;
		}
	}


	function show_list_shortcode( $atts ) {
		// print_r( $atts['id'] ); die;
		return $this->get_list_html( $atts['id']  );

	}
	function get_list_html( $list_id ) {
		global $wp;
		$index =1;
		$main_list_id        = $list_id ;
		$main_list_permalink = get_the_permalink( $list_id );
		$attached_items      = get_field( 'list_items', $list_id, true );
		$promoted_list_items =  get_field( 'promoted_list_items', $list_id, true );
		$items_per_page      = get_field( 'items_per_page', $list_id );
		$voting_closed       = get_field( 'voting_closed', $list_id );
		$current_page        = get_query_var( "page" ) ? get_query_var( "page" ) : 1;
		$total_pages         = ceil( count( $attached_items )/$items_per_page );

		//$items_by_votes = '';
		foreach ( $attached_items as $key =>$child_post ) {
			$total_votes = get_post_meta( $child_post->ID, 'votes_given', true );
			if ( ! isset( $total_votes[$main_list_id] ) ) {
				$total_votes[$main_list_id] = 0;
				$attached_items[$key]->votes = 0;
			}else {
				$attached_items[$key]->votes =$total_votes[$main_list_id];
			}

		}

		$index = ( ( $current_page - 1 ) * $items_per_page )+1;

		//usort( $attached_items, array( $this, "cmp" ) );
		Mes_Lc_Ext::sort($list_id, $attached_items);
		
		$itmes_with_promoted = $attached_items;

		$temp_array=array();

		// make promoted items at top
		if ( ! empty( $promoted_list_items ) ) {
			foreach ( $attached_items as $key => $item ) {
				if ( in_array( $item->ID, $promoted_list_items ) ) {
					unset( $itmes_with_promoted[$key] );
					$item->promoted =1;
					$temp_array[]=$item;
				}

			}
		}
		$attached_items = array_merge( $temp_array, $itmes_with_promoted );
		//krsort($items_by_votes);
		$posts =  array_slice( $attached_items, ( $current_page - 1 ) * $items_per_page , $items_per_page );
	/*echo '<pre>';
		print_r($posts);
		echo '</pre>';  */

	$totalVotes = 0;
    $totalActVOtes = 0;
    $postarr = array();
    $postarrOBJ = array();
    if(!empty($posts))
    {
        foreach($posts as $key=>$list_post)
        {
            //$totalVotes += $list_post->votes;
            if ($list_post->votes > 0) {
                // $list_post->votes = $list_post->votes*2.27;
                $totalActVOtes  += $list_post->votes;
                $totalVotes += Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);


            }
        }
    }
    if(!empty($posts))
    {
        foreach($posts as $key=>$list_post)
        {
            //$totalVotes += $list_post->votes;
//            if ($list_post->votes > 0) {

                $score = Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);
                $score = ($score / $totalVotes) * 100;
                $score = number_format($score, 2);
                $postarr[$list_post->ID] = $score;
                $postarrOBJ[$list_post->ID] = $list_post;

//            }
        }
    }

  $maxsScore = array_keys($postarr, max($postarr));
  $maxsScorePost = $maxsScore[0];
   arsort($postarr);
		if ( ! empty( $posts ) ) {

			ob_start(); ?>

		<link rel="stylesheet" type="text/css" href="<?php echo plugins_url( '/css/morris.css', __DIR__ ); ?>">
		<script type="text/javascript" src="<?php echo plugins_url( '/js/morris.min.js', __DIR__ ); ?>" ></script>
		<script type="text/javascript" src="<?php echo plugins_url( '/js/raphael-min.js', __DIR__ ); ?>" ></script>


		 		 <div class="zombify-main-section-front zombify-screen" id="zombify-main-section-front">

			<div class="zf-container">
				<div class="zf-list zf-numbered" id="zf-list">
					 <div class="graph_section">
		 		 <div class="graph_section_inner">
			<!--	<div id="morrisjs_graph_header" >
					<p>**</p>
					<p>***</p>
					<div class="filter_options">
						<ul>
							<li>
								<a href="javascript:void(0)">1 Month</a>
							</li>
							<li>
								<a href="javascript:void(0)">6 Months</a>
							</li>
							<li>
								<a href="javascript:void(0)">1 Year</a>
							</li>
							<li class="active">
								<a href="javascript:void(0)">All Time</a>
							</li>
						</ul>
					</div>
				</div>-->
				<div id="morrisjs_graph" style="height: 300px;"></div>
				<div id="morrisjs_graph_data" ></div>
				</div>
				</div>
				<script type="text/javascript">
				jQuery(document).ready(function(){
				
						load_graph('<?php echo $list_id; ?>',1);
				});
				
				</script>
				
				<div class="tooltip">Scoring methodology <i class="fa fa-info-circle"></i>
  <span class="tooltiptext">The Scoring is based on our unique algorithm that looks at reviews, votes, behavior, social signals and more. <a rel="nofollow" href="/scoring-methodology/">Learn more</a></span>
</div>
<div class="votes_calculatr"></div>
			<ol>
		<?php $kk = 1;
$promoteditems  = '';
$alllistitems = '';
	foreach($postarr as $key=>$score): // variable must be called $post (IMPORTANT)
				//setup_postdata( $post );
				 $list_post = $postarrOBJ[$key];
				$list_id = $list_post->ID;

				$affiliate_url = get_field( 'affiliate_url', $list_id );
				$affiliate_button_text = get_field( 'affiliate_button_text', $list_id ) == '' ?'Download': get_field( 'affiliate_button_text', $list_id   );
				$source_url = get_field( 'source_url', $list_id );
				$credit = get_field( 'credit', $list_id );
ob_start();
?>
                              <li class=" <?php echo isset( $list_post->promoted )?'promoted-item':''; ?> zf-list_item">

											<div class="zf-list_left">
												<span class="zf-number"><?php echo $index; ?></span>
                                                <div class="" >
                                                    	
                                                    	<?php 
															if($list_id == $maxsScorePost){
																echo '<div class="popular-ribbon">
                          						                  	<span class="ribbon-content inner-ribbon">Voted the BEST</span>
                   													</div>';
															}
														
															if(get_field('editor_choice',$list_id) != ''){
																echo '<div class="popular-ribbony">
                          						                  	<span class="ribbon-contenty inner-ribbon">Editors choice</span>
                   													</div>';
																
															}
														?>
                                                        </span>
                                                    </div>
												<figure class="zf-list_media zf-image">
                                                	
						                         <a class="mes-lc-li-link" data-zf-post-parent-id="<?php echo $main_list_id; ?>" href="<?php echo get_the_permalink( $list_id ) ?>"><?php echo get_the_post_thumbnail( $list_id, 'medium' );?></a>
						                          </figure>
											</div>

											<div class="zf-list_right">

												<h2 class="zf-list_title ranked_titleeee" data-zf-post-parent-id="<?php echo $main_list_id; ?>">
													<a class="mes-lc-li-link update_list_modified_link" data-zf-post-id="<?php echo $list_id; ?>" data-zf-post-parent-id="<?php echo $main_list_id; ?>" href="<?php echo get_the_permalink( $list_id ) ?>"><?php echo get_the_title( $list_id ); ?></a>
												</h2>
                                                
                                                

												<?php $total_votes = get_post_meta( $list_id, 'votes_given', true );
												
																if ( ! isset( $total_votes[$main_list_id] ) ) {
																	$total_votes[$main_list_id] = 0;
																}

																if ( $voting_closed ) {
																	$class_prefix= 'vts_';
																}else {
																	$class_prefix= 'zf_';
																}
																if ( ! $voting_closed ) {
													?>
														<div class="zf-item-vote-box">
														<div class="zf-item-vote" data-zf-post-id="<?php echo $list_id; ?>" data-zf-post-parent-id="<?php echo $main_list_id; ?>">
															<button class="zf-vote_btn <?php echo $class_prefix.'vote_up'; ?>"><i class="zf-icon zf-icon-vote_up"></i>
															</button>
														<span class="zf-vote_count" data-zf-post-id="<?php echo $list_id; ?>" data-zf-votes="<?php echo number_format((float)$total_votes[$main_list_id],1); ?>">
															<i class="zf-icon zf-spinner-pulse"></i>
															<span class="zf-vote_number">
																<?php echo number_format((float)$total_votes[$main_list_id],1); ?>														</span>
														</span>
																<button class="zf-vote_btn <?php echo $class_prefix.'vote_down'; ?>"><i class="zf-icon zf-icon-vote_down"></i></button>
														</div>
													</div>
													<?php  } ?>

												<span style="clear: both; display: block;"></span>
												<div class="avg-rating-cont" style="float:left;width:100%;margin:10px 0"> 
                                                    <div class="editor-rating-cont">
                                                        Editor Rating
                                                    <br/>
                                                   <?php echo do_shortcode( '[rwp_reviewer_rating_stars id=0 size=20 post='.$list_id.']' ); ?>
                                        
                                                    </div>
                                                    <div class="user-rating-cont">
                                                        User's Rating
                                                        <br/>
                                                        <?php
                                                        echo do_shortcode( '[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=20 post='.$list_id.']' );
                                        				?>                                    
                                                    </div>
                                                    <div class="clr"></div>
                                                </div>
												<div class="zf-list_description"><?php echo substr( $list_post->post_content, 0, 200 ); ?> ....
												<a class="mes-lc-li-link update_list_modified_link" data-zf-post-id="<?php echo $list_id; ?>" data-zf-post-parent-id="<?php echo $main_list_id; ?>" href="<?php echo get_the_permalink( $list_id ); ?>" rel="nofollow">Read full review</a>
												<div class="lists-btm-link">
														<?php if ( ! empty( $source_url ) ) { ?>
												<a class="zf-buy-button  btn btn-primary " href="<?php echo $source_url ?>" rel="nofollow" target="_blank"><?php echo $credit ?></a>
													<?php } ?>
												<?php if ( ! empty( $affiliate_url ) ) { ?>
												<a class="mes-lc-li-down btn-affiliate zf-buy-button btn btn-primary" href="<?php echo $affiliate_url; ?>" rel="nofollow" target="_blank"><i class="zf-icon zf-icon-buy_now"></i><?php echo $affiliate_button_text ?></a>
												<?php } ?>


												</div>
												<div class="clr"></div>

												</div>


											</div>
											<?php echo isset( $list_post->promoted )?'<div class="featured-item">Featured</div><div class="clr"></div>':''; ?>
										</li>
										<?php
										 $conval = ob_get_clean();
										
										 if(isset( $list_post->promoted )){
										   $promoteditems .=  $conval;
										 } else{
										     $alllistitems .=  $conval;
										 }
										 $index++; ?>
									<?php endforeach;
									echo $promoteditems;
									echo $alllistitems;
									?>
                                </ol>
				</div>
				</div>
				</div>
<div class="top-list_item_table">
<?php

if(!empty($postarr) && count($postarr) > 0){
    $index = 1;
    echo "<div class='list-tab-heading'>".get_the_title()." Summery</div>
<div class='list-tab-container'><table class='list-tab'><thead><th>Name</th><th>Editor's Rating</th><th>Pricing Model</th></thead>";
    foreach ($postarr as $key1=>$score): // variable must be called $post (IMPORTANT)
    if($index > 5){
        break;
    }
    $main_list_idn = get_the_ID();
				//setup_postdata( $post );
				 $list_postii = $postarrOBJ[$key1];
				$list_idn = $list_postii->ID;
                $pricing_model = get_field( 'pricing_model', $list_idn );
				$score = RWP_API::get_review( $list_idn);
				echo "<tr><td><a class='update_list_modified_link' data-zf-post-id='$list_idn' data-zf-post-parent-id='$main_list_idn' href='".get_permalink($list_idn)."'>".get_the_title($list_idn)."</a> </td><td>".round($score['review_overall_score'],1)."/5</td><td>".str_replace( '_', ' ', implode( ', ', array_map( 'ucfirst', $pricing_model ) ) )."</td></tr>";
				$index++;
    endforeach;
    echo "</table></div>";
}

?>
</div>
					<div id="zombify-main-section" class="zombify-main-section zf-front-submission zombify-screen zf-screen-lg">
						<div class="zf-upload-content">
							<div class="zf-head">
								<a rel="nofollow" href="/get-listed/" id="add_your_content"><i class="zf-icon zf-icon-add"></i> Something missing? add it here...</a>
							</div>

						</div>

					</div>

				<div class="page-nav page-nav-post td-pb-padding-side">
				<?php
			if ( $current_page != 1 ) {
				echo '<a href="'.$main_list_permalink.( $current_page-1 ).'/"><div><i class="td-icon-menu-left"></i></div></a>';
			}
			if ( $total_pages>1 ) {
				for ( $i=1; $i <= $total_pages ; $i++ ) {
					if ( $current_page == $i ) {
						echo '<span class="current-nav-item">'.$i.'</span>';
					}else {
						echo '<a href="'.$main_list_permalink.$i.'/" ><div>'.$i.'</div></a>';
					}
				}
			}
			if ( $current_page != $total_pages ) {
				echo '<a class="next" href="'.$main_list_permalink.( $current_page+1 ).'/"><div><i class="td-icon-menu-right"></i></div></a>';
			}
			echo '</div>';
			$html = ob_get_contents();
			ob_end_clean();
			wp_reset_postdata();

			//var_dump($current_page+1 ); die;

		}
		return $html;

	}

	function update_vote() {
		$post_id = $_POST['post_id'];
		$post_parent_id = $_POST['post_parent_id'];
		$vote_type = $_POST['vote_type'];


		if ( isset( $_COOKIE["list_creator_post_vote_".(int)$_POST["post_id"]] ) ) {

			$voted = 1;
			$voted_type = $_COOKIE["list_creator_post_vote_".(int)$_POST["post_id"]];

		}

		$total_votes = get_post_meta( $post_id, 'votes_given', true );
		if ( ! isset( $total_votes[$post_parent_id] ) ) {
			$total_votes[$post_parent_id] = 0;
		}
		$votes_count = $total_votes[$post_parent_id];


		if ( $voted == 1 ) {

			if ( $_POST["vote_type"] == 'up' ) {

				if ( $voted_type == 'up' ) {
					$votes_count--;

					setcookie( "list_creator_post_vote_".(int)$_POST["post_id"], "up", time()-60*60*24, '/' );
				} else {
					$votes_count+=2;

					setcookie( "list_creator_post_vote_".(int)$_POST["post_id"], "up", time()+60*60*24, '/' );
				}

			} else {

				if ( $voted_type == 'down' ) {
					$votes_count++;

					setcookie( "list_creator_post_vote_".(int)$_POST["post_id"], "down", time()-60*60*24, '/' );
				} else {
					$votes_count-=2;

					setcookie( "list_creator_post_vote_".(int)$_POST["post_id"], "down", time()+60*60*24, '/' );
				}

			}

		} else {

			if ( $_POST["vote_type"] == 'up' ) {
				$votes_count++;
				setcookie( "list_creator_post_vote_".(int)$_POST["post_id"], "up", time()+60*60*24, '/' );
			} else {
				$votes_count--;
				setcookie( "list_creator_post_vote_".(int)$_POST["post_id"], "down", time()+60*60*24, '/' );
			}

		}
		$total_votes[$post_parent_id] = $votes_count;
		update_post_meta( $post_id, 'votes_given', $total_votes );

		echo wp_json_encode( array( 'post_id'=>
				$post_id, 'votes'=>$votes_count ) );
		die;
	}

	function cmp( $a, $b ) {
		return $a->votes <= $b->votes;
		//  return strcmp($b->votes,$a->votes);
	}
function acme_post_exists( $id ) {
  return is_string( get_post_status( $id ) );
}

	function also_mentioned_in() {
		if ( is_singular( 'list_items' ) ) {
			$lists =  get_field( 'add_to_list', get_the_ID(), false );
            $itemiid = get_the_ID();
			if ( !empty ( $lists ) && is_array( $lists ) ) {
			    $listrankord = array();
			    foreach ( $lists as $id ) {
			        if($this->acme_post_exists($id)){
					$post_id = $id;
					$rank = get_item_rank($id,$itemiid);
					$listrankord[$id] = $rank;
					}
					}
					asort($listrankord);
				echo '<h2>'.get_the_title().' is Featured In:</h2>';
				foreach ( $listrankord as $id => $rank ) {
					$post_id = $id;
					$rankhtm = '';
					if($rank > 0){
					    $rankhtm = '<span class="rank-ts"><label>Rank:</label><span class="rr-val">'.$rank.'</span> </span>';
					}
					echo '<div class="zombify_mentioned_in">
					<h4><a href="'.get_the_permalink( $post_id ).'">'.do_shortcode(get_the_title( $post_id )
					).'</a></h4>
					<a href="'.get_the_permalink( $post_id ).'">
					'.$rankhtm.get_the_post_thumbnail( $post_id, array( '230', '200' ) ).'
					</a>
					</div>';
				}
			}
		}
	}

	function get_alternate_items() {
		$review_id= get_the_ID();
		$html ='';
		$alternate_tags = get_field( 'alternate_tag', $review_id  );

		if ( !empty( $alternate_tags ) ) {
			if ( is_singular( 'list_items' ) ) {
				$lists = get_posts( array(
						'post_type' => 'list_items',
						'post__not_in'=> array( $review_id ),
						'posts_per_page'=>'3',
						'meta_query' => array(
							array(
								'key' => 'alternate_tag', // name of custom field
								'value' => $alternate_tags, // matches exaclty "123", not just 123. This prevents a match for "1234"
								'compare' => 'LIKE'
							)
						)
					) );

				// echo '<pre>';
				// var_dump($lists);
				if ( !empty ( $lists ) ) {
					$html ='<div class="alternate-carousel"><h2>Alternatives to '.get_the_title( $review_id ).'</h2>';
					$html .='<div class="see-more-btn" ><a href="'.get_the_permalink( $review_id ).'alternative" >See More</a></div> <div class="clr"></div>';

					foreach ( $lists as $post ) {
						$post_id = $post->ID;
						$html .='<div class="zombify_mentioned_in">
					<h4><a href="'.get_the_permalink( $post_id ).'">'.$post->post_title.'</a></h4>
					<a href="'.get_the_permalink( $post_id ).'">
					'.get_the_post_thumbnail( $post_id, array( '230', '200' ) ).'
					</a>
					</div>';
					}
					$html .='</div>';
				}
			}
		}

		return $html;
	}

	function add_shortcode_column( $columns ) {
		// unset($columns['author']);
		return array_merge( $columns,
			array( 'shortcode' => __( 'Shortcode' ) ) );
	}



	function shortcode_column( $column, $post_id ) {
		switch ( $column ) {
		case 'shortcode':
			echo "[show_list id=$post_id]";
			break;
		}

	}

	function get_single_list_item_header( $post_id ) {
		$affiliate_url = $this->affiliate_url;
		$affiliate_button_text = $this->affiliate_button_text;
		$source_url = $this->source_url;
		$credit_text = $this->credit_text;
		$pricing_model = $this->pricing_model;
		$img = get_the_post_thumbnail( $post_id, 'medium' );
		ob_start();
?>

		<div class="item-header-cnt" 
<?php
		if(isset($_GET['listid']) && !empty($_GET['listid'])) {
			$list_id = $_GET['listid'];
			echo "data-zf-post-id=\"$post_id\" data-zf-post-parent-id=\"$list_id\" ";
		}
?>		
		>
		<div class="item-thumb"><?php echo $img; ?></div>
		<div class="title-rating-cont">
		<h1 class="entry-title1"><?php echo get_the_title( $post_id ); ?></h1>
		<div class="avg-rating-cont">
			<div class="editor-rating-cont">
			 	Editor Rating
			<br/>
			<?php   //$users_rating = RWP_API::get_reviews_box_users_rating($post_id,-1);
			//echo do_shortcode( '[rwp-users-rating-stars  id=0 size=20 post='.$post_id.']' );
		?><?php echo do_shortcode( '[rwp_reviewer_rating_stars id=0 size=20 post='.$post_id.']' ); ?>

			</div>
			<div class="user-rating-cont">
				User's Rating
				<br/>
				<?php
				echo do_shortcode( '[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=20 post='.$post_id.']' );
				// $user_score = get_post_meta( $post_id, 'user_rating_custom', true );
				// if ( $user_score ) {
				// 	$user_score = round( $user_score );
				// }else {
				// 	$user_score = 0;
				// }
				// $obj = new RWP_Rating_Stars_Shortcode();
				// echo $obj->get_stars( $user_score, '20', '5' ) ;
?>


			</div>
			<div class="clr"></div>
		</div>

		<div>Pricing Model: <?php echo str_replace( '_', ' ', implode( ', ', array_map( 'ucfirst', $pricing_model ) ) ); ?></div>
		</div>
		<div class="item-links">
		<?php
		$afflink = empty( $affiliate_url )? $source_url:$affiliate_url;
		$btntext = empty( $affiliate_button_text )? $credit_text:$affiliate_button_text;
?>

		<a class="mes-lc-li-down aff-link" href="<?php echo $afflink; ?>" rel="nofollow" target="_blank"><?php echo $btntext; ?></a>
		<a id="go-to-user-comments" class="custom-link" href="javascript:void(0)" rel="nofollow" target="_blank">Write a Review</a>
		</div>
		<div class="clr"></div>
		</div>
<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}


	function get_single_list_item_content( $post_id, $content ) {
		$affiliate_url = $this->affiliate_url;
		$affiliate_button_text = $this->affiliate_button_text;
		$source_url =$this->source_url;
		$credit = $this->credit_text;
		$details = $this->get_single_list_item_header( $post_id );
		$details.='<div class="list-main-content">';
		$details.=$this->get_product_details();


		$content=$details.$content;
		if ( ! empty( $affiliate_url ) ) {
			$content.='<div style="display: block;text-align:center;"><a class="mes-lc-li-down zf-buy-button rev1" href="'.$affiliate_url.'" rel="nofollow" target="_blank"><button>'.$affiliate_button_text.'</button></a></div>';
		}
		if ( ! empty( $source_url ) ) {
			$content.='<div style="display: block;text-align:center;margin-top:20px;"><a class="zf-buy-button rev2" href="'.$source_url.'" rel="nofollow" target="_blank"><button>'.$credit.'</button></a></div>';
		}
		//$content.='<div style="clear:both"></div>';
		$content.= $this->get_alternate_items();

		$content.='</div>';

		return $content;
	}

	function get_product_details() {
		$affiliate_url = $this->affiliate_url;
		$affiliate_button_text = $this->affiliate_button_text;
		$source_url    = $this->source_url;
		$credit        = $this->credit_text;
		$pricing_model = $this->pricing_model;
		$product_type  = $this->product_type;
		$editor_choice = $this->editor_choice;
		$software      = $this->software;
		$categories    = $this->categories;
		$tags          = $this->tags;
		$category_names ='';
		$tag_names = '';
		if ( $categories ) {
			foreach ( $categories as $cat ) {
				$category_names.='<span class="round-tags"><a href="'.esc_url( get_term_link( $cat->term_id ) ).'" title="'.$cat->name.'">'.$cat->name.'</a></span>&nbsp;';
			}
		}
		if ( $tags ) {
			foreach ( $tags as $tag ) {
				$tag_names.='<span class="round-tags"><a href="'.esc_url( get_term_link( $tag->term_id ) ).'" title="'.$tag->name.'">'.$tag->name.'</a></span>&nbsp;';
			}
		}
		$html='<div class="list-product-details">';
		$html.='<h3>Product Details</h3>';
		$html.='<ul class="product-details">';
		// if ( $pricing_model ) {
		//  $html.='<li><span class="pro-dtl-heading">Pricing Model</span>: '.str_replace( '_', ' ', implode( ', ', array_map( 'ucfirst', $pricing_model ) ) ).'</li>';
		// }
		if ( $product_type ) {
			$html.='<li><span class="pro-dtl-heading">Product Type</span>: '.str_replace( '_', ' ', implode( ', ', array_map( 'ucfirst', $product_type ) ) ).'</li>';
		}
		if ( $software ) {
			$html.='<li><span class="pro-dtl-heading">Software</span>: '.str_replace( '_', ' ', implode( ', ', array_map( 'ucfirst', $software ) ) ).'</li>';
		}
		if ( $editor_choice ) {
			$html.='<li><span class="pro-dtl-heading">Editor Choice</span>: Yes</li>';
		}
		if ( $categories ) {
			$html.='<li><span class="pro-dtl-heading">Category</span>: '.$category_names.'</li>';
		}

		if ( $tags ) {
			$html.='<li><span class="pro-dtl-heading">Tags</span>: '. $tag_names.'</li>';
		}
		$html.='</ul>';

	//	if ( $affiliate_url ) {
	//		$html.='<a class="mes-lc-li-down zf-buy-button rev1" href="' .$affiliate_url.'" rel="nofollow" target="_blank"><button>'.$affiliate_button_text.'</button></a>';
	//	}
		
		if ( $affiliate_url ) {
			$html.='<a rel="nofollow" class="typeform-share button" href="https://brian431.typeform.com/to/PJ7glN" data-mode="popup" style="display:inline-block;text-decoration:none;background-color:#939393;color:white;cursor:pointer;font-family:Helvetica,Arial,sans-serif;font-size:14px;line-height:35px;text-align:center;margin:0;height:35px;padding:0px 23px;border-radius:4px;max-width:100%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:bold;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;" target="_blank">Ask a question </a> <script> (function() { var qs,js,q,s,d=document, gi=d.getElementById, ce=d.createElement, gt=d.getElementsByTagName, id="typef_orm_share", b="https://embed.typeform.com/"; if(!gi.call(d,id)){ js=ce.call(d,"script"); js.id=id; js.src=b+"embed.js"; q=gt.call(d,"script")[0]; q.parentNode.insertBefore(js,q) } })() </script>';
		}		
		if ( $source_url ) {
			$html.='<a class="zf-buy-button rev2" href="'.$source_url.'" rel="nofollow" target="_blank"><button>'.$credit.'</button></a>';
		}

		$html.='</div>';

		return $html;
	}



}

