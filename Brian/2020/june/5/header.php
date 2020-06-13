<?php acf_form_head();
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>


<?php


if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
	get_template_part( 'template-parts/header' );
}

/*SAVE $_GET['aff'] TO COOKIE*/
if(!isset($_SESSION['aff']) and isset($_GET['aff'])){
    $cookie_expire = time()+60*60*24*30;
    $_SESSION['aff'] = $_GET['aff'];
    setcookie('aff', $_SESSION['aff'], $cookie_expire, '/', '.'.$_SERVER['HTTP_HOST']);
}

?>

<?php
$item_name = get_query_var( 'item_name' );

	$current_url = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$url = chop($current_url,"coupon/");
	
if ( function_exists('yoast_breadcrumb') ) {
	?>

		<?php

//	echo "»".$item_name;
}


?>