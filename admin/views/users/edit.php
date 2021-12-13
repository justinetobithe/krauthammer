<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Edit User      
			</h1>
		</div><!-- /.page-header -->
		<input type="hidden" id="action" value="edit_user">
		<input type="hidden" id="hdn_role" value="<?php echo $user['user_role'];?>">

		<div class="row-fluid">
			<form class="form-horizontal span12" id="edit_user_form" action="<?php echo URL;?>users/updateUser" enctype="multipart/form-data" method="post" onsubmit="return validateFormUser()">
				<input type="hidden" name="user_id" value="<?php echo $user['id'];?>">
				<input type="hidden" id="user_username" value="<?php echo $user['username'];?>">
				<input type="hidden" id="user_email" value="<?php echo $user['user_email'];?>">
				<div class="widget-box">
					<div class="widget-header header-color-blue">
						<h5 class="bigger lighter">
							User Details
						</h5>
					</div>

					<div class="widget-body">
						<div class="widget-main">
							<div id="alertUser"></div>
							<div class="row-fluid">
								<div class="span8">
									<div class="control-group">
										<label class="control-label" for="role"><span class="required">*</span> User Role</label>
										<div class="controls">
											<div class="span12">
												<select class="input-medium" name="role" id="role">
													<option value="none">Select Role</option>
													<?php foreach ($roles as $key => $value): ?>
													<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="username"><span class="required">*</span> Username:</label>
										<div class="controls">
											<input type="text" id="username" name="username" class="input span12 req" value="<?php echo $user['username']; ?>">           
										</div>
									</div>
									<hr>
									<div class="control-group" id="password-container-toggle-container">
										<label class="control-label" for="password"></label>
										<div class="controls">
											<a href="javascript:void(0)" id="password-container-toggle-on">Edit Password</a>
											<a href="javascript:void(0)" id="password-container-toggle-off" style="display: none;">Cancel Edit Password</a>
										</div>
									</div>
									<div id="password-container" style="display: none; ">
										<div class="control-group">
											<label class="control-label" for="password"><span class="required">*</span> Password:</label>
											<div class="controls">
												<input type="password" id="password" name="password" class="input span12 req" value="password!">           
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="con_password"><span class="required">*</span> Confirm Password:</label>
											<div class="controls">
												<input type="password" id="con_password"  class="input span12 req"  value="password!" >           
											</div>
										</div>
									</div>
									<hr>
									<div class="control-group">
										<label class="control-label" for="full_name"><span class="required">*</span> Full Name:</label>
										<div class="controls">
											<input type="text" id="full_name" name="full_name" class="input span12 req" value="<?php echo $user['user_fullname']; ?>" >           
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="email"> <span class="required">*</span> Email:</label>
										<div class="controls">
											<input type="text" id="email" name="email" class="input span12 req" value="<?php echo $user['user_email']; ?>" >           
										</div>
									</div>
								</div>
								<div class="span4">
									<div class="control-group">
										<div class="span12 text-center">
											<img src="<?php echo $user['user_profpic']!=""? $user['user_profpic'] : trim(FRONTEND_URL) . '/images/uploads/default.jpg' ?>" alt="<?php echo $user['user_profpic']!=""? $user['user_profpic'] : trim(FRONTEND_URL) . '/images/uploads/default.jpg' ?>" id="user-profile-pic-preview" style="max-width: 100%; max-height: 200px;">
										</div>
									</div>
									<hr>
									<div class="control-group">
										<div class="span12">
											Profile Picture
											<input type="file" id="user-profile-picture" name="user-profile-picture" />
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div class="row-fluid">
								<div class="span12">
									<div class="control-group">
										<div class="controls">
											<input type="submit" value="Save" id="save_orders" class="btn btn-success">
											&nbsp;
											<button class="btn" id="btn_reset" onclick="closeModal(); return false;">Cancel</button>
										</div>
									</div>   
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div><!--PAGE Row END-->
	</div><!--PAGE CONTENT END-->
</div><!--MAIN CONTENT END-->
<!--MODAL-->
<div id="loading" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Wait for a moment updating User
				</div>
			</div>
			<div class="modal-body">
				<div class="center">
					<i id="product_loading" class="icon-spinner icon-spin blue bigger-300 hide"></i>
				</div>
				<div id="loading_msg_success" class="center hide">
					<h4 class="green">User Successfully Updated</h4>
				</div>
				<div id="loading_msg_error" class="center hide">
					<h4 class="red">User Unsuccessfully Updated</h4>
				</div>
			</div>
			<div class="modal-footer no-margin-top hide" id="modal_footer">
				<button class="btn btn-sm pull-left btn-danger hide" id="close_button" data-dismiss="modal">
					<i class="icon-remove"></i>
					Close
				</button>
				<button class="btn btn-sm pull-right btn-success hide" id="continue_button" data-dismiss="modal">
					<i class="icon-check"></i>
					Continue
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
