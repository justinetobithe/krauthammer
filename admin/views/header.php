<?php
$style_version = isset($style_version) ? $style_version : md5(0);
if (!isset($_SESSION['system'])) {
	$_SESSION['system'] = array();
}

if (!isset($_SESSION['system']['website_name'])) {
	$_SESSION['system']['website_name'] = get_system_option('website_name');
	$_SESSION['system']['website_name'] = $_SESSION['system']['website_name'] == '' ? "CMS" : $_SESSION['system']['website_name'];
}

$_website_name = $_SESSION['system']['website_name'];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<script type="text/javascript" src="<?php echo URL;?>/config_js.php"></script>
		<meta charset="utf-8" />
		<title><?php echo get_module_title() ?></title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!--basic styles-->

		<link href="<?php echo URL; ?>assets/css/bootstrap.min.css" rel="stylesheet" />
		<link href="<?php echo URL; ?>assets/css/bootstrap-responsive.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/font-awesome.min.css" />

		<!--[if IE 7]>
		  <link rel="stylesheet" href="<?php echo URL; ?>assets/css/font-awesome-ie7.min.css" />
		<![endif]-->

		<!--page specific plugin styles-->
    <link rel="stylesheet" href="<?php echo URL; ?>assets/css/jquery-ui-1.10.3.full.min.css" />
    <link rel="stylesheet" href="<?php echo URL; ?>assets/css/colorpicker.css" />
    <link rel="stylesheet" href="<?php echo URL; ?>assets/css/chosen.css" />
    <link rel="stylesheet" href="<?php echo URL; ?>assets/css/datepicker.css" />
		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/daterangepicker.css" />
		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/select2.css" />
		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/dropzone.css" />
		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/jquery-ui-1.10.3.custom.min.css" />
		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/jquery.gritter.css" />
		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/colorbox.css" />
		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/ui.jqgrid.css" />
		<!--fonts-->

		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/ace-fonts.css" />

		<!--ace styles-->
		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/ace.min.css" />
		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/ace-responsive.min.css" />
		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/ace-skins.min.css" />
    <link rel="stylesheet" href="<?php echo URL; ?>assets/js/cropper-master-2/cropper.css" >
		<link rel="stylesheet" href="<?php echo URL; ?>assets/js/plugins/jquery_upload/css/jquery.fileupload.css">
		<link rel="stylesheet" href="<?php echo URL; ?>assets/js/plugins/jquery_upload/css/jquery.fileupload-ui.css">
		<!-- Style of Jquery Light Box -->
		<link rel="stylesheet" href="<?php echo FRONTEND_URL; ?>/plugins/lightbox2-master/dist/css/lightbox.min.css">
		<!-- CSS adjustments for browsers with JavaScript disabled -->
		<noscript><link rel="stylesheet" href="<?php echo URL; ?>assets/js/plugins/jquery_upload/css/jquery.fileupload-noscript.css"></noscript>
		<noscript><link rel="stylesheet" href="<?php echo URL; ?>assets/js/plugins/jquery_upload/css/jquery.fileupload-ui-noscript.css"></noscript>

		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="<?php echo URL; ?>assets/css/ace-ie.min.css" />
		<![endif]-->

		<!--inline styles related to this page-->
        
    <!--Style-->
    <link rel="stylesheet" href="<?php echo URL; ?>assets/css/default.css" />
    <link rel="stylesheet" href="<?php echo URL; ?>assets/css/custom/style.css?v=<?php echo $style_version; ?>" />

		<!--ace settings handler-->

		<script src="<?php echo URL; ?>assets/js/ace-extra.min.js"></script>

		<?php if (isset($main_css_file)): ?>
			<?php if($main_css_file != ""): ?>
				<link rel="stylesheet" href="<?php echo $main_css_file; ?>?v=<?php echo $style_version; ?>" />
			<?php endif;  ?>
		<?php endif ?>

		<?php if (isset($css_plugin_files)): ?>
			<?php foreach ($css_plugin_files as $css_files_key => $css_files_value): ?>
				<link rel="stylesheet" href="<?php echo $css_files_value; ?>?v=<?php echo $style_version; ?>" />
				<!-- <script type="text/javascript" src="<?php echo $css_files_value; ?>?v=<?php echo $script_version; ?>"></script> -->
			<?php endforeach ?>
		<?php endif ?>

		<style>
			.table-form{
				display: table !important;
			}
			.table-important{
				width: 100% !important;
			}
			.list-unstyled{
				list-style: none;
			}
			.list-unstyled li label{
				font-size: 100%;
				width: 35%;
				display: inline-block;
			}
			.no-overflow{
				height: 250px;
				overflow: hidden;
			}
		</style>
	</head>

	
	<body>
		<div class="navbar" id="navbar">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-inner">
				<div class="container-fluid">
					<a href="<?php echo isset($_SESSION['id']) ? URL . "users/edit/{$_SESSION['id']}/" : 'javascript:void(0)' ?>" class="brand">
						<small>
							<i class="icon-cogs"></i>
							<?php echo $_website_name ?> (<?php echo SESSION::get('current_user');?>)
						</small>
					</a><!--/.brand-->

					<ul class="nav ace-nav pull-right">
						<li class="blue">
              <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                  <i class="icon-bell-alt icon-animated-bell"></i>
                  <span class="badge badge-important">8</span>
              </a>

              <ul class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-closer">
                  <li class="nav-header">
                      <i class="icon-warning-sign"></i>
                      8 Notifications
                  </li>

                  <li>
                      <a href="javascript:void(0)">
                          <i class="icon icon-warning-sign green"></i>
                          Available Soon
                      </a>
                  </li>

                  <li>
                      <a href="javascript:void(0)">
                          See all notifications
                          <i class="icon-arrow-right"></i>
                      </a>
                  </li>
              </ul>
          	</li>

						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="<?php echo isset($_SESSION['profile_picture_thumb']) ? $_SESSION['profile_picture_thumb'] : (isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : URL. "assets/avatars/user.jpg") ; ?>" id="profile-picture" alt="Jason's Photo" />
								<span class="user-info">
									<small>Welcome,</small>
									<?php echo  SESSION::get('current_user');?>
								</span>

								<i class="icon-caret-down"></i>
							</a>

							<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
								

								<li>
									<a href="<?php echo URL; ?>logout">
										<i class="icon-off"></i>
										Logout
									</a>
								</li>
							</ul>
						</li>
					</ul><!--/.ace-nav-->
				</div><!--/.container-fluid-->
			</div><!--/.navbar-inner-->
		</div>

		<div class="main-container container-fluid">
			<a class="menu-toggler" id="menu-toggler" href="#">
				<span class="menu-text"></span>
			</a>

			<div class="sidebar" id="sidebar">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<?php require('sidebar.php'); ?>


				<div class="sidebar-collapse" id="sidebar-collapse">
					<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
				</div>

				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>
