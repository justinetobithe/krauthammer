<!DOCTYPE html>
<html lang="en">
	<head>
		<script type="text/javascript" src="<?php echo URL;?>/config_js.php"></script>
		<?php 
			if (isset($_GET['reset'])) {
			    $resetcode = $_GET['reset'];
			}
		?>
		<meta charset="utf-8" />
		<title>Reset Password - Ace Admin</title>

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
			<input type="hidden" id="reset_code" value="<?php echo $resetcode; ?>">
			<div class="main-content">
				<div class="row-fluid">
					<div class="span12">
						<div class="login-container">
							<div class="row-fluid">
								<div class="center">
									<h1>
										
										<span class="white">E-Catalog</span>
										<span class="red">System</span>
									</h1>
									<h4 class="blue">&copy; Company Name</h4>
								</div>
							</div>

							<div class="space-6"></div>

							<div class="row-fluid">
								<div class="position-relative">
									<div id="signup-box" class="signup-box visible widget-box no-border">
										<div class="widget-body">
											<div class="widget-main">
												<h4 class="header green lighter bigger">
											
													Please enter your new password<br />
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
																<input type="password" class="span12" id="txt_password" placeholder="New password" />
																<i class="icon-lock"></i>
															</span>
														</label>

														<label>
															<span class="block input-icon input-icon-right">
																<input type="password"  id="txt_confirm_password" class="span12" placeholder="Confirm new password" />
																<i class="icon-lock"></i>
															</span>
														</label>

														<div class="space"></div>

														<div class="clearfix">
							

															<button id="btn_reset_password" class="width-50 pull-right btn btn-small btn-success " onclick="return false;">
																<i class="icon-key" id = "key_reset"></i>
																<i class="icon-spinner icon-spin orange bigger-125" id="loading_reset"></i>
																Reset password
															</button>

														</div>

														<div class="space-4"></div>	
													</fieldset>
												</form>
                                                
											</div><!--/widget-main-->

											<div class="toolbar clearfix">
												<div>
													<a href="<?php echo URL;?>login/" class="back-to-login-link">									
														<i class="icon-arrow-left"></i>
															Back to login
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
        <script type="text/javascript" src="<?php echo URL; ?>assets/js/controllers/<?php echo getJavascriptByUrl('reset-password'); ?>"></script>
		
	</body>
</html>
