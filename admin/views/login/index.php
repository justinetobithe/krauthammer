<?php 
$_temp = get_system_option('website_name');
$_t = explode(' ', $_temp);
$_title = array();
for ($i=0; $i < count($_t) ; $i++) { 
	if ($i%2 == 0) {
		$_title[] = '<span class="white">'. $_t[$i] .'</span>';
	}else{
		$_title[] = '<span class="red">'. $_t[$i] .'</span>';
	}
}
$_company = get_system_option('company_name');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<script type="text/javascript" src="<?php echo URL;?>/config_js.php"></script>
		<meta charset="utf-8" />
		<title>Login Page - <?php echo $_temp ?></title>
	
		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!--basic styles-->

		<link href="<?php echo URL; ?>assets/css/bootstrap.min.css" rel="stylesheet" />
		<link href="<?php echo URL; ?>assets/css/bootstrap-responsive.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/font-awesome.min.css" />

		<!--[if IE 7]>
		  <link rel="stylesheet" href="<?php echo URL; ?>assets/css/font-awesome-ie7.min.css" />
		<![endif]-->

		<!--page specific plugin styles-->

		<!--fonts-->

		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/ace-fonts.css" />

		<!--ace styles-->

		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/ace.min.css" />
		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/ace-responsive.min.css" />
		<link rel="stylesheet" href="<?php echo URL; ?>assets/css/ace-skins.min.css" />

		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="<?php echo URL; ?>assets/css/ace-ie.min.css" />
		<![endif]-->

		<!--inline styles related to this page-->

		<!--ace settings handler-->

		<script src="<?php echo URL; ?>assets/js/ace-extra.min.js"></script>
	</head>

	<body class="login-layout">
		<div class="main-container container-fluid">
			<div class="main-content">
				<div class="row-fluid">
					<div class="span12">
						<div class="login-container">
							<div class="row-fluid">
								<div class="center">
									<h1><?php echo implode(' ', $_title) ?></h1>
									<?php if ($_company != ''): ?>
									<h4 class="blue">&copy; <?php echo $_company ?></h4>
									<?php endif ?>
								</div>
							</div>

							<div class="space-6"></div>

							<div class="row-fluid">
								<div class="position-relative">
									<div id="login-box" class="login-box visible widget-box no-border">
										<div class="widget-body">
											<div class="widget-main">
												<h4 class="header blue lighter bigger">
											
													Please Enter Your Information<br />
												</h4>

												<div class="space-6"></div>
                                                
                                                <div id='messageAlert'>
                                                    <?php echo Session::flash('user_logout','warning','You are logged out.',false); ?>
                                                </div>
                                                
                                          
                                                  <br />
												<form>
													<fieldset>
														<label>
															<span class="block input-icon input-icon-right">
																<input type="text" class="span12" id="username" placeholder="Username" />
																<i class="icon-user"></i>
															</span>
														</label>

														<label>
															<span class="block input-icon input-icon-right">
																<input type="password"  id="password" class="span12" placeholder="Password" />
																<i class="icon-lock"></i>
															</span>
														</label>

														<div class="space"></div>

														<div class="clearfix">
															<label class="inline">
																<input type="checkbox" class="ace" />
																<span class="lbl"> Remember Me</span>
															</label>

															<button id="btnLogin" class="width-35 pull-right btn btn-small btn-primary">
																<i class="icon-key" id = "key_login"></i>
																<i class="icon-spinner icon-spin orange bigger-125 hide" id="loading_login"></i>
																Login
															</button>

														</div>

														<div class="space-4"></div>
													</fieldset>
												</form>
                                                
											</div><!--/widget-main-->

											<div class="toolbar clearfix">
												<div>
													<a href="<?php echo URL;?>forgot-password/" class="forgot-password-link">
														<i class="icon-arrow-left"></i>
														I forgot my password
													</a>
												</div>
                                                
											</div>
										</div><!--/widget-body-->
									</div><!--/login-box-->

								
								</div><!--/position-relative-->
							</div>
						</div>
					</div><!--/.span-->
				</div><!--/.row-fluid-->
			</div>
		</div><!--/.main-container-->

		<!--basic scripts-->

		<!--[if !IE]>-->

		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo URL; ?>assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>

		<!--<![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='<?php echo URL; ?>assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='<?php echo URL; ?>assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo URL; ?>assets/js/bootstrap.min.js"></script>

		<!--page specific plugin scripts-->

		<!--ace scripts-->
		
		<script src="<?php echo URL; ?>assets/js/ace-elements.min.js"></script>
		

		
                
        <script type="text/javascript" src="<?php echo URL; ?>assets/js/controllers/functions.js"></script>     
        <script type="text/javascript" src="<?php echo URL; ?>assets/js/controllers/<?php echo getJavascriptByUrl('login'); ?>"></script>
		
	</body>
</html>
