<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<footer id="site-footer" class="site-footer" role="contentinfo">
    
    
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer") ) : ?>
<?php endif;?>

	<?php // footer ?>

</footer>
