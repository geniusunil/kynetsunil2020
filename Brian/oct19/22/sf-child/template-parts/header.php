<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<header id="site-header" class="site-header" role="banner">


	<div id="logo">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("SFheader") ) : ?>
<?php endif;?>
	</div>

	<nav id="top-menu" role="navigation">
		<?php // top menu ?>
	</nav> 
	


</header>
