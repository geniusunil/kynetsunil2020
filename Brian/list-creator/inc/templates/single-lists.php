<?php
/* Extra code was there
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>	<?php echo get_the_title( ); ?></title>
</head>
<head>
*/
wpb_track_post_views();
get_header();

		// Start the loop.
		while ( have_posts() ) : the_post(); ?>
	<!-- 	
	<div class="td-full-screen-header-image-wrap">

        <div id="td-full-screen-header-image" class="td-image-gradient-style6">
            <img class="td-backstretch test td-animation-stack-type0-1 td-stretch-width" src="<?php the_post_thumbnail_url(); ?>" style="left: 50%; transform: translate3d(-50%, -9px, 0px) scale(1.04, 1.04); opacity: 1;">
        </div>

        <div class="td-container td-post-header">

            <div class="td-post-header-holder">
                <div class="entry-crumbs"><span><?php
				if ( function_exists('yoast_breadcrumb') ) {
				yoast_breadcrumb('
				<p id="breadcrumbs">','</p>
				');
				}
				?> </span></div>

                <header class="td-post-title">

					<h1 class="entry-title"><?php

					//	$attached_items      = get_field( 'list_items', get_the_ID(), false );
					//	echo '<span>'.count($attached_items).' </span>';

					 echo get_the_title( ); ?></h1>

					 <script type="text/javascript">
					 	document.title = "<?php echo count($attached_items); ?> <?php echo get_the_title( ); ?> of <?php the_time('Y') ?>"
					 </script>

                </header>
            </div>
        </div>

    </div>  -->

    <!-- top section -->
    <style type="text/css">
    	.top_section{
    		width: 100%;
    		min-height: 325px;
    		background-size: 100%;
    		background-repeat: no-repeat;
    		position: relative;
    		margin-bottom: 20px;
    	}
    	.top_section_overlay{
    		height: 100%; width: 100%;top:0;left:0;position:absolute;background-color: #000000;opacity: 0.85;
    	}
    	.section_content{
    		position: absolute;
    		z-index: 9;
    		width: 100%;
    		top: 0;
    		height: 100%;
    	}
    	.sec_container{
    		max-width: 1140px;
    		margin: auto;    		
    	}
    	.elementor-widget-wrap-sec{
    		max-width: 62%;    		
    	}
    	.inline_block{
    		display: inline-block;
    		text-align: left;
    	}
    	.rightPart{
    		width: 37%;
    		position: absolute;
    		bottom: 0;
    		right: 0;s
    	}
    	.disTab{
    		display: table;
    		width: 100%;
    		height: 100%;
    	}
    	.distabCell{
    		display: table-cell;
    		vertical-align: middle;
    	}
    	.ImageArea{
    		position: absolute;
    		bottom: 0;
    		z-index: 99;
    		width: 100%;
    		left: 0;
    		text-align: center;
    		padding: 10px;
    		background-color: rgba(255,255,255,0.85);
    		transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
    	}
    	.content-area .container{
    		width: auto;
    	}
    	
    	div.elementor-widget-wrap-sec.clearfix {
    margin-top: -40px;
}
        @media only screen and (max-width: 1300px){
            .sec_container {
                max-width: 90%;
                margin: auto;
            }
            .absImg img{
                max-width: 200px;
            }
        }
        @media only screen and (max-width: 980px){

            .elementor-widget-wrap-sec {
                max-width: 55%;
            }
            .elementor-widget-wrap-sec h1{
                font-size: 22px !important;
            }
            .elementor-widget-wrap-sec .elementor-text-editor span{
                font-size: 14px!important;
            }
            .absImg {
                max-width: 35%;
            }
            .rightPart {
                width: 45%;
            }

        }
         @media only screen and (max-width: 767px){
            .sec_container {
                max-width: 95%;
                margin: auto;
            }
            .elementor-widget-wrap-sec {
                max-width: 100%;
            }
            .rightPart {
               position: inherit;
               width: 100%;
                bottom: 0;
                right: 0;
            }
            .section_content {
                position: relative;
                
            }
            .rightPart{
                text-align: center;
            padding-top: 30px;
            }
            .ImageArea img{
                max-width: 100%;
            }
            .top_section{
                background-size: cover;
            }
        }
        
@media only screen and (max-width: 750px) {
  .rightPart {    
    display: none;
  }
  .top_section {    
    height: 250px;
  }
}
    </style>

    <?php

    	function custom_exc($data, $number){
    		$arr = explode(" ",$data);
    		$output = array_slice($arr, 0, $number);
    		$res = implode(" ",$output);
    		echo $res;
    	}

    ?>

    <section class="top_section" style="background-image: url(<?php $a=true; if($a){ the_post_thumbnail_url(); }else{ echo 'https://test.wpmultiverse.com/wp-content/uploads/2018/01/aa.jpg';  } ?>);">
    	<div class="top_section_overlay"></div>    	
    	<div class="section_content">
	    	<div class="disTab">
	    		<div class="distabCell">
			    	<div class="sec_container clearfix">
			    		<div class="elementor-widget-wrap-sec clearfix">
							<div data-id="7130fe7" class="elementor-element elementor-element-7130fe7 elementor-widget elementor-widget-text-editor" data-element_type="text-editor.default">
								<div class="elementor-widget-container">
									<div class="elementor-text-editor elementor-clearfix"><h1 style="font-size: 29px;  font-family: sans-serif; color: #ffffff; line-height: 33px; min-height: 33px;"><?php echo get_the_title( ); ?></h1>
									
									</div>
								</div>
							</div>
							<div data-id="4b9dad9" class="elementor-element elementor-element-4b9dad9 elementor-widget elementor-widget-text-editor" data-element_type="text-editor.default">
								<div class="elementor-widget-container">
									<div class="elementor-text-editor elementor-clearfix">
									<!-- <span style="color: #ffffff; font-family: sans-serif; font-size: 16px;">Take advantage of our expert reviews to compare the leading website hosting companies and platforms available. Check out each provider's special offers and get your website up and running, today.</span> -->
									<span style="color: #ffffff; font-family: sans-serif; font-size: 16px;"><?php custom_exc(strip_tags(get_the_excerpt()), 36); ?> ...</span>
									</div>
								</div>
							</div>
						</div>
						<div class="rightPart">
							<div class="absImg inline_block">
								<img src="https://www.softwarefindr.com/wp-content/uploads/advisor-.png">
							</div>							
							<div class="elementor-text-editor elementor-clearfix inline_block">
								<p style="margin-bottom: 0px; font-size: 14px; line-height: 18px; color: #ffffff; font-family: sans-serif;">
									<em>"I've screened every<br>software listed here,<br>which is then reviewed<br>& voted on by you!"</em>
								</p>
								<p style="margin-bottom: 0px; font-size: 14px; line-height: 18px; color: #ffffff; font-family: sans-serif;">
									<br><span style="font-weight: 600;">Brian&nbsp;Harris</span><br>Expert Advisor
								</p><br>
							<!--	<p style=" font-size: 12px;  color: #ffffff; font-family: sans-serif;"><em>PS: this all happens in <u>real-time!</u> </em></p>-->
							</div>
						</div>
			    	</div>
		    	</div>
		    </div>
    	</div>
    	<section class="ImageArea">
    		<img src="https://www.softwarefindr.com/wp-content/uploads/FEATURED-on-768x37.png">
    	</section>
    </section>

    <!-- top section end -->

						
    <div class="content-area mv-single-lists a" id="primary" >
		<div class="site-content" id="content" role="main">
			<div class="awwards" style=" float: right;margin: 10px 0 10px 10px;						">
				 <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

				<ins class="adsbygoogle gaads"  style="display:inline-block;width:336px;height:280px"  data-ad-client="ca-pub-7235010157255388" data-ad-slot="6869078844"></ins>
				<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
						    
				 <!--<img class="alignright size-medium wp-image-125711" src="https://www.softwarefindr.com/wp-content/uploads/Best-awards.png" alt="Best software chosen by business owners" width="250" height="300" />--></div>
				<?php
					remove_filter( 'the_content', 'wpautop' );
					the_content( );
					add_filter( 'the_content', 'wpautop' );
				?>
		</div>


		<?php endwhile; ?>
		<em>Was this helpful?</em>
        <div class="rating_container">


		<?php if(function_exists('the_ratings')) { the_ratings(); } ?>
            <div class="last_updated">
                <label>Last Updated:</label>
                <span><?php echo get_post_modified_time('j M Y');
                ?></span>
            </div>
        </div>

		<div class="clr"> </div>
		
			<section class="i-section">
		<div class="container">
			<div class="row section-title reveal">
				<p><?php _e( 'Discussion', 'my-listing' ) ?></p>
				<h2 class="case27-primary-text"><?php _e( 'Share your thoughts', 'my-listing' ) ?></h2>
			</div>
		</div>
				<?php comments_template('', true); ?>
	</section>
		

	</div>
<script data-cfasync="false" src="https://inndel.com/wp-content/plugins/wp-usertrack/userTrack/tracker.js"></script>
<?php

get_footer();

?>
