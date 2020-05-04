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
if(!isset($_SESSION['aff']) and $_GET['aff']){
    $cookie_expire = time()+60*60*24*30;
    $_SESSION['aff'] = $_GET['aff'];
    setcookie('aff', $_SESSION['aff'], $cookie_expire, '/', '.'.$_SERVER['HTTP_HOST']);
}

?>

