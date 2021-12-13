<li class="dd-item" data-id="1">
	<div class="dd-handle dd2-handle">
		<i class="normal-icon icon-move blue bigger-130"></i>
		<i class="drag-icon icon-move bigger-125"></i>
	</div>

	<div class="dd2-content">
		<div class="accordion">
			<div class="accordion-group">
				<div class="accordion-heading">
					<a href="#collapse-<?php echo $detail->id ?>" data-parent="#accordion_new_2" data-toggle="collapse" class="accordion-toggle collapsed">
						<?php echo $detail->label ?>
					</a>
				</div>

				<div class="accordion-body collapse" id="collapse-<?php echo $detail->id ?>">
					<div class="accordion-inner">
						<div class="group" id="menu-item-<?php echo $detail->id ?>">
							<h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-accordion-header-active ui-state-active ui-corner-top" role="tab" id="ui-accordion-accordion_new-header-0" aria-controls="ui-accordion-accordion_new-panel-0" aria-selected="true" tabindex="0">

								<span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span><?php echo $detail->label ?>

								<input type="hidden" class="post_id" value="<?php echo $detail->id ?>">
								<input type="hidden" class="kind" value="<?php echo $display['kind']; ?>">
								<div class="pull-right action-buttons"> <?php echo $display['kind-label'] ?> </div>

							</h3>

							<textarea name="" id="" cols="30" rows="10"><?php echo $sample_tag; ?></textarea>

							<div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active" id="ui-accordion-accordion_new-panel-0" aria-labelledby="ui-accordion-accordion_new-header-0" role="tabpanel" aria-expanded="true" aria-hidden="false" style="display: block;">
								<div class="control-group">
									<div class="controls">
										<div class="input-group"></div>
									</div>
									<label class="control-label">Text Label:</label>
									<div class="controls">
										<div class="input-group">
											<input type="text" class="input-xlarge link_text" value="About Us">
										</div>
									</div>

									<label class="control-label">Title Attribute:</label>

									<div class="controls">
										<div class="input-group">
											<input type="text" class="input-xlarge" id="url_link">
										</div>
									</div>

									<div class="alert alert-info">
										Original:<a> About Us</a>
										<br>
									</div>

									<br><br>
									
									<p><a href="#" style="color: red;" class="delete_node_a" onclick="delete_menu(89617);"> Remove </a> | <a href="#"> Cancel</a></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</li>
