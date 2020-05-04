<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html><head>
	<!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Cache-control" content="no-cache">
	<meta http-equiv="Expires" content="0"> -->

	<title><?php echo $title ?></title>
     <link rel='shortcut icon' href='<?php echo base_url(); ?>images/favicon_r4p_icon.ico' type='image/x-icon' />
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-ui.min.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/daterangepicker.css" />
	
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-multiselect.css" type="text/css"/> 
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/editor/jquery-te-1.4.0.css" type="text/css"/> 
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/awesome-bootstrap-checkbox.css" type="text/css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.datetimepicker.min.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.css" rel="stylesheet">
    
  	<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-ui.css"/>
  	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery/jquery.min.js" ></script>
	<script>
		/*** Handle jQuery plugin naming conflict between jQuery UI and Bootstrap ***/
		$.widget.bridge('uibutton', $.ui.button);
		$.widget.bridge('uitooltip', $.ui.tooltip);
	</script>
	<?php /*<script type="text/javascript" src="<?php echo base_url(); ?>js/tether.min.js" ></script> */?>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap.min.js" ></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/moment.min.js"></script>         
	<script type="text/javascript" src="<?php echo base_url(); ?>js/daterangepicker.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/editor/jquery-te-1.4.0.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/currency_converter.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/timezones.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/typeahead.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/summernote.js"></script>
	<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.js"></script>-->
	<script type="text/javascript" src="<?php echo base_url(); ?>js/angular.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/crm-app.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>/js/create_form.js"></script>
      <script type="text/javascript" src="<?php echo base_url();?>/js/countries.js"></script>
	<!--editor css-->
	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">-->
	
<link href="<?php echo base_url(); ?>css/editor/editor.css" type="text/css" rel="stylesheet"/>
	<?php /* <link href="<?php echo base_url(); ?>css/jquery.datetimepicker.min.css" type="text/css" rel="stylesheet"/> */ ?>
	
	<script type="text/javascript">
		$(function () {
		  	$('[data-toggle="tooltip"]').tooltip()
		  	$('body').tooltip({
			    selector: '[data-toggle="tooltip"]'
			});
		})
	</script>
	
</head>
<body style="background:#FFF !important;">
<style>

</style>
	<div class="container main">
		<?php if($this->session->flashdata('access_denied')){
			echo $this->session->flashdata('access_denied');
		} ?>
		<div id="overlay">
			<div class="please-wait text-center"><img src="<?php echo base_url();?>images/loading_spinner.gif" width="60"><br>Please wait..</div>
		</div>