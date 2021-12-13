<!DOCTYPE html>
<html>
<title>CMS INSTALLER</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="installer/src/plugin/bootstrap-4.0.0-beta/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="installer/src/plugin/font-awesome-4.7.0/css/font-awesome.min.css">
<style></style>
<body class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="text-center"> CMS</h1>
			<hr>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" id="form-install">
				<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="pills-database-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-expanded="true">Database</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-expanded="true">Website</a>
					</li>
				</ul>
				<div class="tab-content" id="pills-tabContent">
					<div class="hide">
						<input type="hidden" name="install" value="install">
					</div>
					<div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
						<div class="form-group">
							<label for="web-name">Website Name</label>
							<input type="text" class="form-control" id="web-name" name="web-name" aria-describedby="web-name-help" placeholder="Website Name" required="true">
							<small id="web-name-help" class="form-text text-muted">Enter the name of your website to be created.</small>
						</div>
						<div class="form-group">
							<label for="web-name">Email</label>
							<input type="text" class="form-control" id="web-email" name="web-email" aria-describedby="web-email-help" placeholder="Email Address" required="true">
							<small id="web-email-help" class="form-text text-muted">Enter email address.</small>
						</div>

					</div>
					<div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-database-tab">
						<div class="form-group">
							<label for="dbconfig-db">Database</label>
							<input type="text" class="form-control" id="dbconfig-db" name="dbconfig-db" aria-describedby="dblabel-db" value="pvs_cms_install" placeholder="Enter Database" required="true">
							<small id="dblabel-db" class="form-text text-muted">Enter database name.</small>
						</div>
						<div class="form-group">
							<label for="dbconfig-user">Username</label>
							<input type="text" class="form-control" id="dbconfig-user" name="dbconfig-user" aria-describedby="dblabel-user" value="root" placeholder="Enter Username" required="true">
							<small id="dblabel-user" class="form-text text-muted">Enter username.</small>
						</div>
						<div class="form-group">
							<label for="dbconfig-pass">Password</label>
							<input type="text" class="form-control" id="dbconfig-pass" name="dbconfig-pass" aria-describedby="dblabel-pass" value="" placeholder="Enter Password">
							<small id="dblabel-pass" class="form-text text-muted">Enter password.</small>
						</div>
						<div class="form-group">
							<label for="dbconfig-pass">Host</label>
							<input type="text" class="form-control" id="dbconfig-host" name="dbconfig-host" aria-describedby="dblabel-pass" value="localhost" placeholder="Enter Host Name" required="true">
							<small id="dblabel-pass" class="form-text text-muted">Enter hostname.</small>
						</div>
					</div>
				</div>
			</form>

			<hr>
			<button type="submit" class="btn btn-primary" id="install-button"><i class="fa fa-download"></i> Install</button>
		</div>
	</div>

	<div class="modal" id="loading">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Installing</h5>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer" style="display: none;">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script src="installer/src/js/jquery-1.10.2.min.js"></script>
	<script src="installer/src/js/popper.js"></script>
	<script src="installer/src/plugin/bootstrap-4.0.0-beta/dist/js/bootstrap.min.js"></script>
	<script src="installer/src/js/jquery.form.js"></script>
	<script>
		$(document).ready(function() {
			$("#loading").modal({
				backdrop : 'static',
				keyboard : false,
				show : false,
			});

			var submission_flag = false;
			$("#install-button").click(function(){
				$("#loading .modal-footer").hide();
				$("#loading .modal-body").html('');
				submission_flag = false;

				if (validateFields()) {
					$("#loading").modal('show');
					checkDB(function(){
						submission_flag = true;
						$("#form-install").trigger('submit');
					});
				}
			});

			$("#form-install").ajaxForm({ 
				target:'#divToUpdate', 
				url:'installer/script/install.php', 
				beforeSubmit:function() {
					if (submission_flag != true) {
						return false;
					}
					if (!validateFields()) {
						return false;
					}

					var _temp = $('<p style="display: none;">Setting up your files...</p>');
					$("#loading .modal-body").append(_temp);
					_temp.slideDown();
				},
				success:function(response) { 
				},
				complete: function(xhr) {
					// $("#loading").modal('hide');
					if (xhr.responseText == 'ok') {
						loadingMessageComplete();
						var _temp = $('<p style="display: none;">Waiting to complete database setup...</p>');
						$("#loading .modal-body").append(_temp);
						_temp.slideDown();
						check(function(){
							install_default();
						});
					}else{
						loadingMessageError();
						$("#loading .modal-footer").show();
						$("#loading .modal-body").append('<p class="text-danger" style="display: none;">Unable to extract files, please check you file.</p>');
					}
				},
				error:  function(xhr, desc, err) { 
					$("#loading").modal('hide');
				} 
			});
		});

		function validateFields(){
			if ($("#web-name").val() =='') {
				alert("Enter Website Name");
				$("#pills-home-tab").trigger('click');
				$("#web-name").focus()
				return false;
			}
			if ($("#web-email").val() =='') {
				alert("Enter Email Address");
				$("#pills-home-tab").trigger('click');
				$("#web-email").focus()
				return false;
			}
			if ($("#dbconfig-db").val()=="" || $("#dbconfig-user").val()=="" || $("#dbconfig-host").val() =="") {
				$("#pills-database-tab").trigger('click');
				alert("Enter required information for database configuration");
				return false;
			}

			return true;
		}

		function check(callback){
			$.post('installer/script/install.php',{
				check : true,
				'dbconfig-host'	: $("#dbconfig-host").val(),
				'dbconfig-user'	: $("#dbconfig-user").val(),
				'dbconfig-pass'	: $("#dbconfig-pass").val(),
				'dbconfig-db' 	: $("#dbconfig-db").val(),
				'web-name' 			: $("#web-name").val(),
				'web-email' 		: $("#web-email").val(),
			},function(response) {
				if (response != '1') {
					setTimeout(function(){ check(callback); }, 10000);
				}else{
					loadingMessageComplete();
					if (typeof callback == 'function') {
						callback();
					}
				}
			});
		}

		function checkDB(callback){
			var _temp = $('<p style="display: none;">Checking Database...</p>');
			$("#loading .modal-body").append(_temp);
			_temp.slideDown();

			$.post('installer/script/install.php',{
				'install-check-db'	: true,
				'dbconfig-host'			: $("#dbconfig-host").val(),
				'dbconfig-user'			: $("#dbconfig-user").val(),
				'dbconfig-pass'			: $("#dbconfig-pass").val(),
				'dbconfig-db' 			: $("#dbconfig-db").val(),
			},function(response) {
				if (response == 'ok') {
					loadingMessageComplete();
					if (typeof callback == 'function') {
						callback();
					}
				}else{
					loadingMessageError();
					$("#loading .modal-body").append('<p class="text-danger">Unable to connect to database.</p>');
					$("#loading .modal-footer").show();
				}
			});
		}

		function install_default(){
			var _temp = $('<p style="display: none;">Installing default values...</p>');
			$("#loading .modal-body").append(_temp);
			_temp.slideDown();

			$.post('installer/script/install.php',{
				'install-default' : true,
				'dbconfig-host' 	: $("#dbconfig-host").val(),
				'dbconfig-user' 	: $("#dbconfig-user").val(),
				'dbconfig-pass' 	: $("#dbconfig-pass").val(),
				'dbconfig-db' 		: $("#dbconfig-db").val(),
				'web-name' 				: $("#web-name").val(),
				'web-email' 			: $("#web-email").val(),
			},function(response) {
				if (response == 'ok') {
					loadingMessageComplete();
					var url = (window.location.href).split('://');
					setTimeout(function(){
						window.location = window.location; 
					}, 2000);
				}
			});
		}

		function loadingMessageComplete(){
			$("#loading .modal-body p:last-child").append('<i class="fa fa-check"></i>');
			$("#loading .modal-body p:last-child").addClass('text-success');
		}
		function loadingMessageError(){
			$("#loading .modal-body p:last-child").append('<i class="fa fa-exclamation-circle"></i>');
			$("#loading .modal-body p:last-child").addClass('text-danger');
		}
	</script>
</body>
</html>