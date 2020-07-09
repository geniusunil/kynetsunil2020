<?php
	wpb_track_post_views();
	get_header();


	$list_setting = get_option( 'mv_list_items_settings' );
	$target_countries = $list_setting['list_page_target_countries'];
	$countryPair = explode(',',$target_countries);
	
	foreach($countryPair as $cp)
	{
		$pair = explode('=>',$cp);
		foreach($pair as $key=>$value)
		{
			$pair[$key]=trim(trim($value),"'");
		}
		$targetCountries[$pair[0]] = $pair[1];
	}
	
	while (have_posts())
	{
		the_post();
		$list_id = $post_id = get_the_ID();
		$main_list_id = $list_id;
		$dp = get_data_points_list($post_id);
		$main_list_permalink = get_the_permalink($list_id);
		$finalVotes = do_shortcode("[total_votes id=$main_list_id]");
		add_datapoints_databse($main_list_id,'list',$dp,$finalVotes);
		$catobj = get_the_terms($post_id, 'list_categories');
		$category_names = '';
		
		if ($catobj && !is_wp_error($catobj)) 
		{
			$categories = $catobj;
			if ($categories) 
			{
				foreach ($categories as $cat) 
				{
					$category_names .= '<span class="list-cat-name"><a href="' . esc_url(get_term_link($cat->term_id)) . '" title="' . $cat->name . '">' . $cat->name . '</a></span>&nbsp;';
					$_SESSION['category_faq'] = $cat->name;
					file_put_contents("single_list.txt", $category_names,FILE_APPEND);
				}
			}
		}
		
		$langPos = strrpos($_SERVER['REQUEST_URI'],"?lang=");
		if($langPos == false)
		{
			$isoCode = do_shortcode('[geoip_detect2 property="country.isoCode"]');
			$country = do_shortcode('[geoip_detect2 property="country.name"]');
		}
		else
		{
			//get it from the url
			$isoCode = substr($_SERVER['REQUEST_URI'],$langPos+6,2);
			$country = get_country_name($isoCode);
		}
			
		$title = get_the_title();
		$origTitle = $title;
		$_SESSION['lang']="none";
		?>
		<script>
			var isoCode = "none";
		</script>
		<?php
		if(array_key_exists($isoCode,$targetCountries))
		{
			$title .=  " in $country";
			$_SESSION['lang']=$isoCode;
			?>
			<script>
				isoCode = '<?php echo $isoCode?>';
				//get existing args from url
				// console.log("current url from footer : "+window.location.href);
				var url = window.location.href;
				var queryStart = url.indexOf("?") + 1,
				queryEnd   = url.indexOf("#") + 1 || url.length + 1,
				query = url.slice(queryStart, queryEnd - 1),
				pairs = query.replace(/\+/g, " ").split("&"),
				parms = {}, i, n, v, nv;
				if (query === url || query === "")
				{
					parms = null;
				}
				else
				{
					for (i = 0; i < pairs.length; i++)
					{
						nv = pairs[i].split("=", 2);
						n = decodeURIComponent(nv[0]);
						v = decodeURIComponent(nv[1]);

						if (!parms.hasOwnProperty(n)) parms[n] = [];
						parms[n].push(nv.length === 2 ? v : null);
					}
				}  
				var pushurl = "?";
				if(parms != null)
				{
					pushurl += "sort=" + parms.sort[0] +"&lang=<?php echo $isoCode ?>#filter";
				}
				else
				{
					pushurl="?lang=<?php echo $isoCode ?>";
				}
				history.replaceState({name: 'Location'}, "pushState example", pushurl);
				document.title = "<?php echo $title ?> | SoftwareFindr";
				jQuery('meta[property="og:title"]').attr("content","<?php echo $title ?> | SoftwareFindr");
			</script>
			<?php
		} ?>
		<div class="single-list-data zf-item-vote" >
			<div class="container-fluid mainslider">
				<div class="container">
					<div class="row" style="overflow: visible;">
						<div class="col-md-12">
							<p class="slidersubtop"><?php echo $category_names; ?></p>
							<h1 class="sliderheader"><?php echo $title; ?></h1>
							<p class="slidertext">
								A comprehensive list of <?php the_title();?> according to <?php echo $finalVotes; ?> users.
								<br>
								With <?php echo do_shortcode('[list_number]'); ?> options to consider you are sure to find the right one for you.
							</p>
						</div>
						<div class="col-md-12">
							<ul class="contributers">
								<li>
									<img class="reuse" src="<?php echo image_path_single('images/reuseicon.jpg'); ?>">
								</li>
								<li class="bold">
									<?php echo get_post_modified_time('j F Y'); ?>
								</li>
								<li class="sep">
									/
								</li>
								<li class="contimage">
									<img src="<?php echo image_path_single('images/contributers.jpg'); ?>">
								</li>
								<li class="contnumbs">
									<?php echo $finalVotes; ?> Contributors
								</li>
								<li class="region-label">
									Select Country
								</li>
								<li class="region">
									<div class="options" data-input-name="country"></div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<?php 
			remove_filter('the_content', 'wpautop');
			the_content();
			add_filter('the_content', 'wpautop');
			?>
		</div>
		</div>
		<!-- FAQ End  -->
		<div class="container-fluid sectionexplore" id="yarrp_container" data-id="<?php echo $main_list_id; ?>" >
			<div class="full_width" data-id="<?php echo $main_list_id; ?>">      
				<?php yarpp_related(array('post_type' => 'lists', 'p' => $main_list_id), $main_list_id);?>
				<div class="container">
					<div class="row justify-content-md-center">
						<div class="col-md-6">
							<div class="fbcomments">
								<div id="social_fb_comments" class="link">
									<button class="toggle">
										<i class="fa fa-commenting-o" aria-hidden="true"></i>
										<a href="#" class="fb-comments-text txt crux-label-style">Questions & answers</a>
										<i class="fa fa-chevron-up" aria-hidden="true"></i>
									</button>
									<div class="fb-comments fb_iframe_widget fb_iframe_widget_fluid">
										<?php comments_template('/commentslist.php', true);?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	
	function get_country_name($isoCode)
	{
		global $targetCountries;
		$country = '';
		$country = $targetCountries[$isoCode];
		// echo "targetCountries[isoCode] $targetCountries[$isoCode]";
		return $country;
	}
	get_footer();
	?>
	<script>
		jQuery('.options').flagStrap({
			countries: <?php echo json_encode($targetCountries) ?>,
			buttonSize: "btn-sm",
			buttonType: "btn-info",
			labelMargin: "10px",
			scrollable: false,
			scrollableHeight: "350px"
		});
		
		var allOptions=jQuery('.options a');
		// console.log("alloptions");
		// console.log(allOptions.length);
		for(var j=0;j<allOptions.length;j++){
			var current = allOptions[j];
			var newC=current.getAttribute("data-val");
			// var newCname=current.text();    
			
			//get existing args from url

				// console.log("current url from footer : "+window.location.href);
				var url = window.location.href;
				var queryStart = url.indexOf("?") + 1,
			queryEnd   = url.indexOf("#") + 1 || url.length + 1,
			query = url.slice(queryStart, queryEnd - 1),
			pairs = query.replace(/\+/g, " ").split("&"),
			parms = {}, i, n, v, nv;

				if (query === url || query === ""){
					parms = null;
				}
			
				else{
					for (i = 0; i < pairs.length; i++) {
						nv = pairs[i].split("=", 2);
						n = decodeURIComponent(nv[0]);
						v = decodeURIComponent(nv[1]);

						if (!parms.hasOwnProperty(n)) parms[n] = [];
						parms[n].push(nv.length === 2 ? v : null);
					}
				}
			// end of get  existing args from url 
					// history.replaceState(null, null, "?lang=<?php //echo $isoCode ?>/");
					var pushurl = "?";
					if(parms.sort != null){
						pushurl += "sort=" + parms.sort[0] +"&lang="+newC;
					}
					else{
						pushurl="?lang="+newC;
					}
			
			// var url = window.location.href;
			// var current = allOptions[i];
			var baseurl = url.substr(0,url.indexOf("?"));
			if(newC != ''){
				current.setAttribute("href",baseurl+pushurl);
			}
			current.style.textDecoration = "none";
			// console.log(allOptions[i]);
		}
		jQuery('.options a').on('click', function() {
		  
			window.open(jQuery(this).attr("href"),"_self");
			
		});

	</script>
	<style>
		.status{
			text-align:center;
		}
		.col-2_4{
			width:20%;
			padding:0 10px;
		}
	</style>

