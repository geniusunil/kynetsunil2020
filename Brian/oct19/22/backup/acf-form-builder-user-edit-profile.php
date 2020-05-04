<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://support.catsplugins.com
 * @since      1.0.0
 *
 * @package    Acf_Form_Builder
 * @subpackage Acf_Form_Builder/public/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php if(isset($_SESSION['errors']) && count($_SESSION['errors'])) : ?>
<ul class="list-errors">
	<?php foreach ($_SESSION['errors'] as $key => $error) : ?>
		<li><?php echo $error; ?></li>
	<?php endforeach; unset($_SESSION['errors']); ?>
</ul>
<?php endif; ?>
<form method="POST" action="">
	<?php acf_form($options);	?>
	<button type="submit">Submit</button>
</form>